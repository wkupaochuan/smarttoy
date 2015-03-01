<?php

class toy_wechat_relation_service extends MY_Service{

    public function __construct(){
        parent::__construct();
        $this->load->model('user/toy_wechat_relation_model');
    }




 /****************************public methods******************************************************************************************/


    /**
     * 根据微信用户，查找app用户
     * @param $toy_wechat_open_id
     * @return mixed
     */
    public function get_child_toy_user($toy_wechat_open_id)
    {
        $toy_user_info = $this->toy_wechat_relation_model->get_child_toy_user_by_wechat_open_id($toy_wechat_open_id);
        if(empty($toy_user_info))
        {}
        else if(count($toy_user_info) > 1)
        {}
        else{
            return $toy_user_info[0]['toy_unique_id'];
        }
    }


    /**
     * 根据app 用户unique_id查找好友的微信号
     * @param $toy_user_unique_id
     * @return mixed
     */
    public function get_parent_wechat_user($toy_user_unique_id)
    {
        $wechat_user_info = $this->toy_wechat_relation_model->get_parent_wechat_user_by_toy_unique_id($toy_user_unique_id);
        if(empty($wechat_user_info))
        {}
        else if(count($wechat_user_info) > 1)
        {}
        else{
            return $wechat_user_info[0]['open_id'];
        }
    }



    /**
     * 处理好友关注、取消消息
     * @param $msg_data
     * @return mixed
     */
    public function handle_relationship_msg($msg_data)
    {
        // 消息内容
        $msg_content = $msg_data['content'];
        $toy_user_unique_id = $this->_get_toy_user_unique_id_from_msg($msg_content);
        $this->load->model('user/user_toy_model');
        $toy_user_info = $this->user_toy_model->get_user_by_unique_id($toy_user_unique_id);
        // todo 多个或者没有app用户
        if(empty($toy_user_info))
        {}
        else if(count($toy_user_info) > 1)
        {}
        else{
            $toy_user_id = $toy_user_info[0]['toy_id'];
        }

        $weixin_open_id = $msg_data['from_username'];
        $developer_weixin_id = $msg_data['to_username'];
        $this->load->model('user/user_wechat_model');
        $weixin_user_info = $this->user_wechat_model->get_user_by_opend_id($weixin_open_id, $developer_weixin_id);
        // todo 多个或者没有app用户
        if(empty($weixin_user_info))
        {}
        else if (count($weixin_user_info) > 1)
        {}
        else{
            $weixin_user_id = $weixin_user_info[0]['id'];
        }

        return $this->_add_relationship($toy_user_id, $weixin_user_id);
    }


 /****************************private methods******************************************************************************************/


    /**
     * 从消息内容中获取app用户唯一id
     * @param $msg_content
     * @return string
     */
    private function _get_toy_user_unique_id_from_msg($msg_content)
    {
        $toy_user_unique_id = substr($msg_content, strpos($msg_content, 'gz:') + 3);
        return $toy_user_unique_id;
    }



    /**
     * 增加好友关系
     * @param $toy_user_id
     * @param $weixin_user_id
     * @return mixed
     */
    private function _add_relationship($toy_user_id, $weixin_user_id)
    {
        return $this->toy_wechat_relation_model->add_relationship($toy_user_id, $weixin_user_id);
    }


} 