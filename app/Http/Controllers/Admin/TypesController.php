<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

use DB;

class TypesController extends Controller
{
	
	/*protected function data($pid=0)
	{
		//遞迴找子孫(此方法缺點就是對資料庫進行太多查詢)
		$arr = DB::table('types')->where('pid',$pid)->get();
		foreach($arr as $v){
			$v->zi = $this->data($v->id);
		}
		return $arr;
	}*/
	
	/*protected function data($arr,$pid=0)  //將資料全部取出後使用遞迴分類
	{
		static $area = array();
		foreach($arr as $v){
			if($v->pid ==$pid){
				$area[] = $v;
				$this->data($arr,$v->id);
			}
		}
		return $area;
	}*/
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		/* (此方法較複雜且難以維護)
		//一皆目錄
		$one = DB::table('types')->where('pid','0')->get(); 
		//二皆目錄
		foreach($one as $v1){
			$v1->zi = DB::table('types')->where('pid',$v1->id)->get();
		}
		//三皆目錄
		foreach($one as $v1){
			foreach($v1->zi as $v2){
				$v2->zi = DB::table('types')->where('pid','=',$v2->id)->get();
			}
		}
		print_r($one);*/

		/*$data = DB::table('types')->orderby('sort','asc')->get();
		print_r($this->data($data));*/
		
		$data = DB::select('select * ,concat(path,id) t from types order by t'); //在資料庫中實現
		return view('admin.types.index')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except(['_token']);
		if(DB::table('types')->insert($data)){
			return redirect('admin/types');
		}else{
			return back();
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
    public function edit($id)
    {
		$data = DB::table('types')->find($id);
        return view('admin/types.edit')->with('data',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		$request->flash();
        $data = $request->except(['_method','_token']);
		$ruler = [
		'name'=>'required',
		'title'=>'required',
		'keywords'=>'required',
		'description'=>'required',
		'sort'=>'required|numeric'
		];
		
		$message = array(
			'name.required'=>'不能為空',
			'title.required'=>'不能為空',
			'keywords.required'=>'不能為空',
			'description.required'=>'不能為空',
			'sort.required'=>'不能為空',
			'sort.numeric'=>'請輸入一個數字'
		);
		$validator = validator::make($data,$ruler,$message);
		if($validator->passes()){
			if(DB::table('types')->where('id',$id)->update($data)){
				return redirect('admin/types');
			}else{
				return back()->with('updateerror','修改失敗(未修改)');
			}
		}else{
			return back()->withInput()->withErrors($validator);
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$types = DB::table('types')->get();	
		$deltypes = $this->deltypes($types,$id); //獲得刪除的目錄下的子目錄
		array_unshift($deltypes,(int)$id);
		$delgoods = DB::table('goods')->wherein('cid',$deltypes)->get();  //獲得刪除的目錄下的商品
		$goodsid = $this->delimg($delgoods);  //獲得刪除的目錄下的商品id
		if(!empty($goodsid)&&!empty($deltypes)){			
			$delimg = DB::table('goodsimg')->wherein('goodsid',$goodsid)->get();  //獲得商品img圖片
				foreach($delgoods as $v){  //刪除商品主圖
					unlink($v->pic);
				}
				foreach($delimg as $v){  //刪除商品小圖
					unlink($v->img);
				}
			if(!DB::table('goods')->wherein('cid',$deltypes)->delete()){
				return 0;
			}
			if(!DB::table('goodsimg')->wherein('goodsid',$goodsid)->delete()){
				return 0;
			}
		}
		
		//根據傳過來的id及path 欄位包含 id 的欄位全刪除以達到分類刪除後其子欄目也刪除
        if(DB::delete('delete from types where id = '.$id.' or path like "%'.$id.'%"')){
			return 1;
		}else{
			return 0;
		}
		
    }
	
	public function is_lou(Request $request)   //是否在首頁樓層展示
	{
		//is_lou狀態
		$data = $request->only(['id','is_lou']);
		if(DB::table('types')->where('id',$data['id'])->update(['is_lou'=>$data['is_lou']])){
			return 1;
		}else{
			return 0;
		}
	}
	
	protected function deltypes($types,$id)  //找types下所有子類
	{
		static $deltypes = array();		
		foreach($types as $v){
			if($id == $v->pid){
				array_push($deltypes,$v->id);
				$this->deltypes($types,$v->id);
			}
		}
		return $deltypes;
	}
	
	protected function delimg($delgoods)  //找types下所有子類、商品、商品圖片
	{
		$delimg = array();		
		foreach($delgoods as $v){
			array_push($delimg,$v->id);
		}
		return $delimg;
	}
}
