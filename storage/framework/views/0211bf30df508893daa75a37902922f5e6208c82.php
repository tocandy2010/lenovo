<?php /* D:\xampp\htdocs\lenovo\resources\views/admin/orders/edit.blade.php */ ?>
<?php $__env->startSection('title'); ?>
商品狀態修改
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>
<form action = '' method = 'post'>
<div>
<label>訂單編號</label>
<span><?php echo e($_GET['code']); ?><span>
<input type = 'hidden' name = 'code' value = <?php echo e($_GET['code']); ?>>
<?php echo e(csrf_field()); ?>

</div>
<div>
<label>狀態</label>
<select name = 'statu'>
	<?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<?php if($_GET['status'] == $v->id): ?>
			<option selected value = '<?php echo e($v->id); ?>'><?php echo e($v->status); ?></option>
		<?php else: ?>
			<option value = '<?php echo e($v->id); ?>'><?php echo e($v->status); ?></option>
		<?php endif; ?>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>
</div>
<div>
<div>
	<?php if(Session::has('error')): ?>
		<span style = "color:red"><?php echo e(session('error')); ?></span>
	<?php endif; ?>
<div>
<input type = 'reset' value = '還原'>
<input type = 'submit' value = '確認'>
</div>
</form>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.public.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>