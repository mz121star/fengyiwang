<?php

class PublicAction extends Action {

    protected $userInfo = array();

    public function __construct(){
        $this->userInfo = session('userinfo');
        if(empty($this->userInfo) || $this->userInfo['user_type'] == 3){
            $this->display('Index:showlogin');
            exit;
        }
        if ($this->userInfo['user_type'] == 2) {
            $shop_role = C('SHOP_ROLE');
            if (!isset($shop_role[MODULE_NAME])) {
                $this->error("没有权限");
            }
            if (!in_array(ACTION_NAME, $shop_role[MODULE_NAME])) {
                $this->error("没有权限");
            }
        }
        $this->assign('current_c', MODULE_NAME);
        $this->assign('current_a', ACTION_NAME);
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