@extends("admin.public.admin")
@section('title')
輪播圖管理
@stop
@section('main')

<div>
	<a href = "javascript:;" data-toggle="modal" data-target="#add">新增圖片</a>
	<span>已上傳<span> {{$count}} </span>張圖片</span>
</div>
<table border = "1">
	<tr>
		<th>id</th>
		<th>順序</th>
		<th>標題</th>
		<th>廣告圖片</th>
		<th>廣告連結</th>
		<th>操作</th>
	</tr>
	@foreach($data as $v)
	<tr>
		<td>{{$v->id}}</td>
		<td>{{$v->orderby}}</td>
		<td>{{$v->title}}</td>
		<td><img src ='../../{{$v->img}}' width = '50px' height = '50px' alt = '{{$v->title}}'></td>		
		<td><a href = '{{$v->href}} 'target="_blank">{{substr($v->href,0,50)."..."}}</a></td>
		<td>
		<a href = "javascript:;" data-toggle="modal" data-target="#edit" onclick = 'edit({{$v->id}})'>修改</a>
		<a href = ''>刪除</a>
		</td>	
	</tr>	
	@endforeach
</table>
<div>
{{$data->links()}}
</div>
<!-- 模態框（新增輪播圖） -->
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="addLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" 
						aria-hidden="true">×
				</button>
				<h4 class="modal-title" id="addLabel">
					新增輪播圖  <!-- 模態框標題 -->
				</h4>
			</div>
			<form  action = "store" method = "post" onsubmit = 'return false' id = 'formAdd' enctype = 'multipart/form-data' >
			<div class="modal-body">
			 <!-- 模態框內容 -->	
				<div>
					<label>標題</label>
					<input type = "text" name = 'title' placeholder = '文字上限為50'>
					<span id = 'titleinfo' style = 'color:red'>
						
					</span>
				</div>
				<div>
					<label>廣告連結</label>
					<input type = "text" name = 'href' placeholder = '請輸入一組連結'>
					<span id = 'hrefinfo' style = 'color:red'>
					</span>
				</div>	
				<div>
					<label>順位</label>
					<input type = "number" name = 'orderby' placeholder = '數字越小越前面'>
					<span id = 'orderbyinfo' style = 'color:red'>
					</span>
				</div>	
				<div>
					<label>上傳圖片</label>
					<span id = 'imginfo' style = 'color:red'>
					</span>
					<input type = "file" name = 'img' >
				</div>				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" 
						data-dismiss="modal" id = 'addcancel' >取消
				</button>
				<input type = 'reset' value = "重置"  class="btn btn-primary" id = 'addreset' style = 'display:none'>
				<input type = 'submit' value = "確認送出"  class="btn btn-primary" onclick = 'add()'>
			</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- 模態框止（新增輪播圖） -->

<!-- 模態框（修改輪播圖） -->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="addLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" 
						aria-hidden="true">×
				</button>
				<h4 class="modal-title" id="addLabel">
					輪播圖修改  <!-- 模態框標題 -->
				</h4>
			</div>
			<form  action = "store" method = "post" onsubmit = 'return false' id = 'formEdit' enctype = 'multipart/form-data' >
			
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- 模態框止（修改輪播圖） -->

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
	
	function add(){
		//錯誤提醒區清空
		var titleinfo = document.getElementById('titleinfo');
		var hrefinfo = document.getElementById('hrefinfo');
		var orderbyinfo = document.getElementById('orderbyinfo');
		var imginfo = document.getElementById('imginfo');
		//新增圖片
		var formAdd = document.getElementById('formAdd');
		var xhr = createxhr();
		var fd = new FormData(formAdd);
		fd.append("_token","{{csrf_token()}}");
		xhr.open('post','slider',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				var error = JSON.parse(xhr.responseText);
				if(xhr.responseText === '1'){
					window.location.reload();
				}else{
					if(error){
						titleinfo.innerHTML = error.title?error.title:"";
						hrefinfo.innerHTML = error.href?error.href:"";
						orderbyinfo.innerHTML = error.orderby?error.orderby:"";
						imginfo.innerHTML = error.img?error.img:"";
					}else{
						alert('新增失敗');
					}
				}
			}
		}
		xhr.send(fd)
	}
	
	function edit(id){
		var xhr = createxhr();
		xhr.open('get','slider/'+id+'/edit',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText){
					var formEdit = document.getElementById('formEdit');
					formEdit.innerHTML = xhr.responseText;
				}else{
					alert('修改失敗');
				}
			}
		}
		xhr.send(null);
	}
	
	function editupdate(obj,id){
		var formEdit = document.getElementById('formEdit'); 
		var xhr = createxhr();
		var fd = new FormData(formEdit);
		fd.append('_method','put');
		fd.append('_token','{{csrf_token()}}');
		xhr.open('post','slider/'+id,true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText === '1'){
					window.location.reload();
				}else{
					alert('修改失敗');
				}
			}
		}
		xhr.send(fd);
	}
</script>

@endsection

