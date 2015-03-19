<?php

class ShakeAction extends PublicAction {

    public function index(){
        $qrcode = M("qrcode");
        import('ORG.Util.Page');
        $count = $qrcode->count();
        $page = new Page($count, 10);
        $userlist = $qrcode->order(array('id'=>'desc'))->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('qrlist', $userlist);
        $this->display();
    }


}