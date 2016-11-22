<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="#" type="image/png">

    <title>登录-电竞之家后台管理系统</title>

    <link href="__PUBLIC__/css/style.css" rel="stylesheet">
    <link href="__PUBLIC__/css/style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="__PUBLIC__/js/html5shiv.js"></script>
    <script src="__PUBLIC__/js/respond.min.js"></script>
    <![endif]-->
</head>

<body class="login-body">

<div class="container">

    <form class="form-signin" action="<?php echo U('User/checklogin');?>" method="post">
        <div class="form-signin-heading text-center">
            <h1 class="sign-title">欢迎登录</h1>
            <h2 style="font-size:26px;color:#666;">电竞之家后台管理系统</h2>
        </div>
        <div class="login-wrap">
            <input type="text" class="form-control" placeholder="用户名：" name="yonghuming" autofocus>
            <input type="password" class="form-control" placeholder="密码：" name="mima">

			<input class="btn btn-lg btn-login btn-block" type="submit" value="登录" >


        </div>


    </form>

</div>



<script src="__PUBLIC__/js/jquery-1.10.2.min.js"></script>
<script src="__PUBLIC__/js/bootstrap.min.js"></script>
<script src="__PUBLIC__/js/modernizr.min.js"></script>

</body>
</html>