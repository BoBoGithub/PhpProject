<?php /* Smarty version Smarty-3.1.7, created on 2018-03-09 15:26:13
         compiled from "/var/www/projects/resources/templates/admin/common/mainLeftMenu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11984879625a98e5ca99a3e3-82757678%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '76cdef01f5db9f5727ab3d1d352764dd2db693a1' => 
    array (
      0 => '/var/www/projects/resources/templates/admin/common/mainLeftMenu.tpl',
      1 => 1520580298,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11984879625a98e5ca99a3e3-82757678',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5a98e5ca99c2a',
  'variables' => 
  array (
    'ADMIN_HOST' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a98e5ca99c2a')) {function content_5a98e5ca99c2a($_smarty_tpl) {?><section class="vbox">
	<section class="w-f scrollable">
		<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
			<nav class="nav-primary hidden-xs">
					<ul class="nav" id="leftMenu">	
							<li class="active">
								<a href="javascript:void(0)" class="active">    
									<i class="fa fa-turkish-lira icon"> <b class="bg-info"></b></i>
									<span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i></span> <span>个人信息管理</span>
								</a>
								<ul class="nav lt" style="display:block;">
										<li><a href="javascript:void(0)"  onclick="Common.loadPage('基础信息','个人信息管理','修改个人信息','<?php echo $_smarty_tpl->tpl_vars['ADMIN_HOST']->value;?>
/setup/user/info', true)"> <i class="fa fa-angle-right"></i><span>修改个人信息</span></a></li>
								</ul>
							</li>
					</ul>
				</ul>
			</nav>
		</div>
	</section>
	<footer class="footer lt hidden-xs b-t b-dark">
		<div id="chat" class="dropup">
			<section class="dropdown-menu on aside-md m-l-n">
				<section class="panel bg-white">
					<header class="panel-heading b-b b-light">Active chats</header>
					<div class="panel-body animated fadeInRight">
						<p class="text-sm">No active chats.</p>
						<p><a href="#" class="btn btn-sm btn-default">Start a chat</a></p>
					</div>
				</section>
			</section>
		</div>
		<div id="invite" class="dropup">
			<section class="dropdown-menu on aside-md m-l-n">
				<section class="panel bg-white">
					<header class="panel-heading b-b b-light">
						John <i class="fa fa-circle text-success"></i>
					</header>
					<div class="panel-body animated fadeInRight">
						<p class="text-sm">No contacts in your lists.</p>
						<p><a href="#" class="btn btn-sm btn-facebook"><i class="fa fa-fw fa-facebook"></i> Invite from Facebook</a></p>
					</div>
				</section>
			</section>
		</div>
		<a href="#nav" data-toggle="class:nav-xs" class="pull-right btn btn-sm btn-dark btn-icon"> 
			<i class="fa fa-angle-left text"></i> <i class="fa fa-angle-right text-active"></i>
		</a>
		<div class="btn-group hidden-nav-xs">
			<button type="button" title="Chats" class="btn btn-icon btn-sm btn-dark" data-toggle="dropdown" data-target="#chat">
				<i class="fa fa-comment-o"></i>
			</button>
			<button type="button" title="Contacts" class="btn btn-icon btn-sm btn-dark" data-toggle="dropdown" data-target="#invite">
				<i class="fa fa-facebook"></i>
			</button>
		</div>
	</footer>
</section>
<?php }} ?>