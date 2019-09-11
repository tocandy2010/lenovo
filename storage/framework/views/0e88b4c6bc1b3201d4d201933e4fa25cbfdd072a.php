<?php /* D:\xampp\htdocs\lenovo\resources\views/home/cart/cart.blade.php */ ?>
<?php $__env->startSection('title'); ?>
購物車
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>
<table border = 1px style = 'position: absolute;left:700px;top:300px'>
	<tr>
		<th  colspan="7" align="right" >共<span id = 'count'><?php echo e($count); ?></span>項商品，購物車總金額<span id = 'totle'><?php echo e($totle); ?></span></th>
	</tr>
	<tr>
		<th>商品名稱</th>
		<th>商品圖片</th>
		<th>商品庫存</th>
		<th>商品單價</th>
		<th>購買數量</th>
		<th>購買金額</th>
		<th>操作</th>
	</tr>
	<?php if(isset($data)&&!empty($data)): ?>
		<form action = 'cart/checkcar' method = 'post'>
		<?php echo e(csrf_field()); ?>

			<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr class = '<?php echo e($v->id); ?>'>
					<td><?php echo e($v->title); ?></td>
					<td><img src = "../<?php echo e($v->pic); ?>" title = "<?php echo e($v->title); ?>" width = '100px'></td>
					<td><?php echo e($v->number); ?></td>
					<td><?php echo e($v->price); ?></td>
					<td>
						<button type = 'button' onclick = 'gless(<?php echo e($v->id); ?>)'>-</button>
						<input type = 'number' onclick = 'changegoods(this,<?php echo e($v->number); ?>,<?php echo e($v->id); ?>)' name = 'num' min = '1' max = '<?php echo e($v->number); ?>' id = '<?php echo e($v->id); ?>' value ="<?php echo e($v->num); ?>" style = 'width:60px'>
						<button type = 'button' onclick = 'gadd(<?php echo e($v->id); ?>,<?php echo e($v->number); ?>)'>+</button>
						<input type = 'hidden' name = 'id' value = '<?php echo e($v->id); ?>'>
					</td>
					<td id = 'add<?php echo e($v->id); ?>'><?php echo e($v->price*$v->num); ?></td>
					<td>
						<a href = 'javascript:;' onclick = 'del(<?php echo e($v->id); ?>)'>刪除</a>
					</td>
				</tr>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<tr>
				<td colspan="7" align="right">
					<input type = 'button' value = '清除購物車' onclick = 'delall()' >
					<input type = 'submit' value = '去結算'>
				</td>
			</tr>
		</form>
	<?php else: ?>
		<td colspan="7" align="right"><?php echo e($nogoods); ?></td>
	<?php endif; ?>
<table>
<?php $__env->stopSection(); ?>

<script>
	function createxhr (){  //onblur
		var xhr = null;		
		if(window.XMLHttpRequest){
			xhr = new XMLHttpRequest();
			if(window.ActiveXObject){
				xhr = new ActivXobject('Mircosoft.HttpXML');
			}
		}
		return xhr;
	}
	
	
	function gadd(id,max){  //商品遞增
		var gid = document.getElementById(id);
		if(gid.value >= max){
			gid.value = max;
		}else{
			gid.value = Number(gid.value)+1;
		}
		var xhr = createxhr();
		var fd = new FormData();
		fd.append('_token','<?php echo e(csrf_token()); ?>');
		fd.append('gadd',gid.value);
		fd.append('id',id);
		xhr.open('post','cart/gadd',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText){//計算每項商品金額和總金額
					var data = JSON.parse(xhr.responseText);
					document.getElementById('totle').innerHTML = data.totle;
					document.getElementById('add'+id).innerHTML = data.money;
				}else{
					alert('商品增加失敗');
				}
			}
		}
		xhr.send(fd);		
	}
	
	function gless(id){ //商品遞減
		var min = 1;
		var gid = document.getElementById(id);
		if(gid.value <= min){
			gid.value = min;
		}else{
			gid.value = Number(gid.value)-1;
		}
		var xhr = createxhr();
		var fd = new FormData();
		fd.append('_token','<?php echo e(csrf_token()); ?>');
		fd.append('gless',gid.value);
		fd.append('id',id);
		xhr.open('post','cart/gless',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText){ //計算每項商品金額和總金額
					var data = JSON.parse(xhr.responseText);
					document.getElementById('totle').innerHTML = data.totle;
					document.getElementById('add'+id).innerHTML = data.money;
				}else{
					alert('商品減少失敗');
				}
			}
		}
		xhr.send(fd);
	}
	
	function del(id){ //商品刪除
		var xhr = createxhr();
		var fd = new FormData();
		fd.append('_token','<?php echo e(csrf_token()); ?>');
		fd.append('id',id);
		xhr.open('post','cart/del',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText){
					var data = JSON.parse(xhr.responseText);
					document.getElementById('totle').innerHTML = data.totle;
					document.getElementById('count').innerHTML = data.count;
					var obj = document.getElementsByClassName(id)[0];
					obj.remove(obj);
				}else{
					alert('商品刪除失敗');
				}
			}
		}
		xhr.send(fd);
	}
	
	function delall(){
		var xhr = createxhr();
		xhr.open('get','cart/delall',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText){
					if(xhr.responseText == '1'){
						window.location.reload();
					}else{
						alert('商品刪除失敗');
					}
				}else{
					alert('商品刪除失敗');
				}
			}
		}
		xhr.send(null);
	}
	
	/*function settlement(){
		var xhr = createxhr();
		var fd = new FormData();
		var settlement = document.getElementById('settlement');
		fd.append('_token','<?php echo e(csrf_token()); ?>');
		fd.append('settlement',settlement);
		xhr.open('post','settlement',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				alert(xhr.responseText);
			}
		}
		xhr.send(fd)
	}*/
	
	function changegoods(obj,max,id){  //input number 當商品數量改變時
		if(obj.value >= max){
			obj.value = max;
		}
		if(obj.value <= 1){
			obj.value = 1;
		}
		var xhr = createxhr();
		var fd = new FormData();
		fd.append('_token','<?php echo e(csrf_token()); ?>');
		fd.append('number',obj.value);
		fd.append('id',id);
		xhr.open('post','cart/changegoods',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText == '0'){
					alert('商品增加錯誤');
				}else{
					if(xhr.responseText){
						var data = JSON.parse(xhr.responseText);
						document.getElementById('totle').innerHTML = data.totle;
						document.getElementById('add'+id).innerHTML = data.money;
					}else{
						alert('未知的錯誤');
					}
				}
			}
		}
		xhr.send(fd)
	}
</script>
<?php echo $__env->make("home.public.home", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>