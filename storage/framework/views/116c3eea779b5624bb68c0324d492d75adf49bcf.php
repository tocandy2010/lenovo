<?php /* D:\xampp\htdocs\lenovo\resources\views/home/login/savepass.blade.php */ ?>
<?php $__env->startSection('title'); ?>
前台商品詳情頁面
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>

<div border = '1px' style = 'position: absolute;left:800px;top:300px;'>
	<form action = 'savepasscheck' method = 'post' id = 'savepasscheck' onsubmit = 'return false'>
		<div>
			<label>新密碼</label>
			<span><input type = 'password' name = 'pass' ></span>			
		</div>
		<div id = 'passinfo'>
		
		</div>
		<?php echo e(csrf_field()); ?>

		<div>
			<label>確認密碼</label>
			<span><input type = 'password' name = 'repass' ></span>
		</div>
		<div id = 'repassinfo'>
		
		</div>
		<div>
			<input type = 'hidden' name = 'id' value = "<?php echo e($id); ?>" >
			<input type = 'hidden' name = 'token' value = "<?php echo e($token); ?>" >
		</div>
		<div>
			<span >
				<input type = 'reset' value = '清除'>
				<input type = 'submit' value = '確定' onclick = 'savepasscheck()'>
			</span>
			<span id = 'send'></span>
		</div>
	</form>
</div>

<script>

	function createxhr(){
		var xhr = null;		
		if(window.XMLHttpRequest){
			xhr = new XMLHttpRequest();
			if(window.ActiveXObject){
				xhr = new ActivXobject('Mircosoft.HttpXML');
			}
		}
		return xhr;
	}
	
	function savepasscheck(){
		var passinfo = document.getElementById('passinfo');
		passinfo.innerHTML = "";
		var repassinfo = document.getElementById('repassinfo');
		repassinfo.innerHTML = "";
		var send = document.getElementById('send');
		send.innerHTML = "";
		var savepasscheck = document.getElementById('savepasscheck');
		var xhr = createxhr();
		var fd = new FormData(savepasscheck);
		fd.append('_token','<?php echo e(csrf_token()); ?>');
		xhr.open('post','../savepasscheck',true);
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				if(xhr.responseText === '1'){
					window.location.href = 'http://localhost/lenovo/public/home';
				}else{
					if(xhr.responseText === '0'){
						send.innerHTML = '密碼修改失敗';
						send.setAttribute('style','color:red');
					}else{
						if(xhr.responseText === '2'){
							send.innerHTML = '驗證碼已過期';
							send.setAttribute('style','color:red');
						}else{
							if(xhr.responseText === '3'){
								send.innerHTML = '密碼必須包含一組大小寫英文';
								send.setAttribute('style','color:red');
							}else{
								if(xhr.responseText){
									var error = JSON.parse(xhr.responseText);
									if(error.pass){
										passinfo.innerHTML = error.pass;
										passinfo.setAttribute('style','color:red');
									}
									if(error.repass){
										repassinfo.innerHTML = error.repass;
										repassinfo.setAttribute('style','color:red');
									}
								}else{
									send.innerHTML = '未知的錯誤';
									send.setAttribute('style','color:red');
								}
							}
						}
					}
				}
			}
		}
		xhr.send(fd);
		
	}

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("home.public.home", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>