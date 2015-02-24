<?php

/**
 * 与微信通信
 * Class Index
 */
class Index extends  MY_Controller{

    const TOKEN = 'xiaodudu';
    const APP_ID = 'wxc7b4f0790c32423f';
    const SECRET_KEY = '134696a01b8efdcfb2cc167f59968ddf ';

    public function __construct()
    {
        parent::__construct();
    }


    /************************************接收消息--begin***********************************************************/

    
    /**
     * 接收微信消息
     */
    public function dispatch()
    {
        if (isset($_GET['echostr'])) {
            echo $_GET['echostr'];exit;
        }else{
            $this->load->service('wechat/receive_wechat_msg_service');
            $msg_data = $this->receive_wechat_msg_service->get_msg();

            $this->debug_log($msg_data);
            echo '';exit;
        }
    }

    /************************************客服消息--end***********************************************************/




    /************************************客服消息--begin***********************************************************/

    /**
     * 发送客服消息接口
     */
    public function send_custom_msg()
    {
        try{
            $params = $this->input->post();

            // 获取参数
            $toy_user = $params['toyUser'];
            $msg_type = $params['messageType'];
            $msg_content = isset($params['content'])? $params['content']:'';
            $file_path = isset($params['filePath'])? $params['filePath']:'';
            $file_path = '/var/www/dev_tool/ToyAppApi/' . $file_path;

            // 获取接收人
            $this->load->service('wechat/wechat_user_service');
            $to_user = $this->wechat_user_service->get_parent_wechat_user($toy_user);
            if(empty($toy_user))
            {
                throw new Exception('找不到对应的接收人');
            }

            // 发送消息
            $this->load->service('wechat/custom_msg_service');
            $this->custom_msg_service->send_msg($to_user, $msg_type, $msg_content, $file_path);

            $this->rest_success('', '发送消息成功');
        }
        catch(Exception $e){
            $this->rest_fail($e->getMessage());
        }

    }

    /************************************客服消息--end***********************************************************/


    /**
     * 上传文件
     */
    public function upload_file()
    {
        $uploadedFileData = $_FILES['Filedata'];

        $tempFile = $uploadedFileData['tmp_name'];

        // Define a destination
        $targetPath = $_SERVER['DOCUMENT_ROOT'] . '/' . 'upload_file';
        $targetFileName = time().'.'.pathinfo($uploadedFileData['name'], PATHINFO_EXTENSION);
        $targetFile = $targetPath. '/' .$targetFileName;

        // 移动文件到目的目录
        $this->moveFile($tempFile,$targetFile);

        echo 'upload_file/' . $targetFileName;
    }

    public function moveFile($tempFile,$targetFile)
    {
        if(!file_exists($targetFile))
        {
            move_uploaded_file($tempFile,$targetFile);
        }
    }


    /**********************************************测试 方法**************************************************************************************/

    /**
     * 发送客服消息接口
     */
    public function test_send_custom_msg()
    {
        $url = 'http://toy-api.wkupaochuan.com/wechat/index/send_custom_msg';
        $data = array(
            'filePath' => 'upload_file/notice.mp3'
            , 'messageType' => 'voice'
            , 'toyUser' => 'eb2c8e820c13836'
        );
        $res = $this->make_post_request($url, $data);
        print_r($res);exit;
    }


    /**
     * 测试下载多媒体文件
     */
    public function test_download_media()
    {
        $media_id = '_qTP_i7NGOQLkTiXcBd7phkPvonSL5Tdz5xFPVP11UIjZlTXYgM7WuYsk0DlhD1s';
        $this->load->library('wechat/media_deliver');
        $this->media_deliver->download_media($media_id);
    }


} 