
<form action = 'check' method = 'post'>
<table border = 1px >
{{csrf_field()}}
	<th colspan="2">登入商城後台</th>
	<tr>
		<td>帳號</td>
		<td><input type = "text" name = 'name' value = "{{old('name')}}" style = "width:100%" autocomplete = 'off'></td>
	</tr>
	<tr>
		<td>密碼</td>
		<td><input type = "password" name = 'pass' value = '' style = "width:100%" autocomplete = 'off'></td>
	</tr>
	<tr>
		<td>驗證碼</td>
		<td><input type = "text" name = 'vcode' value = '' style = "width:40%;" autocomplete = 'off' >
		<img src = 'http://localhost/lenovo/public/admin/vcode' width = '47%' height = '75%' 
		onclick = 'this.src = "http://localhost/lenovo/public/admin/vcode?"+Math.random()' alt = "驗證碼"></td>

	</tr>

	<tr>
		<td colspan="2" >
		<input type = "reset">
		<input type = "submit" value = "登入">	
		</td>
	</tr>
</table>
</form>
<div>
	@if(count($errors)>0)
	
		<ul>
			@foreach($errors->all() as $v)
				<li style = 'color:darkred'>{{$v}}</li>
			
			@endforeach
		</ul>
	@endif
	@if(Session::has('vcodeerror'))
		<li style = 'color:darkred'>{{session('vcodeerror')}}</li>
	@endif
	@if(Session::has('passerror'))
		<li style = 'color:darkred'>{{session('passerror')}}</li>
	@endif
	@if(Session::has('nameerror'))
		<li style = 'color:darkred'>{{session('nameerror')}}</li>
	@endif
<div>

