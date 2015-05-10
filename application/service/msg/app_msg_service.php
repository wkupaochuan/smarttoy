<?php


class app_msg_service extends MY_Service{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('msg/app_msg_model');
    }



    /**************************************public methods****************************************************************************/

    /**
     * 保存app用户消息
     * @param $wechat_user_id
     * @param $msg_content
     * @param $msg_type
     * @param $msg_media_path
     * @return mixed
     */
    public function add_msg($wechat_user_id, $msg_content, $msg_type, $msg_media_path = '')
    {
        // 获取消息类型id
        $this->load->service('msg/msg_type_enum_service');
        $msg_type_id = $this->msg_type_enum_service->get_type_id_by_display($msg_type);

        $data = array(
            'toy_user_id' => $wechat_user_id
        , 'msg_type_id' => $msg_type_id
        , 'msg_content' => $msg_content
        , 'msg_media_path' => $msg_media_path
        );

        return  $this->app_msg_model->insert($data);
    }



    /**************************************private methods****************************************************************************/


} 