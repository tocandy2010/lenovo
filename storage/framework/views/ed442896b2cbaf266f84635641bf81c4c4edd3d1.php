<?php /* D:\xampp\htdocs\lenovo\resources\views/admin/index.blade.php */ ?>
<?php $__env->startSection('title'); ?>
後台首頁

<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>
<div>
	<?php if(Session::has('error')): ?>
		<span style = 'color:red'><?php echo e(session('error')); ?></span><br/>
	<?php else: ?>
		<span></span><br/>
	<?php endif; ?>
	
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make("admin.public.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>