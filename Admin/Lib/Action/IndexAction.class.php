<?php

class IndexAction extends Action {

    public function index(){
        $userInfo = session('userinfo');
        if(empty($userInfo) || $userInfo['user_type'] == 2){
            $this->redirect('Index/showlogin');
        } else {
            $this->display();
        }
    }

    public function showlogin(){
        $userInfo = session('userinfo');
        if(empty($userInfo) || $userInfo['user_type'] == 2){
            $this->display();
        } else {
            $this->redirect('Index/index');
        }
    }
    public function weixin() {
        if (!$_SESSION['access_token']) {
            /*  $appid = 'wx746191c3d2d0ebd7';
              $appsecret = 'a4e835f6b20748fba46a827017cc835a';*/
             $app_id = 'wxccf0766ad3d06490';

             $app_secret = 'caa282ed24424a7f999f5196b4352ee6';
            $access_token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$app_id.'&secret='.$app_secret;
            $access_token_result = file_get_contents($access_token_url);
            $access_token_result = json_decode($access_token_result);
            $_SESSION['access_token'] = $access_token_result->access_token;
        }

        $underbar_url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$_SESSION['access_token'];
//            $underbar_content = array('button' => array(array('type'=>'click', 'name'=>'最近吃啥', 'key'=>'ws_zjcs'), array('type'=>'click', 'name'=>'节目汇编', 'key'=>'ws_jmhb'), array('name'=>'精选', 'sub_button'=>array(array('type'=>'click', 'name'=>'精选商品', 'key'=>'ws_jxsp'), array('type'=>'click', 'name'=>'精选活动', 'key'=>'ws_jxhd')))));
        $underbar_content = '{
    "button": [
        {
            "type": "click",
            "name": "团购",
            "key": "fy_tuan"
        },
        {
            "type": "click",
            "name": "代金券",
            "key": "fy_tuan"
        },
        {
            "type": "view",
            "name": "红包",
            "url": "http://fy.webs.dlwebs.com/index.php/shake"
        }
    ]
}
                                                       ';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $underbar_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $underbar_content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $item_str = curl_exec($ch);
        curl_close($ch);
        echo $item_str;exit;
    }
    public function login(){
        $userInfo = session('userinfo');
        if(!empty($userInfo) && $userInfo['user_type'] != 2){
            $this->redirect('Index/index');
        }
        $user = M("User");
        $_POST['user_id'] = $this->_post('user_id');
        $_POST['user_pw'] = md5($this->_post('user_pw'));
        $_POST['user_status'] = 1;
        $userInfo = $user->where($_POST)->field('id,user_id,user_name,user_phone,user_type')->find();
        if(!empty($userInfo) && $userInfo['user_type'] != 2){
            session('userinfo', $userInfo);
            $this->redirect('Index/index');
        } else {
            $this->redirect('Index/showlogin');
        }
    }

    public function logout() {
        $userInfo = session('userinfo');
        if(!empty($userInfo)){
            session('userinfo', null);
        }
        $this->redirect('Index/showlogin');
    }
}