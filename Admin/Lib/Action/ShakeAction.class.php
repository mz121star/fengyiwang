<?php

class ShakeAction extends PublicAction {

    public function index(){

            $Model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表
            $result=$Model->query("  SELECT * FROM fy_shake_user s,fy_hongbaorecord h WHERE s.openid=h.openid");
            $this->assign("list",$result);
             $this->display();
    }


}