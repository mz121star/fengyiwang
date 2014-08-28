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
}