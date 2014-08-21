<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>订餐系统</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/dashboard.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/js/ie10-viewport-bug-workaround.js"></script>
    <!--[if lt IE 9]>
      <script src="/js/html5shiv.js"></script>
      <script src="/js/respond.min.js"></script>
    <![endif]-->
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/docs.min.js"></script>
  </head>
  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="__GROUP__/Index">订餐系统后台管理</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="__GROUP__/Index/logout">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">

            <?php if($_SESSION['userinfo']['user_type']== 1): ?><ul class="nav nav-sidebar">
            <li <?php if($current_c == 'User' and $current_a == 'shoplist'): ?>class="active"<?php endif; ?>><a href="__GROUP__/User/shoplist">商户列表</a></li>
            <li <?php if($current_c == 'User' and $current_a == 'peoplelist'): ?>class="active"<?php endif; ?>><a href="__GROUP__/User/peoplelist">普通用户列表</a></li>
            <li <?php if($current_c == 'User' and $current_a == 'showadd'): ?>class="active"<?php endif; ?>><a href="__GROUP__/User/showadd">添加用户</a></li>
            <li <?php if($current_c == 'User' and $current_a == 'shoptype'): ?>class="active"<?php endif; ?>><a href="__GROUP__/User/shoptype">商户类型</a></li>
          </ul><?php endif; ?>

          <ul class="nav nav-sidebar">
            <li <?php if($current_c == 'Food' and $current_a == 'lists'): ?>class="active"<?php endif; ?>><a href="__GROUP__/Food/lists">菜品列表</a></li>
             <?php if($_SESSION['userinfo']['user_type']== 2): ?><li <?php if($current_c == 'Food' and $current_a == 'showadd'): ?>class="active"<?php endif; ?>><a href="__GROUP__/Food/showadd">添加菜肴</a></li><?php endif; ?>
          </ul>
            
           <ul class="nav nav-sidebar">
            <li <?php if($current_c == 'Notice' and $current_a == 'lists'): ?>class="active"<?php endif; ?>><a href="__GROUP__/Notice/lists">公告列表</a></li>
             <?php if($_SESSION['userinfo']['user_type']== 2): ?><li <?php if($current_c == 'Notice' and $current_a == 'showadd'): ?>class="active"<?php endif; ?>><a href="__GROUP__/Notice/showadd">添加公告</a></li><?php endif; ?>
          </ul>

          <ul class="nav nav-sidebar">
            <li <?php if($current_c == 'Order' and $current_a == 'lists'): ?>class="active"<?php endif; ?>><a href="__GROUP__/Order/lists">订单管理</a></li>
          </ul>

            <?php if($_SESSION['userinfo']['user_type']== 1): ?><ul class="nav nav-sidebar">
            <li <?php if($current_c == 'System' and $current_a == 'showset'): ?>class="active"<?php endif; ?>><a href="__GROUP__/System/showset">系统信息配置</a></li>
            <li <?php if($current_c == 'System' and $current_a == 'showimage'): ?>class="active"<?php endif; ?>><a href="__GROUP__/System/showimage">首页图片配置</a></li>
          </ul><?php endif; ?>
            <?php if($_SESSION['userinfo']['user_type']== 2): ?><ul class="nav nav-sidebar">
            <li <?php if($current_c == 'User' and $current_a == 'modself'): ?>class="active"<?php endif; ?>><a href="__GROUP__/User/modself">信息设定</a></li>
          </ul><?php endif; ?>

        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h2 class="sub-header">欢迎来到订餐系统后台管理</h2>
        </div>
      </div>
    </div>
  </body>
</html>