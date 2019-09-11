@extends("admin.public.admin")
@section('title')
訂單管理首頁
@stop
@section('main')

<div>	
	<a href = 'javascript:;' data-toggle="modal" data-target="#add">新增狀態</a>
	<span>共<span></span>樣商品</span>
</div>
<table border = "1">
	<tr>
		<th>代號</th>
		<th>狀態名稱</th>
		<th>操作</th>		
	</tr>
	@foreach($data as $v)
	<tr>
		<td>{{$v->number}}</td>
		<td>{{$v->status}}</td>	
		<td>
			<a href = 'javascript:;' data-toggle="modal" data-target="#edit" onclick = 'edit({{$v->id}})'>修改</a>
			<a href = 'javascript:;' onclick = 'del({{$v->id}},"{{$v->status}}")'>刪除</a>
		</td>			
	</tr>
	@endforeach
</table>
<div>

</div>

<!-- 模態框（新增狀態） -->
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="addLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" 
						aria-hidden="true">×
				</button>
				<h4 class="modal-title" id="addLabel">
					新增狀態表   <!-- 模態框標題 -->
				</h4>
			</div>
			<form  action = "statuslist" method = "post" onsubmit = 'return false' id = 'formAdd' >
			<div class="modal-body">
			 <!-- 模態框內容 -->	
				<div>
					<label>代碼</label>
					<input type = "number" name = 'number' value = "{{old('number')}}">
					<div id = 'numberinfo' style = 'color:darkred'>
						
					</div>
				</div>
				<div>
					<label>狀態名稱</label>
					<input type = "test" name = 'status' value = "{{old('status')}}">
					<div id = 'statusinfo' style = 'color:darkred'>
					</div>
				</div>					
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" 
						data-dismiss="modal" id = 'addcancel' onclick = "resetall('add')">取消
				</button>
				<input type = 'reset' value = "重置"  id = 'addreset' style = 'display:none' >
				<input type = 'submit' value = "確認送出"  class="btn btn-primary" onclick = 'add()'>
			</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- 模態框止（新增狀態） -->

<!-- 模態框（修改狀態） -->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="addLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" 
						aria-hidden="true">×
				</button>
				<h4 class="modal-title" id="addLabel">
					修改狀態   <!-- 模態框標題 -->
				</h4>
			</div>
			<form  action = "statuslist" method = "post" onsubmit = 'return false' id = 'formEdit' >
			
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- 模態框止（修改狀態） -->

<script>

	function createxhr(){
		var xhr = null;
		
		if(window.XMLHttpRequest){
			xhr = new XMLHttpRequest();
			if(window.ActiveXObject){
				xhr = new ActivXobject('Mircosoft.HttpXML');
			}
		}
		return xhr;
	}
	
	function add(){  //新增狀態
		var formAdd = document.getElementById('formAdd');
		var xhr = createxhr();
		var fd = new FormData(formAdd);
		fd.append('_token','{{csrf_token()}}');
		xhr.open('post','statuslist',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText === '1'){
					window.location.reload();
				}else{
					if(xhr.responseText){
						var errors = JSON.parse(xhr.responseText);
						if(errors.number){
							document.getElementById('numberinfo').innerHTML = errors.number;
						}
						if(errors.status){
							document.getElementById('statusinfo').innerHTML = errors.status;
						}
					}else{
						alert('新增失敗');
					}
				}
			}
		}
		xhr.send(fd);
	}
	
	function del(id,name){ //刪除狀態
		if(confirm('確定刪除狀態: '+name+' 嗎?')){
			var xhr = createxhr();
			var fd = new FormData();
			fd.append('_token',"{{csrf_token()}}");
			fd.append('_method',"delete");
			xhr.open('post','statuslist/'+id,true);
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
	
	function edit(id){  //加載editstatus頁面
		var xhr = createxhr();	
		xhr.open('get','statuslist/'+id+'/edit',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText){
					document.getElementById('formEdit').innerHTML = xhr.responseText;
				}else{
					window.location.reload();
				}
			}
		}
		xhr.send(null);
	}
	
	function update(id){
		var formEdit = document.getElementById('formEdit');
		var xhr = createxhr();
		var fd = new FormData(formEdit);
		fd.append('_token',"{{csrf_token()}}");
		fd.append('_method',"put");
		xhr.open('post','statuslist/'+id,true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText === '1'){
					window.location.reload();
				}else{
					alert('修改失敗');
					window.location.reload();
				}
			}
		}
		xhr.send(fd);
	}
	
	function resetall(id){
		document.getElementById(id+'reset').click();
		document.getElementById('numberinfo').innerHTML = "";
		document.getElementById('statusinfo').innerHTML = "";
	}
	

</script>
@endsection



