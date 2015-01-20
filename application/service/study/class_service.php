<?php

class class_service extends MY_Service{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('study/class_model');
    }


    /**
     * 获取课程列表
     * @return mixed|null
     */
    public function get_class_list()
    {
        $class_list = $this->class_model->get_class_list();
        foreach($class_list as & $record)
        {
            $record['class_cover_path'] =
                empty($record['class_cover_path'])? '':TOY_ADMIN_URL . $record['class_cover_path'];

        }
        return $class_list;
    }

} 