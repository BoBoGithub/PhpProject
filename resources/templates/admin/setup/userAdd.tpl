{{include "../common/header.tpl"}}
<section class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-3 control-label">选择角色</label>
					<div class="col-sm-9">
						<div class="dropdown">  
								<button class="btn btn-default" data-toggle="dropdown" id="dropdownBtn">  
									<span id="roleText">选择角色</span>  
									<span class="caret"></span>  
								</button>  
								<input type="hidden" value="0" id="roleId" />
								<ul class="dropdown-menu" style="max-height: 285px; overflow-y: auto;cursor:pointer;" id="roleSelect">  
									<li data-original-index="0" class="selected active"><a>选择角色</a></li>
									{{foreach from=$userRoleData item=roleData}}
										<li data-original-index="{{$roleData['roleid']}}"><a>{{$roleData['rolename']}}</a></li>  
									{{/foreach}}
								</ul>  
						</div> 
					</div>
				</div>
				<div class="line line-dashed line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">用户名</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" placeholder="请输入用户名" name="username" id="username">
					</div>
				</div>
				<div class="line line-dashed line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">真实姓名</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" placeholder="请输入真实新姓名" name="realname" id="realname">
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
		var roleId		 = $.trim($("#roleId").val());
		var username = $.trim($("#username").val());
		var realname	= $.trim($("#realname").val());
		var password = $.trim($("#password").val());
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
				
		//提交新增数据
		$.ajax({
	              type: 'post',
	              url: '{{$ADMIN_HOST}}/setup/user/post/add',
	              dataType: 'json',
	              cache:false,
	              timeout: 60000, 
	              data:{
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
{{include "../common/footer.tpl"}}
