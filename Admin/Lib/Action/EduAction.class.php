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
        $eduinfo = $edu->field('fy_edu.id, edu_name, edu_star, edu_image, edu_discount, edu_desc, section_id')->where('fy_edu.id='.$eduid)->join(' fy_section on fy_section.id=fy_edu.section_id')->find();
        if (!$eduinfo) {
            $this->redirect('Edu/lists');
        }
        $this->assign('eduinfo', $eduinfo);

        $section = M("section");
        $sectionlist = $section->select();
        $this->assign('sectionlist', $sectionlist);
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
        if (isset($post['id']) && $post['id']) {
            $edunumber = $edu->where('id='.$post['id'])->save($post);
        } else {
            $eduid = $edu->add($post);
        }
        $this->redirect('Edu/lists');
    }
}