<?php

class receive_wechat_msg_service extends MY_Service{

    public function __construct()
    {
        parent::__construct();

        // 加载微信帮助类
        $this->load->library('wechat/wechat_auth');
    }


    /*******************************************************public methods*****************************************************************************/


    public function get_msg()
    {
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

        $res = array(
            'from_username' => (string)$postObj->FromUserName
            , 'to_username' => (string)$postObj->ToUserName
            , 'msg_type' => (string)$postObj->MsgType
            , 'media_id' => (string)$postObj->MediaId
            , 'content' => (string)$postObj->Content
        );

        return $res;
    }


    /*******************************************************private methods*****************************************************************************/

} 