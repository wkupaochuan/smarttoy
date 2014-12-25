<?php


class Index extends  CI_Controller{

    private $o_story_service;

    public function __construct()
    {
        parent::__construct();

        // 加载service
        $this->load->service('story/story_service');
        $this->o_story_service = $this->story_service;
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

        // 搜索故事
        $res = $this->o_story_service->get_story_list_by_search($search_words);

        echo json_encode($res);
    }

} 