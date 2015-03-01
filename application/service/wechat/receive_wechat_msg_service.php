<?php

class receive_wechat_msg_service extends MY_Service{

    public function __construct()
    {
        parent::__construct();

        // 加载微信帮助类
        $this->load->library('wechat/wechat_auth');
    }


    /*******************************************************public methods*****************************************************************************/


    public function get_msg($download_media = false)
    {
        // todo for test
//        $res = array(
//            'from_username' => 'og0UpuEhZ0No4K7Wf0DflsBYQzPE'
//        , 'to_username' => 'gh_f3e29636ebd7'
//        , 'msg_type' => 'text'
//        , 'media_id' => ''
//        , 'media_path' => ''
//        , 'content' => 'jj'
//        , 'event' => ''
//        );
//
//        return $res;

        $res = array();
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        if(empty($postStr))
        {
            return $res;
        }

        //extract post data
        /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
        libxml_disable_entity_loader(true);
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

        // 下载多媒体文件
        $media_path = '';
        if($download_media && !empty($postObj->MediaId))
        {
            $media_path  = $this->_download_media($postObj->MediaId);
        }

        // 组装消息参数
        $res = array(
            'from_username' => (string)$postObj->FromUserName
            , 'to_username' => (string)$postObj->ToUserName
            , 'msg_type' => (string)$postObj->MsgType
            , 'media_id' => (string)$postObj->MediaId
            , 'media_path' => $media_path
            , 'content' => (string)$postObj->Content
            , 'event' => (string)$postObj->Event
        );

        return $res;
    }


    /**
     * 发送文字消息
     * @param $to_username
     * @param $devepoer_username
     * @param $content
     */
    public function send_text_msg($to_username, $devepoer_username, $content)
    {
        $created_time = time();
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                    </xml>";
        $resultStr = sprintf($textTpl, $to_username, $devepoer_username, $created_time, 'text', $content);//格式化写入XML
        echo $resultStr;//发送
        exit;
    }


    /*******************************************************private methods*****************************************************************************/


    /**
     * 下载多媒体
     * @param $media_id
     * @return null
     */
    private function _download_media($media_id)
    {
        if(empty($media_id))
        {
            return null;
        }

        $this->load->library('wechat/media_deliver');
        $download_file_path = $this->media_deliver->download_media($media_id);

        return $download_file_path;
    }

} 