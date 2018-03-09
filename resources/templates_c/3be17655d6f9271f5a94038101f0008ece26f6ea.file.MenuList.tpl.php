<?php /* Smarty version Smarty-3.1.7, created on 2018-03-07 17:31:57
         compiled from "/var/www/projects/common/conf/../../resources/templates/admin/setup/MenuList.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19028358225a9f46bd6fdf93-67353697%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3be17655d6f9271f5a94038101f0008ece26f6ea' => 
    array (
      0 => '/var/www/projects/common/conf/../../resources/templates/admin/setup/MenuList.tpl',
      1 => 1520415008,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19028358225a9f46bd6fdf93-67353697',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5a9f46bd71e5c',
  'variables' => 
  array (
    'STATIC_HOST' => 0,
    'menuListData' => 0,
    'menuData' => 0,
    'ADMIN_HOST' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a9f46bd71e5c')) {function content_5a9f46bd71e5c($_smarty_tpl) {?><link href="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/css/admin/style.css" rel="stylesheet">
<div class="margin-top-2 alert alert-info" data-ng-show="vm.tableDisplay &amp;&amp; !vm.nopermission"><b bo-text="'msg.cm.lb.tips'|translate">提醒：</b><span bo-text="'msg.sub.contact.tip'|translate">Tip提示信息测试使用</span></div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h4>菜单管理列表</h4>
                    <div style="float:right;margin-top:-30px;">
                    	<button type="button" id="addNewMenu" class="btn btn-primary marR10">新增菜单</button>
                    </div>
                </div>
                <div class="ibox-content">
					<div id="user_list_table_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
						<table class="table table-striped table-bordered table-hover  dataTable no-footer" id="menu_list_table" role="grid" aria-describedby="menu_list_table_info" style="width: 980px;">
						    <thead>
						        <tr role="row">
						        	<th class="text-center sorting_disabled"  style="width: 40px;"><input id="" type="checkbox" class="ipt_check_all"></th>
						            <th class="text-center sorting_asc" style="width: 74px;">菜单ID</th>
						            <th class="text-center sorting" style="width: 650px;">菜单名称</th>
						            <th class="text-center sorting" style="width: 156px;">管理操作</th>
						        </tr>
						    </thead>
						    <tbody>
						    		<?php  $_smarty_tpl->tpl_vars['menuData'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menuData']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['menuListData']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menuData']->key => $_smarty_tpl->tpl_vars['menuData']->value){
$_smarty_tpl->tpl_vars['menuData']->_loop = true;
?>
							    		<tr>
								    		<td class='text-center'><input type='checkbox'></td>
								    		<td><?php echo $_smarty_tpl->tpl_vars['menuData']->value['id'];?>
</td>
								    		<td><?php echo $_smarty_tpl->tpl_vars['menuData']->value['name'];?>
</td>
								    		<td><a onclick="addChildMenu(<?php echo $_smarty_tpl->tpl_vars['menuData']->value['id'];?>
)" style="cursor:pointer;">添加子菜单</a> | <a onclick="editChildMenu(<?php echo $_smarty_tpl->tpl_vars['menuData']->value['id'];?>
)" style="cursor:pointer;">修改</a> | <a onclick="delChildMenu(<?php echo $_smarty_tpl->tpl_vars['menuData']->value['id'];?>
)" style="cursor:pointer;">删除</a></td>
								    	</tr>
								<?php } ?>
						    </tbody>
						</table>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script style="text/javascript">
	//新增菜单
	$('#addNewMenu').click(function(){
		addChildMenu(0);
	});
	
	//刷新当前页面
	function flushPage(){
		var tb = $("#loadhtml");
    		tb.html(Common.loadingImg());
		tb.load("<?php echo $_smarty_tpl->tpl_vars['ADMIN_HOST']->value;?>
/setup/menu/list");
	}
	//新增子菜单
	function addChildMenu(id){
	 	//弹出层
		layer.open({
			  title: '新增菜单',
			  type: 2,
			  area: ['720px', '407px'],
			  content: ['<?php echo $_smarty_tpl->tpl_vars['ADMIN_HOST']->value;?>
/setup/menu/add?menuId='+id, 'no'],
			});
	}
	
	//修改菜单
	function editChildMenu(id){
	 	//弹出层
		layer.open({
			  title: '修改菜单',
			  type: 2,
			  area: ['720px', '407px'],
			  content: ['<?php echo $_smarty_tpl->tpl_vars['ADMIN_HOST']->value;?>
/setup/menu/edit?menuId='+id, 'no'],
		});
	}
	
	//删除菜单
	function delChildMenu(menuId){
	$.ajax({
            type: 'post',
            url: '<?php echo $_smarty_tpl->tpl_vars['ADMIN_HOST']->value;?>
/setup/del/menu',
            dataType: 'json',
            data:{
            	menuId: menuId
            },  
            timeout: 60000, 
            success: function (json) {
            	 if(json.errno == 0 && json.ret){
	            	layer.msg("删除成功！");

			//重新加载页面
	            	flushPage();
		}else{
			layer.msg(json.errmsg);
		}
            }
        });
	}
</script>
<?php }} ?>