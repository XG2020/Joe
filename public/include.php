<?php $this->need('public/config.php'); ?>
<?php
/* CDN/本地资源基址：JCdnStatus 关闭时用本地 assets/cdn/；开启时优先自定义 CDN 源，否则回退 fastly */
$JoeCdnUrl = trim((string) $this->options->JCdnUrl);
$JoeCdn = ($this->options->JCdnStatus === 'off')
    ? rtrim($this->options->themeUrl, '/') . '/assets/cdn/'
    : ($JoeCdnUrl !== '' ? rtrim($JoeCdnUrl, '/') . '/' : 'https://fastly.jsdelivr.net/');
?>
<meta charset="utf-8" />
<meta name="renderer" content="webkit" />
<meta name="format-detection" content="email=no" />
<meta name="format-detection" content="telephone=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
<meta itemprop="image" content="<?php $this->options->JShare_QQ_Image() ?>" />
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, shrink-to-fit=no, viewport-fit=cover">
<link rel="shortcut icon" href="<?php $this->options->JFavicon() ?>" />
<title><?php $this->archiveTitle(array('category' => '分类 %s 下的文章', 'search' => '包含关键字 %s 的文章', 'tag' => '标签 %s 下的文章', 'author' => '%s 发布的文章'), '', ' - '); ?><?php $this->options->title(); ?></title>
<?php if ($this->is('single')) : ?>
    <meta name="keywords" content="<?php echo $this->fields->keywords ? $this->fields->keywords : htmlspecialchars($this->_keywords); ?>" />
    <meta name="description" content="<?php echo $this->fields->description ? $this->fields->description : htmlspecialchars($this->_description); ?>" />
    <?php $this->header('keywords=&description='); ?>
<?php else : ?>
    <?php $this->header(); ?>
<?php endif; ?>
<link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/joe.min.css'); ?>">
<link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/joe.banner.min.css'); ?>">
<link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/joe.mode.min.css'); ?>">
<link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/joe.normalize.min.css'); ?>">
<link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/joe.global.min.css'); ?>">
<link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/joe.responsive.min.css'); ?>">
<link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/joe.compat.css'); ?>">
<link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/bootstrap-grid.min.css'); ?>">
<link rel="stylesheet" href="<?php echo $JoeCdn; ?>npm/typecho-joe-next@6.0.0/plugin/qmsg/qmsg.css">
<link rel="stylesheet" href="<?php echo $JoeCdn; ?>npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<link rel="stylesheet" href="<?php echo $JoeCdn; ?>npm/animate.css@3.7.2/animate.min.css" />
<link rel="stylesheet" href="<?php echo $JoeCdn; ?>npm/font-awesome@4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo $JoeCdn; ?>npm/aplayer@1.10.1/dist/APlayer.min.css">
<link rel="stylesheet" type="text/css" href="<?php $this->options->themeUrl('assets//css/alert.css'); ?>">
<script src="<?php echo $JoeCdn; ?>npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="<?php echo $JoeCdn; ?>npm/typecho-joe-next@6.0.0/plugin/scroll/joe.scroll.js"></script>
<script src="<?php echo $JoeCdn; ?>npm/lazysizes@5.3.0/lazysizes.min.js"></script>
<script src="<?php echo $JoeCdn; ?>npm/aplayer@1.10.1/dist/APlayer.min.js"></script>
<script src="<?php echo $JoeCdn; ?>npm/typecho-joe-next@6.0.0/plugin/sketchpad/joe.sketchpad.js"></script>
<script src="<?php echo $JoeCdn; ?>npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script src="<?php echo $JoeCdn; ?>npm/typecho-joe-next@6.0.0/assets/js/joe.extend.min.js"></script>
<script src="<?php echo $JoeCdn; ?>npm/typecho-joe-next@6.0.0/plugin/qmsg/qmsg.js"></script>
<script src="<?php $this->options->themeUrl('assets/js/alert.js'); ?>"></script>
<?php if ($this->options->JAside_3DTag === 'on') : ?>
    <script src="<?php echo $JoeCdn; ?>npm/typecho-joe-next@6.2.3/plugin/3dtag/3dtag.min.js"></script>
<?php endif; ?>
<script src="<?php echo $JoeCdn; ?>npm/typecho-joe-next@6.0.0/plugin/smooth/joe.smooth.js" async></script>
<?php if ($this->options->JCursorEffects && $this->options->JCursorEffects !== 'off') : ?>
    <script src="<?php $this->options->themeUrl('assets/cursor/' . $this->options->JCursorEffects); ?>" async></script>
<?php endif; ?>
<?php if ($this->options->JCursorType && $this->options->JCursorType !== 'off') : ?>
    <style>
        :root {
            cursor: url('<?php $this->options->themeUrl('assets/cur/' . $this->options->JCursorType); ?>'), auto;
        }
    </style>
<?php endif; ?>
<!-- 颜色选择器 -->
<?php if ($this->options->JGlobalThemeStatus === 'on') : ?>
    <link rel="stylesheet" href="<?php echo $JoeCdn; ?>npm/jquery-colpick@3.1.0/css/colpick.min.css" />
<?php endif; ?>
<script src="<?php $this->options->themeUrl('assets/js/joe.global.min.js?v=7.2.9'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/js/joe.short.min.js?v=7.2.9'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/js/joe.compat.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/js/joe.banner.min.js'); ?>"></script>
<?php $this->options->JCustomHeadEnd() ?>

<?php if ($this->options->JSnow === 'on') : ?>
    <script src="<?php $this->options->themeUrl('assets/js/snow.js'); ?>"></script>
<?php endif; ?>
<?php if ($this->options->JAside_Weather === 'on') : ?>
    <link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/xweather.css'); ?>" />
    <script src="<?php $this->options->themeUrl('assets/js/xweather.js'); ?>"></script>
<?php endif; ?>