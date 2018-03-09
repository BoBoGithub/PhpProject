{{include "../common/header.tpl"}}
<section class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-3 control-label">上级菜单</label>
					<div class="col-sm-9">
						<div class="dropdown">  
								<button class="btn btn-default" data-toggle="dropdown" id="dropdownBtn">  
									<span id="menText">{{if empty($menuData['name'])}}作为一级菜单{{else}}{{$menuData['name']}}{{/if}}</span>  
									<span class="caret"></span>  
								</button>  
								<input type="hidden" value="{{if empty($menuData['id'])}}0{{else}}{{$menuData['id']}}{{/if}}" id="parentId" />
								<ul class="dropdown-menu" style="max-height: 285px; overflow-y: auto;cursor:pointer;" id="menuSelect">  
									<li data-original-index="0" {{if $menuId == 0}}class="selected active"{{/if}}><a>作为一级菜单</a></li>
									{{foreach from=$menuListData item=menu}}
										<li data-original-index="{{$menu['id']}}"><a>{{$menu['name']}}</a></li>  
									{{/foreach}}
								</ul>  
						</div> 
					</div>
				</div>
				<div class="line line-dashed line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">菜单名称</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" placeholder="请输入菜单名称" name="menuName" id="menuName">
					</div>
				</div>
				<div class="line line-dashed line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">请求地址</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" placeholder="请输入请求地址" name="requestUrl" id="requestUrl">
					</div>
				</div>
				<div class="line line-dashed line-lg pull-in"></div>
 				<div class="form-group">
				    <label for="menuStatus" class="col-sm-3 control-label">是否显示菜单：</label>
					<label class="radio-inline">
						<input type="radio" name="menuStatus" value="0" checked> 是
					</label>
					<label class="radio-inline">
						<input type="radio" name="menuStatus"  value="1" > 否
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
				var parentId	= $.trim($("#parentId").val());
				var menuName	= $.trim($("#menuName").val());
				var requestUrl	= $.trim($("#requestUrl").val());
				var menuStatus  = $("input[name='menuStatus']:checked").val();
				
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
		              url: '{{$ADMIN_HOST}}/setup/add/menu',
		              dataType: 'json',
		              timeout: 60000, 
		              data:{
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
{{include "../common/footer.tpl"}}
