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



    /*****************************************private methods*************************************************/

} 