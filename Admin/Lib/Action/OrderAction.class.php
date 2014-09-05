<?php

class OrderAction extends PublicAction {

    public function lists(){
        $order = M("order");
        import('ORG.Util.Page');
        $count = $order->count();
        $page = new Page($count, 10);
        $orderlist = $order->order(array('id'=>'desc'))->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('orderlist', $orderlist);
        
        $order_status = C('ORDER_STATUS');
        $this->assign('order_status_list', $order_status);
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
        if ($post['user_id']) {
            $where['user_id'] = $post['user_id'];
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
        
        $this->assign('order_date', $post['order_date']);
        $this->assign('order_number', $post['order_number']);
        $this->assign('user_id', $post['user_id']);
        $this->display('lists');
    }
    
    public function jblists(){
        $jborder = M("jborder");
        import('ORG.Util.Page');
        $count = $jborder->where('order_parent=0 and user_jbname != ""')->count();
        $page = new Page($count, 10);
        $orderlist = $jborder->field('fy_jborder.id, fy_jbedu.id as jid, user_jbname, user_jbphone, user_jbdesc, order_number, order_date, order_status, edu_name, edu_image')->where('order_parent=0 and user_jbname != ""')->join(' fy_jbedu on fy_jbedu.jborder_id=fy_jborder.id')->join(' fy_edu on fy_jbedu.edu_id=fy_edu.id')->limit($page->firstRow.','.$page->listRows)->select();
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
        if ($post['user_id']) {
            $where['user_id'] = $post['user_id'];
        }
        $where['order_parent'] = 0;
        $where['user_jbname'] = array('neq', '');
        $jborder = M("jborder");
        import('ORG.Util.Page');
        $count = $jborder->where($where)->count();
        $page = new Page($count, 10);
        $orderlist = $jborder->field('fy_jborder.id, fy_jbedu.id as jid, user_jbname, user_jbphone, user_jbdesc, order_date, order_status, edu_name, edu_image')->where($where)->join(' fy_jbedu on fy_jbedu.jborder_id=fy_jborder.id')->join(' fy_edu on fy_jbedu.edu_id=fy_edu.id')->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('orderlist', $orderlist);
        
        $order_status_list = C('ORDER_STATUS');
        $this->assign('order_status_list', $order_status_list);
        
        $this->assign('order_date', $post['order_date']);
        $this->assign('order_number', $post['order_number']);
        $this->assign('user_id', $post['user_id']);
        $this->display('jblists');
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
        $orderinfo = $order->where('order_number="'.$order_number.'"')->find();
        if ($orderinfo) {
            $order->where('order_number="'.$order_number.'"')->setField('order_status','3');
        } else {
            $jborder = M("jborder");
            $jborderinfo = $jborder->where('order_number="'.$order_number.'" and order_parent = 0')->find();
            if ($jborderinfo) {
                $jborder->where('order_number="'.$order_number.'"')->setField('order_status','3');
                $jborder->where('order_parent='.$jborderinfo['id'])->setField('order_status','3');
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
        } else {
            $jborder = M("jborder");
            $jborderinfo = $jborder->where('order_number="'.$order_number.'" and order_parent = 0')->find();
            if ($jborderinfo) {
                $this->assign('orderinfo', $jborderinfo);
            }
        }
        $this->display();
    }

    public function updateorder() {
        $order = M("order");
        $order_number = $this->_post('order_number');
        $order_status = $this->_post('order_status');
        $orderinfo = $order->where('order_number="'.$order_number.'"')->find();
        if ($orderinfo) {
            $order->where('order_number="'.$order_number.'"')->setField('order_status', $order_status);
        } else {
            $jborder = M("jborder");
            $jborderinfo = $jborder->where('order_number="'.$order_number.'" and order_parent = 0')->find();
            if ($jborderinfo) {
                $this->where('order_number="'.$order_number.'" and order_parent = 0')->setField('order_status', $order_status);
                $this->where('order_parent = '.$jborderinfo['id'])->setField('order_status', $order_status);
            }
        }
        $this->redirect('Order/lists');
    }
    
    public function delorder(){
        $order = M("order");
        $order_number = $this->_post('order_number');
        $order_status = $this->_post('order_status');
        $orderinfo = $order->where('order_number="'.$order_number.'"')->find();
        if ($orderinfo) {
            $order->where('order_number="'.$order_number.'"')->delete();
        } else {
            $jborder = M("jborder");
            $jborderinfo = $jborder->where('order_number="'.$order_number.'" and order_parent = 0')->find();
            if ($jborderinfo) {
                $this->where('order_number="'.$order_number.'" and order_parent = 0')->delete();
                $this->where('order_parent = '.$jborderinfo['id'])->delete();
            }
        }
        $this->redirect('Order/lists');
    }
}