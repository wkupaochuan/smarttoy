<?php


class app_msg_service extends MY_Service{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('msg/app_msg_model');
    }



    /**************************************public methods****************************************************************************/

    /**
     * 新增消息
     * @param $app_unique_id
     * @param $msg_type_display
     * @param $msg_media
     * @param $msg_content
     * @return mixed
     */
    public function add_msg($app_unique_id, $msg_type_display, $msg_media, $msg_content)
    {
        $msg_created_time = date('Y-m-d H:i:s');

        // 获取消息类型id
        $this->load->service('msg/msg_type_enum_service');
        $msg_type_id = $this->msg_type_enum_service->get_type_id_by_display($msg_type_display);

        $insert_id = $this->app_msg_model->insert($app_unique_id, $msg_type_id, $msg_content, $msg_media, $msg_created_time);
        return $insert_id;
    }



    /**************************************private methods****************************************************************************/


} 