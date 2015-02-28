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
        $a = array('3', '30', '2', '22', 'a');
        natsort($a);
        print_r($a);
        $b = array('ab21', 'a1', 'a3' , 'b1', 'c8');
        natsort($b);
        print_r($b);
        $res = 'i love you!, Sanguniang!'.date('Y-m-d', time());
        echo json_encode($res);
    }

    public function test_xmpp()
    {
        $this->load->library('xmpphp/xmpp_client');
        $this->xmpp_client->send_msg('admin@iz255gm1qk6z', 'test1@iz255gm1qk6z', 'hi');exit;
    }

    public function test_wechat()
    {
        $msg = array(
            'messageType' => 'text'
            , 'text' => 'wojiaowangchuan'
            , 'file' => ''
        );


        $from_user = 'test1';
        $to_user = 'eb2c8e820c13836';
        $post_fields = array(
            'from_user' => 'eb2c8e820c13836'
            ,'to_user' => 'test1'
            ,'msg' => json_encode($msg)
        );

        $url = '127.0.0.1:8090/im_server/servlet/ChatServlet?from_user='. $from_user
        . '&to_user=' . $to_user . '&password=' . '&msg=' . json_encode($msg);

        $this->load->library('wechat/wechat_auth');
        $res = $this->wechat_auth->https_request($url, $post_fields);

        print_r($res);
    }

} 