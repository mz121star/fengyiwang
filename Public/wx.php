<?php

define("TOKEN", "fengyiwang");
$wxObj = new weixin();
$wxObj->responseMsg();

class weixin {

    public function valid() {
        $echoStr = $_GET["echostr"];
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg() {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
            //消息类型分离
            switch ($RX_TYPE)
            {
            case "event":
            $result = $this->receiveEvent($postObj);
            break;
            default:
            $result = "unknown msg type: ".$RX_TYPE;
            break;
            }
            echo $result;
        }else {
            echo "";
            exit;
        }
    }
    
    //接收事件消息
    private function receiveEvent($object)  {
       $result = $this->transmitNews($object);
        return $result;
    }
    
    //回复图文消息
    private function transmitNews($object) {
        $itemTpl = " <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
        </item>
        ";
        if ($object->EventKey == 'fy_jblx') {
            $item_str = sprintf($itemTpl, '结伴留学', '结伴留学', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/jblx');
        } elseif ($object->EventKey == 'fy_jbxx') {
            $item_str = sprintf($itemTpl, '结伴学习', '结伴学习', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/jbpx');
        } elseif ($object->EventKey == 'fy_tglx') {
            $item_str = sprintf($itemTpl, '团购留学', '团购留学', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/tglx');
        } elseif ($object->EventKey == 'fy_tgxx') {
            $item_str = sprintf($itemTpl, '团购学习', '团购学习', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/tgpx');
        } elseif ($object->EventKey == 'fy_tjpy') {
            $item_str = sprintf($itemTpl, '推荐朋友', '推荐朋友', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/tjpy');
        } elseif ($object->EventKey == 'fy_home') {
            $item_str = sprintf($itemTpl, '微官网', '微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍微官网介绍商务合作商务合作商务合作商务合作商务合作商务合作商务合作商务合作商务合作商务合作商务合作商务合作商务合作商务合作商务合作商务合作商务合作商务合作商务合作商务合作商务合作商务合作商务合作商务合作商务合作商务合作', '', $_SERVER['SERVER_NAME'].'/index.php/wx/'.$object->FromUserName);
        } elseif ($object->EventKey == 'fy_bindphone') {
            $item_str = sprintf($itemTpl, '手机绑定', '手机绑定', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/regphone');
        } elseif ($object->EventKey == 'fy_swhz') {
            $item_str = sprintf($itemTpl, '商务合作', '商务合作', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/swhz');
        } elseif ($object->EventKey == 'fy_logo') {
            $item_str = sprintf($itemTpl, 'Logo', 'Logo投票', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/logo');
        } elseif ($object->Event == 'subscribe') {
            $item_str = sprintf($itemTpl, '欢迎关注人人汇', '', '', '');
            $item_str1 = sprintf($itemTpl, '绑定手机', '绑定手机享受更多服务', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/regphone');
            $item_str2 = sprintf($itemTpl, 'Logo投票', 'Logo投票', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/logo');
            $xmlTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[news]]></MsgType>
            <ArticleCount>%s</ArticleCount>
            <Articles>
            $item_str
            $item_str1
            $item_str2
            </Articles>
            </xml>";
            $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), 3);
            return $result;
        }
        
        $xmlTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[news]]></MsgType>
        <ArticleCount>%s</ArticleCount>
        <Articles>
        $item_str</Articles>
        </xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), 1);
        return $result;
    }

    private function checkSignature() {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}