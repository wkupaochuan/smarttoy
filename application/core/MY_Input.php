<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class MY_Input extends CI_Input
{

/****************************************public methods*********************************************************************/


    public function __construct()
	{
		parent::__construct();
	}


    /**
     * 获取参数
     * @param null $index
     * @param $xss_clean
     * @return string
     */
    public function get_params($index = NULL, $xss_clean)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == 'POST')
        {
            return $this->post($index, $xss_clean);
        }
        else{
            return $this->get($index, $xss_clean);
        }
    }
}

	