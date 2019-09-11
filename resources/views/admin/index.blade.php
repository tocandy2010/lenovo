@extends("admin.public.admin")
@section('title')
後台首頁

@stop
@section('main')
<div>
	@if(Session::has('error'))
		<span style = 'color:red'>{{session('error')}}</span><br/>
	@else
		<span></span><br/>
	@endif
	
</div>

@endsection