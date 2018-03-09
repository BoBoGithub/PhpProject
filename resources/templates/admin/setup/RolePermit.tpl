{{include "../common/header.tpl"}}
<link href="{{$STATIC_HOST}}/css/admin/jquery.treeTable.css" rel="stylesheet">
<script type="text/javascript" src="{{$STATIC_HOST}}/js/admin/jquery/jquery.treetable.js"></script>
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
						{{foreach from=$menuListData item=menuData}}
							<tr id='node-{{$menuData['id']}}'  {{$menuData['pnode']}}>
								<td style='padding-left:30px;'><input type='checkbox' name='menuId' value='{{$menuData['id']}}' level='{{$menuData['level']}}' {{$menuData['checked']}} onclick='javascript:checknode(this);'> {{$menuData['name']}}</td>
							</tr>
						{{/foreach}}
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
            url: '{{$ADMIN_HOST}}/setup/set/role/permit',
            dataType: 'json',
            data:{
          	roleId: {{$roleId}}, 
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
{{include "../common/footer.tpl"}}
