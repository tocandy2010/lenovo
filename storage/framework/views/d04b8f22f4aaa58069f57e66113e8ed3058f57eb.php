<?php /* D:\xampp\htdocs\lenovo\resources\views/admin/admin/index.blade.php */ ?>
<?php $__env->startSection('title'); ?>
管理員首頁
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>

<div>
	<a href = "javascript:;" data-toggle="modal" data-target="#add">新增管理人員</a>
	<span>共<span id = 'count'><?php echo e($count); ?></span>位</span>
	</div>
<table border = "1">
	<tr>
		<th>id</th>
		<th>姓名</th>
		<th>密碼</th>
		<th>建立時間</th>
		<th>最後登入時間</th>
		<th>登入次數</th>
		<th>狀態</th>
		<th>操作</th>
	</tr>
	<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<tr id = '<?php echo e($v->id); ?>'>
		<td><?php echo e($v->id); ?></td>
		<td><?php echo e($v->name); ?></td>
		<td><?php echo e(substr($v->pass,0,15)."..."); ?></td>
		<td><?php echo e($v->time); ?></td>
		<td><?php echo e($v->lasttime); ?></td>
		<td><?php echo e($v->count); ?></td>
		<?php if(!$v->status): ?>
		<td><span class = 'btn btn-success' onclick = 'status(this,<?php echo e($v->id); ?>,0)'>正常</span></td>
		<?php else: ?>
		<td><span class = 'btn btn-danger' onclick = 'status(this,<?php echo e($v->id); ?>,1)'>停權</span></td>
		<?php endif; ?>
		<td>
		<a href = "javascript:;" data-toggle="modal" data-target="#edit" on onclick = 'edit(<?php echo e($v->id); ?>)'>修改</a>   
		<a href = "javascript:;" onclick = "deletes(this,<?php echo e($v->id); ?>,<?php echo e("'".$v->name."'"); ?>)">刪除</a>
		</td>
	</tr>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>	
</table>

<div>
<?php echo e($data->links()); ?>

</div>

<!-- 模態框（新增管理員） -->
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="addLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" 
						aria-hidden="true">×
				</button>
				<h4 class="modal-title" id="addLabel">
					新增管理員   <!-- 模態框標題 -->
				</h4>
			</div>
			<form  action = "store" method = "post" onsubmit = 'return false' id = 'formAdd' >
			<div class="modal-body">
			 <!-- 模態框內容 -->	
				<div>
					<label>帳號</label>
					<input type = "text" name = 'name'>
					<div id = 'nameinfo'>
						
					</div>
				</div>
				<div>
					<label>密碼</label>
					<input type = "password" name = 'pass'>
					<div id = 'passinfo'>
					</div>
				</div>	
				<div>
					<label>確認密碼</label>
					<input type = "password" name = 'repass'>
				</div>	
				<div>
					<label>狀態</label>
					開啟<input type = "radio" name = 'status' checked = 'true' value = '0' >
					停止<input type = "radio" name = 'status' value = '1'>
				</div>				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" 
						data-dismiss="modal" id = 'addcancel' >取消
				</button>
				<input type = 'reset' value = "重置"  class="btn btn-primary" id = 'addreset' style = 'display:none'>
				<input type = 'submit' value = "確認送出"  class="btn btn-primary" onclick = 'add()'>
			</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- 模態框止（新增管理員） -->

<!-- 模態框（管理員修改） -->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" 
						aria-hidden="true">×
				</button>
				<h4 class="modal-title" id="editLabel">
					修改管理員   <!-- 模態框標題 -->
				</h4>
			</div>
			<form  action = "edit/" method = "post" onsubmit = 'return false' id = 'formEdit' >
			
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- 模態框止（管理員修改） -->

<script>
	function createxhr(){
		var xhr = null;
		
		if(window.XMLHttpRequest){
			xhr = new XMLHttpRequest();
			if(window.ActiveXObject){
				xhr = new ActivXobject('Mircosoft.HttpXML');
			}
		}
		return xhr;
	}


	function add(){
		//ajax無刷新模態框添加管理員
		var nameinfo = document.getElementById('nameinfo');
		var passinfo = document.getElementById('passinfo');
		nameinfo.innerHTML = "";
		passinfo.innerHTML = "";
		var xhr = createxhr();
		var formAdd = document.getElementById('formAdd');
		var fd = new FormData(formAdd);
		fd.append("_token","<?php echo e(csrf_token()); ?>");
		xhr.open('post','admin',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText==="0"){
					//成功關閉視窗
					/*document.getElementById('addreset').click();
					document.getElementById('addcancel').click();
					var count = document.getElementById('count');
					count.innerHTML = (Number(count.innerHTML)+1);*/
					window.location.reload();  //刷新頁面
				}else{ 
					//提示錯誤訊息
					if(xhr.responseText){
						var errors = JSON.parse(xhr.responseText);
						var nameinfo = document.getElementById('nameinfo');
						var passinfo = document.getElementById('passinfo');
						if(errors.name){							
							nameinfo.innerHTML = "<span style='color:red'>"+errors.name+"</span>";
						}
						if(errors.pass){							
							passinfo.innerHTML = "<span style='color:red'>"+errors.pass+"</span>";
						}						
					}else{
						alert('管理員新增失敗');
					}
				}

			}
		}
		
		xhr.send(fd);
	}
	
	function deletes(obj,id,name){
		//刪除管理人員
		if(confirm('確認刪除帳號:  '+name+' 嗎?')){
			var xhr = createxhr();
			var fd = new FormData();
			fd.append("_token",'<?php echo e(csrf_token()); ?>');
			fd.append('_method','delete');
			xhr.open('post','admin/'+id,true);
			xhr.onreadystatechange = function (){
				if(this.readyState === 4 && this.status === 200){
					if(xhr.responseText==="1"){
						var idd = document.getElementById(id);
						idd.remove(idd);
						var count = document.getElementById('count');
						count.innerHTML -=1;
					}else{
						alert('刪除失敗');
					}
				}
			}
			xhr.send(fd);
		}
	}
	
	function status(obj,id,status){
		//修改狀態
		var xhr = createxhr();
		var fd = new FormData();
		fd.append('_token','<?php echo e(csrf_token()); ?>');
		fd.append('id',id);
		fd.append('status',status);
		xhr.open('post','admin/status',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText === "1"){
					if(status ===0){
					obj.setAttribute('class','btn btn-danger');  //改變原本的class
					obj.setAttribute('onclick','status(this,'+id+',1)');  //修改點擊事件的參數3
					obj.innerHTML = '停權';
					}else{
						if(status===1){
							obj.setAttribute('class','btn btn-success');  //改變原本的class
							obj.setAttribute('onclick','status(this,'+id+',0)');	//修改點擊事件的參數3						
							obj.innerHTML = '正常';
						}else{
							alert('操作失敗1');
						}
					}
				}else{
					alert('操作失敗2');
				}
			}
		}
		xhr.send(fd);
	}
	
	function edit(id){
		//修改管理人員
		var xhr = createxhr();
		xhr.open('get','admin/'+id+'/edit',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				var formEdit = document.getElementById('formEdit');
				formEdit.innerHTML = xhr.responseText;
				//alert(xhr.responseText);
			}			
		}
		xhr.send(null);
	}
	
	function update(id){
		//送出管理人員修改form表單
		var editpass = document.getElementById('editpass');
			editpass.innerHTML = " ";
		var originalpass = document.getElementById('originalpass');
			originalpass.innerHTML = " ";
		var xhr = createxhr();
		var formEdit = document.getElementById('formEdit');
		var fd = new FormData(formEdit);
		fd.append('_token','<?php echo e(csrf_token()); ?>');
		fd.append('_method','put');
		xhr.open('post','admin/'+id,true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText === '2'){
					originalpass.innerHTML = '<span style="color:red">密碼錯誤</span>';
				}
				if(xhr.responseText === '1'){
					window.location.reload();  //刷新頁面
				}else{
					if(xhr.responseText){
						var error = JSON.parse(xhr.responseText);
						if(error.pass){
							editpass.innerHTML = '<span style="color:red">'+error.pass+'</span>';							
						}
					}else{
						alert('修改失敗');
					}
				}
			}
		}
		xhr.send(fd);
	}
</script>
<?php $__env->stopSection(); ?>




<?php echo $__env->make("admin.public.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>