/*!
 * Joe 1.0 短代码兼容交互
 *
 * 配合 core/compat.php 生成的 1.0 j-* HTML，还原 Joe1.0/assets/js/joe.config.js
 * 中对应的交互行为：点击复制(j-copy)、打字机(j-typing)、Tab 切换(j-tabs)、
 * 伸缩展开(j-collapse)、视频册预览(j-short-video)。
 *
 * 依赖 2.0 已全局引入的 jQuery 与 Qmsg。
 */
(function ($) {
	'use strict';
	if (typeof $ === 'undefined') return;

	/* 文章内容容器 */
	var SCOPE = '.joe_detail__article';

	/* 点击复制 j-copy */
	function initCopy() {
		$(document).on('click', SCOPE + ' .j-copy', function () {
			var text = $(this).attr('data-copy') || $(this).text();
			var done = function () {
				if (typeof Qmsg !== 'undefined') Qmsg.success('复制成功！');
			};
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(text).then(done, function () {
					fallbackCopy(text, done);
				});
			} else {
				fallbackCopy(text, done);
			}
		});
	}

	function fallbackCopy(text, done) {
		var $input = $('<textarea>').val(text).css({ position: 'fixed', left: '-9999px', top: '0' }).appendTo('body');
		$input[0].select();
		try {
			document.execCommand('copy');
			done();
		} catch (e) {
			if (typeof Qmsg !== 'undefined') Qmsg.warning('复制失败，请手动复制！');
		}
		$input.remove();
	}

	/* 打字机 j-typing —— 逐字显示，末尾闪烁光标 */
	function initTyping() {
		$(SCOPE + ' .j-typing').each(function () {
			var $el = $(this);
			if ($el.data('typed')) return;
			$el.data('typed', true);
			var full = $el.text();
			$el.text('');
			var index = 0;
			var timer = setInterval(function () {
				if (index > full.length) {
					clearInterval(timer);
					$el.text(full);
					return;
				}
				$el.text(full.substring(0, index) + '_');
				index++;
			}, 100);
		});
	}

	/* Tab 栏切换 j-tabs */
	function initTabs() {
		$(SCOPE).find('.j-tabs').each(function () {
			var $tabs = $(this);
			$tabs.on('click', '.nav span', function () {
				var panel = $(this).attr('data-panel');
				$tabs.find('.nav span').removeClass('active');
				$(this).addClass('active');
				$tabs.find('.content > div').removeClass('active');
				$tabs.find('.content > div[data-panel="' + panel + '"]').addClass('active');
			});
		});
	}

	/* 伸缩展开 j-collapse */
	function initCollapse() {
		$(document).on('click', SCOPE + ' .j-collapse .collapse-head', function () {
			var $head = $(this);
			var $body = $head.next('.collapse-body');
			var $svg = $head.find('svg');
			var opened = $body.is(':visible');
			/* 关闭同组其它项 */
			$head.closest('.j-collapse').find('.collapse-body').slideUp(200);
			$head.closest('.j-collapse').find('.collapse-head svg').css('transform', 'rotate(180deg)');
			if (!opened) {
				$body.slideDown(200);
				$svg.css('transform', 'rotate(0deg)');
			}
		});
	}

	/* 视频册预览 j-short-video —— 动态注入弹层，点击播放 */
	function initVideoPreview() {
		var $preview = null;
		function ensurePreview() {
			if ($preview) return $preview;
			$preview = $(
				'<div class="j-video-preview">' +
					'<svg class="close" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M572.16 512l183.466667-183.04a42.666667 42.666667 0 1 0-60.586667-60.586667L512 451.84l-183.04-183.466667a42.666667 42.666667 0 0 0-60.586667 60.586667L451.84 512l-183.466667 183.04a42.666667 42.666667 0 0 0 0 60.586667 42.666667 42.666667 0 0 0 60.586667 0L512 572.16l183.04 183.466667a42.666667 42.666667 0 0 0 60.586667 0 42.666667 42.666667 0 0 0 0-60.586667z"></path></svg>' +
					'<iframe scrolling="no" allowfullscreen="allowfullscreen" frameborder="0"></iframe>' +
				'</div>'
			);
			$('body').append($preview);
			var close = function () {
				$preview.removeClass('active');
				setTimeout(function () { $preview.find('iframe').attr('src', ''); }, 350);
			};
			$preview.on('click', '.close', close);
			$preview.on('click', function (e) {
				if (e.target === $preview[0]) close();
			});
			return $preview;
		}
		$(document).on('click', SCOPE + ' .j-short-video .inner', function () {
			var src = $(this).attr('data-src');
			if (!src) return;
			var base = (window.Joe && window.Joe.THEME_URL ? window.Joe.THEME_URL : '') + '/library/player.php?url=';
			var $p = ensurePreview();
			$p.find('iframe').attr('src', base + src);
			$p.addClass('active');
		});
	}

	$(function () {
		if (!$(SCOPE).length) return;
		initCopy();
		initTyping();
		initTabs();
		initCollapse();
		initVideoPreview();
	});
})(window.jQuery);
