<?php


class Index extends  CI_Controller{

    public function __construct()
    {
        parent::__construct();

        $this->load->service('story/story_service');
    }

    /**
     * 获取故事列表
     */
    public function get_story_list()
    {
        // 获取参数
        $params = $this->input->post();

        // 兼容post和get模式，优先Post
        if(empty($params))
        {
            $params = $this->input->get();
        }

        // 获取搜索条件
        $search_words = isset($params['search_words'])? $params['search_words']:'';

        $res = $this->story_service->get_story_list_by_search($search_words);
//        $res = $this->get_story_list_by_search($search_words);

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
            $res = $this->get_most_similar_storys($search_words, $all_storys);
        }
        else{
            $res = $all_storys;
        }

        return $res;
    }


    private function get_most_similar_storys($search_words, $story_list)
    {
        foreach($story_list as & $story)
        {
            $story['similarity'] = similar_text($search_words, $story['name']);
        }

        $story_list = $this->array_sort($story_list, 'similarity', 'desc');

        $story_list = array_slice($story_list, 0, 6);
        return $story_list;
    }


    public function array_sort ($arr, $keys, $type='asc' )
    {
        $key_value = $new_arr = array();
        foreach ($arr as $k=>$v)
        {
            $key_value[$k] = $v[$keys];
        }
        if($type == 'asc')
        {
            asort($key_value);
        }
        else
        {
            arsort($key_value);
        }
        reset($key_value);
        foreach ($key_value as $k=>$v)
        {
            $new_arr[$k] = $arr[$k];
        }
        return $new_arr;
    }


} 