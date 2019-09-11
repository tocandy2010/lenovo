@extends("home.public.home")
<head>
	<title>{{config('web.title')}}</title>
	<meta name = 'keywords' content = "{{config('web.keywords')}}">
	<meta name = 'description' content = "{{config('web.description')}}">
</head>

<style>

</style>
@section('title')
前台首頁
@stop
@section('main')
	<ul class="drop-down-menu">
		@foreach($list as $one)
		<li><a href ='home/types/{{$one->id}}' >{{$one->name}}</a><ul>
			@foreach($one->zi as $two)			
				<li><a href ='home/types/{{$two->id}}' >{{$two->name}}</a><ul>
					@foreach($two->zi as $three)
						<li><a href ='home/types/{{$three->id}}' >{{$three->name}}</a>
					@endforeach
				</ul></li>				
			@endforeach				
		</ul></li><br/>
		@endforeach
    </ul>
	
	<table border='1' style = 'position: absolute;left:400;top:800' >
		@foreach($types as $v)
			@if($v->is_lou === 1)
				<tr>
					<td  rowspan="2">{{$v->name}}</td ><td colspan="4" style = 'text-align:right'>
					@foreach($v->zi as $zi)
						@if($zi->pid == $v->id)
							{{$zi->name}}						
						@endif	
					@endforeach
					</td ><tr>
					@foreach($v->goods as $g)
						<td  style="text-align:center;"><a href = 'home/goods/{{$g->id}}'><img src = '{{$g->pic}}' width = '100' height = '100px'></a><br/> 售價{{$g->price}}元</td>
					@endforeach
					</tr>
				</tr>
			@endif
		@endforeach
	</table>
	
	
@endsection

