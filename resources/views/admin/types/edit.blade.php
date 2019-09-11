@extends('admin.public.admin')
@section('title')
分類修改
@endsection
@section('main')
<form action = 'http://localhost/lenovo/public/admin/types/{{$data->id}}' method = 'post'>
<div>
<label>分類名稱</label>  <!--在create頁面判斷 如果有$_GET則使用沒有則為頂級-->
<input type = 'text' name = 'name'  value = "{{$data->name}}" autocomplete='off' >
{{csrf_field()}}
<input type = 'hidden' name = '_method' value = 'put'>
@if ($errors->has('name'))
    <span style="color:red;">{{ $errors->first('name') }}</span>
@endif
</div>
<div>
<label>標題</label>
<input type = 'text' name = 'title' autocomplete="off" value = '{{$data->title}}' >
@if ($errors->has('title'))
    <span style="color:red;">{{ $errors->first('title') }}</span>
@endif
</div>
<div>
<label>關鍵字</label>
<input type = 'text' name = 'keywords' autocomplete="off" value = '{{$data->keywords}}'>
@if ($errors->has('keywords'))
    <span style="color:red;">{{ $errors->first('keywords') }}</span>
@endif
</div>
<div>
<label>商品介紹</label>
<input type = 'text' name = 'description' autocomplete="off" value = '{{$data->description}}'>
@if ($errors->has('description'))
    <span style="color:red;">{{ $errors->first('description') }}</span>
@endif
</div>
<div>
<label>排序</label>
<input type = 'text' name = 'sort' autocomplete="off" value = '{{$data->sort}}'>
@if ($errors->has('sort'))
    <span style="color:red;">{{ $errors->first('sort') }}</span>
@endif
</div>
<div>
<label>是否樓層</label>
是<input type = 'radio' name = 'is_lou' value = '1' >
否<input type = 'radio' name = 'is_lou' checked value = '0'>
</div>
<div style="color:red;font-weight:bold">
	@if(Session::has('updateerror'))
		{{Session('updateerror')}}
	@endif
</div>
<div>
<input type = 'reset' onclick = 'back()' value = '取消'>
<input type = 'reset' value = '還原'>
<input type = 'submit' value = '確認'>
</div>
</form>

@endsection

<script>
	function back(){
		history.back();
	}
</script>