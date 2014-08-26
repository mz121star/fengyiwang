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

    
}