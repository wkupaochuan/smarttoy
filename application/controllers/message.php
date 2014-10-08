<?php
/**
 * Created by PhpStorm.
 * User: smarttoy
 * Date: 14-8-26
 * Time: 14:39
 */

define("TOKEN", "wkupaochuanTest");

class Message extends CI_Controller{

    public function getMsg(){
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->saveMsg($postObj);
            //$this->writeMsg(var_export($postObj, true));

            $answer = $this->makeRequest((string)$postObj->Content);

            $responseMsg = $this->transmitText($postObj, date('Y-m-d h:i:s').$answer);

            $this->saveMsg($postObj, $answer);

            //$this->writeMsg($responseMsg);
            echo $responseMsg;
        }

        echo '';exit();
    }


    public function showMessage()
    {
        $this->load->model('message_model');
        $messages = $this->message_model->getMessages();

        $this->load->view('show_message_view', array('messages' => $messages));

    }


    /**
     * 存储消息
     * @param $postObj
     */
    private function saveMsg($postObj, $content = null)
    {
        $this->load->model('message_model');
        if(isset($content)){
            $this->message_model->createMessage((string)$postObj->FromUserName, (string)$postObj->ToUserName, $content);
        }
        else{
            $this->message_model->createMessage((string)$postObj->FromUserName, (string)$postObj->ToUserName, (string)$postObj->Content);
        }

    }


    /**
     * 生成普通文本消息
     * @param $object
     * @param $content
     * @return string
     */
    private function transmitText($object, $content)
    {
        $textTpl =  <<<EOD
        <xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>0</FuncFlag>
        </xml>
EOD;
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $resultStr;
    }


    /**
     * 消息写入文件
     * @param $msg
     */
    private function writeMsg($msg)
    {
        $fp = fopen('/tmp/msg.txt', 'a+');
        fputs($fp, date('Y-m-d H:i:s') . '---' . $msg . "\n");
        fclose($fp);
    }


    private function  makeRequest($msg)
    {
        $app_key="NVKEaT3VtVUD";//这里填入你的小i机器人key
        $app_secret="SAKrSyJrV4YkE3QBGhvc";//这里填入你的小i机器人secret
        $realm = "xiaoi.com";
        $method = "POST";
        $uri = "/robot/ask.do";
        $nonce="";
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        for ( $i = 0; $i < 40; $i++)
            $nonce .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        $HA1 = sha1($app_key . ":" . $realm . ":" . $app_secret);
        $HA2 = sha1($method . ":" . $uri);
        $sign = sha1($HA1 . ":" . $nonce . ":" . $HA2);
        $msg=urlencode($msg);
        $openid=urlencode('wkupaochua');
        $url="http://nlp.xiaoi.com/robot/ask.do";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Auth:    app_key="'.$app_key.'", nonce="'.$nonce.'", signature="'.$sign.'"'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "question=".$msg."&userId=".$openid."&type=0");
        $data = curl_exec($ch);
        return  $data;

    }


}


