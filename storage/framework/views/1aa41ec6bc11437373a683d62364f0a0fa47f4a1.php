<?php /* D:\xampp\htdocs\lenovo\resources\views/home/cart/orders.blade.php */ ?>
<?php $__env->startSection('title'); ?>
訂單訊息
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>
<div style = 'width:1500px;position: absolute;left:400px;top:200px'>
<table border = 1px >
	<tr>
		<th  colspan="7" align="right" >
			<span id = 'count'>共<?php echo e($count); ?></span>項</span>
			<select name = 'status' onchange = 'status(this)'>
				<?php $__currentLoopData = $ordersgstatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php if($status == $v->number): ?>
						<option value = '<?php echo e($v->number); ?>' selected ><?php echo e($v->status); ?></option>
					<?php else: ?>
						<option value = '<?php echo e($v->number); ?>'><?php echo e($v->status); ?></option>
					<?php endif; ?>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>
		</th>
	</tr>
	<tr>
		<th>訂單編號</th>
		<th>訂單資訊</th>
		<th>訂單狀態</th>		
		<th>取貨方式</th>
		<th>取貨地址</th>
		<th>訂單時間</th>
	</tr>
	<?php $__currentLoopData = $arr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<tr>
			<td><a href = "ordersgoods/<?php echo e($v['code']); ?>"><?php echo e($v['code']); ?></a></td>
			<td><?php echo e($v['count']); ?>件商品 應付金額＄<?php echo e($v['totle']); ?></td>
			<td><?php echo e($v['status']); ?></td>
			<td><?php echo e($v['method']); ?></td>
			<td><?php echo e($v['address']); ?></td>
			<td><?php echo e(date('Y-m-d H:i:s',$v['time'])); ?></td>	
			<?php if($v['statu'] == '4'): ?>
				<?php if($v['comment'] >0): ?>
					<td style = 'color:red'>未完成評價</td>
				<?php else: ?>
					<td>已完成評價</td>
				<?php endif; ?>
			<?php endif; ?>
		</tr>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
</div>
<div style = 'width:1500px;position: absolute;left:400px;top:400px'>
<?php echo $data->appends(['status'=>$status])->links(); ?>

</div>

<?php $__env->stopSection(); ?>

<script>
	function createxhr (){
		var xhr = null;		
		if(window.XMLHttpRequest){
			xhr = new XMLHttpRequest();
			if(window.ActiveXObject){
				xhr = new ActivXobject('Mircosoft.HttpXML');
			}
		}
		return xhr;
	}
	
	function status(obj){
		window.location.href = 'orders?status='+obj.value;
	}
</script>
<?php echo $__env->make("home.public.home", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>