<?php

class UserAction extends PublicAction {

    public function center() {
        $userid = $this->userInfo['user_id'];
        if(!$userid){
            $this->redirect('Index/index');
        }
        $order = M("Order");
        $orderlist = $order->where('user_id = "'.$userid.'"')->select();
        $this->assign('orderlist', $orderlist);
        
        $order = M("Order");
        $orderlist = $order->where('user_id = "'.$userid.'"')->select();
        $this->assign('orderlist', $orderlist);
        $this->display();
    }
}
