<?php

class wechat_user_service extends MY_Service{



/************************************public methods******************************************************************/


    public function __construct()
    {
        parent::__construct();

    }


    /**
     * 获取玩具用户对应的父母微信号
     * @param $toy_user
     * @return mixed
     * todo 完善用户系统
     */
    public function get_parent_wechat_user($toy_user)
    {
        $dic = array(
            // 王川 小米3
            'eb2c8e820c13836' => 'og0UpuEhZ0No4K7Wf0DflsBYQzPE'
        );

//        return $dic[$toy_user];
        return 'og0UpuEhZ0No4K7Wf0DflsBYQzPE';
    }




/************************************private methods******************************************************************/


} 