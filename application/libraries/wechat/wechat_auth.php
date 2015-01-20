<?php

/**
 * 微信授权模块
 * Class wechat_auth
 */
class wechat_auth {

//    // wkupaochuan
//    const TOKEN = 'xiaodudu';
//    const APP_ID = 'wxc7b4f0790c32423f';
//    const SECRET_KEY = '134696a01b8efdcfb2cc167f59968ddf ';


    // buct
    const TOKEN = 'xiaodudu';
    const APP_ID = 'wx4edc66c8e1529915';
    const SECRET_KEY = '2e900bfa25e55b681f8bbf8ceb62ca3d';


    // 微信access token 的redis key
    const WECHAT_ACCESS_TOKEN_REDIS_KEY = 'wechat_access_token';
    const WECHAT_ACCESS_TOKEN_REDIS_TIMEOUT = 7200;


    /**
     * 获取access_token
     * @return mixed
     */
    public function get_access_token()
    {
        $CI = & get_instance();
        $CI->load->library('cache/redis_cache');
        $access_token = $CI->redis_cache->get(self::WECHAT_ACCESS_TOKEN_REDIS_KEY);
        if(empty($access_token))
        {
            $access_token = $this->_get_access_token_from_wechat();
            $CI->redis_cache->set(self::WECHAT_ACCESS_TOKEN_REDIS_KEY, $access_token, self::WECHAT_ACCESS_TOKEN_REDIS_TIMEOUT);
        }

        return $access_token;
    }

    /**
     * 向微信发起请求，以获取access token
     * @return mixed
     */
    private function _get_access_token_from_wechat()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::APP_ID."&secret=".self::SECRET_KEY;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        $ret = curl_exec($ch);
        $ret = json_decode($ret);
        curl_close($ch);
        echo '---发起微信请求---';
        return $ret->access_token;
    }

} 