<?php


class Index extends  CI_Controller{

    const TOKEN = 'xiaodudu';
    const APP_ID = 'wxc7b4f0790c32423f';
    const SECRET_KEY = '134696a01b8efdcfb2cc167f59968ddf ';

    public function __construct()
    {
        parent::__construct();
    }


    public function dispatch()
    {
//        error_log(print_r($_GET, true), 3, '/tmp/a.log');
        if (isset($_GET['echostr'])) {
            $this->valid();
        }else{
            $this->autoResponseMsg();
        }
    }


    /**
     * 回复消息
     */
    public function reponseMsg()
    {
//        $constentStr = $_REQUEST['msg'];

        $toUsername = 'gh_cf38532f3c17';

        $accessToken = '74_Cu3QcMavGX8Q6Hci_UxSDg-UzzNra6z-0kL5r2f3C1ecm6ZckDr90kmyWlOUsZ3bWvUviRcVOdgZfXTXpBWi2KgEBBKOwvnI_sF9GbWg';
        //$this->getAccessToken();
        error_log(print_r($accessToken, true), 3, '/tmp/a.log');
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$accessToken;

        // 参数数组
        $data = array (
            'touser' => $toUsername
            ,'msgtype' => 'text'
            ,'text' => array(
                'content' => 'ddd'
            )
        );


        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url);
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        $return = curl_exec ( $ch );
        error_log(print_r($return, true), 3, '/tmp/a.log');
        curl_close ( $ch );
    }



    /**
     * 获取access_token
     * @return mixed
     */
    private function getAccessToken()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::APP_ID."&secret=".self::SECRET_KEY;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        $ret = curl_exec($ch);
        $ret = json_decode($ret);
        curl_close($ch);
        return $ret->access_token;
    }




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

} 