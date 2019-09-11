<?php /* D:\xampp\htdocs\lenovo\resources\views/errors/404.blade.php */ ?>
<?php $__env->startSection('title'); ?>
前台商品詳情頁面
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>

<div style = 'position: absolute;left:550px;top:300px'>
<h1>HTTP 404 - Not Found</h1>
<hr/>
找不到您所指定的頁面，請重新確認網址是否正確後再試一次。<br/>
<a href = 'http://localhost/lenovo/public/home'>回首頁</a>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("home.public.home", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>