<?php /* D:\xampp\htdocs\lenovo\resources\views/admin/public/admin.blade.php */ ?>
<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
		<link href="http://localhost/lenovo/public/style/admin/style.css" rel="stylesheet">
		<link href="http://localhost/lenovo/public/style/admin/accountcontrol.css" rel="stylesheet">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
		
		<link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
		<script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="http://localhost/lenovo/public/Layer/layer/layer.js"></script>	
		
        <style>

           
        </style>
    </head>
    <body>
            <div class="content">
                <p class="title m-b-md">
                    <?php echo $__env->yieldContent('title'); ?>              
					<span style = 'font-size:16px;position:absolute;right:240px;position:absolute;top:10px;' >管理員登入：<?php echo e(session('lenovoAdminUserInfo.name')); ?> </span>
						<ul class="drop-down-menu">
							<li>
								<a href="#">帳號管理</a>
								<ul>
									<li><a href = "http://localhost/lenovo/public/home">商城前台首頁</a></li>
									<li><a href = "http://localhost/lenovo/public/admin/flush">清除暫存</a></li>
									<li><a href="http://localhost/lenovo/public/admin/logout">管理員登出</a></li>
								</ul>
							</li>
						</ul>
				</p>
                <div class="links">
					<a href="http://localhost/lenovo/public/admin">首頁</a>
                    <a href="http://localhost/lenovo/public/admin/admin">管理員管理</a>
                    <a href="http://localhost/lenovo/public/admin/user">會員管理</a>
                    <a href="http://localhost/lenovo/public/admin/types">分類管理</a>
                    <a href="http://localhost/lenovo/public/admin/goods">商品管理</a>
                    <a href="http://localhost/lenovo/public/admin/orders">訂單管理</a>
                    <a href="http://localhost/lenovo/public/admin/comment">評論管理</a>
                    <a href="javascript:;" id = 'sys'>系統管理</a>

                </div>			
            </div>
		<?php echo $__env->yieldContent('main'); ?>
		
    </body>
	<script>
		$('#sys').on('click', function(){
			layer.open({
				type: 1,
				area: ['600px', '360px'],
				shadeClose: true, //点击遮罩关闭
				content:
				
				"<div class='links'><a href = 'http://localhost/lenovo/public/admin/sys/ads'>廣告管理</a><br/><a href = 'http://localhost/lenovo/public/admin/sys/config'>系統管理</a><br/><a href = 'http://localhost/lenovo/public/admin/sys/slider'>輪播圖管理</a><br/></div>"
				
			});
		});
		
	
	</script>
</html>