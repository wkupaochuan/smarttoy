<?php

class wechat_user_service extends MY_Service{



/************************************public methods******************************************************************/


    public function __construct()
    {
        parent::__construct();

        $this->load->model('user/user_wechat_model');

    }


    /**
     * 获取玩具用户对应的父母微信号
     * @param $toy_user
     * @return mixed
     * todo 完善用户系统
     */
    public function get_parent_wechat_user($toy_user)
    {
        $dic = array(
            // 王川 小米3
            'eb2c8e820c13836' => 'og0UpuEhZ0No4K7Wf0DflsBYQzPE'
        );

//        return $dic[$toy_user];
        return 'og0UpuEhZ0No4K7Wf0DflsBYQzPE';
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