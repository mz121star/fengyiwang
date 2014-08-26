<?php

class EduAction extends PublicAction {

    public function lists(){
        $sid = $this->_get('sid');
        $sectionedu = M("sectionedu");
        $edulist = $sectionedu->field('fy_edu.id, edu_name, edu_star, edu_image, edu_discount, edu_desc, edu_browse, edu_choose, edu_sign')->where('section_id = '.$sid)->join(' fy_edu on fy_edu.id=fy_sectionedu.edu_id')->select();
        $this->assign('edulist', $edulist);
        $this->display();
    }
    
    public function detail() {
        $post = $this->filterAllParam('post');
        $edu = M("edu");
        $edulist = array();
        foreach ($post['edu_id'] as $value) {
            $eduinfo = $edu->field('edu_name')->where('id = '.$value)->find();
            $edulist[] = array('id'=>$value, 'edu_name'=>$eduinfo['edu_name']);
        }
        $this->assign('edulist', $edulist);
        $this->display();
    }
    
    public function order() {
        $userid = $this->userInfo['user_id'];
        if (!$userid) {
//            $this->error("请先登录", 'lists');
        }
        $post = $this->filterAllParam('post');
        if (!$post['user_name']) {
            $post['user_name'] = $this->userInfo['user_name'];
        }
        $order = M("order");
        foreach ($post['edu_id'] as $key => $value) {
            $insert = array('user_id'=>$userid, 'user_name'=>$post['user_name'], 'edu_id'=>$value, 'edu_name'=>$post[$key]['edu_name'], 'order_date'=>date('Y-m-d H:i:s'), 'order_phone'=>$post['order_phone']);
            $order->add($insert);
        }
        $this->redirect('Index/index');
    }
    
    public function jblx() {
        $edu = M("edu");
        $edulist = $edu->field('id, edu_name, edu_image')->select();
        $this->assign('edulist', $edulist);
        $this->display();
    }
    
    public function subjblx() {
        $post = $this->filterAllParam('post');
        
        $this->redirect('Index/index');
    }
}