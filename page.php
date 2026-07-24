<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <?php $this->need('public/include.php'); ?>
    <?php
    /* CDN/本地资源基址：JCdnStatus 关闭时用本地 assets/cdn/；开启时优先自定义 CDN 源，否则回退 fastly */
    $JoeCdnUrl = trim((string) $this->options->JCdnUrl);
    $JoeCdn = ($this->options->JCdnStatus === 'off')
        ? rtrim($this->options->themeUrl, '/') . '/assets/cdn/'
        : ($JoeCdnUrl !== '' ? rtrim($JoeCdnUrl, '/') . '/' : 'https://fastly.jsdelivr.net/');
    ?>
    <?php if ($this->options->JPrismTheme) : ?>
        <link rel="stylesheet" href="<?php $this->options->JPrismTheme() ?>">
    <?php else : ?>
        <link rel="stylesheet" href="<?php echo $JoeCdn; ?>npm/prismjs@1.23.0/themes/prism.min.css">
    <?php endif; ?>
    <script src="<?php echo $JoeCdn; ?>npm/clipboard@2.0.6/dist/clipboard.min.js"></script>
    <script src="<?php echo $JoeCdn; ?>npm/typecho-joe-next@6.2.4/plugin/prism/prism.min.js"></script>
    <script src="<?php $this->options->themeUrl('assets/js/joe.post_page.min.js'); ?>"></script>
</head>

<body>
    <div id="Joe">
        <?php $this->need('public/header.php'); ?>
        <div class="joe_container">
            <div class="joe_main">
                <div class="joe_detail" data-cid="<?php echo $this->cid ?>">
                    <?php $this->need('public/batten.php'); ?>
                    <?php $this->need('public/article.php'); ?>
                    <?php $this->need('public/handle.php'); ?>
                    <?php $this->need('public/copyright.php'); ?>
                </div>
                <?php $this->need('public/comment.php'); ?>
            </div>
            <?php $this->need('public/aside.php'); ?>
        </div>
        <?php $this->need('public/footer.php'); ?>
    </div>
</body>

</html>