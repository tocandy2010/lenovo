<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Crypt;

use DB;

class StatuslistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$data = DB::table('ordersgstatus')->get();
		return view('admin.orders.statuslist')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() //添加
    {
		//
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)  //插入
    {
		$data = $request->except(['_token']);
		$request->flash();
		$ruler = [
			'number'=>'required|numeric|unique:ordersgstatus,number',
			'status'=>'required|max:255'
		];
		$message = array(
			'number.required'=>'請輸入代碼',
			'number.numeric'=>'代碼為一個數字',
			'number.unique'=>'代碼重複',
			'status.required'=>'請輸入狀態名稱',
			'status.max'=>'狀態名稱最地字數為255'
		);
		
		$validator = validator::make($data,$ruler,$message);
		if($validator->passes()){
			if(DB::table('ordersgstatus')->insert($data)){
				return 1;
			}else{
				return 0;
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
		$data = DB::table('ordersgstatus')->find($id);
		return view('admin.orders.editstatus')->with('data',$data);
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
		$data = $request->except(['_method','_token']);
		if(DB::table('ordersgstatus')->where('id',$id)->update($data)){
			return 1;
		}else{
			return 0;
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
		if(DB::table('ordersgstatus')->where('id',$id)->delete()){
			return 1;
		}else{
			return 0;
		}
    }
	
	public function status(Request $request)
	{	
		//
	}
	


}
