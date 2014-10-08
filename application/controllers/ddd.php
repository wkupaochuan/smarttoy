<?php
///**
// * Created by PhpStorm.
// * User: jixiaofeng
// * Date: 14-8-26
// * Time: 16:28
// */
//
//      
//define('TOKEN', 'www.luchg.com');
//$valid = new ApiValid();
//  
//// 权限验证
//if($valid->checkSignature()){
//        // 验证通过，处理消息
//    $msg = new MSG();
//    $msg->handleMsg();
//}else{
//        echo '没有权限';
//    exit();
//}
//  
//  
//// 消息处理
//class MSG{
//  
//    public function handleMsg(){
//            // 接收消息
//        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
//  
//        // 判断是否为空
//        if(!empty($postStr)){
//            // 从 XML 中读取消息
//            $requestMsg = $this->readMsg($postStr);
//  
//            // 返回消息内容
//            $responseMsg = array();
//            // 接收用户
//            $responseMsg['ToUserName'] = $requestMsg->FromUserName;
//            // 发送用户
//            $responseMsg['FromUserName'] = $requestMsg->ToUserName;
//            // 发送时间
//            $responseMsg['CreateTime'] = time();
//  
//            // 发送内容
//            $sendMsg = "";
//  
//            // 判断消息类型
//            switch($requestMsg->MsgType){
//  
//                // 文本消息
//                case 'text':
//                    $responseMsg['MsgType'] = 'text';
// 
//                    if(stristr($requestMsg->Content, 'h')){
//                        $responseMsg['Content'] = '您好,我是小L,请回复数字选择服务.' . "\n\n"
//                            . '1. 最新天气预报.[格式 1.上海]' . "\n"
//                            . '2. 最新IT数码资讯.' . "\n"
//                            . '3. 高性价比数码推荐.' . "\n"
//                            . '4. 来个笑话.';
//                    }else if(stristr($requestMsg->Content, '1')){
//                                $responseMsg['Content'] = '天气预报';
//                    }else if(stristr($requestMsg->Content, '2')){
//                                $responseMsg['Content'] = 'IT数码资讯';
//                    }else if(stristr($requestMsg->Content, '3')){
//                                $responseMsg['Content'] = '数码推荐';
//                    }else if(stristr($requestMsg->Content, '4')){
//                                $responseMsg['Content'] = '笑话';
//                    }else{
//                                $responseMsg['Content'] = '未知功能.请回复[h]查看所有服务.';
//                    }
// 
//                    break;
//  
//                // 其他类型
//                default:
//  
//                    $responseMsg['MsgType'] = 'text';
//                    $responseMsg['Content'] = '目前仅支持对文字内容回复.请回复[h]查看所有服务.';
//  
//                    break;
//            }
//  
//            // 创建 XML 消息并返回
//            $sendMsg = $this->createTextMsg($responseMsg);
//            echo $sendMsg;
//        }else{
//                    echo '';
//            exit;
//        }
//    }
//  
//    /**
//     * 从 XML 中读取消息内容
//     * @param  [type] $xml [description]
//     * @return [type]      [description]
//     */
//    private function readMsg($xml){
//            return simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
//    }
//  
//    /**
//     * 创建 XML 类型的消息
//     * @param  [type] $msg [description]
//     * @return [type]      [description]
//     */
//    private function createTextMsg($msg){
//            $xml =
//                    "<xml>
//                <ToUserName><![CDATA[%s]]></ToUserName>
//                <FromUserName><![CDATA[%s]]></FromUserName>
//                <CreateTime>%s</CreateTime>
//                <MsgType><![CDATA[%s]]></MsgType>
//                <Content><![CDATA[%s]]></Content>
//            </xml>";
//  
//        $resultStr = sprintf($xml, $msg['ToUserName'], $msg['FromUserName'], $msg['CreateTime'],
//                    $msg['MsgType'], $msg['Content']);
//        return $resultStr;
//    }
//}
//  
//  
///**
// * 验证登录
// */
//class ApiValid{
//  
//    public function valid(){
//            $echoStr = $_GET['echostr'];
//  
//        if($this->checkSignature()){
//            echo $echoStr;
//            exit;
//        }
//    }
//  
//    public function checkSignature(){
//      
//        $signature = $_GET['signature'];
//        $timestamp = $_GET['timestamp'];
//        $nonce = $_GET['nonce'];   
//                  
//        $token = TOKEN;
//        $tmpArr = array($token, $timestamp, $nonce);
//        sort($tmpArr, SORT_STRING);
//        $tmpStr = implode( $tmpArr );
//        $tmpStr = sha1( $tmpStr );
//          
//        if( $tmpStr == $signature ){
//            return true;
//        }else{
//                    return false;
//        }
//    }
//  
//}