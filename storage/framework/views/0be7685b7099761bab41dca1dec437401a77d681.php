<?php /* D:\xampp\htdocs\lenovo\resources\views/admin/orders/list.blade.php */ ?>
<?php $__env->startSection('title'); ?>
訂單管理首頁
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>

<div>	
	<span>共<span id = 'count'><?php echo e($count); ?></span>樣商品</span>
	<a href = '../orders'>回訂單管理</a>
</div>
<table border = "1">
	<tr>
		<th>商品圖片</th>
		<th>商品名稱</th>		
		<th>購買數量</th>
		<th>商品單價</th>
	</tr>
	<?php
	$number = 0;
	$price = 0;
	?>
	<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<tr>
		<td><img src = '../../<?php echo e($v->pic); ?>' width = 100px height = 100px></td>
		<td><?php echo e($v->title); ?></td>				
		<td><?php echo e($v->number); ?></td>
		<td><?php echo e($v->price); ?></td>
		<?php
			$number += $v->number;
			$price += $v->number*$v->price;
		?>
	</tr>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	<tr>
		<td  colspan="6" align="right">商品總數量: <?php echo e($number); ?>商品總價格: <?php echo e($price); ?><td>
	</tr>
</table>
<div style = 'font-size:20px'>

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


</script>
<?php $__env->stopSection(); ?>




<?php echo $__env->make("admin.public.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>