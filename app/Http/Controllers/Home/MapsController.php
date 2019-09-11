<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use DB;
use DOMDocument;
use DOMXPATH;

class MapsController extends Controller
{	
	public function maps(Request $request)
	{
		
		$city = array();
		$data = $request->only(['city','town','street']);
		if(!empty($data)){ //商店位置
			if($this->checkadd($data)){
				$shop = DB::table('shop711')->where('address','like',$data['city'].$data['town'].'%')->get();			
				$city = $this-> LatandLonshop($shop);
			}
		}
		if(empty($data['city']) ||empty( $data['town'])){ //初始位置
			$location = array('lat'=>25.048100,'lng'=>121.516909);
		}else{  //選擇地址
			$location = $this-> LatandLonself((array(implode($data))));
		}
		return view('home.maps.maps')->with('city',$city)->with('location',$location);
	}
	
	protected function checkadd($data)  //檢查傳過來mpas 下的地址是否正確
	{
		$dom = new DOMdocument("1.0","utf-8");
		$dom->load("public/address/taiwan.xml");
		$xpath = new DOMXPATH($dom);
		$sql = "//row[Col2[text()='".$data['city']."'] and Col3[text()='".$data['town']."']]/Col4";
		$res = $xpath->query($sql);
		foreach($res as $v){
			if($v->nodeValue == $data['street']){
				return true;
			}
		}
		return false;
	}
	
	protected function LatandLonshop($add)  //將超商地址送到google轉成經緯度後存入
	{ 
		foreach($add as $v){  
			$jsonmap = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($v->address)."&sensor=false&key=AIzaSyBcP818DDUKLoY-TndO2UiijUSbYaGrCpU&language=zh-TW"));
			$markshop[] = array('lat'=>$jsonmap->results[0]->geometry->location->lat,
			'lng'=>$jsonmap->results[0]->geometry->location->lng,'address'=>$v->address,'name'=>$v->name.'門市','number'=>$v->number);
		}
		return $markshop;
	}
	
	protected function LatandLonself($add)  //將超商地址送到google轉成經緯度後存入
	{ 
		foreach($add as $v){  
			$jsonmap = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($v)."&sensor=false&key=AIzaSyBcP818DDUKLoY-TndO2UiijUSbYaGrCpU&language=zh-TW"));
			$markshop = array('lat'=>$jsonmap->results[0]->geometry->location->lat,'lng'=>$jsonmap->results[0]->geometry->location->lng);
		}
		return $markshop;
	}
}
