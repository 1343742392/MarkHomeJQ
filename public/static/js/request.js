/**
 * @param {String} strData "name=s&b=c"
 *  @param {String} url      192.168.1.1/index....
 *  @param {Function} back     function(res){}
 */
function request(strData, url, back, method = 'get'){
    var xhr = new XMLHttpRequest();  
    xhr.open(method, url, true); 
    xhr.onload = back;
    xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xhr.send(strData); 
}

export{request}