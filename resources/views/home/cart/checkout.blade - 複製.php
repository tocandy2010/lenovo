@extends("home.public.home")
@section('title')
結帳
@stop
@section('main')
<table border = 1px style = 'position: absolute;left:700px;top:300px'>
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
		<td colspan="4">{{$user->name}}</td>
	</tr>
	<form action = 'checkorder' method = 'post'>
	{{csrf_field()}}
		<tr>
			<td>寄送方式</td>
			<td colspan="4">
				<select style ='width:150px;height:23px' name = 'method' onchange = 'sendmethod(this)'>
					<option checked>付款方式</option>
					<option value = 1>貨到付款(+100)</option>
					<option value = 2>超商取貨(7-11 +60)</option>
				</select>
				@if(Session::has('error'))
					<span style = "color:red">{{session('error')}}</span>
				@endif
			</td>		
		</tr>
		<tr  id = 'addr' style = 'display:none'>
			<td>收件地址</td>
			<td><input type = 'text' name = 'address' value = '{{$user->address}}'></td>
		</tr>
		<tr  id = 'converstore' style = 'display:none'>
			<td><a href = ''>選擇門市</a></td>
		</tr>
		
		<tr>
			<td colspan="5" align="right">
			<input type = 'button' value = '返回購物車' onclick = 'backcart()'>
			<input type = 'submit' value = '確認'>
			</td>
		</tr>
	</form>
<table>

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
		var converstore = document.getElementById('converstore');
		if(obj.value === '1'){			
			addr.setAttribute('style','display:block');
			converstore.setAttribute('style','display:none');
		}else{
			if(obj.value === '2'){				
				converstore.setAttribute('style','display:block');
				addr.setAttribute('style','display:none');
			}
		}
	}
	
	function backcart(){  //回購物車
		window.location.href = 'http://localhost/lenovo/public/home/cart';
	}
	
</script>