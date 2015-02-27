<?php

require_once dirname(__FILE__) . "/XMPP.php";

class xmpp_client {


    public function send_msg($from_user, $to_user, $msg)
    {
        $conn = new XMPPHP_XMPP('182.92.130.192', 5222, $from_user, 'ijnUHB', null);

        try {
            $conn->connect();
            $conn->processUntil('session_start');
            $conn->presence();
            $conn->message($to_user, $msg);
            $conn->disconnect();
        } catch (XMPPHP_Exception $e) {
            echo $e->getMessage();exit;
        }
    }

    public function test()
    {
        echo __FILE__;
    }

} 