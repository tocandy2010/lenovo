@extends("admin.public.admin")
@section('title')
系統配置管理
@stop
@section('main')

<form  action = "config" method = "post"  id = 'formAdd' enctype = 'multipart/form-data' >	
		{{csrf_field()}}
		<div>
			<label>網站名稱</label>
			<input type = "text" name = 'title' value = "{{config('web.title')}}">
			<span id = 'titleinfo' style = 'color:red'>	
			</span>
		</div>
		<div>
			<label>搜索關鍵字</label>
			<input type = "text" name = 'keywords' value = "{{config('web.keywords')}}">
			<span id = 'keywordsinfo' style = 'color:red'>
			</span>
		</div>	
		<div>
			<label>網站描述</label>
			<input type = "test" name = 'description' value = "{{config('web.description')}}">
			<span id = 'descriptioninfo' style = 'color:red'>
			</span>
		</div>	
		<div>
			<label>網站圖片</label>
			<span id = 'imginfo' style = 'color:red'>
			</span>
			<input type = "file" name = 'img' >
			<input type = 'hidden' name = 'oldimg' value = "{{config('web.img')}}" >
		</div>	
		<div>
		<img src = "../../{{config('web.img')}}" width = '100px' height = '100px'>
		</div>
	<div>
		<div style = 'color:red'>
			@if(Session::has('error'))
				{{session('error')}}
			@endif
		</div>
		<input type = 'reset' value = "重置"  class="btn btn-primary">
		<input type = 'submit' value = "確認送出"  class="btn btn-success">
	</div>
</form>



@endsection

