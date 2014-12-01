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
            $item_str = sprintf($itemTpl, '结伴留学', '找几个小伙伴一起留学走起！结伴留学有更多申请名校的机会', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/jblx');
        } elseif ($object->EventKey == 'fy_jbxx') {
            $item_str = sprintf($itemTpl, '结伴学习', '找几个小伙伴一起来结伴学习吧，结伴学习优惠更多噢', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/jbpx');
        } elseif ($object->EventKey == 'fy_tglx') {
            $item_str = sprintf($itemTpl, '团购留学', '如果找到3个同学要留学，就有团购留学的机会哦，价格更优惠，申请名校也更多，还在等什么？', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/tglx');
        } elseif ($object->EventKey == 'fy_tgxx') {
            $item_str = sprintf($itemTpl, '团购学习', '寻找5-10个同学一起团购学习吧，不需要跑到很远去学习，人人汇邀请名师来学校给大家讲课', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/tgpx');
        } elseif ($object->EventKey == 'fy_tjpy') {
            $item_str = sprintf($itemTpl, '推荐朋友', '同学们想挣钱吗？别告诉我你不想，想的话，就把想留学的同学和想学习的同学介绍过来吧', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/tjpy');
        } elseif ($object->EventKey == 'fy_home') {
            $item_str = sprintf($itemTpl, '微官网', '欢迎同学们进入微官网', 'http://'.$_SERVER['SERVER_NAME'].'/uploads/xiaoyuanzhixing.jpg', $_SERVER['SERVER_NAME'].'/index.php/wx/'.$object->FromUserName);
        } elseif ($object->EventKey == 'fy_bindphone') {
            $item_str = sprintf($itemTpl, '手机绑定', '手机绑定后，才可以进行预约咨询', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/regphone');
        } elseif ($object->EventKey == 'fy_swhz') {
            $item_str = sprintf($itemTpl, '商务合作', '商务合作', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/swhz');
        } elseif ($object->EventKey == 'fy_logo') {
            $item_str = sprintf($itemTpl, 'Logo', 'Logo投票', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/logo');
        } elseif ($object->Event == 'SCAN') {
            $fromsource = $object->EventKey;
            file_get_contents('http://'.$_SERVER['SERVER_NAME'].'/index.php/setsource/'.$object->FromUserName.'/'.$fromsource);
            $item_str = sprintf($itemTpl, '欢迎关注人人汇', '欢迎同学们进入微官网', 'http://'.$_SERVER['SERVER_NAME'].'/uploads/xiaoyuanzhixing.jpg', $_SERVER['SERVER_NAME'].'/index.php/wx/'.$object->FromUserName);
        } elseif ($object->Event == 'subscribe') {
            if (isset($object->EventKey) && $object->EventKey) {
                $fromsource = substr($object->EventKey, 8);
            } else {
                $fromsource = 0;
            }
            file_get_contents('http://'.$_SERVER['SERVER_NAME'].'/index.php/setsource/'.$object->FromUserName.'/'.$fromsource);
            $item_str = sprintf($itemTpl, '欢迎关注人人汇', '欢迎同学们进入微官网', 'http://'.$_SERVER['SERVER_NAME'].'/uploads/xiaoyuanzhixing.jpg', $_SERVER['SERVER_NAME'].'/index.php/wx/'.$object->FromUserName);
//            $item_str = sprintf($itemTpl, '欢迎关注人人汇', '', '', '');
//            $item_str1 = sprintf($itemTpl, '绑定手机', '手机绑定后，才可以进行预约咨询', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/regphone');
//            $item_str2 = sprintf($itemTpl, 'Logo投票', 'Logo投票', '', $_SERVER['SERVER_NAME'].'/index.php/weixin/'.$object->FromUserName.'/logo');
//            $xmlTpl = "<xml>
//            <ToUserName><![CDATA[%s]]></ToUserName>
//            <FromUserName><![CDATA[%s]]></FromUserName>
//            <CreateTime>%s</CreateTime>
//            <MsgType><![CDATA[news]]></MsgType>
//            <ArticleCount>%s</ArticleCount>
//            <Articles>
//            $item_str
//            $item_str1
//            $item_str2
//            </Articles>
//            </xml>";
//            $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), 3);
//            return $result;
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