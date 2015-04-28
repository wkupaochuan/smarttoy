<?php

class MY_Session extends CI_Session{

    private $_ci;


    /**************************************** public method************************************************************/
    public function __construct()
    {
        parent::__construct();
        $this->_ci = & get_instance();
    }


    /**
     * 登陆
     * @param $user_info
     * @return string
     */
    public function  login($user_info)
    {
        $access_token = $this->_generate_access_token($user_info['user_name'], $user_info['password']);
        $_SESSION['access_token'] = $access_token;

        // 设置两小时过期的
        $this->update_user_info($user_info);
        $this->_ci->redis_cache->expire($access_token, 2*3600);

        return $access_token;
    }


    /**
     * 更新session信息
     * @param $user_info
     */
    public function update_user_info($user_info)
    {
        $this->_ci->redis_cache->hMset($_SESSION['access_token'], $user_info);
    }


    /**
     * 校验是否登陆了
     * @param $access_token
     * @return bool
     */
    public function check_login($access_token)
    {
        $valid = false;
        if($this->_ci->redis_cache->exists($access_token))
        {
            $valid = true;
            $_SESSION['access_token'] = $access_token;
        }

        return $valid;
    }




    /**************************************** private method************************************************************/


    /**
     * 生成access_token
     * @param $user_name
     * @param $password
     * @return string
     */
    private function _generate_access_token($user_name, $password)
    {
        $access_token =  md5('xiaomili'.$user_name.$password);
        return $access_token;
    }



}