<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Toy_check_hook
{
	public function __construct()
	{
        $params = $_REQUEST;
        $url = $_SERVER['REQUEST_URI'];
        error_log(date('Y-m-d H:i:s').' '.$url.' '.print_r($params, true), 3, '/var/log/dev_tool/toy_app_api/access.log');
		//nothing to do yet!	
	}

    public function check_toy()
    {
        // 获取参数
        $param = $_REQUEST;
        $toy_unique_id = isset($param['toy_unique_id'])? $param['toy_unique_id']:null;

        // 添加新账号
        if(!empty($toy_unique_id))
        {
            // 获取全局类
            $CI = & get_instance();

            // 添加
            $CI->load->service('user/user_toy_service');
            $CI->user_toy_service->add_user_toy($toy_unique_id);
        }

    }
}

