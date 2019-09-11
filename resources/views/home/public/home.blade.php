<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
	<link href="http://localhost/lenovo/public/style/admin/style.css" rel="stylesheet">
	<link href="http://localhost/lenovo/public/style/home/goodslist.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
	<script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- Styles -->
     <style>
           
    </style>
</head>
<body>
    <div class="content">
        <span class="title m-b-md">
			<a href = 'http://localhost/lenovo/public/home'><img src = "http://localhost/lenovo/public/{{config('web.img')}}" width = '200' style = 'position:absolute;left:300px'></a>
			@if(Session::has('lenovoHomeUserInfo'))
			<span style = 'position:absolute;right:250px;font-size:25px'>歡迎用戶:{{session('lenovoHomeUserInfo.name')}}
			<a href = 'http://localhost/lenovo/public/home/cart'>購物車</a>
			<a href = 'http://localhost/lenovo/public/home/orders'>查看訂單</a>
			<a href = 'http://localhost/lenovo/public/home/logout'>登出</a>				
			</span>
             @else
				<span style = 'position:absolute;right:250px;font-size:25px'>
				<a href = 'http://localhost/lenovo/public/home/reg'>註冊</a>|<a href = 'http://localhost/lenovo/public/home/login'>登入</a>
			</span>
			@endif
			<span>@yield('title')</span>				
        </span>
        <div class="links">
        </div>
    </div>
		@yield('main')		
    </body>
</html>