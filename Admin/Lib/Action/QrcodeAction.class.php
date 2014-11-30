<?php

class QrcodeAction extends PublicAction {

    public function lists(){
        $qrcode = M("qrcode");
        import('ORG.Util.Page');
        $count = $qrcode->count();
        $page = new Page($count, 10);
        $userlist = $qrcode->order(array('id'=>'desc'))->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('qrlist', $userlist);
        $this->display();
    }

    public function showadd(){
        $this->display();
    }

    public function delqrcode(){
        $qrid = $this->_get('qrid');
        $qrcode = M("qrcode");
        $qrinfo = $qrcode->where('id="'.$qrid.'"')->find();
        if ($qrinfo) {
            $qrnumber = $qrcode->where('id="'.$uid.'"')->delete();
            if ($qrnumber) {
                $this->redirect('Qrcode/lists');
            } else {
                $this->error("删除失败", 'lists');
            }
        } else {
            $this->error("删除失败", 'lists');
        }
    }

    public function save(){
        $qrcode = M("qrcode");
        $post = $this->filterAllParam('post');
        $qrcodeid = $qrcode->add($post);
        if ($qrcodeid) {
            $access_token = session('access_token');
            if (!$access_token) {
                $access_token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=APPSECRET';
                $access_token = $this->_getpage($access_token_url);
                $access_token = json_decode($access_token);
                $access_token = $access_token['access_token'];
                session('access_token', $access_token);
            }
            $qr_info = $this->_getpage('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token, 'post', array('action_name'=>'QR_LIMIT_SCENE', 'action_info'=>array('scene'=>array('scene_id'=>$qrcodeid))));
            $qr_info = json_decode($qr_info);
            $isok = $qrcode->where('id = "'.$qrcodeid.'"')->setField(array('qrcode_ticket'=>$qr_info['ticket'],'qrcode_url'=>$qr_info['url']));
            if ($isok) {
                $this->redirect('Qrcode/lists');
            } else {
                $qrcode->where('id = "'.$qrcodeid.'"')->delete();
                $this->error('生成二维码失败，请重新添加');
            }
        } else {
            $this->error('生成二维码失败，请重新添加');
        }
    }

    private function _getpage($url, $method = 'get', $data = array()) {
        if (!$url) {
            return '';
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($method == 'post' || $method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if (substr($url, 0, 5) == "https"){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}