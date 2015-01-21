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

    // 发送客服消息的url
    const CUSTOM_MSG_URL = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=';


    /**
     * 发送文字消息
     * @param $content
     */
    public function send_text_message($content)
    {
        $body = '{
                    "touser":"' . $this->toUsername . '",
                    "msgtype":"text",
                    "text":
                    {
                         "content":"'. $content .'"
                    }
                }';
        $this->_send_message($body);
    }


    /**
     * 发送图片消息
     */
    public function send_image_message()
    {
        $CI = &get_instance();
        $CI->load->library('wechat/media_deliver');
        $media_id = $CI->media_deliver->upload_voice();

        $body = '{
                    "touser":"' . $this->toUsername . '",
                    "msgtype":"image",
                    "image":
                    {
                         "media_id":"'. $media_id .'"
                    }
                }';
        $this->_send_message($body);
    }


    /**
     * 发送消息
     * @param $body
     */
    private function _send_message($body)
    {
        // 获取access token
        $CI = & get_instance();
        $CI->load->library('wechat/wechat_auth');
        $access_token = $CI->wechat_auth->get_access_token();

        $url = self::CUSTOM_MSG_URL.$access_token;

        $CI->load->library('wechat/wechat_auth');
        $CI->wechat_auth->https_request($url, $body);
    }

} 