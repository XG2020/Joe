const parser = new HyperDown();
const player = window.JoeConfig.playerAPI;
const compatLazyLoad = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
const compatCardNavBackgrounds = [
	'linear-gradient(to right, #6DE195, #C4E759)',
	'linear-gradient(to right, #41C7AF, #54E38E)',
	'linear-gradient(to right, #99E5A2, #D4FC78)',
	'linear-gradient(to right, #ABC7FF, #C1E3FF)',
	'linear-gradient(to right, #6CACFF, #8DEBFF)',
	'linear-gradient(to right, #5583EE, #41D8DD)',
	'linear-gradient(to right, #D279EE, #F8C390)',
	'linear-gradient(to right, #F78FAD, #FDEB82)',
	'linear-gradient(to right, #A43AB2, #E13680)'
];
let compatEventsBound = false;

function escapeRegExp(str) {
	return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

function compatNormalizeParagraph(str, shortcode) {
	const open = escapeRegExp(shortcode);
	const close = shortcode.replace('[', '[/');
	return str.replace(new RegExp(`<p>${open}([\\s\\S]*?)${escapeRegExp(close)}<\\/p>`, 'gi'), `${shortcode}$1${close}`);
}

function compatStripBreaks(str, open, close) {
	return str.replace(new RegExp(`${escapeRegExp(open)}([\\s\\S]*?)${escapeRegExp(close)}`, 'gi'), match => match.replace(/<br.*?>/gi, ''));
}

function parseCompatShortcodes(str) {
	if (str.indexOf('[') === -1) return str;

	str = str.replace(/<p>\[hide\]([\s\S]*?)\[\/hide\]<\/p>/gi, '<joe-hide></joe-hide>');
	str = str.replace(/\[hide\]([\s\S]*?)\[\/hide\]/gi, '<joe-hide></joe-hide>');

	str = compatNormalizeParagraph(str, '[photo]');
	str = compatStripBreaks(str, '[photo]', '[/photo]');
	str = str.replace(/\[photo\]([\s\S]*?)\[\/photo\]/gi, '<div class="j-photos">$1</div>');

	str = str.replace(/\[tag type="(.*?)".*?\]([\s\S]*?)\[\/tag\]/gi, '<span class="j-tag $1">$2</span>');
	str = str.replace(/\[btn href="(.*?)" type="(.*?)".*?\]([\s\S]*?)\[\/btn\]/gi, '<a href="$1" class="j-btn $2">$3</a>');

	str = str.replace(/<p>\[alt type="(.*?)".*?\]([\s\S]*?)\[\/alt\]<\/p>/gi, '[alt type="$1"]$2[/alt]');
	str = str.replace(/\[alt type="(.*?)".*?\]([\s\S]*?)\[\/alt\]/gi, '<div class="j-alt $1">$2</div>');

	str = compatNormalizeParagraph(str, '[line]');
	str = str.replace(/\[line\]([\s\S]*?)\[\/line\]/gi, '<div class="j-line"><span>$1</span></div>');

	str = compatNormalizeParagraph(str, '[tabs]');
	str = compatStripBreaks(str, '[tabs]', '[/tabs]');
	str = str.replace(/\[tabs\]([\s\S]*?)\[\/tabs\]/gi, (_, inner) => {
		const labelMatches = [...inner.matchAll(/label="(.*?)"\]/gi)];
		const paneMatches = [...inner.matchAll(/\[tab-pane label=".*?"\]([\s\S]*?)\[\/tab-pane\]/gi)];
		const tabs = labelMatches
			.map((item, index) => `<span class="${index === 0 ? 'active' : ''}" data-panel="${index}">${item[1]}</span>`)
			.join('');
		const panes = paneMatches
			.map((item, index) => `<div class="${index === 0 ? 'active' : ''}" data-panel="${index}">${item[1]}</div>`)
			.join('');
		return `<div class="j-tabs"><div class="nav">${tabs}</div><div class="content">${panes}</div></div>`;
	});

	str = str.replace(/<p>\[card-default width="(.*?)" label="(.*?)".*?\]([\s\S]*?)\[\/card-default\]<\/p>/gi, '[card-default width="$1" label="$2"]$3[/card-default]');
	str = str.replace(
		/\[card-default width="(.*?)" label="(.*?)".*?\]([\s\S]*?)\[\/card-default\]/gi,
		'<div class="j-card-default" style="width: $1"><div class="head">$2</div><div class="content">$3</div></div>'
	);

	str = compatNormalizeParagraph(str, '[collapse]');
	str = compatStripBreaks(str, '[collapse]', '[/collapse]');
	str = str.replace(/\[collapse\]([\s\S]*?)\[\/collapse\]/gi, '<div class="j-collapse">$1</div>');
	str = str.replace(/<p>\[collapse-item label="(.*?)".*?\]([\s\S]*?)\[\/collapse-item\]<\/p>/gi, '[collapse-item label="$1"]$2[/collapse-item]');
	str = str.replace(
		/\[collapse-item label="(.*?)".*?\]([\s\S]*?)\[\/collapse-item\]/gi,
		'<div class="collapse-head"><span>$1</span><svg viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M21.6 772.8c28.8 28.8 74.4 28.8 103.2 0L512 385.6 899.2 772.8c28.8 28.8 74.4 28.8 103.2 0 28.8-28.8 28.8-74.4 0-103.2l-387.2-387.2-77.6-77.6c-14.4-14.4-37.6-14.4-51.2 0l-77.6 77.6-387.2 387.2c-28.8 28.8-28.8 75.2 0 103.2z"></path></svg></div><div class="collapse-body">$2</div>'
	);

	str = compatNormalizeParagraph(str, '[timeline]');
	str = compatStripBreaks(str, '[timeline]', '[/timeline]');
	str = str.replace(/\[timeline\]([\s\S]*?)\[\/timeline\]/gi, '<div class="j-timeline">$1</div>');
	str = str.replace(/<p>\[timeline-item\]([\s\S]*?)\[\/timeline-item\]<\/p>/gi, '[timeline-item]$1[/timeline-item]');
	str = str.replace(/\[timeline-item\]([\s\S]*?)\[\/timeline-item\]/gi, '<div class="item">$1</div>');

	str = str.replace(/\[copy\]([\s\S]*?)\[\/copy\]/gi, '<span class="j-copy" data-copy="$1">$1</span>');
	str = str.replace(/\[typing\]([\s\S]*?)\[\/typing\]/gi, '<span class="j-typing">$1</span>');

	str = compatNormalizeParagraph(str, '[card-nav]');
	str = compatStripBreaks(str, '[card-nav]', '[/card-nav]');
	str = str.replace(/\[card-nav\]([\s\S]*?)\[\/card-nav\]/gi, '<div class="j-card-nav">$1</div>');
	str = str.replace(/\[card-nav-item src="(.*?)" title="(.*?)" img="(.*?)".*?\/\]/gi, (_, src, title, img) => {
		const icon = img === 'auto' ? `${src}/favicon.ico` : img;
		const background = compatCardNavBackgrounds[Math.floor(Math.random() * compatCardNavBackgrounds.length)];
		return `<div class="item"><a href="${src}" class="nav" style="background-image: ${background}"><span class="avatar" style="background-image: url(${icon})"></span><span class="content">${title}</span><svg viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M428.8 928h-60.8v-282.24l280.96-284.16 46.08 44.8-263.04 265.6v164.48l137.6-131.2 174.08 80 98.56-568.32-604.8 334.72 96 44.16-26.88 58.24L96 556.8l832-460.8-135.68 782.72-209.92-97.28z" p-id="5322"></path></svg></a></div>`;
	});

	str = str.replace(/<p>\[dplayer src="(.*?)".*?\/\]<\/p>/gi, '[dplayer src="$1" /]');
	str = str.replace(/\[dplayer src="(.*?)".*?\/\]/gi, `<iframe scrolling="no" allowfullscreen="allowfullscreen" frameborder="0" width="100%" class="iframe-dplayer" src="${player}$1"></iframe>`);

	str = str.replace(/<p>\[plyr src="(.*?)".*?\/\]<\/p>/gi, '[plyr src="$1" /]');
	str = str.replace(/\[plyr src="(.*?)".*?\/\]/gi, `<iframe scrolling="no" allowfullscreen="allowfullscreen" frameborder="0" width="100%" class="iframe-dplayer" src="${player}$1"></iframe>`);

	str = str.replace(/<p>\[music id="(.*?)".*?\/\]<\/p>/gi, '[music id="$1" /]');
	str = str.replace(/\[music id="(.*?)".*?\/\]/gi, '<iframe class="iframe-music" frameborder="no" border="0" width="330" height="86" src="//music.163.com/outchain/player?type=2&id=$1&auto=1&height=66"></iframe>');

	str = str.replace(/<p>\[music-list id="(.*?)".*?\/\]<\/p>/gi, '[music-list id="$1" /]');
	str = str.replace(/\[music-list id="(.*?)".*?\/\]/gi, '<iframe class="iframe-music" frameborder="no" border="0" width="330" height="450" src="//music.163.com/outchain/player?type=0&id=$1&auto=1&height=430"></iframe>');

	str = str.replace(/<p>\[video\]([\s\S]*?)\[\/video\]<\/p>/gi, '[video]$1[/video]');
	str = compatStripBreaks(str, '[video]', '[/video]');
	str = str.replace(/\[video\]([\s\S]*?)\[\/video\]/gi, '<div class="j-short-video">$1</div>');
	str = str.replace(
		/\[video-item src="(.*?)" poster="(.*?)".*?\/\]/gi,
		`<div class="item"><div class="inner" data-poster="$2" data-src="$1" style="background-image: url(${compatLazyLoad})"><svg t="1607510948740" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="19996" width="80" height="80"><path d="M512 65c247.424 0 448 200.576 448 448S759.424 961 512 961 64 760.424 64 513 264.576 65 512 65z m0 64c-212.077 0-384 171.923-384 384s171.923 384 384 384 384-171.923 384-384-171.923-384-384-384z m-63 214.657a64 64 0 0 1 33.593 9.525L655.857 460.03c30.086 18.552 39.435 57.982 20.882 88.067a64 64 0 0 1-21.324 21.152L482.151 674.17c-30.235 18.308-69.587 8.64-87.896-21.594A64 64 0 0 1 385 619.425V407.657c0-35.346 28.654-64 64-64z m1.196 74.49a8 8 0 0 0-1.196 4.207v183.432a8 8 0 0 0 12.15 6.84l149.688-90.851a8 8 0 0 0 0.057-13.643L461.208 415.55a8 8 0 0 0-11.012 2.595z" p-id="19997"></path></svg></div></div>`
	);

	return str;
}

function bindCompatPreviewEvents() {
	if (compatEventsBound) return;
	compatEventsBound = true;

	$(document).on('click', '.cm-preview-content .j-copy', function () {
		const text = $(this).attr('data-copy') || $(this).text();
		const success = () => typeof Qmsg !== 'undefined' && Qmsg.success('复制成功！');
		if (navigator.clipboard && navigator.clipboard.writeText) {
			navigator.clipboard.writeText(text).then(success);
			return;
		}
		const textarea = $('<textarea>').val(text).css({ position: 'fixed', left: '-9999px', top: '0' }).appendTo('body');
		textarea[0].select();
		try {
			document.execCommand('copy');
			success();
		} finally {
			textarea.remove();
		}
	});

	$(document).on('click', '.cm-preview-content .j-tabs .nav span', function () {
		const item = $(this);
		const panel = item.attr('data-panel');
		const tabs = item.closest('.j-tabs');
		tabs.find('.nav span').removeClass('active');
		item.addClass('active');
		tabs.find('.content > div').removeClass('active');
		tabs.find(`.content > div[data-panel="${panel}"]`).addClass('active');
	});

	$(document).on('click', '.cm-preview-content .j-collapse .collapse-head', function () {
		const head = $(this);
		const body = head.next('.collapse-body');
		const collapse = head.closest('.j-collapse');
		const opened = body.is(':visible');
		collapse.find('.collapse-body').slideUp(200);
		collapse.find('.collapse-head svg').css('transform', 'rotate(180deg)');
		if (!opened) {
			body.slideDown(200);
			head.find('svg').css('transform', 'rotate(0deg)');
		}
	});

	$(document).on('click', '.cm-preview-content .j-short-video .inner', function () {
		const inner = $(this);
		const src = inner.attr('data-src');
		if (!src) return;
		inner.closest('.item').html(`<iframe scrolling="no" allowfullscreen="allowfullscreen" frameborder="0" width="100%" class="iframe-dplayer" src="${player}${src}"></iframe>`);
	});
}

function initCompatPreviewTyping() {
	$('.cm-preview-content .j-typing').each(function () {
		const el = $(this);
		const text = el.text();
		let index = 0;
		el.text('');
		const timer = setInterval(() => {
			if (index > text.length) {
				clearInterval(timer);
				el.text(text);
				return;
			}
			el.text(text.slice(0, index) + '_');
			index += 1;
		}, 100);
	});
}

export default function createPreviewHtml(str) {
	if (!window.JoeConfig.canPreview) return $('.cm-preview-content').html('1. 预览已默认关闭<br>2. 点击上方预览按钮启用预览<br>3. 若编辑器卡顿可尝试关闭预览');

	if (str.indexOf('　') !== -1) {
		str = str.replace(/　/g, '&emsp;');
	}

	/* 生成html */
	str = parser.makeHtml(str);
	str = parseCompatShortcodes(str);

	str = str.replace(/\:\:\(\s*(呵呵|哈哈|吐舌|太开心|笑眼|花心|小乖|乖|捂嘴笑|滑稽|你懂的|不高兴|怒|汗|黑线|泪|真棒|喷|惊哭|阴险|鄙视|酷|啊|狂汗|what|疑问|酸爽|呀咩爹|委屈|惊讶|睡觉|笑尿|挖鼻|吐|犀利|小红脸|懒得理|勉强|爱心|心碎|玫瑰|礼物|彩虹|太阳|星星月亮|钱币|茶杯|蛋糕|大拇指|胜利|haha|OK|沙发|手纸|香蕉|便便|药丸|红领巾|蜡烛|音乐|灯泡|开心|钱|咦|呼|冷|生气|弱|吐血|狗头)\s*\)/g, function ($0, $1) {
		$1 = encodeURI($1).replace(/%/g, '');
		return `<img class="owo" src="${window.JoeConfig.themeURL}assets/owo/paopao/${$1}_2x.png" />`;
	});

	str = str.replace(/\:\@\(\s*(高兴|小怒|脸红|内伤|装大款|赞一个|害羞|汗|吐血倒地|深思|不高兴|无语|亲亲|口水|尴尬|中指|想一想|哭泣|便便|献花|皱眉|傻笑|狂汗|吐|喷水|看不见|鼓掌|阴暗|长草|献黄瓜|邪恶|期待|得意|吐舌|喷血|无所谓|观察|暗地观察|肿包|中枪|大囧|呲牙|抠鼻|不说话|咽气|欢呼|锁眉|蜡烛|坐等|击掌|惊喜|喜极而泣|抽烟|不出所料|愤怒|无奈|黑线|投降|看热闹|扇耳光|小眼睛|中刀)\s*\)/g, function ($0, $1) {
		$1 = encodeURI($1).replace(/%/g, '');
		return `<img class="owo" src="${window.JoeConfig.themeURL}assets/owo/aru/${$1}_2x.png" />`;
	});

	if (str.indexOf('{lamp') !== -1) {
		str = str.replace(/{lamp\/}/g, '<span class="joe_lamp"></span>');
	}
	if (str.indexOf('{x}') !== -1) {
		str = str.replace(/{x}/g, '<input type="checkbox" class="joe_checkbox" checked disabled></input>');
	}
	if (str.indexOf('{ }') !== -1) {
		str = str.replace(/{ }/g, '<input type="checkbox" class="joe_checkbox" disabled></input>');
	}
	if (str.indexOf('{mtitle') !== -1) {
		str = str.replace(/{mtitle([^}]*)\/}/g, '<joe-mtitle $1></joe-mtitle>');
	}
	if (str.indexOf('{dplayer') !== -1) {
		str = str.replace(/{dplayer([^}]*)\/}/g, '<joe-dplayer player="' + player + '" $1></joe-dplayer>');
	}
	if (str.indexOf('{bilibili') !== -1) {
		str = str.replace(/{bilibili([^}]*)\/}/g, '<joe-bilibili $1></joe-bilibili>');
	}
	if (str.indexOf('{music-list') !== -1) {
		str = str.replace(/{music-list([^}]*)\/}/g, '<joe-mlist $1></joe-mlist>');
	}
	if (str.indexOf('{music') !== -1) {
		str = str.replace(/{music([^}]*)\/}/g, '<joe-music $1></joe-music>');
	}
	if (str.indexOf('{mp3') !== -1) {
		str = str.replace(/{mp3([^}]*)\/}/g, '<joe-mp3 $1></joe-mp3>');
	}
	if (str.indexOf('{abtn') !== -1) {
		str = str.replace(/{abtn([^}]*)\/}/g, '<joe-abtn $1></joe-abtn>');
	}
	if (str.indexOf('{anote') !== -1) {
		str = str.replace(/{anote([^}]*)\/}/g, '<joe-anote $1></joe-anote>');
	}
	if (str.indexOf('{copy') !== -1) {
		str = str.replace(/{copy([^}]*)\/}/g, '<joe-copy $1></joe-copy>');
	}
	if (str.indexOf('{dotted') !== -1) {
		str = str.replace(/{dotted([^}]*)\/}/g, '<joe-dotted $1></joe-dotted>');
	}
	if (str.indexOf('{message') !== -1) {
		str = str.replace(/{message([^}]*)\/}/g, '<joe-message $1></joe-message>');
	}
	if (str.indexOf('{progress') !== -1) {
		str = str.replace(/{progress([^}]*)\/}/g, '<joe-progress $1></joe-progress>');
	}
	if (str.indexOf('{cloud') !== -1) {
		str = str.replace(/{cloud([^}]*)\/}/g, '<joe-cloud $1></joe-cloud>');
	}
	if (str.indexOf('{hide') !== -1) {
		str = str.replace(/{hide[^}]*}([\s\S]*?){\/hide}/g, '<joe-hide></joe-hide>');
	}
	if (str.indexOf('{card-default') !== -1) {
		str = str.replace(/{card-default([^}]*)}([\s\S]*?){\/card-default}/g, '<section style="margin-bottom: 15px"><joe-card-default $1><span class="_temp" style="display: none">$2</span></joe-card-default></section>');
	}
	if (str.indexOf('{callout') !== -1) {
		str = str.replace(/{callout([^}]*)}([\s\S]*?){\/callout}/g, '<section style="margin-bottom: 15px"><joe-callout $1><span class="_temp" style="display: none">$2</span></joe-callout></section>');
	}
	if (str.indexOf('{card-describe') !== -1) {
		str = str.replace(/{card-describe([^}]*)}([\s\S]*?){\/card-describe}/g, '<section style="margin-bottom: 15px"><joe-card-describe $1><span class="_temp" style="display: none">$2</span></joe-card-describe></section>');
	}
	if (str.indexOf('{tabs') !== -1) {
		str = str.replace(/{tabs}([\s\S]*?){\/tabs}/g, '<section style="margin-bottom: 15px"><joe-tabs><span class="_temp" style="display: none">$1</span></joe-tabs></section>');
	}
	if (str.indexOf('{card-list') !== -1) {
		str = str.replace(/{card-list}([\s\S]*?){\/card-list}/g, '<section style="margin-bottom: 15px"><joe-card-list><span class="_temp" style="display: none">$1</span></joe-card-list></section>');
	}
	if (str.indexOf('{timeline') !== -1) {
		str = str.replace(/{timeline}([\s\S]*?){\/timeline}/g, '<section style="margin-bottom: 15px"><joe-timeline><span class="_temp" style="display: none">$1</span></joe-timeline></section>');
	}
	if (str.indexOf('{collapse') !== -1) {
		str = str.replace(/{collapse}([\s\S]*?){\/collapse}/g, '<section style="margin-bottom: 15px"><joe-collapse><span class="_temp" style="display: none">$1</span></joe-collapse></section>');
	}
	if (str.indexOf('{alert') !== -1) {
		str = str.replace(/{alert([^}]*)}([\s\S]*?){\/alert}/g, '<section style="margin-bottom: 15px"><joe-alert $1><span class="_temp" style="display: none">$2</span></joe-alert></section>');
	}
	if (str.indexOf('{gird') !== -1) {
		str = str.replace(/{gird([^}]*)}([\s\S]*?){\/gird}/g, '<section style="margin-bottom: 15px"><joe-gird $1><span class="_temp" style="display: none">$2</span></joe-gird></section>');
	}
	$('.cm-preview-content').html(str);
	$('.cm-preview-content p:empty').remove();
	bindCompatPreviewEvents();
	initCompatPreviewTyping();
	Prism.highlightAll();
}
