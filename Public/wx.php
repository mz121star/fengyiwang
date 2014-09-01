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
    private function receiveEvent($object)
        {
        $content = "";
        switch ($object->EventKey)
        {
        case "COMPANY":
        $content = "聚优客为您提供互联网相关产品与服务。";
        break;
        default:
        $content = "点击菜单：".$object->EventKey;
        break;
        }
        
        if(is_array($content)){
        if (isset($content[0]['PicUrl'])){
        $result = $this->transmitNews($object, $content);
        }else if (isset($content['MusicUrl'])){
        $result = $this->transmitMusic($object, $content);
        }
        }else{
        $result = $this->transmitText($object, $content);
        }
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