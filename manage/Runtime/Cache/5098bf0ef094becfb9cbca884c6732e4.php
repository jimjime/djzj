<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="keywords" content="admin, dashboard, bootstrap, template, flat, modern, theme, responsive, fluid, retina, backend, html5, css, css3">
  <meta name="description" content="">
  <meta name="author" content="ThemeBucket">
  <link rel="shortcut icon" href="#" type="image/png">

  <title>电竞之家后台管理系统</title>

  <!--pickers css-->
  <link rel="stylesheet" type="text/css" href="__PUBLIC__/js/bootstrap-datepicker/css/datepicker-custom.css" />
  <link rel="stylesheet" type="text/css" href="__PUBLIC__/js/bootstrap-timepicker/css/timepicker.css" />
  <link rel="stylesheet" type="text/css" href="__PUBLIC__/js/bootstrap-colorpicker/css/colorpicker.css" />
  <link rel="stylesheet" type="text/css" href="__PUBLIC__/js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
  <link rel="stylesheet" type="text/css" href="__PUBLIC__/js/bootstrap-datetimepicker/css/datetimepicker-custom.css" />

  <!--data table-->
  <link rel="stylesheet" href="__PUBLIC__/js/data-tables/DT_bootstrap.css" />

  <!--dashboard calendar-->
  <link href="__PUBLIC__/css/clndr.css" rel="stylesheet">


  <!--common-->
  <link href="__PUBLIC__/css/style.css" rel="stylesheet">
  <link href="__PUBLIC__/css/style-responsive.css" rel="stylesheet">




  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="__PUBLIC__/js/html5shiv.js"></script>
  <script src="__PUBLIC__/js/respond.min.js"></script>
  <![endif]-->
</head>

<body class="sticky-header">

<section>
    <!-- left side start-->
    <div class="left-side sticky-left-side">

        <!--logo and iconic logo start-->
        <div class="logo">
            <a href="<?php echo U('Index/shouye');?>">电竞之家</a>
        </div>

        <div class="logo-icon text-center">
            <a href="<?php echo U('Index/shouye');?>">电竞之家</a>
        </div>
        <!--logo and iconic logo end-->

        <div class="left-side-inner">

            <!-- visible to small devices only -->
            <div class="visible-xs hidden-sm hidden-md hidden-lg">
                <div class="media logged-user">
                    <img alt="" src="__PUBLIC__/images/photos/user-avatar.png" class="media-object">
                    <div class="media-body">
                        <h4><a href="javascript:;"><?php echo ($admin_info["manage_name"]); ?></a></h4>
                    </div>
                </div>

                <ul class="nav nav-pills nav-stacked custom-nav">
                  <li><a href="javascript:;" onClick="cleancache()"><i class="fa fa-cog"></i> <span>清理缓存</span></a></li>
                </ul>
            </div>

            <!--sidebar nav start-->
            <ul class="nav nav-pills nav-stacked custom-nav">

                <li class="menu-list <?php if((MODULE_NAME) == "Games"): ?>nav-active<?php endif; ?> ">
                    <a href="javascript:;"><i class="fa fa-bullhorn"></i> <span>开盘</span></a>
                    <ul class="sub-menu-list">
                        <li class=" <?php if(($_GET["gid"]) == "1"): ?>active<?php endif; ?> "><a href="<?php echo U('Games/games',array(gid=>1,fid=>1));?>">LOL</a></li>
                        <li class=" <?php if(($_GET["gid"]) == "2"): ?>active<?php endif; ?> "><a href="<?php echo U('Games/games',array(gid=>2,fid=>1));?>">Dota2</a></li>
                        <li class=" <?php if((ACTION_NAME) == "game_fenlei"): ?>active<?php endif; ?> "><a href="<?php echo U('Games/game_fenlei');?>">分类列表</a></li>
                        <li class=" <?php if((ACTION_NAME) == "game_cstate"): ?>active<?php endif; ?> "><a href="<?php echo U('Games/game_cstate');?>">竞猜状态</a></li>

                    </ul>
                </li>

                <li class="menu-list <?php if((MODULE_NAME) == "Pan"): ?>nav-active<?php endif; ?> ">
                    <a href="javascript:;"><i class="fa fa-tasks"></i> <span>盘管理</span></a>
                    <ul class="sub-menu-list">
                        <li class=" <?php if(($_GET["gid"]) == "1"): ?>active<?php endif; ?> "><a href="<?php echo U('Pan/pan',array(gid=>1,showid=>1));?>">LOL</a></li>
                        <li class=" <?php if(($_GET["gid"]) == "2"): ?>active<?php endif; ?> "><a href="<?php echo U('Pan/pan',array(gid=>2,showid=>1));?>">Dota2</a></li>

                    </ul>
                </li>

                <li class="menu-list <?php if((MODULE_NAME) == "Saishi"): ?>nav-active<?php endif; ?> ">
                    <a href="javascript:;"><i class="fa fa-tasks"></i> <span>赛事系统</span></a>
                    <ul class="sub-menu-list">
                        <li class=" <?php if((ACTION_NAME) == "bs_saicheng"): ?>active<?php endif; ?> "><a href="<?php echo U('Saishi/bs_saicheng');?>">赛程管理</a></li>
                        <li class=" <?php if((ACTION_NAME) == "bs_day"): ?>active<?php endif; ?> "><a href="<?php echo U('Saishi/bs_day');?>">赛程日期</a></li>
                        <li class=" <?php if((ACTION_NAME) == "bs_xuexiao"): ?>active<?php endif; ?> "><a href="<?php echo U('Saishi/bs_xuexiao');?>">赛区管理</a></li>
                        <li class=" <?php if((ACTION_NAME) == "bs_team"): ?>active<?php endif; ?> "><a href="<?php echo U('Saishi/bs_team');?>">战队管理</a></li>

                    </ul>
                </li>

                <li class="menu-list <?php if((MODULE_NAME) == "Index"): ?>nav-active<?php endif; ?> ">
                    <a href="javascript:;"><i class="fa fa-laptop"></i> <span>后台首页</span></a>
                    <ul class="sub-menu-list">
                        <li class=" <?php if((ACTION_NAME) == "shouye"): ?>active<?php endif; ?> "><a href="<?php echo U('Index/shouye');?>"> 后台首页</a></li>
                        <li class=" <?php if((ACTION_NAME) == "index"): ?>active<?php endif; ?> "><a href="<?php echo U('Index/index');?>"> 运营数据</a></li>
                    </ul>
                </li>

                <li class="menu-list <?php if((MODULE_NAME) == "User"): ?>nav-active<?php endif; ?> ">
					<a href="javascript:;"><i class="fa fa-laptop"></i> <span>管理员管理</span></a>
                    <ul class="sub-menu-list">
                        <li class=" <?php if((ACTION_NAME) == "admins"): ?>active<?php endif; ?> "><a href="<?php echo U('User/admins');?>"> 管理员列表</a></li>
                        <li class=" <?php if((ACTION_NAME) == "admin_group"): ?>active<?php endif; ?> "><a href="<?php echo U('User/admin_group');?>"> 管理员分组</a></li>
                        <li class=" <?php if((ACTION_NAME) == "admin_quan"): ?>active<?php endif; ?> "><a href="<?php echo U('User/admin_quan');?>"> 权限列表</a></li>

                    </ul>
                </li>
                <li class="menu-list <?php if((MODULE_NAME) == "Users"): ?>nav-active<?php endif; ?> ">
					<a href="javascript:;"><i class="fa fa-book"></i> <span>用户管理</span></a>
                    <ul class="sub-menu-list">
                        <li class=" <?php if((ACTION_NAME) == "users"): ?>active<?php endif; ?> "><a href="<?php echo U('Users/users');?>"> 用户列表</a></li>
                    </ul>
                    <ul class="sub-menu-list">
                        <li class=" <?php if((ACTION_NAME) == "allusers"): ?>active<?php endif; ?> "><a href="<?php echo U('Users/allusers');?>">all用户列表</a></li>
                    </ul>
                </li>
                <li class="menu-list <?php if((MODULE_NAME) == "Tupian"): ?>nav-active<?php endif; ?> ">
					<a href="javascript:;"><i class="fa fa-cogs"></i> <span>图片管理</span></a>
                    <ul class="sub-menu-list">
                        <li class=" <?php if((ACTION_NAME) == "game_type"): ?>active<?php endif; ?> "><a href="<?php echo U('Tupian/game_type');?>"> 分类列表</a></li>
                        <li class=" <?php if((ACTION_NAME) == "tupian"): ?>active<?php endif; ?> "><a href="<?php echo U('Tupian/tupian');?>"> 图片列表</a></li>
                        <li class=" <?php if((ACTION_NAME) == "ptlogo"): ?>active<?php endif; ?> "><a href="<?php echo U('Tupian/ptlogo');?>"> 平台LOGO</a></li>

                    </ul>
                </li>

                <li class="menu-list <?php if((MODULE_NAME) == "Inchannel"): ?>nav-active<?php endif; ?> ">
					<a href="javascript:;"><i class="fa fa-bar-chart-o"></i> <span>渠道管理</span></a>
                    <ul class="sub-menu-list">
                        <li class=" <?php if((ACTION_NAME) == "invite_channel"): ?>active<?php endif; ?>  "><a href="<?php echo U('Inchannel/invite_channel');?>">渠道列表</a></li>
                    </ul>
                </li>
                <li class="menu-list <?php if((MODULE_NAME) == "Art"): ?>nav-active<?php endif; ?> ">
					<a href="javascript:;"><i class="fa fa-file-text"></i> <span>规则管理</span></a>
                    <ul class="sub-menu-list">
                        <li class=" <?php if((ACTION_NAME) == "sys_art"): ?>active<?php endif; ?>  <?php if((ACTION_NAME) == "sys_art_edit"): ?>active<?php endif; ?> "><a href="<?php echo U('Art/sys_art');?>"> 规则列表</a></li>
                        <li class=" <?php if((ACTION_NAME) == "sys_art_fenlei"): ?>active<?php endif; ?>  <?php if((ACTION_NAME) == "sys_art_fenlei_edit"): ?>active<?php endif; ?> "><a href="<?php echo U('Art/sys_art_fenlei');?>"> 规则分类</a></li>
                    </ul>
				</li>
                <li class="menu-list <?php if((MODULE_NAME) == "News"): ?>nav-active<?php endif; ?> ">
                    <a href="javascript:;"><i class="fa fa-file-text"></i> <span>资讯管理</span></a>
                    <ul class="sub-menu-list">
                        <li class=" <?php if((ACTION_NAME) == "getarticles"): ?>active<?php endif; ?>  <?php if((ACTION_NAME) == "getarticles_edit"): ?>active<?php endif; ?> "><a href="<?php echo U('News/getArticles');?>"> 资讯列表</a></li>
                        <li class=" <?php if((ACTION_NAME) == "getarticleclasses"): ?>active<?php endif; ?>  <?php if((ACTION_NAME) == "getarticleclasses_edit"): ?>active<?php endif; ?> "><a href="<?php echo U('News/getArticleClasses');?>"> 资讯分类</a></li>
                    </ul>
                </li>
				<li class="<?php if((MODULE_NAME) == "Exchange"): ?>active<?php endif; ?> ">
					<a href="<?php echo U('Exchange/exchange',array(txstate=>1));?>"><i class="fa fa-th-list"></i> <span>兑换信息</span></a>
				</li>
				<li class="<?php if((MODULE_NAME) == "Pay"): ?>active<?php endif; ?> ">
					<a href="<?php echo U('Pay/pay');?>"><i class="fa fa-th-list"></i> <span>充值记录</span></a>
				</li>
				<li class="menu-list <?php if((MODULE_NAME) == "Hero"): ?>nav-active<?php endif; ?> ">
					<a href="javascript:;"><i class="fa fa-file-text"></i> <span>英雄猜管理</span></a>
					<ul class="sub-menu-list">
						<li class=" <?php if(($_GET["gid"]) == "1"): ?>active<?php endif; ?> "><a href="<?php echo U('Hero/peilv',array(gid=>1));?>">LOL赔率</a></li>
						<li class=" <?php if(($_GET["gid"]) == "2"): ?>active<?php endif; ?> "><a href="<?php echo U('Hero/peilv',array(gid=>2));?>">Dota2赔率</a></li>
					</ul>
                </li>
                <li class="menu-list <?php if((MODULE_NAME) == "Hero"): ?>nav-active<?php endif; ?> ">
                    <a href="javascript:;"><i class="fa fa-file-text"></i> <span>召唤英雄管理</span></a>
                    <ul class="sub-menu-list">
                        <li class=" <?php if(($_GET["gid"]) == "1"): ?>active<?php endif; ?> "><a href="<?php echo U('Summonhero/optionlist',array(gid=>1));?>">LOL赔率</a></li>
                        <li class=" <?php if(($_GET["gid"]) == "2"): ?>active<?php endif; ?> "><a href="<?php echo U('Summonhero/optionlist',array(gid=>2));?>">Dota2赔率</a></li>
                    </ul>
                </li>
				<li class="menu-list <?php if((MODULE_NAME) == "Chouj"): ?>nav-active<?php endif; ?> ">
					<a href="javascript:;"><i class="fa fa-file-text"></i> <span>LOL抽奖管理</span></a>
					<ul class="sub-menu-list">
						<li class=" <?php if((ACTION_NAME) == "cjjl"): ?>active<?php endif; ?> "><a href="<?php echo U('Chouj/cjjl');?>">抽奖记录</a></li>
						<li class=" <?php if((ACTION_NAME) == "dhjl"): ?>active<?php endif; ?> "><a href="<?php echo U('Chouj/dhjl',array(isuse=>1));?>">兑换记录</a></li>
					</ul>
                </li>
				<li class="menu-list <?php if((MODULE_NAME) == "Roll"): ?>nav-active<?php endif; ?> ">
					<a href="javascript:;"><i class="fa fa-file-text"></i> <span>ROLL活动</span></a>
					<ul class="sub-menu-list">
						<li class=" <?php if((ACTION_NAME) == "active_roll_term"): ?>active<?php endif; ?> "><a href="<?php echo U('Roll/active_roll_term');?>">活动期</a></li>
						<!--<li class=" <?php if((ACTION_NAME) == "active_roll_record"): ?>active<?php endif; ?> "><a href="<?php echo U('Roll/active_roll_record');?>">用户参加记录</a></li>-->
					</ul>
                </li>
				<li class="menu-list <?php if((MODULE_NAME) == "Bigmess"): ?>nav-active<?php endif; ?> ">
					<a href="javascript:;"><i class="fa fa-file-text"></i> <span>通告</span></a>
					<ul class="sub-menu-list">
						<li class=" <?php if((ACTION_NAME) == "bigmess"): ?>active<?php endif; ?> "><a href="<?php echo U('Bigmess/bigmess');?>">紧急通告</a></li>
					</ul>
                </li>
               <li><a href="<?php echo U('User/logout');?>"><i class="fa fa-sign-in"></i> <span>退出</span></a></li>

            </ul>
            <!--sidebar nav end-->

        </div>
    </div>
    <!-- left side end-->
  
    <!-- main content start-->
    <div class="main-content" >

        <!-- header section start-->
        <div class="header-section">

            <!--toggle button start-->
            <a class="toggle-btn"><i class="fa fa-bars"></i></a>
            <!--toggle button end-->

            <!--notification menu start -->
            <div class="menu-right">
                <ul class="notification-menu">
                   <!-- <li>
                        <a href="#" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown">
                            <i class="fa fa-tasks"></i>
                            <span class="badge">8</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-head pull-right">
                            <h5 class="title">You have 8 pending task</h5>
                            <ul class="dropdown-list user-list">
                                <li class="new">
                                    <a href="#">
                                        <div class="task-info">
                                            <div>Database update</div>
                                        </div>
                                        <div class="progress progress-striped">
                                            <div style="width: 40%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="40" role="progressbar" class="progress-bar progress-bar-warning">
                                                <span class="">40%</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="new">
                                    <a href="#">
                                        <div class="task-info">
                                            <div>Dashboard done</div>
                                        </div>
                                        <div class="progress progress-striped">
                                            <div style="width: 90%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="90" role="progressbar" class="progress-bar progress-bar-success">
                                                <span class="">90%</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="task-info">
                                            <div>Web Development</div>
                                        </div>
                                        <div class="progress progress-striped">
                                            <div style="width: 66%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="66" role="progressbar" class="progress-bar progress-bar-info">
                                                <span class="">66% </span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="task-info">
                                            <div>Mobile App</div>
                                        </div>
                                        <div class="progress progress-striped">
                                            <div style="width: 33%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="33" role="progressbar" class="progress-bar progress-bar-danger">
                                                <span class="">33% </span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="task-info">
                                            <div>Issues fixed</div>
                                        </div>
                                        <div class="progress progress-striped">
                                            <div style="width: 80%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="80" role="progressbar" class="progress-bar">
                                                <span class="">80% </span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="new"><a href="__PUBLIC__/">See All Pending Task</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown">
                            <i class="fa fa-envelope-o"></i>
                            <span class="badge">5</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-head pull-right">
                            <h5 class="title">You have 5 Mails </h5>
                            <ul class="dropdown-list normal-list">
                                <li class="new">
                                    <a href="__PUBLIC__/">
                                        <span class="thumb"><img src="__PUBLIC__/images/photos/user1.png" alt="" /></span>
                                        <span class="desc">
                                          <span class="name">John Doe <span class="badge badge-success">new</span></span>
                                          <span class="msg">Lorem ipsum dolor sit amet...</span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="__PUBLIC__/">
                                        <span class="thumb"><img src="__PUBLIC__/images/photos/user2.png" alt="" /></span>
                                        <span class="desc">
                                          <span class="name">Jonathan Smith</span>
                                          <span class="msg">Lorem ipsum dolor sit amet...</span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="__PUBLIC__/">
                                        <span class="thumb"><img src="__PUBLIC__/images/photos/user3.png" alt="" /></span>
                                        <span class="desc">
                                          <span class="name">Jane Doe</span>
                                          <span class="msg">Lorem ipsum dolor sit amet...</span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="__PUBLIC__/">
                                        <span class="thumb"><img src="__PUBLIC__/images/photos/user4.png" alt="" /></span>
                                        <span class="desc">
                                          <span class="name">Mark Henry</span>
                                          <span class="msg">Lorem ipsum dolor sit amet...</span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="__PUBLIC__/">
                                        <span class="thumb"><img src="__PUBLIC__/images/photos/user5.png" alt="" /></span>
                                        <span class="desc">
                                          <span class="name">Jim Doe</span>
                                          <span class="msg">Lorem ipsum dolor sit amet...</span>
                                        </span>
                                    </a>
                                </li>
                                <li class="new"><a href="__PUBLIC__/">Read All Mails</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="badge">4</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-head pull-right">
                            <h5 class="title">Notifications</h5>
                            <ul class="dropdown-list normal-list">
                                <li class="new">
                                    <a href="__PUBLIC__/">
                                        <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                        <span class="name">Server #1 overloaded.  </span>
                                        <em class="small">34 mins</em>
                                    </a>
                                </li>
                                <li class="new">
                                    <a href="__PUBLIC__/">
                                        <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                        <span class="name">Server #3 overloaded.  </span>
                                        <em class="small">1 hrs</em>
                                    </a>
                                </li>
                                <li class="new">
                                    <a href="__PUBLIC__/">
                                        <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                        <span class="name">Server #5 overloaded.  </span>
                                        <em class="small">4 hrs</em>
                                    </a>
                                </li>
                                <li class="new">
                                    <a href="__PUBLIC__/">
                                        <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                        <span class="name">Server #31 overloaded.  </span>
                                        <em class="small">4 hrs</em>
                                    </a>
                                </li>
                                <li class="new"><a href="__PUBLIC__/">See All Notifications</a></li>
                            </ul>
                        </div>
                    </li>-->
                    <li>
                        <a href="javascript:;" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <img src="__PUBLIC__/images/photos/user-avatar.png" alt="" />
                            <?php echo ($admin_info["manage_name"]); ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                            <!--<li><a href="#"><i class="fa fa-user"></i>  我的权限</a></li>
                            <li><a href="#"><i class="fa fa-cog"></i>  设置</a></li>-->
                            <li><a href="javascript:;" onClick="cleancache()"><i class="fa fa-cog"></i>  清理缓存</a></li>
                            <li><a href="<?php echo U('User/logout');?>"><i class="fa fa-sign-out"></i> 退出</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
            <!--notification menu end -->

        </div>
        <!-- header section end-->
  



        <!--body wrapper start-->
        <div class="wrapper">
             <div class="row">
                <div class="col-sm-12">
                <section class="panel">
                <header class="panel-heading">
                    盘分类
                    <!--<span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                        <a href="javascript:;" class="fa fa-times"></a>
					</span>-->
                </header>
                <div class="panel-body">
                <div class="adv-table editable-table ">
                <div class="clearfix">
                    <div class="btn-group">
						<a href="#addmyModal" data-toggle="modal" >
							<button class="btn btn-primary ">
								添加 <i class="fa fa-plus"></i>
							</button>
                        </a>

                    </div>
                    <!--<div class="btn-group pull-right">
                        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="#">Export to Excel</a></li>
                        </ul>
                    </div>-->
                </div>
                <div class="space15"></div>
                <table class="table table-striped table-hover table-bordered" id="editable-sample">
                <thead>
                <tr>
                    <th>序号</th>
                    <th>ID</th>
                    <th>名称</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
					<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$l): $mod = ($i % 2 );++$i;?><tr class="">
							<td><?php echo ($i); ?></td>
							<td><?php echo ($l["id"]); ?></td>
							<td><?php echo ($l["name"]); ?></td>
							<td>
								<a class="tan_edit" data-toggle="modal" href="#bianjibox<?php echo ($l["id"]); ?>">编辑</a>
								|
								<?php if($l['state']==1){ ?>
									<a href="javascript:;" onclick="del_admin(<?php echo ($l["id"]); ?>,'game_fenlei',this,'id')">删除</a>
								<?php }else{ ?>
									<a href="javascript:;" onclick="hui_admin(<?php echo ($l["id"]); ?>,'game_fenlei',this,'id')">恢复</a>
									|
									<a href="javascript:;" class="cddel_admin_btn" onclick="cddel_admin(<?php echo ($l["id"]); ?>,'game_fenlei',this,'id')">彻底删除</a>
								<?php } ?>
							</td>
						</tr>
						<!--编辑框 start-->
						<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="bianjibox<?php echo ($l["id"]); ?>" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
										<h4 class="modal-title">编辑</h4>
									</div>
		
									<div class="modal-body ">
										<form action="<?php echo U('Games/game_fenlei_edit');?>" method="post">
											<input type="hidden" name="id" value="<?php echo ($l["id"]); ?>">
											<div class="form-group">
												<label>名称</label>
												<input type="text" name="name" value="<?php echo ($l["name"]); ?>" class="form-control">
											</div>
											<button type="submit" class="btn btn-primary">保存</button>
										</form>
									</div>
		
								</div>
							</div>
						</div>
						<!--编辑框 end--><?php endforeach; endif; else: echo "" ;endif; ?>

                </tbody>
                </table>
                </div>
                </div>
                </section>
                </div>
                </div>
        </div>
        <!--body wrapper end-->

		<!--添加框 start-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addmyModal" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
						<h4 class="modal-title">添加</h4>
					</div>
					<div class="modal-body">

						<form action="<?php echo U('Games/game_fenlei_edit');?>" method="post">
							<input type="hidden" name="id" value="">
							<div class="form-group">
								<label>名称</label>
								<input type="text" name="name" value="" class="form-control">
							</div>
							<button type="submit" class="btn btn-primary">保存</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!--添加框 end-->

    </div>
    <!-- main content end-->
	
    

</section>
<style type="text/css">
.clear{clear:both;}
.zhaomuzhanyou{width:100%;clear:both;}
.zhaomuzhanyou_li{width:100%;text-align:center;line-height:30px;height:30px;float:left;}
.zhaomuzhanyou_1{width:8%;float:left;border:1px solid #ccc;}
.zhaomuzhanyou_2{width:38%;float:left;border:1px solid #ccc;}
.zhaomuzhanyou_3{width:8%;float:left;border:1px solid #ccc;}
.zhaomuzhanyou_3 img{height:20px;}
.zhaomuzhanyou_4{width:38%;float:left;border:1px solid #ccc;}
.jinbiliushui{width:100%; display:table;}
.jinbiliushui_li{width:100%;text-align:center;line-height:20px;height:auto;float:left;}
.jinbiliushui_1{width:18%;float:left;height:40px;border:1px solid #ccc;overflow:hidden;}
.jinbiliushui_2{width:38%;float:left;height:40px;border:1px solid #ccc;overflow:hidden;}
.jinbiliushui_3{width:18%;float:left;height:40px;border:1px solid #ccc;overflow:hidden;word-break:break-all;}
.jinbiliushui_4{width:18%;float:left;height:40px;border:1px solid #ccc;overflow:hidden;word-break:break-all;}
.jinbixx{width:100%; display:table;}
.jinbixx_li{width:100%;text-align:center;line-height:20px;height:auto;float:left;}
.jinbixx_1{width:18%;float:left;height:40px;border:1px solid #ccc;overflow:hidden;word-break:break-all;}
.jinbixx_2{width:38%;float:left;height:40px;border:1px solid #ccc;overflow:hidden;word-break:break-all;}
.jinbixx_3{width:38%;float:left;height:40px;border:1px solid #ccc;overflow:hidden;word-break:break-all;}
.touzhuxx{width:100%; display:table;}
.touzhuxx_li{width:100%;text-align:center;line-height:20px;height:auto;float:left;}
.touzhuxx_1{width:18%;float:left;height:40px;border:1px solid #ccc;overflow:hidden;word-break:break-all;}
.touzhuxx_2{width:18%;float:left;height:40px;border:1px solid #ccc;overflow:hidden;word-break:break-all;}
.touzhuxx_3{width:18%;float:left;height:40px;border:1px solid #ccc;overflow:hidden;word-break:break-all;}
.touzhuxx_4{width:18%;float:left;height:40px;border:1px solid #ccc;overflow:hidden;word-break:break-all;}
.touzhuxx_5{width:18%;float:left;height:40px;border:1px solid #ccc;overflow:hidden;word-break:break-all;}
/* 翻页样式 */
.page {padding:10px 0;text-align:center;}
.page a{display:inline-block;padding:5px 8px 4px 8px;border:#E4E4E4 1px solid;margin:2px 4px;color:#666;text-decoration:none;cursor:pointer;}
.page a:hover{border:#CF5D02 1px solid;color:#fff;background:#FF7101;}
.page span.current{display:inline-block;padding:5px 8px 4px 8px;color:#fff;background:#FF7101;border:#CF5D02 1px solid;margin:2px 4px;}
.page span.no{border:1px #CF5D02 solid;margin:2px;color:#fff;background:#FF7101;text-decoration:none;}

</style>
<!-- Placed js at the end of the document so the pages load faster -->
<script src="__PUBLIC__/js/jquery-1.10.2.min.js"></script>
<script src="__PUBLIC__/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="__PUBLIC__/js/jquery-migrate-1.2.1.min.js"></script>
<script src="__PUBLIC__/js/bootstrap.min.js"></script>
<script src="__PUBLIC__/js/modernizr.min.js"></script>
<script src="__PUBLIC__/js/jquery.nicescroll.js"></script>

<!--common scripts for all pages-->
<script src="__PUBLIC__/js/scripts.js"></script>

<!--Dashboard Charts-->
<script src="__PUBLIC__/js/dashboard-chart-init.js"></script>

<!--data table-->
<script type="text/javascript" src="__PUBLIC__/js/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/data-tables/DT_bootstrap.js"></script>

<!--pickers plugins-->
<script type="text/javascript" src="__PUBLIC__/js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>

<!--pickers initialization-->
<script src="__PUBLIC__/js/pickers-init.js"></script>

<!--script for editable table-->
<script src="__PUBLIC__/js/editable-table.js"></script>

<!-- END JAVASCRIPTS -->
<script>
    jQuery(document).ready(function() {
        EditableTable.init();
        setInterval('message()',60000);
    });
</script>

<script type="text/javascript">
function message() {
	$.post("<?php echo U('Users/getTxMessage');?>", {}, function(v){
		if(v=='1'){
			// alert("       有用户需要兑换                                                  〜(￣▽￣〜)");
		}else{
			if(v=='2'){
			}	
		}
	});
}

function del_admin(id,sql_m,obj,idname){
	$.post("<?php echo U('User/admin_del');?>", {id:id,sql_m:sql_m,idname:idname}, function(v){
		if(v=='cg'){
			$(obj).text("恢复");
			$(obj).attr("onclick","");
			$(obj).attr("onclick","hui_admin("+id+",'"+sql_m+"',this,'"+idname+"')");
		}else{
			
		}
	});

}
function cddel_admin(id,sql_m,obj,idname){
	$.post("<?php echo U('User/admin_cddel');?>", {id:id,sql_m:sql_m,idname:idname}, function(v){
		if(v=='cg'){
			$(obj).parent().parent().remove();
		}else{
			if(v=='wqx'){
				alert("没有权限");
			}	
		}
	});

}
function hui_admin(id,sql_m,obj,idname){
	$.post("<?php echo U('User/admin_hui');?>", {id:id,sql_m:sql_m,idname:idname}, function(v){
		if(v=='cg'){
			$(obj).text("删除");
			$(obj).attr("onclick","");
			$(obj).attr("onclick","del_admin("+id+",'"+sql_m+"',this,'"+idname+"')");
			$(obj).parent().find(".cddel_admin_btn").remove();
		}else{
			
		}
	});

}
function cleancache(){
	$.post("./cleancache.php", {}, function(v){
		alert(v);
	});

}

</script>

<!--编辑器 js-->
<script src="./Public/js/kindeditor/kindeditor.js"></script>
<script language="javascript">
var k1;
KindEditor.ready(function(K) {
	k1 = K.create('.content_edit', {
		allowFileManager : true
	});
});

</script>



</body>
</html>