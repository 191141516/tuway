<?php

namespace Library\Wechat\Decode;

/**
 * Prpcrypt class.
 */
class Prpcrypt
{
    public $key;

    public function __construct($k)
    {
        $this->key = $k;
    }

    /**
     * 对密文进行解密.
     *
     * @param string $aesCipher 需要解密的密文
     * @param string $aesIV     解密的初始向量
     *
     * @return string 解密得到的明文
     */
    public function decrypt($aesCipher, $aesIV)
    {
        try {
            //解密
            $decrypted = openssl_decrypt(base64_decode($aesCipher), 'aes-128-cbc', base64_decode($this->key), OPENSSL_RAW_DATA, base64_decode($aesIV));
        } catch (\Exception $e) {
            return false;
        }
        try {
            //去除补位字符
            $pkc_encoder = new PKCS7Encoder();
            $result = $pkc_encoder->decode($decrypted);
        } catch (\Exception $e) {
            return false;
        }

        return $result;
    }
}
