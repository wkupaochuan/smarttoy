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


 /******************************public methods**********************************************************************************/

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
        $CI = & get_instance();
        $CI->load->library('wechat/wechat_auth');
        $access_token = $CI->wechat_auth->get_access_token();
        $url .= 'access_token='.$access_token;
        $url .= '&media_id='.$media_id;

        $file_path = $this->_download_weixin_file($url);
        return $file_path;
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
        $CI = & get_instance();
        $CI->load->library('wechat/wechat_auth');
        $access_token = $CI->wechat_auth->get_access_token();
        $url .= 'access_token='.$access_token;
        $url .= '&type='.$type;

        return $url;
    }



    /**
     * 下载微信文件
     * @param $url
     * @return string
     */
    private function _download_weixin_file($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);    //对body进行输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);

        curl_close($ch);
        $media = array_merge(array('mediaBody' => $package), $httpinfo);

        //求出文件格式, 获取文件名称
        preg_match('/\w\/(\w+)/i', $media["content_type"], $extmatches);
        $fileExt = $extmatches[1];
        $filename = time().rand(100,999).".{$fileExt}";

        // 确保下载文件夹存在
        $file_dir = FCPATH . FILE_DOWNLOAD_ROOT_DIR;
        if(!file_exists($file_dir)){
            mkdir($file_dir,0777,true);
        }

        file_put_contents($file_dir.'/'.$filename,$media['mediaBody']);

        return FILE_DOWNLOAD_ROOT_DIR . '/'.$filename;
    }


    /**
     * 长传文件到微信
     * @param $url
     * @param $fields
     * @return null|string
     */
    private function _upload_file_to_weixin($url, $fields)
    {
        $CI = & get_instance();
        $CI->load->library('wechat/wechat_auth');
        $res =  $CI->wechat_auth->https_request($url, $fields);

        return isset($res->media_id)? $res->media_id:null;
    }



} 