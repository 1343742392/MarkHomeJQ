export function binaryToUtf8(binaryString) {
    // 将二进制字符串转换为字节数组
    const bytes = new Uint8Array(binaryString.length);
    for (let i = 0; i < binaryString.length; i++) {
        bytes[i] = binaryString.charCodeAt(i);
    }
    // 使用TextDecoder将字节数组解码为UTF-8字符串
    const decoder = new TextDecoder('utf-8');
    return decoder.decode(bytes);
}

