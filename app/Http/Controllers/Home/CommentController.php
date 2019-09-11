<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

//使用類
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

use DB;




class CommentController extends Controller
{	
	public function comment(Request $request)
	{
		$data = $request->except(['_token','com']);
		
		$ruler = [
			'id'=>'required|exists:orders,id',
			'gid'=>'required|exists:goods,id',
			'text'=>'required|max:500',
			'star'=>'required|between:1,5'
		];
		
		$message = array(
			'id.required'=>'無效的訂單',
			'id.exists'=>'無效的訂單',
			'gid.required'=>'無效的商品',
			'gid.exists'=>'無效的商品',
			'text.required'=>'請輸入評論',
			'text.max'=>'已超過最大字數500',
			'star.required'=>'請輸入滿意度',
			'star.between'=>'滿意度錯誤',
		);
		
		$validator = validator::make($data,$ruler,$message);
		if($validator->passes()){
			$com = array();
			$user = $request->session()->get('lenovoHomeUserInfo');
			$com['uid'] = $user['id'];
			$com['gid'] = $data['gid'];
			$com['star'] = $data['star'];
			$com['text'] = $data['text'];
			$com['time'] = time();
			$com['statu'] = 1;
			if(DB::table('comment')->insert($com)){
				DB::table('orders')->where('id',$data['id'])->update(['comment'=>'0']); //0代表已經評論
				return 1;
			}else{
				return 0;
			}
		}else{
			return $validator->getMessageBag()->getMessageBag();
		}
	}
}
