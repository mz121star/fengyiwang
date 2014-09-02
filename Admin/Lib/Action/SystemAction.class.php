<?php

class SystemAction extends PublicAction {

    public function showimage() {
        $system = M("systempic");
        $syspicinfo = $system->select();
        $this->assign('syspicinfo', $syspicinfo);
        $max = $system->field('max(id) as id')->find();
        $this->assign('totalpic', $max['id']);
        $this->display();
    }

    public function setimage() {
        $post = $this->filterAllParam('post');
        $isdelimage = $post['del_image'];
        $del_image = array();
        if (count($isdelimage)) {
            foreach ($isdelimage as $key => $value) {
                $del_image[] = $key;
                unlink('./upload/'.$value);
            }
        }
        $system = M("systempic");
        foreach ($del_image as $id) {
            $system->where('id='.$id)->delete();
        }

        import('ORG.Net.UploadFile');
        $upload = new UploadFile();
        $upload->maxSize = 3145728;//3M
        $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->savePath = './upload/';
        if($upload->upload()) {
            $info = $upload->getUploadFileInfo();
        }

        foreach ($info as $key => $value) {
            $id = explode('-', $value['key'])[1];
            $ishave = $system->where('id = '.$id)->count();
            if ($ishave) {
                $system->where('id = '.$id)->save(array('system_pic'=>$value['savename'], 'system_picurl'=>$post['system_picurl-'.$id]));
            } else {
                $system->add(array('system_pic'=>$value['savename'], 'system_picurl'=>$post['system_picurl-'.$id]));
            }
            unset($post['system_picurl-'.$id]);
        }

        foreach ($post as $key => $value) {
            $id = explode('-', $key)[1];
            $ishave = $system->where('id = '.$id)->count();
            if ($ishave) {
                $system->where('id = '.$id)->save(array('system_picurl'=>$post['system_picurl-'.$id]));
            }
        }
        $this->redirect('System/showimage');
    }
}