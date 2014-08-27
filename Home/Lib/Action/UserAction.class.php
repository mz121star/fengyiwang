<?php

class UserAction extends PublicAction {

    public function login() {
        $userInfo = session('userinfo');
        if(!empty($userInfo) && $userInfo['user_type'] == 2){
            $this->redirect('Index/index');
        }
        $user = M("User");
        $_POST['user_id'] = $this->_post('user_id');
        $_POST['user_pw'] = md5($this->_post('user_pw'));
        $_POST['user_status'] = 1;
        $userInfo = $user->where($_POST)->field('id,user_id,user_type,user_name,user_phone')->find();
        if(!empty($userInfo) && $userInfo['user_type'] == 2){
            session('userinfo', $userInfo);
        }
        $this->redirect('Index/index');
    }

    public function logout() {
        $userInfo = session('userinfo');
        if(!empty($userInfo)){
            session('userinfo', null);
        }
        $this->redirect('Index/index');
    }
    
    public function adduser() {
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
        $this->redirect('Index/index');
    }
}
