<?php
/* CDN/本地资源基址：JCdnStatus 关闭时用本地 assets/cdn/；开启时优先自定义 CDN 源，否则回退 fastly */
$JoeCdnUrl = trim((string) $this->options->JCdnUrl);
$JoeCdn = ($this->options->JCdnStatus === 'off')
    ? rtrim($this->options->themeUrl, '/') . '/assets/cdn/'
    : ($JoeCdnUrl !== '' ? rtrim($JoeCdnUrl, '/') . '/' : 'https://fastly.jsdelivr.net/');
?>
<footer class="joe_footer">
    <div class="joe_container">
        <div class="item">
            <?php if ($this->options->JFooter_Left) : ?>
                <span class="info"><?php $this->options->JFooter_Left() ?></span>
            <?php else : ?>
                <span class="info">2020 - 2021 © Reach - <a href="//xggm.top">XG.孤梦</a></span>
            <?php endif; ?>
        </div>
        <?php if ($this->options->JBirthDay) : ?>
            <div class="item run">
                <span>已运行 <strong class="joe_run__day">00</strong> 天 <strong class="joe_run__hour">00</strong> 时 <strong class="joe_run__minute">00</strong> 分 <strong class="joe_run__second">00</strong> 秒</span>
            </div>
        <?php endif; ?>
        <div class="item">
             <section class="banquan-links">
                <?php if ($this->options->JFooter_Right) : ?>
                    <?php $this->options->JFooter_Right() ?>
                <?php else : ?>                    
                    <a target="_blank" href="<?php $this->options->feedUrl(); ?>">RSS</a>
                    <a target="_blank" href="<?php echo $this->options->siteUrl . "index.php/sitemap.xml" ?>">MAP</a>
                <?php endif; ?>
				<div class="tooltip">当前在线<?php echo online_users() ?>人<span class="tooltiptext">博主 <?php get_last_login(1); ?> 在线</span></div>              
            </section>
        </div>
    </div>
</footer>

<div class="joe_action">
    <div class="joe_action_item scroll">
        <svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="25" height="25">
            <path d="M725.902 498.916c18.205-251.45-93.298-410.738-205.369-475.592l-6.257-3.982-6.258 3.414c-111.502 64.853-224.711 224.142-204.8 475.59-55.751 53.476-80.214 116.623-80.214 204.8v15.36l179.2-35.27c11.378 40.39 58.596 69.973 113.21 69.973 54.613 0 101.262-29.582 112.64-68.836l180.337 36.41v-15.36c-.569-89.885-25.031-153.6-82.489-206.507zM571.733 392.533c-33.564 31.29-87.04 28.445-118.329-5.12s-28.444-87.04 5.12-117.76c33.565-31.289 87.04-28.444 118.33 5.12s28.444 86.471-5.12 117.76zm-56.32 368.64c-35.84 0-64.284 29.014-64.284 64.285 0 35.84 54.044 182.613 64.284 182.613s64.285-146.773 64.285-182.613c0-35.271-29.014-64.285-64.285-64.285z" />
        </svg>
    </div>
    <div class="joe_action_item mode">
        <svg class="icon-1" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="25" height="25">
            <path d="M587.264 104.96c33.28 57.856 52.224 124.928 52.224 196.608 0 218.112-176.128 394.752-393.728 394.752-29.696 0-58.368-3.584-86.528-9.728C223.744 832.512 369.152 934.4 538.624 934.4c229.376 0 414.72-186.368 414.72-416.256 1.024-212.992-159.744-389.12-366.08-413.184z" />
            <path d="M340.48 567.808l-23.552-70.144-70.144-23.552 70.144-23.552 23.552-70.144 23.552 70.144 70.144 23.552-70.144 23.552-23.552 70.144zM168.96 361.472l-30.208-91.136-91.648-30.208 91.136-30.208 30.72-91.648 30.208 91.136 91.136 30.208-91.136 30.208-30.208 91.648z" />
        </svg>
        <svg class="icon-2" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="25" height="25">
            <path d="M234.24 512a277.76 277.76 0 1 0 555.52 0 277.76 277.76 0 1 0-555.52 0zM512 187.733a42.667 42.667 0 0 1-42.667-42.666v-102.4a42.667 42.667 0 0 1 85.334 0v102.826A42.667 42.667 0 0 1 512 187.733zm-258.987 107.52a42.667 42.667 0 0 1-29.866-12.373l-72.96-73.387a42.667 42.667 0 0 1 59.306-59.306l73.387 72.96a42.667 42.667 0 0 1 0 59.733 42.667 42.667 0 0 1-29.867 12.373zm-107.52 259.414H42.667a42.667 42.667 0 0 1 0-85.334h102.826a42.667 42.667 0 0 1 0 85.334zm34.134 331.946a42.667 42.667 0 0 1-29.44-72.106l72.96-73.387a42.667 42.667 0 0 1 59.733 59.733l-73.387 73.387a42.667 42.667 0 0 1-29.866 12.373zM512 1024a42.667 42.667 0 0 1-42.667-42.667V878.507a42.667 42.667 0 0 1 85.334 0v102.826A42.667 42.667 0 0 1 512 1024zm332.373-137.387a42.667 42.667 0 0 1-29.866-12.373l-73.387-73.387a42.667 42.667 0 0 1 0-59.733 42.667 42.667 0 0 1 59.733 0l72.96 73.387a42.667 42.667 0 0 1-29.44 72.106zm136.96-331.946H878.507a42.667 42.667 0 1 1 0-85.334h102.826a42.667 42.667 0 0 1 0 85.334zM770.987 295.253a42.667 42.667 0 0 1-29.867-12.373 42.667 42.667 0 0 1 0-59.733l73.387-72.96a42.667 42.667 0 1 1 59.306 59.306l-72.96 73.387a42.667 42.667 0 0 1-29.866 12.373z" />
        </svg>
    </div>
     <?php if ($this->options->JGlobalThemeStatus === 'on') : ?>
        <div class="joe_action_item active" id="openColorPick">
            <svg viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <path d="M509.8 986.6c-95.9 0-187.6-34.7-258.2-97.7C180.4 825.1 137.1 738.6 130 645c-7.3-93.7 22.2-185.4 82.8-258.7l0.2-0.2L471.2 46.2c7.9-10.5 20.7-16.8 34.3-16.9h0.2c13.3 0 26 6.2 34.2 16.4l263.7 336.6c0.3 0.5 1 1.1 1.4 1.4 60.9 72.2 91.2 163.3 85.3 256.4-6 93.8-47.9 180.9-118.5 245.5-71 65-164.1 101-262 101zM217.1 639.2c-0.3 1.6 0.1 3.5 0.9 6.4 8.7 36.6 37.8 101.7 134.7 126 15.9 4 31.4 6 46.2 6 52.8 0 89.8-24.6 125.4-48.6 30.4-20.3 59.1-39.6 94.6-39.6 3.7 0 7.6 0.2 11.5 0.6 50.1 5.7 106.9 24.1 143.4 39.4 1 0.3 1.9 0.5 3 0.5 1 0 1.9-0.2 2.8-0.5 1.8-0.6 3.3-2.1 4.1-3.9 41.3-98.4 23.5-210.4-46.8-291.9L512.2 147.4c-1.4-1.8-3.5-2.9-5.8-2.9s-4.5 1.2-6 2.9L281 435.9l-1.3 1.7c-0.2 0.3-0.4 0.5-0.6 1-41.8 50.6-64.8 114.2-64.7 179.1 0 0.3 0 0.5 0.1 1l2.6 20.5z" p-id="6717"></path>
            </svg>
            <div class="color-pick" id="colorPick"></div>
        </div>
    <?php endif; ?>	
    
 <!--   <div class="joe_action_item active" id="readBook">-->
	<!--    <a style="font-weight:bold;font-size:18px;color:var(--theme)" id="translateLink" href="javascript:translatePage();">繁</a>-->
	<!--</div>	-->
	<!-- 多语言翻译切换 -->
	<?php if ($this->options->JTranslateStatus === 'on') : ?>
		<?php
		/* 解析语言列表：显示名称 || 语言代码，每行一个，留空用默认列表 */
		$JTranslateDefault = "简体中文 || chinese_simplified\nEnglish || english";
		$JTranslateText = trim((string) $this->options->JTranslateLangs);
		if ($JTranslateText === '') {
			$JTranslateText = $JTranslateDefault;
		}
		$JTranslateList = array();
		foreach (preg_split('/\r\n|\r|\n/', $JTranslateText) as $JTranslateLine) {
			/* 容错：全角竖杠转半角，单/双竖杠均可分隔 */
			$JTranslateLine = str_replace(array('｜', '︱'), '|', $JTranslateLine);
			$JTranslateItem = preg_split('/\|+/', $JTranslateLine);
			if (count($JTranslateItem) === 2 && trim($JTranslateItem[0]) !== '' && trim($JTranslateItem[1]) !== '') {
				$JTranslateList[] = array('name' => trim($JTranslateItem[0]), 'code' => trim($JTranslateItem[1]));
			}
		}
		/* 内容格式全部无效时回退默认列表，避免菜单为空 */
		if (empty($JTranslateList)) {
			foreach (preg_split('/\r\n|\r|\n/', $JTranslateDefault) as $JTranslateLine) {
				$JTranslateItem = explode('||', $JTranslateLine);
				$JTranslateList[] = array('name' => trim($JTranslateItem[0]), 'code' => trim($JTranslateItem[1]));
			}
		}
		?>
		<div class="joe_action_item lang ignore" id="joeTranslate">
			<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="25" height="25">
				<path d="M12.87 15.07l-2.54-2.51.03-.03c1.74-1.94 2.98-4.17 3.71-6.53H17V4h-7V2H8v2H1v1.99h11.17C11.5 7.92 10.44 9.75 9 11.35 8.07 10.32 7.3 9.19 6.69 8h-2c.73 1.63 1.73 3.17 2.98 4.56l-5.09 5.02L4 19l5-5 3.11 3.11.76-2.04zM18.5 10h-2L12 22h2l1.12-3h4.75L21 22h2l-4.5-12zm-2.62 7l1.62-4.33L19.12 17h-3.24z" />
			</svg>
			<nav class="joe_lang__menu">
				<?php foreach ($JTranslateList as $JTranslateItem) : ?>
					<span class="joe_lang__menu-item" data-lang="<?php echo htmlspecialchars($JTranslateItem['code']) ?>"><?php echo htmlspecialchars($JTranslateItem['name']) ?></span>
				<?php endforeach; ?>
			</nav>
		</div>
		<style>
			.joe_action_item.lang .joe_lang__menu {
				position: absolute;
				right: 50px;
				bottom: 0;
				min-width: 110px;
				padding: 5px 0;
				background: var(--background);
				border-radius: var(--radius-inner);
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.1), 0 5px 20px rgba(0, 0, 0, 0.2);
				opacity: 0;
				visibility: hidden;
				transform: translateX(10px);
				transition: opacity 0.25s, visibility 0.25s, transform 0.25s;
				cursor: auto;
			}
			.joe_action_item.lang.active .joe_lang__menu {
				opacity: 1;
				visibility: visible;
				transform: translateX(0);
			}
			.joe_lang__menu-item {
				display: block;
				padding: 6px 15px;
				font-size: 13px;
				text-align: center;
				white-space: nowrap;
				color: var(--main);
				cursor: pointer;
				transition: color 0.25s, background 0.25s;
			}
			.joe_lang__menu-item:hover {
				color: var(--theme);
				background: var(--classD);
			}
			.joe_lang__menu-item.active {
				color: var(--theme);
				font-weight: 500;
			}
		</style>
	<?php endif; ?>
	<div class="read_book_button  not_read_book"   style="display: none"></div>
</div>
<!--- 移动端悬浮按钮贴边半隐藏，点击展开 开始 --->
<style>
    @media screen and (max-width: 768px) {
        .joe_action {
            right: 12px;
            transition: transform .35s ease, opacity .35s ease;
        }
        .joe_action.joe_action--collapsed {
            transform: translateX(calc(50% + 12px));
            opacity: .45;
        }
        .joe_action.joe_action--collapsed:active {
            opacity: .7;
        }
    }
</style>
<script>
    (function () {
        var mq = window.matchMedia('(max-width: 768px)');
        var COLLAPSED = 'joe_action--collapsed';
        function getAction() {
            return document.querySelector('.joe_action');
        }
        function apply() {
            var action = getAction();
            if (!action) return;
            mq.matches ? action.classList.add(COLLAPSED) : action.classList.remove(COLLAPSED);
        }
        apply();
        /* document/matchMedia 级监听只绑一次，Pjax 换页重执行时仅重设折叠状态 */
        if (window.__joeActionCollapseInit) return;
        window.__joeActionCollapseInit = true;
        if (mq.addEventListener) {
            mq.addEventListener('change', apply);
        } else if (mq.addListener) {
            mq.addListener(apply);
        }
        document.addEventListener('click', function (e) {
            var action = getAction();
            if (!action || !mq.matches) return;
            /* 折叠状态下首次点击只负责展开，并拦截内部按钮的点击 */
            if (action.classList.contains(COLLAPSED)) {
                if (action.contains(e.target)) {
                    e.stopPropagation();
                    e.preventDefault();
                    action.classList.remove(COLLAPSED);
                }
                return;
            }
            /* 点击外部区域重新折叠回边缘 */
            if (!action.contains(e.target)) {
                action.classList.add(COLLAPSED);
            }
        }, true);
    })();
</script>
<!--- 移动端悬浮按钮贴边半隐藏，点击展开 结束 --->
<?php if ($this->options->JGlobalThemeStatus === 'on') : ?>
    <script src="<?php echo $JoeCdn; ?>npm/jquery-colpick@3.1.0/js/colpick.min.js"></script>
<?php endif; ?>
<!-- 多语言翻译（translate.js，翻译在浏览器端完成） -->
<?php if ($this->options->JTranslateStatus === 'on') : ?>
    <script src="<?php echo ($this->options->JCdnStatus === 'off') ? 'https://fastly.jsdelivr.net/' : $JoeCdn; ?>npm/i18n-jsautotranslate@3.18.98/index.min.js"></script>
    <script>
        (function () {
            /* translate.js 加载成功才初始化翻译，失败时菜单交互仍可用 */
            if (typeof translate !== 'undefined') {
                translate.selectLanguageTag.show = false; /* 隐藏自带的语言下拉框，使用主题自己的按钮 */
                /* 禁用对 api.translate.zvo.cn 的初始化/测速等请求，client.edge 通道下无需它们，避免接口不通时控制台报错 */
                translate.request.api.init = '';
                translate.request.api.connectTest = '';
                translate.request.api.ip = '';
                translate.request.api.language = '';
                translate.language.setLocal('chinese_simplified'); /* 站点原始语言 */
                translate.service.use('client.edge'); /* 免费翻译通道，无需后端 */
                /* 监听 DOM 变化：首页文章列表等 AJAX 异步插入的内容也跟随翻译（Pjax 回首页时列表晚于 execute 加载）；
                   监听挂在 document 上换页后仍有效，只需启动一次，避免 Pjax 重执行时叠加多个观察器 */
                if (!window.__joeTranslateListener && translate.listener && translate.listener.start) {
                    window.__joeTranslateListener = true;
                    translate.listener.start();
                }
                translate.execute(); /* 恢复上次选择的语言 */
            }

            /* 标记菜单中当前语言 */
            function markActive() {
                var wrap = document.getElementById('joeTranslate');
                if (!wrap || typeof translate === 'undefined') return;
                var current = translate.language.getCurrent();
                wrap.querySelectorAll('.joe_lang__menu-item').forEach(function (item) {
                    item.classList.toggle('active', item.getAttribute('data-lang') === current);
                });
            }
            markActive();

            /* document 级监听只绑一次，Pjax 换页重执行时跳过 */
            if (window.__joeTranslateInit) return;
            window.__joeTranslateInit = true;
            document.addEventListener('click', function (e) {
                var wrap = document.getElementById('joeTranslate');
                if (!wrap) return;
                var item = e.target.closest('.joe_lang__menu-item');
                if (item && wrap.contains(item)) {
                    var lang = item.getAttribute('data-lang');
                    if (typeof translate !== 'undefined' && lang && lang !== translate.language.getCurrent()) {
                        translate.changeLanguage(lang);
                        markActive();
                    }
                    wrap.classList.remove('active');
                    return;
                }
                /* 点按钮展开/收起菜单，点外部关闭 */
                wrap.contains(e.target) ? wrap.classList.toggle('active') : wrap.classList.remove('active');
            });
        })();
    </script>
<?php endif; ?>
<!-- 目录树 -->
<?php if ($this->options->JDirectoryStatus === 'on'  && !_isMobile()) : ?>
    <script src="<?php $this->options->themeUrl('assets/js/jfloor.min.js') ?>"></script>
<?php endif; ?>

<!--- 阅读模式开始 --->
<script>    
    // 判断是否出现正文出现正文的时候出现read按钮
    var topics = document.querySelector('.joe_detail__article');
    var read_book_button = document.querySelector('.read_book_button');    
    if (topics && read_book_button) {
        read_book_button.style.display = 'block';                
    }
    
    // 保存侧边栏原始状态
    var sidebarOriginalWidth = null;
    var sidebarOriginalActive = false;
    
    read_book_button.onclick = function () {
        // 点击事件切换类名
        var class_name = this.classList[1];
        class_name == 'read_book' ? this.className = 'read_book_button not_read_book' : this.className = 'read_book_button read_book'

        // 获取元素
        var head = document.querySelector('.joe_header');
        var sideBar = document.querySelector('.joe_aside');
        var stretchButton = document.querySelector('.joe-stretch');
        var comment_form = document.querySelector('.joe_comment');
        var footer = document.querySelector('.joe_footer');          
        var related = document.querySelector('.joe_detail__related');            
        var read_color = document.querySelector('.joe_detail');
        var read_size = document.querySelector('.joe_detail__article');
        
        // 获取灯笼元素
        var dengBoxes = [
            document.querySelector('.deng-box'),
            document.querySelector('.deng-box1'),
            document.querySelector('.deng-box2'),
            document.querySelector('.deng-box3')
        ];
        
        if (class_name == 'read_book') {
            // 退出阅读模式
            if (head) head.style.display = 'block';
            if (stretchButton) stretchButton.style.display = 'block';
            if (comment_form) comment_form.style.display = 'block';                       
            if (footer) footer.style.display = 'block';              
            if (related) related.style.display = 'block';                       
            if (read_color) read_color.style.backgroundColor = 'var(--background)';
            if (read_size) read_size.style.fontSize = 'medium';
            
            // 显示灯笼
            dengBoxes.forEach(function(dengBox) {
                if (dengBox) dengBox.style.display = 'block';
            });
            
            // 恢复侧边栏原始状态
            if (sidebarOriginalWidth !== null && sideBar) {
                sideBar.style.width = sidebarOriginalWidth;
                sideBar.style.overflow = '';
                if (stretchButton) {
                    if (sidebarOriginalActive) {
                        stretchButton.classList.add('active');
                    } else {
                        stretchButton.classList.remove('active');
                    }
                }
            }
        } else {
            // 进入阅读模式
            if (head) head.style.display = 'none';
            if (stretchButton) stretchButton.style.display = 'none';
            if (comment_form) comment_form.style.display = 'none';                      
            if (footer) footer.style.display = 'none';              
            if (related) related.style.display = 'none';                    
            if (read_color) read_color.style.backgroundColor = 'var(--background)'; 
            if (read_size) read_size.style.fontSize = "20px";
            
            // 隐藏灯笼
            dengBoxes.forEach(function(dengBox) {
                if (dengBox) dengBox.style.display = 'none';
            });
            
            // 保存侧边栏原始状态
            if (sidebarOriginalWidth === null && sideBar) {
                sidebarOriginalWidth = sideBar.style.width;
                if (stretchButton) {
                    sidebarOriginalActive = stretchButton.classList.contains('active');
                }
            }
            
            // 隐藏侧边栏（与原逻辑一致，通过设置宽度为0）
            if (sideBar) {
                sideBar.style.width = '0';
                sideBar.style.overflow = 'hidden';
            }
        }
    }
</script>
<!-- 复制提醒 -->
<?php if($this -> options -> copying): ?>
<script type="text/javascript">
var copyText = "<?php echo addslashes($this->options->copying); ?>";
function warning(){ if(navigator.userAgent.indexOf("MSIE")>0) 
{art.dialog.swal("复制成功！", copyText, "success"); } 
else 
{ swal("复制成功！", copyText, "success"); }}
document.body.oncopy=function(){warning();}
</script>
<?php endif; ?>

<!-- 灯笼1 -->
<?php if ($this->options->denglong) : ?>
<?php
        $adContent1 = $this->options->denglong;
        $adContent1Counts = explode("||", $adContent1);
        ?>
<div class="deng-box">
<div class="deng">
<div class="xian"></div>
<div class="deng-a">
<div class="deng-b"><div class="deng-t"><?php echo $adContent1Counts[3] ?></div></div>
</div>
<div class="shui shui-a"><div class="shui-c"></div><div class="shui-b"></div></div>
</div>
</div>
<!-- 灯笼2 -->
<div class="deng-box1">
<div class="deng">
<div class="xian"></div>
<div class="deng-a">
<div class="deng-b"><div class="deng-t"><?php echo $adContent1Counts[2] ?></div></div>
</div>
<div class="shui shui-a"><div class="shui-c"></div><div class="shui-b"></div></div>
</div>
</div>
<!-- 灯笼3 -->
<div class="deng-box2">
<div class="deng">
<div class="xian"></div>
<div class="deng-a">
<div class="deng-b"><div class="deng-t"><?php echo $adContent1Counts[1] ?></div></div>
</div>
<div class="shui shui-a"><div class="shui-c"></div><div class="shui-b"></div></div>
</div>
</div>
<!-- 灯笼4 -->
<div class="deng-box3">
<div class="deng">
<div class="xian"></div>
<div class="deng-a">
<div class="deng-b"><div class="deng-t"><?php echo $adContent1Counts[0] ?></div></div>
</div>
<div class="shui shui-a"><div class="shui-c"></div><div class="shui-b"></div></div>
</div>
</div>
<?php endif; ?>

<script>
    <?php
    $cookie = Typecho_Cookie::getPrefix();
    $notice = $cookie . '__typecho_notice';
    $type = $cookie . '__typecho_notice_type';
    ?>
    <?php if (isset($_COOKIE[$notice]) && isset($_COOKIE[$type]) && ($_COOKIE[$type] == 'success' || $_COOKIE[$type] == 'notice' || $_COOKIE[$type] == 'error')) : ?>
        Qmsg.info("<?php echo preg_replace('#\[\"(.*?)\"\]#', '$1', $_COOKIE[$notice]); ?>！")
    <?php endif; ?>
    <?php
    Typecho_Cookie::delete('__typecho_notice');
    Typecho_Cookie::delete('__typecho_notice_type');
    ?>
    console.log("%c页面加载耗时：<?php _endCountTime(); ?> | Theme By Joe", "color:#fff; background: linear-gradient(270deg, #986fee, #8695e6, #68b7dd, #18d7d3); padding: 8px 15px; border-radius: 0 15px 0 15px");
    <?php $this->options->JCustomScript() ?>
</script>

<?php $this->options->JCustomBodyEnd() ?>

<?php if ($this->options->JCommentImg === 'on') : ?>
    <script type="text/javascript" src="<?php $this->options->themeUrl('assets/js/img.min.js'); ?>"></script>
<?php endif; ?>

<?php $this->footer(); ?>