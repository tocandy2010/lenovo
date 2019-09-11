<?php /* D:\xampp\htdocs\lenovo\resources\views/home/home/index.blade.php */ ?>
<head>
	<title><?php echo e(config('web.title')); ?></title>
	<meta name = 'keywords' content = "<?php echo e(config('web.keywords')); ?>">
	<meta name = 'description' content = "<?php echo e(config('web.description')); ?>">
</head>

<style>

</style>
<?php $__env->startSection('title'); ?>
前台首頁
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>
	<ul class="drop-down-menu">
		<?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $one): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<li><a href ='home/types/<?php echo e($one->id); ?>' ><?php echo e($one->name); ?></a><ul>
			<?php $__currentLoopData = $one->zi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $two): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>			
				<li><a href ='home/types/<?php echo e($two->id); ?>' ><?php echo e($two->name); ?></a><ul>
					<?php $__currentLoopData = $two->zi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $three): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><a href ='home/types/<?php echo e($three->id); ?>' ><?php echo e($three->name); ?></a>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</ul></li>				
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>				
		</ul></li><br/>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
	
	<table border='1' style = 'position: absolute;left:500;top:200' >
		
		<?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<?php if($v->is_lou === 1): ?>
				<tr>
					<td  rowspan="2"><?php echo e($v->name); ?></td ><td colspan="6" style = 'text-align:right'>
					<?php $__currentLoopData = $v->zi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php if($zi->pid == $v->id): ?>
							<a href = 'home/types/<?php echo e($zi->id); ?>'><?php echo e($zi->name); ?></a>&nbsp&nbsp&nbsp				
						<?php endif; ?>	
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</td ><tr>
					<?php $__currentLoopData = $v->goods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php $__currentLoopData = $is_lougoods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<?php if(in_array($g->id,$v)): ?>
								<td  style="text-align:center;"><a href = 'home/goods/<?php echo e($g->id); ?>'>
									<img src = '<?php echo e($g->pic); ?>' width = '100' height = '100px'></a><br/> 售價<?php echo e($g->price); ?>元
								</td>
							<?php endif; ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</tr>
				</tr>
			<?php endif; ?>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</table>
	
	
<?php $__env->stopSection(); ?>


<?php echo $__env->make("home.public.home", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>