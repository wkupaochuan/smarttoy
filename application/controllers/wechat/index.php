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

    public function upload()
    {
        $this->load->library('wechat/media_deliver');
        $name = $this->media_deliver->upload_voice();
        echo $name;
    }


    /**
     * 接收微信消息
     */
    public function dispatch()
    {
        error_log(print_r($_REQUEST, true), 3, '/tmp/a.log');
        if (isset($_GET['echostr'])) {
            $this->valid();
        }else{
            $this->autoResponseMsg();
        }
    }


    /**
     * 回复消息
     */
    public function response_msg()
    {
        $msg = 'i am chuan 王川川';
        $this->load->library('wechat/custom_message');
        $this->custom_message->send_text_message($msg);
        $this->custom_message->send_image_message();
    }


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
     * 自动回复消息
     */
    private function autoResponseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            error_log(print_r($postObj, true), 3, '/tmp/a.log');

            if(!empty( $keyword ))
            {
                $contentStr = "Welcome to wechat world!哈哈";
                $resultStr = $this->getTextMsg($toUsername, $fromUsername, $contentStr);
                echo $resultStr;
            }else{
                echo "Input something...";
            }

        }else {
            echo "";
            exit;
        }
    }


    /**
     * 获取文字类型的消息
     * @param $fromUsername
     * @param $toUsername
     * @param $contentStr
     * @return string
     */
    private function getTextMsg($fromUsername, $toUsername, $contentStr)
    {
        // from gh_cf38532f3c17
        // to oGICajj4qQ7Nfheyfwbm518r8xqY
        // msgType text

        $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
        $msgType = "text";
        $resultStr = sprintf($textTpl, $toUsername, $fromUsername, time(), $msgType, $contentStr);
        return $resultStr;
    }


    /**
     * 接口验证
     */
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }


    private function checkSignature()
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }


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
            'filePath' => 'upload_file/1423124456.amr'
            , 'messageType' => 'voice'
            , 'toyUser' => 'eb2c8e820c13836'
        );
        $res = $this->make_post_request($url, $data);
        print_r($res);exit;
    }


} 