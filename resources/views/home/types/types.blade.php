@extends("home.public.home")
@section('title')
前台分類首頁
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
		<span>{{'>'.$endbread->name}}</span>
</div>
<table  style = 'position: absolute;left:400px;top:250px'>
<?php $i = 1 ?>		
@foreach($goods as $v)
	@if($i == 1)
	<tr>
	@endif
		<td style = 'width:250px;height:200px'>
			<span><a href = '../goods/{{$v->id}}'><img src = '../../{{$v->pic}}' width = '150px'><br/>
			<span>{{$v->title}}</span></a></span><br/>
		</td>
		<?php $i +=1 ?>
	@if($i === 6)
	</tr>
	<?php $i =1 ?>
	@endif
@endforeach		
</table>
<div>
{{$goods->links()}}
</div>
@endsection

