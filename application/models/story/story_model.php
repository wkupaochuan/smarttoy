<?php

class Story_model extends CI_Model{

    private $_table_name = 'toy_story';


    /***************************************************** public methods **********************************************************************************************/


    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
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
                case 'is_deleted':
                    $this->db->where('is_deleted', $search_value);
                    break;
                case 'toy_unique_id':
                    $this->db->where('toy_unique_id', $search_value);
                    break;
                default:
                    break;
            }
        }
    }
} 