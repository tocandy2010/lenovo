@extends('admin.public.admin')
@section('title')
新增分類
@endsection
@section('main')
<form action = 'http://localhost/lenovo/public/admin/types' method = 'post'>
<div>
<label>分類名稱</label>  <!--在create頁面判斷 如果有$_GET則使用沒有則為頂級-->
<input type = 'text' name = 'name' autocomplete="off" >
<input type = 'hidden' name = 'pid' value = '<?php echo (isset($_GET['pid'])&&!empty($_GET['pid']))?$_GET['pid']:0;?>'>
<input type = 'hidden' name = 'path' value = '<?php echo (isset($_GET['path'])&&!empty($_GET['path']))?$_GET['path'].",":'0,';?>'>
{{csrf_field()}}
</div>
<div>
<label>標題</label>
<input type = 'text' name = 'title' autocomplete="off" >
</div>
<div>
<label>關鍵字</label>
<input type = 'text' name = 'keywords' autocomplete="off" >
</div>
<div>
<label>商品介紹</label>
<input type = 'text' name = 'description' autocomplete="off" >
</div>
<div>
<label>排序</label>
<input type = 'text' name = 'sort' autocomplete="off">
</div>
<div>
@if($_GET == null)	<!--如果有GET則顯示是否樓層-->
<label>是否樓層</label>
是<input type = 'radio' name = 'is_lou' value = '1' >
否<input type = 'radio' name = 'is_lou' checked value = '0'>
@endif
</div>

<div>
<input type = 'reset' value = '清除'>
<input type = 'submit' value = '確認'>
</div>
</form>
@endsection
