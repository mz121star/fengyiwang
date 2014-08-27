<?php

class EduAction extends PublicAction {

    public function lists(){
        $sid = $this->_get('sid');
        $sectionedu = M("sectionedu");
        $edulist = $sectionedu->field('fy_edu.id, edu_name, edu_star, edu_image, edu_discount, edu_desc, edu_browse, edu_choose, edu_sign')->where('section_id = '.$sid)->join(' fy_edu on fy_edu.id=fy_sectionedu.edu_id')->select();
        $this->assign('edulist', $edulist);
        $this->display();
    }

    public function peixun() {
        $sid = $this->_get('sid');
        $sectionedu = M("sectionedu");
        $sql = 'SELECT distinct(edu_id) FROM `fy_sectionedu` WHERE section_id=1 or section_id=2 or section_id=3 or section_id=4 or section_id=5';
        $result = $sectionedu->query($sql);
        $edulist = array();
        $edu = M("edu");
        foreach ($result as $value) {
            $edulist[] = $edu->field('id, edu_name, edu_star, edu_image, edu_discount, edu_desc, edu_browse, edu_choose, edu_sign')->where('id = '.$value['edu_id'])->find();
        }
        $this->assign('edulist', $edulist);
        $this->display('lists');
    }

    public function liuxue() {
        $sid = $this->_get('sid');
        $sectionedu = M("sectionedu");
        $sql = 'SELECT distinct(edu_id) FROM `fy_sectionedu` WHERE section_id=6';
        $result = $sectionedu->query($sql);
        $edulist = array();
        $edu = M("edu");
        foreach ($result as $value) {
            $edulist[] = $edu->field('id, edu_name, edu_star, edu_image, edu_discount, edu_desc, edu_browse, edu_choose, edu_sign')->where('id = '.$value['edu_id'])->find();
        }
        $this->assign('edulist', $edulist);
        $this->display('lists');
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
            $this->error("请先登录", 'lists');
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
        $sql = 'SELECT id,edu_name,edu_image FROM `fy_edu` WHERE edu_jblx="1" or edu_tglx="1"';
        $edulist = $edu->query($sql);
        $this->assign('edulist', $edulist);
        $this->assign('is_tg', 0);
        $this->display();
    }
    
    public function savejblx() {
        $userid = $this->userInfo['user_id'];
        if (!$userid) {
            $this->error("请先登录", 'jblx');
        }
        $post = $this->filterAllParam('post');
        $jborder = M("jborder");
        $post['user_id'] = $userid;
        $post['order_date'] = date('Y-m-d H:i:s');
        if ($post['is_tg'] == 1) {
            $jborder_id = $jborder->add($post);
            foreach ($post['user_jbname'] as $key => $value) {
                $jborder->add(array('user_id'=>$post['user_id'], 'user_jbname'=>$value, 'user_jbphone'=>$post['user_jbphone'][$key], 'user_jbdesc'=>$post['user_jbdesc'][$key], 'order_date'=>$post['order_date'], 'order_parent'=>$jborder_id));
            }
        } else {
            $post['user_jbname'] = $post['user_jbname'][0];
            $post['user_jbphone'] = $post['user_jbphone'][0];
            $post['user_jbdesc'] = $post['user_jbdesc'][0];
            $jborder_id = $jborder->add($post);
        }
        $jbedu = M("jbedu");
        foreach ($post['edu_id'] as $key => $value) {
            $insert = array('jborder_id'=>$jborder_id, 'edu_id'=>$value);
            $jbedu->add($insert);
        }
        $this->redirect('Index/index');
    }

    public function jbpx() {
        $edu = M("edu");
        $sql = 'SELECT id,edu_name,edu_image FROM `fy_edu` WHERE edu_jbxx="1" or edu_tgxx="1"';
        $edulist = $edu->query($sql);
        $this->assign('edulist', $edulist);
        $this->assign('is_tg', 0);
        $this->display();
    }
    
    public function savejbpx() {
        $userid = $this->userInfo['user_id'];
        if (!$userid) {
            $this->error("请先登录", 'jbpx');
        }
        $post = $this->filterAllParam('post');
        $jborder = M("jborder");
        $post['user_id'] = $userid;
        $post['order_date'] = date('Y-m-d H:i:s');
        if ($post['is_tg'] == 1) {
            $jborder_id = $jborder->add($post);
            foreach ($post['user_jbname'] as $key => $value) {
                $jborder->add(array('user_id'=>$post['user_id'], 'user_jbname'=>$value, 'user_jbphone'=>$post['user_jbphone'][$key], 'user_jbdesc'=>$post['user_jbdesc'][$key], 'order_date'=>$post['order_date'], 'order_parent'=>$jborder_id));
            }
        } else {
            $post['user_jbname'] = $post['user_jbname'][0];
            $post['user_jbphone'] = $post['user_jbphone'][0];
            $post['user_jbdesc'] = $post['user_jbdesc'][0];
            $jborder_id = $jborder->add($post);
        }
        $jbedu = M("jbedu");
        foreach ($post['edu_id'] as $key => $value) {
            $insert = array('jborder_id'=>$jborder_id, 'edu_id'=>$value);
            $jbedu->add($insert);
        }
        $this->redirect('Index/index');
    }
}