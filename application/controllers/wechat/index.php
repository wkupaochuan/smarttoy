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
            try{
                $this->load->service('wechat/receive_wechat_msg_service');
                $msg_data = $this->receive_wechat_msg_service->get_msg(true);

                switch($msg_data['msg_type'])
                {
                    case 'text':
                    case 'voice':
                        $this->_send_msg_to_app($msg_data);
                        break;
                    case 'event':
                        $this->load->service('wechat/wechat_user_service');
                        $this->wechat_user_service->handle_user_subscribe_event($msg_data);
                        break;
                }
            }
            catch(Exception $e)
            {
                $this->debug_log($e->getTraceAsString());
            }
            echo '';exit;
        }
    }


    private function _send_msg_to_app($msg_data)
    {
        $msg = array(
            'messageType' => $msg_data['msg_type']
        , 'text' => $msg_data['content']
        , 'file' => HOME_URL. '/' . $msg_data['media_path']
        );

        $from_user = 'test1';
        $to_user = 'eb2c8e820c13836';

        $url = '127.0.0.1:8090/im_server/servlet/ChatServlet?from_user='. $from_user
            . '&to_user=' . $to_user . '&password=' . '&msg=' . json_encode($msg);

        $this->load->library('wechat/wechat_auth');
        $res = $this->wechat_auth->https_request($url);
        $this->debug_log($msg_data);
        echo '';exit;
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
        try{
            $uploadedFileData = $_FILES['Filedata'];

            $tempFile = $uploadedFileData['tmp_name'];

            // Define a destination
            $targetFileName = time().'.'.pathinfo($uploadedFileData['name'], PATHINFO_EXTENSION);
            $targetFile = FILE_UPLOAD_PATH . '/' .$targetFileName;

            // 移动文件到目的目录
            $this->moveFile($tempFile,$targetFile);

            echo $targetFileName;exit;
        }
        catch(Exception $e)
        {
            $this->rest_fail($e->getMessage());
        }
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
//            'filePath' => '123456.jpg'
//            , 'messageType' => 'image'
            'filePath' => '1424855307819.amr'
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
        $media_id = 'N00fINLh8zxZ30JO5rg6FdubWvjuxuAeSY2fQv7oamzbw9ePmo7gUitfprZ5ud0h';
        $this->load->library('wechat/media_deliver');
        $res = $this->media_deliver->download_media($media_id);
        echo $res;exit;
    }


} 