<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//前台路由
Route::group(['namespace'=>'Home','prefix'=>'home'],function(){

Route::get('/','IndexController@index');//前台首頁
Route::get('/login','LoginController@index');//用戶登入
Route::get('/homevcode','LoginController@vcode');//驗證碼
Route::post('/login/check','LoginController@check');//登入驗證
Route::get('/reg','RegController@index');//用戶註冊
Route::get('/regvcode','RegController@vcode');//註冊驗證碼
Route::post('/reg/check','RegController@check');//註冊驗證
Route::get('/reg/regmail','RegController@regmail');//註冊成功發送驗證信
Route::get('/reg/open/{id}/{token}','RegController@open');//接收註冊發送驗證信
Route::get('/logout','LoginController@logout');//接收註冊發送驗證信
Route::get('/forgetpass','LoginController@forgetpass');//忘記密碼
Route::post('/forgetpasscheck','LoginController@forgetpasscheck');//忘記密碼驗證
Route::get('/savepass/{id}/{token}','LoginController@savepass');//忘記密碼驗證
Route::post('/savepass/savepasscheck','LoginController@savepasscheck');//忘記密碼修改驗證
Route::get('/types/{id}','TypesController@index');//商品點擊後的分類頁面

Route::group(['middleware'=>'homeLogin'],function(){
	Route::get('/goods/{id}','GoodsController@index');//商品詳情頁面
	Route::get('/cart','CartController@index');//購物車頁面
	Route::post('/cart/addcart','CartController@addcart');//購物車頁面
	Route::post('/cart/gadd','CartController@gadd');//購物車++
	Route::post('/cart/gless','CartController@gless');//購物車--
	Route::post('/cart/changegoods','CartController@changegoods');//購物車內商品數量改變時
	Route::post('/cart/del','CartController@del');//購物del
	Route::get('/cart/delall','CartController@delall');//購物delall
	Route::post('/cart/checkcar','CartController@checkcar');//跳轉到結帳路由
	Route::get('/cart/settlement','CartController@settlement');//加載結帳頁面
	Route::get('/maps','MapsController@maps');//加載超商地圖
	Route::post('/cart/checkorder','CartController@checkorder');//結帳成立訂單
	Route::get('/orders','CartController@orders');//查看所有訂單
	Route::get('/ordersgoods/{code}','CartController@ordersgoods');//查看訂單頁面
	Route::post('/comment','CommentController@comment');//查看訂單頁面
	Route::post('/cart/town','CartController@town');//找城市
	Route::post('/cart/street','CartController@street');//找城市
	Route::post('/cart/choseshop','CartController@choseshop');//將商店存到SESSION中
	});
});

//後台路由
Route::get('admin/login','Admin\LoginController@index');	//登入頁面
Route::post('admin/check','Admin\LoginController@check');  //登入檢查
Route::get('admin/vcode','Admin\LoginController@vcode');  //登入驗證碼
Route::get('admin/logout','Admin\LoginController@logout');	//用戶登出
Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>'adminLogin'],function(){
//後臺首頁
Route::get('/','IndexController@index');
//管理員管理
Route::resource('/admin',"AdminController");
Route::post('/admin/status','AdminController@status');
//會員管理
Route::resource('/user',"UserController");
//分類管理
Route::resource('/types',"TypesController");
Route::post('/types/is_lou','TypesController@is_lou');
//商品管理
Route::resource('/goods','GoodsController');
Route::post('/goods/uploadimg','GoodsController@uploadimg');  //上傳多圖
Route::post('/goods/uploadpic','GoodsController@uploadpic');  //上傳封面
//Route::post('/goods/cancelupload','GoodsController@cancelupload');  //點擊刪除
Route::post('/goods/uploadchange','GoodsController@uploadchange');  //點擊換圖
//訂單管理
Route::get('/orders','OrdersController@index');  //訂單首頁(只能用查看的方式所以只用get請求)
Route::get('/orders/list','OrdersController@list');  //訂單內容
Route::match(['get','post'],'/orders/ordersgstatus','OrdersController@ordersgstatus');  //修改訂單狀態
Route::resource('/orders/statuslist','StatuslistController');  //訂單狀態表
Route::get('/comment','CommentController@index'); //評論管理首頁
Route::post('/comment/statu','CommentController@statu');  //是否顯示評論
Route::get('/flush','IndexController@flush');  //清除暫存
//系統管理	
Route::group(['prefix'=>'sys'],function(){
	//廣告管理
	Route::resource('ads','AdsController');
	Route::post('ads/upimg','AdsController@upimg');  //上傳封面
	//系統管理
	Route::resource('config','ConfigController');
	//輪播管理
	Route::resource('slider','SliderController');
	//分類管理
	Route::resource('types','TypesAdsController');
	});
});