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
        try{
            // 获取参数
            $params = $this->input->get_params();
            $page_size  = isset($params['page_size'])? $params['page_size'] : 10;
            $page_num  = isset($params['page_num'])? $params['page_num'] : 1;
            $search_words = isset($params['search_words'])? $params['search_words']:'';

            // 搜索故事
            $story_list = $this->story_service->get_story_list_by_search($search_words, $page_size, ($page_num - 1) * $page_size);

            $this->rest_success($story_list);
        }catch (Exception $e)
        {
            $this->rest_fail('请求列表出错!');
        }
    }


    /************************************ private methods ************************************************************/





} 