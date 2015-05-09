<?php




class curl {

    private $_ci;

    /*************************************************** public methods *************************************************************************/


    public function __construct()
    {
        $this->_ci = &get_instance();
    }


    /**
     * 微信请求
     * @param $url
     * @param null $data
     * @return mixed
     * @throws Exception
     */
    public function wechat_request($url, $data = null)
    {
        $res = $this->request($url, $data);
        $res = json_decode($res);
        if(isset($res->errcode) && $res->errcode != 0)
        {
            throw new Exception("请求微信错误:错误码--" . $res->errcode. ';错误内容--' .$res->errmsg);
        }
        return $res;
    }


    /**
     * post
     * @param $url
     * @param null $data
     * @return mixed
     * @throws Exception
     */
    public function request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        curl_close($curl);

        // 此处$rest不做处理, 具体是json方式还是其他处理在具体场景处理，
        return $res;
    }


    /*************************************************** private methods *************************************************************************/



}