<?php /* Smarty version Smarty-3.1.7, created on 2018-03-08 16:37:36
         compiled from "/var/www/projects/common/conf/../../resources/templates/admin/setup/RolePermit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:21449264435aa099eb272fe1-36333372%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c8aca2850e98ecfde66660308627c69560a2b68d' => 
    array (
      0 => '/var/www/projects/common/conf/../../resources/templates/admin/setup/RolePermit.tpl',
      1 => 1520491892,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21449264435aa099eb272fe1-36333372',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5aa099eb2a219',
  'variables' => 
  array (
    'STATIC_HOST' => 0,
    'menuListData' => 0,
    'menuData' => 0,
    'ADMIN_HOST' => 0,
    'roleId' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5aa099eb2a219')) {function content_5aa099eb2a219($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("../common/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<link href="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/css/admin/jquery.treeTable.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/js/admin/jquery/jquery.treetable.js"></script>
<script type="text/javascript">
//处理table
$(document).ready(function() {
   $("#dnd-example").treeTable({
   	indent: 20
   });
});

  function checknode(obj){
      //更新自身的是否选中状态
      $(obj).attr("checked", ($(obj).attr("checked") == 'checked' ? 'checked' : false));
      var chk = $("input[type='checkbox']");
      var count = chk.length;
      var num = chk.index(obj);
      var level_top = level_bottom =  chk.eq(num).attr('level')
      for (var i=num; i>=0; i--){
              var le = chk.eq(i).attr('level');
              if(eval(le) < eval(level_top)) {
                  chk.eq(i).attr("checked",'checked');
                  var level_top = level_top-1;
              }
      }
      for (var j=num+1; j<count; j++){
              var le = chk.eq(j).attr('level');
              if(chk.eq(num).attr("checked")=='checked') {
                  if(eval(le) > eval(level_bottom)) chk.eq(j).attr("checked",'checked');
                  else if(eval(le) == eval(level_bottom)) break;
              }else {
                  if(eval(le) > eval(level_bottom)) chk.eq(j).attr("checked",false);
                  else if(eval(le) == eval(level_bottom)) break;
              }
      }
  }
</script>

<section class="panel panel-default">
			<div class="panel-body">
				<table width="100%" cellspacing="0" id="dnd-example" class="table table-striped table-bordered table-hover  dataTable no-footer">
					<tbody>
						<?php  $_smarty_tpl->tpl_vars['menuData'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menuData']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['menuListData']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menuData']->key => $_smarty_tpl->tpl_vars['menuData']->value){
$_smarty_tpl->tpl_vars['menuData']->_loop = true;
?>
							<tr id='node-<?php echo $_smarty_tpl->tpl_vars['menuData']->value['id'];?>
'  <?php echo $_smarty_tpl->tpl_vars['menuData']->value['pnode'];?>
>
								<td style='padding-left:30px;'><input type='checkbox' name='menuId' value='<?php echo $_smarty_tpl->tpl_vars['menuData']->value['id'];?>
' level='<?php echo $_smarty_tpl->tpl_vars['menuData']->value['level'];?>
' <?php echo $_smarty_tpl->tpl_vars['menuData']->value['checked'];?>
 onclick='javascript:checknode(this);'> <?php echo $_smarty_tpl->tpl_vars['menuData']->value['name'];?>
</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="line line-dashed line-lg pull-in"></div>
			
			<footer class="panel-footer text-right bg-light lter">
				<button type="button" class="btn btn-success btn-s-xs" id="submit">提交</button>
				<button type="button" class="btn btn-danger btn-s-xs" id="closeWin">关闭</button>
			</footer> 
		</section>

<script type="text/javascript">
	//提交操作
	$("#submit").click(function(){
		//设置按钮状态
		Common.changeBtnDisable("#submit");
		
		//提取选中的checkbox
		var menuIds = [];
		$("[name='menuId'][checked]").each(function(){
			menuIds.push($(this).val());
		});

	//提交设置角色权限
	$.ajax({
            type: 'post',
            url: '<?php echo $_smarty_tpl->tpl_vars['ADMIN_HOST']->value;?>
/setup/set/role/permit',
            dataType: 'json',
            data:{
          	roleId: <?php echo $_smarty_tpl->tpl_vars['roleId']->value;?>
, 
          	menuIds: menuIds
            },  
            timeout: 60000, 
            success: function (json) {
		if(json.errno == 0 && json.ret){
			//获取窗口索引
		  	var index = parent.layer.getFrameIndex(window.name); 
						
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
	//关闭操作
	$('#closeWin').click(function(){
		//获取窗口索引
		var index = parent.layer.getFrameIndex(window.name); 
		
		//关闭当前窗口
		parent.layer.close(index);
	});
</script>
<?php echo $_smarty_tpl->getSubTemplate ("../common/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>