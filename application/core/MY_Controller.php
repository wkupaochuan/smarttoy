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
        }

        // 设置出错处理方法
        set_error_handler(function ($errno, $errstr, $errfile, $errline, $errcontext){
            // 记录错误日志
            $url = $_SERVER['REQUEST_URI'];
            $msg = date('Y-m-d H:i:s') . '  error -- '  . $url . ' -- ' . $errstr. ' in file  ' .$errfile . ' on line ' . $errline . '  ' . $errcontext . "\n";
            $file_name = 'route_' . date('Y-m-d') . '.log';
            $file_path = '/var/www/dev_tool/ToyAppApi/log/';
            error_log($msg, 3, $file_path . $file_name);
        }, E_ALL);

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
    public function rest_success($data, $msg = null)
    {
        $rest_data = array(
            'data' => $data
            , 'msg' => $msg
        );
        echo json_encode($rest_data);

        // 记录返回日志
        $this->_response_log($rest_data);
        exit();
    }


    /**
     * 请求失败
     * @param $msg
     */
    public function rest_fail($msg){
        echo json_encode($msg);

        // 记录返回日志
        $this->_response_log(null, $msg);
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
     * 记录返回日志
     * @param $data
     * @param $msg
     */
    private function _response_log($data, $msg = '')
    {
        $url = $_SERVER['REQUEST_URI'];
        $this->_log(date('Y-m-d H:i:s') . '  response -- '  . $url . " data\n  " .print_r($data, true). $msg);
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

}

	