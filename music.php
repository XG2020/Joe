<?php

/**
 * 电台
 * 
 * @package custom 
 * 
 **/

?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <?php $this->need('public/include.php'); ?>
    <link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/joe.video.min.css'); ?>">
    <script src="<?php $this->options->themeUrl('assets/js/joe.video.min.js'); ?>"></script>
</head>

<body>
    <div id="Joe">
        <?php $this->need('public/header.php'); ?>
        <div class="joe_container">
            <div class="joe_main">
               <!-- 主体 -->
                <section class="container j-post">
                    <section class="j-adaption">
        			<div >
                        <iframe width="100%" scrolling="no" height="700" align="middle" frameborder="no"
                        src="https://www.xggm.top/music/" hspace="0" vspace="0" marginheight="0"
                        marginwidth="0" name="tv">
                        </iframe>
        				</div>
                    </section>
                </section>
            </div>
        </div>
        <?php $this->need('public/footer.php'); ?>
    </div>
</body>

</html>