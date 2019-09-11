<!DOCTYPE html>
<html>
  <head>
    <title>googleMap</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
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
      var map;
      function initMap() {  //此為可搜尋樣式
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 25.031339, lng: 121.566840},  //初始位置
          zoom: 16  //放大比例
        });
		var geocoder = new google.maps.Geocoder();
		@if(isset($location)&&!empty($location))
			marklocation(geocoder,map,'{{$location}}');
		@endif
		@if(isset($city)&&!empty($city))
			@foreach($city as $v)
				markshop(geocoder,map,'{{$v->address}}','{{$v->name}}','{{$v->number}}');
			@endforeach
		@endif
	  }
	  
	  function markshop(geocoder,resultsMap,shopadd,shopname,shopnumber) {  //標記商店
        geocoder.geocode({'address': shopadd}, function(results, status) {
          if (status === 'OK') {
			resultsMap.setCenter(results[0].geometry.location);
			var marker = new google.maps.Marker({
			position: results[0].geometry.location,
			map: resultsMap,
			icon:{
				//url: 'http://maps.google.com/mapfiles/ms/micons/cabs.png',  //可自訂義圖片
				url: 'http://localhost/lenovo/public/public/shoplogo/7-11.jpg',
				scaledSize: new google.maps.Size(50,50)
				}
			});
			var infowindow = new google.maps.InfoWindow({
				content: '<span>'+shopname+'門市'+shopnumber+'</span><br/><span>地址:'+shopadd+'</span><br/><button onclick = useshop('+shopnumber+',"'+shopadd+'","'+shopname+'")>確認</button>'
				});
			 marker.addListener('click',function(){  //標記點擊事件
				if(marker.getAnimation()==null){
				  //marker.setAnimation(google.maps.Animation.BOUNCE);
				   infowindow.open(map, marker);
				}else{
				  marker.setAnimation(null);
				}
			  });
          } else {
			//alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      }
	  
	  function marklocation(geocoder,resultsMap,locationadd) {  //標記選擇地點
        geocoder.geocode({'address': locationadd}, function(results, status) {
          if (status === 'OK') {
			resultsMap.setCenter(results[0].geometry.location);
			var marker = new google.maps.Marker({
			position: results[0].geometry.location,
			map: resultsMap,
			});
          } else {
			//alert('Geocode was not successful for the following reason: ' + status);
          }
        });
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
	
	  function useshop(shopnumber,shopadd,shopname){
		  if(confirm('確認收貨商店為:"'+shopname+'"門市 地址:"'+shopadd+'"')){
			var xhr = createxhr();
			var fd = new FormData();
			fd.append('shopadd',shopadd);
			fd.append('shopnumber',shopnumber)
			fd.append('_token','{{csrf_token()}}');
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