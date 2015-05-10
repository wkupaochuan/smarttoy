<?php

class toy_wechat_relation_service extends MY_Service{

    public function __construct(){
        parent::__construct();
        $this->load->model('user/toy_wechat_relation_model');
    }




 /****************************public methods******************************************************************************************/


    /**
     * 根据微信用户，查找app用户
     * @param $wechat_user_id
     * @return mixed
     */
    public function get_child_toy_users($wechat_user_id)
    {
        $where = array('wechat_user_id' => $wechat_user_id);
        return $this->toy_wechat_relation_model->get($where);
    }


    /**
     * 根据app 用户unique_id查找好友的微信号
     * @param $toy_user_id
     * @return mixed
     */
    public function get_parent_wechat_user($toy_user_id)
    {
        $where = array('toy_user_id' => $toy_user_id);
        return $this->toy_wechat_relation_model->get($where);
    }


    /**
     * 新增好友关系
     * @param $toy_user_id
     * @param $wechat_user_id
     * @return mixed
     */
    public function add_relation($toy_user_id, $wechat_user_id)
    {
        $where = array(
            'toy_user_id' => $toy_user_id
            , 'wechat_user_id' => $wechat_user_id
        );
        $exists_relations = $this->toy_wechat_relation_model->get($where);

        $this->log->write_log('debug', '添加关系，已有关系:'.var_export($exists_relations, true));
        if(empty($exists_relations))
        {
            return $this->toy_wechat_relation_model->insert($where);
        }
    }


 /****************************private methods******************************************************************************************/


} 