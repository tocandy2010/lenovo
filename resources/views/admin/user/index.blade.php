@extends("admin.public.admin")
@section('title')
會員管理
@stop
@section('main')

<div>	
	<form action = 'user' method = 'get'>
		<span>共<span>{{count($count)}}</span>位會員</span>
		<input type = text name = 'search' placeholder="請輸入電話號碼"  >
		<input type = 'submit' value = '搜尋' >
		<input type = 'button' value = '返回' onclick = 'backuser()'>
	</form>
</div>
<table border = "1">
	<tr>
		<th>id</th>
		<th>帳號</th>
		<th>使用者</th>
		<th>信箱</th>		
		<th>電話</th>
		<th>地址</th>
		<th>狀態</th>
	</tr>
	@foreach($data as $v)
	<tr>
		<td>{{$v->id}}</td>
		<td>{{$v->account}}</td>
		<td>{{$v->name}}</td>
		<td>{{$v->email}}</td>	
		<td>{{$v->tel}}</td>
		<td>{{$v->address}}</td>			
		<!-- 狀態 0:封鎖 1:開通 2:未開通 -->
		@if($v->status === 0)  
			<td class = 'btn btn-danger' onclick = 'status(this,{{$v->id}},1)' >封鎖中</td>
		@elseif($v->status === 1)
			<td class = 'btn btn-success' onclick = 'status(this,{{$v->id}},0)'>已開通</td>
		@else
			<td class = 'btn btn-warning'>未開通</td>			
		@endif
	</tr>	
	@endforeach
</table>
<div>
{{$data->links()}}
</div>

<script>
	function createxhr(){
		var xhr = null;
		if(window.XMLHttpRequest){
			xhr = new XMLHttpRequest();
			if(window.ActiveXObject){
				xhr = new ActiveXObject('Mircosoft.HttpXML');
			}
		}
		return xhr;
	}
	
	function status(obj,id,status){
		var xhr = createxhr();
		var fd = new FormData();
		fd.append('status',status);
		fd.append('_token','{{csrf_token()}}');
		fd.append('_method','put');
		xhr.open('post','user/'+id,true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText === '1'){
					if(status == 1){
						obj.setAttribute('class','btn btn-success');
						obj.setAttribute('onclick','status(this,'+id+',0)')
						obj.innerHTML = '已開通'
					}else{
						if(status == 0){
						obj.setAttribute('class','btn btn-danger');
						obj.setAttribute('onclick','status(this,'+id+',1)')	
						obj.innerHTML = '封鎖中'						
						}else{
							alert('操作失敗2')							
						}
					}
				}else{
					alert('操作失敗')
				}
			}
		}
		xhr.send(fd);
	}
	
	function backuser(){
		window.location.href = 'http://localhost/lenovo/public/admin/user';
	}
</script>

@endsection

