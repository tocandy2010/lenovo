@extends('admin.public.admin')
@section('title')
新增外部廣告
@endsection
@section('main')


<form action = 'http://localhost/lenovo/public/admin/sys/ads' method = 'post' Enctype="Multipart/Form-Data">
	<div>
		<label>廣告名稱</label>
		<input type = 'text' name = 'title' autocomplete="off"  value = "{{old('title')}}">
			{{csrf_field()}}
	</div>
	<div>
		<label>廣告連結</label>
		<input type = 'text' name = 'href' autocomplete="off" value = "{{old('href')}}">
	</div>
	<div>
		<label>廣告排序</label>
		<input type = 'text' name = 'sort' autocomplete="off" value = "{{old('sort')}}">
	</div>
	<div>
		<label>商品圖片上傳</label>
		<input type = 'file' name = 'img' onchange = 'upimg(this)'>
	</div>
	<div id = 'showimg'>
	</div>
	@if(count($errors)>0)
		<div class = 'alert alert-danger'>
			<ul>
				@foreach($errors->all() as $v)
					<li>{{$v}}</li>
				@endforeach
			</ul>
		</div>
	@else
		&nbsp
	@endif
	<div>
		<input type = 'reset' value = '重置'>
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
		xhr.open('post','upimg',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status){
				var showimg = document.getElementById('showimg');
				if(xhr.responseText){
					showimg.innerHTML = '<img src = "../../../'+xhr.responseText+'" width = 200px height = 200px>';
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
