<?php /* D:\xampp\htdocs\lenovo\resources\views/home/reg/regmail.blade.php */ ?>
<div>
	<p>親愛的用戶 <?php echo e($name); ?> 您好</p>
	<p>  歡迎註冊本商城請立即點擊以下連結開通帳號</p>
	<p>  <a href = 'http://localhost/lenovo/public/home/reg/open/<?php echo e($id); ?>/<?php echo e($token); ?>'>點擊驗證</a></p>
</div>
