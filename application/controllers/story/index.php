<?php


class Index extends  MY_Controller{

    /************************************ public methods ************************************************************/

    public function __construct()
    {
        parent::__construct();

        // 加载service
        $this->load->service('story/story_service');
    }


    /**
     * 获取故事列表
     */
    public function get_story_list()
    {
        // 获取参数
        $params = $this->input->get_params();
//        $limit = isset($params['limit'])? $params['limit']:$this->config->my_item('toy/common_page', 'default_limit');
//        $offset = isset($params['offset'])? $params['limit']:$this->config->my_item('toy/common_page', 'default_offset');
        $search_words = isset($params['search_words'])? $params['search_words']:'';

        // 搜索故事
        $story_list = $this->story_service->get_story_list_by_search($search_words);

        $this->rest_success($story_list);
    }


    /************************************ private methods ************************************************************/





} 