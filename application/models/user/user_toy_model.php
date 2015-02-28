<?php

class User_toy_model extends  CI_Model{

    // 表名称
    private $_table_name = 'toy_user_toy';

    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
    }


    /**
     * 添加用户
     * @param $toy_unique_id
     */
    public function add_user_toy($toy_unique_id)
    {
        $str_sql = <<<EOD
            INSERT INTO $this->_table_name
            (`toy_unique_id`)
            VALUES ('$toy_unique_id')
            ON DUPLICATE KEY UPDATE `toy_unique_id` = '$toy_unique_id'
EOD;
        $this->db->query($str_sql);
    }


    /**
     * 根据unique_id获取app用户信息
     * @param $toy_user_unique_id
     * @return mixed
     */
    public function get_user_by_unique_id($toy_user_unique_id)
    {
        $str_sql = <<<EOD
            SELECT
                toy_id, toy_unique_id, toy_name, created_time
            FROM
                $this->_table_name
            WHERE
                toy_unique_id = '$toy_user_unique_id'
EOD;
        $query = $this->db->query($str_sql);
        return $query->result_array();
    }


} 