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
            $userid = $user->add($post);
        }
        $this->redirect('User/lists');
    }
}