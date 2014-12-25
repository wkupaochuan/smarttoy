<?php


class User_class extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 获取今日课程
     */
    public function get_classes_for_today()
    {
        $array_classes = array();
        for($i = 0; $i < 4; ++$i)
        {
            $class = array(
                'class_title' => '我是'.$i,
                'class_cover' => 'http://toy-admin.wkupaochuan.com/mp3_files/62c7c2a287015e5f5a59d1b1d701a52f.jpg',
                'class_progress' => intval(20*$i),
                'class_grade' => intval(80)
            );
            array_push($array_classes, $class);
        }

        echo json_encode($array_classes);
    }


} 