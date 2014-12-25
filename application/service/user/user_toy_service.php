<?php

class User_toy_service extends MY_Service{

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
        $this->user_toy_model->add_user_toy($toy_unique_id);
    }
} 