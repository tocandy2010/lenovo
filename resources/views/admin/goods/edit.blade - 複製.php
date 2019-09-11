@extends('admin.public.admin')
@section('title')
新增商品
@endsection
@section('main')


<form action = '../../goods/{{$goods->id}}' method = 'post' >
	<div>
		<label>商品分類</label>
		<select name = 'cid' autocomplete="off">
			<option selected>請選擇此商品分類</option>
			@foreach ($data as $v)  
				@if($v->size <2)
						<option disabled value = '{{$v->id}}' >{{$v->name}}</option>
				@else
					@if(old('cid') == $v->id)
						<option value = '{{$v->id}}' selected >{{$v->name}}</option>
					@elseif($goods->cid == $v->id )
						<option value = '{{$v->id}}' selected >{{$v->name}}</option>
					@else
						<option value = '{{$v->id}}' >{{$v->name}}</option>
					@endif
				@endif
			@endforeach
		</select>
		{{csrf_field()}}
	</div>
	<div>
		<label>商品名稱</label>
		<input type = 'hidden' name = '_method' value = 'put'>
		<input type = 'text' name = 'title' autocomplete="off"  
		@if(old('title'))
			value = "{{old('title')}}"
		@else
			value = "{{$goods->title}}"
		@endif
		>
	</div>
	<div>
		<label>商品簡介</label>
		<script id="UEditinfo" name="info" type="text/plain">
		@if(old('info'))
			{!!old('info')!!}
		@else
			{!!$goods->info!!}
		@endif
		</script>
	</div>
	<div>
		<label>商品價格</label>
		<input type = 'text' name = 'price' autocomplete="off" 
		@if(old('price'))
			value = "{{old('price')}}"
		@else
			value = "{{$goods->price}}"
		@endif
		>
	</div>
	<div>
		<label>商品數量</label>
		<input type = 'text' name = 'number' autocomplete="off" 
		@if(old('number'))
			value = "{{old('number')}}"
		@else
			value = "{{$goods->number}}"
		@endif
		>
	</div>
	<div>
		<label>商品詳細訊息</label>
		<script id="UEdittext" name="text" type="text/plain">
		@if(old('text'))
			{!!old('text')!!}
		@else
			{!!$goods->text!!}
		@endif
		</script>
	</div>
	<div>
		<label>商品配置訊息</label>
		<script id="UEditconfig" name="config" type="text/plain" >
		@if(old('config'))
			{!!old('config')!!}
		@else
			{!!$goods->config!!}
		@endif
		</script>
	</div>
	<div>
		<label>商品封面圖</label>
		<div>
			<input type = 'file' name = 'pic' id = 'pic' onchange = 'uploadpic()' style = 'display:none' >
			<a href = 'javascript:;'  id = 'openpic' onclick = 'uploadchange("pic","showpic","openpic")'>取消並重新上傳</a>
		</div>
	</div>
	<div id = 'showpic'>
	<img src = "{{'http://localhost/lenovo/public/'.$goods->pic}}" width = "150px" height = "150px">
	</div>
	<div>
		<label>商品圖片(可多選)</label>
		<div>
			<input type = 'file' name = 'img[]' id = 'img' multiple="multiple" onchange = 'uploadimg(this)' style = 'display:none'>
			<a href = 'javascript:;'  id = 'openimg' onclick = 'uploadchange("img","showimg","openimg")'>取消並重新上傳</a>
		</div>
	</div>
	<div id = 'showimg'>
		@foreach($goodsimg as $v)
			@if($goods->id == $v->goodsid)
				<img src = "{{'http://localhost/lenovo/public/'.$v->img}}" width = "150px" height = "150px">
			@endif
		@endforeach
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
	@if(Session::has('error'))
		{{session('error')}}
	@endif
		<input type = 'reset' value = '重置'>
		<input type = 'submit' value = '確認'>
	</div>
</form>

&nbsp

<script>//enctype = "Multipart/form-data"
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
	
	function uploadimg(){
		//上傳圖片  img 上傳後隱藏 並開啟重新上傳
		var showimg = document.getElementById('showimg');
		showimg.innerHTML = '';
		var upimg = document.getElementById('img');
		var openimg = document.getElementById('openimg');
		upimg.setAttribute('style','display:block');
		openimg.setAttribute('style','display:none');
		var img = upimg['files'];
		var xhr = createxhr();
		var fd  = new FormData();	
		for(var i =0;i<=(img.length-1);i++){
			fd.append('img[]',img[i])
		}
		fd.append('_token','{{csrf_token()}}');
		xhr.open('post','../uploadimg',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText === '0'){
					upimg.value = "";
					showimg.innerHTML = "<span style = 'color:red'>失敗! 可能上傳文件非圖片文件</span>";
				}else{
					if(xhr.responseText){
						var imgs = JSON.parse(xhr.responseText);
						for(var j =0;j<=(imgs.length)-1;j++){
							showimg.innerHTML += '<img src = "../../../'+imgs[j]+'" width = "150px" height = "150px" onclick = "cancelupload(this)">';
							upimg.setAttribute('style','display:none');
							openimg.setAttribute('style','display:block');
						}
					}else{
						alert('操作失敗');
					}
				}
			}
		}
		xhr.send(fd);
	}
	
		function uploadpic(){
		//上傳封面 pic 上傳後隱藏 並開啟重新上傳
		var showpic = document.getElementById('showpic');
		var openpic = document.getElementById('openpic');
		showpic.innerHTML = '';
		var uppic = document.getElementById('pic');
		uppic.setAttribute('style','display:block');
		openpic.setAttribute('style','display:block');
		var pic = uppic['files'][0];
		var xhr = createxhr();
		var fd  = new FormData();
		fd.append('_token','{{csrf_token()}}');
		fd.append('pic',pic);
		xhr.open('post','../uploadpic',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				var pics = xhr.responseText;
				if(pics === '0'){
					uppic.value = "";
					showpic.innerHTML = "<span style = 'color:red'>失敗! 可能上傳文件非圖片文件</span>";
				}else{
					if(pics){
					showpic.innerHTML = '<img src = "../../../'+pics+'" width = "150px" height = "150px" onclick = "cancelupload(this)">';
					uppic.setAttribute('style','display:none');
					openpic.setAttribute('style','display:block');
					}else{
						alert('上傳失敗');
					}
				}
			}
		}
		xhr.send(fd);
	}
	
	/*function cancelupload(img){
		//點擊圖片則刪除
		var xhr = createxhr();
		var fd = new FormData();
		fd.append('img',decodeURI(img.src));
		fd.append('_token','{{csrf_token()}}');
		xhr.open('post','cancelupload',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText === '1'){
					img.remove(img);
				}else{
					alert('操作失敗,請重新整理');
				}
			}
		}
		xhr.send(fd);
	}*/
	
	function uploadchange(obj,show,open){
		//取消上傳  點擊後隱藏 並開啟上傳功能
		obj = document.getElementById(obj);
		show = document.getElementById(show);
		open = document.getElementById(open);
		obj.setAttribute('style','display:block');
		obj.value = "";
		open.setAttribute('style','display:none');
		if(obj['files'].length>0){
			var xhr = createxhr();
			var fd = new FormData();
			var arr = new Array()
			fd.append('_token','{{csrf_token()}}');
			fd.append('path',obj.id);
			for(var i=0;(obj['files'].length)-1>=i;i++){
				arr.push(obj['files'][i]['name'])
			}
			fd.append('arr',arr);
			xhr.open('post','uploadchange',true);
			xhr.onreadystatechange = function(){
				if(this.readyState === 4 && this.status === 200){
					if(xhr.responseText === '1'){
						show = document.getElementById(show);
						open = document.getElementById(open);
						show.innerHTML = '';
						obj.setAttribute('style','display:block');
						obj.value = "";
						open.setAttribute('style','display:none');
					}else{
						alert('操作失敗,請重新整理');
					}
				}
			}
			xhr.send(fd);
		}else{
			return true;
		}
	}
	
	
	
</script

	
	<!-- 加载编辑器的容器 -->
    <script id="container" name="content" type="text/plain"></script>
    <!-- 配置文件 -->
    <script type="text/javascript" src="../../../UEditor/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="../../../UEditor/ueditor.all.js"></script>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('UEdittext');
		var ue = UE.getEditor('UEditinfo');
		var ue = UE.getEditor('UEditconfig');
    </script>
	
	
	


@endsection
