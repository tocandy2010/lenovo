<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

//使用類
use App\Http\Controllers\Controller;
use App\Http\Models\GoodsModel;
use App\Http\Models\OrdersModel;
use DB;

//後台首頁控制器
class IndexController extends Controller
{	
	public function index()
	{
		$types =DB::table('types')->get();	//所有分類
		$typelist = $this->checktypes($types);	//商品分類排序
		$goods = DB::table('goods')->get();	//所有商品
		
		foreach($types as $one){  //找到3級分類後和商品配對後傳到types下的goods並在前台頁面展示商品封面圖	
			$arr = array();
			foreach($one->zi as $tow){
				foreach($tow->zi as $three){
					$arr[] = $three->id;
					$one->goods = DB::table('goods')->wherein('cid',$arr)->get();
				}				
			}						
		}
		$is_lougoods =  $this->is_lougoods($types);
		return view('home.home.index')->with('list',$typelist)->with('goods',$goods)
		->with('types',$types)->with('is_lougoods',$is_lougoods);
	}
	
	protected function checktypes($data,$pid = 0)
	{
		$arr = array();
		foreach($data as $v){
			if($pid == $v->pid){
				$arr[$v->id] = $v;
				$arr[$v->id]->zi = $this->checktypes($data,$v->id);
			}
		}
		return $arr;
	}
	
	protected function is_lougoods($types)
	{
		$arr = array();
		foreach($types as $k1=>$v1){  //將樓層大類下的商品分類
			$area = array();
			foreach($v1 as $k2=>$v2){
				if($k2 == 'goods'){
					foreach($v2 as $k3=>$v3){
						$area[$v3->id] = $v3->id;
					}
				}
			}
			if(!empty($area)){
				$arr[$v1->id] = $area;
			}
		}
		
		foreach($arr as $k=>$v){
			shuffle($v);
			while(count($v)>6){
				array_pop($v);
			}
			$arr[$k] =  $v;
			
		}
		return $arr;
	}
	
	
	/*
    public function index()
	{
		$types =DB::table('types')->get();	//所有分類
		$typelist = $this->checktypes($types);	//商品分類排序
		$goods = DB::table('goods')->get();	//所有商品
		
		foreach($types as $one){  //找到3級分類後和商品配對後傳到types下的goods並在前台頁面展示商品封面圖	
			$arr = array();
			foreach($one->zi as $tow){
				foreach($tow->zi as $three){
					$arr[] = $three->id;
					$one->goods = DB::table('goods')->wherein('cid',$arr)->get();
				}
				
			}						
		}
		return view('home.home.index')->with('list',$typelist)->with('goods',$goods)->with('types',$types);
	}
	
	protected function checktypes($data,$pid = 0)
	{
		$arr = array();
		foreach($data as $v){
			if($pid == $v->pid){
				$arr[$v->id] = $v;
				$arr[$v->id]->zi = $this->checktypes($data,$v->id);
			}
		}
		return $arr;
	}
	*/
}
