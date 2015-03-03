<?php

/**
 * 与微信通信
 * Class Index
 */
class Index extends  MY_Controller{

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
                        $this->receive_wechat_msg_service->send_text_msg($msg_data['from_username'], $msg_data['to_username'], '消息发送成功');
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
        }
        echo '';exit;
    }


    /**
     * 发送消息给app用户
     * @param $msg_data
     */
    private function _send_msg_to_app($msg_data)
    {
        // 内定的开发者才发送消息，其他用户屏蔽，以免影响账号的正常使用
        $developers = array('og0UpuEhZ0No4K7Wf0DflsBYQzPE', 'og0UpuAo8aPWb-QqIugaB48gI94Q');
        if( !in_array($msg_data['from_username'], $developers))
        {
            echo '';exit;
        }

        // 记录微信消息
        $this->load->service('msg/wechat_msg_service');
        $this->wechat_msg_service->add_msg($msg_data['from_username'], $msg_data['msg_type'], $msg_data['media_path'], $msg_data['media_id'], $msg_data['content']);

        $this->load->service('user/toy_wechat_relation_service');
        // 处理关注事件
        if(strpos($msg_data['content'], 'gz:') === 0)
        {
            $this->toy_wechat_relation_service->handle_relationship_msg($msg_data);
        }
        else{
            $msg = array(
                'messageType' => $msg_data['msg_type']
            , 'text' => $msg_data['content']
            , 'file' => HOME_URL. '/' . $msg_data['media_path']
            );

            // todo 暂时统一使用admin用户发送消息
            $from_user = 'admin';
            // 获取app用户unique_id
            $to_user = $this->toy_wechat_relation_service->get_child_toy_user($msg_data['from_username']);

            $url = '127.0.0.1:8090/im_server/servlet/ChatServlet?from_user='. $from_user
                . '&to_user=' . $to_user . '&password=' . '&msg=' . json_encode($msg);

            $this->load->library('wechat/wechat_auth');
            $this->wechat_auth->https_request($url);
        }
    }

    /************************************接收消息---end***********************************************************/




    /************************************客服消息--begin***********************************************************/

    /**
     * 发送客服消息接口
     */
    public function send_custom_msg()
    {
        try{
            $params = $this->input->post();

            // 获取参数
            $toy_user_unique_id = $params['toyUser'];
            $msg_type = $params['messageType'];
            $msg_content = isset($params['content'])? $params['content']:'';
            $file_path = isset($params['filePath'])? $params['filePath']:'';

            // 获取接收人
            $this->load->service('user/toy_wechat_relation_service');
            $to_user_open_id = $this->toy_wechat_relation_service->get_parent_wechat_user($toy_user_unique_id);
            if(empty($to_user_open_id))
            {
                throw new Exception('找不到对应的接收人');
            }

            // 记录app消息
            $this->load->service('msg/app_msg_service');
            $this->app_msg_service->add_msg($toy_user_unique_id, $msg_type, $file_path, $msg_content);

            // 发送消息
            $this->load->service('wechat/custom_msg_service');
            $this->custom_msg_service->send_msg($to_user_open_id, $msg_type, $msg_content, $file_path);

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
//            'filePath' => '1424855307819.amr'
//            , 'messageType' => 'voice'
            'filePath' => 'ddd'
            , 'messageType' => 'text'
            , 'toyUser' => 'eb2c8e820c13836'
            , 'content' => 'hahah测试消息里'
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


    /**
     * 测试好友关系
     */
    public function test_relationship()
    {
        $msg_data = array(
            'from_username' => 'og0UpuEhZ0No4K7Wf0DflsBYQzPE'
        , 'to_username' => 'gh_f3e29636ebd7'
        , 'msg_type' => 'text'
        , 'media_id' => ''
        , 'media_path' => ''
        , 'content' => 'gz:eb2c8e820c13836'
        , 'event' => ''
        );
        $this->_send_msg_to_app($msg_data);
    }


    /**
     * 测试自动接收消息
     */
    public function test_recieve_msg()
    {
        $url = 'http://toy-api.wkupaochuan.com/wechat/index/dispatch';
        $data = array(
            'FromUserName' => 'og0UpuEhZ0No4K7Wf0DflsBYQzPE'
            , 'ToUserName' => 'gh_f3e29636ebd7'
            , 'MsgType' => 'text'
            , 'Content' => 'ddd'
        );
        $res = $this->make_post_request($url, $data);
        print_r($res);exit;
    }


} 