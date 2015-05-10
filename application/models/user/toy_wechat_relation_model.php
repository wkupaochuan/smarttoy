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
        $this->_build_select();
        $query = $this->db->get('', $limit, $offset);
        return $query->result_array();
    }


    /***************************************************** private methods **********************************************************************************************/


    private function _build_select()
    {
        $select = <<<EOD
            toy_user.id as toy_user_id, wechat_user.id as wechat_user_id, toy_user.user_name, toy_user.nick_name
            , wechat_user.open_id
EOD;

        $this->db->select($select);
    }


    /**
     * 查询
     */
    private function _select_from()
    {
        $this->db->from($this->_table_name . ' as relation');
        $this->db->join('toy_user_toy toy_user', 'toy_user.id = relation.toy_user_id', 'left');
        $this->db->join('toy_user_wechat wechat_user', 'wechat_user.id = relation.wechat_user_id', 'left');
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
                    $this->db->where('toy_user.id', $search_value);
                    break;
                case 'wechat_user_id':
                    $this->db->where('wechat_user.id', $search_value);
                    break;
                default:
                    break;
            }
        }
    }



} 