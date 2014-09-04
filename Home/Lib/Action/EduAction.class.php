<?php

class EduAction extends PublicAction {

    public function lists(){
        $sid = $this->_get('sid');
        $sectionedu = M("sectionedu");
        $edulist = $sectionedu->field('fy_edu.id, edu_name, edu_star, edu_image, edu_discount, edu_desc, edu_browse, edu_showprice, edu_sign, edu_giveprice, edu_ask, edu_recommend')->where('section_id = '.$sid)->join(' fy_edu on fy_edu.id=fy_sectionedu.edu_id')->select();
        $this->assign('edulist', $edulist);
        $this->assign('pagetitle', '机构列表');
        $this->display();
    }
    
    public function detailedu(){
        $eid = $this->_get('eid');
        $edu = M("edu");
        $eduinfo = $edu->where('id = '.$eid)->find();
        $this->assign('eduinfo', $eduinfo);
        $this->assign('pagetitle', '机构详情');
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
            $edulist[] = $edu->field('id, edu_name, edu_star, edu_image, edu_discount, edu_desc, edu_browse, edu_showprice, edu_sign, edu_giveprice, edu_ask, edu_recommend')->where('id = '.$value['edu_id'])->find();
        }
        $this->assign('edulist', $edulist);
        $this->assign('pagetitle', '培训机构列表');
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
            $edulist[] = $edu->field('id, edu_name, edu_star, edu_image, edu_discount, edu_desc, edu_browse, edu_showprice, edu_sign, edu_giveprice, edu_ask, edu_recommend')->where('id = '.$value['edu_id'])->find();
        }
        $this->assign('edulist', $edulist);
        $this->assign('pagetitle', '留学机构列表');
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
        $this->assign('pagetitle', '订单详情');
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
            $order_number = time().rand(100, 999);
            $insert = array('user_id'=>$userid, 'user_name'=>$post['user_name'], 'edu_id'=>$value, 'edu_name'=>$post['edu_name'][$key], 'order_date'=>date('Y-m-d H:i:s'), 'order_phone'=>$post['order_phone'], 'order_remark'=>$post['order_remark'], 'order_number'=>$order_number);
            $order->add($insert);
        }
        $this->success('下单成功', 'User/center');
    }
    
    public function jblx() {
        $edu = M("edu");
        $sql = 'SELECT id,edu_name,edu_image FROM `fy_edu` WHERE edu_jblx="1"';
        $edulist = $edu->query($sql);
        $this->assign('edulist', $edulist);
        $this->assign('is_tg', 0);
        $this->assign('pagetitle', '结伴留学');
        $this->display();
    }
    
    public function tglx() {
        $edu = M("edu");
        $sql = 'SELECT id,edu_name,edu_image FROM `fy_edu` WHERE edu_tglx="1"';
        $edulist = $edu->query($sql);
        $this->assign('edulist', $edulist);
        $this->assign('is_tg', 1);
        $this->assign('pagetitle', '团购留学');
        $this->display();
    }

    public function jbpx() {
        $edu = M("edu");
        $sql = 'SELECT id,edu_name,edu_image FROM `fy_edu` WHERE edu_jbxx="1"';
        $edulist = $edu->query($sql);
        $this->assign('edulist', $edulist);
        $this->assign('is_tg', 0);
        $this->assign('pagetitle', '结伴培训');
        $this->display();
    }
    
    public function tgpx() {
        $edu = M("edu");
        $sql = 'SELECT id,edu_name,edu_image FROM `fy_edu` WHERE edu_tgxx="1"';
        $edulist = $edu->query($sql);
        $this->assign('edulist', $edulist);
        $this->assign('is_tg', 1);
        $this->assign('pagetitle', '团购培训');
        $this->display();
    }
    
    public function tjpy() {
        
    }
    
    public function savejbtg() {
        $userid = $this->userInfo['user_id'];
        if (!$userid) {
            $this->error("请先登录");
        }
        $post = $this->filterAllParam('post');
        if (!count($post['edu_id'])) {
            $this->error("请选择机构");
        }
        $jborder = M("jborder");
        $post['user_id'] = $userid;
        $post['order_date'] = date('Y-m-d H:i:s');
        $post['order_number'] = time().rand(100, 999);
        if ($post['is_tg'] == 1) {
            $jborder_id = $jborder->add($post);
            foreach ($post['user_jbname'] as $key => $value) {
                $jborder->add(array('user_id'=>$post['user_id'], 'user_jbname'=>$value, 'user_jbphone'=>$post['user_jbphone'][$key], 'user_jbdesc'=>$post['user_jbdesc'][$key], 'order_date'=>$post['order_date'], 'order_parent'=>$jborder_id));
            }
        } else {
            if (!$post['user_jbname'][0]) {
                $this->error("请填写伙伴名");
            }
            if (!$post['user_jbphone'][0]) {
                $this->error("请填写伙伴电话");
            }
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
        $this->success('下单成功', 'peixun');
    }
}