<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class MY_Controller extends CI_Controller
{

/****************************************public methods*********************************************************************/


    public function __construct()
	{
		parent::__construct();

        if(!$this->input->is_cli_request())
        {
            // 记录请求日志
            $this->_request_log();

            // 校验登陆状态
            if(!$this->_is_no_check_login_uri())
            {
                $this->_check_login();
            }
        }

	}



    /**
     * 记录调试信息
     * @param $data
     */
    public function debug_log($data)
    {
        // 记录调试内容
        $this->_debug_log($data);
    }


    /**
     * 请求成功
     * @param $data
     * @param $msg
     */
    public function rest_success($data, $msg = '')
    {
        $rest_data = array(
            'error_code' => $this->config->my_item('toy/app_error_code', 'success')
            , 'data' => $data
            , 'msg' => $msg
        );
        echo json_encode($rest_data);
        exit();
    }



    /**
     * 请求失败
     * @param $msg
     * @param $error_code
     */
    public function rest_fail($msg, $error_code = NULL){
        $rest_data = array(
            'error_code' => empty($error_code)? $this->config->my_item('toy/app_error_code', 'fail'):$error_code
            , 'msg' => $msg
        );
        echo json_encode($rest_data);
        exit();
    }


    /**
     * 发送post请求
     * @param $url
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function make_post_request($url, $data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);print_r($output);exit;
        $res  = json_decode($output);
        return $res;
    }




 /****************************************private methods*********************************************************************/

    /**
     * 记录请求日志
     */
    private function _request_log()
    {
        $params = $_REQUEST;
        $url = $_SERVER['REQUEST_URI'];
        $this->_log(date('Y-m-d H:i:s') . '  request -- ' . $url . " params\n  "   .print_r($params, true));
    }


    /**
     * 记录调试日志
     * @param $data
     */
    private function _debug_log($data)
    {
        $url = $_SERVER['REQUEST_URI'];
        $this->_log(date('Y-m-d H:i:s') . '  debug -- '  . $url . " data\n  " .print_r($data, true));
    }


    /**
     * 记录日志
     * @param $msg
     */
    private function _log($msg)
    {
        $file_name = 'route_' . date('Y-m-d') . '.log';
        $file_path = '/var/www/dev_tool/ToyAppApi/log/';
        error_log($msg . "\n", 3, $file_path . $file_name);
    }

    /**
     * 校验登陆状态
     */
    private  function _check_login()
    {
        $params = $this->input->get_params();
        if(!isset($params['access_token']) || !$this->session->check_login($params['access_token']))
        {
            $this->rest_fail('登陆过期', $this->config->my_item('toy/app_error_code', 'login_expire'));
        }
    }


    /**
     * 校验是否是不需要校验登陆的请求
     */
    private function _is_no_check_login_uri()
    {
        $request_uri = $_SERVER['REQUEST_URI'];

        $pos = strpos($request_uri, '?');
        if($pos)
        {
            $request_uri = substr($request_uri, 0, $pos);
        }

        $out_uri_array = array(
            '/app_user/index/login'
            , '/app_user/index/register'
            , '/wechat/index/dispatch'
        );

        $out_uri_tmp = array(
//            '/wechat/index/get_qrcode'
        );

        return in_array($request_uri, array_merge($out_uri_array, $out_uri_tmp));
    }

}

	