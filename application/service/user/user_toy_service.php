<?php

class User_toy_service extends MY_Service{


    /*****************************************public methods*************************************************/

    public function __construct()
    {
        parent::__construct();

        // 加载model
        $this->load->model('user/user_toy_model');
    }


    /**
     * 添加新用户
     * @param $toy_unique_id
     */
    public function add_user_toy($toy_unique_id)
    {
        $data = array(
            'toy_unique_id' => $toy_unique_id
        );
        $this->user_toy_model->insert($data);
    }


    /**
     * 注册
     * @param $user_name
     * @param $password
     * @return mixed
     */
    public function register($user_name, $password)
    {
        $user = array(
            'user_name' => $user_name
            , 'password' => $password
        );
        return $this->user_toy_model->insert($user);
    }


    /**
     * 查询列表
     * @param $condition
     * @param null $offset
     * @param null $limit
     * @return mixed
     */
    public function get_user_toy_list($condition, $offset = null, $limit = null)
    {
        $res = $this->user_toy_model->get($condition, $offset, $limit);
        return $res;
    }


    /**
     * 更新用户信息
     * @param $params
     * @param $toy_user_id
     */
    public function update_user($params, $toy_user_id)
    {
        $where = array(
            'id' => $toy_user_id
        );

        $user_data = array();

        // 头像
        if(isset($params['face_url']) && !empty($params['face_url']))
        {
            $user_data['face_url'] = $params['face_url'];
        }

        // 昵称
        if(isset($params['nick_name']) && !empty($params['nick_name']))
        {
            $user_data['nick_name'] = $params['nick_name'];
        }

        // 生日
        if(isset($params['birhday']) && !empty($params['birhday']))
        {
            $user_data['birhday'] = $params['birhday'];
        }

        // 性别
        if(isset($params['sex']) && !empty($params['sex']))
        {
            $user_data['sex'] = $params['sex'];
        }

        // 性别
        if(isset($params['phone_num']) && !empty($params['phone_num']))
        {
            $user_data['phone_num'] = $params['phone_num'];
        }

        $this->user_toy_model->update($user_data,$where);
    }


    /*****************************************private methods*************************************************/

} 