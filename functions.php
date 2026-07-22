<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/* Joe核心文件 */
require_once("core/core.php");

function themeConfig($form)
{
    $_db = Typecho_Db::get();
    $_prefix = $_db->getPrefix();
    try {
        if (!array_key_exists('views', $_db->fetchRow($_db->select()->from('table.contents')->page(1, 1)))) {
            $_db->query('ALTER TABLE `' . $_prefix . 'contents` ADD `views` INT DEFAULT 0;');
        }
        if (!array_key_exists('agree', $_db->fetchRow($_db->select()->from('table.contents')->page(1, 1)))) {
            $_db->query('ALTER TABLE `' . $_prefix . 'contents` ADD `agree` INT DEFAULT 0;');
        }
    } catch (Exception $e) {
    }
?>
    <link rel="stylesheet" href="<?php Helper::options()->themeUrl('typecho/config/css/joe.config.min.css') ?>">
    <script src="<?php Helper::options()->themeUrl('typecho/config/js/joe.config.min.js') ?>"></script>
    <div class="joe_config">
        <div>
            <div class="joe_config__aside">
                <div class="logo">Joe <?php echo _getVersion() ?></div>
                <ul class="tabs">
                    <li class="item" data-current="joe_notice">最新公告</li>
                    <li class="item" data-current="joe_global">全局设置</li>
                    <li class="item" data-current="joe_image">图片设置</li>
                    <li class="item" data-current="joe_post">文章设置</li>
                    <li class="item" data-current="joe_aside">侧栏设置</li>
                    <li class="item" data-current="joe_index">首页设置</li>
                    <li class="item" data-current="joe_other">其他设置</li>
                </ul>
                <?php require_once('core/backup.php'); ?>
            </div>
        </div>
        <span id="joe_version" style="display: none;"><?php echo _getVersion() ?></span>
        <div class="joe_config__notice">请求数据中...</div>
    <?php
    $JFavicon = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JFavicon',
        NULL,
        NULL,
        '网站 Favicon 设置',
        '介绍：用于设置网站 Favicon，一个好的 Favicon 可以给用户一种很专业的观感 <br />
         格式：图片 URL地址 或 Base64 地址 <br />
         其他：免费转换 Favicon 网站 <a target="_blank" href="//tool.lu/favicon">tool.lu/favicon</a>'
    );
    $JFavicon->setAttribute('class', 'joe_content joe_image');
    $form->addInput($JFavicon);

    $JLogo = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JLogo',
        NULL,
        'https://xggm.top/logo.svg',
        '网站 Logo 设置',
        '介绍：用于设置网站 Logo，一个好的 Logo 能为网站带来有效的流量 <br />
         格式：图片 URL地址 或 Base64 地址 <br />
         其他：免费制作 logo 网站 <a target="_blank" href="//www.uugai.com">www.uugai.com</a>'
    );
    $JLogo->setAttribute('class', 'joe_content joe_image');
    $form->addInput($JLogo);

    $JCommentStatus = new Typecho_Widget_Helper_Form_Element_Select(
        'JCommentStatus',
        array(
            'on' => '开启（默认）',
            'off' => '关闭'
        ),
        '3',
        '开启或关闭全站评论',
        '介绍：用于一键开启关闭所有页面的评论 <br>
         注意：此处的权重优先级最高 <br>
         若关闭此项而文章内开启评论，评论依旧为关闭状态'
    );
    $JCommentStatus->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JCommentStatus->multiMode());

    $JNavMaxNum = new Typecho_Widget_Helper_Form_Element_Select(
        'JNavMaxNum',
        array(
            '3' => '3个（默认）',
            '4' => '4个',
            '5' => '5个',
            '6' => '6个',
            '7' => '7个',
        ),
        '3',
        '选择导航栏最大显示的个数',
        '介绍：用于设置最大多少个后，以更多下拉框显示'
    );
    $JNavMaxNum->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JNavMaxNum->multiMode());

    $JCustomNavs = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JCustomNavs',
        NULL,
        NULL,
        '导航栏自定义链接（非必填）',
        '介绍：用于自定义导航栏链接 <br />
         格式：跳转文字 || 跳转链接（中间使用两个竖杠分隔）<br />
         其他：一行一个，一行代表一个超链接 <br />
         例如：<br />
            百度一下 || https://baidu.com <br />
            腾讯视频 || https://v.qq.com
         '
    );
    $JCustomNavs->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JCustomNavs);

    $JList_Animate = new Typecho_Widget_Helper_Form_Element_Select(
        'JList_Animate',
        array(
            'off' => '关闭（默认）',
            'bounce' => 'bounce',
            'flash' => 'flash',
            'pulse' => 'pulse',
            'rubberBand' => 'rubberBand',
            'headShake' => 'headShake',
            'swing' => 'swing',
            'tada' => 'tada',
            'wobble' => 'wobble',
            'jello' => 'jello',
            'heartBeat' => 'heartBeat',
            'bounceIn' => 'bounceIn',
            'bounceInDown' => 'bounceInDown',
            'bounceInLeft' => 'bounceInLeft',
            'bounceInRight' => 'bounceInRight',
            'bounceInUp' => 'bounceInUp',
            'bounceOut' => 'bounceOut',
            'bounceOutDown' => 'bounceOutDown',
            'bounceOutLeft' => 'bounceOutLeft',
            'bounceOutRight' => 'bounceOutRight',
            'bounceOutUp' => 'bounceOutUp',
            'fadeIn' => 'fadeIn',
            'fadeInDown' => 'fadeInDown',
            'fadeInDownBig' => 'fadeInDownBig',
            'fadeInLeft' => 'fadeInLeft',
            'fadeInLeftBig' => 'fadeInLeftBig',
            'fadeInRight' => 'fadeInRight',
            'fadeInRightBig' => 'fadeInRightBig',
            'fadeInUp' => 'fadeInUp',
            'fadeInUpBig' => 'fadeInUpBig',
            'fadeOut' => 'fadeOut',
            'fadeOutDown' => 'fadeOutDown',
            'fadeOutDownBig' => 'fadeOutDownBig',
            'fadeOutLeft' => 'fadeOutLeft',
            'fadeOutLeftBig' => 'fadeOutLeftBig',
            'fadeOutRight' => 'fadeOutRight',
            'fadeOutRightBig' => 'fadeOutRightBig',
            'fadeOutUp' => 'fadeOutUp',
            'fadeOutUpBig' => 'fadeOutUpBig',
            'flip' => 'flip',
            'flipInX' => 'flipInX',
            'flipInY' => 'flipInY',
            'flipOutX' => 'flipOutX',
            'flipOutY' => 'flipOutY',
            'rotateIn' => 'rotateIn',
            'rotateInDownLeft' => 'rotateInDownLeft',
            'rotateInDownRight' => 'rotateInDownRight',
            'rotateInUpLeft' => 'rotateInUpLeft',
            'rotateInUpRight' => 'rotateInUpRight',
            'rotateOut' => 'rotateOut',
            'rotateOutDownLeft' => 'rotateOutDownLeft',
            'rotateOutDownRight' => 'rotateOutDownRight',
            'rotateOutUpLeft' => 'rotateOutUpLeft',
            'rotateOutUpRight' => 'rotateOutUpRight',
            'hinge' => 'hinge',
            'jackInTheBox' => 'jackInTheBox',
            'rollIn' => 'rollIn',
            'rollOut' => 'rollOut',
            'zoomIn' => 'zoomIn',
            'zoomInDown' => 'zoomInDown',
            'zoomInLeft' => 'zoomInLeft',
            'zoomInRight' => 'zoomInRight',
            'zoomInUp' => 'zoomInUp',
            'zoomOut' => 'zoomOut',
            'zoomOutDown' => 'zoomOutDown',
            'zoomOutLeft' => 'zoomOutLeft',
            'zoomOutRight' => 'zoomOutRight',
            'zoomOutUp' => 'zoomOutUp',
            'slideInDown' => 'slideInDown',
            'slideInLeft' => 'slideInLeft',
            'slideInRight' => 'slideInRight',
            'slideInUp' => 'slideInUp',
            'slideOutDown' => 'slideOutDown',
            'slideOutLeft' => 'slideOutLeft',
            'slideOutRight' => 'slideOutRight',
            'slideOutUp' => 'slideOutUp',
        ),
        'off',
        '选择一款炫酷的列表动画',
        '介绍：开启后，列表将会显示所选择的炫酷动画'
    );
    $JList_Animate->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JList_Animate->multiMode());

    $JFooter_Left = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JFooter_Left',
        NULL,
        '2020 - 2026 © Reach - <a href="https://xggm.top" target="_blank" rel="noopener noreferrer">XG.GM</a>',
        '自定义底部栏左侧内容（非必填）',
        '介绍：用于修改全站底部左侧内容（wap端上方） <br>
         例如：2020 - 2026 © Reach - Joe             '
    );
    $JFooter_Left->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JFooter_Left);

    $JFooter_Right = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JFooter_Right',
        NULL,
        NULL,
        '自定义底部栏右侧内容（非必填）',
        '介绍：用于修改全站底部右侧内容（wap端下方） <br>
         例如：&lt;a href="/"&gt;首页&lt;/a&gt; &lt;a href="/"&gt;关于&lt;/a&gt;'
    );
    $JFooter_Right->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JFooter_Right);

    $JLive2d = new Typecho_Widget_Helper_Form_Element_Select(
        'JLive2d',
        array(
            'off' => '关闭（默认）',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-shizuku@1.0.5/assets/shizuku.model.json' => 'shizuku',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-izumi@1.0.5/assets/izumi.model.json' => 'izumi',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-haru@1.0.5/01/assets/haru01.model.json' => 'haru01',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-haru@1.0.5/02/assets/haru02.model.json' => 'haru02',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-wanko@1.0.5/assets/wanko.model.json' => 'wanko',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-hijiki@1.0.5/assets/hijiki.model.json' => 'hijiki',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-koharu@1.0.5/assets/koharu.model.json' => 'koharu',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-z16@1.0.5/assets/z16.model.json' => 'z16',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-haruto@1.0.5/assets/haruto.model.json' => 'haruto',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-tororo@1.0.5/assets/tororo.model.json' => 'tororo',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-chitose@1.0.5/assets/chitose.model.json' => 'chitose',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-miku@1.0.5/assets/miku.model.json' => 'miku',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-epsilon2_1@1.0.5/assets/Epsilon2.1.model.json' => 'Epsilon2.1',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-unitychan@1.0.5/assets/unitychan.model.json' => 'unitychan',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-nico@1.0.5/assets/nico.model.json' => 'nico',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-rem@1.0.1/assets/rem.model.json' => 'rem',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-nito@1.0.5/assets/nito.model.json' => 'nito',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-nipsilon@1.0.5/assets/nipsilon.model.json' => 'nipsilon',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-ni-j@1.0.5/assets/ni-j.model.json' => 'ni-j',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-nietzsche@1.0.5/assets/nietzche.model.json' => 'nietzche',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-platelet@1.1.0/assets/platelet.model.json' => 'platelet',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-isuzu@1.0.4/assets/model.json' => 'isuzu',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-jth@1.0.0/assets/model/katou_01/katou_01.model.json' => 'katou_01',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-mikoto@1.0.0/assets/mikoto.model.json' => 'mikoto',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-mashiro-seifuku@1.0.1/assets/seifuku.model.json' => 'seifuku',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-ichigo@1.0.1/assets/ichigo.model.json' => 'ichigo',
            'https://fastly.jsdelivr.net/npm/live2d-widget-model-hk_fos@1.0.0/assets/hk416.model.json' => 'hk416'
        ),
        'off',
        '选择一款喜爱的Live2D动态人物模型（仅在屏幕分辨率大于1760px下显示）',
        '介绍：开启后会在右下角显示一个小人'
    );
    $JLive2d->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JLive2d->multiMode());

    $JDocumentTitle = new Typecho_Widget_Helper_Form_Element_Text(
        'JDocumentTitle',
        NULL,
        NULL,
        '网页被隐藏时显示的标题',
        '介绍：在PC端切换网页标签时，网站标题显示的内容。如果不填写，则默认不开启 <br />
         注意：严禁加单引号或双引号！！！否则会导致网站出错！！'
    );
    $JDocumentTitle->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JDocumentTitle);

    $JCursorEffects = new Typecho_Widget_Helper_Form_Element_Select(
        'JCursorEffects',
        array(
            'off' => '关闭（默认）',
            'cursor1.js' => '效果1',
            'cursor2.js' => '效果2',
            'cursor3.js' => '效果3',
            'cursor4.js' => '效果4',
            'cursor5.js' => '效果5',
            'cursor6.js' => '效果6',
            'cursor7.js' => '效果7',
            'cursor8.js' => '效果8',
            'cursor9.js' => '效果9',
            'cursor10.js' => '效果10',
            'cursor11.js' => '效果11',
        ),
        'off',
        '选择鼠标特效',
        '介绍：用于开启炫酷的鼠标特效'
    );
    $JCursorEffects->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JCursorEffects->multiMode());

    $JCursorType = new Typecho_Widget_Helper_Form_Element_Select(
        'JCursorType',
        array(
            'off' => '默认样式（默认）',
            'cursor1.cur' => '风格1',
            'cursor2.cur' => '风格2',
            'cursor3.cur' => '风格3',
            'cursor4.cur' => '风格4',
            'cursor5.cur' => '风格5',
            'cursor6.cur' => '风格6',
        ),
        'off',
        '是否开启自定义鼠标风格（仅限PC）',
        '介绍：选择一款您所喜欢的鼠标默认样式。'
    );
    $JCursorType->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JCursorType->multiMode());

    $JCustomCSS = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JCustomCSS',
        NULL,
        NULL,
        '自定义CSS（非必填）',
        '介绍：请填写自定义CSS内容，填写时无需填写style标签。<br />
         其他：如果想修改主题色、卡片透明度等，都可以通过这个实现 <br />
         例如：body { --theme: #ff6800; --background: rgba(255,255,255,0.85) }'
    );
    $JCustomCSS->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JCustomCSS);

    $JCustomScript = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JCustomScript',
        NULL,
        NULL,
        '自定义JS（非必填）',
        '介绍：请填写自定义JS内容，例如网站统计等，填写时无需填写script标签。'
    );
    $JCustomScript->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JCustomScript);

    $JCustomHeadEnd = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JCustomHeadEnd',
        NULL,
        NULL,
        '自定义增加&lt;head&gt;&lt;/head&gt;里内容（非必填）',
        '介绍：此处用于在&lt;head&gt;&lt;/head&gt;标签里增加自定义内容 <br />
         例如：可以填写引入第三方css、js等等'
    );
    $JCustomHeadEnd->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JCustomHeadEnd);

    $JCustomBodyEnd = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JCustomBodyEnd',
        NULL,
        NULL,
        '自定义&lt;body&gt;&lt;/body&gt;末尾位置内容（非必填）',
        '介绍：此处用于填写在&lt;body&gt;&lt;/body&gt;标签末尾位置的内容 <br>
         例如：可以填写引入第三方js脚本等等'
    );
    $JCustomBodyEnd->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JCustomBodyEnd);

    $JBirthDay = new Typecho_Widget_Helper_Form_Element_Text(
        'JBirthDay',
        NULL,
        NULL,
        '网站成立日期（非必填）',
        '介绍：用于显示当前站点已经运行了多少时间。<br>
         注意：填写时务必保证填写正确！例如：2021/1/1 00:00:00 <br>
         其他：不填写则不显示，若填写错误，则不会显示计时'
    );
    $JBirthDay->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JBirthDay);

    $JCustomFont = new Typecho_Widget_Helper_Form_Element_Text(
        'JCustomFont',
        NULL,
        NULL,
        '自定义网站字体（非必填）',
        '介绍：用于修改全站字体，填写则使用引入的字体，不填写使用默认字体 <br>
         格式：字体URL链接（推荐使用woff格式的字体，网页专用字体格式） <br>
         注意：字体文件一般有几兆，建议使用cdn链接'
    );
    $JCustomFont->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JCustomFont);

    $JCustomAvatarSource = new Typecho_Widget_Helper_Form_Element_Text(
        'JCustomAvatarSource',
        NULL,
        NULL,
        '自定义头像源（非必填）',
        '介绍：用于修改全站头像源地址 <br>
         例如：https://gravatar.ihuan.me/avatar/ <br>
         其他：非必填，默认头像源为https://gravatar.helingqi.com/wavatar/ <br>
         注意：填写时，务必保证最后有一个/字符，否则不起作用！'
    );
    $JCustomAvatarSource->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JCustomAvatarSource);

    $JGlobalThemeColor = new Typecho_Widget_Helper_Form_Element_Text(
        'JGlobalThemeColor',
        NULL,
        NULL,
        '网站默认主题色（非必填，填写时请务必按格式填写正确！例如：#ff6800）',
        '介绍：用户第一次进入页面，或者是在前台没有选择过颜色时候的默认主题色 <br />
         格式：颜色值（例如：#ff6800），若填写请务必按照格式填写，否则会导致网站主题色无法显示！！！'
    );
    $JGlobalThemeColor->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JGlobalThemeColor);

    $JGlobalThemeStatus = new Typecho_Widget_Helper_Form_Element_Select(
        'JGlobalThemeStatus',
        array('off' => '关闭（默认）', 'on' => '开启'),
        'off',
        '是否开启自定义主题',
        '介绍：本模板的特色，采用最新CSS var语法，无任何性能消耗，极其推荐开启 <br />
         注意：不兼容垃圾IE'
    );
    $JGlobalThemeStatus->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JGlobalThemeStatus->multiMode());

    $aidao_ri = new Typecho_Widget_Helper_Form_Element_Select(
        'aidao_ri',
        array('off' => '关闭（默认）', 'on' => '开启'),
        'off',
        '是否开启哀悼模式',
        '开启后全站都显示灰色，为逝者默哀！'
    );
    $aidao_ri->setAttribute('class', 'joe_content joe_global');
    $form->addInput($aidao_ri->multiMode());

    $denglong = new Typecho_Widget_Helper_Form_Element_Text(
        'denglong',
        NULL,
        NULL,
        '顶栏大红灯笼',
        '介绍：填写内容将会在页面右上角显示一个喜气洋洋的大红灯笼，不填写则不显示。<br />
         格式：内容 || 内容 （中间使用两个竖杠分隔）<br />
         例如：喜||迎||新||春 <br />
         注意：每个内容最多为一个汉字'
    );
    $denglong->setAttribute('class', 'joe_content joe_global');
    $form->addInput($denglong);

    $JSnow = new Typecho_Widget_Helper_Form_Element_Select(
        'JSnow',
        array('off' => '关闭（默认）', 'on' => '开启'),
        'off',
        '是否开启雪花飘落效果',
        '介绍：开启后所有页面都有雪花飘落特效<br />
         注意：如果修改了主题文件夹名称，需要修改 主题文件名\assets\js\snow.js 里的ice.png引入路径'
    );
    $JSnow->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JSnow->multiMode());

    $copying = new Typecho_Widget_Helper_Form_Element_Text(
        'copying',
        NULL,
        NULL,
        '复制内容提醒',
        '介绍：输入自定义文本开启复制内容提醒，留空则关闭。例如：若要转载请务必保留原文链接，申明来源，谢谢合作！'
    );
    $copying->setAttribute('class', 'joe_content joe_global');
    $form->addInput($copying);

    $JProgressStatus = new Typecho_Widget_Helper_Form_Element_Select(
        'JProgressStatus',
        array('off' => '关闭（默认）', 'on' => '开启'),
        'off',
        '是否开启或关闭页面进度条',
        '介绍：开启后，网站头部将会显示进度条，该进度条与页面长度成对应关系，页面滚动多少，那么进度条的宽度就是多少。'
    );
    $JProgressStatus->setAttribute('class', 'joe_content joe_global');
    $form->addInput($JProgressStatus->multiMode());

    $JAside_Author_Nick = new Typecho_Widget_Helper_Form_Element_Text(
        'JAside_Author_Nick',
        NULL,
        "XG.孤梦",
        '博主栏博主昵称 - PC/WAP',
        '介绍：用于修改博主栏的博主昵称 <br />
         注意：如果不填写时则显示 *个人设置* 里的昵称'
    );
    $JAside_Author_Nick->setAttribute('class', 'joe_content joe_aside');
    $form->addInput($JAside_Author_Nick);
    /* --------------------------------------- */
    $JAside_Author_Avatar = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JAside_Author_Avatar',
        NULL,
        NULL,
        '博主栏博主头像 - PC/WAP',
        '介绍：用于修改博主栏的博主头像 <br />
         注意：如果不填写时则显示 *个人设置* 里的头像'
    );
    $JAside_Author_Avatar->setAttribute('class', 'joe_content joe_aside');
    $form->addInput($JAside_Author_Avatar);
    /* --------------------------------------- */
    $JAside_Author_Image = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JAside_Author_Image',
        NULL,
        "https://fastly.jsdelivr.net/npm/typecho-joe-next@6.0.0/assets/img/aside_author_image.jpg",
        '博主栏背景壁纸 - PC',
        '介绍：用于修改PC端博主栏的背景壁纸 <br/>
         格式：图片地址 或 Base64地址'
    );
    $JAside_Author_Image->setAttribute('class', 'joe_content joe_aside');
    $form->addInput($JAside_Author_Image);
    /* --------------------------------------- */
    $JAside_Wap_Image = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JAside_Wap_Image',
        NULL,
        "https://fastly.jsdelivr.net/npm/typecho-joe-next@6.0.0/assets/img/wap_aside_image.jpg",
        '博主栏背景壁纸 - WAP',
        '介绍：用于修改WAP端博主栏的背景壁纸 <br/>
         格式：图片地址 或 Base64地址'
    );
    $JAside_Wap_Image->setAttribute('class', 'joe_content joe_aside');
    $form->addInput($JAside_Wap_Image);
    /* --------------------------------------- */
    $JAside_Author_Link = new Typecho_Widget_Helper_Form_Element_Text(
        'JAside_Author_Link',
        NULL,
        NULL,
        '博主栏昵称跳转地址 - PC/WAP',
        '介绍：用于修改博主栏点击博主昵称后的跳转地址'
    );
    $JAside_Author_Link->setAttribute('class', 'joe_content joe_aside');
    $form->addInput($JAside_Author_Link);
    /* --------------------------------------- */
    $JAside_Author_Motto = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JAside_Author_Motto',
        NULL,
        "随风而动，随遇而安......",
        '博主栏座右铭（一言）- PC/WAP',
        '介绍：用于修改博主栏的座右铭（一言） <br />
         格式：可以填写多行也可以填写一行，填写多行时，每次随机显示其中的某一条，也可以填写API地址 <br />
         其他：API和自定义的座右铭完全可以一起写（换行填写），不会影响 <br />
         注意：API需要开启跨域权限才能调取，否则会调取失败！<br />
         推荐API：https://api.vvhan.com/api/ian'
    );
    $JAside_Author_Motto->setAttribute('class', 'joe_content joe_aside');
    $form->addInput($JAside_Author_Motto);
    /* --------------------------------------- */
    $JAside_Author_Nav = new Typecho_Widget_Helper_Form_Element_Select(
        'JAside_Author_Nav',
        array(
            'off' => '关闭（默认）',
            '3' => '开启，并显示3条最新文章',
            '4' => '开启，并显示4条最新文章',
            '5' => '开启，并显示5条最新文章',
            '6' => '开启，并显示6条最新文章',
            '7' => '开启，并显示7条最新文章',
            '8' => '开启，并显示8条最新文章',
            '9' => '开启，并显示9条最新文章',
            '10' => '开启，并显示10条最新文章'
        ),
        'off',
        '博主栏下方随机文章条目 - PC',
        '介绍：用于设置博主栏下方的随机文章显示数量'
    );
    $JAside_Author_Nav->setAttribute('class', 'joe_content joe_aside');
    $form->addInput($JAside_Author_Nav->multiMode());
    /* --------------------------------------- */
    $JRanking = new Typecho_Widget_Helper_Form_Element_Select(
        'JRanking',
        array(
            'off' => '关闭（默认）',
            'bilibili热门视频$bilibili' => 'bilibili热门视频',
            'bilibili番剧排行榜$bilibili_fan' => 'bilibili番剧排行榜',
            'bilibili视频排行榜$bilibili$video' => 'bilibili视频排行榜',
        ),
        'off',
        '选择一款您想展示的排行榜',
        '介绍：开启后侧边栏将显示您所选择的排行榜 <br>
         注意：开启可能会导致网址变慢！！！
        '
    );
    $JRanking->setAttribute('class', 'joe_content joe_aside');
    $form->addInput($JRanking->multiMode());
    /* --------------------------------------- */
    $JAside_Timelife_Status = new Typecho_Widget_Helper_Form_Element_Select(
        'JAside_Timelife_Status',
        array(
            'off' => '关闭（默认）',
            'on' => '开启'
        ),
        'off',
        '是否开启人生倒计时模块 - PC',
        '介绍：用于控制是否显示人生倒计时模块'
    );
    $JAside_Timelife_Status->setAttribute('class', 'joe_content joe_aside');
    $form->addInput($JAside_Timelife_Status->multiMode());
    /* --------------------------------------- */
    $JAside_Hot_Num = new Typecho_Widget_Helper_Form_Element_Select(
        'JAside_Hot_Num',
        array(
            'off' => '关闭（默认）',
            '3' => '显示3条',
            '4' => '显示4条',
            '5' => '显示5条',
            '6' => '显示6条',
            '7' => '显示7条',
            '8' => '显示8条',
            '9' => '显示9条',
            '10' => '显示10条',
        ),
        'off',
        '是否开启热门文章栏 - PC',
        '介绍：用于控制是否开启热门文章栏'
    );
    $JAside_Hot_Num->setAttribute('class', 'joe_content joe_aside');
    $form->addInput($JAside_Hot_Num->multiMode());
    /* --------------------------------------- */
    $JAside_Newreply_Status = new Typecho_Widget_Helper_Form_Element_Select(
        'JAside_Newreply_Status',
        array(
            'off' => '关闭（默认）',
            'on' => '开启'
        ),
        'off',
        '是否开启最新回复栏 - PC',
        '介绍：用于控制是否开启最新回复栏 <br>
         注意：如果您关闭了全站评论，将不会显示最新回复！'
    );
    $JAside_Newreply_Status->setAttribute('class', 'joe_content joe_aside');
    $form->addInput($JAside_Newreply_Status->multiMode());
    /* --------------------------------------- */
    $JAside_Weather_IKey = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JAside_Weather_IKey',
        NULL,
        NULL,
        '天气栏ID/KEY值 - PC',
        '介绍：用于初始化天气栏 <br/>
         注意：填写格式：ID || KEY (中间使用两个竖杠分隔)务必填写正确！不填写则不会显示<br />
         其他：免费申请地址：<a href="//www.apihz.cn/user/">点击申请</a><br />
         简要：为空默认使用公共ID与KEY值88888888，公共ID共享每分钟调用频次,推荐填写自己的ID与KEY值'
    );
    $JAside_Weather_IKey->setAttribute('class', 'joe_content joe_aside');
    $form->addInput($JAside_Weather_IKey);
    /* --------------------------------------- */
    $JAside_Weather = new Typecho_Widget_Helper_Form_Element_Select(
        'JAside_Weather',
        array(
            'off' => '关闭（默认）',
            'on' => '开启'
        ),
        'off',
        '侧边栏天气 - PC',
        '介绍：开启后侧边栏显示天气挂件 <br />
         注意：需要先填写推荐填写自己的ID与KEY值'
    );
    $JAside_Weather->setAttribute('class', 'joe_content joe_aside');
    $form->addInput($JAside_Weather->multiMode());
    /* --------------------------------------- */
    $JADContent = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JADContent',
        NULL,
        NULL,
        '侧边栏广告 - PC',
        '介绍：用于设置侧边栏广告 <br />
         格式：广告图片 || 跳转链接 （中间使用两个竖杠分隔）<br />
         注意：如果您只想显示图片不想跳转，可填写：广告图片 || javascript:void(0)'
    );
    $JADContent->setAttribute('class', 'joe_content joe_aside');
    $form->addInput($JADContent);
    /* --------------------------------------- */
    $JCustomAside = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JCustomAside',
        NULL,
        NULL,
        '自定义侧边栏模块 - PC',
        '介绍：用于自定义侧边栏模块 <br />
         格式：请填写前端代码，不会写请勿填写 <br />
         例如：您可以在此处添加一个搜索框、时间、宠物、恋爱计时等等'
    );
    $JCustomAside->setAttribute('class', 'joe_content joe_aside');
    $form->addInput($JCustomAside);
    /* --------------------------------------- */
    $JAside_3DTag = new Typecho_Widget_Helper_Form_Element_Select(
        'JAside_3DTag',
        array(
            'off' => '关闭（默认）',
            'on' => '开启'
        ),
        'off',
        '是否开启3D云标签 - PC',
        '介绍：用于设置侧边栏是否显示3D云标签'
    );
    $JAside_3DTag->setAttribute('class', 'joe_content joe_aside');
    $form->addInput($JAside_3DTag->multiMode());
    /* --------------------------------------- */
    $JAside_Flatterer = new Typecho_Widget_Helper_Form_Element_Select(
        'JAside_Flatterer',
        array(
            'off' => '关闭（默认）',
            'on' => '开启'
        ),
        'off',
        '是否开启舔狗日记 - PC',
        '介绍：用于设置侧边栏是否显示舔狗日记'
    );
    $JAside_Flatterer->setAttribute('class', 'joe_content joe_aside');
    $form->addInput($JAside_Flatterer->multiMode());
    /* --------------------------------------- */
    $JAside_History_Today = new Typecho_Widget_Helper_Form_Element_Select(
        'JAside_History_Today',
        array(
            'off' => '关闭（默认）',
            'on' => '开启'
        ),
        'off',
        '是否开启那年今日 - PC',
        '介绍：用于设置侧边栏是否显示往年今日的文章 <br />
         其他：如果往年今日有文章则显示，没有则不显示！'
    );
    $JAside_History_Today->setAttribute('class', 'joe_content joe_aside');
    $form->addInput($JAside_History_Today->multiMode());


    $JThumbnail = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JThumbnail',
        NULL,
        NULL,
        '自定义缩略图',
        '介绍：用于修改主题默认缩略图 <br/>
         格式：图片地址，一行一个 <br />
         注意：不填写时，则使用主题内置的默认缩略图
         '
    );
    $JThumbnail->setAttribute('class', 'joe_content joe_image');
    $form->addInput($JThumbnail);

    $JLazyload = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JLazyload',
        NULL,
        "https://fastly.jsdelivr.net/npm/typecho-joe-next@6.0.0/assets/img/lazyload.jpg",
        '自定义懒加载图',
        '介绍：用于修改主题默认懒加载图 <br/>
         格式：图片地址'
    );
    $JLazyload->setAttribute('class', 'joe_content joe_image');
    $form->addInput($JLazyload);

    $JDynamic_Background = new Typecho_Widget_Helper_Form_Element_Select(
        'JDynamic_Background',
        array(
            'off' => '关闭（默认）',
            'backdrop1.js' => '效果1',
            'backdrop2.js' => '效果2',
            'backdrop3.js' => '效果3',
            'backdrop4.js' => '效果4',
            'backdrop5.js' => '效果5',
            'backdrop6.js' => '效果6'
        ),
        'off',
        '是否开启动态背景图（仅限PC）',
        '介绍：用于设置PC端动态背景<br />
         注意：如果您填写了下方PC端静态壁纸，将优先展示下方静态壁纸！如需显示动态壁纸，请将PC端静态壁纸设置成空'
    );
    $JDynamic_Background->setAttribute('class', 'joe_content joe_image');
    $form->addInput($JDynamic_Background->multiMode());

    $JBig_Banner = new Typecho_Widget_Helper_Form_Element_Select(
        'JBig_Banner',
        array(
            'off' => '关闭（默认）',
            'on' => '开启'
        ),
        'off',
        '是否开启首页大图',
        '介绍：开启后显示首页随机大图'
    );
    $JBig_Banner->setAttribute('class', 'joe_content joe_image');
    $form->addInput($JBig_Banner->multiMode());

    $JBig_Banner_Url = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JBig_Banner_Url',
        NULL,
        'https://api.btstu.cn/sjbz/api.php?lx=dongman',
        '首页大图背景图片URL',
        '介绍：用于设置首页大图的背景图片URL <br />
         格式：图片URL地址 或 随机图片API地址 <br />
         例如：https://api.btstu.cn/sjbz/api.php?lx=dongman'
    );
    $JBig_Banner_Url->setAttribute('class', 'joe_content joe_image');
    $form->addInput($JBig_Banner_Url);

    $JWallpaper_Background_PC = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JWallpaper_Background_PC',
        NULL,
        NULL,
        'PC端网站背景图片（非必填）',
        '介绍：PC端网站的背景图片，不填写时显示默认的灰色。<br />
         格式：图片URL地址 或 随机图片api <br />
         例如：https://api.btstu.cn/sjbz/?lx=dongman <br />
         注意：如果需要显示上方动态壁纸，请不要填写此项，此项优先级最高！'
    );
    $JWallpaper_Background_PC->setAttribute('class', 'joe_content joe_image');
    $form->addInput($JWallpaper_Background_PC);

    $JWallpaper_Background_WAP = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JWallpaper_Background_WAP',
        NULL,
        NULL,
        'WAP端网站背景图片（非必填）',
        '介绍：WAP端网站的背景图片，不填写时显示默认的灰色。<br />
         格式：图片URL地址 或 随机图片api 例如：https://api.btstu.cn/sjbz/?lx=m_dongman'
    );
    $JWallpaper_Background_WAP->setAttribute('class', 'joe_content joe_image');
    $form->addInput($JWallpaper_Background_WAP);

    $JShare_QQ_Image = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JShare_QQ_Image',
        NULL,
        NULL,
        'QQ分享链接图片',
        '介绍：用于修改在QQ内分享时卡片链接显示的图片 <br/>
         格式：图片地址'
    );
    $JShare_QQ_Image->setAttribute('class', 'joe_content joe_image');
    $form->addInput($JShare_QQ_Image);

    $JIndex_Carousel = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JIndex_Carousel',
        NULL,
        NULL,
        '首页轮播图',
        '介绍：用于显示首页轮播图，请务必填写正确的格式 <br />
         格式：图片地址 || 跳转链接 || 标题 （中间使用两个竖杠分隔）<br />
         其他：一行一个，一行代表一个轮播图 <br />
         例如：<br />
            https://puui.qpic.cn/media_img/lena/PICykqaoi_580_1680/0 || https://baidu.com || 百度一下 <br />
            https://puui.qpic.cn/tv/0/1223447268_1680580/0 || https://v.qq.com || 腾讯视频
         '
    );
    $JIndex_Carousel->setAttribute('class', 'joe_content joe_index');
    $form->addInput($JIndex_Carousel);

    $JIndex_Recommend = new Typecho_Widget_Helper_Form_Element_Text(
        'JIndex_Recommend',
        NULL,
        NULL,
        '首页推荐文章（非必填，填写时请填写2个，否则不显示！）',
        '介绍：用于显示推荐文章，请务必填写正确的格式 <br/>
         格式：文章的id || 文章的id （中间使用两个竖杠分隔）<br />
         例如：1 || 2 <br />
         注意：如果填写的不是2个，将不会显示'
    );
    $JIndex_Recommend->setAttribute('class', 'joe_content joe_index');
    $form->addInput($JIndex_Recommend);

    $JIndexSticky = new Typecho_Widget_Helper_Form_Element_Text(
        'JIndexSticky',
        NULL,
        NULL,
        '首页置顶文章（非必填）',
        '介绍：请务必填写正确的格式 <br />
         格式：文章的ID || 文章的ID || 文章的ID （中间使用两个竖杠分隔）<br />
         例如：1 || 2 || 3'
    );
    $JIndexSticky->setAttribute('class', 'joe_content joe_index');
    $form->addInput($JIndexSticky);

    $JIndex_Hot = new Typecho_Widget_Helper_Form_Element_Radio(
        'JIndex_Hot',
        array('off' => '关闭（默认）', 'on' => '开启'),
        'off',
        '是否开启首页热门文章',
        '介绍：开启后，网站首页将会显示浏览量最多的4篇热门文章'
    );
    $JIndex_Hot->setAttribute('class', 'joe_content joe_index');
    $form->addInput($JIndex_Hot->multiMode());

    $JIndex_Ad = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JIndex_Ad',
        NULL,
        NULL,
        '首页大屏广告',
        '介绍：请务必填写正确的格式 <br />
         格式：广告图片 || 广告链接 （中间使用两个竖杠分隔，限制一个）<br />
         例如：https://puui.qpic.cn/media_img/lena/PICykqaoi_580_1680/0 || https://baidu.com'
    );
    $JIndex_Ad->setAttribute('class', 'joe_content joe_index');
    $form->addInput($JIndex_Ad);

    $JIndex_Notice = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JIndex_Notice',
        NULL,
        NULL,
        '首页通知文字（非必填）',
        '介绍：请务必填写正确的格式 <br />
         格式：通知文字 || 跳转链接（中间使用两个竖杠分隔，限制一个）<br />
         例如：欢迎加入Joe-QQ群 || https://baidu.com'
    );
    $JIndex_Notice->setAttribute('class', 'joe_content joe_index');
    $form->addInput($JIndex_Notice);

    $JCommentIp = new Typecho_Widget_Helper_Form_Element_Select(
        'JCommentIp',
        array('off' => '关闭（默认）', 'on' => '开启'),
        'off',
        '是否开启评论区IP归属地',
        '介绍：开启后评论区会显示IP归属地。<br />
         注意：IP地址归属地依赖于本地IP数据包查询，部分IP地址可能出现位置错误，请及时更新IP据包<br />
         IP数据库文件路径：/Joe/core/qqwry.dat。最新IP数据包下载地址：https://github.com/nmgliangwei/qqwry/releases'
    );
    $JCommentIp->setAttribute('class', 'joe_content joe_other');
    $form->addInput($JCommentIp->multiMode());

    $JFriends = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JFriends',
        NULL,
        '日志随记 || https://xggm.top || https://xggm.top/logo.png || 随风而动，随遇而安......',
        '友情链接（非必填）',
        '介绍：用于填写友情链接 <br />
         注意：您需要先增加友链链接页面（新增独立页面-右侧模板选择友链），该项才会生效 <br />
         格式：博客名称 || 博客地址 || 博客头像 || 博客简介 <br />
         其他：一行一个，一行代表一个友链'
    );
    $JFriends->setAttribute('class', 'joe_content joe_other');
    $form->addInput($JFriends);

    $JMaccmsAPI = new Typecho_Widget_Helper_Form_Element_Text(
        'JMaccmsAPI',
        NULL,
        NULL,
        '苹果CMS开放API',
        '介绍：请填写苹果CMS V10开放API，用于视频页面使用<br />
         例如：https://v.ini0.com/api.php/provide/vod/ <br />
         如果您搭建了苹果cms网站，那么用你自己的即可，如果没有，请去网上找API <br />
         '
    );
    $JMaccmsAPI->setAttribute('class', 'joe_content joe_other');
    $form->addInput($JMaccmsAPI);

    $JCustomPlayer = new Typecho_Widget_Helper_Form_Element_Text(
        'JCustomPlayer',
        NULL,
        NULL,
        '自定义视频播放器（非必填）',
        '介绍：用于修改主题自带的默认播放器 <br />
         例如：https://v.ini0.com/player/?url= <br />
         注意：主题自带的播放器只能解析M3U8的视频格式'
    );
    $JCustomPlayer->setAttribute('class', 'joe_content joe_other');
    $form->addInput($JCustomPlayer);

    $JSensitiveWords = new Typecho_Widget_Helper_Form_Element_Textarea(
        'JSensitiveWords',
        NULL,
        '你妈死了 || 傻逼 || 操你妈 || 射你妈一脸',
        '评论敏感词（非必填）',
        '介绍：用于设置评论敏感词汇，如果用户评论包含这些词汇，则将会把评论置为审核状态 <br />
         例如：你妈死了 || 你妈炸了 || 我是你爹 || 你妈坟头冒烟 （多个使用 || 分隔开）'
    );
    $JSensitiveWords->setAttribute('class', 'joe_content joe_other');
    $form->addInput($JSensitiveWords);

    $JLimitOneChinese = new Typecho_Widget_Helper_Form_Element_Select(
        'JLimitOneChinese',
        array('off' => '关闭（默认）', 'on' => '开启'),
        'off',
        '是否开启评论至少包含一个中文',
        '介绍：开启后如果评论内容未包含一个中文，则将会把评论置为审核状态 <br />
         其他：用于屏蔽国外机器人刷的全英文垃圾广告信息'
    );
    $JLimitOneChinese->setAttribute('class', 'joe_content joe_other');
    $form->addInput($JLimitOneChinese->multiMode());

    $JTextLimit = new Typecho_Widget_Helper_Form_Element_Text(
        'JTextLimit',
        NULL,
        NULL,
        '限制用户评论最大字符',
        '介绍：如果用户评论的内容超出字符限制，则将会把评论置为审核状态 <br />
         其他：请输入数字格式，不填写则不限制'
    );
    $JTextLimit->setAttribute('class', 'joe_content joe_other');
    $form->addInput($JTextLimit->multiMode());

    $JSiteMap = new Typecho_Widget_Helper_Form_Element_Select(
        'JSiteMap',
        array(
            'off' => '关闭（默认）',
            '100' => '显示最新 100 条链接',
            '200' => '显示最新 200 条链接',
            '300' => '显示最新 300 条链接',
            '400' => '显示最新 400 条链接',
            '500' => '显示最新 500 条链接',
            '600' => '显示最新 600 条链接',
            '700' => '显示最新 700 条链接',
            '800' => '显示最新 800 条链接',
            '900' => '显示最新 900 条链接',
            '1000' => '显示最新 1000 条链接',
        ),
        'off',
        '是否开启主题自带SiteMap功能',
        '介绍：开启后博客将享有SiteMap功能 <br />
         其他：链接为博客最新实时链接 <br />
         好处：无需手动生成，无需频繁提交，提交一次即可 <br />
         开启后SiteMap访问地址：<br />
         http(s)://域名/sitemap.xml （开启了伪静态）<br />  
         http(s)://域名/index.php/sitemap.xml （未开启伪静态）
         '
    );
    $JSiteMap->setAttribute('class', 'joe_content joe_other');
    $form->addInput($JSiteMap->multiMode());

    $JBTPanel = new Typecho_Widget_Helper_Form_Element_Text(
        'JBTPanel',
        NULL,
        NULL,
        '宝塔面板地址',
        '介绍：用于统计页面获取服务器状态使用 <br>
         例如：http://192.168.1.245:8888/ <br>
         注意：结尾需要带有一个 / 字符！<br>
         该功能需要去宝塔面板开启开放API，并添加白名单才可使用'
    );
    $JBTPanel->setAttribute('class', 'joe_content joe_other');
    $form->addInput($JBTPanel->multiMode());

    $JBTKey = new Typecho_Widget_Helper_Form_Element_Text(
        'JBTKey',
        NULL,
        NULL,
        '宝塔开放接口密钥',
        '介绍：用于统计页面获取服务器状态使用 <br>
         例如：thVLXFtUCCNzBShBweKTPBmw8296q8R8 <br>
         该功能需要去宝塔面板开启开放API，并添加白名单才可使用'
    );
    $JBTKey->setAttribute('class', 'joe_content joe_other');
    $form->addInput($JBTKey->multiMode());

    /* 评论发信 */
    $JCommentMail = new Typecho_Widget_Helper_Form_Element_Select(
        'JCommentMail',
        array('off' => '关闭（默认）', 'on' => '开启'),
        'off',
        '是否开启评论邮件通知',
        '介绍：开启后评论内容将会进行邮箱通知 <br />
         注意：此项需要您完整无错的填写下方的邮箱设置！！ <br />
         其他：下方例子以QQ邮箱为例，推荐使用QQ邮箱'
    );
    $JCommentMail->setAttribute('class', 'joe_content joe_other');
    $form->addInput($JCommentMail->multiMode());

    $JCommentMailHost = new Typecho_Widget_Helper_Form_Element_Text(
        'JCommentMailHost',
        NULL,
        NULL,
        '邮箱服务器地址',
        '例如：smtp.qq.com'
    );
    $JCommentMailHost->setAttribute('class', 'joe_content joe_other');
    $form->addInput($JCommentMailHost->multiMode());

    $JCommentSMTPSecure = new Typecho_Widget_Helper_Form_Element_Select(
        'JCommentSMTPSecure',
        array('ssl' => 'ssl（默认）', 'tsl' => 'tsl'),
        'ssl',
        '加密方式',
        '介绍：用于选择登录鉴权加密方式'
    );
    $JCommentSMTPSecure->setAttribute('class', 'joe_content joe_other');
    $form->addInput($JCommentSMTPSecure->multiMode());

    $JCommentMailPort = new Typecho_Widget_Helper_Form_Element_Text(
        'JCommentMailPort',
        NULL,
        NULL,
        '邮箱服务器端口号',
        '例如：465'
    );
    $JCommentMailPort->setAttribute('class', 'joe_content joe_other');
    $form->addInput($JCommentMailPort->multiMode());

    $JCommentMailFromName = new Typecho_Widget_Helper_Form_Element_Text(
        'JCommentMailFromName',
        NULL,
        NULL,
        '发件人昵称',
        '例如：帅气的象拔蚌'
    );
    $JCommentMailFromName->setAttribute('class', 'joe_content joe_other');
    $form->addInput($JCommentMailFromName->multiMode());

    $JCommentMailAccount = new Typecho_Widget_Helper_Form_Element_Text(
        'JCommentMailAccount',
        NULL,
        NULL,
        '发件人邮箱',
        '例如：2323333339@qq.com'
    );
    $JCommentMailAccount->setAttribute('class', 'joe_content joe_other');
    $form->addInput($JCommentMailAccount->multiMode());

    $JCommentMailPassword = new Typecho_Widget_Helper_Form_Element_Text(
        'JCommentMailPassword',
        NULL,
        NULL,
        '邮箱授权码',
        '介绍：这里填写的是邮箱生成的授权码 <br>
         获取方式（以QQ邮箱为例）：<br>
         QQ邮箱 > 设置 > 账户 > IMAP/SMTP服务 > 开启 <br>
         其他：这个可以百度一下开启教程，有图文教程'
    );
    $JCommentMailPassword->setAttribute('class', 'joe_content joe_other');
    $form->addInput($JCommentMailPassword->multiMode());

    $JBig_PostBanner = new Typecho_Widget_Helper_Form_Element_Select(
        'JBig_PostBanner',
        array(
            'off' => '关闭（默认）',
            'on' => '开启'
        ),
        'off',
        '是否文章大图',
        '介绍：开启后显示文章页大图，为文章第一张图片'
    );
    $JBig_PostBanner->setAttribute('class', 'joe_content joe_post');
    $form->addInput($JBig_PostBanner->multiMode());

    $JDirectoryStatus = new Typecho_Widget_Helper_Form_Element_Select(
        'JDirectoryStatus',
        array('off' => '关闭（默认）', 'on' => '开启'),
        'off',
        '是否开启文章页和自定义页目录树（仅限PC并且分辨率大于1570像素才会显示）',
        '介绍：开启后，文章页面和自定义页面将显示目录树（小屏幕上不会显示）'
    );
    $JDirectoryStatus->setAttribute('class', 'joe_content joe_post');
    $form->addInput($JDirectoryStatus->multiMode());

    $JBaiduToken = new Typecho_Widget_Helper_Form_Element_Text(
        'JBaiduToken',
        NULL,
        NULL,
        '百度推送Token',
        '介绍：填写此处，前台文章页如果未收录，则会自动将当前链接推送给百度加快收录 <br />
         其他：Token在百度收录平台注册账号获取'
    );
    $JBaiduToken->setAttribute('class', 'joe_content joe_post');
    $form->addInput($JBaiduToken);

    $JOverdue = new Typecho_Widget_Helper_Form_Element_Select(
        'JOverdue',
        array(
            'off' => '关闭（默认）',
            '3' => '大于3天',
            '7' => '大于7天',
            '15' => '大于15天',
            '30' => '大于30天',
            '60' => '大于60天',
            '90' => '大于90天',
            '120' => '大于120天',
            '180' => '大于180天'
        ),
        'off',
        '是否开启文章更新时间大于多少天提示（仅针对文章有效）',
        '介绍：开启后如果文章在多少天内无任何修改，则进行提示'
    );
    $JOverdue->setAttribute('class', 'joe_content joe_post');
    $form->addInput($JOverdue->multiMode());

    $JEditor = new Typecho_Widget_Helper_Form_Element_Select(
        'JEditor',
        array(
            'on' => '开启（默认）',
            'off' => '关闭',
        ),
        'on',
        '是否启用Joe自定义编辑器',
        '介绍：开启后，文章编辑器将替换成Joe编辑器 <br>
         其他：目前编辑器处于拓展阶段，如果想继续使用原生编辑器，关闭此项即可'
    );
    $JEditor->setAttribute('class', 'joe_content joe_post');
    $form->addInput($JEditor->multiMode());

    $JPrismTheme = new Typecho_Widget_Helper_Form_Element_Select(
        'JPrismTheme',
        array(
            '//fastly.jsdelivr.net/npm/prismjs@1.23.0/themes/prism.min.css' => 'prism（默认）',
            '//fastly.jsdelivr.net/npm/prismjs@1.23.0/themes/prism-dark.min.css' => 'prism-dark',
            '//fastly.jsdelivr.net/npm/prismjs@1.23.0/themes/prism-okaidia.min.css' => 'prism-okaidia',
            '//fastly.jsdelivr.net/npm/prismjs@1.23.0/themes/prism-solarizedlight.min.css' => 'prism-solarizedlight',
            '//fastly.jsdelivr.net/npm/prismjs@1.23.0/themes/prism-tomorrow.min.css' => 'prism-tomorrow',
            '//fastly.jsdelivr.net/npm/prismjs@1.23.0/themes/prism-twilight.min.css' => 'prism-twilight',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-a11y-dark.min.css' => 'prism-a11y-dark',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-atom-dark.min.css' => 'prism-atom-dark',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-base16-ateliersulphurpool.light.min.css' => 'prism-base16-ateliersulphurpool.light',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-cb.min.css' => 'prism-cb',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-coldark-cold.min.css' => 'prism-coldark-cold',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-coldark-dark.min.css' => 'prism-coldark-dark',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-darcula.min.css' => 'prism-darcula',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-dracula.min.css' => 'prism-dracula',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-duotone-dark.min.css' => 'prism-duotone-dark',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-duotone-earth.min.css' => 'prism-duotone-earth',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-duotone-forest.min.css' => 'prism-duotone-forest',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-duotone-light.min.css' => 'prism-duotone-light',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-duotone-sea.min.css' => 'prism-duotone-sea',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-duotone-space.min.css' => 'prism-duotone-space',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-ghcolors.min.css' => 'prism-ghcolors',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-gruvbox-dark.min.css' => 'prism-gruvbox-dark',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-hopscotch.min.css' => 'prism-hopscotch',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-lucario.min.css' => 'prism-lucario',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-material-dark.min.css' => 'prism-material-dark',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-material-light.min.css' => 'prism-material-light',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-material-oceanic.min.css' => 'prism-material-oceanic',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-night-owl.min.css' => 'prism-night-owl',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-nord.min.css' => 'prism-nord',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-pojoaque.min.css' => 'prism-pojoaque',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-shades-of-purple.min.css' => 'prism-shades-of-purple',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-synthwave84.min.css' => 'prism-synthwave84',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-vs.min.css' => 'prism-vs',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-vsc-dark-plus.min.css' => 'prism-vsc-dark-plus',
            '//fastly.jsdelivr.net/npm/prism-themes@1.7.0/themes/prism-xonokai.min.css' => 'prism-xonokai',
            '//fastly.jsdelivr.net/npm/prism-theme-one-light-dark@1.0.4/prism-onelight.min.css' => 'prism-onelight',
            '//fastly.jsdelivr.net/npm/prism-theme-one-light-dark@1.0.4/prism-onedark.min.css' => 'prism-onedark',
            '//fastly.jsdelivr.net/npm/prism-theme-one-dark@1.0.0/prism-onedark.min.css' => 'prism-onedark2',
        ),
        '//fastly.jsdelivr.net/npm/prismjs@1.23.0/themes/prism.min.css',
        '选择一款您喜欢的代码高亮样式',
        '介绍：用于修改代码块的高亮风格 <br>
         其他：如果您有其他样式，可通过源代码修改此项，引入您的自定义样式链接'
    );
    $JPrismTheme->setAttribute('class', 'joe_content joe_post');
    $form->addInput($JPrismTheme->multiMode());
} ?>