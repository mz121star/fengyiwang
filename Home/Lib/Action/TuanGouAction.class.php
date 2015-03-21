<?php

class TuanGouAction extends PublicAction {

    public function index(){
         $tuangou=M("tuangou");
        $list=$tuangou->select();
        $this->assign('list', $list);
        $this->display();
    }
    
    public function detail(){
        $eid = $this->_get('id');
        $edu = M("tuangou");
        $eduinfo = $edu->where('Id = '.$eid)->find();
        $this->assign('eduinfo', $eduinfo);

        $this->display();
    }
    public function getcode() {

        $code =  $this->_get('code');
        $telphone =  $this->_get('phone');
        echo 'ea'; exit;
        //短信接口机构代码 $jgid
        $jgid = '300';
        //短信接口用户名 $loginname
        $loginname = 'hummerlys';
        //短信接口密码 $passwd
        $passwd = '851514';
        //发送到的目标手机号码 $telphone，多个号码用半角分号分隔

        //短信内容 $message
        $message = urlencode('尊敬的客户：'.$code.'（人人汇手机动态验证码以生成，请完成验证）');
        $gateway = 'http://223.4.21.214:8180/service.asmx/SendMessageStr?Id='.$jgid.'&Name='.$loginname.'&Psw='.$passwd.'&Message='.$message.'&Phone='.$telphone.'&Timestamp=0';
        $result = file_get_contents($gateway);

        echo $result;
        exit;
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


    public function gotousercenter() {
        $this->redirect('User/center');
    }
    
    public function order() {
        $userid = $this->userInfo['user_id'];
        if (!$userid) {
            $this->error("请先登录", 'lists');
        }
        $user = M("user");
        $userinfo = $user->where('user_id = "'.$userid.'"')->find();
        if (!$userinfo['user_phone']) {
            $this->error("请先绑定手机再下单", 'lists');
        }
        $post = $this->filterAllParam('post');
        if (!$post['user_name']) {
            $post['user_name'] = $this->userInfo['user_name'];
        }
        $order = M("order");
        $edu = M("edu");
        foreach ($post['edu_id'] as $key => $value) {
            $order_number = time().rand(100, 999);
            $insert = array('user_id'=>$userid, 'user_name'=>$post['user_name'], 'edu_id'=>$value, 'edu_name'=>$post['edu_name'][$key], 'order_date'=>date('Y-m-d H:i:s'), 'order_phone'=>$post['order_phone'], 'order_remark'=>$post['order_remark'], 'order_number'=>$order_number);
            $issuccess = $order->add($insert);
            if ($issuccess) {
                $eduinfo = $edu->where('id = '.$value)->find();
                $edu_ask = $eduinfo['edu_ask'];
                $edu_ask = ($edu_ask) ? $edu_ask + 1 : 1;
                $edu->where('id = '.$value)->setField('edu_ask', $edu_ask);
            }
        }
        $this->success('下单成功', 'gotousercenter');
    }
    
    public function jblx() {
        $edu = M("edu");
        $sql = 'SELECT id,edu_name,edu_image FROM `fy_edu` WHERE edu_jblx="1"';
        $edulist = $edu->query($sql);
        $this->assign('edulist', $edulist);
        $this->assign('is_tg', 1);
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
        $this->assign('is_tg', 1);
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
        $this->assign('pagetitle', '推荐朋友');
        $this->display();
    }

    public function savepy() {
        $userid = $this->userInfo['user_id'];
        if (!$userid) {
            $this->error("请先登录");
        }
        $user = M("user");
        $userinfo = $user->where('user_id = "'.$userid.'"')->find();
        if (!$userinfo['user_phone']) {
            $this->error("请先绑定手机再下单");
        }
        $post = $this->filterAllParam('post');
        $pyorder = M("pyorder");
        $post['user_id'] = $userid;
        $post['order_date'] = date('Y-m-d H:i:s');
        $post['order_number'] = time().rand(100, 999);
        $pyorder_id = $pyorder->add($post);
        $this->success('推荐成功', 'gotousercenter');
    }
    
    public function savejbtg() {
        $userid = $this->userInfo['user_id'];
        if (!$userid) {
            $this->error("请先登录");
        }
        $user = M("user");
        $userinfo = $user->where('user_id = "'.$userid.'"')->find();
        if (!$userinfo['user_phone']) {
            $this->error("请先绑定手机再下单");
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
                $jborder->add(array('user_id'=>$post['user_id'], 'user_jbname'=>$value, 'user_jbphone'=>$post['user_jbphone'][$key], 'user_jbdesc'=>$post['user_jbdesc'][$key], 'order_type'=>$post['order_type'], 'order_date'=>$post['order_date'], 'order_parent'=>$jborder_id));
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
            $post['order_type'] = 1;
            $jborder_id = $jborder->add($post);
        }
        $jbedu = M("jbedu");
        $edu = M("edu");
        foreach ($post['edu_id'] as $key => $value) {
            $insert = array('jborder_id'=>$jborder_id, 'edu_id'=>$value);
            $issuccess = $jbedu->add($insert);
            if ($issuccess) {
                $eduinfo = $edu->where('id = '.$value)->find();
                $edu_ask = $eduinfo['edu_ask'];
                $edu_ask = ($edu_ask) ? $edu_ask + 1 : 1;
                $edu->where('id = '.$value)->setField('edu_ask', $edu_ask);
            }
        }
        $this->success('下单成功', 'gotousercenter');
    }
}