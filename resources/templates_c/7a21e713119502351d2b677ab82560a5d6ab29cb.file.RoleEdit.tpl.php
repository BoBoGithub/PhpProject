<?php /* Smarty version Smarty-3.1.7, created on 2018-03-09 15:00:48
         compiled from "/var/www/projects/common/conf/../../resources/templates/admin/setup/RoleEdit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13407793045a9e3bdba39003-83911737%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7a21e713119502351d2b677ab82560a5d6ab29cb' => 
    array (
      0 => '/var/www/projects/common/conf/../../resources/templates/admin/setup/RoleEdit.tpl',
      1 => 1520326604,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13407793045a9e3bdba39003-83911737',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5a9e3bdba6212',
  'variables' => 
  array (
    'roleData' => 0,
    'ADMIN_HOST' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a9e3bdba6212')) {function content_5a9e3bdba6212($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("../common/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<section class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-3 control-label">角色名称</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" placeholder="请输入角色名称" name="roleName" id="roleName" value="<?php echo $_smarty_tpl->tpl_vars['roleData']->value['rolename'];?>
">
					</div>
				</div>
				<div class="line line-dashed line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">角色描述</label>
					<div class="col-sm-9">
						<textarea class="form-control" rows="3" placeholder="请输入角色描述" name="roleDesc" id="roleDesc"><?php echo $_smarty_tpl->tpl_vars['roleData']->value['roledesc'];?>
</textarea>
					</div>
				</div>
				<div class="line line-dashed line-lg pull-in"></div>
 				<div class="form-group">
				    <label for="roleStatus" class="col-sm-3 control-label">角色状态：</label>
					<label class="radio-inline">
						<input type="radio" name="roleStatus" value="0" <?php if ($_smarty_tpl->tpl_vars['roleData']->value['status']==0){?>checked<?php }?>> 启用
					</label>
					<label class="radio-inline">
						<input type="radio" name="roleStatus"  value="1" <?php if ($_smarty_tpl->tpl_vars['roleData']->value['status']==1){?>checked<?php }?>> 禁止
					</label>
					<label class="radio-inline">
						<input type="radio" name="roleStatus" value="-1"> 删除
					</label>
				</div>
			</div>
			<footer class="panel-footer text-right bg-light lter">
				<button type="button" class="btn btn-success btn-s-xs" id="submit">保存</button>
				<button type="button" class="btn btn-danger btn-s-xs" id="closeWin">取消</button>
			</footer> 
		</section>
		<script>
			//关闭操作
			$('#closeWin').click(function(){
            			//获取窗口索引
  				var index = parent.layer.getFrameIndex(window.name); 
            	
				//关闭当前窗口
  			    	parent.layer.close(index);
			});
		
			//提交操作
			$('#submit').click(function(){
				//设置按钮状态
				Common.changeBtnDisable("#submit");
				
				//获取提交参数
				var roleName = $.trim($("#roleName").val());
				var roleDesc	= $.trim($("#roleDesc").val());
				var roleStatus = $("input[name='roleStatus']:checked").val();
				
				//检查角色名称
				if(roleName == ''){
					//提示角色名称不能为空
					layer.msg("角色名称不能为空！");
					$("#roleName").select();
					//设置按钮状态
					Common.changeBtnAble("#submit");
					
					return false;
				}
				
				//检查角色描述
				if(roleDesc == ''){
					//提示角色描述不能为空
					layer.msg("角色描述不能为空！");
					$("#roleDesc").select();
					
					//设置按钮状态
					Common.changeBtnAble("#submit");
					
					return false;
				}
				
			//提交新增数据
			$.ajax({
			      type: 'post',
		              url: '<?php echo $_smarty_tpl->tpl_vars['ADMIN_HOST']->value;?>
/setup/edit/role',
		              dataType: 'json',
		              timeout: 60000, 
		              data:{
		            	  roleId: <?php echo $_smarty_tpl->tpl_vars['roleData']->value['roleid'];?>
,
		            	  roleName: roleName,
		            	  roleDesc: roleDesc,
		            	  roleStatus: roleStatus,
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
		            		//提示信息
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