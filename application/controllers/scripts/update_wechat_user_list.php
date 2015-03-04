<?php

class update_wechat_user_list extends MY_Controller{


    private $_exists_users;

    public function __construct()
    {
        parent::__construct();

        if(!$this->input->is_cli_request())
        {
            throw new Exception('脚本必须以cli方式执行');
        }

        $this->load->database('default');
        $this->load->service('wechat/wechat_user_service');
    }


    public function index()
    {
        $this->_exists_users = $this->_get_exists_wechat_users_from_db();
        $this->_exists_users = array_column($this->_exists_users, 'open_id');

        $next_open_id = null;
        while(true)
        {
            $res = $this->wechat_user_service->get_user_list_from_wechat($next_open_id);

            if(empty($res['next_open_id']))
            {
                break;
            }

            $user_list = $this->_filter_exists_users($res['user_list']);
            if(!empty($user_list))
            {
                $this->_add_users($user_list);
            }

            $next_open_id = $res['next_open_id'];
        }

    }




    /**
     * 从数据库查询已经存在的用户
     * @return mixed
     */
    private function _get_exists_wechat_users_from_db()
    {
        $sql = <<<EOD
            select open_id from toy_user_wechat
EOD;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }


    /**
     * 过滤掉已经存在的账号
     * @param $users
     * @return array
     */
    private function _filter_exists_users($users)
    {
        $res = array();

        foreach($users as $open_id)
        {
            if(!in_array($open_id, $this->_exists_users))
            {
                $res[]['open_id'] = $open_id;
            }
        }

        return $res;
    }


    /**
     * 增加用户
     * @param $users
     */
    private function _add_users($users)
    {
        $this->db->insert_batch('toy_user_wechat', $users);
    }



} 