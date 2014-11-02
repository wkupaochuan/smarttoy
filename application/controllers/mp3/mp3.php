<?php
/**
 * Created by PhpStorm.
 * User: Gain
 * Date: 14-10-10
 * Time: 17:28
 */


/**
 * mp3操作类
 * Class Mp3
 */
class Mp3 extends  CI_Controller{


    /**
     * 获取故事列表
     */
    public function get_story_list()
    {
        // 获取参数
        $params = $this->input->post();

        //$params = $this->input->get();

        // 获取搜索条件
        $search_words = isset($params['search_words'])? $params['search_words']:'';

        $res = $this->get_story_list_by_search($search_words);

        echo json_encode($res);

    }

    /**
     * 搜索故事
     * @param $search_words
     * @return array
     */
    private function get_story_list_by_search($search_words)
    {
        $res = array();

        // 获取所有故事
        $this->load->model('base_model/mp3/mp3_model');
        $all_storys = $this->mp3_model->get_sotry_list();

        if(!empty($search_words)){
            // 根据搜索条件获取故事列表
            $res = $all_storys;
        }
        else{
            $res = $all_storys;
        }

        return $res;

    }

} 