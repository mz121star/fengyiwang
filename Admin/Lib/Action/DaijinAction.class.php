<?php

class DaijinAction extends PublicAction {

    public function lists(){
        $edu = M("baoming_dj");
        import('ORG.Util.Page');
        $count = $edu->count();
        $page = new Page($count, 10);
        $edulist = $edu->order(array('Id'=>'desc'))->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('edulist', $edulist);
        

        $this->display();
    }
    
    public function eduorder(){
        $order = $this->_get("order");
        $edu = M("edu");
        import('ORG.Util.Page');
        $count = $edu->count();
        $page = new Page($count, 10);
        if ($order) {
            $orderby = array($order=>'desc');
        } else {
            $orderby = array('id'=>'desc');
        }
        $edulist = $edu->order($orderby)->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('edulist', $edulist);
        
        $section = M("section");
        $sectionlist = $section->select();
        $this->assign('sectionlist', $sectionlist);
        $this->assign('orderby', $order);
        $this->display('lists');
    }
    
    public function search(){
        $post = $this->filterAllParam('post');
        $where = array();
        if ($post['edu_name']) {
            $where['edu_name'] = array('like', '%'.$post['edu_name'].'%');
        }
        if ($post['edu_jblx']) {
            $where['edu_jblx'] = "1";
        }
        if ($post['edu_tglx']) {
            $where['edu_tglx'] = "1";
        }
        if ($post['edu_jbxx']) {
            $where['edu_jbxx'] = "1";
        }
        if ($post['edu_tgxx']) {
            $where['edu_tgxx'] = "1";
        }
        $eduidlist = array();
        if (count($post['section_id'])) {
            $sectionedu = M("sectionedu");
            $sections = implode(',', $post['section_id']);
            $sectionwhere['section_id']  = array('in', $sections);
            $sectioneduinfo = $sectionedu->field('distinct(edu_id) as edu_id')->where($sectionwhere)->select();
            foreach ($sectioneduinfo as $info) {
                $eduidlist[] = $info['edu_id'];
            }
        }
        if (count($eduidlist)) {
            $sectionids = implode(',', $eduidlist);
            $where['id'] = array('in', $sectionids);
        } elseif (count($post['section_id'])) {
            $where['id'] = 0;
        }
        $edu = M("edu");
        import('ORG.Util.Page');
        $count = $edu->where($where)->count();
        $page = new Page($count, 10);
        $edulist = $edu->where($where)->order(array('id'=>'desc'))->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('edulist', $edulist);
        
        $section = M("section");
        $sectionlist = $section->select();
        $sectionarray = array();
        foreach ($sectionlist as $sinfo) {
            if (in_array($sinfo['id'], $post['section_id'])) {
                $sinfo['isselect'] = 1;
            } else {
                $sinfo['isselect'] = 0;
            }
            $sectionarray[] = $sinfo;
        }
        $this->assign('sectionlist', $sectionarray);
        
        $this->assign('edu_name', $post['edu_name']);
        $this->assign('edu_jblx', $post['edu_jblx']);
        $this->assign('edu_tglx', $post['edu_tglx']);
        $this->assign('edu_jbxx', $post['edu_jbxx']);
        $this->assign('edu_tgxx', $post['edu_tgxx']);
        $this->display('lists');
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
        if (!isset($_POST['name']) || !$_POST['name']) {
            $this->error("请填写团购名称");
        }
        $isdelimage = $this->_post('image');
        if ($isdelimage) {
            $_POST['image'] = '';
            unlink('./upload/'.$isdelimage);
        }
        if ($_FILES['image']['name']) {
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
            $_POST['image'] = $info[0]['savename'];
        }
        $tuangou = M("daijin");
        $post = $this->filterAllParam('post');

        $tuangouid = $tuangou->add($post);
        if($tuangouid){
            $this->success("增加代金券信息成功");
        }else{
            $this->error("增加代金券信息失败");
        }
    }
}