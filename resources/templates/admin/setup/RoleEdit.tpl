{{include "../common/header.tpl"}}
<section class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-3 control-label">角色名称</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" placeholder="请输入角色名称" name="roleName" id="roleName" value="{{$roleData['rolename']}}">
					</div>
				</div>
				<div class="line line-dashed line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">角色描述</label>
					<div class="col-sm-9">
						<textarea class="form-control" rows="3" placeholder="请输入角色描述" name="roleDesc" id="roleDesc">{{$roleData['roledesc']}}</textarea>
					</div>
				</div>
				<div class="line line-dashed line-lg pull-in"></div>
 				<div class="form-group">
				    <label for="roleStatus" class="col-sm-3 control-label">角色状态：</label>
					<label class="radio-inline">
						<input type="radio" name="roleStatus" value="0" {{if $roleData['status'] == 0}}checked{{/if}}> 启用
					</label>
					<label class="radio-inline">
						<input type="radio" name="roleStatus"  value="1" {{if $roleData['status'] == 1}}checked{{/if}}> 禁止
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
		              url: '{{$ADMIN_HOST}}/setup/edit/role',
		              dataType: 'json',
		              timeout: 60000, 
		              data:{
		            	  roleId: {{$roleData['roleid']}},
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
{{include "../common/footer.tpl"}}
