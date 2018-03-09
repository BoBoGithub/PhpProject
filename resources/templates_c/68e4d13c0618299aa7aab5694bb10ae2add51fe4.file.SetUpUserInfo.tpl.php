<?php /* Smarty version Smarty-3.1.7, created on 2018-03-05 11:00:09
         compiled from "/var/www/projects/common/conf/../../resources/templates/admin/setup/SetUpUserInfo.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13930800435a98ecdc95f710-85583325%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '68e4d13c0618299aa7aab5694bb10ae2add51fe4' => 
    array (
      0 => '/var/www/projects/common/conf/../../resources/templates/admin/setup/SetUpUserInfo.tpl',
      1 => 1520218805,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13930800435a98ecdc95f710-85583325',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5a98ecdc98963',
  'variables' => 
  array (
    'STATIC_HOST' => 0,
    'roleName' => 0,
    'userName' => 0,
    'realName' => 0,
    'email' => 0,
    'ADMIN_HOST' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a98ecdc98963')) {function content_5a98ecdc98963($_smarty_tpl) {?><link href="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/css/admin/style.css" rel="stylesheet">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h4>个人信息</h4>
                </div>
                <div class="ibox-content">
					<div class="form-horizontal" role="form">
						<div class="form-group">
							<label class="col-sm-2 control-label" style="margin-left:-70px;">所属角色：</label>
							<div class="col-sm-9">
								<input type="text" class="form-control"   value="<?php echo $_smarty_tpl->tpl_vars['roleName']->value;?>
" disabled>
							</div>
						</div>
						<div class="line line-dashed line-lg pull-in"></div>
						<div class="form-group">
							<label class="col-sm-2 control-label" style="margin-left:-70px;">用户名：</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['userName']->value;?>
" disabled>
							</div>
						</div>
						<div class="line line-dashed line-lg pull-in"></div>
						<div class="form-group">
							<label class="col-sm-2 control-label" style="margin-left:-70px;" for="realname">真实姓名：</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" placeholder="请输入真实新姓名" name="realname" id="realname" value="<?php echo $_smarty_tpl->tpl_vars['realName']->value;?>
">
							</div>
						</div>
						<div class="line line-dashed line-lg pull-in"></div>
						<div class="form-group">
							<label class="col-sm-2 control-label" style="margin-left:-70px;" for="email">用户邮箱：</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" placeholder="请输入邮箱地址" name="email" id="email" value="<?php echo $_smarty_tpl->tpl_vars['email']->value;?>
">
							</div>
						</div>
					</div>
					<footer class="panel-footer text-left bg-light lter">
						<button type="button" class="btn btn-success btn-s-xs" id="submit">提交</button>
					</footer> 
			</div>
		</div>
	</div>
</div>
</div>
	
<script>
	$('#submit').click(function(){
		//设置按钮状态
		Common.changeBtnDisable("#submit");
		
		//获取提交参数
		var realName	= $.trim($("#realname").val());
		var email	= $.trim($("#email").val());
		
		//检查真实姓名
		if(realname == ''){
			//提示
			layer.msg("请填写真实姓名！");
			$("#realname").select();
			
			//设置按钮状态
			Common.changeBtnAble("#submit");
			
			return false;	
		}
		
		//检查用户邮箱
		if(email == ""){
			//提示
			layer.msg("请填写邮箱地址！");
			$("#email").select();
			
			//设置按钮状态
			Common.changeBtnAble("#submit");
			
			return false;	
		}
		
		//提交用户修改
		$.ajax({
		  type: 'post',
		  url: '<?php echo $_smarty_tpl->tpl_vars['ADMIN_HOST']->value;?>
/setup/user/edit/info',
		  dataType: 'json',
		  cache:false,
		  timeout: 60000, 
		  data:{realName: realName, email: email},
		  success: function (json) {
			  if(json.errno == 0 && json.updRet){
				  layer.msg("修改成功！");
			  }else{
				  layer.msg(json.errmsg);
			  }
			  
			//设置按钮状态
			Common.changeBtnAble("#submit");
		  }
	  });
	});
</script>
<?php }} ?>