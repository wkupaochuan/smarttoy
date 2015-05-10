<?php


/**
 * 多媒体上传下载
 * Class media_deliver
 */

class media_deliver {

    // 上传url
    const UPLOAD_URL = 'http://file.api.weixin.qq.com/cgi-bin/media/upload?';
    // 下载url
    const DOWNLOAD_URL = 'http://file.api.weixin.qq.com/cgi-bin/media/get?';

    // 多媒体类型
    const MEDIA_TYPE_VOICE = 'voice';
    const MEDIA_TYPE_IMAGE = 'image';

    private $_ci;


 /******************************public methods**********************************************************************************/

    public function __construct()
    {
        $this->_ci = & get_instance();
        $this->_ci->load->library('wechat/wechat_auth');
    }

    /**
     * 上传音频
     */
    public function upload_voice($file_path)
    {
        $url = $this->_get_full_upload_url(self::MEDIA_TYPE_VOICE);
        $fields = array(
            'media' => '@'.$file_path
        );

        return $this->_upload_file_to_weixin($url, $fields);
    }


    /**
     * 上传图片
     * @param $file_path
     * @return null|string
     */
    public function upload_image($file_path)
    {
        $url = $this->_get_full_upload_url(self::MEDIA_TYPE_IMAGE);
        $fields = array(
            'media' => '@'.$file_path
        );

        return $this->_upload_file_to_weixin($url, $fields);
    }


    /**
     * 下载多媒体文件
     * @param $media_id
     * @return string
     */
    public function download_media($media_id)
    {

        $url = self::DOWNLOAD_URL;

        $access_token = $this->_ci->wechat_auth->get_access_token();
        $url .= 'access_token='.$access_token;
        $url .= '&media_id='.$media_id;


        return $this->_ci->resources_path->download_wechat_msg_media($url);
    }


/******************************private methods**********************************************************************************/

    /**
     * 获取上传文件的全路径
     * @param $type
     * @return string
     */
    private function _get_full_upload_url($type)
    {
        $url = self::UPLOAD_URL;
        $access_token = $this->_ci->wechat_auth->get_access_token();
        $url .= 'access_token='.$access_token;
        $url .= '&type='.$type;

        return $url;
    }


    /**
     * 长传文件到微信
     * @param $url
     * @param $fields
     * @return null|string
     */
    private function _upload_file_to_weixin($url, $fields)
    {
        $res =  $this->_ci->curl->wechat_request($url, $fields);
        return isset($res->media_id)? $res->media_id:null;
    }



} 