@extends("admin.public.admin")
@section('title')
訂單管理首頁
@stop
@section('main')

<div>	
	<span>共<span id = 'count'>{{$count}}</span>樣商品</span>
	<a href = '../orders'>回訂單管理</a>
</div>
<table border = "1">
	<tr>
		<th>商品圖片</th>
		<th>商品名稱</th>		
		<th>購買數量</th>
		<th>商品單價</th>
	</tr>
	<?php
	$number = 0;
	$price = 0;
	?>
	@foreach($data as $k=>$v)
	<tr>
		<td><img src = '../../{{$v->pic}}' width = 100px height = 100px></td>
		<td>{{$v->title}}</td>				
		<td>{{$v->number}}</td>
		<td>{{$v->price}}</td>
		<?php
			$number += $v->number;
			$price += $v->number*$v->price;
		?>
	</tr>
	@endforeach
	<tr>
		<td  colspan="6" align="right">商品總數量: {{$number}}商品總價格: {{$price}}<td>
	</tr>
</table>
<div style = 'font-size:20px'>

</div>


<script>
	function createxhr(){
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
@endsection



