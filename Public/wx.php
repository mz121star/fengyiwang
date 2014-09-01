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
            $item_str = sprintf($itemTpl, '结伴学习', '结伴学习', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/jbxx');
        } elseif ($object->EventKey == 'fy_tglx') {
            $item_str = sprintf($itemTpl, '团购留学', '团购留学', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/tglx');
        } elseif ($object->EventKey == 'fy_tgxx') {
            $item_str = sprintf($itemTpl, '团购学习', '团购学习', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/tgxx');
        } elseif ($object->EventKey == 'fy_tjpy') {
            $item_str = sprintf($itemTpl, '推荐朋友', '推荐朋友', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/tjpy');
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