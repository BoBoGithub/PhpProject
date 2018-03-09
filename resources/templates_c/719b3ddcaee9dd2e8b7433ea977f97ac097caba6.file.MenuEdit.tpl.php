<?php /* Smarty version Smarty-3.1.7, created on 2018-03-07 17:37:02
         compiled from "/var/www/projects/common/conf/../../resources/templates/admin/setup/MenuEdit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1137849395a9f8a4e8a3937-27782710%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '719b3ddcaee9dd2e8b7433ea977f97ac097caba6' => 
    array (
      0 => '/var/www/projects/common/conf/../../resources/templates/admin/setup/MenuEdit.tpl',
      1 => 1520410744,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1137849395a9f8a4e8a3937-27782710',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5a9f8a4e8c4ac',
  'variables' => 
  array (
    'parentMenu' => 0,
    'menuId' => 0,
    'menuListData' => 0,
    'menu' => 0,
    'menuData' => 0,
    'ADMIN_HOST' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a9f8a4e8c4ac')) {function content_5a9f8a4e8c4ac($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("../common/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<section class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-3 control-label">上级菜单</label>
					<div class="col-sm-9">
						<div class="dropdown">  
								<button class="btn btn-default" data-toggle="dropdown" id="dropdownBtn">  
									<span id="menText"><?php echo $_smarty_tpl->tpl_vars['parentMenu']->value['name'];?>
</span>  
									<span class="caret"></span>  
								</button>  
								<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['parentMenu']->value['id'];?>
" id="parentId" />
								<ul class="dropdown-menu" style="max-height: 285px; overflow-y: auto;cursor:pointer;" id="menuSelect">  
									<li data-original-index="0" <?php if ($_smarty_tpl->tpl_vars['menuId']->value==0){?>class="selected active"<?php }?>><a>作为一级菜单</a></li>
									<?php  $_smarty_tpl->tpl_vars['menu'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['menuListData']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menu']->key => $_smarty_tpl->tpl_vars['menu']->value){
$_smarty_tpl->tpl_vars['menu']->_loop = true;
?>
										<li data-original-index="<?php echo $_smarty_tpl->tpl_vars['menu']->value['id'];?>
"><a><?php echo $_smarty_tpl->tpl_vars['menu']->value['name'];?>
</a></li>  
									<?php } ?>
								</ul>  
						</div> 
					</div>
				</div>
				<div class="line line-dashed line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">菜单名称</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" placeholder="请输入菜单名称" name="menuName" id="menuName" value="<?php echo $_smarty_tpl->tpl_vars['menuData']->value['name'];?>
">
					</div>
				</div>
				<div class="line line-dashed line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">请求地址</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" placeholder="请输入请求地址" name="requestUrl" id="requestUrl" value="<?php echo $_smarty_tpl->tpl_vars['menuData']->value['url'];?>
">
					</div>
				</div>
				<div class="line line-dashed line-lg pull-in"></div>
 				<div class="form-group">
				    <label for="menuStatus" class="col-sm-3 control-label">是否显示菜单：</label>
					<label class="radio-inline">
						<input type="radio" name="menuStatus" value="0" <?php if ($_smarty_tpl->tpl_vars['menuData']->value['status']==0){?>checked<?php }?>> 是
					</label>
					<label class="radio-inline">
						<input type="radio" name="menuStatus"  value="1" <?php if ($_smarty_tpl->tpl_vars['menuData']->value['status']==1){?>checked<?php }?>> 否
					</label>
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
				$("#menuSelect").show();
			});
			
		    //父级菜单　Select选择框点击
		    $('.dropdown-menu li').click(function(){
		    	//动态赋值
		    	$("#menText").html($(this).find('a').html());
		    	$("#parentId").val($(this).attr('data-original-index'));
		    	
		    	//设置选装状态
		    	$(this).addClass("selected active").siblings().removeClass("selected active");
		    	
		    	//隐藏弹出窗
		    	$("#menuSelect").hide();
		    });
		
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
				var parentId		= $.trim($("#parentId").val());
				var menuName = $.trim($("#menuName").val());
				var requestUrl	= $.trim($("#requestUrl").val());
				var menuStatus = $("input[name='menuStatus']:checked").val();
				
				//检查菜单名称
				if(menuName == ''){
					//提示菜单名称不能为空
					layer.msg("菜单名称不能为空！");
					$("#menuName").select();
					
					//设置按钮状态
					Common.changeBtnAble("#submit");
					
					return false;
				}
				
				//检查菜单请求地址
				if(requestUrl == ''){
					//提示菜单请求地址不能为空
					layer.msg("请求地址不能为空！");
					$("#requestUrl").select();
					
					//设置按钮状态
					Common.changeBtnAble("#submit");
					
					return false;
				}
				
			//提交新增数据
			$.ajax({
			      type: 'post',
		              url: '<?php echo $_smarty_tpl->tpl_vars['ADMIN_HOST']->value;?>
/setup/edit/menu',
		              dataType: 'json',
		              timeout: 60000, 
		              data:{
		            	  menuId: <?php echo $_smarty_tpl->tpl_vars['menuData']->value['id'];?>
,
		            	  parentId: parentId,
		            	  menuName: menuName,
		            	  requestUrl: requestUrl,
		            	  menuStatus: menuStatus
		              },
		              success: function (json) {
		            	  if(json.errno == 0 && json.ret){
			            	//获取窗口索引
					var index = parent.layer.getFrameIndex(window.name); 
				  				
			            	//刷新菜单列表
			            	parent.flushPage();
				            	
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