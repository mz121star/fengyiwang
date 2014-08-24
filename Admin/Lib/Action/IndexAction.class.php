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