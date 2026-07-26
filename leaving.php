<?php

/**
 * 留言
 * 
 * @package custom 
 * 
 **/

?>

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
    <script src="<?php echo $JoeCdn; ?>npm/draggabilly@2.3.0/dist/draggabilly.pkgd.js"></script>
    <script src="<?php $this->options->themeUrl('assets/js/joe.leaving.min.js'); ?>"></script>
    <style>
        .joe_detail__leaving-list .item .wrapper .content a[data-fancybox] {
            display: block;
        }
        .joe_detail__leaving-list .item .wrapper .content img:not(.owo_image),
        .joe_detail__leaving-list .item .wrapper .content video {
            display: block;
            max-width: 100%;
            max-height: 128px;
            width: auto;
            height: auto;
            margin: 6px auto;
            border-radius: 6px;
            object-fit: contain;
            cursor: zoom-in;
        }
        .joe_detail__leaving-list .item .wrapper .content audio {
            width: 100%;
            margin: 6px 0;
        }
        /* 隐藏便签内容区滚动条（保留滚动功能） */
        .joe_detail__leaving-list .item .wrapper .content {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .joe_detail__leaving-list .item .wrapper .content::-webkit-scrollbar {
            width: 0;
            height: 0;
            display: none;
        }
    </style>
</head>

<body>
    <div id="Joe">
        <?php $this->need('public/header.php'); ?>
        <div class="joe_container">
            <div class="joe_main">
                <div class="joe_detail" data-cid="<?php echo $this->cid ?>">
                    <?php $this->need('public/batten.php'); ?>
                    <div class="joe_detail__leaving">
                        <?php $this->comments()->to($comments); ?>
                        <?php if ($comments->have()) : ?>
                            <ul class="joe_detail__leaving-list">
                                <?php while ($comments->next()) : ?>
                                    <li class="item">
                                        <div class="user">
                                            <img class="avatar lazyload" src="<?php _getAvatarLazyload(); ?>" data-src="<?php _getAvatarByMail($comments->mail) ?>" alt="用户头像" />
                                            <div class="nickname"><?php $comments->author(); ?></div>
                                            <div class="date"><?php $comments->date('Y/m/d'); ?></div>
                                        </div>
                                        <div class="wrapper">
                                            <div class="content"><?php _parseLeavingReply($comments->content); ?></div>
                                        </div>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        <?php else : ?>
                            <div class="joe_detail__leaving-none">暂无留言，期待第一个脚印。</div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $this->need('public/comment.php'); ?>
            </div>
        </div>
        <?php $this->need('public/footer.php'); ?>
        <script type="text/javascript">
            /* 放在 #Joe 容器内并立即执行：Pjax 只重执行 head 与容器内脚本，
               挂 DOMContentLoaded 且置于容器外会导致 Pjax 进入时图片未包 fancybox 无法放大 */
            (function () {
                var imgs = document.querySelectorAll('.joe_detail__leaving-list .item .wrapper .content img:not(.owo_image)');
                Array.prototype.forEach.call(imgs, function (img) {
                    if (img.closest('a[data-fancybox]')) return;
                    var src = img.getAttribute('data-src') || img.getAttribute('src');
                    if (!src) return;
                    var link = document.createElement('a');
                    link.setAttribute('data-fancybox', 'leaving');
                    link.setAttribute('href', src);
                    img.parentNode.insertBefore(link, img);
                    link.appendChild(img);
                });
            })();
        </script>
    </div>
</body>

</html>