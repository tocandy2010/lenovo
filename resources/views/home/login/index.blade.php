@extends("home.public.home")
@section('title')
會員登入
@stop
@section('main')

<table border = '1px' style = 'position: absolute;left:800px;top:300px;'>
	<form action = 'login/check' method = 'post'>
		<tr>
			<td>帳號</td>
			<td><input type = 'text' name = 'account'
			@if(old('account'))
				value = "{{old('account')}}"
			@elseif(null!==Cache::get('remember'))
				value = "{{Cache::get('remember')}}"
			@endif
			></td>
		</tr>
		{{csrf_field()}}
		<tr>
			<td>密碼</td>
			<td><input type = 'password' name = 'pass'></td>
		</tr>
		<tr>
			<td>驗證碼</td>
			<td>
			<input type = 'text' name = 'vcode' style = 'width:80px'>
			<img src = 'homevcode' title = '驗證碼' width = '100px' onclick = 'changevcode(this)'><br/>
			<span style='font-size:10px;color:darkred'>點擊圖片更換驗證碼</span>
			</td>
		</tr>
		@if(count($errors)>0)
			<tr>
				<ul><td colspan="2">
				@foreach($errors->all() as $v)
					<li style = 'color:darkred'>{{$v}}</li>
				@endforeach
				</ul></td>
			</tr>
		@endif
		@if(Session::has('accounterror'))
			<tr>
				<ul><td colspan="2">
					<li style = 'color:darkred'>{{session('accounterror')}}</li>
				</ul></td>
			</tr>
		@endif
		@if(Session::has('vcodeerror'))
			<tr>
				<ul><td colspan="2">
					<li style = 'color:darkred'>{{session('vcodeerror')}}</li>
				</ul></td>
			</tr>
		@endif
		@if(Session::has('passerror'))
			<tr>
				<ul><td colspan="2">
					<li style = 'color:darkred'>{{session('passerror')}}</li>
				</ul></td>
			</tr>
		@endif
		@if(Session::has('wait'))
			<tr>
				<td colspan="2" style = 'color:darkred'>{{session('wait')}}<a href = "http://{{session('hrefemail')}}">立即前往註冊信箱</a></td>
			</tr>
		@endif
		<tr>
			<td  colspan="2">
			@if(null!==Cache::get('remember'))
				<input type = 'checkbox' checked name = 'remember' value = '1'>
			@else
				<input type = 'checkbox' name = 'remember' value = '1'>
			@endif
			記住帳號
			</td>
			
		</tr>
		<tr>
			<td  colspan="2">
			<input type = 'reset' value = '清除'>
			<input type = 'submit' value = '登入'>
			<a href = 'forgetpass'>忘記密碼</a>
			</td>
		</tr>
	</form>
		<tr>
			<td colspan="2">還不是會員嗎?請點擊<a href = 'reg'>註冊</a></td>
		</tr>
</table>

<script>
function changevcode(obj){
	var rand = Math.random();
	obj.src = 'homevcode?'+rand;
}

</script>
@endsection