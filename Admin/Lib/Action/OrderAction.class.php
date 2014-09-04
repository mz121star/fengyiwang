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
        $this->display();
    }
    
    public function jblists(){
        $jborder = M("jborder");
        import('ORG.Util.Page');
        $count = $jborder->where('order_parent=0 and user_jbname != ""')->count();
        $page = new Page($count, 10);
        $orderlist = $jborder->field('fy_jborder.id, fy_jbedu.id as jid, user_jbname, user_jbphone, user_jbdesc, order_date, order_status, edu_name, edu_image')->where('order_parent=0 and user_jbname != ""')->join(' fy_jbedu on fy_jbedu.jborder_id=fy_jborder.id')->join(' fy_edu on fy_jbedu.edu_id=fy_edu.id')->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('orderlist', $orderlist);
        $this->display();
    }

    public function search(){
        $post = $this->filterAllParam('post');
        $where = array();
        if ($post['order_date']) {
            $where['order_date'] = $post['order_date'];
        }
        if ($post['order_status']) {
            $where['order_status'] = $post['order_status'];
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
        $this->display('lists');
    }

    public function recommend(){
        $oid = $this->_get('oid');
        $order = M("order");
        $orderinfo = $order->where('id='.$oid)->find();
        if ($orderinfo) {
            $order->where('id='.$oid)->setField('order_status','1');
        } else {
            $jborder = M("jborder");
            $jborderinfo = $jborder->where('id='.$oid)->find();
            if ($jborderinfo) {
                $jborder->where('id='.$oid)->setField('order_status','1');
                $jborder->where('order_parent='.$oid)->setField('order_status','1');
            }
        }
        $this->redirect('Order/lists');
    }

    public function sign(){
        $oid = $this->_get('oid');
        $order = M("order");
        $orderinfo = $order->where('id='.$oid)->find();
        if ($orderinfo) {
            $order->where('id='.$oid)->setField('order_status','3');
        } else {
            $jborder = M("jborder");
            $jborderinfo = $jborder->where('id='.$oid)->find();
            if ($jborderinfo) {
                $jborder->where('id='.$oid)->setField('order_status','3');
                $jborder->where('order_parent='.$oid)->setField('order_status','3');
            }
        }
        $this->redirect('Order/lists');
    }

    public function modorder(){
        $oid = $this->_get('oid');
        $order = M("order");
        $orderinfo = $order->where('id='.$oid)->find();
        if ($orderinfo) {
            $this->assign('orderinfo', $orderinfo);
        } else {
            $jborder = M("jborder");
            $jborderinfo = $jborder->where('id='.$oid)->find();
            if ($jborderinfo) {
                $this->assign('orderinfo', $jborderinfo);
            }
        }
        $this->display();
    }

    public function updateorder() {
        $this->redirect('Order/lists');
    }
    
    public function delorder(){
        $this->redirect('Order/lists');
    }
}