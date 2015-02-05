<?php


/**
 * 多媒体上传下载
 * Class media_deliver
 */

class media_deliver {

    const UPLOAD_URL = 'http://file.api.weixin.qq.com/cgi-bin/media/upload?';

    const MEDIA_TYPE_VOICE = 'voice';

    const MEDIA_TYPE_IMAGE = 'image';

    /**
     * 上传音频
     */
    public function upload_voice($file_path)
    {
        $url = self::UPLOAD_URL;
        $CI = & get_instance();
        $CI->load->library('wechat/wechat_auth');
        $access_token = $CI->wechat_auth->get_access_token();
        $url .= 'access_token='.$access_token;
        $url .= '&type='.self::MEDIA_TYPE_VOICE;

        $file_data = array(
            'media' => '@'.$file_path
        );

        $CI->load->library('wechat/wechat_auth');
        $ret = $CI->wechat_auth->https_request($url, $file_data);

        return $ret->media_id;
    }

} 