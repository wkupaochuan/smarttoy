<?php

class User_toy_model extends  CI_Model{

    // 表名称
    private static $_table_name = 'toy_user_toy';

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
            INSERT INTO toy_user_toy
            (`toy_unique_id`)
            VALUES ('$toy_unique_id')
            ON DUPLICATE KEY UPDATE `toy_unique_id` = '$toy_unique_id'
EOD;
        $this->db->query($str_sql);
    }


} 