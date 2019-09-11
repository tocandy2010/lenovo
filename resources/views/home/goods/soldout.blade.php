@extends("home.public.home")
@section('title')
前台商品詳情頁面
@stop
@section('main')

<div style = 'position: absolute;left:550px;top:300px'>
<h1>現無商品</h1>
<hr/>
很抱歉，您所訂購的商品目前將不繼續販售，請重新選擇其他商品。 如果要回到剛剛瀏覽的頁面，
<span style = 'color:darkblue'>請按「上一頁」。</span>
</div>
@endsection

<script>
	function createxhr (){
		var xhr = null;		
		if(window.XMLHttpRequest){
			xhr = new XMLHttpRequest();
			if(window.ActiveXObject){
				xhr = new ActivXobject('Mircosoft.HttpXML');
			}
		}
		return xhr;
	}
</script>