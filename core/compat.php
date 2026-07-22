<?php

/**
 * Joe 1.0 短代码向下兼容解析器
 *
 * Joe1.0 使用方括号 [xxx] 短代码，经其 Short_* 函数渲染成带 j-* 类名的 HTML；
 * Joe2.0 重构后改用花括号 {xxx} 短代码 + <joe-*> Web Component，因此不识别 1.0 的
 * [xxx] 语法，导致老文章的短代码以原始文本形式泄漏到页面、破坏排版。
 *
 * 本文件忠实移植 Joe1.0/core/core.php 中的 ParseCode/Short_* 渲染逻辑，将 1.0 的
 * [xxx] 短代码翻译为与 1.0 完全一致的 j-* HTML，配合 assets/css/joe.compat.css 与
 * assets/js/joe.compat.js 还原样式与交互。
 *
 * 入口：_parseCompat($content, $post, $login)
 */

if (!function_exists('_compatGetLazyLoad')) {
    /* 获取懒加载占位图（2.0 使用 lazysizes，这里返回透明占位） */
    function _compatGetLazyLoad()
    {
        return 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
    }
}

if (!function_exists('_compatGetPlayer')) {
    /* 获取模板内置播放器地址（兼容 2.0 的自定义播放器选项） */
    function _compatGetPlayer()
    {
        if (Helper::options()->JCustomPlayer) {
            return Helper::options()->JCustomPlayer;
        }
        return Helper::options()->themeUrl . '/library/player.php?url=';
    }
}

/* 图片相册短代码 [photo] */
function _compatShortPhoto($text)
{
    $text = preg_replace_callback('/<p>\[photo\](.*?)\[\/photo\]<\/p>/ism', function ($text) {
        return '[photo]' . $text[1] . '[/photo]';
    }, $text);
    $text = preg_replace_callback('/\[photo\](.*?)\[\/photo\]/ism', function ($text) {
        return preg_replace('~<br.*?>~', '', $text[0]);
    }, $text);
    $text = preg_replace_callback('/\[photo\](.*?)\[\/photo\]/ism', function ($text) {
        return '<div class="j-photos">' . $text[1] . '</div>';
    }, $text);
    return $text;
}

/* tag 标签短代码 [tag type=""] */
function _compatShortTag($text)
{
    $text = preg_replace_callback('/\[tag type=\"(.*?)\".*?\](.*?)\[\/tag\]/ism', function ($text) {
        return '<span class="j-tag ' . $text[1] . '">' . $text[2] . '</span>';
    }, $text);
    return $text;
}

/* 按钮短代码 [btn href="" type=""] */
function _compatShortButton($text)
{
    $text = preg_replace_callback('/\[btn href=\"(.*?)\" type=\"(.*?)\".*?\](.*?)\[\/btn\]/ism', function ($text) {
        return '<a href="' . $text[1] . '" class="j-btn ' . $text[2] . '">' . $text[3] . '</a>';
    }, $text);
    return $text;
}

/* 提示框短代码 [alt type=""] */
function _compatShortAlt($text)
{
    $text = preg_replace_callback('/<p>\[alt type=\"(.*?)\".*?\](.*?)\[\/alt\]<\/p>/ism', function ($text) {
        return '[alt type="' . $text[1] . '"]' . $text[2] . '[/alt]';
    }, $text);
    $text = preg_replace_callback('/\[alt type=\"(.*?)\".*?\](.*?)\[\/alt\]/ism', function ($text) {
        return '<div class="j-alt ' . $text[1] . '">' . $text[2] . '</div>';
    }, $text);
    return $text;
}

/* 线状标题短代码 [line] */
function _compatShortLine($text)
{
    $text = preg_replace_callback('/<p>\[line\](.*?)\[\/line\]<\/p>/ism', function ($text) {
        return '[line]' . $text[1] . '[/line]';
    }, $text);
    $text = preg_replace_callback('/\[line\](.*?)\[\/line\]/ism', function ($text) {
        return '<div class="j-line"><span>' . $text[1] . '</span></div>';
    }, $text);
    return $text;
}

/* Tab 栏切换短代码 [tabs] + [tab-pane label=""] */
function _compatShortTabs($text)
{
    $text = preg_replace_callback('/<p>\[tabs\](.*?)\[\/tabs\]<\/p>/ism', function ($text) {
        return '[tabs]' . $text[1] . '[/tabs]';
    }, $text);
    $text = preg_replace_callback('/\[tabs\](.*?)\[\/tabs\]/ism', function ($text) {
        return preg_replace('~<br.*?>~', '', $text[0]);
    }, $text);
    $text = preg_replace_callback('/\[tabs\](.*?)\[\/tabs\]/ism', function ($text) {
        $tabname = '';
        preg_match_all('/label=\"(.*?)\"\]/i', $text[1], $tabnamearr);
        for ($i = 0; $i < count($tabnamearr[1]); $i++) {
            if ($i === 0) {
                $tabname .= '<span class="active" data-panel="' . $i . '">' . $tabnamearr[1][$i] . '</span>';
            } else {
                $tabname .= '<span data-panel="' . $i . '">' . $tabnamearr[1][$i] . '</span>';
            }
        }
        $tabcon = '';
        preg_match_all('/"\](.*?)\[\//i', $text[1], $tabconarr);
        for ($i = 0; $i < count($tabconarr[1]); $i++) {
            if ($i === 0) {
                $tabcon .= '<div class="active" data-panel="' . $i . '">' . $tabconarr[1][$i] . '</div>';
            } else {
                $tabcon .= '<div data-panel="' . $i . '">' . $tabconarr[1][$i] . '</div>';
            }
        }
        return '<div class="j-tabs"><div class="nav">' . $tabname . '</div><div class="content">' . $tabcon . '</div></div>';
    }, $text);
    return $text;
}

/* 默认卡片短代码 [card-default width="" label=""] */
function _compatShortCardDefault($text)
{
    $text = preg_replace_callback('/<p>\[card-default width=\"(.*?)\" label=\"(.*?)\".*?\](.*?)\[\/card-default\]<\/p>/ism', function ($text) {
        return '[card-default width="' . $text[1] . '" label="' . $text[2] . '"]' . $text[3] . '[/card-default]';
    }, $text);
    $text = preg_replace_callback('/\[card-default width=\"(.*?)\" label=\"(.*?)\".*?\](.*?)\[\/card-default\]/ism', function ($text) {
        return '<div class="j-card-default" style="width: ' . $text[1] . '">
                <div class="head">' . $text[2] . '</div>
                <div class="content">' . $text[3] . '</div>
            </div>';
    }, $text);
    return $text;
}

/* 伸缩展开短代码 [collapse] + [collapse-item label=""] */
function _compatShortCollapse($text)
{
    $text = preg_replace_callback('/<p>\[collapse\](.*?)\[\/collapse\]<\/p>/ism', function ($text) {
        return '[collapse]' . $text[1] . '[/collapse]';
    }, $text);
    $text = preg_replace_callback('/\[collapse\](.*?)\[\/collapse\]/ism', function ($text) {
        return preg_replace('~<br.*?>~', '', $text[0]);
    }, $text);
    $text = preg_replace_callback('/\[collapse\](.*?)\[\/collapse\]/ism', function ($text) {
        return '<div class="j-collapse">' . $text[1] . '</div>';
    }, $text);
    $text = preg_replace_callback('/\<p>\[collapse-item label=\"(.*?)\".*?\](.*?)\[\/collapse-item\]<\/p>/ism', function ($text) {
        return '[collapse-item label="' . $text[1] . '"]' . $text[2] . '[/collapse-item]';
    }, $text);
    $text = preg_replace_callback('/\[collapse-item label=\"(.*?)\".*?\](.*?)\[\/collapse-item\]/ism', function ($text) {
        return '<div class="collapse-head"><span>' . $text[1] . '</span><svg viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M21.6 772.8c28.8 28.8 74.4 28.8 103.2 0L512 385.6 899.2 772.8c28.8 28.8 74.4 28.8 103.2 0 28.8-28.8 28.8-74.4 0-103.2l-387.2-387.2-77.6-77.6c-14.4-14.4-37.6-14.4-51.2 0l-77.6 77.6-387.2 387.2c-28.8 28.8-28.8 75.2 0 103.2z"></path></svg></div><div class="collapse-body">' . $text[2] . '</div>';
    }, $text);
    return $text;
}

/* 时间线短代码 [timeline] + [timeline-item] */
function _compatShortTimeLine($text)
{
    $text = preg_replace_callback('/<p>\[timeline\](.*?)\[\/timeline\]<\/p>/ism', function ($text) {
        return '[timeline]' . $text[1] . '[/timeline]';
    }, $text);
    $text = preg_replace_callback('/\[timeline\](.*?)\[\/timeline\]/ism', function ($text) {
        return preg_replace('~<br.*?>~', '', $text[0]);
    }, $text);
    $text = preg_replace_callback('/\[timeline\](.*?)\[\/timeline\]/ism', function ($text) {
        return '<div class="j-timeline">' . $text[1] . '</div>';
    }, $text);
    $text = preg_replace_callback('/<p>\[timeline-item\](.*?)\[\/timeline-item\]<\/p>/ism', function ($text) {
        return '[timeline-item]' . $text[1] . '[/timeline-item]';
    }, $text);
    $text = preg_replace_callback('/\[timeline-item\](.*?)\[\/timeline-item\]/ism', function ($text) {
        return '<div class="item">' . $text[1] . '</div>';
    }, $text);
    return $text;
}

/* 点击复制短代码 [copy] */
function _compatShortCopy($text)
{
    $text = preg_replace_callback('/\[copy\](.*?)\[\/copy\]/ism', function ($text) {
        return '<span class="j-copy" data-copy="' . $text[1] . '">' . $text[1] . '</span>';
    }, $text);
    return $text;
}

/* 打字机短代码 [typing] */
function _compatShortTyping($text)
{
    $text = preg_replace_callback('/\[typing\](.*?)\[\/typing\]/ism', function ($text) {
        return '<span class="j-typing">' . $text[1] . '</span>';
    }, $text);
    return $text;
}

/* 链接卡片短代码 [card-nav] + [card-nav-item src="" title="" img=""] */
function _compatShortCardNav($text)
{
    $text = preg_replace_callback('/<p>\[card-nav\](.*?)\[\/card-nav\]<\/p>/ism', function ($text) {
        return '[card-nav]' . $text[1] . '[/card-nav]';
    }, $text);
    $text = preg_replace_callback('/\[card-nav\](.*?)\[\/card-nav\]/ism', function ($text) {
        return preg_replace('~<br.*?>~', '', $text[0]);
    }, $text);
    $text = preg_replace_callback('/\[card-nav\](.*?)\[\/card-nav\]/ism', function ($text) {
        return '<div class="j-card-nav">' . $text[1] . '</div>';
    }, $text);
    $text = preg_replace_callback('/\[card-nav-item src=\"(.*?)\" title=\"(.*?)\" img=\"(.*?)\".*?\/\]/ism', function ($text) {
        $img = $text[3] === "auto" ? $text[1] . '/favicon.ico' : $text[3];
        $arr = array(
            0 => "linear-gradient(to right, #6DE195, #C4E759)",
            1 => "linear-gradient(to right, #41C7AF, #54E38E)",
            2 => "linear-gradient(to right, #99E5A2, #D4FC78)",
            3 => "linear-gradient(to right, #ABC7FF, #C1E3FF)",
            4 => "linear-gradient(to right, #6CACFF, #8DEBFF)",
            5 => "linear-gradient(to right, #5583EE, #41D8DD)",
            6 => "linear-gradient(to right, #D279EE, #F8C390)",
            7 => "linear-gradient(to right, #F78FAD, #FDEB82)",
            8 => "linear-gradient(to right, #A43AB2, #E13680)",
        );
        return '<div class="item">
                    <a href="' . $text[1] . '" class="nav" style="background-image: ' . $arr[rand(0, 8)] . '">
                        <span class="avatar" style="background-image: url(' . $img . ')"></span>
                        <span class="content">' . $text[2] . '</span>
                        <svg viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M428.8 928h-60.8v-282.24l280.96-284.16 46.08 44.8-263.04 265.6v164.48l137.6-131.2 174.08 80 98.56-568.32-604.8 334.72 96 44.16-26.88 58.24L96 556.8l832-460.8-135.68 782.72-209.92-97.28z" p-id="5322"></path></svg>
                    </a>
                </div>';
    }, $text);
    return $text;
}

/* dplayer 短代码 [dplayer src="" /] */
function _compatShortDplayer($text)
{
    $text = preg_replace_callback('/<p>\[dplayer src="(.*?)".*?\/]<\/p>/ism', function ($text) {
        return '[dplayer src="' . $text[1] . '" /]';
    }, $text);
    $text = preg_replace_callback('/\[dplayer src="(.*?)".*?\/]/ism', function ($text) {
        return '<iframe scrolling="no" allowfullscreen="allowfullscreen" frameborder="0" width="100%" class="iframe-dplayer" src="' . _compatGetPlayer() . $text[1] . '"></iframe>';
    }, $text);
    return $text;
}

/* plyr 短代码 [plyr src="" /] */
function _compatShortPlyr($text)
{
    $text = preg_replace_callback('/<p>\[plyr src="(.*?)".*?\/]<\/p>/ism', function ($text) {
        return '[plyr src="' . $text[1] . '" /]';
    }, $text);
    $text = preg_replace_callback('/\[plyr src="(.*?)".*?\/]/ism', function ($text) {
        return '<iframe scrolling="no" allowfullscreen="allowfullscreen" frameborder="0" width="100%" class="iframe-dplayer" src="' . _compatGetPlayer() . $text[1] . '"></iframe>';
    }, $text);
    return $text;
}

/* 网易云音乐短代码 [music id="" /] */
function _compatShortMusic($text)
{
    $text = preg_replace_callback('/<p>\[music id="(.*?)".*?\/]<\/p>/ism', function ($text) {
        return '[music id="' . $text[1] . '" /]';
    }, $text);
    $text = preg_replace_callback('/\[music id="(.*?)".*?\/]/ism', function ($text) {
        return '<iframe class="iframe-music" frameborder="no" border="0" width="330" height="86" src="//music.163.com/outchain/player?type=2&id=' . $text[1] . '&auto=1&height=66"></iframe>';
    }, $text);
    return $text;
}

/* 网易云歌单短代码 [music-list id="" /] */
function _compatShortMusicList($text)
{
    $text = preg_replace_callback('/<p>\[music-list id="(.*?)".*?\/]<\/p>/ism', function ($text) {
        return '[music-list id="' . $text[1] . '" /]';
    }, $text);
    $text = preg_replace_callback('/\[music-list id="(.*?)".*?\/]/ism', function ($text) {
        return '<iframe class="iframe-music" frameborder="no" border="0" width="330" height="450" src="//music.163.com/outchain/player?type=0&id=' . $text[1] . '&auto=1&height=430"></iframe>';
    }, $text);
    return $text;
}

/* 视频册短代码 [video] + [video-item src="" poster=""] */
function _compatShortVideoList($text)
{
    $text = preg_replace_callback('/<p>\[video](.*?)\[\/video]<\/p>/ism', function ($text) {
        return '[video]' . $text[1] . '[/video]';
    }, $text);
    $text = preg_replace_callback('/\[video](.*?)\[\/video]/ism', function ($text) {
        return preg_replace('~<br.*?>~', '', $text[0]);
    }, $text);
    $text = preg_replace_callback('/\[video](.*?)\[\/video]/ism', function ($text) {
        return '<div class="j-short-video">' . $text[1] . '</div>';
    }, $text);
    $text = preg_replace_callback('/\[video-item src="(.*?)" poster="(.*?)".*?\/]/ism', function ($text) {
        return '<div class="item">
                    <div class="inner" data-poster="' . $text[2] . '" data-src="' . $text[1] . '" style="background-image: url(' . _compatGetLazyLoad() . ')">
                        <svg t="1607510948740" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="19996" width="80" height="80"><path d="M512 65c247.424 0 448 200.576 448 448S759.424 961 512 961 64 760.424 64 513 264.576 65 512 65z m0 64c-212.077 0-384 171.923-384 384s171.923 384 384 384 384-171.923 384-384-171.923-384-384-384z m-63 214.657a64 64 0 0 1 33.593 9.525L655.857 460.03c30.086 18.552 39.435 57.982 20.882 88.067a64 64 0 0 1-21.324 21.152L482.151 674.17c-30.235 18.308-69.587 8.64-87.896-21.594A64 64 0 0 1 385 619.425V407.657c0-35.346 28.654-64 64-64z m1.196 74.49a8 8 0 0 0-1.196 4.207v183.432a8 8 0 0 0 12.15 6.84l149.688-90.851a8 8 0 0 0 0.057-13.643L461.208 415.55a8 8 0 0 0-11.012 2.595z" p-id="19997"></path></svg>
                    </div>
                </div>';
    }, $text);
    return $text;
}

/* 回复可见短代码 [hide] —— 与 2.0 的 {hide} 保持一致的评论/登录可见逻辑 */
function _compatShortHide($text, $post, $login)
{
    if (strpos($text, '[hide]') === false) {
        return $text;
    }
    $visible = $login;
    if (!$visible && $post) {
        $db = Typecho_Db::get();
        $hasComment = $db->fetchAll($db->select()->from('table.comments')->where('cid = ?', $post->cid)->where('mail = ?', $post->remember('mail', true))->limit(1));
        $visible = (bool) $hasComment;
    }
    if ($visible) {
        $text = preg_replace('/<p>\[hide\](.*?)\[\/hide\]<\/p>/ism', '$1', $text);
        $text = str_replace(array('[hide]', '[/hide]'), '', $text);
    } else {
        $text = preg_replace('/<p>\[hide\](.*?)\[\/hide\]<\/p>/ism', '<joe-hide></joe-hide>', $text);
        $text = preg_replace('/\[hide\](.*?)\[\/hide\]/ism', '<joe-hide></joe-hide>', $text);
    }
    return $text;
}

/**
 * 兼容解析主入口：将 Joe1.0 的 [xxx] 短代码翻译为 1.0 的 j-* HTML
 *
 * @param string $content 已由 Typecho 解析为 HTML 的文章内容
 * @param object $post    当前文章 Widget（用于 [hide] 的评论可见判断）
 * @param bool   $login   当前访客是否为已登录用户
 * @return string
 */
function _parseCompat($content, $post = null, $login = false)
{
    /* 无 1.0 短代码时直接返回，避免无谓的正则开销 */
    if (strpos($content, '[') === false) {
        return $content;
    }
    $content = _compatShortHide($content, $post, $login);
    $content = _compatShortPhoto($content);
    $content = _compatShortTag($content);
    $content = _compatShortButton($content);
    $content = _compatShortAlt($content);
    $content = _compatShortLine($content);
    $content = _compatShortTabs($content);
    $content = _compatShortCardDefault($content);
    $content = _compatShortCollapse($content);
    $content = _compatShortTimeLine($content);
    $content = _compatShortCopy($content);
    $content = _compatShortTyping($content);
    $content = _compatShortCardNav($content);
    $content = _compatShortDplayer($content);
    $content = _compatShortPlyr($content);
    $content = _compatShortMusic($content);
    $content = _compatShortMusicList($content);
    $content = _compatShortVideoList($content);
    return $content;
}
