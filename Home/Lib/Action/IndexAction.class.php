<?php

class IndexAction extends Action {

    public function index(){
        $uid = $this->_get('uid');
        $user = M('User');
        $userinfo = session('userinfo');
        if (!$userinfo['user_id'] || ($userinfo['user_id'] != $uid && $uid)) {
            $userinfo = $user->field('id, user_id, user_type')->where('user_id = "'.$uid.'"')->find();
            if (!empty($userinfo) && $userinfo['user_type'] == 2) {
                session('userinfo', $userinfo);
            } else {
                $id = $user->add(array('user_id'=>$uid, 'user_pw'=>'827ccb0eea8a706c4c34a16891f84e7b'));
                session('userinfo', array('id'=>$id, 'user_id'=>$uid, 'user_type'=>2));
            }
        }
        $section = M("section");
        $sectionlist = $section->order(array('id'=>'desc'))->limit('0,6')->select();
        $this->assign('sectionlist', $sectionlist);
        $this->display();
    }

    public function reg() {
        $this->display();
    }

    public function showlogin() {
        $this->display();
    }

    public function getcode() {
        $code=1243456;
        //短信接口机构代码 $jgid
        $jgid = '300';
        //短信接口用户名 $loginname
        $loginname = 'hummerlys';
        //短信接口密码 $passwd
        $passwd = '851514';
        //发送到的目标手机号码 $telphone，多个号码用半角分号分隔
        $telphone = $this->_post('phone');
        //短信内容 $message
        $message = "尊敬的客户，您的验证码是：$code【风翼网】";
        $gateway = "http://IP:8180/service.asmx/SendMessageStr?Id=$jgid&Name=$loginname&Psw=$passwd&Message=$message&Phone=$telphone&Timestamp=0";
        $result = file_get_contents($gateway);
        echo $result;
        exit;
    }

    public function login() {
        $userInfo = session('userinfo');
        if(!empty($userInfo) && $userInfo['user_type'] == 3 ){
            $this->redirect('Index/index');
        }
        $user = M("User");
        $_POST['user_id'] = $this->_post('user_id');
        $_POST['user_pw'] = md5($this->_post('user_pw'));
        $_POST['user_status'] = 1;
        $userInfo = $user->where($_POST)->field('id,user_id,user_type')->find();
        if(!empty($userInfo) && $userInfo['user_type'] == 3){
            session('userinfo', $userInfo);
        }
        $this->redirect('Index/index');
    }

    public function logout() {
        $userInfo = session('userinfo');
        if(!empty($userInfo)){
            session('userinfo', null);
            unset($_SESSION['cart']);
        }
        $this->redirect('Index/index');
    }
    
    public function doregist() {
        $post = $this->filterAllParam('post');
        if (!$post['user_id']) {
            $this->error("用户名不能为空", 'index');
        }
        if (!$post['user_pw1']) {
            $this->error("密码不能为空", 'index');
        }
        if ($post['user_pw1'] != $post['user_pw2']) {
            $this->error("密码不一致", 'index');
        }
        $user = M("User");
        $userInfo = $user->where('user_id="'.$post['user_id'].'"')->field('id')->find();
        if ($userInfo) {
            $this->error("用户ID已存在", 'index');
        }
        $post['user_pw'] = md5($post['user_pw1']);
        $userid = $user->add($post);
        if ($userid) {
            $people = M("People");
            $peopleid = $people->add(array('user_id'=>$post['user_id'], 'people_email'=>$post['people_email']));
        }
        $this->redirect('User/changeinfo');
    }
}