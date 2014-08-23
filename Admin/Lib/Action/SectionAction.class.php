<?php

class SectionAction extends PublicAction {

    public function lists(){
        $section = M("section");
        import('ORG.Util.Page');
        $count = $section->count();
        $page = new Page($count, 10);
        $sectionlist = $section->order(array('id'=>'desc'))->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('sectionlist', $sectionlist);
        $this->display();
    }

    public function showadd(){
        $this->display();
    }
    
    public function modsection() {
        $sid = $this->_get('sid');
        $section = M("section");
        $sectioninfo = $section->where('id='.$sid)->find();
        if (!$sectioninfo) {
            $this->redirect('Section/lists');
        }
        $this->assign('sectioninfo', $sectioninfo);
        $this->display();
    }
    
    public function delsection(){
        $sid = $this->_get('sid');
        $section = M("section");
        $sectioninfo = $section->where('id='.$sid)->find();
        if ($sectioninfo) {
            $sectionnumber = $section->where('id='.$sid)->delete();
            if ($sectionnumber) {
                unlink('./upload/'.$sectioninfo['section_image']);
                $edu = M("edu");
                $edu->where('section_id='.$sid)->delete();
                $this->redirect('Section/lists');
            } else {
                $this->error("删除板块失败", 'lists');
            }
        } else {
            $this->error("删除板块失败", 'lists');
        }
    }

    public function save(){
        $userid = $this->userInfo['user_id'];
        $isdelimage = $this->_post('delsection_image');
        if ($isdelimage) {
            $_POST['section_image'] = '';
            unlink('./upload/'.$isdelimage);
        }
        if ($_FILES['section_image']['name']) {
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
            $_POST['section_image'] = $info[0]['savename'];
        }
        $section = M("section");
        $post = $this->filterAllParam('post');
        if (isset($post['id']) && $post['id']) {
            $sectionnumber = $section->where('id='.$post['id'])->save($post);
        } else {
            $sectionid = $section->add($post);
        }
        $this->redirect('Section/lists');
    }
}