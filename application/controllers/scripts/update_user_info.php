<?php


class update_user_info extends MY_Controller{

    const LIMIT = 100;

    public function __construct()
    {
        parent::__construct();

        if(!$this->input->is_cli_request())
        {
            throw new Exception('脚本必须以cli方式执行');
        }

        $this->load->database('default');
        $this->load->service('user/wechat_user_service');
    }


    public function index()
    {
        $page = 0;
        while(true)
        {
            $start = $page * self::LIMIT;
            $users = $this->_get_users($start, self::LIMIT);

            echo $start. '获取待完善用户' . count($users) . '个'. "\n";

            if(empty($users))
            {
                break;
            }

            $users = $this->_fill_user_info($users);
            $this->_update_users($users);

            ++$page;
        }
    }


    /**
     * 更新
     * @param $users
     */
    private function _update_users($users)
    {
        foreach($users as $row)
        {
            $this->db->where('open_id', $row['open_id']);
            $this->db->update('toy_user_wechat', $row);
        }
    }


    /**
     * 拉去微信全部信息
     * @param $users
     * @return mixed
     */
    private function _fill_user_info($users)
    {
        foreach($users as &$row)
        {
            $full_user_info = $this->wechat_user_service->get_user_info_from_wechat($row['open_id']);
            if($full_user_info->subscribe == 1){
                $row['nickname'] = $full_user_info->nickname;
                $row['sex'] = $full_user_info->sex;
                $row['language'] = $full_user_info->language;
                $row['city'] = $full_user_info->city;
                $row['province'] = $full_user_info->province;
                $row['country'] = $full_user_info->country;
                $row['subscribe_time'] = $full_user_info->subscribe_time;
                $row['headimgurl'] = $full_user_info->headimgurl;
            }
        }

        return $users;
    }



    /**
     * 获取用户信息
     * @param $start
     * @param $limit
     * @return mixed
     */
    private function _get_users($start, $limit)
    {
        $sql = <<<EOD
            SELECT
                *
            FROM toy_user_wechat
            WHERE
                subscribe_status = 1
            AND nickname = ''
            LIMIT $start,
             $limit
EOD;

        $query = $this->db->query($sql);
        return $query->result_array();
    }





} 