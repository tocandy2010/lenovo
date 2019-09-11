<?php /* D:\xampp\htdocs\lenovo\resources\views/home/goods/goods.blade.php */ ?>
<?php $__env->startSection('title'); ?>
前台商品詳情頁面
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>

<ul class="drop-down-menu">
		<?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $one): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<li><a href ='../../home/types/<?php echo e($one->id); ?>' ><?php echo e($one->name); ?></a><ul>
			<?php $__currentLoopData = $one->zi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $two): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>			
				<li><a href ='../../home/types/<?php echo e($two->id); ?>' ><?php echo e($two->name); ?></a><ul>
					<?php $__currentLoopData = $two->zi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $three): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><a href ='../../home/types/<?php echo e($three->id); ?>' ><?php echo e($three->name); ?></a>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</ul></li>				
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>				
		</ul></li><br/>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>

<div style = 'position: absolute;left:300px;top:150px'>
	<span><a href = 'http://localhost/lenovo/public/home'>首頁</a></span>
	<?php $__currentLoopData = $bread; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<span><a href = '<?php echo e($v->bread); ?>'><?php echo e('>'.$v->name); ?></a></span>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	<span><?php echo e('>'.$data->title); ?></span>
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
		<td><?php echo e($data->title); ?></td>
		<td><img src = '../../<?php echo e($data->pic); ?>' title = "<?php echo e($data->title); ?>" width = '100px'></td>		
		<?php if($data->number >=1): ?>
			<td><?php echo e($data->number); ?></td>
		<?php else: ?>
			<td style = 'color:red'>缺貨中</td>
		<?php endif; ?>
		<td><?php echo e($data->price); ?></td>
		<td>
			<input type = 'hidden' id = 'id' value = "<?php echo e($data->id); ?>">
			<input type = 'number' id = 'goodsnumber'  
			<?php if($data->number<=0): ?>
				min="0"
				value = '0'
			<?php else: ?>
				value = '1'
				min='1'
			<?php endif; ?>
				style = 'width:50px' max="<?php echo e($data->number); ?>" onchange = "goods(this,<?php echo e($data->number); ?>)">
			<input type = 'button' value = '加入購物車' onclick = 'addcart()'
				<?php if($data->number<=0): ?>
					disabled = "disabled"
				<?php endif; ?>
				><br/>
			<span id = 'cartinfo'></span>
		</td>
	</tr>
	<tr>
		<td>商品圖片</td>
		<?php $__currentLoopData = $img; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<td><img src = '../../<?php echo e($img->img); ?>'width = '100px' ></td>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</tr>
	<tr>
		<th>商品簡介</th>
		<td colspan="4"><?php echo $data->info; ?></td>
	</tr>
	<tr>
		<th>商品詳細介紹</th>
		<td colspan="4"><?php echo $data->text; ?></td>
	</tr>
	<tr>
		<th>商品規格</th>
		<td colspan="4"><?php echo $data->config; ?></td>
	</tr>
</table>
<table border = '1px' style ='width :1100px'>
	<tr>
		<td style = 'width :150px'>評論者</td>
		<td style = 'width :130px'>評價</td>
		<td style = 'width :170px'>評論時間</td>
		<td colspan="3">評語(<?php echo e($countcom); ?>則評論)</td>
	</tr>
	<?php $__currentLoopData = $comment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<tr>
		<td><?php echo e(substr($v->account,0,4).'***'); ?></td>
		<td style = 'color:darkred'><?php echo e(str_repeat('★',$v->star).str_repeat('☆',$star-$v->star)); ?></td>
		<td ><?php echo e(date('Y-m-d H:i:s',$v->time)); ?></td>
		<td colspan="2"><?php echo e($v->text); ?></td>
	</tr>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
</div>
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
	function addcart(){
		var xhr = createxhr()
		var id	= document.getElementById('id');
		var goodsnumber = document.getElementById('goodsnumber');
		var cartinfo = document.getElementById('cartinfo');
		cartinfo.innerHTML = '';
		var fd = new FormData();
		fd.append('id',id.value);
		fd.append('goodsnumber',goodsnumber.value);
		fd.append('_token','<?php echo e(csrf_token()); ?>');
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
<?php echo $__env->make("home.public.home", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>