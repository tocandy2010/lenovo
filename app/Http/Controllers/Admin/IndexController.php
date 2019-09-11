<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

//使用類
use App\Http\Controllers\Controller;

use Cache;

//後台首頁控制器
class IndexController extends Controller
{
    public function index(){   //後臺首頁
		return view('admin.index');
	}
	
	public function flush()  //清除暫存
	{
		$views = '../storage/framework/views';
		if($this->delflush($views) && Cache::flush()){
			return redirect('admin');
		}else{
			return back()->with('error','清除暫存失敗');
		}

	}
	
	private function delflush($path){
		$dh = scandir($path);
		foreach($dh as $v){
			if($v === '.' || $v === '..'){
				continue;
			}else{
				if(!unlink($path.'/'.$v)){
					return 0;
				}				
						
			}
		}
		return true;
	}
}
