/**
 * Joe 主题 - 评论图片上传
 * 依赖：jQuery、Qmsg（Joe 主题已全局加载）
 * 配置：window.JoeCommentImg = { api: '自建图床接口', fallback: 'Typecho 上传接口' }
 *   - api 非空时优先使用自建图床接口
 *   - api 为空时回退 Typecho 自带上传接口（仅登录用户可用，游客会 403）
 */
(function ($) {
    "use strict";
    if (!$) return;

    /* 允许的图片后缀（如需放开限制，可自行增删） */
    var IMG_REG = /\.(jpe?g|png|gif|ico|webp|bmp|tiff?|svg)$/i;

    /* 轻量提示：优先 Qmsg，否则退回 alert */
    function tip(type, msg) {
        if (typeof Qmsg !== "undefined" && Qmsg[type]) {
            Qmsg[type](msg);
        } else {
            window.alert(msg);
        }
    }

    /* 在 textarea 光标处插入文本 */
    function insertAtCursor(el, text) {
        if (typeof el.selectionStart === "number") {
            var start = el.selectionStart;
            var end = el.selectionEnd;
            el.value = el.value.substring(0, start) + text + el.value.substring(end);
            el.selectionStart = el.selectionEnd = start + text.length;
        } else {
            el.value += text;
        }
        /* 触发 input 事件，兼容主题的字数统计等逻辑 */
        el.dispatchEvent(new Event("input", { bubbles: true }));
    }

    /* 从接口返回中解析图片直链与名称，兼容 Typecho 与常见自建图床 */
    function resolveResult(res) {
        /* Typecho 上传接口返回：[url字符串, { url, title, isImage, ... }] */
        if (Array.isArray(res)) {
            var info = res[1] || {};
            return { url: info.url || res[0], name: info.title || "image" };
        }
        if (res && typeof res === "object") {
            var url = res.viewurl || res.url || res.data && (res.data.url || res.data.viewurl) || "";
            var name = res.name || res.title || (res.data && (res.data.name || res.data.title)) || "image";
            return { url: url, name: name };
        }
        return { url: "", name: "" };
    }

    $(function () {
        var $file = $("#joe_comment_file");
        var $btn = $("#joe_comment_img_btn");
        var $name = $(".joe_comment__upload .showFileName");
        var textarea = document.getElementById("joe_comment_text");
        if (!$file.length || !$btn.length || !textarea) return;

        var cfg = window.JoeCommentImg || {};

        /* 初始隐藏文件名与插入按钮 */
        $name.hide();
        $btn.hide();

        /* 选择文件：校验类型并展示文件名 */
        $file.on("change", function () {
            var path = $(this).val();
            if (!path) {
                $name.hide();
                $btn.hide();
                return;
            }
            if (!IMG_REG.test(path)) {
                $name.show().text("请选择图片文件！");
                $btn.hide();
                this.value = "";
                return;
            }
            var arr = path.split("\\");
            var fileName = arr[arr.length - 1];
            $name.show().text("已选择：" + fileName);
            $btn.show();
        });

        /* 点击插入：上传并写入 Markdown 图片语法 */
        $btn.on("click", function () {
            var files = $file.get(0).files;
            if (!files || !files.length) {
                tip("info", "请先选择图片");
                return;
            }
            var target = cfg.api ? cfg.api : cfg.fallback;
            if (!target) {
                tip("error", "未配置上传接口");
                return;
            }
            /* 未配置图床接口而走 Typecho 回退时，游客无上传权限，提前提示 */
            if (!cfg.api && cfg.login === false) {
                tip("warning", "上传图片需登录账号后使用");
                return;
            }

            var formData = new FormData();
            formData.append("file", files[0]);

            var $self = $(this);
            $self.prop("disabled", true).text("上传中...");

            $.ajax({
                url: target,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (res) {
                    var result = resolveResult(res);
                    if (!result.url) {
                        tip("error", "上传成功但未解析到图片链接，请检查接口返回格式");
                        return;
                    }
                    insertAtCursor(textarea, "![" + result.name + "](" + result.url + ")");
                    tip("success", "图片已插入评论框");
                    /* 重置选择状态 */
                    $file.val("");
                    $name.hide();
                    $self.hide();
                },
                error: function (xhr) {
                    if (xhr && xhr.status === 403) {
                        tip("error", "无权限上传（Typecho 接口需登录），请配置图床接口或登录后再试");
                    } else {
                        tip("error", "上传失败，请稍后重试");
                    }
                },
                complete: function () {
                    $self.prop("disabled", false).text("插入");
                }
            });
        });
    });
})(window.jQuery);
