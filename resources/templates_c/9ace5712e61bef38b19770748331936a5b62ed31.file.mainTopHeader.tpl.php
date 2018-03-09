<?php /* Smarty version Smarty-3.1.7, created on 2018-03-09 15:39:09
         compiled from "/var/www/projects/resources/templates/admin/common/mainTopHeader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4918652715a98e5bfd8d452-00625588%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9ace5712e61bef38b19770748331936a5b62ed31' => 
    array (
      0 => '/var/www/projects/resources/templates/admin/common/mainTopHeader.tpl',
      1 => 1520580601,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4918652715a98e5bfd8d452-00625588',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5a98e5bfd93ae',
  'variables' => 
  array (
    'STATIC_HOST' => 0,
    'userBigMenuList' => 0,
    'bigMenu' => 0,
    'themIcon' => 0,
    'userInfo' => 0,
    'ADMIN_HOST' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a98e5bfd93ae')) {function content_5a98e5bfd93ae($_smarty_tpl) {?>
<!--设置菜单图标样式-->
<?php $_smarty_tpl->tpl_vars['themIcon'] = new Smarty_variable(array("fa-th-large","fa-file-o","fa-search-minus","fa-dribbble","fa-windows","fa-apple","fa-arrow-circle-o-up","fa-list-alt","fa-gavel","fa-maxcdn","fa-flag-o","fa-chevron-circle-up","fa-xing","fa-tumblr"), null, 0);?>
<header class="bg-dark dk header navbar navbar-fixed-top-xs">
	<div class="navbar-header aside-md" >
		<a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen,open" data-target="#nav,html"><i class="fa fa-bars"></i></a>
		<a  class="navbar-brand" data-toggle="fullscreen"><img src="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/css/admin/notebook/notebook_files/logo.png" class="m-r-sm">Mi-Gang</a>
		<a class="btn btn-link visible-xs" data-toggle="dropdown"data-target=".nav-user"> <i class="fa fa-cog"></i></a>
	</div>
	<ul class="nav navbar-nav hidden-xs">
		<?php  $_smarty_tpl->tpl_vars['bigMenu'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['bigMenu']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['userBigMenuList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['bigMenu']->key => $_smarty_tpl->tpl_vars['bigMenu']->value){
$_smarty_tpl->tpl_vars['bigMenu']->_loop = true;
?>
			<li>
				<a class="dker" href="javascript:void(0);"  onclick="getSubMenu('<?php echo $_smarty_tpl->tpl_vars['bigMenu']->value['name'];?>
', <?php echo $_smarty_tpl->tpl_vars['bigMenu']->value['id'];?>
);"> <i class="fa <?php echo $_smarty_tpl->tpl_vars['themIcon']->value[$_smarty_tpl->tpl_vars['bigMenu']->value['id']%14];?>
"></i> <span class="font-bold"><?php echo $_smarty_tpl->tpl_vars['bigMenu']->value['name'];?>
</span></a>
			</li>
		<?php } ?>
	</ul>
	<ul class="nav navbar-nav navbar-right m-n hidden-xs nav-user" style="cursor:pointer;">
		<li class="hidden-xs">
			<a class="dropdown-toggle dk" data-toggle="dropdown"> <i class="fa fa-bell"></i> <span class="badge badge-sm up bg-danger m-l-n-sm count" style="display: inline-block;">3</span></a>
			<section class="dropdown-menu aside-xl">
				<section class="panel bg-white">
					<header class="panel-heading b-light bg-light">
						<strong>You have <span class="count" style="display: inline;">3</span> notifications</strong>
					</header>
					<div class="list-group list-group-alt animated fadeInRight">
						<a   class="media list-group-item" style="display: block;">
							<span class="pull-left thumb-sm text-center"><i class="fa fa-envelope-o fa-2x text-success"></i></span>
							<span class="media-body block m-b-none">Sophi sent you a email<br>
							<small class="text-muted">1 minutes ago</small></span>
						</a>
						<a class="media list-group-item">
							<span class="pull-left thumb-sm"><img src="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/css/admin/notebook/notebook_files/avatar.jpg" alt="John said" class="img-circle"></span> 
							<span class="media-body block m-b-none"> Use awesome animate.css<br> <small class="text-muted">10minutes ago</small></span>
						</a> 
						<a  class="media list-group-item">
							<span class="media-body block m-b-none"> 1.0 initial released<br><small class="text-muted">1 hour ago</small></span>
						</a>
					</div>
					<footer class="panel-footer text-sm">
						<a class="pull-right"><i class="fa fa-cog"></i></a> <a href="index.html#notes" data-toggle="class:show animated fadeInRight">See all the notifications</a>
					</footer>
				</section>
			</section>
		</li>
		<li class="dropdown hidden-xs">
			<a class="dropdown-toggle dker" data-toggle="dropdown"><i class="fa fa-fw fa-search"></i></a>
			<section class="dropdown-menu aside-xl animated fadeInUp">
				<section class="panel bg-white">
					<form role="search">
						<div class="form-group wrapper m-b-none">
							<div class="input-group">
								<input type="text" class="form-control" placeholder="Search">
								<span class="input-group-btn">
									<button type="submit" class="btn btn-info btn-icon">
										<i class="fa fa-search"></i>
									</button>
								</span>
							</div>
						</div>
					</form>
				</section>
			</section>
		</li>
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown"> 
				<span class="thumb-sm avatar pull-left"> <img src="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/css/admin/notebook/notebook_files/avatar.jpg"></span> <?php echo $_smarty_tpl->tpl_vars['userInfo']->value['username'];?>
 <b class="caret"></b>
			</a>
			<ul class="dropdown-menu animated fadeInRight">
					<span class="arrow top"></span>
					<li><a>设置</a></li>
					<li><a onclick="javascript:updPwd();">密码修改</a></li>
					<li><a > <span class="badge bg-danger pull-right">3</span> 消息</a></li>
					<li><a>帮助</a></li>
					<li class="divider"></li>
					<li><a href="<?php echo $_smarty_tpl->tpl_vars['ADMIN_HOST']->value;?>
/setup/user/logout">退出</a></li>
				</ul>
		</li>
	</ul>
	<script>
		//修改密码
		function updPwd(){
		 	//弹出层
			layer.open({
			  title: '密码修改',
			  type: 2,
			  area: ['720px', '420px'],
			  content: ['<?php echo $_smarty_tpl->tpl_vars['ADMIN_HOST']->value;?>
/setup/user/edit/pwd', 'no'],
			});
		}
		
	//动态加载子菜单
	function getSubMenu(menuName, pid){
	//提交用户修改
	$.ajax({
              type: 'post',
              url: '<?php echo $_smarty_tpl->tpl_vars['ADMIN_HOST']->value;?>
/setup/get/sub/menu',
              dataType: 'json',
              timeout: 60000, 
              data:{
		pid: pid
              },
              success: function (menuData) {
   		if(menuData.errno == 0 && menuData.subMenuList.length > 0){
   			//拼接菜单追加到左侧栏
   			var html = "";
   			var themIcon = ['fa-dashboard', 'fa-pencil-square', 'fa-columns', 'fa-book', 'fa-camera', 'fa-turkish-lira', 'fa-search-plus', 'fa-plus-square-o'];
   			var themColor= ["bg-info", "bg-danger", "bg-warning", "bg-primary", "bg-dark", "bg-empty"];
   			var subMenuList = menuData.subMenuList;
   			for(var i = 0;i<subMenuList.length;i++){
   				html += '<li>';
   				html +=		 '<a href="javascript:void(0)">';
   				html += 		'<i class="fa '+themIcon[i%8]+' icon"> <b class="'+themColor[i%6]+'"></b></i>';
   				html += 		'<span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i></span> <span>'+subMenuList[i].name+'</span>';
   				html += 	'</a>';
   				html += 	'<ul class="nav lt menuLi">';
   							
   				//设置三级菜单
   				for(var j = 0; j<subMenuList[i].child.length; j++){
   					html += 		'<li><a href="javascript:void(0)"  onclick="Common.loadPage(\''+menuName+'\', \''+subMenuList[i].name+'\',\''+subMenuList[i].child[j].name+'\',\'<?php echo $_smarty_tpl->tpl_vars['ADMIN_HOST']->value;?>
'+subMenuList[i].child[j].url+'\', true)"> <i class="fa fa-angle-right"></i> <span>'+subMenuList[i].child[j].name+'</span></a></li>';
   				}
   				html += 	'</ul>';
   				html += '</li>';
   			}
						
			//追加到页面
			$('#leftMenu').html(html);
   		}else if(menuData.errno != 0){
   			layer.msg(menuData.errmsg);
   		}
             }
          });
	}
</script>
</header>
<?php }} ?>