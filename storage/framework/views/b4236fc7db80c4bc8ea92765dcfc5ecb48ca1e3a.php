<?php /* D:\xampp\htdocs\lenovo\resources\views/home/types/types.blade.php */ ?>
<?php $__env->startSection('title'); ?>
前台分類首頁
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>
<ul class="drop-down-menu">
<?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $one): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<li><a href ='../../home/types/<?php echo e($one->id); ?>' ><?php echo e($one->name); ?></a><ul>
		<?php $__currentLoopData = $one->zi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $two): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>			
			<li><a href ='../../home/types/<?php echo e($two->id); ?>' ><?php echo e($two->name); ?></a><ul>
				<?php $__currentLoopData = $two->zi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $three): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<li><a href ='../../home/types/<?php echo e($three->id); ?>' ><?php echo e($three->name); ?></a>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</ul></li>				
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>				
	</ul></li><br/>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<div style = 'position: absolute;left:300px;top:150px'>
	<span><a href = 'http://localhost/lenovo/public/home'>首頁</a></span>
	<?php $__currentLoopData = $bread; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<span><a href = '<?php echo e($v->bread); ?>'><?php echo e('>'.$v->name); ?></a></span>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		<span><?php echo e('>'.$endbread->name); ?></span>
</div>
<table  style = 'position: absolute;left:400px;top:250px'>
<?php $i = 1 ?>		
<?php $__currentLoopData = $goods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<?php if($i == 1): ?>
	<tr>
	<?php endif; ?>
		<td style = 'width:250px;height:200px'>
			<span><a href = '../goods/<?php echo e($v->id); ?>'><img src = '../../<?php echo e($v->pic); ?>' width = '150px'><br/>
			<span><?php echo e($v->title); ?></span></a></span><br/>
		</td>
		<?php $i +=1 ?>
	<?php if($i === 6): ?>
	</tr>
	<?php $i =1 ?>
	<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>		
</table>
<div>
<?php echo e($goods->links()); ?>

</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make("home.public.home", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>