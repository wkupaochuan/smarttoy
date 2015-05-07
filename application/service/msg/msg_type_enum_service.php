<?php

class msg_type_enum_service extends MY_Service{

    const MSG_TYPE_TEXT_ID = 1;
    const MSG_TYPE_VOICE_ID = 2;
    const MSG_TYPE_IMAGE_ID = 3;
    const MSG_TYPE_EVENT_ID = 4;

    const MSG_TYPE_TEXT_DISPLAY = 'text';
    const MSG_TYPE_VOICE_DISPLAY = 'voice';
    const MSG_TYPE_IMAGE_DISPLAY = 'image';
    const MSG_TYPE_EVENT_DISPLAY = 'event';

    private $_msg_type_id_display_map = array(
        self::MSG_TYPE_TEXT_ID => self::MSG_TYPE_TEXT_DISPLAY
        ,self::MSG_TYPE_VOICE_ID => self::MSG_TYPE_VOICE_DISPLAY
        ,self::MSG_TYPE_IMAGE_ID => self::MSG_TYPE_IMAGE_DISPLAY
        ,self::MSG_TYPE_EVENT_ID => self::MSG_TYPE_EVENT_DISPLAY
    );


    /******************************public methods*********************************************************************************************************/

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 获取消息类型ID
     * @param $msg_type_display
     * @return null
     */
    public function get_type_id_by_display($msg_type_display)
    {
        $map = array_flip($this->_msg_type_id_display_map);
        return empty($map[$msg_type_display])? null:$map[$msg_type_display];
    }


    /**
     * 获取消息类型的展示
     * @param $msg_type_id
     * @return null
     */
    public function get_type_display_by_id($msg_type_id)
    {
        return empty($this->_msg_type_id_display_map[$msg_type_id])? null:$this->_msg_type_id_display_map[$msg_type_id];
    }



    /**
     * 根据微信消息类型获取本系统消息类型
     * @param $wechat_msg_type
     * @return bool
     */
    public function get_msg_type_by_wechat_msg_type($wechat_msg_type)
    {
        $dic = array_flip($this->_msg_type_id_display_map);
        return isset($dic[$wechat_msg_type])? $dic[$wechat_msg_type]:null;
    }

    /******************************private methods*********************************************************************************************************/



} 