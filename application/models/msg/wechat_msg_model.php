<?php

/**
 * 微信用户消息model
 * Class wechat_msg_model
 */

class wechat_msg_model extends CI_Model{

    private $_table_name = 'toy_msg_wechat';

    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
    }


    /**
     * 新增消息
     * @param $wechat_open_id
     * @param $msg_type_id
     * @param $msg_content
     * @param $msg_media
     * @param $media_id
     * @param null $msg_created_time
     * @return mixed
     * @throws Exception
     */
    public  function insert($wechat_open_id, $msg_type_id, $msg_content, $msg_media, $media_id, $msg_created_time = null)
    {
        if(empty($wechat_open_id))
        {
            throw new Exception('新增微信用户消息，必须指定open_id');
        }

        $msg_created_time = empty($msg_created_time)? date('Y-m-d H:i:s'):$msg_created_time;
        $insert_data = array(
            'wechat_open_id' => $wechat_open_id
            , 'msg_type_id' => $msg_type_id
            , 'msg_content' => $msg_content
            , 'msg_media' => $msg_media
            , 'msg_media_id' => $media_id
            , 'created_time' => $msg_created_time
        );

        $this->db->insert($this->_table_name, $insert_data);
        return $this->db->insert_id();
    }

} 