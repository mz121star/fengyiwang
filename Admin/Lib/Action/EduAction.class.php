<?php

class EduAction extends PublicAction {

    public function lists(){
        $edu = M("edu");
        import('ORG.Util.Page');
        $count = $edu->count();
        $page = new Page($count, 10);
        $edulist = $edu->order(array('id'=>'desc'))->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('edulist', $edulist);
        $this->display();
    }

    public function showadd(){
        $section = M("section");
        $sectionlist = $section->select();
        $this->assign('sectionlist', $sectionlist);
        $this->display();
    }

    public function modedu() {
        $eduid = $this->_get('eduid');
        $edu = M("edu");
        $eduinfo = $edu->where('id='.$eduid)->find();
        if (!$eduinfo) {
            $this->redirect('Edu/lists');
        }
        $this->assign('eduinfo', $eduinfo);

        $section = M("section");
        $sectionlist = $section->select();
        $edusection = array();
        $sectionedu = M("sectionedu");
        $edusectionlist = $sectionedu->where('edu_id = '.$eduid)->select();
        $selectsection = array();
        foreach ($edusectionlist as $value) {
            $selectsection[] = $value['section_id'];
        }
        foreach ($sectionlist as $value) {
            if (in_array($value['id'], $selectsection)) {
                $value['is_selected'] = 'checked';
            } else {
                $value['is_selected'] = '';
            }
            $edusection[] = $value;
        }
        $this->assign('sectionlist', $edusection);
        $this->display();
    }
    
    public function deledu(){
        $eduid = $this->_get('eduid');
        $edu = M("edu");
        $eduinfo = $edu->where('id='.$eduid)->find();
        if ($eduinfo) {
            $edunumber = $edu->where('id='.$eduid)->delete();
            if ($edunumber) {
                unlink('./upload/'.$eduinfo['edu_image']);
                $sectionedu = M("sectionedu");
                $edunumber = $sectionedu->where('edu_id='.$eduid)->delete();
                $this->redirect('Edu/lists');
            } else {
                $this->error("删除机构失败", 'lists');
            }
        } else {
            $this->error("删除机构失败", 'lists');
        }
    }

    public function save(){
        $isdelimage = $this->_post('deledu_image');
        if ($isdelimage) {
            $_POST['edu_image'] = '';
            unlink('./upload/'.$isdelimage);
        }
        if ($_FILES['edu_image']['name']) {
            import('ORG.Net.UploadFile');
            $upload = new UploadFile();
            $upload->maxSize = 3145728;//3M
            $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg');
            $upload->savePath = './upload/';
            if(!$upload->upload()) {
                $this->error($upload->getErrorMsg());
            }else{
                $info = $upload->getUploadFileInfo();
            }
            $_POST['edu_image'] = $info[0]['savename'];
        }
        $edu = M("edu");
        $post = $this->filterAllParam('post');
        if (!isset($post['edu_jblx'])) {
            $post['edu_jblx'] = '0';
        }
        if (!isset($post['edu_tglx'])) {
            $post['edu_tglx'] = '0';
        }
        if (!isset($post['edu_jbxx'])) {
            $post['edu_jbxx'] = '0';
        }
        if (!isset($post['edu_tgxx'])) {
            $post['edu_tgxx'] = '0';
        }
        $sectionedu = M("sectionedu");
        if (isset($post['id']) && $post['id']) {
            $edunumber = $edu->where('id='.$post['id'])->save($post);
            $deletenumber = $sectionedu->where('edu_id='.$post['id'])->delete();
            $eduid = $post['id'];
        } else {
            $eduid = $edu->add($post);
        }
        foreach ($post['section_id'] as $value) {
            $sectionedu->add(array('edu_id'=>$eduid, 'section_id'=>$value));
        }
        $this->redirect('Edu/lists');
    }
}