<?php /* D:\xampp\htdocs\lenovo\resources\views/home/Reg/index.blade.php */ ?>
<?php $__env->startSection('title'); ?>
前台商品詳情頁面
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>

<div style = 'position: absolute;left:800px;top:300px;'>
	<form action = 'reg/check' method = 'post'>
		<div>
			<label>註冊帳號</label>
			<span><input type = 'text' name = 'account' autocomplete="off" value = '<?php echo e(old("account")); ?>'></span><br/>
			<span style = 'font-size:4px;color:red'>請輸入至少6位數(不可輸入符號)</span>			
		</div>
		<?php echo e(csrf_field()); ?>

		<div>
			<label>註冊密碼</label>
			<span><input type = 'password' name = 'pass' autocomplete="off"></span><br/>
			<span style = 'font-size:4px;color:red'>請輸入至少6位數()</span>
		</div>
		<div>
			<label>確認密碼</label>
			<span><input type = 'password' name = 'repass' autocomplete="off"></span>
		</div>
		<div>
			<label>用戶姓名</label>
			<span><input type = 'text' name = 'name' autocomplete="off" value = '<?php echo e(old("name")); ?>'></span>
		</div>
		<div>
			<label>行動電話</label>
			<span><input type = 'text' name = 'tel' autocomplete="off" value = '<?php echo e(old("tel")); ?>'></span><br/>
			<span style = 'font-size:4px;color:red'>例：0912345678</span>
		</div>
		<div>
			<label>註冊信箱</label>
			<span><input type = 'text' name = 'email' autocomplete="off" value = '<?php echo e(old("email")); ?>'></span><br/>
			<span style = 'font-size:4px;color:red'>例：email@example.com</span>
		</div>
		<div>
			<label>現居地址</label>
			<span><input type = 'text' name = 'address' autocomplete="off" value = '<?php echo e(old("address")); ?>'></span>
		</div>
		<div>
			<label>驗證碼</label>
			<span>
			<input type = 'text' name = 'vcode' style = 'width:133px;height:30px' autocomplete="off">
			<img src = 'regvcode' title = '註冊驗證碼' width = '100px' onclick = 'changevcode(this)'><br/>
			<span style = 'font-size:10px;color:darkred'>點擊圖片更換驗證碼</span>
			</span>
		</div>
		<div>
			<span>
			<input type = 'reset' value = '清除'>
			<input type = 'submit' value = '確認'>
			</span>
		</div>		
	</form>
	<div>
		<?php if(count($errors)>0): ?>
			<div>
				<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<li style = 'color:darkred'><?php echo e($v); ?></li>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</div>
		<?php endif; ?>
		<?php if(Session::has('vcodeerror')): ?>
			<div>
				<li style = 'color:darkred'><?php echo e(session('vcodeerror')); ?></li>
			</div>
		<?php endif; ?>
		<?php if(Session::has('regerror')): ?>
			<div>
				<li style = 'color:darkred'><?php echo e(session('regerror')); ?></li>
			</div>
		<?php endif; ?>	
		<?php if(Session::has('pass')): ?>
			<div>
				<li style = 'color:darkred'><?php echo e(session('pass')); ?></li>
			</div>
		<?php endif; ?>
	</div>
</div>




<?php $__env->stopSection(); ?>

<script>
function changevcode(obj){
	var rand = Math.random();
	obj.src = 'regvcode?'+rand;
}

</script>
<?php echo $__env->make("home.public.home", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>