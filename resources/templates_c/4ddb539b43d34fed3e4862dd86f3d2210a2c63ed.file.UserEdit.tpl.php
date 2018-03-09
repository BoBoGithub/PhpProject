<?php /* Smarty version Smarty-3.1.7, created on 2018-03-06 09:49:42
         compiled from "/var/www/projects/common/conf/../../resources/templates/admin/setup/UserEdit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:192683955a9d062faf1b31-65853276%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4ddb539b43d34fed3e4862dd86f3d2210a2c63ed' => 
    array (
      0 => '/var/www/projects/common/conf/../../resources/templates/admin/setup/UserEdit.tpl',
      1 => 1520244103,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '192683955a9d062faf1b31-65853276',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5a9d062fb1fde',
  'variables' => 
  array (
    'currRoleData' => 0,
    'roleListData' => 0,
    'roleData' => 0,
    'adminUserInfo' => 0,
    'ADMIN_HOST' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a9d062fb1fde')) {function content_5a9d062fb1fde($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("../common/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<section class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-3 control-label">选择角色</label>
					<div class="col-sm-9">
						<div class="dropdown">  
							<?php if (!empty($_smarty_tpl->tpl_vars['currRoleData']->value)){?>
								<button class="btn btn-default" data-toggle="dropdown" id="dropdownBtn">  
									<span id="roleText"><?php echo $_smarty_tpl->tpl_vars['currRoleData']->value['rolename'];?>
</span>  
									<span class="caret"></span>  
								</button>  
								<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['currRoleData']->value['roleid'];?>
" id="roleId" />
							<?php }else{ ?>
								<button class="btn btn-default" data-toggle="dropdown" id="dropdownBtn">  
									<span id="roleText">选择角色</span>  
									<span class="caret"></span>  
								</button>  
								<input type="hidden" value="0" id="roleId" />
							<?php }?>
								<ul class="dropdown-menu" style="max-height: 285px; overflow-y: auto;cursor:pointer;" id="roleSelect">  
									<li data-original-index="0" <?php if (empty($_smarty_tpl->tpl_vars['currRoleData']->value)){?>class="selected active"<?php }?>>
										<a>选择角色</a>
									</li>
									<?php  $_smarty_tpl->tpl_vars['roleData'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['roleData']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['roleListData']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['roleData']->key => $_smarty_tpl->tpl_vars['roleData']->value){
$_smarty_tpl->tpl_vars['roleData']->_loop = true;
?>
										<li data-original-index="<?php echo $_smarty_tpl->tpl_vars['roleData']->value['roleid'];?>
"><a><?php echo $_smarty_tpl->tpl_vars['roleData']->value['rolename'];?>
</a></li>  
									<?php } ?>
								</ul>  
						</div> 
					</div>
				</div>
				<div class="line line-dashed line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">用户名</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" placeholder="请输入用户名" name="username" id="username" value="<?php echo $_smarty_tpl->tpl_vars['adminUserInfo']->value['username'];?>
">
					</div>
				</div>
				<div class="line line-dashed line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">真实姓名</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" placeholder="请输入真实新姓名" name="realname" id="realname" value="<?php echo $_smarty_tpl->tpl_vars['adminUserInfo']->value['realname'];?>
">
					</div>
				</div>
				<div class="line line-dashed line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">密码</label>
					<div class="col-sm-6">
						<input type="password" class="form-control" placeholder="请输入密码" name="password" id="password">
					</div>
				</div>
				<div class="line line-dashed line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">确认密码</label>
					<div class="col-sm-9">
						<input type="password" class="form-control" placeholder="请输入确认密码" name="cpassword" id="cpassword">
					</div>
				</div>
			</div>
			<footer class="panel-footer text-right bg-light lter">
				<button type="button" class="btn btn-success btn-s-xs" id="submit">提交</button>
				<button type="button" class="btn btn-danger btn-s-xs" id="closeWin">关闭</button>
			</footer> 
		</section>
	<script>
	    //点击上级菜单操作
	    $("#dropdownBtn").click(function(){
		$("#roleSelect").show();
   	    });
		
	    //父级菜单　Select选择框点击
	    $('.dropdown-menu li').click(function(){
	    	//动态赋值
	    	$("#roleText").html($(this).find('a').html());
	    	$("#roleId").val($(this).attr('data-original-index'));
	    	
	    	//设置选装状态
	    	$(this).addClass("selected active").siblings().removeClass("selected active");
	    	
	    	//隐藏弹出窗
	    	$("#roleSelect").hide();
	    });
		 
			//关闭操作
			$('#closeWin').click(function(){
	        		//获取窗口索引
				var index = parent.layer.getFrameIndex(window.name); 
	        	
				//关闭当前窗口
			        parent.layer.close(index);
			});
		
			$('#submit').click(function(){
				//设置按钮状态
				Common.changeBtnDisable("#submit");
				
				//获取提交参数
				var roleId	= $.trim($("#roleId").val());
				var username 	= $.trim($("#username").val());
				var realname	= $.trim($("#realname").val());
				var password	= $.trim($("#password").val());
				var cpassword	= $.trim($("#cpassword").val());
				
				//检查角色
				if(roleId == 0){
					//提示角色有问题
					layer.msg("请选择角色！");
					
					//设置按钮状态
					Common.changeBtnAble("#submit");
					
					return false;
				}
				
				//检查用户名
				if(username == ''){
					//提示密码有问题
					layer.msg("请填写用户名！");
					$("#username").select();
					
					//设置按钮状态
					Common.changeBtnAble("#submit");
					
					return false;	
				}
				
				//检查真实姓名
				if(realname == ''){
					//提示密码有问题
					layer.msg("请填写真实姓名！");
					$("#realname").select();
					
					//设置按钮状态
					Common.changeBtnAble("#submit");
					
					return false;	
				}
				
				//检查密码
				if(password == ''){
					//提示密码有问题
					layer.msg("请填写用户密码！");
					$("#password").select();
					
					//设置按钮状态
					Common.changeBtnAble("#submit");
					
					return false;	
				}
				
				//检查密码和确认密码是否相同
				if(password != cpassword){
					//提示密码有问题
					layer.msg("两次密码不一致！");
					$("#password").select();
					
					//设置按钮状态
					Common.changeBtnAble("#submit");
					
					return false;
				}
				
		//提交用户修改
		$.ajax({
	              type: 'post',
	              url: '<?php echo $_smarty_tpl->tpl_vars['ADMIN_HOST']->value;?>
/setup/user/post/edit',
	              dataType: 'json',
	              cache:false,
	              timeout: 60000, 
	              data:{
	            	  adminUid: <?php echo $_smarty_tpl->tpl_vars['adminUserInfo']->value['uid'];?>
,
	            	  roleId:   roleId,
	            	  userName: username, 
	            	  realName: realname,
	            	  password: password
	              },
	              success: function (json) {
	            	  if(json.errno == 0 && json.ret){
			            	//获取窗口索引
	  				var index = parent.layer.getFrameIndex(window.name); 
	
	  				//重新加载用户列表
	  				parent.getListData(1);
	  				//关闭当前窗口
  	  			        parent.layer.close(index);
	            	  }else{
	            		layer.msg(json.errmsg);
	            		  
	  			//设置按钮状态
	  			Common.changeBtnAble("#submit");
	            	  }
	              }
	          });
	});
</script>
<?php echo $_smarty_tpl->getSubTemplate ("../common/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>