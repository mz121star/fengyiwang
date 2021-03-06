<?php

class OrderAction extends PublicAction {

    public function lists(){
        $order = M("order");
        $user = M("user");
        import('ORG.Util.Page');
        $count = $order->count();
        $page = new Page($count, 10);
        $orderlist = $order->order(array('id'=>'desc'))->limit($page->firstRow.','.$page->listRows)->select();
        $orders = array();
        foreach ($orderlist as $order) {
            $userinfo = $user->field('user_name, user_phone, user_recommend')->where('user_id = "'.$order['user_id'].'"')->find();
            $order['user_realname'] = $userinfo['user_name'];
            $order['user_realphone'] = $userinfo['user_phone'];
            $order['user_realrecommend'] = $userinfo['user_recommend'];
            $orders[] = $order;
        }
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('orderlist', $orders);
        
        $order_status = C('ORDER_STATUS');
        $this->assign('order_status_list', $order_status);
        
        $section = M("section");
        $sectionlist = $section->order(array('id'=>'asc'))->select();
        $this->assign('sectionlist', $sectionlist);
        $this->display();
    }
    
    public function search(){
        $post = $this->filterAllParam('post');
        $where = array();
        if ($post['order_date']) {
            $where['order_date'] = $post['order_date'];
        }
        if ($post['order_status']) {
            $order_status = implode(',', $post['order_status']);
            $where['order_status'] = array('in', $order_status);
        }
        if ($post['order_number']) {
            $where['order_number'] = array('like', '%'.$post['order_number'].'%');
        }
        if ($post['user_name']) {
            $where['user_name'] = array('like', '%'.$post['user_name'].'%');
        }
        if (count($post['section'])) {
            $sectionedu = M("sectionedu");
            $sectionids = implode(',', $post['section']);
            $w['section_id'] = array('in', $sectionids);
            $eduids = $sectionedu->where($w)->select();
            $eid = array();
            foreach ($eduids as $value) {
                $eid[] = $value['edu_id'];
            }
            if (count($eid)) {
                $eid = implode(',', $eid);
                $where['edu_id'] = array('in', $eid);
            }
        }
        $order = M("order");
        import('ORG.Util.Page');
        $count = $order->where($where)->count();
        $page = new Page($count, 10);
        $orderlist = $order->where($where)->order(array('id'=>'desc'))->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('orderlist', $orderlist);
        
        $order_status_list = C('ORDER_STATUS');
        $this->assign('order_status_list', $order_status_list);
        
        $section = M("section");
        $sectionlist = $section->order(array('id'=>'asc'))->select();
        $this->assign('sectionlist', $sectionlist);
        
        $this->assign('order_date', $post['order_date']);
        $this->assign('order_number', $post['order_number']);
        $this->assign('user_name', $post['user_name']);
        $this->display('lists');
    }
    
    public function jblists(){
        $jborder = M("jborder");
        import('ORG.Util.Page');
        $count = $jborder->where('order_parent=0 and order_type = "1"')->count();
        $page = new Page($count, 10);
        $orderlist = $jborder->order(array('fy_jborder.id'=>'desc'))->field('fy_jborder.id, fy_jbedu.id as jid, user_name, user_jbname, user_jbphone, user_jbdesc, order_number, order_date, order_status, edu_name, edu_image')->where('order_parent=0 and order_type = "1"')->join(' fy_jbedu on fy_jbedu.jborder_id=fy_jborder.id')->join(' fy_edu on fy_jbedu.edu_id=fy_edu.id')->join(' fy_user on fy_user.user_id=fy_jborder.user_id')->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('orderlist', $orderlist);
        
        $order_status_list = C('ORDER_STATUS');
        $this->assign('order_status_list', $order_status_list);
        $this->display();
    }

    public function jbsearch(){
        $post = $this->filterAllParam('post');
        $where = array();
        if ($post['order_date']) {
            $where['order_date'] = $post['order_date'];
        }
        if ($post['order_status']) {
            $order_status = implode(',', $post['order_status']);
            $where['order_status'] = array('in', $order_status);
        }
        if ($post['order_number']) {
            $where['order_number'] = array('like', '%'.$post['order_number'].'%');
        }
        if ($post['user_jbname']) {
            $where['user_jbname'] = array('like', '%'.$post['user_jbname'].'%');
        } else {
            $where['user_jbname'] = array('neq', '');
        }
        $where['order_parent'] = 0;
        $where['order_type'] = 1;
        $jborder = M("jborder");
        import('ORG.Util.Page');
        $count = $jborder->where($where)->count();
        $page = new Page($count, 10);
        $orderlist = $jborder->field('fy_jborder.id, fy_jbedu.id as jid, user_name, user_jbname, user_jbphone, user_jbdesc, order_date, order_status, edu_name, edu_image')->where($where)->join(' fy_jbedu on fy_jbedu.jborder_id=fy_jborder.id')->join(' fy_edu on fy_jbedu.edu_id=fy_edu.id')->join(' fy_user on fy_user.user_id=fy_jborder.user_id')->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('orderlist', $orderlist);
        
        $order_status_list = C('ORDER_STATUS');
        $this->assign('order_status_list', $order_status_list);
        
        $this->assign('order_date', $post['order_date']);
        $this->assign('order_number', $post['order_number']);
        $this->assign('user_jbname', $post['user_jbname']);
        $this->display('jblists');
    }

    public function tglists(){
        $jborder = M("jborder");
        import('ORG.Util.Page');
        $count = $jborder->where('order_parent=0 and order_type = "2"')->count();
        $page = new Page($count, 10);
        $orderlist = $jborder->order(array('fy_jborder.id'=>'desc'))->field('fy_jborder.id, fy_jbedu.id as jid, user_name, user_jbname, user_jbphone, user_jbdesc, order_number, order_date, order_status, edu_name, edu_image')->where('order_parent=0 and order_type = "2"')->join(' fy_jbedu on fy_jbedu.jborder_id=fy_jborder.id')->join(' fy_edu on fy_jbedu.edu_id=fy_edu.id')->join(' fy_user on fy_user.user_id=fy_jborder.user_id')->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('orderlist', $orderlist);
        
        $order_status_list = C('ORDER_STATUS');
        $this->assign('order_status_list', $order_status_list);
        $this->display();
    }

    public function tgsearch(){
        $post = $this->filterAllParam('post');
        $where = array();
        if ($post['order_date']) {
            $where['order_date'] = $post['order_date'];
        }
        if ($post['order_status']) {
            $order_status = implode(',', $post['order_status']);
            $where['order_status'] = array('in', $order_status);
        }
        if ($post['order_number']) {
            $where['order_number'] = array('like', '%'.$post['order_number'].'%');
        }
        if ($post['user_jbname']) {
            $where['user_jbname'] = array('like', '%'.$post['user_jbname'].'%');
        } else {
            $where['user_jbname'] = array('neq', '');
        }
        $where['order_parent'] = 0;
        $where['order_type'] = 2;
        $jborder = M("jborder");
        import('ORG.Util.Page');
        $count = $jborder->where($where)->count();
        $page = new Page($count, 10);
        $orderlist = $jborder->field('fy_jborder.id, fy_jbedu.id as jid, user_name, user_jbname, user_jbphone, user_jbdesc, order_date, order_status, edu_name, edu_image')->where($where)->join(' fy_jbedu on fy_jbedu.jborder_id=fy_jborder.id')->join(' fy_edu on fy_jbedu.edu_id=fy_edu.id')->join(' fy_user on fy_user.user_id=fy_jborder.user_id')->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('orderlist', $orderlist);
        
        $order_status_list = C('ORDER_STATUS');
        $this->assign('order_status_list', $order_status_list);
        
        $this->assign('order_date', $post['order_date']);
        $this->assign('order_number', $post['order_number']);
        $this->assign('user_jbname', $post['user_jbname']);
        $this->display('tglists');
    }

    public function pylists(){
        $pyorder = M("pyorder");
        import('ORG.Util.Page');
        $count = $pyorder->count();
        $page = new Page($count, 10);
        $orderlist = $pyorder->order(array('fy_pyorder.id'=>'desc'))->field('order_number, user_name, user_pyphone, order_date, user_pyname, order_status, user_phone')->join(' fy_user on fy_user.user_id=fy_pyorder.user_id')->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('orderlist', $orderlist);
        
        $order_status_list = C('ORDER_STATUS');
        $this->assign('order_status_list', $order_status_list);
        $this->display();
    }

    public function pysearch(){
        $post = $this->filterAllParam('post');
        $where = array();
        if ($post['order_date']) {
            $where['order_date'] = $post['order_date'];
        }
        if ($post['order_status']) {
            $order_status = implode(',', $post['order_status']);
            $where['order_status'] = array('in', $order_status);
        }
        if ($post['order_number']) {
            $where['order_number'] = array('like', '%'.$post['order_number'].'%');
        }
        if ($post['user_pyname']) {
            $where['user_pyname'] = array('like', '%'.$post['user_pyname'].'%');
        }
        $pyorder = M("pyorder");
        import('ORG.Util.Page');
        $count = $pyorder->where($where)->count();
        $page = new Page($count, 10);
        $orderlist = $pyorder->field('order_number, user_name, user_pyphone, order_date, user_pyname, order_status')->where($where)->join(' fy_user on fy_user.user_id=fy_pyorder.user_id')->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('orderlist', $orderlist);
        
        $order_status_list = C('ORDER_STATUS');
        $this->assign('order_status_list', $order_status_list);
        
        $this->assign('order_date', $post['order_date']);
        $this->assign('order_number', $post['order_number']);
        $this->assign('user_pyname', $post['user_pyname']);
        $this->display('pylists');
    }

    public function recommend(){
        $order_number = $this->_get('oid');
        $order = M("order");
        $orderinfo = $order->where('order_number="'.$order_number.'"')->find();
        if ($orderinfo) {
            $order->where('order_number="'.$order_number.'"')->setField('order_status','1');
        } else {
            $jborder = M("jborder");
            $jborderinfo = $jborder->where('order_number="'.$order_number.'" and order_parent = 0')->find();
            if ($jborderinfo) {
                $jborder->where('order_number="'.$order_number.'"')->setField('order_status','1');
                $jborder->where('order_parent='.$jborderinfo['id'])->setField('order_status','1');
            }
        }
        $this->redirect('Order/lists');
    }

    public function sign(){
        $order_number = $this->_get('oid');
        $order = M("order");
        $edu = M("edu");
        $orderinfo = $order->where('order_number="'.$order_number.'"')->find();
        if ($orderinfo) {
            $issuccess = $order->where('order_number="'.$order_number.'"')->setField('order_status','2');
            if ($issuccess) {
                $eduinfo = $edu->where('id = '.$orderinfo['edu_id'])->find();
                $edu_sign = $eduinfo['edu_sign'];
                $edu_sign = ($edu_sign) ? $edu_sign + 1 : 1;
                $edu->where('id = '.$value)->setField('edu_sign', $edu_sign);
            }
        } else {
            $jborder = M("jborder");
            $jborderinfo = $jborder->where('order_number="'.$order_number.'" and order_parent = 0')->find();
            if ($jborderinfo) {
                $issuccess = $jborder->where('order_number="'.$order_number.'"')->setField('order_status','2');
                $jborder->where('order_parent='.$jborderinfo['id'])->setField('order_status','2');
                if ($issuccess) {
                    $jbedu = M("jbedu");
                    $edulists = $jbedu->field('edu_id')->where('jborder_id = "'.$jborderinfo['id'].'"')->select();
                    foreach ($edulists as $eduinfo) {
                        $eduinfo = $edu->where('id = '.$eduinfo['edu_id'])->find();
                        $edu_sign = $eduinfo['edu_sign'];
                        $edu_sign = ($edu_sign) ? $edu_sign + 1 : 1;
                        $edu->where('id = '.$value)->setField('edu_sign', $edu_sign);
                    }
                }
            }
        }
        $this->redirect('Order/lists');
    }

    public function modorder(){
        $order_number = $this->_get('oid');
        $order = M("order");
        $orderinfo = $order->where('order_number="'.$order_number.'"')->find();
        if ($orderinfo) {
            $this->assign('orderinfo', $orderinfo);
            $this->display();
        } else {
            $jborder = M("jborder");
            $jborderinfo = $jborder->where('order_number="'.$order_number.'" and order_parent = 0')->find();
            if ($jborderinfo) {
                $this->assign('jborderinfo', $jborderinfo);
                
                $jblistinfo = $jborder->where('order_parent = '.$jborderinfo['id'])->select();
                if (!count($jblistinfo)) {
                    $jblistinfo[] = $jborderinfo;
                }
                $this->assign('jblistinfo', $jblistinfo);
                
                $jbedu = M("jbedu");
                $jbeduinfo = $jbedu->field('edu_name')->where('jborder_id = '.$jborderinfo['id'])->join(' fy_edu on fy_edu.id = fy_jbedu.edu_id')->select();
                $this->assign('jbeduinfo', $jbeduinfo);
                
                $this->display('modjborder');
            } else {
                $pyorder = M("pyorder");
                $pyorderinfo = $pyorder->where('order_number="'.$order_number.'"')->find();
                $this->assign('pyorderinfo', $pyorderinfo);
                $this->display('modpyorder');
            }
        }
    }

    public function updateorder() {
        $order = M("order");
        $edu = M("edu");
        $order_number = $this->_post('order_number');
        $order_status = $this->_post('order_status');
        $orderinfo = $order->where('order_number="'.$order_number.'"')->find();
        if ($orderinfo) {
            $issuccess = $order->where('order_number="'.$order_number.'"')->setField('order_status', $order_status);
            if ($issuccess && $order_status == 2) {
                $eduinfo = $edu->where('id = '.$orderinfo['edu_id'])->find();
                $edu_sign = $eduinfo['edu_sign'];
                $edu_sign = ($edu_sign) ? $edu_sign + 1 : 1;
                $edu->where('id = '.$value)->setField('edu_sign', $edu_sign);
            }
        } else {
            $jborder = M("jborder");
            $jborderinfo = $jborder->where('order_number="'.$order_number.'" and order_parent = 0')->find();
            if ($jborderinfo) {
                $issuccess = $jborder->where('order_number="'.$order_number.'" and order_parent = 0')->setField('order_status', $order_status);
                $jborder->where('order_parent = '.$jborderinfo['id'])->setField('order_status', $order_status);
                if ($issuccess && $order_status == 2) {
                    $jbedu = M("jbedu");
                    $edulists = $jbedu->field('edu_id')->where('jborder_id = "'.$jborderinfo['id'].'"')->select();
                    foreach ($edulists as $eduinfo) {
                        $eduinfo = $edu->where('id = '.$eduinfo['edu_id'])->find();
                        $edu_sign = $eduinfo['edu_sign'];
                        $edu_sign = ($edu_sign) ? $edu_sign + 1 : 1;
                        $edu->where('id = '.$value)->setField('edu_sign', $edu_sign);
                    }
                }
            }
        }
        $this->redirect('Order/lists');
    }
    
    public function delorder(){
        $order_number = $this->_get('oid');
        $order = M("order");
        $orderinfo = $order->where('order_number="'.$order_number.'"')->find();
        if ($orderinfo) {
            $order->where('order_number="'.$order_number.'"')->delete();
        } else {
            $jborder = M("jborder");
            $jborderinfo = $jborder->where('order_number="'.$order_number.'" and order_parent = 0')->find();
            if ($jborderinfo) {
                $jborder->where('order_number="'.$order_number.'" and order_parent = 0')->delete();
                $jborder->where('order_parent = '.$jborderinfo['id'])->delete();
            } else {
                $pyorder = M("pyorder");
                $pyorderinfo = $pyorder->where('order_number="'.$order_number.'"')->find();
                if ($pyorderinfo) {
                    $pyorder->where('order_number="'.$order_number.'"')->delete();
                }
            }
        }
        $this->redirect('Order/lists');
    }
}