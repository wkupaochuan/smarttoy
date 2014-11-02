<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
        echo 'test time is '.date('Y-m-d');
        exit();
	}
}




/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */