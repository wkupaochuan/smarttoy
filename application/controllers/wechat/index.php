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
            // 这里比较特殊，解析、处理、回复消息完全交给service去完成
            $this->load->service('wechat/receive_wechat_msg_service');
            $this->receive_wechat_msg_service->handle_msg();
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


    /**
     * 上传app用户消息中的多媒体
     */
    public function upload_toy_msg_media()
    {
        try{
            $res = $this->resources_path->upload_toy_msg_media();
            $this->rest_success($res);
        }catch (Exception $e)
        {
            $this->rest_fail($e->getMessage());
        }
    }


    /************************************客服消息--end***********************************************************/



    /************************************ 二维码 --begin ***********************************************************/


    /**
     * 获取临时二维码
     */
    public function get_qrcode()
    {
        try{
            // 获取登陆用户的信息
            $user_info = $this->session->get_user_info();
            $scene_id = $user_info['id'];

            $this->load->service('wechat/qrcode_service');
            $res = $this->qrcode_service->create_qrcode($scene_id);
            $this->rest_success($res);
        }
        catch(Exception $e){
            $this->rest_fail($e->getMessage());
        }

    }

    /************************************ 二维码--end ***********************************************************/















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



} 