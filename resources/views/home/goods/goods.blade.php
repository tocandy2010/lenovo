@extends("home.public.home")
@section('title')
前台商品詳情頁面
@stop
@section('main')

<ul class="drop-down-menu">
		@foreach($list as $one)
		<li><a href ='../../home/types/{{$one->id}}' >{{$one->name}}</a><ul>
			@foreach($one->zi as $two)			
				<li><a href ='../../home/types/{{$two->id}}' >{{$two->name}}</a><ul>
					@foreach($two->zi as $three)
						<li><a href ='../../home/types/{{$three->id}}' >{{$three->name}}</a>
					@endforeach
				</ul></li>				
			@endforeach				
		</ul></li><br/>
		@endforeach
</ul>

<div style = 'position: absolute;left:300px;top:150px'>
	<span><a href = 'http://localhost/lenovo/public/home'>首頁</a></span>
	@foreach($bread as $v)
		<span><a href = '{{$v->bread}}'>{{'>'.$v->name}}</a></span>
	@endforeach
	<span>{{'>'.$data->title}}</span>
</div>
<div style = 'position: absolute;left:450px;top:300px' >
<table border = '1px' style ='width :1100px'>
	<tr>
		<td>商品名稱</td>
		<td>商品圖片</td>
		<td>商品庫存</td>
		<td>商品單價</td>
		<td style="text-align:center;">購買</td>
	</tr>
	<tr>	
		<td>{{$data->title}}</td>
		<td><img src = '../../{{$data->pic}}' title = "{{$data->title}}" width = '100px'></td>		
		@if($data->number >=1)
			<td>{{$data->number}}</td>
		@else
			<td style = 'color:red'>缺貨中</td>
		@endif
		<td>{{$data->price}}</td>
		<td>
			<input type = 'hidden' id = 'id' value = "{{$data->id}}">
			<input type = 'number' id = 'goodsnumber'  
			@if($data->number<=0)
				min="0"
				value = '0'
			@else
				value = '1'
				min='1'
			@endif
				style = 'width:50px' max="{{$data->number}}" onchange = "goods(this,{{$data->number}})">
			<input type = 'button' value = '加入購物車' onclick = 'addcart()'
				@if($data->number<=0)
					disabled = "disabled"
				@endif
				><br/>
			<span id = 'cartinfo'></span>
		</td>
	</tr>
	<tr>
		<td>商品圖片</td>
		@foreach($img as $img)
			<td><img src = '../../{{$img->img}}'width = '100px' ></td>
		@endforeach
	</tr>
	<tr>
		<th>商品簡介</th>
		<td colspan="4">{!!$data->info!!}</td>
	</tr>
	<tr>
		<th>商品詳細介紹</th>
		<td colspan="4">{!!$data->text!!}</td>
	</tr>
	<tr>
		<th>商品規格</th>
		<td colspan="4">{!!$data->config!!}</td>
	</tr>
</table>
<table border = '1px' style ='width :1100px'>
	<tr>
		<td style = 'width :150px'>評論者</td>
		<td style = 'width :130px'>評價</td>
		<td style = 'width :170px'>評論時間</td>
		<td colspan="3">評語({{$countcom}}則評論)</td>
	</tr>
	@foreach($comment as $v)
	<tr>
		<td>{{substr($v->account,0,4).'***'}}</td>
		<td style = 'color:darkred'>{{str_repeat('★',$v->star).str_repeat('☆',$star-$v->star)}}</td>
		<td >{{date('Y-m-d H:i:s',$v->time)}}</td>
		<td colspan="2">{{$v->text}}</td>
	</tr>
	@endforeach
</table>
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
	function addcart(){
		var xhr = createxhr()
		var id	= document.getElementById('id');
		var goodsnumber = document.getElementById('goodsnumber');
		var cartinfo = document.getElementById('cartinfo');
		cartinfo.innerHTML = '';
		var fd = new FormData();
		fd.append('id',id.value);
		fd.append('goodsnumber',goodsnumber.value);
		fd.append('_token','{{csrf_token()}}');
		xhr.open('post','../cart/addcart',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText === '1'){
					alert('已加入購物車');
				}else{
					if(xhr.responseText === '2'){
						alert('庫存不足');
					}else{
						alert('錯誤');
					}
				}
			}
		}
		xhr.send(fd);
	}
	
	function goods(obj,max){
		if(obj.value>=max){
			obj.value = max;
		}
		if(obj.value<=1){
			obj.value = 1;
		}
	}
</script>