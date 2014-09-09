<?php

class IndexAction extends Action {

    public function index(){
        $section = M("section");
        $sectionlist = $section->order(array('id'=>'asc'))->limit('0,6')->select();
        $this->assign('sectionlist', $sectionlist);

        $system = M("systempic");
        $syspicinfo = $system->select();
        $this->assign('syspicinfo', $syspicinfo);
        $this->display();
    }
    
    public function home(){
        $uid = $this->_get('uid');
        
        $user = M('User');
        $userinfo = $user->field('id, user_id, user_name, user_phone, user_school, user_zhuanye, user_age, user_weixin, user_type')->where('user_id = "'.$uid.'"')->find();
        if ($userinfo) {
            session('userinfo', $userinfo);
        } else {
            $today = date('Y-m-d H:i:s');
            $id = $user->add(array('user_id'=>$uid, 'user_pw'=>  md5($uid), 'user_weixin'=>$uid, 'user_regdate'=>$today));
            session('userinfo', array('id'=>$id, 'user_id'=>$uid, 'user_name'=>'', 'user_phone'=>'', 'user_school'=>'', 'user_zhuanye'=>'', 'user_age'=>'', 'user_weixin'=>$uid, 'user_type'=>2));
        }
        
        $section = M("section");
        $sectionlist = $section->order(array('id'=>'asc'))->limit('0,6')->select();
        $this->assign('sectionlist', $sectionlist);
        
        $system = M("systempic");
        $syspicinfo = $system->select();
        $this->assign('syspicinfo', $syspicinfo);
        $this->display('index');
    }
    
    public function send() {
        $uid = $this->_get('uid');
        $send = $this->_get('send');

        $user = M('User');
        $userinfo = $user->field('id, user_id, user_name, user_phone, user_school, user_zhuanye, user_age, user_weixin, user_type')->where('user_id = "'.$uid.'"')->find();
        if ($userinfo) {
            session('userinfo', $userinfo);
        } else {
            $today = date('Y-m-d H:i:s');
            $id = $user->add(array('user_id'=>$uid, 'user_pw'=>  md5($uid), 'user_weixin'=>$uid, 'user_regdate'=>$today));
            session('userinfo', array('id'=>$id, 'user_id'=>$uid, 'user_name'=>'', 'user_phone'=>'', 'user_school'=>'', 'user_zhuanye'=>'', 'user_age'=>'', 'user_weixin'=>$uid, 'user_type'=>2));
        }
        if ($send == 'regphone') {
            $this->redirect('index/regphone');
        } elseif ($send == 'swhz') {
            $this->redirect('index/swhz');
        } else {
            $this->redirect('edu/'.$send);
        }
    }

    public function swhz() {
        $business = M("business");
        $businessinfo = $business->find();
        $this->assign('businessinfo', $businessinfo);
        $this->display();
    }
    
    public function reg() {
        $this->display();
    }

    public function jump() {
        $this->display();
    }
    
    public function regphone() {
        $this->display();
    }
    
    public function doregphone() {
        $userinfo = session('userinfo');
        $post = $this->filterAllParam('post');
        $user = M("User");
        $userInfo = $user->where('user_id="'.$userinfo['user_id'].'"')->find();
        if (!$userInfo) {
            $this->error("用户不存在", 'index');
        }
        if (!$post['user_name']) {
            $this->error("请填写姓名", 'regphone');
        }
        if (!$post['code']) {
            $this->error("请填写验证码", 'regphone');
        }
        $getcode = session('getcode');
        if ($getcode != $post['code']) {
            $this->error("验证码错误", 'regphone');
        } else {
            session('getcode', null);
        }
        $userid = $user->where('user_id="'.$userinfo['user_id'].'"')->save($post);
        $this->success('绑定成功', 'index');
    }

    public function showlogin() {
        $this->display();
    }

    public function getcode() {
        $code = rand(10000, 99999);
        //短信接口机构代码 $jgid
        $jgid = '300';
        //短信接口用户名 $loginname
        $loginname = 'hummerlys';
        //短信接口密码 $passwd
        $passwd = '851514';
        //发送到的目标手机号码 $telphone，多个号码用半角分号分隔
        $telphone = $this->_post('phone');
        //短信内容 $message
        $message = urlencode('尊敬的客户：'.$code.'（人人汇手机动态码以生成，请完成验证）');
        $gateway = 'http://223.4.21.214:8180/service.asmx/SendMessageStr?Id='.$jgid.'&Name='.$loginname.'&Psw='.$passwd.'&Message='.$message.'&Phone='.$telphone.'&Timestamp=0';
        $result = file_get_contents($gateway);
        session('getcode', $code);
        echo $result;
        exit;
    }

    public function doregist() {
        $post = $this->filterAllParam('post');
        if (!$post['user_id']) {
            $this->error("用户名不能为空", 'index');
        }
        if (!$post['user_pw']) {
            $this->error("密码不能为空", 'index');
        }
        $user = M("User");
        $userInfo = $user->where('user_id="'.$post['user_id'].'"')->field('id')->find();
        if ($userInfo) {
            $this->error("用户ID已存在", 'index');
        }
        $getcode = session('getcode');
        if ($getcode != $post['code']) {
            $this->error("验证码错误", 'index');
        } else {
            session('getcode', null);
        }
        $post['user_pw'] = md5($post['user_pw']);
        $userid = $user->add($post);
        $this->redirect('index/index');
    }

    public function login() {
        $user = M("User");
        $_POST['user_id'] = $this->_post('user_id');
        $_POST['user_pw'] = md5($this->_post('user_pw'));
        $_POST['user_status'] = 1;
        $userInfo = $user->where($_POST)->field('id,user_id,user_name,user_phone,user_school,user_zhuanye,user_age,user_type')->find();
        if(!empty($userInfo) && $userInfo['user_type'] == 2){
            session('userinfo', $userInfo);
        }
        $this->redirect('Index/index');
    }

    public function logout() {
        $userInfo = session('userinfo');
        if(!empty($userInfo)){
            session('userinfo', null);
            unset($_SESSION['cart']);
        }
        $this->redirect('Index/index');
    }
    
    protected function filterAllParam($type = 'get') {
        $param = array();
        if ($type == 'get') {
            foreach ($_GET as $key => $value) {
                $param[$key] = $this->_get($key);
            }
        } elseif ($type == 'post') {
            foreach ($_POST as $key => $value) {
                $param[$key] = $this->_post($key);
            }
        } else {
            foreach ($_REQUEST as $key => $value) {
                $param[$key] = $this->_param($key);
            }
        }
        return $param;
    }
}