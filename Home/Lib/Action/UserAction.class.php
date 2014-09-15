<?php

class UserAction extends PublicAction {

    public function center() {
        $this->assign('pagetitle', '订单中心');
        $this->display();
    }

    public function orderlist() {
        $userid = $this->userInfo['user_id'];
        if(!$userid){
            $this->redirect('Index/index');
        }
        $order = M("Order");
        $orderlist = $order->field('fy_order.id, user_name, fy_order.edu_name, order_date, edu_image,order_status,edu_discount')->where('user_id = "'.$userid.'"')->join(' fy_edu on fy_order.edu_id=fy_edu.id')->order(array('fy_order.id'=>'desc'))->select();
        $this->assign('orderlist', $orderlist);
        $this->assign('pagetitle', '个人订单');
        $this->display();
    }

    public function pyorderlist() {
        $userid = $this->userInfo['user_id'];
        if(!$userid){
            $this->redirect('Index/index');
        }
        $pyorder = M("pyorder");
        $orderlist = $pyorder->where('user_id = "'.$userid.'"')->order(array('id'=>'desc'))->select();
        $this->assign('orderlist', $orderlist);
        $this->assign('pagetitle', '我的推荐');
        $this->display();
    }

    public function jborderlist() {
        $userid = $this->userInfo['user_id'];
        if(!$userid){
            $this->redirect('Index/index');
        }
        $jborder = M("jborder");
        $orderlist1 = $jborder->field('fy_jborder.id, fy_jbedu.id as jid, user_jbname, user_jbphone, user_jbdesc, order_date, order_status, edu_name, edu_image,edu_discount')->where('user_id = "'.$userid.'" and order_parent=0 and user_jbname != ""')->join(' fy_jbedu on fy_jbedu.jborder_id=fy_jborder.id')->join(' fy_edu on fy_jbedu.edu_id=fy_edu.id')->order(array('fy_jborder.id'=>'desc'))->select();
        $this->assign('orderlist1', $orderlist1);
        $this->assign('pagetitle', '结伴订单');
        $this->display();
    }

    public function tgorderlist() {
        $userid = $this->userInfo['user_id'];
        if(!$userid){
            $this->redirect('Index/index');
        }
        $jborder = M("jborder");
        $orderlist1 = $jborder->field('fy_jborder.id, fy_jbedu.id as jid, user_jbname, user_jbphone, user_jbdesc, order_date, order_status, edu_name, edu_image')->where('user_id = "'.$userid.'" and order_parent=0 and user_jbname = ""')->join(' fy_jbedu on fy_jbedu.jborder_id=fy_jborder.id')->join(' fy_edu on fy_jbedu.edu_id=fy_edu.id')->order(array('fy_jborder.id'=>'desc'))->select();
        $this->assign('orderlist1', $orderlist1);
        $this->assign('pagetitle', '团购订单');
        $this->display();
    }

    public function detailorder() {
        $userid = $this->userInfo['user_id'];
        if(!$userid){
            $this->redirect('Index/index');
        }
        $joid = $this->_get('joid');
        $jeid = $this->_get('jeid');
        $this->assign('pagetitle', '订单详情');
        if (!$jeid) {
            $order = M("Order");
            $orderinfo = $order->field('fy_order.id, user_name, fy_order.edu_name, order_date, edu_image, order_status, order_number')->where('fy_order.id = "'.$joid.'"')->join(' fy_edu on fy_order.edu_id=fy_edu.id')->find();
            $this->assign('orderinfo', $orderinfo);
            $this->display('commonorder');
        } else {
            $jborder = M("jborder");
            $jborderinfo = $jborder->where('id = "'.$joid.'"')->find();
            $this->assign('jborderinfo', $jborderinfo);
            
            $tgorderinfo = $jborder->where('order_parent = "'.$jborderinfo['id'].'"')->select();
            if (!$tgorderinfo) {
                $jbedu = M("jbedu");
                $jbeduorderinfo = $jbedu->where('jborder_id = "'.$jborderinfo['id'].'"')->join(' fy_edu on fy_edu.id=fy_jbedu.edu_id')->select();
                $this->assign('jbeduorderinfo', $jbeduorderinfo);
                $this->display('jborder');
            } else {
                $jbedu = M("jbedu");
                $total = array();
                foreach ($tgorderinfo as $tg) {
                    $jbeduorderinfo = $jbedu->where('jborder_id = "'.$tg['id'].'"')->join(' fy_edu on fy_edu.id=fy_jbedu.edu_id')->select();
                    $total = array_merge($total, $jbeduorderinfo);
                }
                $this->assign('tgorderinfo', $tgorderinfo);
                $this->assign('jbeduorderinfo', $total);
                $this->display('tgorder');
            }
        }
    }

    public function info() {
        $userid = $this->userInfo['user_id'];
        if(!$userid){
            $this->redirect('Index/index');
        }
        $user = M("user");
        $userinfo = $user->where('user_id = "'.$userid.'"')->find();
        $this->assign('userinfom', $userinfo);
        $this->assign('pagetitle', '个人信息设定');
        $this->display();
    }

    public function upinfo() {
        $userid = $this->userInfo['user_id'];
        if(!$userid){
            $this->redirect('Index/index');
        }
        $post = $this->filterAllParam('post');
        $infoarray = array('user_name'=>$post['user_name'], 'user_phone'=>$post['user_phone'], 'user_school'=>$post['user_school'], 'user_zhuanye'=>$post['user_zhuanye'], 'user_age'=>$post['user_age']);
        $user = M("user");
        $issucess = $user->where('user_id = "'.$userid.'"')->setField($infoarray);
        if ($issucess) {
            $this->success('修改成功', 'center');
        } else {
            $this->error('修改失败', 'info');
        }
    }
}
