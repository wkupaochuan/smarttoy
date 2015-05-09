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
//    const TOKEN = 'xiaodudu';
//    const APP_ID = 'wx4edc66c8e1529915';
//    const SECRET_KEY = '2e900bfa25e55b681f8bbf8ceb62ca3d';


    // wentong
    const TOKEN = 'xiaodudu';
    const APP_ID = 'wx4f3bcf1893bea033';
    const SECRET_KEY = '2b04cc3f4574446234bfdee0bcb295b0';


    // 微信access token 的redis key
    const WECHAT_ACCESS_TOKEN_REDIS_KEY = 'wechat_access_token';
    const WECHAT_ACCESS_TOKEN_REDIS_TIMEOUT = 7200;


    private $_ci;


    /**************************************************************** public methods ******************************************************************************************************/


    public function __construct()
    {
        $this->_ci = & get_instance();
    }


    /**
     * 获取access_token
     * @return mixed
     */
    public function get_access_token()
    {
        $access_token = $this->_ci->redis_cache->get(self::WECHAT_ACCESS_TOKEN_REDIS_KEY);
        if(empty($access_token))
        {
            $access_token = $this->_get_access_token_from_wechat();
            $this->_ci->redis_cache->set(self::WECHAT_ACCESS_TOKEN_REDIS_KEY, $access_token, self::WECHAT_ACCESS_TOKEN_REDIS_TIMEOUT);
        }

        return $access_token;
    }



    /**************************************************************** private methods ******************************************************************************************************/



    /**
     * 向微信发起请求，以获取access token
     * @return mixed
     */
    private function _get_access_token_from_wechat()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::APP_ID."&secret=".self::SECRET_KEY;
        $ret = $this->_ci->curl->wechat_request($url);
        return $ret->access_token;
    }

} 