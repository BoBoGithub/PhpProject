<?php /* Smarty version Smarty-3.1.7, created on 2018-03-13 10:16:36
         compiled from "/var/www/projects/common/conf/../../resources/templates/admin/data/HouseAjkList.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7540028985aa5eed4ebb0c7-45884820%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'db258c5f9d09b80f4f0933c895d360695efeeef1' => 
    array (
      0 => '/var/www/projects/common/conf/../../resources/templates/admin/data/HouseAjkList.tpl',
      1 => 1520844668,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7540028985aa5eed4ebb0c7-45884820',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5aa5eed4edb6a',
  'variables' => 
  array (
    'STATIC_HOST' => 0,
    'ADMIN_HOST' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5aa5eed4edb6a')) {function content_5aa5eed4edb6a($_smarty_tpl) {?><link href="<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/css/admin/style.css" rel="stylesheet">
<div class="margin-top-2 alert alert-info" data-ng-show="vm.tableDisplay &amp;&amp; !vm.nopermission"><b bo-text="'msg.cm.lb.tips'|translate">提醒：</b><span bo-text="'msg.sub.contact.tip'|translate">当前数据是脚本采集过来的, 只作为参考使用</span></div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h4>房屋数据列表</h4>
                </div>
                <div class="ibox-content">
                	<div class="form-horizontal" role="form">
           		 	 <div class="form-group">
						    <label for="accountName" class="col-sm-2 control-label">小区名称：</label>
						    <div class="col-sm-1">
						      <input type="text" class="form-control" style="width: 150px;" id="siteName" placeholder="请输入小区名">
						    </div>
						    
						    <label for="accountName" class="col-sm-2 control-label">房屋面积：</label>
						    <div class="col-sm-1">
						      <input type="text" class="form-control" style="width: 150px;" id="totalSize" placeholder="请输入房屋总">
						    </div>

						    <label for="accountName" class="col-sm-2 control-label">业务员：</label>
						    <div class="col-sm-1">
						      <input type="text" class="form-control" style="width: 150px;" id="userName" placeholder="请输入业务员姓名">
						    </div>

						    <label for="mobile" class="col-sm-1 control-label"></label>
						    <div class="col-sm-1">
						      <a href="javascript:void(0)" class="btn btn-default" id="search">查询</a> 
						    </div>
					  </div>
					  </div>
                
					<div id="user_list_table_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
						<table class="table table-striped table-bordered table-hover  dataTable no-footer" id="role_list_table" style="width: 1000px;">
						    <thead>
						        <tr role="row">
						      	    <th class="text-center sorting_disabled"  style="width: 20px;"><input id="" type="checkbox" class="ipt_check_all"></th>
						            <th class="text-center sorting_asc"  style="width: 64px;">房屋编号</th>
						            <th class="text-center sorting"  style="width: 110px;">小区名称</th>
						            <th class="text-center sorting"  style="width: 113px;">房屋户型</th>
						            <th class="text-center sorting"  style="width: 150px;">总面积/总报价</th>
						            <th class="text-center sorting"  style="width: 106px;">装修状态</th>
						            <th class="text-center sorting"  style="width: 86px;">业务员</th>
						            <th class="text-center sorting"  style="width: 116px;">所属门店</th>
						            <th class="text-center sorting"  style="width: 116px;">发布时间</th>
						        </tr>
						    </thead>
						    <tbody id="tableList"></tbody>
						</table>
						<section class="scrollable" style="margin-top: 25px;"><div id="subLoadhtml"></div></section>
						<div id="pagerList"></div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script style="text/javascript">
	//查询数据
	$("#search").click(function(){
		//检查查询条件
		var searchStr = $.trim($("#siteName").val());
		if(searchStr.length == 0){
			//提示信息并返回
			//$("#accountName").select();
			//layer.msg('请输入正确的用户名！', function(){});
			//return false;
		}
		
		//获取数据
		getListData(1);
	});
	
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
				html += "<tr class='text-center'><td><input type='checkbox'></td><td>"+data[item].house_num+"</td>"+"<td>"+data[item].site_name+"</td>"+"<td>"+data[item].house_type+"</td>"+"<td>"+data[item].total_size+"/"+data[item].total_price+"</td>"+"<td>"+data[item].decorate_state+"</td><td>"+data[item].user_name+"</td><td>"+data[item].user_gate+"</td><td>"+data[item].send_time+"</td>";
			html += "</tr>";
			});
		}else{
			html += "<tr><td colspan='8' style='text-align:center;'>暂无数据！</td></tr>";
		}
		
		return html;
	}
	
	//加载列表数据
	var page = 1;
	getListData(1);
	function getListData(pageNum){
		//加载图标
		$("#subLoadhtml").html("<div style='text-align:center'><img src='<?php echo $_smarty_tpl->tpl_vars['STATIC_HOST']->value;?>
/images/admin/loading.gif'/></div>");
		
		//设置查询页码
		 page = pageNum;
		var pageSize = 9;
		
		//加载数据
		  $.ajax({
	              type: 'post',
	              url: '<?php echo $_smarty_tpl->tpl_vars['ADMIN_HOST']->value;?>
/data/get/ajk/house',
	              dataType: 'json',
	              timeout: 60000,
	              data:{
	            	  page: page, 
	            	  pageSize: pageSize,
	            	  siteName:$.trim($("#siteName").val()),
	            	  userName:$.trim($("#userName").val()),
	            	  totalSize:$.trim($("#totalSize").val())
	              },  
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
</script>
<?php }} ?>