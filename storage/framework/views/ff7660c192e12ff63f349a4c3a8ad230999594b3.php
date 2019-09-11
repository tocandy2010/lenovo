<?php /* D:\xampp\htdocs\lenovo\resources\views/home/login/index.blade.php */ ?>
<?php $__env->startSection('title'); ?>
會員登入
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>

<table border = '1px' style = 'position: absolute;left:800px;top:300px;'>
	<form action = 'login/check' method = 'post'>
		<tr>
			<td>帳號</td>
			<td><input type = 'text' name = 'account'
			<?php if(old('account')): ?>
				value = "<?php echo e(old('account')); ?>"
			<?php elseif(null!==Cache::get('remember')): ?>
				value = "<?php echo e(Cache::get('remember')); ?>"
			<?php endif; ?>
			></td>
		</tr>
		<?php echo e(csrf_field()); ?>

		<tr>
			<td>密碼</td>
			<td><input type = 'password' name = 'pass'></td>
		</tr>
		<tr>
			<td>驗證碼</td>
			<td>
			<input type = 'text' name = 'vcode' style = 'width:80px'>
			<img src = 'homevcode' title = '驗證碼' width = '100px' onclick = 'changevcode(this)'><br/>
			<span style='font-size:10px;color:darkred'>點擊圖片更換驗證碼</span>
			</td>
		</tr>
		<?php if(count($errors)>0): ?>
			<tr>
				<ul><td colspan="2">
				<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<li style = 'color:darkred'><?php echo e($v); ?></li>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</ul></td>
			</tr>
		<?php endif; ?>
		<?php if(Session::has('accounterror')): ?>
			<tr>
				<ul><td colspan="2">
					<li style = 'color:darkred'><?php echo e(session('accounterror')); ?></li>
				</ul></td>
			</tr>
		<?php endif; ?>
		<?php if(Session::has('vcodeerror')): ?>
			<tr>
				<ul><td colspan="2">
					<li style = 'color:darkred'><?php echo e(session('vcodeerror')); ?></li>
				</ul></td>
			</tr>
		<?php endif; ?>
		<?php if(Session::has('passerror')): ?>
			<tr>
				<ul><td colspan="2">
					<li style = 'color:darkred'><?php echo e(session('passerror')); ?></li>
				</ul></td>
			</tr>
		<?php endif; ?>
		<?php if(Session::has('wait')): ?>
			<tr>
				<td colspan="2" style = 'color:darkred'><?php echo e(session('wait')); ?><a href = "http://<?php echo e(session('hrefemail')); ?>">立即前往註冊信箱</a></td>
			</tr>
		<?php endif; ?>
		<tr>
			<td  colspan="2">
			<?php if(null!==Cache::get('remember')): ?>
				<input type = 'checkbox' checked name = 'remember' value = '1'>
			<?php else: ?>
				<input type = 'checkbox' name = 'remember' value = '1'>
			<?php endif; ?>
			記住帳號
			</td>
			
		</tr>
		<tr>
			<td  colspan="2">
			<input type = 'reset' value = '清除'>
			<input type = 'submit' value = '登入'>
			<a href = 'forgetpass'>忘記密碼</a>
			</td>
		</tr>
	</form>
		<tr>
			<td colspan="2">還不是會員嗎?請點擊<a href = 'reg'>註冊</a></td>
		</tr>
</table>

<script>
function changevcode(obj){
	var rand = Math.random();
	obj.src = 'homevcode?'+rand;
}

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("home.public.home", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>