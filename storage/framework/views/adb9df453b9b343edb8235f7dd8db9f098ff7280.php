<?php /* D:\xampp\htdocs\lenovo\resources\views/home/login/forgetpass.blade.php */ ?>
<?php $__env->startSection('title'); ?>
前台商品詳情頁面
<?php $__env->stopSection(); ?>
<?php $__env->startSection('main'); ?>

<div border = '1px' style = 'position: absolute;left:800px;top:300px;'>
	<form action = 'forgetpasscheck' method = 'post' id = 'forgetpasscheck' onsubmit = 'return false'>
		<div>
			<label>帳號</label>
			<span><input type = 'text' name = 'account' value = "<?php echo e(old('account')); ?>"></span>			
		</div>
		<div id = 'accountinfo'>
		
		</div>
		<?php echo e(csrf_field()); ?>

		<div>
			<label>電話</label>
			<span><input type = 'text' name = 'tel' value = "<?php echo e(old('account')); ?>"></span>
		</div>
		<div id = 'telinfo'>
		
		</div>
		<div>
			<label>信箱</label>
			<span><input type = 'text' name = 'email' value = "<?php echo e(old('account')); ?>"	></span>
		</div>
		<div id = 'emailinfo'>
		
		</div>
		<div>
			<label>驗證碼</label>
			<span>
			<input type = 'text' name = 'vcode' style = 'width:80px'>
			<img src = 'homevcode' title = '驗證碼' width = '100px' onclick = 'changevcode(this)'>		
			</span>
		</div>
		<div id = 'vcodeinfo'>
				
		</div>
		<div>
			<span >
				<input type = 'reset' value = '清除'>
				<input type = 'submit' value = '確定' onclick = 'forgetpasscheck()'>
				<span id = 'send'></span>
			</span>			
		</div>
	</form>
</div>

<script>
	function changevcode(obj){  //換驗證碼圖
		var rand = Math.random();
		obj.src = 'homevcode?'+rand;
	}

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
	
	function forgetpasscheck(){  //發送修改密碼信
		var send = document.getElementById('send');
		var vcodeinfo = document.getElementById('vcodeinfo');
		vcodeinfo.innerHTML = ''; //每次發送請求驗證時先清空
		var accountinfo = document.getElementById('accountinfo');
		accountinfo.innerHTML = ''; //每次發送請求驗證時先清空
		var emailinfo = document.getElementById('emailinfo');
		emailinfo.innerHTML = ''; //每次發送請求驗證時先清空
		var telinfo = document.getElementById('telinfo');
		telinfo.innerHTML = ''; //每次發送請求驗證時先清空
		var vcode = document.getElementById('vcodeinfo');
		vcodeinfo.innerHTML = ''; //每次發送請求驗證時先清空
		send.innerHTML = '<img src = "../public/upload/loadingimg/loading.gif" title = "等待中" width =200px height:100px >';
		var xhr = createxhr();
		var forget = document.getElementById('forgetpasscheck');
		var fd = new FormData(forget);
		fd.append('_token','<?php echo e(csrf_token()); ?>');
		xhr.open('post','forgetpasscheck',true)
		xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				send.innerHTML = "";
				if(xhr.responseText === '1'){
					send.innerHTML = '郵件已發送至註冊信箱';
					send.setAttribute('style','color:red');
				}else{
					if(xhr.responseText === '2'){						
						vcodeinfo.innerHTML = '驗證碼錯誤';
						vcodeinfo.setAttribute('style','color:red');
					}else{
						if(xhr.responseText === '0'){
							send.innerHTML = '資料錯誤請確認';
							send.setAttribute('style','color:red');							
						}else{
							if(xhr.responseText){
								var error = JSON.parse(xhr.responseText);
								if(error.account){									
									accountinfo.innerHTML = error.account
									accountinfo.setAttribute('style','color:red');
								}
								if(error.email){									
									emailinfo.innerHTML = error.email
									emailinfo.setAttribute('style','color:red');
								}
								if(error.tel){									
									telinfo.innerHTML = error.tel
									telinfo.setAttribute('style','color:red');
								}
								if(error.vcode){									
									vcode.innerHTML = error.vcode
									vcode.setAttribute('style','color:red');
								}
							}else{
								send.innerHTML = '失敗未知的錯誤';
								send.setAttribute('style','color:red');
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