<?php

class class_model extends CI_Model{

    // 表名称
    private static $_table_name = 'toy_class';

    /**
     * 构造方法
     * 1-- 加载数据库类
     */
    public function __construct()
    {
        $this->load->database('default');
    }


    /**
     * 获取课程列表
     * @return mixed
     */
    public function get_class_list()
    {
        // 查询sql
        $sql = 'select * from '.self::$_table_name;

        // 查询
        $query = $this->db->query($sql);

        // 返回数据
        return $query->result_array();
    }

} 