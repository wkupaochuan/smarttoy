<?php
/**
 * Created by PhpStorm.
 * User: Gain
 * Date: 14-10-10
 * Time: 17:38
 */


/**
 * mp3 model
 * Class Mp3_model
 */
class Mp3_model extends CI_Model{

    // 数据库表名常量
    const TABLE_NAME_MP3 = 'toy_mp3_item';

    /**
     * 构造方法
     * 1-- 加载数据库类
     */
    public function __construct()
    {
        $this->load->database('default');
    }


    /**
     * 获取故事列表
     */
    public function get_sotry_list()
    {
        // 查询sql
        $sql = 'select * from '.self::TABLE_NAME_MP3;

        // 查询
        $query = $this->db->query($sql);

        // 返回数据
        return $query->result_array();
    }

} 