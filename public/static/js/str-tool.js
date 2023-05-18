function strSize(str, charset) {
    let total = 0;
    charset = charset?.toLowerCase() || '';
    for (i = 0; i < str.length; i++) {
        let charCode = str.charCodeAt(i);
        if (charset === 'utf-16' || charset === 'utf16') {
            total += charCode <= 0xffff ? 2 : 4;
        } else {
            if (charCode <= 0x007f) {
                total += 1;
            } else if (charCode <= 0x07ff) {
                total += 2;
            } else if (charCode <= 0xffff) {
                total += 3;
            } else {
                total += 4;
            }
        }
    }
    return total;
}
export {strSize};