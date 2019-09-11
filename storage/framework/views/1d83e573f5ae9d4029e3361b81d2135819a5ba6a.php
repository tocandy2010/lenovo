<?php /* D:\xampp\htdocs\lenovo\resources\views/admin/types/index.blade.php */ ?>
<?php $__env->startSection('title'); ?>
分類管理首頁
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>

<div>
	<a href = "types/create" >新增分類</a>
	<span>共<span id = 'count'><?php echo e(count($data)); ?></span>類</span>
	</div>
<table border = "1">
	<tr>
		<th>分類名稱</th>
		<th>標題</th>
		<th>關鍵字</th>
		<th>商品介紹</th>
		<th>排序</th>
		<th>子分類</th>
		<th>是否樓層</th>
		<th>操作</th>
	</tr>
	<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<tr id = '<?php echo e($v->id); ?>'>
		<?php
		 $number = explode(',',$v->t);
		 $total = count($number)-2;
		?>
		<td><?php echo e(str_repeat('|--',$total).$v->name); ?></td>
		<td><?php echo e($v->title); ?></td>
		<td><?php echo e($v->keywords); ?></td>
		<td><?php echo e($v->description); ?></td>
		<td><?php echo e($v->sort); ?></td>
		<?php if((count($number)-2)>=2): ?>
			<td>結束</td>
		<?php else: ?>
			<td><a href = 'types/create?pid=<?php echo e($v->id); ?>&path=<?php echo e($v->path); ?><?php echo e($v->id); ?>'>新增</a></td> <!--點擊後回到添加頁面並帶上 id 參數-->
		<?php endif; ?>
		<td>
		<?php if((count($number)-2)==0): ?>
			<?php if($v->is_lou === 1): ?>
			<span class = 'btn btn-success' onclick = "is_lou(this,<?php echo e($v->id); ?>,0)">是</span>
			<?php else: ?>
			<span class = 'btn btn-danger' onclick = "is_lou(this,<?php echo e($v->id); ?>,1)">否</span>
			<?php endif; ?>
		<?php else: ?>
			非頂層
		<?php endif; ?>
		</td>
		<td>
		<a href = "types/<?php echo e($v->id); ?>/edit">修改</a>   
		<a href = "javascript:;"  onclick = 'deltet(this,<?php echo e($v->id); ?>,"<?php echo e($v->name); ?>")' >刪除</a>
		</td>
	</tr>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
<script>
	function createxhr(){
		var xhr = null;
		if(window.XMLHttpRequest){
			xhr = new XMLHttpRequest();
			if(window.ActivObject){
				xhr = new ActiveXObject('Mircosoft.HtttpXML');
			}
		}
		return xhr;
	}
	function deltet(obj,id,name){
		//刪除分類
		if(!confirm('確認刪除分類 "'+name+'" 含所有子分類及商品嗎!')){
			return false;
		}
		var xhr = createxhr();
		var fd = new FormData();
		fd.append('id',id);
		fd.append('_token','<?php echo e(csrf_token()); ?>');
		fd.append('_method','delete');
		xhr.open('post','types/'+id,true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText === '1'){
					window.location.reload();
				}else{
					alert('刪除失敗')
				}
			}
		}
		xhr.send(fd);
	}
	
	function is_lou(obj,id,is_lou){
		//修改是否開啟樓層狀態
		var xhr = createxhr();
		var fd = new FormData();
		fd.append('id',id);
		fd.append('is_lou',is_lou);
		fd.append('_token','<?php echo e(csrf_token()); ?>');
		xhr.open('post','types/is_lou',true);
		xhr.onreadystatechange = function(){
			if(this.readyState ===4 && this.status){
				if(xhr.responseText==='1'){
					if(is_lou === 0){
						obj.setAttribute('class','btn btn-danger');
						obj.setAttribute('onclick','is_lou(this,'+id+',1)');
						obj.innerHTML = '否'
					}else{
						if(is_lou === 1){
						obj.setAttribute('class','btn btn-success');
						obj.setAttribute('onclick','is_lou(this,'+id+',0)');
						obj.innerHTML = '是'
						}else{
							alert('操作失敗2');
						}
					}
				}else{
					alert('操作失敗');
				}
			}
		}
		xhr.send(fd)
	}
	
	function update(){
		var xhr = createxhr();
		var fd = new FormData();
		var fd = ('_method','put')
	}
</script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('admin.public.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>