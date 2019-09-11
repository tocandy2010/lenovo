<?php /* D:\xampp\htdocs\lenovo\resources\views/admin/goods/index.blade.php */ ?>
<?php $__env->startSection('title'); ?>
商品管理首頁
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>

<div>
	<form action = 'goods' method = 'get'>
		<a href = "goods/create">新增商品</a>
		<span>已建立 <span id = 'count'><?php echo e($count); ?></span> 件商品</span>
		<input type = 'text' name = 'search' placeholder="請輸入商品名稱"
		<?php if(isset($search)): ?>
			value = "<?php echo e($search); ?>"
		<?php endif; ?>
		>
		<input type = 'submit' value = '搜索' >
		<input type = 'button' value = '取消' onclick = 'backgoods()'>
	</form>
</div>
<table border = "2">
	<tr>
		<th>商品封面</th>
		<th>商品分類</th>
		<th>商品名稱</th>
		<th>商品簡介</th>
		<th>商品價格</th>
		<th>商品數量</th>
		<th>商品詳細訊息</th>
		<th>商品配置訊息</th>
		<th>上架時間</th>
		<th>操作</th>
	</tr>
	<?php $__currentLoopData = $goods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<tr>
		<td>
		<img src = '../<?php echo e($v->pic); ?>' width = 130px height = 130px><br>
		<?php $__currentLoopData = $goodsimg; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<?php if($v->id == $value->goodsid): ?>
				<img src = '../<?php echo e($value->img); ?>' width = 80px height = 80px>
			<?php endif; ?>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</td>
		<td><?php echo e($v->cid); ?></td>
		<td width = '200px'><?php echo e($v->title); ?></td>
		<td width = '500px'><?php echo $v->info; ?></td>
		<td>NT<?php echo e($v->price); ?></td>
		<td>庫存量:<?php echo e($v->number); ?></td>
		<td width = '300px'><?php echo $v->text; ?></td>
		<td><?php echo $v->config; ?></td>
		<td><?php echo e(date('Y-m-d H:i:s',$v->time)); ?></td>
		<td>
		<a href = "goods/<?php echo e($v->id); ?>/edit">修改</a>   
		<a href = "javascript:;" onclick = 'del(<?php echo e($v->id); ?>,"<?php echo e($v->title); ?>")'>刪除</a>
		</td>
	</tr>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
<div>
<?php echo e($goods->links()); ?>

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
	
	function del(id,name){
		if(confirm("確認刪除商品 "+name+" 嗎")){
			var xhr = createxhr();
			var fd = new FormData();
			fd.append('_token','<?php echo e(csrf_token()); ?>');
			fd.append('_method','delete');
			xhr.open('post','goods/'+id,true);
			xhr.onreadystatechange = function(){
				if(this.readyState === 4 && this.status === 200){
					if(xhr.responseText === '1'){
						window.location.reload();
					}else{
						alert('無效的刪除')
					}
				}
			}
			xhr.send(fd);
		}else{
			return true;
		}
	}
	
	function backgoods(){
		window.location.href = 'http://localhost/lenovo/public/admin/goods';
	}
	
</script>
<?php $__env->stopSection(); ?>




<?php echo $__env->make("admin.public.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>