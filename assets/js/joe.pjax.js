/*
 * Joe 主题 Pjax 无刷新加载
 *
 * 站内跳转时仅替换 #Joe 容器并同步 head 资源，音乐播放器（QPlayer2 等挂载在
 * body 上、位于 #Joe 之外的挂件）不会被打断。
 *
 * 配合后台「是否开启 Pjax 无刷新加载」开关（JPjax）由 public/include.php 按需引入。
 * 如需某个链接/表单强制整页刷新，给元素（或其祖先）加上 data-no-pjax 属性即可。
 *
 * 页面脚本约定：
 *   1. 外部脚本按 src 去重，已加载过的不会重复加载（QPlayer、jQuery 等）；
 *   2. head 与 #Joe 容器内的内联脚本每次导航都会重新执行（评论配置、Qmsg 通知等）；
 *   3. 换页完成后派发合成 DOMContentLoaded，主题各 min.js 的初始化随之重跑；
 *   4. 需要额外重新初始化的脚本可调用 JoePjax.onLoad(fn) 注册钩子。
 */
(function () {
	'use strict';
	if (window.JoePjax) return;

	var CONTAINER = '#Joe';
	var currentUrl = location.href;
	var loading = false;
	var loadingSince = 0;
	var collected = false;
	var loadHooks = [];
	var loadedSrcs = {};
	var loadedCss = {};
	var baseStyles = null;

	/* 守卫 customElements.define：合成 DOMContentLoaded 等场景下重复定义直接跳过，避免抛异常 */
	if (window.customElements && customElements.define) {
		var _define = customElements.define.bind(customElements);
		customElements.define = function (name, ctor, opts) {
			if (customElements.get(name)) return;
			return _define(name, ctor, opts);
		};
	}

	function absUrl(u) {
		try { return new URL(u, document.baseURI).href; } catch (e) { return u; }
	}

	function stripHash(u) {
		return String(u).replace(/#.*$/, '');
	}

	/* 首次导航前收集当前页已加载的脚本与样式，作为去重基准 */
	function ensureCollected() {
		if (collected) return;
		collected = true;
		var i, list;
		list = document.querySelectorAll('script[src]');
		for (i = 0; i < list.length; i++) loadedSrcs[absUrl(list[i].getAttribute('src'))] = 1;
		list = document.querySelectorAll('link[rel="stylesheet"]');
		for (i = 0; i < list.length; i++) loadedCss[absUrl(list[i].getAttribute('href'))] = 1;
		baseStyles = {};
		list = document.head.querySelectorAll('style');
		for (i = 0; i < list.length; i++) baseStyles[list[i].textContent.trim()] = 1;
	}

	/* ---------- 顶部加载进度条 ---------- */
	var progressEl = null;
	var progressTimer = null;
	function progressStart() {
		progressDone(true);
		progressEl = document.createElement('div');
		progressEl.style.cssText = 'position:fixed;top:0;left:0;height:2px;width:0;background:var(--theme,#409eff);z-index:99999;transition:width .3s ease;pointer-events:none;';
		document.body.appendChild(progressEl);
		var w = 0;
		progressTimer = setInterval(function () {
			w = Math.min(w + Math.random() * 12, 90);
			if (progressEl) progressEl.style.width = w + '%';
		}, 200);
	}
	function progressDone(immediate) {
		if (progressTimer) { clearInterval(progressTimer); progressTimer = null; }
		var el = progressEl;
		progressEl = null;
		if (!el) return;
		if (immediate) { el.remove(); return; }
		el.style.width = '100%';
		setTimeout(function () {
			el.style.opacity = '0';
			setTimeout(function () { el.remove(); }, 300);
		}, 150);
	}

	/* ---------- 链接过滤 ---------- */
	var EXT_RE = /\.(jpe?g|png|gif|webp|svg|ico|css|js|json|xml|txt|zip|rar|7z|gz|mp3|mp4|m4a|aac|flac|ts|m3u8|pdf|docx?|xlsx?|pptx?|apk|exe)([?#]|$)/i;

	/* 文章内容里存在指向本站旧域名/裸域名的绝对链接（如 https://www.xggm.top/...），
	   以及 http/https 协议不一致的同域链接，跨域判断会放行导致整页跳转，
	   这里统一归一化为当前域内链接 */
	var ALIAS_HOST_RE = /^(www\.)?xggm\.top$/i;
	function normalizeAlias(u) {
		if (u.origin !== location.origin && (u.hostname === location.hostname || ALIAS_HOST_RE.test(u.hostname))) {
			try { return new URL(u.pathname + u.search + u.hash, location.origin); } catch (e) { return u; }
		}
		return u;
	}
	function isExcluded(u) {
		var p = u.pathname;
		if (EXT_RE.test(p)) return true;
		/* 后台、安装目录、独立音乐播放器、Typecho action（登录/登出等） */
		if (/\/(admin|xggm|install|music)(\/|$)/i.test(p)) return true;
		if (/\/action\//i.test(p) || /[?&]do=/i.test(u.search)) return true;
		if (/(feed|sitemap)/i.test(p)) return true;
		return false;
	}

	/* ---------- 资源同步 ---------- */
	function syncMeta(doc) {
		document.title = doc.title;
		var names = ['keywords', 'description'];
		for (var i = 0; i < names.length; i++) {
			var neu = doc.head.querySelector('meta[name="' + names[i] + '"]');
			var cur = document.head.querySelector('meta[name="' + names[i] + '"]');
			if (neu && cur) cur.setAttribute('content', neu.getAttribute('content') || '');
		}
	}

	/* 新页面缺失的样式表按序补载（等待加载完成，避免样式闪烁） */
	function syncHeadCss(doc) {
		var promises = [];
		var links = doc.head.querySelectorAll('link[rel="stylesheet"]');
		for (var i = 0; i < links.length; i++) {
			var href = links[i].getAttribute('href');
			if (!href) continue;
			var abs = absUrl(href);
			if (loadedCss[abs]) continue;
			loadedCss[abs] = 1;
			var n = document.createElement('link');
			n.rel = 'stylesheet';
			n.href = href;
			promises.push(new Promise(function (res) {
				n.onload = n.onerror = res;
				setTimeout(res, 3000);
			}));
			document.head.appendChild(n);
		}
		return Promise.all(promises);
	}

	/* 页面级内联 style（如留言板）：上一页的移除，新页的加上并打标 */
	function syncHeadStyles(doc) {
		var i, list = document.head.querySelectorAll('style[data-joe-pjax]');
		for (i = 0; i < list.length; i++) list[i].remove();
		list = doc.head.querySelectorAll('style');
		for (i = 0; i < list.length; i++) {
			var t = list[i].textContent.trim();
			if (!t || baseStyles[t]) continue;
			var n = document.createElement('style');
			n.setAttribute('data-joe-pjax', '');
			n.textContent = list[i].textContent;
			document.head.appendChild(n);
		}
	}

	/* ---------- 脚本处理 ---------- */
	function isJsType(type) {
		return !type || /(text|application)\/(java|ecma)script|module/i.test(type);
	}

	/* 逐个执行脚本：外部按 src 去重并等待加载，内联原位重建立即执行 */
	function execScript(old, fallbackParent) {
		return new Promise(function (resolve) {
			var type = old.getAttribute('type');
			if (!isJsType(type)) return resolve();
			var src = old.getAttribute('src');
			/* QPlayer2 等音乐播放器的内联配置只需执行一次：
			   重复 setList 会触发 list setter 的 pause()+load()，直接打断当前播放 */
			if (!src && /QPlayer/.test(old.text) && window.QPlayer && window.QPlayer.plugin) return resolve();
			var s = document.createElement('script');
			if (type) s.type = type;
			/* 注意：DOMParser 虚拟文档里的节点 isConnected 也为 true，
			   必须确认属于当前 document 才能原位替换，否则脚本会被插进虚拟文档永不执行
			   （head 内联脚本如 Typecho 反垃圾令牌不执行会导致第二条评论提交失败） */
			var inDoc = old.ownerDocument === document && old.parentNode && old.parentNode.isConnected;
			if (src) {
				var abs = absUrl(src);
				if (loadedSrcs[abs]) return resolve();
				loadedSrcs[abs] = 1;
				/* CDN 不可达时 onerror 可能要数十秒才触发，超时兜底避免导航被卡死 */
				var done = false;
				var finish = function () { if (!done) { done = true; resolve(); } };
				s.onload = s.onerror = finish;
				setTimeout(finish, 4000);
				s.src = src;
				(inDoc ? old.parentNode : fallbackParent).appendChild(s);
			} else {
				s.text = old.text;
				if (inDoc) {
					old.parentNode.replaceChild(s, old);
				} else {
					fallbackParent.appendChild(s);
					s.remove();
				}
				resolve();
			}
		});
	}

	function runScriptsInOrder(scripts, fallbackParent) {
		var p = Promise.resolve();
		scripts.forEach(function (old) {
			p = p.then(function () { return execScript(old, fallbackParent); });
		});
		return p;
	}

	/* ---------- 滚动处理 ---------- */
	function cssEscape(s) {
		return (window.CSS && CSS.escape) ? CSS.escape(s) : s.replace(/([^\w-])/g, '\\$1');
	}

	function scrollToTarget(name) {
		var el = document.getElementById(name) || document.querySelector('.' + cssEscape(name));
		if (!el) return;
		var header = document.querySelector('.joe_header');
		var top = el.getBoundingClientRect().top + window.pageYOffset - (header ? header.offsetHeight : 0) - 15;
		window.scrollTo({ top: top, behavior: 'smooth' });
	}

	function handleScroll(url, opts) {
		if (opts.keepScroll) return;
		if (typeof opts.scrollTo === 'number') { window.scrollTo(0, opts.scrollTo); return; }
		var u;
		try { u = new URL(url, location.href); } catch (e) { return; }
		var scroll = u.searchParams.get('scroll');
		if (scroll) {
			/* 图片懒加载可能改变布局，延时两次校正（对齐 joe.post_page 的 ?scroll= 逻辑） */
			setTimeout(function () { scrollToTarget(scroll); }, 100);
			setTimeout(function () { scrollToTarget(scroll); }, 700);
			return;
		}
		if (u.hash) {
			var el = document.getElementById(u.hash.slice(1));
			if (el) { el.scrollIntoView(); return; }
		}
		window.scrollTo(0, 0);
	}

	/* ---------- 核心渲染 ---------- */
	function render(doc, url, opts) {
		var newContainer = doc.querySelector(CONTAINER);
		var container = document.querySelector(CONTAINER);
		/* 目标页面没有 #Joe 容器（如独立应用页），整页跳转兜底 */
		if (!newContainer || !container) { location.href = url; return Promise.resolve(false); }

		syncMeta(doc);
		syncHeadStyles(doc);
		return syncHeadCss(doc).then(function () {
			/* 替换容器（importNode 的 script 不会执行，随后统一按序处理） */
			var imported = document.importNode(newContainer, true);
			container.parentNode.replaceChild(imported, container);

			/* head 脚本：缺失的外部脚本补载，内联脚本重执行（window.Joe 配置等） */
			var headScripts = Array.prototype.slice.call(doc.head.querySelectorAll('script'));
			/* 容器脚本：footer 内联逻辑、评论配置、QPlayer 配置等 */
			var bodyScripts = Array.prototype.slice.call(imported.querySelectorAll('script'));
			return runScriptsInOrder(headScripts.concat(bodyScripts), document.head);
		}).then(function () { return true; });
	}

	function afterLoad(opts) {
		/* 重触发主题各 min.js 中持久的 DOMContentLoaded 初始化 */
		document.dispatchEvent(new Event('DOMContentLoaded', { bubbles: true, cancelable: false }));
		loadHooks.forEach(function (fn) {
			try { fn(); } catch (err) { console.error('[JoePjax] onLoad hook error:', err); }
		});
		handleScroll(currentUrl, opts);
		window.dispatchEvent(new Event('joe:pjax:complete'));
	}

	function load(url, opts) {
		opts = opts || {};
		if (loading) {
			/* 卡死保护：上次导航超 10 秒未完成时再点击，直接整页跳转兜底 */
			if (Date.now() - loadingSince > 10000) location.href = url;
			return;
		}
		loading = true;
		loadingSince = Date.now();
		ensureCollected();
		progressStart();

		var ctrl = ('AbortController' in window) ? new AbortController() : null;
		var timer = ctrl ? setTimeout(function () { ctrl.abort(); }, 20000) : null;
		var fopts = { credentials: 'same-origin' };
		if (ctrl) fopts.signal = ctrl.signal;
		if (opts.method === 'POST') { fopts.method = 'POST'; fopts.body = opts.body; }

		fetch(url, fopts).then(function (res) {
			/* 伪静态格式链接（无 /index.php 前缀）在未开启重写的环境下 404，补前缀重试一次 */
			if (res.status === 404) {
				var pu;
				try { pu = new URL(url, location.href); } catch (e) { pu = null; }
				if (pu && !/^\/index\.php(\/|$)/.test(pu.pathname)) {
					return fetch(pu.origin + '/index.php' + pu.pathname + pu.search, fopts).then(function (r2) {
						return r2.ok ? r2 : res;
					}, function () { return res; });
				}
			}
			return res;
		}).then(function (res) {
			if (!res.ok) throw new Error('HTTP ' + res.status);
			var finalUrl = res.url || url;
			return res.text().then(function (html) { return { html: html, url: finalUrl }; });
		}).then(function (r) {
			var doc = new DOMParser().parseFromString(r.html, 'text/html');
			return render(doc, r.url, opts).then(function (ok) {
				if (ok === false) return false;
				if (opts.push !== false) {
					/* 记录当前位置，便于返回时恢复 */
					history.replaceState({ joePjax: 1, scroll: window.pageYOffset }, '', currentUrl);
					history.pushState({ joePjax: 1 }, '', r.url);
				} else if (opts.replace) {
					history.replaceState({ joePjax: 1 }, '', r.url);
				}
				currentUrl = r.url;
				afterLoad(opts);
				return true;
			});
		}).then(function (ok) {
			if (timer) clearTimeout(timer);
			loading = false;
			progressDone(ok === false);
		}).catch(function () {
			/* 任意失败整页跳转兜底，保证可达性 */
			if (timer) clearTimeout(timer);
			loading = false;
			progressDone(true);
			location.href = url;
		});
	}

	/* ---------- 事件拦截 ---------- */
	document.addEventListener('click', function (e) {
		if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
		if (!e.target || !e.target.closest) return;
		var a = e.target.closest('a[href]');
		if (!a || a.closest('[data-no-pjax]')) return;
		if (a.target && a.target !== '_self') return;
		if (a.hasAttribute('download') || /(^|\s)external(\s|$)/i.test(a.rel || '')) return;
		var href = a.href;
		if (!/^https?:/i.test(href)) return;
		var u;
		try { u = new URL(href); } catch (err) { return; }
		u = normalizeAlias(u);
		if (u.origin !== location.origin) return;
		if (isExcluded(u)) return;
		/* 同页锚点跳转交给浏览器 */
		if (u.pathname === location.pathname && u.search === location.search && u.hash) return;
		e.preventDefault();
		load(u.href, {});
	});

	/* 拦截搜索表单（含 input[name=s]），POST 跟随重定向后以最终地址入栈 */
	document.addEventListener('submit', function (e) {
		if (e.defaultPrevented) return;
		var form = e.target;
		if (!form || !form.querySelector || form.closest('[data-no-pjax]')) return;
		if (!form.querySelector('input[name="s"]')) return;
		var action = form.getAttribute('action') || location.href;
		var u;
		try { u = new URL(action, location.href); } catch (err) { return; }
		if (u.origin !== location.origin) return;
		e.preventDefault();
		if ((form.method || 'get').toUpperCase() === 'POST') {
			load(u.href, { method: 'POST', body: new FormData(form) });
		} else {
			new FormData(form).forEach(function (v, k) { u.searchParams.set(k, v); });
			load(u.href, {});
		}
	});

	window.addEventListener('popstate', function (e) {
		/* 仅 hash 变化不走 Pjax */
		if (stripHash(location.href) === stripHash(currentUrl)) { currentUrl = location.href; return; }
		load(location.href, {
			push: false,
			scrollTo: (e.state && typeof e.state.scroll === 'number') ? e.state.scroll : 0
		});
	});

	if ('scrollRestoration' in history) history.scrollRestoration = 'manual';
	history.replaceState({ joePjax: 1 }, '', location.href);

	/* ---------- 对外 API ---------- */
	window.JoePjax = {
		enabled: true,
		/* 注册每次 Pjax 换页完成后的回调（首次进站仍由各脚本自身的 ready 执行） */
		onLoad: function (fn) {
			if (typeof fn === 'function') loadHooks.push(fn);
		},
		/* 局部刷新当前页（评论提交成功后调用），保持滚动位置 */
		reload: function (opts) {
			var o = { push: false, replace: true, keepScroll: true };
			if (opts) for (var k in opts) o[k] = opts[k];
			load(location.href, o);
		},
		/* 手动跳转 */
		visit: function (url) { load(url, {}); }
	};
})();
