<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: wangchuan
 * Date: 14-10-8
 * Time: 22:05
 */

class Webtest extends  \CI_Controller{

    public function index()
    {

        $res = 'i love you!, Sanguniang!'.date('Y-m-d', time());
        echo json_encode($res);
    }

} 