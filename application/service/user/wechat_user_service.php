<?php

class wechat_user_service extends MY_Service{



/************************************public methods******************************************************************/


    public function __construct()
    {
        parent::__construct();

        $this->load->model('user/user_wechat_model');
        $this->load->library('wechat/wechat_auth');
    }


    /**
     * 用户关注
     * @param $open_id
     * @param $developer_weixin_name
     * @return mixed
     */
    public function subscribe($open_id, $developer_weixin_name)
    {
        // 查找是否已经入库
        $where = array(
            'open_id' => $open_id
            , 'developer_weixin_name' => $developer_weixin_name
        );
        $exists_users = $this->get_user_info($where);

        $new_user = array(
            'open_id' => $open_id
        , 'developer_weixin_name' => $developer_weixin_name
        );

        // 更新
        if($exists_users)
        {
            $user = array(
                'subscribe_status' => 1
            );

            $this->user_wechat_model->update($user, $new_user);
        }
        // 新增
        else{
            return $this->user_wechat_model->insert($new_user);
        }
    }


    /**
     * 取消关注
     * @param $open_id
     * @param $developer_weixin_name
     * @return mixed
     */
    public function unsubscribe($open_id, $developer_weixin_name)
    {
        // 查找是否已经入库
        $where = array(
            'open_id' => $open_id
        , 'developer_weixin_name' => $developer_weixin_name
        );
        $exists_users = $this->get_user_info($where);

        $where = array(
            'open_id' => $open_id
        , 'developer_weixin_name' => $developer_weixin_name
        );

        // 不存在，直接入库
        if(!$exists_users)
        {
            $where['subscribe_status'] = 0;
            return $this->user_wechat_model->insert($where);
        }
        // 存在则更新状态
        else{
            $user = array(
                'subscribe_status' => 0
            );
            // 更新关注状态
            $this->user_wechat_model->update($user, $where);

            // 更新对应的app用户的绑定状态
        }
    }


    /**
     * 获取单个user info
     * @param $condition
     * @return bool|mixed
     */
    public function get_user_info($condition)
    {
        $exists_users = $this->user_wechat_model->get($condition);

        return !empty($exists_users) && count($exists_users) === 1? current($exists_users):false;
    }



    public function get_user_list(){}


    /**
     * 从微信拉去用户全部信息
     * @param $wechat_open_id
     * @return null
     */
    public function get_user_info_from_wechat($wechat_open_id)
    {
        if(empty($wechat_open_id))
        {
            return null;
        }

        $access_token = $this->wechat_auth->get_access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $wechat_open_id . '&lang=zh_CN';

        $wechat_user_info = $this->wechat_auth->https_request($url);
        return $wechat_user_info;
    }




    /**
     * 从微信拉取用户列表
     * @param null $next_openid
     * @return array
     */
    public function get_user_list_from_wechat($next_openid = null)
    {
        $access_token = $this->wechat_auth->get_access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$access_token.'&next_openid=';
        if(!empty($next_openid))
        {
            $url .= $next_openid;
        }

        $res = $this->wechat_auth->https_request($url);

        return array(
            'user_list' => $res->data->openid
            , 'next_open_id' => $res->next_openid
        );
    }



/************************************private methods******************************************************************/


    private function update_wechat_relation_status_by_wechat_user_id($wechat_user_id)
    {
        if(empty($wechat_user_id))
        {
            return null;
        }

        $this->load->service('user/user_toy_service');
        $where = array();
        $toy_users = $this->user_toy_service->get_user_toy_list();

    }


} 