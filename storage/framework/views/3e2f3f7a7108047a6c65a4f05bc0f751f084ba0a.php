<?php /* D:\xampp\htdocs\lenovo\resources\views/admin/user/index.blade.php */ ?>
<?php $__env->startSection('title'); ?>
會員管理
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>

<div>	
	<form action = 'user' method = 'get'>
		<span>共<span><?php echo e(count($count)); ?></span>位會員</span>
		<input type = text name = 'search' placeholder="請輸入電話號碼"  >
		<input type = 'submit' value = '搜尋' >
		<input type = 'button' value = '返回' onclick = 'backuser()'>
	</form>
</div>
<table border = "1">
	<tr>
		<th>id</th>
		<th>帳號</th>
		<th>使用者</th>
		<th>信箱</th>		
		<th>電話</th>
		<th>地址</th>
		<th>狀態</th>
	</tr>
	<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<tr>
		<td><?php echo e($v->id); ?></td>
		<td><?php echo e($v->account); ?></td>
		<td><?php echo e($v->name); ?></td>
		<td><?php echo e($v->email); ?></td>	
		<td><?php echo e($v->tel); ?></td>
		<td><?php echo e($v->address); ?></td>			
		<!-- 狀態 0:封鎖 1:開通 2:未開通 -->
		<?php if($v->status === 0): ?>  
			<td class = 'btn btn-danger' onclick = 'status(this,<?php echo e($v->id); ?>,1)' >封鎖中</td>
		<?php elseif($v->status === 1): ?>
			<td class = 'btn btn-success' onclick = 'status(this,<?php echo e($v->id); ?>,0)'>已開通</td>
		<?php else: ?>
			<td class = 'btn btn-warning'>未開通</td>			
		<?php endif; ?>
	</tr>	
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
<div>
<?php echo e($data->links()); ?>

</div>

<script>
	function createxhr(){
		var xhr = null;
		if(window.XMLHttpRequest){
			xhr = new XMLHttpRequest();
			if(window.ActiveXObject){
				xhr = new ActiveXObject('Mircosoft.HttpXML');
			}
		}
		return xhr;
	}
	
	function status(obj,id,status){
		var xhr = createxhr();
		var fd = new FormData();
		fd.append('status',status);
		fd.append('_token','<?php echo e(csrf_token()); ?>');
		fd.append('_method','put');
		xhr.open('post','user/'+id,true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText === '1'){
					if(status == 1){
						obj.setAttribute('class','btn btn-success');
						obj.setAttribute('onclick','status(this,'+id+',0)')
						obj.innerHTML = '已開通'
					}else{
						if(status == 0){
						obj.setAttribute('class','btn btn-danger');
						obj.setAttribute('onclick','status(this,'+id+',1)')	
						obj.innerHTML = '封鎖中'						
						}else{
							alert('操作失敗2')							
						}
					}
				}else{
					alert('操作失敗')
				}
			}
		}
		xhr.send(fd);
	}
	
	function backuser(){
		window.location.href = 'http://localhost/lenovo/public/admin/user';
	}
</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make("admin.public.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>