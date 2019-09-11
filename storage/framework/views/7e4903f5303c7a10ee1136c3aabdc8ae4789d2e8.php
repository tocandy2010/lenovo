<?php /* D:\xampp\htdocs\lenovo\resources\views/home/cart/ordersgoods.blade.php */ ?>
<?php $__env->startSection('title'); ?>
購物車
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>
<table border = 1px style = 'width:800px;position: absolute;left:558px;top:300px'>
	<tr>
		<th  colspan="7" align="right" >
			<span id = 'count'>共<?php echo e($count); ?></span>項&nbsp&nbsp&nbsp <a href = 'http://localhost/lenovo/public/home/orders'>查看其他訂單</a></span>
		</th>
	</tr>
	<tr>
		<th>商品圖片</th>
		<th>商品名稱</th>
		<th>購買數量</th>		
		<th>金額</th>
	</tr>
	<?php $__currentLoopData = $goods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<tr>
			<td align="center"><a href = '../goods/<?php echo e($v->gid); ?>'><img src = '../../<?php echo e($v->pic); ?>' width = 100px></a></td>
			<td align="center"><a href = '../goods/<?php echo e($v->gid); ?>'><?php echo e($v->title); ?></a></td>
			<td align="center"><?php echo e($v->number); ?></td>
			<td align="center">$<?php echo e($v->price); ?></td>
			<?php if($v->statu === '4' && $v->comment === 1): ?>
				<td align="center"><a href = 'javascript:;' data-toggle="modal" data-target="#comment" 
					onclick = 'comgoods("<?php echo e($v->title); ?>","<?php echo e($v->id); ?>","<?php echo e($v->gid); ?>")'>評價商品</a></td>
			<?php elseif($v->statu === '4' && $v->comment === 0): ?>
				<td align="center">此商品已完成評價</td>
			<?php endif; ?>
		</tr>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	<tr>
		<td colspan="7" align="right" style = 'color:darkred'>應付金額 $ <?php echo e($totle); ?></td>
	</tr>
</table>
<!-- 模態框（商品評論） -->
<div class="modal fade" id="comment" tabindex="-1" role="dialog" aria-labelledby="comLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" 
						aria-hidden="true">×
				</button>
				<h4 class="modal-title" id="comLabel">
					商品評價   <!-- 模態框標題 -->
				</h4>
			</div>
			<form  action = "comment" method = "post" onsubmit = 'return false' id = 'formcom' >
			<div class="modal-body">
			 <!-- 模態框內容 -->	
				<div id = 'com'>
					<label>評論</label><br/>
					<textarea  style="resize: none;max-width:567px;max-height:250px;width:567px;height:250px;"
					name = 'text' id = 'textarea'></textarea>
					<div style = 'color:darkred'>
						1★<input type = 'radio' name = 'star' value = '1'>
						2★<input type = 'radio' name = 'star' value = '2'>
						3★<input type = 'radio' name = 'star' value = '3'>
						4★<input type = 'radio' name = 'star' value = '4'>
						5★<input type = 'radio' name = 'star' value = '5'>
						<span id = 'cominfo' style = 'color:red'></span>
					</div>
				</div>				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" 
						data-dismiss="modal" id = 'addcancel' >取消
				</button>
				<input type = 'reset' value = "重置"  class="btn btn-primary" id = 'comreset' style = 'display:none'>
				<input type = 'submit' value = "確認送出"  class="btn btn-primary" onclick = 'comment()'>
			</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- 模態框止（商品評論） -->
<?php $__env->stopSection(); ?>

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
	
	function comment(){
		var xhr = createxhr();
		var cominfo = document.getElementById('cominfo');
		cominfo.innerHTML = '';
		var com = document.getElementById('formcom');
		var fd = new FormData(com);
		fd.append('_token','<?php echo e(csrf_token()); ?>');
		xhr.open('post','../comment','true');
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText === '0'){
					alert('評論失敗')
				}else{
					if(xhr.responseText === '1'){
						window.location.reload();
					}else{
						if(xhr.responseText){
							var error = JSON.parse(xhr.responseText);
							if(error.id){
								cominfo.innerHTML += '<li>'+error.id+'</li>'
							}
							if(error.gid){
								cominfo.innerHTML += '<li>'+error.gid+'</li>'
							}
							if(error.text){
								cominfo.innerHTML += '<li>'+error.text+'</li>'
							}
							if(error.star){
								cominfo.innerHTML += '<li>'+error.star+'</li>'
							}
						}else{
							alert('評論失敗')
						}
					}
				}
			}
		}
		xhr.send(fd);
	}
	
	function comgoods(gname,id,gid){
		document.getElementById('comLabel').innerHTML = gname;
		document.getElementById('com').innerHTML += '<input type = "hidden" name = "id" value = "'+id+'">';
		document.getElementById('com').innerHTML += '<input type = "hidden" name = "gid" value = "'+gid+'">'
	}
</script>
<?php echo $__env->make("home.public.home", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>