<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

//使用類
use App\Http\Controllers\Controller;

use DB;

//後台首頁控制器
class TypesController extends Controller
{
    public function index($id){
		$alltypes = DB::table('types')->get();	//所有分類
		$bread = $this->bread($alltypes,$id); //麵包屑導航
		if(empty($bread)){
			return redirect('http://localhost/lenovo/public/home');
		}
		$endbread = array_pop($bread);
		$typelist = $this->checktypes($alltypes);	//商品左側分類排序
		$allgoods = DB::table('goods')->get();	//所有商品
		$types = $this->goods($alltypes,$id); //依點選分類找商品
		$goods = DB::table('goods')->wherein('cid',$types)->paginate();
		//dd($typelist);
		return view('home.types.types')->with('list',$typelist)->with('goods',$goods)
		->with('bread',$bread)->with('endbread',$endbread);
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
	
	protected function goods($data,$id)  //找子孫
	{
		
		static $arr = array();
		foreach($data as $v){
			if($id == $v->pid){
				array_push($arr,$v->id);
				$this->goods($data,$v->id);
			}
		}
		array_push($arr,$id);
		return $arr;
	}
	
	protected function bread($types,$id){
		static $arr = array();
		foreach($types as $k=>$v){
			if($v->id == $id){
				$v->bread = 'http://localhost/lenovo/public/home/types/'.$v->id;
				array_unshift($arr,$v);
				$this->bread($types,$v->pid);
			}
		}
		return $arr;
	}
}
