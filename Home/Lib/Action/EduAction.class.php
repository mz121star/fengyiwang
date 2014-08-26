<?php

class EduAction extends PublicAction {

    public function lists(){
        $sid = $this->_get('sid');
        $sectionedu = M("sectionedu");
        $edulist = $sectionedu->field('fy_edu.id, edu_name, edu_star, edu_image, edu_discount, edu_desc, edu_browse, edu_choose, edu_sign')->where('section_id = '.$sid)->join(' fy_edu on fy_edu.id=fy_sectionedu.edu_id')->select();
        $this->assign('edulist', $edulist);
        $this->display();
    }
    
    public function detail() {
        $post = $this->filterAllParam('post');
        print_r($post);exit;
    }
}