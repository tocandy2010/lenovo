<?php /* D:\xampp\htdocs\lenovo\resources\views/admin/comment/index.blade.php */ ?>
<?php $__env->startSection('title'); ?>
商品評論管理首頁
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>

<div>
	<span>共<span><?php echo e($count); ?></span>則評論</span>
</div>
<table border = "1">
	<tr>
		<th>商品圖片</th>
		<th>商品名稱</th>
		<th>評論者</th>
		<th>評價</th>
		<th>評論</th>
		<th>評論時間</th>
		<th>操作</th>
	</tr>
	<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<tr>
		<td><img src = '../<?php echo e($v->pic); ?>' width = 100px height = 100px></td>
		<td><?php echo e($v->title); ?></td>
		<td>
		帳號: <?php echo e($v->account); ?><br>
		姓名: <?php echo e($v->name); ?>

		</td>
		<td style = 'color:darkred;font-size:20px;'>
		<?php echo e(str_repeat('★',$v->star).str_repeat('☆',5-$v->star)); ?>

		</td>
		<td><?php echo e($v->text); ?></td>
		<td><?php echo e(date('Y-m-d H:i:s',$v->time)); ?></td>
		<?php if($v->statu === 1): ?>
			<td><span class = 'btn btn-success' onclick = 'statu(this,0,<?php echo e($v->id); ?>)'>顯示中</span></td>
		<?php else: ?>
			<td><span class = 'btn btn-danger' onclick = 'statu(this,1,<?php echo e($v->id); ?>)'>封鎖中</span></td>
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
				xhr = new ActivXobject('Mircosoft.HttpXML');
			}
		}
		return xhr;
	}
	
	function statu(obj,statu,id){
		var xhr = createxhr();
		var fd = new FormData();
		fd.append('statu',statu);
		fd.append('id',id);
		fd.append('_token','<?php echo e(csrf_token()); ?>');
		xhr.open('post','comment/statu',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status){
				if(xhr.responseText === '1'){
					if(statu === 0){
						obj.setAttribute('class','btn btn-danger');
						obj.setAttribute('onclick','statu(this,1,'+id+')');
						obj.innerHTML = '封鎖中';
					}else{
						if(statu === 1){
							obj.setAttribute('class','btn btn-success');
							obj.setAttribute('onclick','statu(this,0,'+id+')');
							obj.innerHTML = '顯示中';
						}else{
							alert('操作失敗');
						}
					}
				}else{
					alert('操作失敗');
				}
			}
		}
		xhr.send(fd);
	}


</script>
<?php $__env->stopSection(); ?>




<?php echo $__env->make("admin.public.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>