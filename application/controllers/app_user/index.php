<?php


class index extends \MY_Controller{


    /**
     * 构造方法
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->service('user/user_toy_service');
    }


    /**
     * 注册
     */
    public function register()
    {
        // 获取所有参数
        $params = $this->input->get_params();
        $user_name = $params['user_name'];
        $password = $params['password'];

        // 查询是否存在
        $condition = array(
            'user_name' => $user_name
        );
        $exists_list = $this->user_toy_service->get_user_toy_list($condition);
        if(!empty($exists_list))
        {
            $this->rest_fail('用户名已经存在!');
        }

        // 密码加密
        $password = md5($password);
        $res = $this->user_toy_service->register($user_name, $password);
        $this->rest_success($res);
    }


    /**
     * 登陆
     */
    public function login()
    {
        // 获取所有参数
        $params = $this->input->get_params();
        $user_name = $params['user_name'];
        $password = $params['password'];

        // 查询是否存在
        $condition = array(
            'user_name' => $user_name
            , 'password' => md5($password)
        );
        $exists_list = $this->user_toy_service->get_user_toy_list($condition);
        if(empty($exists_list))
        {
            $this->rest_fail('用户名或密码错误!');
        }
        $user_info = current($exists_list);

        $access_token = $this->session->login($user_info);

        $res = array(
            'toy_user_id' => $user_info['id']
            , 'user_name' => $user_info['user_name']
            , 'wechat_relation_status' => $user_info['wechat_relation_status']
            , 'access_token' => $access_token
        );

        $this->rest_success($res);
    }


    /**
     * 设置用户信息
     */
    public function update_user_info()
    {
        $params = $this->input->get_params();

        $user = $this->session->get_user_info();

        $this->user_toy_service->update_user($params, $user['id']);

        $this->rest_success('');
    }


    /**
     * 上传头像
     */
    public function upload_face_url()
    {
        $res = $this->resources_path->upload_toy_face_url();

        $this->rest_success($res);
    }

} 