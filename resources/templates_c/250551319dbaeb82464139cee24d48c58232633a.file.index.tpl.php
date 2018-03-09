<?php /* Smarty version Smarty-3.1.7, created on 2018-03-02 13:48:58
         compiled from "/var/www/projects/common/conf/../../resources/templates/admin/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2859392385a967a0d997421-16099018%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '250551319dbaeb82464139cee24d48c58232633a' => 
    array (
      0 => '/var/www/projects/common/conf/../../resources/templates/admin/index.tpl',
      1 => 1519969736,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2859392385a967a0d997421-16099018',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5a967a0d9cc8a',
  'variables' => 
  array (
    'STATIC_HOST' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a967a0d9cc8a')) {function content_5a967a0d9cc8a($_smarty_tpl) {?><!DOCTYPE html>
<html class="app">
<head>
<title>管理后台</title>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/css/admin/notebook/notebook_files/font.css" type="text/css">
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/css/admin/notebook/notebook_files/app.v1.css" type="text/css">
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/css/admin/numberONe.css" type="text/css">
<!-- base start 重要部分不可删改-->
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/css/admin/notebook/notebook_files/app.v1.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/css/admin/notebook/notebook_files/app.plugin.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/js/admin/jquery/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/js/admin/jquery/jquery-validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/js/admin/jquery/jquery-validation/messages_cn.js"></script>
<!--  <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/js/admin/layer-v1.9.2/layer/layer.js"></script>-->
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/js/admin/layer/layer.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/js/admin/common.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/js/admin/jquery/underscore.js"></script>
<!--<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/js/admin/lyGrid.js"></script>-->
<link href="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/js/admin/date/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/js/admin/date/font-awesome.min.css" rel="stylesheet">
<style type="text/css">
.l_err{
    background: none repeat scroll 0 0 #FFFCC7;
    border: 1px solid #FFC340;
    font-size: 12px;
    padding: 4px 8px;
    width: 200px;
    display: none;
}
.error{
  border: 3px solid #FFCCCC;
}
.form-group{
  padding-left: 15px
}
.left{
	text-align: left;
	padding-left: 10px;
}
.right{
	text-align: right;
}
.hidden-xs{
	display: inherit;
}
.gray-bg {
  background-color: #f3f3f4;
}
</style>
<!-- base end -->
<script type="text/javascript">
//设置Host
var rootPath = "${basePath}";
var resPath		= "${basePath}/resources";
function onloadurl(){
	return true;
	$("[data-url]").each(function () {
		var tb = $(this);
		tb.html(Common.loadingImg());
		tb.load(rootPath+tb.attr("data-url"));
    });
}
layer.config({
    extend: ['default/layer.css'], //加载新皮肤
    fix : false,// 用于设定层是否不随滚动条而滚动，固定在可视区域。
    skin: 'layer-ext-myskin' //一旦设定，所有弹层风格都采用此主题。
});
</script>
<!-- 返回顶部 -->
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/js/admin/scrolltopcontrol.js"></script>
<script type="text/javascript">
	$(function() {
		//修改在手机上点击菜单后菜单不关闭问题
		var winwidth = $("body").width();
		if(winwidth<770){
		  $("#nav ul.lt li").click(function(){
			$("#nav").removeClass("nav-off-screen");
		 });
		}
		var tb = $("#loadhtml");
		tb.html(Common.loadingImg());
		tb.load(rootPath+"/main/welcome");
	});
</script>
</head>
<body>
	<section class="vbox">
		<?php echo $_smarty_tpl->getSubTemplate ('./common/mainTopHeader.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

		<section>
			<section class="hbox stretch">
				<aside class="bg-dark lter aside-md hidden-print" id="nav">
					<?php echo $_smarty_tpl->getSubTemplate ('./common/mainLeftMenu.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

				</aside>
				<section id="content">
					<section id="id_vbox" class="vbox gray-bg">
						<ul class="breadcrumb no-border no-radius b-b b-light" id="topli"></ul>
						<section class="scrollable " style="margin-top: 35px;"><div id="loadhtml" ></div></section>
					</section>
				</section>
				<aside class="bg-light lter b-l aside-md hide" id="notes">
					<div class="wrapper">Notification</div>
				</aside>
			</section>
		</section>
	</section>
	<!-- Bootstrap -->
	<div id="flotTip" style="display:none ; position: absolute;">－－底部－－</div>
	<script style="text/javascript">Common.checkUserStatus();</script>
</body>
</html><?php }} ?>