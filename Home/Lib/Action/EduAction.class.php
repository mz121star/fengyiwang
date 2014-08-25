<?php

class EduAction extends PublicAction {

    public function lists(){
        $sid = $this->_get('sid');
        $edu = M("edu");
        import('ORG.Util.Page');
        $count = $edu->count();
        $page = new Page($count, 100);
        $edulist = $edu->where('section_id = '.$sid)->order(array('id'=>'desc'))->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('edulist', $edulist);
        $this->display();
    }
}