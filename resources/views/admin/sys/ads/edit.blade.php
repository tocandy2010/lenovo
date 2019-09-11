@extends('admin.public.admin')
@section('title')
外部廣告修改
@endsection
@section('main')


<form action = 'http://localhost/lenovo/public/admin/sys/ads/{{$data->id}}' method = 'post' Enctype="Multipart/Form-Data">
	<div>
		<label>廣告名稱</label>
		<input type = 'text' name = 'title' autocomplete="off"  
		@if(old('title'))
			value = "{{old('title')}}"
		@else
			value = {{$data->title}}
		@endif
		>
		{{csrf_field()}}
		<input type = "hidden" name ="_method" value = 'put'>
	</div>
	<div>
		<label>廣告連結</label>
		<input type = 'text' name = 'href' autocomplete="off" 
		@if(old('title'))
			value = "{{old('href')}}"
		@else
			value = {{$data->href}}
		@endif
		>
	</div>
	<div>
		<label>廣告排序</label>
		<input type = 'text' name = 'sort' autocomplete="off" 
		@if(old('title'))
			value = "{{old('sort')}}"
		@else
			value = {{$data->sort}}
		@endif
		>
	</div>
	<div>
		<label>商品圖片上傳</label>
		<input type = 'file' name = 'img' onchange = 'upimg(this)'>
	</div>
	<div id = 'showimg'>
		<img src = '../../../../{{$data->img}}' width = 100px height = 100px>
	</div>
	@if(count($errors)>0)
		<div class = 'alert alert-danger'>
			<ul>
				@foreach($errors->all() as $v)
					<li>{{$v}}</li>
				@endforeach
			</ul>
		</div>
	@elseif(Session::has('error'))
	<div>
		{{session('error')}}
	</div>
	@else
		&nbsp
	@endif
	<div>
		<input type = 'reset' value = '初始化'>
		<input type = 'submit' value = '確認'>
	</div>
</form>

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
	
	function upimg(obj){
		var xhr = createxhr();
		var fd = new FormData();
		fd.append('_token',"{{csrf_token()}}");
		fd.append('img',obj.files[0]);		
		xhr.open('post','../upimg',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status){
				var showimg = document.getElementById('showimg');
				if(xhr.responseText){
					showimg.innerHTML = '<img src = "../../../../'+xhr.responseText+'" width = 100px height = 100px>';
				}else{
					showimg.innerHTML ="<span style = 'color:red'>錯誤!請上傳圖片檔</span>";
					obj.value = ""
				}
			}
		}
		xhr.send(fd);
	}
	
	
</script

	
	


@endsection
