<?php

class wechat_user_service extends MY_Service{



/************************************public methods******************************************************************/


    public function __construct()
    {
        parent::__construct();

        $this->load->model('user/user_wechat_model');

    }


    /**
     * 处理用户关注取消关注事件
     * @param $msg_data
     * @return bool
     */
    public function handle_user_subscribe_event($msg_data)
    {
        $user_open_id = $msg_data['from_username'];
        $developer_weixin_name = $msg_data['to_username'];
        switch ($msg_data['event'])
        {
            case 'subscribe':
                $this->_add_wechat_user($user_open_id, $developer_weixin_name);
                break;
            case 'unsubscribe':
                $this->_user_unsubscribe($user_open_id, $developer_weixin_name);
                break;
        }

        return true;
    }




/************************************private methods******************************************************************/

    /**
     * 增加微信号
     * @param $open_id
     * @param $developer_weixin_name
     * @return mixed
     */
    private function _add_wechat_user($open_id, $developer_weixin_name)
    {
        $user_id = $this->user_wechat_model->add_user($open_id, $developer_weixin_name);
        return $user_id;
    }

    /**
     * 用户取消关注
     * @param $open_id
     * @param $developer_weixin_name
     */
    private function _user_unsubscribe($open_id, $developer_weixin_name)
    {
        $this->user_wechat_model->user_unsubscribe($open_id, $developer_weixin_name);
    }

} 