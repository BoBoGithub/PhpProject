<?php /* Smarty version Smarty-3.1.7, created on 2018-03-07 09:43:58
         compiled from "/var/www/projects/common/conf/../../resources/templates/admin/setup/RoleUser.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12334967165a9e50ae36f398-12393419%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f8ccf821f2da300c299c2219f5ab9703c9c55f6e' => 
    array (
      0 => '/var/www/projects/common/conf/../../resources/templates/admin/setup/RoleUser.tpl',
      1 => 1520329743,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12334967165a9e50ae36f398-12393419',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5a9e50ae3b1b9',
  'variables' => 
  array (
    'STATIC_HOST' => 0,
    'ADMIN_HOST' => 0,
    'roleId' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a9e50ae3b1b9')) {function content_5a9e50ae3b1b9($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("../common/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<section class="panel panel-default">
			<div class="panel-body">
				<table width="700px" cellspacing="0" id="dnd-example" class="table table-striped table-bordered table-hover  dataTable no-footer">
					<thead>
				        <tr role="row">
				            <th class="text-center sorting_asc" style="width: 144px;">用户UID</th>
				            <th class="text-center sorting" style="width: 110px;">用户名</th>
				            <th class="text-center sorting" style="width: 133px;">真实姓名</th>
				        </tr>
				    </thead>
					<tbody id="tableList"></tbody>
				</table>
				<section class="scrollable" style="margin-top: 25px;"><div id="subLoadhtml"></div></section>
				<div style="float:right;" id="pagerList"></div>
				</div>
			</div>
			<div class="line line-dashed line-lg pull-in"></div>
			
			<footer class="panel-footer text-right bg-light lter">
				<button type="button" class="btn btn-danger btn-s-xs" id="closeWin">关闭</button>
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

	//加载列表数据
	getListData(1);
	function getListData(pageNum){
		//加载图标
		$('#tableList').html("");
		$("#pagerList").html("");
		$("#subLoadhtml").show().html("<div style='text-align:center'><img src='<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/images/admin/loading.gif'/></div>");

		//设置查询页码
		var page = pageNum;
		var pageSize = 5;
		//加载数据
		  $.ajax({
	              type: 'post',
	              url: '<?php echo $_smarty_tpl->tpl_vars['ADMIN_HOST']->value;?>
/setup/role/user/list',
	              dataType: 'json',
	              cache:false,
	              data:{
	            	  roleId: <?php echo $_smarty_tpl->tpl_vars['roleId']->value;?>
,
	            	  page: page, 
	            	  pageSize: pageSize,
	              },  
	              timeout: 60000, 
	              success: function (json) {
	            	  $('#subLoadhtml').hide();
			  if(json.errno == 0){
				appendHtml(json.list, page, Math.ceil(json.total/pageSize));
			  }else{
				layer.msg(json.errmsg);
			  }
	              }
	          });
	}
	
	//页面追加数据
	function appendHtml(data, page, total){
		//提取列表数据
		var  html = dealUserList(data);
		
		//提取分页数据
		var pager = Common.createPagerHtml(page, total);
		
		//追加用户数据
		$('#tableList').html(html);
		
		//追加分页数据
		$('#pagerList').html(pager);
	}
	
	//处理列表数据
	function dealUserList(data){
		var  html = "";
		if(data.length > 0){
			$.each(data, function(item){
				html += "<tr role='row'>"+
										"<td class='text-center'>"+data[item].uid+"</td>"+
										"<td class='text-center'>"+data[item].username+"</td>"+
										"<td class='text-center'>"+data[item].realname+"</td>"+
								"</tr>";
			});
		}else{
			html += "<tr><td colspan='3' style='text-align:center;'>暂无数据！</td></tr>";
		}
		
		return html;
	}
	
</script>
<?php echo $_smarty_tpl->getSubTemplate ("../common/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>