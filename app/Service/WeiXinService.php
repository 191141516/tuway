<?php

namespace App\Service;

use GuzzleHttp\Client;

/**
 * Created by PhpStorm.
 * User: quoyle
 * Date: 2017/9/25
 * Time: 下午5:25.
 */
class WeiXinService
{
    /** @var string 微信用code换取session_key地址 */
    private static $weixin_url = 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code';

    public static function getSessionKey($code)
    {
        $url = sprintf(self::$weixin_url, env('WX_APPID'), env('WX_APPSECRET'), $code);

        $client = new Client();

        $result = $client->get($url);

        if (200 == $result->getStatusCode()) {
            $response = json_decode($result->getBody(), true);

            if (isset($response['errcode'])) {
                throw new \Exception($response['errmsg']);
            }
            return $response;

        }else{
            throw new \Exception('服务器异常');
        }
    }
}
