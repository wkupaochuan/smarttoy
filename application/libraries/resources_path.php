<?php

/**
 * 处理项目里的资源
 * Class resources_path
 */


class resources_path {

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


    /*************************************************** public methods *************************************************************************/


    public function __construct()
    {
        $this->_init_root_folders();
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
            move_uploaded_file($tempFile,$absolute_target_file);
        }

        return  array(
            'filename' => $targetFile
            , 'url' => $this->get_resource_path($targetFile)
        );
    }

}