<?php /* D:\xampp\htdocs\lenovo\resources\views/home/goods/soldout.blade.php */ ?>
<?php $__env->startSection('title'); ?>
前台商品詳情頁面
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>

<div style = 'position: absolute;left:550px;top:300px'>
<h1>現無商品</h1>
<hr/>
很抱歉，您所訂購的商品目前將不繼續販售，請重新選擇其他商品。 如果要回到剛剛瀏覽的頁面，
<span style = 'color:darkblue'>請按「上一頁」。</span>
</div>
<?php $__env->stopSection(); ?>

<script>
	function createxhr (){
		var xhr = null;		
		if(window.XMLHttpRequest){
			xhr = new XMLHttpRequest();
			if(window.ActiveXObject){
				xhr = new ActivXobject('Mircosoft.HttpXML');
			}
		}
		return xhr;
	}
</script>
<?php echo $__env->make("home.public.home", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>