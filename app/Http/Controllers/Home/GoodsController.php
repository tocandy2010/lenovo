<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

//使用類
use App\Http\Controllers\Controller;

use DB;

//後台首頁控制器
class GoodsController extends Controller
{
    public function index($goodsid){
		$types =DB::table('types')->get();	//所有分類
		$data = DB::table('goods')->where('id',$goodsid)->first(); //商品訊息
		if(empty($data)){  //判斷商品不存在則轉跳
			return view('home.goods.soldout');
		}
		$bread = $this->bread($types,$data->cid);  //麵包屑導航
		$typelist = $this->checktypes($types);	//商品左側分類排序		
		$img = DB::table('goodsimg')->where('goodsid',$goodsid)->get(); //商品圖片
		$comment = DB::table('comment')->select('comment.*','users.account')
		->join('users','users.id','=','comment.uid')->where('gid',$goodsid)->where('statu',1)->get();  //商品評論
		$countcom =count($comment);		
		return view('home.goods.goods')->with('data',$data)->with('img',$img)
		->with('comment',$comment)->with('list',$typelist)->with('star',5)->with('countcom',$countcom)
		->with('bread',$bread);
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
	
	protected function bread($types,$cid){
		static $arr = array();
		foreach($types as $v){
			if($v->id == $cid){
				$v->bread = 'http://localhost/lenovo/public/home/types/'.$v->id;
				array_unshift($arr,$v);
				$this->bread($types,$v->pid);
			}
		}
		return $arr;
	}
	
}
