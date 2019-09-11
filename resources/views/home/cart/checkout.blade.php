@extends("home.public.home")
@section('title')
結帳
@stop
@section('main')
<div style = 'position: absolute;left:400px;top:200px'>
	<table border = 1px >
		<tr>
			<th  colspan="7" align="right" >共<span id = 'count'>{{$count}}</span>項商品</th>
		</tr>
		<tr>
			<th>商品名稱</th>
			<th>商品圖片</th>
			<th>商品單價</th>
			<th>購買數量</th>
			<th>購買金額</th>
		</tr>
		@foreach($goods as $k=>$v)
		<tr>
			<td>{{$v->title}}</td>
			<td><img src = "../../{{$v->pic}}" title = "{{$v->title}}" width = '100px'></td>
			<td>{{$v->price}}</td>
			<td>{{$v->goodsnumber}}</td>
			<td>{{$v->money}}</td>
		</tr>
		@endforeach
		<tr>
			<td colspan="4" align="right">總金額：</td>
			<td align="right">{{$totle}}</td>
		</tr>
		<tr>
			<th>收件人</th>
			<td colspan="4">{{$user->name}}</td>
		</tr>
		<tr>
			<th>連絡電話</th>
			<td colspan="4">{{$user->tel}}</td>
		</tr>
		<form action = 'checkorder' method = 'post' id = 'checkorder'>
		{{csrf_field()}}
			<tr>
				<td>寄送方式</td>
				<td colspan="4">
					<select style ='width:170px;height:23px' name = 'method' onchange = 'sendmethod(this)'>
						<option checked>付款方式</option>
						<option value = 1 >貨到付款(+100)</option>
						<option value = 2>超商取貨(7-11 +60)</option>
					</select>
					@if(Session::has('error'))
						<span style = "color:red">{{session('error')}}</span>
					@endif
				</td>		
			</tr>
			
			<tr>
				<td colspan="5" align="right">
				<input type = 'button' value = '返回購物車' onclick = 'backcart()'>
				<input type = 'submit' value = '確認'>
				</td>
			</tr>
		</form>
	<table>
	<div id = 'converstore' style = 'display:none'>
		<form action = '' method = ''>
		<span>現在位置</span>
			<select name = 'usecity' onchange = 'town(this)'>
				<option>城市</option>
				@foreach($city as $v)
					<option value = '{{$v}}'>{{$v}}</option>
				@endforeach
			</select>
			<select name = 'usetown' id = 'selecttown' onchange = 'street(this)'>
				<option>鄉鎮市區</option>
			</select>
			<select name = 'usestreet' id = 'selectstreet'>
				<option>道路</option>
			</select>
			<button type = 'button' onclick = 'mapsreload()'>找門市</button>
		</form>
	</div>
</div>
<div style = 'position: absolute;left:650px;top:-100px'>
	<iframe  src="../maps" width = '800' id = 'maps' height = '600' frameborder="2" style = 'display:none' ></iframe>
</div>
<div  id = 'addr' style = 'display:none'>
	<span>收件地址:</span>
	<span id = 'changeaddr'>{{$user->address}}<a href = 'jacascript:;' onclick = 'changeaddr()'>修改</a></span>
</div>
@endsection

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
	function sendmethod(obj){
		var addr = document.getElementById('addr');
		var maps = document.getElementById('maps');
		var converstore = document.getElementById('converstore');
		if(obj.value === '1'){			
			addr.setAttribute('style','display:block');
			converstore.setAttribute('style','display:none');
			maps.setAttribute('style','display:none');
		}else{
			if(obj.value === '2'){				
				converstore.setAttribute('style','display:block');
				maps.setAttribute('style','display:block');
				addr.setAttribute('style','display:none');
			}
		}
	}
	
	function backcart(){  //回購物車
		window.location.href = 'http://localhost/lenovo/public/home/cart';
	}
	
	function town(obj){  //鄉鎮市區
		var xhr = createxhr();
		var fd = new FormData();
		fd.append('city',obj.value);
		fd.append('_token','{{csrf_token()}}');
		xhr.open('post','town',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				var town = document.getElementById('selecttown');
				if(xhr.responseText === ''){
					town.innerHTML = "<option>鄉鎮市區</option>"
				}else{
					if(xhr.responseText){						
						town.innerHTML = xhr.responseText;
					}else{
						town.innerHTML = "<option>鄉鎮市區</option>"
					}
				}
			}
		}		
		xhr.send(fd);
	}
	
	function street(obj){  //街道
		var xhr = createxhr();
		var fd = new FormData();
		var city = document.getElementsByName('usecity')[0];
		fd.append('town',obj.value);
		fd.append('city',city.value);
		fd.append('_token','{{csrf_token()}}');
		xhr.open('post','street',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				var street = document.getElementById('selectstreet');
				if(xhr.responseText === ''){
					street.innerHTML = "<option>道路</option>"
				}else{
					if(xhr.responseText){	
						street.innerHTML = xhr.responseText;
					}else{
						street.innerHTML = "<option>道路</option>"
					}
				}
			}
		}		
		xhr.send(fd);
	}
	function mapsreload(){  //選完地址後加載地圖
		var city = document.getElementsByName('usecity')[0].value;
		var town = document.getElementsByName('usetown')[0].value;
		var street = document.getElementsByName('usestreet')[0].value;
		document.getElementById('maps').src = '../maps?city='+city+'&town='+town+'&street='+street;
	}
	
	function changeaddr(){
		var changeaddr = document.getElementById('changeaddr');
		changeaddr.innerHTML= '<input type = "text" id = "updateaddr" >';
		changeaddr.innerHTML += '<input type = "button" value = "確認" onclick = "updateaddr()">';
	}
	
	function updateaddr(){
		/*var xhr = createxhr();
		var fd = new FormData();
		var updateaddr = document.getElementsByName('updateaddr');
		fd.append('_token','{{csrf_token()}}');
		fd.append('newaddr',updateaddr.value);
		xhr.open('post','updateaddr',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				alert('修改成功')
		}		
		xhr.send(fd);	
		}*/
		var updateaddr = document.getElementById("updateaddr").value;
		if(updateaddr === ''){
			alert('修改失敗');
		}
		if(updateaddr){
			document.getElementById('checkorder').innerHTML += '<input type = "hidden" name = "changeaddr" value = "'+updateaddr+'">';
			alert('修改成功');
		}
	}
	
</script>