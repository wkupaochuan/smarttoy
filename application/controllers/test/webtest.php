<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: wangchuan
 * Date: 14-10-8
 * Time: 22:05
 */

class Webtest extends  \CI_Controller{

    public function index()
    {
        $a = array('3', '30', '2', '22', 'a');
        natsort($a);
        print_r($a);
        $b = array('ab21', 'a1', 'a3' , 'b1', 'c8');
        natsort($b);
        print_r($b);
        $res = 'i love you!, Sanguniang!'.date('Y-m-d', time());
        echo json_encode($res);
    }

    public function test_xmpp()
    {
        $this->load->library('xmpphp/xmpp_client');
        $this->xmpp_client->send_msg('admin@iz255gm1qk6z', 'test1@iz255gm1qk6z', 'hi');exit;
    }

    public function test_wechat()
    {
        $msg = array(
            'messageType' => 'text'
            , 'text' => 'wojiaowangchuan'
            , 'file' => ''
        );


        $from_user = 'test1';
        $to_user = 'eb2c8e820c13836';
        $post_fields = array(
            'from_user' => 'eb2c8e820c13836'
            ,'to_user' => 'test1'
            ,'msg' => json_encode($msg)
        );

        $url = '127.0.0.1:8090/im_server/servlet/ChatServlet?from_user='. $from_user
        . '&to_user=' . $to_user . '&password=' . '&msg=' . json_encode($msg);

        $res = $this->curl->request($url, $post_fields);

        print_r($res);
    }


    public function test_bubble_sort()
    {
        $array_size = 100000;
        $a = array();
        for($i = 0; $i < $array_size; ++$i)
        {
            $a[] = rand(0, $array_size);
        }


        $this->_cal_function_time('_bubble_sort', $a);
        $this->_cal_function_time('_bubble_sort_faster', $a);
        $this->_cal_function_time('_bubble_sort_fasest', $a);
        $this->_cal_function_time('_both_way_bubble_sort', $a);
        $this->_cal_function_time('_quick_sort', $a);
        $this->_cal_function_time('sort', $a);
    }


    private function _cal_function_time($private_method_name, $param)
    {
        $t1 = explode(' ', microtime());
        if($private_method_name == 'sort')
        {
            $private_method_name($param);
        }
        else if($private_method_name == '_quick_sort')
        {
            $this->$private_method_name(0, count($param) - 1, $param);
        }
        else{
            $this->$private_method_name($param);
        }
        $t2 = explode(' ', microtime());
        echo 'method:' . $private_method_name.' cost time----'.($t2[1]-$t1[1]).'s '.($t2[0]-$t1[0]).'ms<br>';
    }


    private function _bubble_sort($a)
    {
        $count = 0;
        for($i = 1; $i < count($a); ++$i)
        {
            for($j = 0; $j < count($a) - $i; ++$j)
            {
                ++$count;
                if($a[$j] > $a[$j + 1])
                {
                    list($a[$j], $a[$j+1]) = array($a[$j+1], $a[$j]);
                }
            }
        }

        print_r($count);echo '<br>';
        return $a;
    }


    private function _bubble_sort_faster($a)
    {
        $count = 0;
        for($i = 1; $i < count($a); ++$i)
        {
            $exchange_flag = false;
            for($j = 0; $j < count($a) - $i; ++$j)
            {
                ++$count;
                if($a[$j] > $a[$j + 1])
                {
                    list($a[$j], $a[$j+1]) = array($a[$j+1], $a[$j]);
                    $exchange_flag = true;
                }
            }
            if(!$exchange_flag){
                break;
            }
        }

        print_r($count);echo '<br>';
        return $a;
    }

    private function _bubble_sort_fasest($a)
    {
        $count = 0;
        $inner_length = count($a) - 1;
        for($i = 1; $i < count($a); ++$i)
        {
            $exchange_flag = false;
            $x = $inner_length;
            for($j = 0; $j < $x; ++$j)
            {
                ++$count;
                if($a[$j] > $a[$j + 1])
                {
                    list($a[$j], $a[$j+1]) = array($a[$j+1], $a[$j]);
                    $exchange_flag = true;
                    $inner_length = $j;
                }
            }
            if(!$exchange_flag){
                break;
            }
        }

        print_r($count);echo '<br>';
        return $a;
    }


    private function _both_way_bubble_sort($a)
    {
        $left = 0;
        $right = count($a) - 1;
        $count = 0;
        while($left < $right){

            $edge_mark = $left;
            $exchange_flag = false;
            for($i = $left; $i < $right; ++$i)
            {
                $count ++;
                if($a[$i] > $a[$i + 1])
                {
                    list($a[$i], $a[$i + 1]) = array($a[$i + 1], $a[$i]);
                    $edge_mark = $i;
                    $exchange_flag = true;
                }
            }

            if(!$exchange_flag)
            {
                break;
            }

            // 重新定义右边界
            $right = $edge_mark;

            $exchange_flag = false;
            for($i = $right; $i > $left; --$i)
            {
                $count ++;
                if($a[$i] < $a[$i - 1])
                {
                    list($a[$i], $a[$i - 1]) = array($a[$i - 1], $a[$i]);
                    $edge_mark = $i;
                    $exchange_flag = true;
                }
            }

            if(!$exchange_flag)
            {
                break;
            }

            // 重新定义左边界
            $left = $edge_mark;
        }

        print_r($count);echo '<br>';
    }



    private function _quick_sort($low, $high, & $array)
    {
        if($low < $high)
        {
            $pivot = $this->_partition($low, $high, $array);
            $this->_quick_sort($low, $pivot -1 , $array);
            $this->_quick_sort($pivot + 1, $high , $array);
        }
    }

    private function _partition($low, $high, & $array)
    {
        // 找出参照标准、并挖坑
        $pivot = $array[$low];

        while($low < $high)
        {
            while($low < $high && $array[$high] >= $pivot ) --$high;
            $array[$low] = $array[$high];

            while($low < $high && $array[$low] <= $pivot) ++ $low;
            $array[$high] = $array[$low];
        }

        $array[$low] = $pivot;
        return $low;
    }






} 