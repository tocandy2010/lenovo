<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Crypt;

use DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$admin = DB::table('admin')->paginate(2);
		$count = DB::table('admin')->count();
       return view('admin.admin.index')->with('data',$admin)->with('count',$count);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() //添加
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)  //插入
    {
		$request->input('repass');
        $ruler = [
			'name'=>'required|unique:admin,name|between:6,12',
			'pass'=>'required|between:6,12|same:repass'
		];
		
		$message = array(
			'name.required'=>'請輸入帳號',
			'name.unique'=>'帳號已被使用',
			'name.between'=>'帳號長度為6-12位',
			'pass.required'=>'請輸入一組密碼',
			'pass.between'=>'密碼長度為6-12位',
			'pass.same'=>'2次密碼不相同'
		);
		
		$data = $_POST;
		unset($data['_token']);
		unset($data['repass']);
		$validator = validator::make($request->all(),$ruler,$message);
		if($validator->passes()){
			$data["pass"] = crypt::encrypt($data["pass"]);
			$data['time'] = time();
			if(DB::table('admin')->insert($data)){
				return 0;
			}else{
				return 1;
			}
		}else{
			return $validator->getMessageBag()->getMessageBag();
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)  //修改
    {
       $arr = DB::table('admin')->find($id);  //到資料庫尋找id
	  return view('admin.admin.edit')->with('data',$arr);  //再新增edit.blade.php 傳參並返回到 index.blade.php
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)  //更新
    {	
		$arr = $request->except(['_token','_method']);
		$data = DB::table('admin')->find($id);
		if(crypt::decrypt($data->pass) !== $arr['originalpass']){
			return 2;
		}	
		
        $ruler = [
			'pass'=>'required|between:6,12|same:repass'
		];
		
		$message = array(
			'pass.required'=>'請輸入一組密碼',
			'pass.between'=>'密碼長度為6-12位',
			'pass.same'=>'2次密碼不相同'
		);
		
		$validator = validator::make($arr,$ruler,$message);
			if($validator->passes()){
				unset($arr['repass']);
				unset($arr['originalpass']);
				$arr['pass'] = crypt::encrypt($arr['pass']);
				if(DB::table('admin')->where('id','=',$id)->update($arr)){
					echo 1;
				}else{
					echo 0;
				}
			}else{
				return $validator->getMessageBag()->getMessageBag();
			}
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)  //刪除
    {
        if(DB::table('admin')->where('id','=',$id)->delete()){
			return 1;
		}else{
			return 0;
		}
    }
	
	public function status(Request $request)
	{	
		$arr = $request->except('_token');
		$statuschange = $arr['status'] === '1'?0:1;
		if(DB::table('admin')->where('id','=',$arr['id'])->update(['status'=>$statuschange])){
			return 1;
		}else{
			return 0;
		}
	}
}
