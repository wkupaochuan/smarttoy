<?php

class qrcode_service extends MY_Service{

    // 临时 OR 永久二维码URL
    const QRCODE_URL = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=';

    // 二维码展示地址
    const SHOW_QRCODE_URL = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=';

/************************************public methods******************************************************************/

    public function __construct()
    {
        parent::__construct();

        // 加载微信帮助类
        $this->load->library('wechat/wechat_auth');
    }


    /**
     * 创建临时二维码
     * @param $scene_id
     * @return null
     */
    public function create_qrcode($scene_id)
    {
        if(empty($scene_id))
        {
            return null;
        }

        // 拼接请求地址
        $access_token = $this->wechat_auth->get_access_token();
        $url = self::QRCODE_URL . $access_token;

        $body = <<<EOD
                {
                    "expire_seconds": 604800,
                    "action_name": "QR_SCENE",
                    "action_info": {
                        "scene": {
                            "scene_id": 1000
                        }
                    }
                }
EOD;

        $res = $this->wechat_auth->https_request($url, $body);

        $res['qr_code_url'] = self::SHOW_QRCODE_URL . $res['ticket'];
        return $res;
    }


/************************************private methods******************************************************************/


} 