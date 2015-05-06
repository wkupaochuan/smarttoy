<?php

class toy_wechat_relation_model extends CI_Model{

    private $_table_name = 'toy_user_toy_wechat_relationship';

    /***************************************************** public methods **********************************************************************************************/

    public function __construct(){
        parent::__construct();
        $this->load->database('default');
    }


    /**
     * 新增
     * @param $relation
     * @return mixed
     */
    public function insert($relation)
    {
        $this->db->insert($this->_table_name, $relation);
        return $this->db->insert_id();
    }


    /**
     * 更新
     * @param $relation
     * @param $where
     */
    public function update($relation, $where)
    {
        $this->db->update($this->_table_name, $relation, $where);
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


    /**
     * 查询
     * @param $condition
     * @param null $limit
     * @param $offset
     * @return mixed
     */
    public function get($condition, $limit = null, $offset = null)
    {
        $this->_build_where_for_select($condition);
        $this->_select_from();
        $query = $this->db->get('', $limit, $offset);
        return $query->result_array();
    }


    /***************************************************** private methods **********************************************************************************************/



    /**
     * 查询
     */
    private function _select_from()
    {
        $this->db->from($this->_table_name);
    }


    /**
     * 构造查询条件
     * @param $condition
     */
    private function _build_where_for_select($condition)
    {
        foreach($condition as $search_key => $search_value)
        {
            $search_value = trim($search_value);
            if(strlen($search_value) === 0)
            {
                continue;
            }

            switch($search_key)
            {
                case 'toy_user_id':
                    $this->db->where('toy_user_id', $search_value);
                    break;
                case 'wechat_user_id':
                    $this->db->where('wechat_user_id', $search_value);
                    break;
                default:
                    break;
            }
        }
    }



} 