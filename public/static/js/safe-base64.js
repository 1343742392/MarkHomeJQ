function bencode(str) {
    return btoa(unescape(encodeURIComponent(str)));
}

function bdecode(base64str) {
    return decodeURIComponent(escape(atob(base64str)));
}


export {bencode, bdecode};