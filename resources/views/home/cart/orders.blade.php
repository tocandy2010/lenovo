@extends("home.public.home")
@section('title')
訂單訊息
@stop
@section('main')
<div style = 'width:1500px;position: absolute;left:400px;top:200px'>
<table border = 1px >
	<tr>
		<th  colspan="7" align="right" >
			<span id = 'count'>共{{$count}}</span>項</span>
			<select name = 'status' onchange = 'status(this)'>
				@foreach($ordersgstatus as $v)
					@if($status == $v->number)
						<option value = '{{$v->number}}' selected >{{$v->status}}</option>
					@else
						<option value = '{{$v->number}}'>{{$v->status}}</option>
					@endif
				@endforeach
			</select>
		</th>
	</tr>
	<tr>
		<th>訂單編號</th>
		<th>訂單資訊</th>
		<th>訂單狀態</th>		
		<th>取貨方式</th>
		<th>取貨地址</th>
		<th>訂單時間</th>
	</tr>
	@foreach($arr as $k=>$v)
		<tr>
			<td><a href = "ordersgoods/{{$v['code']}}">{{$v['code']}}</a></td>
			<td>{{$v['count']}}件商品 應付金額＄{{$v['totle']}}</td>
			<td>{{$v['status']}}</td>
			<td>{{$v['method']}}</td>
			<td>{{$v['address']}}</td>
			<td>{{date('Y-m-d H:i:s',$v['time'])}}</td>	
			@if($v['statu'] == '4')
				@if($v['comment'] >0)
					<td style = 'color:red'>未完成評價</td>
				@else
					<td>已完成評價</td>
				@endif
			@endif
		</tr>
	@endforeach
</table>
</div>
<div style = 'width:1500px;position: absolute;left:400px;top:400px'>
{!! $data->appends(['status'=>$status])->links() !!}
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
	
	function status(obj){
		window.location.href = 'orders?status='+obj.value;
	}
</script>