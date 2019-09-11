<?php /* D:\xampp\htdocs\lenovo\resources\views/home/maps/maps.blade.php */ ?>
<!DOCTYPE html>
<html>
  <head>
    <title>googleMap</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
	<script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
	<script src="http://localhost/lenovo/public/Layer/layer/layer.js"></script>	
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
	  #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script>
    function initMap() {		
		var map = new google.maps.Map(
		  document.getElementById('map'), {
			  zoom: 14, 
			  center:{
				lat: <?php echo e($location['lat']); ?>,
				lng: <?php echo e($location['lng']); ?>

			  }
		});
		<?php $__currentLoopData = $city; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			markshop(<?php echo e($v['lat']); ?>,<?php echo e($v['lng']); ?>,"<?php echo e($v['name']); ?>","<?php echo e($v['address']); ?>",<?php echo e($v['number']); ?>)
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		markself(<?php echo e($location['lat']); ?>,<?php echo e($location['lng']); ?>)
		  // The location of Uluru
		function markshop(slat,slng,name,address,number){
			var uluru = {lat: slat, lng: slng};
			// The map, centered at Uluru  
			// The marker, positioned at Uluru
			var marker = new google.maps.Marker({
				position: uluru,
				map: map,
				icon:{
					url: 'http://localhost/lenovo/public/public/shoplogo/7-11.jpg',
					scaledSize: new google.maps.Size(50,50)
					}
				});
			var infowindow = new google.maps.InfoWindow({
				content: '<span>'+number+name+'</span><br/>地址:'+address+'<br/><button types = "button" onclick = useshop('+number+',"'+address+'","'+name+'")>確認</button>'
				});
				marker.addListener('click',function(){
				  infowindow.open(map, marker)
				}) 
		}
		
		function markself(slat,slng){
			var uluru = {lat: slat, lng: slng};
			// The map, centered at Uluru  
			// The marker, positioned at Uluru
			var marker = new google.maps.Marker({
				position: uluru,
				map: map
				});
			var infowindow = new google.maps.InfoWindow({
				content: '您的位置'
				});
				marker.addListener('click',function(){
				  infowindow.open(map, marker)
				}) 
		}
	}
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
	function useshop(number,address,name){   //發送門市地址
		  if(confirm('確認商店:\n門市:'+name+'\n地址:'+address)){
			var xhr = createxhr();
			var fd = new FormData();
			fd.append('shopadd',address);
			fd.append('shopnumber',number)
			fd.append('_token','<?php echo e(csrf_token()); ?>');
			xhr.open('post','cart/choseshop',true);
			xhr.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
					if(xhr.responseText === '1'){
						alert('門市選擇成功');
					}else{
						alert('錯誤');
					}
				}
			}
			xhr.send(fd);
		  }
	  }
    </script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcP818DDUKLoY-TndO2UiijUSbYaGrCpU&libraries=places&callback=initMap" async defer></script>
  
  </body>
</html>