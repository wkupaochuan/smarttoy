<?php
/**
 * Created by PhpStorm.
 * User: jixiaofeng
 * Date: 14-8-27
 * Time: 17:29
 */



class Message_model extends  \CI_Model{

    public function __construct()
    {
        $this->load->database('default');
    }

    /**
     * 新增一条信息
     * @param $fromUser
     * @param $toUser
     * @param $content
     */
    public function createMessage($fromUser, $toUser, $content)
    {
        $message = array(
            'from_user' => $fromUser,
            'to_user' => $toUser,
            'content' => $content
        );
        $this->db->insert('st_message', $message);
    }


    /**
     * 获取信息
     */
    public function getMessages()
    {
        $query = $this->db->query('select * from st_message order by created_time desc');
        return $query->result_array();
    }



} 