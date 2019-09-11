<?php /* D:\xampp\htdocs\lenovo\resources\views/admin/orders/index.blade.php */ ?>
<?php $__env->startSection('title'); ?>
訂單管理首頁
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>

<div>
	<form action = 'orders' method = 'get'>
		<a href = 'orders/statuslist'>修改狀態表</a>
		<span>共<span id = 'count'><?php echo e($count); ?></span>張訂單</span>	
		<span>
			<select name = 'types'>
			<option selected>搜索類別</option>
			<?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<?php if($type === $k): ?>
					<option selected value = '<?php echo e($k); ?>'><?php echo e($v); ?></option>
				<?php else: ?>
					<option value = '<?php echo e($k); ?>'><?php echo e($v); ?></option>
				<?php endif; ?>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>
			<input type = 'text' name = 'search' 
			<?php if($searchs): ?>
				value = "<?php echo e($searchs); ?>"
			<?php endif; ?>
			>
			<input type = 'submit' value = '搜尋'>
			<button type = 'button' onclick = 'backorders()'>取消</button>
		</span>
	</form>
</div>
<table border = "1">
	<tr>
		<th>訂單編號</th>
		<th>購買人</th>
		<th>連絡電話</th>
		<th>email</th>
		<th>收貨方式</th>
		<th>收貨物地址</th>
		<th>支付狀態</th>
		<th>訂單狀態</th>
		<th>訂單成立時間</th>
		<th>訂單商品</th>
		<th>操作</th>
	</tr>
	<?php $__currentLoopData = $arr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<tr>
		<td><?php echo e($v->code); ?></td>
		<td><?php echo e($v->account); ?></td>
		<td><?php echo e($v->tel); ?></td>
		<td><?php echo e($v->email); ?></td>
		<td><?php echo e($v->method); ?></td>
		<td><?php echo e($v->address); ?></td>
		<?php if($v->money === 0): ?>
			<td style = 'color:red'>未付款</td>
		<?php else: ?>
			<td>已付款</td>
		<?php endif; ?>
		<td><?php echo e($v->status); ?></td>
		<td><?php echo e(date('Y-m-d H:i;s',$v->time)); ?></td>
		<td><a href = "orders/list?orders=<?php echo e($v->code); ?>">查看商品</a></td>
		<?php if($v->statu == 4): ?>
			<td><span>已完成交易</span></td>
		<?php else: ?>
			<td><a href = "orders/ordersgstatus?code=<?php echo e($v->code); ?>&status=<?php echo e($v->statu); ?>">修改狀態</a></td>
		<?php endif; ?>
	</tr>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>

<div>

</div>

<script>

function backorders(){
	window.location.href = 'orders';
}

</script>
<?php $__env->stopSection(); ?>




<?php echo $__env->make("admin.public.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>