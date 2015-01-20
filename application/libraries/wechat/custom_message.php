<?php


/**
 * 客服接口
 * Class custom_message
 */

class custom_message {

    // wkupaochuan的open id
    private $toUsername = 'og0UpuEhZ0No4K7Wf0DflsBYQzPE';

    // 文字类型消息
    const MSG_TYPE_TEXT = 'text';


    /**
     * 发送文字消息
     * @param $content
     */
    public function send_text_message($content)
    {
        $body = array (
            'touser' => $this->toUsername
            ,'msgtype' => self::MSG_TYPE_TEXT
            ,'text' => array(
                    'content' => $content
                )
        );
        $this->_send_message($body);
    }


    /**
     * 发送消息
     * @param $body
     */
    private function _send_message($body)
    {
        // wkupaochuan 的open_id


        // 获取access token
        $CI = & get_instance();
        $CI->load->library('wechat/wechat_auth');
        $access_token = $CI->wechat_auth->get_access_token();

        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;

        $body = json_encode($body);

        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url);
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; encoding=gb2312',
                'Content-Length: ' . mb_strlen($body)
            )
        );
        curl_exec ( $ch );
        curl_close ( $ch );
    }

} 