<?php /* D:\xampp\htdocs\lenovo\resources\views/home/login/forgetpassmail.blade.php */ ?>
<div>
	<p>親愛的用戶 <?php echo e($name); ?> 您好</p>
	<p>  歡迎註冊本商城請立即點擊以下連結修改密碼</p>
	<p>  <a href = 'http://localhost/lenovo/public/home/savepass/<?php echo e($id); ?>/<?php echo e($token); ?>'>立即修改</a></p>
</div>
