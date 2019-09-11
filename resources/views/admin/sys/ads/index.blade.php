@extends("admin.public.admin")
@section('title')
外部廣告管理
@stop
@section('main')

<div>
	<a href = "ads/create">新增廣告</a>
	<span>已上傳<span>{{$count}}</span>則廣告</span>
</div>
<table border = "1">
	<tr>
		<th>id</th>
		<th>廣告圖片</th>
		<th>順序</th>
		<th>廣告標題</th>
		<th>廣告連結</th>
		<th>操作</th>
	</tr>
	@foreach($data as $v)
	<tr>
		<td>{{$v->id}}</td>
		<td><img src = '../../{{$v->img}}' width = 100px height = 100px></td>
		<td>{{$v->sort}}</td>
		<td>{{$v->title}}</td>		
		<td><a href = "{{$v->href}}"  target="_blank">廣告連結地址</a></td>
		<td>
			<a href = "ads/{{$v->id}}/edit">修改</a>
			<a href = 'javascript:;' onclick = "del('{{$v->id}}','{{$v->title}}')">刪除</a>
		</td>	
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


	function del(id,name){
		if(confirm("確認刪除 "+name+' 嗎?')){
			var xhr = createxhr();
			var fd = new FormData();
			fd.append('_method','delete');
			fd.append('_token',"{{csrf_token()}}");
			xhr.open('post','ads/'+id,true);
			xhr.onreadystatechange = function(){
				if(this.readyState === 4 && this.status === 200){
					if(xhr.responseText === '1'){
						window.location.reload();
					}else{
						alert('刪除失敗');
					}
				}
			}
			xhr.send(fd);
		}else{
			return true;
		}
	}
</script>

@endsection

