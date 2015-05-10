<?php


class wechat_msg_service extends MY_Service{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('msg/wechat_msg_model');
        $this->load->service('user/wechat_user_service');
    }



    /**************************************public methods****************************************************************************/

    /**
     * 处理微信推送的普通消息(文字、图片、声音)
     * @param $msg_from_wechat array('from_username', 'to_username', 'msg_type', 'media_id', 'media_path', 'content', 'event', 'event_key', 'ticket' );
     * @return bool
     */
    public function handle_normal_msg_from_wechat($msg_from_wechat)
    {
        // 获取对应的微信用户
        $where = array('open_id' => $msg_from_wechat['from_username'], 'developer_weixin_name' => $msg_from_wechat['to_username']);
        $wechat_user_info = $this->wechat_user_service->get_user_info($where);
        if(empty($wechat_user_info))
        {
            return false;
        }

        // add wechat msg
        $this->_save_msg_from_wechat($msg_from_wechat, $wechat_user_info);

        // send msg to app user
        $this->_forward_msg_to_app($msg_from_wechat, $wechat_user_info);
    }




    /**************************************private methods****************************************************************************/


    /**
     * 新增消息
     * @param $msg_from_wechat
     * @param $wechat_user_info
     * @return mixed
     */
    private function _save_msg_from_wechat($msg_from_wechat, $wechat_user_info)
    {
        // 获取消息类型id
        $this->load->service('msg/msg_type_enum_service');
        $msg_type_id = $this->msg_type_enum_service->get_msg_type_by_wechat_msg_type($msg_from_wechat['msg_type']);

        $data = array(
            'wechat_user_id' => $wechat_user_info['id']
            , 'msg_type_id' => $msg_type_id
            , 'msg_content' => $msg_from_wechat['content']
            , 'msg_media_id' => $msg_from_wechat['media_id']
            , 'msg_media_path' => $msg_from_wechat['media_path']
        );

        return $this->wechat_msg_model->insert($data);
    }



    /**
     * 转发微信消息到app用户
     * @param $msg_from_wechat
     * @param $wechat_user_info
     * @return bool
     */
    private function _forward_msg_to_app($msg_from_wechat, $wechat_user_info)
    {
        // find children
        $this->load->service('user/toy_wechat_relation_service');
        $toy_users = $this->toy_wechat_relation_service->get_child_toy_users($wechat_user_info['id']);

        // 没有绑定的孩子，直接返回
        if(empty($toy_users))
        {
            return true;
        }


        // 构建消息
        $media_path = $msg_from_wechat['media_path'];
        if(!empty($media_path))
        {
            $media_path = '';
        }

        $msg = array(
            'messageType' => $msg_from_wechat['msg_type']
            , 'content' => $msg_from_wechat['content']
            , 'media_path' => $media_path
        );

        // todo 暂时统一使用admin用户发送消息
        $from_user = 'admin';

        // send msg
        foreach($toy_users as $child)
        {
            // 获取app用户
            $to_user = $child['user_name'];

            $url = '127.0.0.1:8090/im_server/servlet/ChatServlet?from_user='. $from_user
                . '&to_user=' . $to_user . '&password=' . '&msg=' . json_encode($msg);

            $res = $this->curl->request($url);
        }
    }



} 