<?php

/**
 * 处理项目里的资源
 * Class resources_path
 */


class resources_path {

    private $_ci;

    // 域名
    const PROJECT_URI = 'http://toy-admin.wkupaochuan.com';

    // 项目根目录
    const PROJECT_PATH = '/var/www/dev_tool/ToyAdmin/';

    // 上传文件的根目录
    const UPLOAD_ROOT = 'user_resources/upload';

    // 下载文件的根目录
    const DOWNLOAD_ROOT = 'user_resources/download';

    // 故事资源文件根目录
    private $_story_root;

    // 故事封面目录
    private $_story_cover_root;

    // 故事音频目录
    private $_story_voice_root;

    // app 用户头像目录
    private $_toy_user_face_root;

    // 从微信消息下载的多媒体
    private $_wechat_msg_media_root;

    // app用户消息中上传的多媒体
    private $_toy_msg_media_root;


    /*************************************************** public methods *************************************************************************/


    public function __construct()
    {
        $this->_init_root_folders();
        $this->_ci = &get_instance();
    }


    /**
     * 上传故事封面
     */
    public function upload_story_cover()
    {
        return $this->_upload_file($this->_story_cover_root);
    }


    /**
     * 上传故事音频
     */
    public function upload_story_voice()
    {
        return $this->_upload_file($this->_story_voice_root);
    }


    /**
     * 上传app用户头像
     * @return string
     */
    public function upload_toy_face_url()
    {
        return $this->_upload_file($this->_toy_user_face_root);
    }


    /**
     * 上传app用户消息中的多媒体
     * @return string
     */
    public function upload_toy_msg_media()
    {
        return $this->_upload_file($this->_toy_user_face_root);
    }


    /**
     * 下载微信消息中的多媒体
     * @param $media_url
     * @return string
     */
    public function download_wechat_msg_media($media_url)
    {
        $ch = curl_init($media_url);
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

        $this->_make_sure_folder(self::PROJECT_PATH . $this->_wechat_msg_media_root);
        file_put_contents(self::PROJECT_PATH . $this->_wechat_msg_media_root. '/'. $filename,$media['mediaBody']);

        return  array(
            'filename' => $this->_wechat_msg_media_root. '/'. $filename
            , 'url' => $this->get_resource_path($this->_wechat_msg_media_root. '/'. $filename)
        );
    }


    /**
     * 获取资源的可访问地址
     * @param $path
     * @return string
     */
    public function get_resource_path($path)
    {
        return self::PROJECT_URI . '/'. $path;
    }




    /*************************************************** private methods *************************************************************************/

    /**
     * 初始化根目录
     */
    private function _init_root_folders(){
        $this->_story_root = self::UPLOAD_ROOT . '/' . 'story';

        $this->_story_cover_root = $this->_story_root . '/' . 'cover';

        $this->_story_voice_root = $this->_story_root . '/' . 'voice';

        // 初始化app用户头像目录
        $this->_toy_user_face_root = self::UPLOAD_ROOT . '/toy_face_url';

        // 初始化微信消息多媒体目录
        $this->_wechat_msg_media_root = self::UPLOAD_ROOT . '/msg/wechat';

        // 初始化app用户消息多媒体目录
        $this->_toy_msg_media_root = self::UPLOAD_ROOT . '/msg/toy';
    }



    /**
     * 确保目录可用
     */
    private function _make_sure_folder($folder)
    {
        if (!is_dir($folder)) {
            if (!mkdir($folder, 0777, TRUE)) {
                throw new Exception('Failed to create folder: ' . $folder);
            }
        }
    }


    /**
     * 上传文件
     * @param $target_root
     * @return string
     */
    private function _upload_file($target_root)
    {
        $uploadedFileData = $_FILES['Filedata'];

        $tempFile = $uploadedFileData['tmp_name'];

        // 目标文件
        $targetFileName = time().'.'.pathinfo($uploadedFileData['name'], PATHINFO_EXTENSION);
        $targetFile = $target_root. '/' .$targetFileName;
        $absolute_target_file = self::PROJECT_PATH  . $targetFile;

        // 移动文件
        if(!file_exists($absolute_target_file))
        {
            $this->_make_sure_folder(self::PROJECT_PATH . $target_root);
            move_uploaded_file($tempFile,$absolute_target_file);
        }

        return  array(
            'filename' => $targetFile
            , 'url' => $this->get_resource_path($targetFile)
        );
    }

}