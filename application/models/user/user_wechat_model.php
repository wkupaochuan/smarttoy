<?php


/**
 * 微信关注者用户列表
 * Class user_wechat_model
 */

class user_wechat_model extends CI_Model{

    // 表名称
    private $_table_name = 'toy_user_wechat';

    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
    }


    /**
     * 添加用户
     * @param $open_id
     * @param $developer_weixin_name
     * @return mixed
     */
    public function add_user($open_id, $developer_weixin_name)
    {
        $subscribe_time = time();

        $str_sql = <<<EOD
            INSERT INTO {$this->_table_name} (`open_id`, `subscribe_time`, `developer_weixin_name`)
            VALUES
                ('$open_id', $subscribe_time, '$developer_weixin_name')
                ON DUPLICATE KEY UPDATE `subscribe_time` = $subscribe_time, `subscribe_status` = 1
EOD;
        $this->db->query($str_sql);
        return $this->db->insert_id();
    }



    /**
     * 用户取消关注
     * @param $open_id
     * @param $developer_weixin_name
     */
    public function user_unsubscribe($open_id, $developer_weixin_name)
    {
        $unsubscribe_time = time();

        $str_sql = <<<EOD
            UPDATE {$this->_table_name}
            SET subscribe_status = 0,
             unsubscribe_time = $unsubscribe_time
            WHERE
                open_id = '$open_id'
            AND developer_weixin_name = '$developer_weixin_name'
EOD;
        $this->db->query($str_sql);
    }


    /**
     * 根据open_id和公众号获取用户信息
     * @param $opend_id
     * @param $developer_weixin_id
     * @return mixed
     */
    public function get_user_by_opend_id($opend_id, $developer_weixin_id)
    {
        $sql = <<<EOD
            SELECT
                id, open_id, developer_weixin_name, subscribe_status
            FROM
                toy_user_wechat
            WHERE
                open_id = '$opend_id' and developer_weixin_name = '$developer_weixin_id' and subscribe_status = 1
EOD;
        $query = $this->db->query($sql);
        return $query->result_array();
    }


} 