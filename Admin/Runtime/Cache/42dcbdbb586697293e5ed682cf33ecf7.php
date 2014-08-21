<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>订餐系统</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/signin.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <form class="form-signin" role="form" name="loginform" method="post" action="__GROUP__/Index/login">
        <input type="text" id="user_id" name="user_id" class="form-control" placeholder="用户名" required autofocus>
        <input type="password" id="user_pw" name="user_pw" class="form-control" placeholder="请输入密码" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
      </form>
    </div>
  </body>
</html>