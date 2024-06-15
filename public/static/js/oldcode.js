
function checkModernBrowserVersion() {
    var userAgent = navigator.userAgent;

    // Chrome
    if (userAgent.indexOf("Chrome") > -1) {
        var chromeMatch = userAgent.match(/Chrome\/(\d+)/);
        if (chromeMatch && parseInt(chromeMatch[1]) >= 70) {
            return true; // Chrome 70+，大约2018年10月发布
        }
    }
    // Firefox
    else if (userAgent.indexOf("Firefox") > -1) {
        var firefoxMatch = userAgent.match(/Firefox\/(\d+)/);
        if (firefoxMatch && parseInt(firefoxMatch[1]) >= 63) {
            return true; // Firefox 63+，大约2018年10月发布
        }
    }
    // Safari（注意：Safari的版本号在"Version/"之后）
    else if (userAgent.indexOf("Safari") > -1 && userAgent.indexOf("Chrome") === -1) { // 确保不是基于WebKit的其他浏览器
        var safariMatch = userAgent.match(/Version\/(\d+)/);
        if (safariMatch && parseInt(safariMatch[1]) >= 11) {
            return true; 
        }
    }
    // Microsoft Edge (Chromium-based, post-2018)
    // else if (userAgent.indexOf("Edg") > -1) { // 新版Edge基于Chromium
    //     var edgeMatch = userAgent.match(/Edg\/(\d+)/);
    //     if (edgeMatch && parseInt(edgeMatch[1]) >= 79) {
    //         return true; // Edge 79+，首个基于Chromium的稳定版，2020年发布，但远超2018年，作为示例
    //     }
    // }
    // 对于旧版Edge（EdgeHTML内核），最后一个大版本在2018年是Edge 44，但更推荐检查Chromium-based Edge
    else if (userAgent.indexOf("Edge/") > -1) {
        var oldEdgeMatch = userAgent.match(/Edge\/(\d+)/);
        if (oldEdgeMatch && parseInt(oldEdgeMatch[1]) >= 44) {
            return true; 
        }
    }

    // 如果没有匹配到任何现代版本，则提示用户
    alert("为了获得最佳体验，请使用最新版本的浏览器。");
    return false;
}

// 调用函数
checkModernBrowserVersion();