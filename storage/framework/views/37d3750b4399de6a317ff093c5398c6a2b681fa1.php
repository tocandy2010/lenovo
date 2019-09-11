<?php /* D:\xampp\htdocs\lenovo\resources\views/admin/login.blade.php */ ?>

<form action = 'check' method = 'post'>
<table border = 1px >
<?php echo e(csrf_field()); ?>

	<th colspan="2">登入商城後台</th>
	<tr>
		<td>帳號</td>
		<td><input type = "text" name = 'name' value = "<?php echo e(old('name')); ?>" style = "width:100%" autocomplete = 'off'></td>
	</tr>
	<tr>
		<td>密碼</td>
		<td><input type = "password" name = 'pass' value = '' style = "width:100%" autocomplete = 'off'></td>
	</tr>
	<tr>
		<td>驗證碼</td>
		<td><input type = "text" name = 'vcode' value = '' style = "width:40%;" autocomplete = 'off' >
		<img src = 'http://localhost/lenovo/public/admin/vcode' width = '47%' height = '75%' 
		onclick = 'this.src = "http://localhost/lenovo/public/admin/vcode?"+Math.random()' alt = "驗證碼"></td>

	</tr>

	<tr>
		<td colspan="2" >
		<input type = "reset">
		<input type = "submit" value = "登入">	
		</td>
	</tr>
</table>
</form>
<div>
	<?php if(count($errors)>0): ?>
	
		<ul>
			<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<li style = 'color:darkred'><?php echo e($v); ?></li>
			
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</ul>
	<?php endif; ?>
	<?php if(Session::has('vcodeerror')): ?>
		<li style = 'color:darkred'><?php echo e(session('vcodeerror')); ?></li>
	<?php endif; ?>
	<?php if(Session::has('passerror')): ?>
		<li style = 'color:darkred'><?php echo e(session('passerror')); ?></li>
	<?php endif; ?>
	<?php if(Session::has('nameerror')): ?>
		<li style = 'color:darkred'><?php echo e(session('nameerror')); ?></li>
	<?php endif; ?>
<div>

