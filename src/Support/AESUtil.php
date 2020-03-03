<?php
/**
 * Created by PhpStorm.
 * User: wulin
 * Date: 17/7/31
 * Time: 18:06
 */

namespace App\Support;


class AESUtil
{
    const IV              = "Ksgmau1ybnd97261";//初始化向量
    const MD5_CODE_FORMAT = 'kshHsskaga&^%s^SHANjasnGS1%s';
    const CIPHER_METHOD   = 'AES-128-CBC';//java仅支持128bit加密算法

    /**
     * 产生私钥
     * @param $code
     * @return string
     */
    private static function generateSecretKey($code)
    {
        return md5(sprintf(self::MD5_CODE_FORMAT, $code, $code), true);
    }

    /**
     * 秘钥对称加密
     * @param $src
     * @param $code
     * @return string
     */
    public static function encrypt($src, $code)
    {
        $secret_key = self::generateSecretKey($code);
        //openssl_encrypt use PKCS5/7 style padding
        $encode = openssl_encrypt($src, self::CIPHER_METHOD, $secret_key, OPENSSL_RAW_DATA, self::IV);
        return base64_encode($encode);
    }

    /**
     * 秘钥对称解密
     * @param $encrypt_str
     * @param $code
     * @return string
     */
    public static function decrypt($encrypt_str, $code)
    {
        $encrypt_str = base64_decode($encrypt_str);
        $secret_key  = self::generateSecretKey($code);
        //openssl_encrypt use PKCS5/7 style padding
        return openssl_decrypt($encrypt_str, self::CIPHER_METHOD, $secret_key, OPENSSL_RAW_DATA, self::IV);
    }


    public static function randomHash()
    {
        $time = (new \DateTime('now'))->format('Y-m-d H:i:s');
        $secret_key = self::generateSecretKey($time);
        //openssl_encrypt use PKCS5/7 style padding
        return  bin2hex(openssl_encrypt($time, self::CIPHER_METHOD, $secret_key, OPENSSL_RAW_DATA, self::IV));
    }
}