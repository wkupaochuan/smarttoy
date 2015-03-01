<?php

class toy_wechat_relation_model extends CI_Model{

    private $_table_name = 'toy_user_toy_wechat_relationship';

    public function __construct(){
        parent::__construct();
        $this->load->database('default');
    }


    /**
     * 新增好友关系
     * @param $toy_id
     * @param $weixin_id
     * @return mixed
     */
    public function add_relationship($toy_id, $weixin_id)
    {
        $sql = <<<EOD
            INSERT INTO $this->_table_name (toy_id, weixin_id)
            VALUES
                ($toy_id, $weixin_id)
EOD;

        $this->db->query($sql);
        return $this->db->insert_id();
    }


    /**
     * 查找app用户对应的微信好友
     * @param $toy_unique_id
     * @return mixed
     */
    public function get_parent_wechat_user_by_toy_unique_id($toy_unique_id)
    {
        $sql = <<<EOD
            select wechat.open_id, wechat.developer_weixin_name from toy_user_toy as toy
            left join toy_user_toy_wechat_relationship as relation on relation.toy_id = toy.toy_id
            left join toy_user_wechat as wechat on relation.weixin_id = wechat.id
            where wechat.open_id is  not null and toy.toy_unique_id = '{$toy_unique_id}'
EOD;

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /**
     * 根据微信用户，查找app用户
     * @param $wechat_open_id
     * @return mixed
     */
    public function get_child_toy_user_by_wechat_open_id($wechat_open_id)
    {
        $sql = <<<EOD
            SELECT
                toy.toy_id,
                toy.toy_unique_id
            FROM
                toy_user_wechat AS wechat
            LEFT JOIN toy_user_toy_wechat_relationship AS relation ON relation.weixin_id = wechat.id
            LEFT JOIN toy_user_toy AS toy ON relation.toy_id = toy.toy_id
            WHERE
                wechat.open_id IS NOT NULL
            AND wechat.open_id = '{$wechat_open_id}'
EOD;

        $query = $this->db->query($sql);
        return $query->result_array();
    }

} 