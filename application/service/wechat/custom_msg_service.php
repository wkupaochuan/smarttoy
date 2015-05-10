<?php

class custom_msg_service extends MY_Service{

    // 文字类型消息
    const MSG_TYPE_TEXT = 'text';

    // 发送客服消息的url
    const CUSTOM_MSG_URL = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=';

/************************************public methods******************************************************************/

    public function __construct()
    {
        parent::__construct();

        $this->load->library('wechat/media_deliver');
    }


    /**
     * 发送客服消息
     * @param $to_user          消息接收人
     * @param $msg_type         消息类型
     * @param $msg_content      消息内容
     * @param $file_path        文件地址
     */
    public function send_msg($to_user, $msg_type, $msg_content, $file_path)
    {
        // 处理文件路径
        if(!empty($file_path))
        {
            $file_path = $this->resources_path->get_resource_path($file_path);
        }
        switch($msg_type)
        {
            case 'text':
                $this->_send_text_msg($to_user, $msg_content);
                break;
            case 'voice':
                $this->_send_voice_msg($to_user, $file_path);
                break;
            case 'image':
                $this->_send_image_msg($to_user, $file_path);
                break;
        }
    }


/************************************private methods******************************************************************/

    /**
     * 发送文字类型消息
     * @param $to_user          消息接收人
     * @param $msg_content      消息内容
     */
    private function _send_text_msg($to_user, $msg_content)
    {
        $body = <<<EOD
                {
                    "touser":"$to_user",
                    "msgtype":"text",
                    "text":
                    {
                         "content":"$msg_content"
                    }
                }
EOD;

        // todo 处理发送结果
        $this->_send($body);
    }


    /**
     * 发送图片消息
     * @param $to_user
     * @param $file_path
     * @throws Exception
     */
    private function _send_image_msg($to_user, $file_path)
    {
        $media_id = $this->media_deliver->upload_image($file_path);

        if(empty($media_id))
        {
            throw new Exception('上传图片到微信失败');
        }

        $body = <<<EOD
                {
                    "touser":"$to_user",
                    "msgtype":"image",
                    "image":
                    {
                         "media_id":"$media_id"
                    }
                }
EOD;

        // todo 处理发送结果
        $this->_send($body);
    }

    /**
     * 发送语音消息
     * @param $to_user
     * @param $file_path
     * @throws Exception
     */
    private function _send_voice_msg($to_user, $file_path)
    {
        $media_id = $this->media_deliver->upload_voice($file_path);
        if(empty($media_id))
        {
            throw new Exception('上传音频到微信失败');
        }

        $body = <<<EOD
                {
                    "touser":"$to_user",
                    "msgtype":"voice",
                    "voice":
                    {
                         "media_id":"$media_id"
                    }
                }
EOD;

        // todo 处理发送结果
        $this->_send($body);
    }


    /**
     * 发送消息
     * @param $body
     */
    private function _send($body)
    {
        // 获取access token
        $access_token = $this->wechat_auth->get_access_token();

        // 拼接url
        $url = self::CUSTOM_MSG_URL.$access_token;

        // 发送请求
        $this->curl->wechat_request($url, $body);
    }


} 