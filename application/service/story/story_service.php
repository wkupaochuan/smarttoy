<?php

class Story_service extends MY_Service{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('story/story_model');
    }


    /************************************ public methods ************************************************************/


    /**
     * 搜索故事
     * @param $search_words
     * @param null $limit
     * @param null $offset
     * @return array
     */
    public function get_story_list_by_search($search_words, $limit = null, $offset = null)
    {
        // 获取所有故事
        $all_storys = $this->story_model->get(array('is_deleted' => 2), $limit, $offset);

        // 补全故事和图片地址
        foreach($all_storys as & $story)
        {
            $story['story_cover'] = empty($story['story_cover'])? '':$this->resources_path->get_resource_url($story['story_cover']);
            $story['story_voice'] = empty($story['story_voice'])? '':$this->resources_path->get_resource_url($story['story_voice']);
        }

        if(!empty($search_words)){
            // 根据搜索条件获取故事列表
            $all_storys = $this->_get_most_similar_storys($search_words, $all_storys);
        }

        // 返回10条
        $res = array_slice($all_storys, 0, 10);

        return $res;
    }



    /************************************ private methods ************************************************************/


    /**
     * 计算故事与搜索条件的相似度
     * @param $search_words
     * @param $story_list
     * @return array
     */
    private function _get_most_similar_storys($search_words, $story_list)
    {
        foreach($story_list as & $story)
        {
            $story['similarity'] = similar_text($search_words, $story['story_title']);
        }

        $story_list = $this->array_sort($story_list, 'similarity', 'desc');

        return $story_list;
    }


    /**
     * 二维数组排序
     * @param $arr
     * @param $keys
     * @param string $type
     * @return array
     */
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