@extends("admin.public.admin")
@section('title')
訂單管理首頁
@stop
@section('main')

<div>
	<form action = 'orders' method = 'get'>
		<a href = 'orders/statuslist'>修改狀態表</a>
		<span>共<span id = 'count'>{{$count}}</span>張訂單</span>	
		<span>
			<select name = 'types'>
			<option selected>搜索類別</option>
			@foreach($types as $k=>$v)
				@if($type === $k)
					<option selected value = '{{$k}}'>{{$v}}</option>
				@else
					<option value = '{{$k}}'>{{$v}}</option>
				@endif
			@endforeach
			</select>
			<input type = 'text' name = 'search' 
			@if($searchs)
				value = "{{$searchs}}"
			@endif
			>
			<input type = 'submit' value = '搜尋'>
			<button type = 'button' onclick = 'backorders()'>取消</button>
		</span>
	</form>
</div>
<table border = "1">
	<tr>
		<th>訂單編號</th>
		<th>購買人</th>
		<th>連絡電話</th>
		<th>email</th>
		<th>收貨方式</th>
		<th>收貨物地址</th>
		<th>支付狀態</th>
		<th>訂單狀態</th>
		<th>訂單成立時間</th>
		<th>訂單商品</th>
		<th>操作</th>
	</tr>
	@foreach($arr as $k=>$v)
	<tr>
		<td>{{$v->code}}</td>
		<td>{{$v->account}}</td>
		<td>{{$v->tel}}</td>
		<td>{{$v->email}}</td>
		<td>{{$v->method}}</td>
		<td>{{$v->address}}</td>
		@if($v->money === 0)
			<td style = 'color:red'>未付款</td>
		@else
			<td>已付款</td>
		@endif
		<td>{{$v->status}}</td>
		<td>{{date('Y-m-d H:i;s',$v->time)}}</td>
		<td><a href = "orders/list?orders={{$v->code}}">查看商品</a></td>
		@if($v->statu == 4)
			<td><span>已完成交易</span></td>
		@else
			<td><a href = "orders/ordersgstatus?code={{$v->code}}&status={{$v->statu}}">修改狀態</a></td>
		@endif
	</tr>
	@endforeach
</table>

<div>

</div>

<script>

function backorders(){
	window.location.href = 'orders';
}

</script>
@endsection



