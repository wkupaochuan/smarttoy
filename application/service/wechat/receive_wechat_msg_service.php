<?php

class receive_wechat_msg_service extends MY_Service{


    /*******************************************************public methods*****************************************************************************/

    public function __construct()
    {
        parent::__construct();

        // 加载微信帮助类
        $this->load->library('wechat/wechat_auth');
        $this->load->service('user/wechat_user_service');
        $this->load->model('user/user_toy_model');
        $this->load->service('msg/wechat_msg_service');
    }


    /**
     * 处理微信推送的消息
     */
    public function handle_msg()
    {
        $res = '';
        try{
            // 解析微信消息
            $msg = $this->_decode_msg_from_wechat(true);

            switch($msg['msg_type'])
            {
                case 'text':
                case 'voice':
                case 'image':
                    $this->wechat_msg_service->handle_normal_msg_from_wechat($msg);
                    break;
                case 'event':
                    $this->_handle_event_msg($msg);
                    break;
            }
        }catch (Exception $e)
        {
            $res =  $this->_get_reponse_text_msg($msg['to_username'], $msg['from_username'], '发送失败，稍后重试!');
        }

        $res = $this->_get_reponse_text_msg($msg['to_username'], $msg['from_username'], '您的消息已经转发成功!');

        $this->log->write_log('debug', '接收消息后回复:' . $res);
        echo $res;
        die;
    }


    /*******************************************************private methods*****************************************************************************/



    /**
     * 解析微信推送的消息
     * @param bool $download_media
     * @return array
     */
    private function _decode_msg_from_wechat($download_media = false)
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

        // 下载多媒体文件
        $media_path = '';
        if($download_media && !empty($postObj->MediaId))
        {
            $this->load->library('wechat/media_deliver');
            $res = $this->media_deliver->download_media($postObj->MediaId);

            $media_path = $res['url'];
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
            , 'event_key' => isset($postObj->EventKey)? (string)$postObj->EventKey:''
            , 'ticket' => isset($postObj->ticket)? (string)$postObj->ticket:''
        );
        return $res;
    }


    /**
     * 处理event类型的消息
     * @param $msg
     */
    private function _handle_event_msg($msg)
    {
        switch($msg['event'])
        {
            // 关注或者扫描二维码
            case 'subscribe':
                $this->_handle_subscribe_event($msg);
                break;
            // 取消关注
            case 'unsubscribe':
                $this->_handle_unsubscribe_event($msg);
                break;
            // 扫描二维码
            case 'SCAN':
                $this->_handle_scan_event($msg['from_username'], $msg['to_username'], $msg['event_key']);
                break;
            default:
                break;
        }
    }


    /**
     * 处理subscribe类型的event
     * @param $msg
     */
    private function _handle_subscribe_event($msg)
    {
        // 添加微信关注者用户
        $this->wechat_user_service->subscribe($msg['from_username'], $msg['to_username']);

        // 未关注者扫描二维码事件
        if(!empty($msg['event_key']))
        {
            // 处理二维码扫描事件
            $event_key = $msg['event_key'];
            $scene_id = substr($event_key, strpos($event_key, '_') + 1);
            $this->_handle_scan_event($msg['from_username'], $msg['to_username'], $scene_id);
        }
    }


    /**
     * 处理subscribe类型的event
     * @param $msg
     */
    private function _handle_unsubscribe_event($msg)
    {
        // 添加微信关注者用户
        $this->wechat_user_service->unsubscribe($msg['from_username'], $msg['to_username']);
    }


    /**
     * 处理二维码扫描事件
     * @param $open_id
     * @param $developer_weixin_name
     * @param $scene_id
     * @return mixed
     */
    private function _handle_scan_event($open_id, $developer_weixin_name, $scene_id)
    {
        $toy_user_id = $scene_id;

        $where = array(
            'open_id' => $open_id
        , 'developer_weixin_name' => $developer_weixin_name
        );
        $wechat_user = $this->wechat_user_service->get_user_info($where);

        // 添加好友关系
        $this->load->service('user/toy_wechat_relation_service');
        $relation_id = $this->toy_wechat_relation_service->add_relation($toy_user_id, $wechat_user['id']);

        // 更新app用户的好友绑定状态
        $new_user = array('wechat_relation_status' => 1);
        $where = array('id' => $toy_user_id);
        $this->user_toy_model->update($new_user, $where);

        return $relation_id;
    }


    /**
     * 回复消息
     */
    public function _get_reponse_text_msg($from_user, $to_user, $msg_content)
    {
        $text_msg_tpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[%s]]></Content>
                <FuncFlag>%d</FuncFlag>
                </xml>";

        $resultStr = sprintf($text_msg_tpl, $from_user, $to_user, time(), $msg_content, 0);
        return $resultStr;
    }

} 