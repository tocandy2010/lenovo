@extends('admin.public.admin')
@section('title')
商品狀態修改
@endsection
@section('main')
<form action = '' method = 'post'>
<div>
<label>訂單編號</label>
<span>{{$_GET['code']}}<span>
<input type = 'hidden' name = 'code' value = {{$_GET['code']}}>
{{csrf_field()}}
</div>
<div>
<label>狀態</label>
<select name = 'statu'>
	@foreach($status as $v)
		@if($_GET['status'] == $v->id)
			<option selected value = '{{$v->id}}'>{{$v->status}}</option>
		@else
			<option value = '{{$v->id}}'>{{$v->status}}</option>
		@endif
	@endforeach
</select>
</div>
<div>
<div>
	@if(Session::has('error'))
		<span style = "color:red">{{session('error')}}</span>
	@endif
<div>
<input type = 'reset' value = '還原'>
<input type = 'submit' value = '確認'>
</div>
</form>

@endsection

