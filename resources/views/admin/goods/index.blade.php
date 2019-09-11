@extends("admin.public.admin")
@section('title')
商品管理首頁
@stop
@section('main')

<div>
	<form action = 'goods' method = 'get'>
		<a href = "goods/create">新增商品</a>
		<span>已建立 <span id = 'count'>{{$count}}</span> 件商品</span>
		<input type = 'text' name = 'search' placeholder="請輸入商品名稱"
		@if(isset($search))
			value = "{{$search}}"
		@endif
		>
		<input type = 'submit' value = '搜索' >
		<input type = 'button' value = '取消' onclick = 'backgoods()'>
	</form>
</div>
<table border = "2">
	<tr>
		<th>商品封面</th>
		<th>商品分類</th>
		<th>商品名稱</th>
		<th>商品簡介</th>
		<th>商品價格</th>
		<th>商品數量</th>
		<th>商品詳細訊息</th>
		<th>商品配置訊息</th>
		<th>上架時間</th>
		<th>操作</th>
	</tr>
	@foreach($goods as $v)
	<tr>
		<td>
		<img src = '../{{$v->pic}}' width = 130px height = 130px><br>
		@foreach($goodsimg as $value)
			@if($v->id == $value->goodsid)
				<img src = '../{{$value->img}}' width = 80px height = 80px>
			@endif
		@endforeach
		</td>
		<td>{{$v->cid}}</td>
		<td width = '200px'>{{$v->title}}</td>
		<td width = '500px'>{!!$v->info!!}</td>
		<td>NT{{$v->price}}</td>
		<td>庫存量:{{$v->number}}</td>
		<td width = '300px'>{!!$v->text!!}</td>
		<td>{!!$v->config!!}</td>
		<td>{{date('Y-m-d H:i:s',$v->time)}}</td>
		<td>
		<a href = "goods/{{$v->id}}/edit">修改</a>   
		<a href = "javascript:;" onclick = 'del({{$v->id}},"{{$v->title}}")'>刪除</a>
		</td>
	</tr>
	@endforeach
</table>
<div>
{{$goods->links()}}
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
		if(confirm("確認刪除商品 "+name+" 嗎")){
			var xhr = createxhr();
			var fd = new FormData();
			fd.append('_token','{{csrf_token()}}');
			fd.append('_method','delete');
			xhr.open('post','goods/'+id,true);
			xhr.onreadystatechange = function(){
				if(this.readyState === 4 && this.status === 200){
					if(xhr.responseText === '1'){
						window.location.reload();
					}else{
						alert('無效的刪除')
					}
				}
			}
			xhr.send(fd);
		}else{
			return true;
		}
	}
	
	function backgoods(){
		window.location.href = 'http://localhost/lenovo/public/admin/goods';
	}
	
</script>
@endsection



