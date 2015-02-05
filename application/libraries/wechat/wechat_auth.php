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
        $ret = $this->https_request($url);
        echo '向微信获取access_token:'.$ret->access_token;
        return $ret->access_token;
    }


    /**
     * 发送微信请求
     * @param $url
     * @param null $data
     * @return mixed
     * @throws Exception
     */
    function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        $res  = json_decode($output);
        if(isset($res->errcode) && $res->errcode != 0)
        {
            throw new Exception("请求微信错误:错误码--" . $res->errcode. ';错误内容--' .$res->errmsg);
        }
        return $res;
    }

} 