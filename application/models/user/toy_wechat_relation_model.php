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

} 