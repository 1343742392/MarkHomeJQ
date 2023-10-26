//设置cookie
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/";
}
//获取cookie
function getCookie(cname) {
    var tokenName = " " + cname + "=";
    let strCookie = " " + document.cookie;
    var cookies = strCookie.split(';');
    for(var i=0; i<cookies.length; i++) {
        var c = cookies[i];
        if (c.indexOf(tokenName) != -1) return c.substring(tokenName.length, c.length);
    }
    return "";
}
//清除cookie
function clearCookie(name) {
    setCookie(name, "", -1);
}


export{setCookie, getCookie, clearCookie};