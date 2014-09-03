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
}