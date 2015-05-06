<?php


/**
 * 微信关注者用户列表
 * Class user_wechat_model
 */

class user_wechat_model extends CI_Model{

    // 表名称
    private $_table_name = 'toy_user_wechat';

    /***************************************************** public methods **********************************************************************************************/

    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
    }

    /**
     * 新增
     * @param $user
     * @return mixed
     */
    public function insert($user)
    {
        $this->db->insert($this->_table_name, $user);
        return $this->db->insert_id();
    }


    /**
     * 更新
     * @param $user
     * @param $where
     */
    public function update($user, $where)
    {
        $this->db->update($this->_table_name, $user, $where);
    }



    /**
     * 查询
     * @param $condition
     * @param null $limit
     * @param $offset
     * @return mixed
     */
    public function get($condition, $limit = null, $offset = null)
    {
        $this->_build_where_for_select($condition);
        $this->_select_from();
        $query = $this->db->get('', $limit, $offset);
        return $query->result_array();
    }


    /**
     * 根据open_id和公众号获取用户信息
     * @param $opend_id
     * @param $developer_weixin_id
     * @return mixed
     */
    public function get_user_by_opend_id($opend_id, $developer_weixin_id)
    {
        $sql = <<<EOD
            SELECT
                id, open_id, developer_weixin_name, subscribe_status
            FROM
                toy_user_wechat
            WHERE
                open_id = '$opend_id' and developer_weixin_name = '$developer_weixin_id' and subscribe_status = 1
EOD;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /***************************************************** private methods **********************************************************************************************/



    /**
     * 查询
     */
    private function _select_from()
    {
        $this->db->from($this->_table_name);
    }


    /**
     * 构造查询条件
     * @param $condition
     */
    private function _build_where_for_select($condition)
    {
        foreach($condition as $search_key => $search_value)
        {
            $search_value = trim($search_value);
            if(strlen($search_value) === 0)
            {
                continue;
            }

            switch($search_key)
            {
                case 'open_id':
                    $this->db->where('open_id', $search_value);
                    break;
                case 'developer_weixin_name':
                    $this->db->where('developer_weixin_name', $search_value);
                    break;
                case 'subscribe_status':
                    $this->db->where('subscribe_status', $search_value);
                    break;
                default:
                    break;
            }
        }
    }

} 