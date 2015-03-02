<?php

/**
 * app用户消息model
 * Class app_msg_model
 */

class app_msg_model extends CI_Model{

    private $_table_name = 'toy_msg_app';

    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
    }


    /**
     * 新增消息
     * @param $app_unique_id
     * @param $msg_type_id
     * @param $msg_content
     * @param $msg_media
     * @param null $msg_created_time
     * @return mixed
     * @throws Exception
     */
    public  function insert($app_unique_id, $msg_type_id, $msg_content, $msg_media, $msg_created_time = null)
    {
        if(empty($app_unique_id))
        {
            throw new Exception('新增app用户消息，必须指定unique_id');
        }

        $msg_created_time = empty($msg_created_time)? date('Y-m-d H:i:s'):$msg_created_time;
        $insert_data = array(
            'app_unique_id' => $app_unique_id
            , 'msg_type_id' => $msg_type_id
            , 'msg_content' => $msg_content
            , 'msg_media' => $msg_media
            , 'created_time' => $msg_created_time
        );

        $this->db->insert($this->_table_name, $insert_data);
        return $this->db->insert_id();
    }

} 