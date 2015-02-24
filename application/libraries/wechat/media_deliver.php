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
//        print_r($ret);

        return isset($ret->media_id)? $ret->media_id:null;
    }


    /**
     * 下载多媒体文件
     * @param $media_id
     * @param $destination
     */
    public function download_media($media_id, $destination)
    {
        $url = self::DOWNLOAD_URL;
        $CI = & get_instance();
        $CI->load->library('wechat/wechat_auth');
        $access_token = $CI->wechat_auth->get_access_token();
        $url .= 'access_token='.$access_token;
        $url .= '&media_id='.$media_id;

        $file_content = $this->_download_weixin_file($url);
        $filename = '/var/www/dev_tool/ToyAppApi/download_file/' . time() . '.amr';
        $this->_save_weixin_file($filename, $file_content);
    }


/******************************private methods**********************************************************************************/

    /**
     * 下载微信文件
     * @param $url
     * @return array
     */
    private function _download_weixin_file($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_NOBODY, 0); // 只去body头

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($curl);
        $http_info = curl_getinfo($curl);
        curl_close($curl);

        return array_merge(array('header' => $http_info), array('body' => $package));
    }



    /**
     * 保存微信文件
     * @param $filename
     * @param $file_content
     */
    private function _save_weixin_file($filename, $file_content)
    {
        $local_file = fopen($filename, 'w');
        if(false != $local_file)
        {
            if(false != fwrite($local_file, $file_content))
            {
                fclose($local_file);
            }
        }
    }



} 