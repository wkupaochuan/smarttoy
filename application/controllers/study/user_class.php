<?php


class User_class extends CI_Controller{

    public function __construct()
    {
        parent::__construct();

        $this->load->service('study/class_service');
    }


    /**
     * 获取今日课程
     */
    public function get_classes_for_today()
    {
        $class_list = $this->class_service->get_class_list();

        foreach($class_list as &$record)
        {
            $record['class_cover'] = $record['class_cover_path'];
            $record['class_progress'] = intval(70);
            $record['class_grade'] = intval(70);
        }

        echo json_encode($class_list);
    }


} 