<?php

class UserAction extends PublicAction {

    public function lists(){
        $user = M("user");
        import('ORG.Util.Page');
        $count = $user->where('user_id != "admin"')->count();
        $page = new Page($count, 10);
        $userlist = $user->where('user_id != "admin"')->order(array('id'=>'desc'))->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('userlist', $userlist);
        $this->display();
    }
    
    public function search(){
        $post = $this->filterAllParam('post');
        $where = array();
        if ($post['user_name']) {
            $where['user_name'] = array('like', '%'.$post['user_name'].'%');
        }
        if ($post['user_phone']) {
            $where['user_phone'] = array('like', '%'.$post['user_phone'].'%');
        }
        if ($post['user_recommend']) {
            $where['user_recommend'] = array('eq', $post['user_recommend']);
        }
        if ($post['user_isrecommend']) {
            $where['user_isrecommend'] = array('eq', $post['user_isrecommend']);
        }
        $where['user_id'] = array('neq', 'admin');
        $user = M("user");
        import('ORG.Util.Page');
        $count = $user->where($where)->count();
        $page = new Page($count, 10);
        $userlist = $user->where($where)->order(array('id'=>'desc'))->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('userlist', $userlist);
        $this->assign('user_name', $post['user_name']);
        $this->assign('user_phone', $post['user_phone']);
        $this->assign('user_recommend', $post['user_recommend']);
        $this->assign('user_isrecommend', $post['user_isrecommend']);
        $this->display('lists');
    }

    public function showadd(){
        $this->display();
    }

    public function moduser() {
        $uid = $this->_get('uid');
        $user = M("user");
        $userinfo = $user->where('user_id="'.$uid.'"')->find();
        if (!$userinfo) {
            $this->error("查无此人", 'lists');
        }
        $this->assign('userinfo', $userinfo);
        $this->display();
    }
    
    public function deluser(){
        $uid = $this->_get('uid');
        $user = M("user");
        $userinfo = $user->where('user_id="'.$uid.'"')->find();
        if ($userinfo) {
            $usernumber = $user->where('user_id="'.$uid.'"')->delete();
            if ($usernumber) {
                $this->redirect('User/lists');
            } else {
                $this->error("删除用户失败", 'lists');
            }
        } else {
            $this->error("删除用户失败", 'lists');
        }
    }

    public function save(){
//        $isdelimage = $this->_post('deledu_image');
//        if ($isdelimage) {
//            $_POST['edu_image'] = '';
//            unlink('./upload/'.$isdelimage);
//        }
//        if ($_FILES['edu_image']['name']) {
//            import('ORG.Net.UploadFile');
//            $upload = new UploadFile();
//            $upload->maxSize = 3145728;//3M
//            $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg');
//            $upload->savePath = './upload/';
//            if(!$upload->upload()) {
//                $this->error($upload->getErrorMsg());
//            }else{
//                $info = $upload->getUploadFileInfo();
//            }
//            $_POST['edu_image'] = $info[0]['savename'];
//        }
        $user = M("user");
        $post = $this->filterAllParam('post');
        if (isset($post['id']) && $post['id']) {
            if ($post['user_pw']) {
                $post['user_pw'] = md5($post['user_pw']);
            } else {
                unset($post['user_pw']);
            }
            $usernumber = $user->where('id="'.$post['id'].'"')->save($post);
        } else {
            $post['user_pw'] = md5($post['user_pw']);
            $post['user_status'] = '1';
            $post['user_regdate'] = date('Y-m-d H:i:s');
            $userid = $user->add($post);
        }
        $this->redirect('User/lists');
    }
}