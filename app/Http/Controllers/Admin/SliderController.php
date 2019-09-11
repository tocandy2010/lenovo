<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use DB;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$data = DB::table('slider')->orderby('orderby','asc')->orderby('id','asc')->paginate(3);
		$count = DB::table('slider')->count();
		return view('admin.sys.slider.index')->with('data',$data)->with('count',$count);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
		$ruler = [
		'title'=>'required|unique:slider|max:50',
		'href'=>'required|max:255|active_url',
		'orderby'=>'required|max:127|integer',
		'img'=>'image|required|mimes:gif,jpeg,jpg,bmp,png,tif'  //mimes:檢查檔名
		];
		
		$message = array(
		'title.required'=>'未輸入標題',
		'title.unique'=>'標題已存在',
		'title.max'=>'字數已超過上限50',
		'href.required'=>'未輸入連結',
		'href.active_url'=>'不正確的連結',
		'href.max'=>'連結錯誤',
		'orderby.required'=>'未輸入輪播順序',
		'orderby.integer'=>'請輸入整數',
		'orderby.max'=>'數字錯誤',
		'img.required'=>'未選擇圖片',
		'img.image'=>'必須為圖片',
		'img.mimes'=>'必須為圖片'
		);
		
		$validator = validator::make($data,$ruler,$message);
		if($validator->passes()){
			$path = $request->file('img')->move('public\upload\slider',$data['title'].'.'.$request->file('img')->getclientoriginalextension());
			if(file_exists($path)){
				$data['img'] = $path;
				if(DB::table('slider')->insert($data)){
					return 1;
				}else{
					return 0;
				}
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
    public function edit($id)
    {
		$data = DB::table('slider')->find($id);
		if($data){
			return view('admin.sys.slider.edit')->with('data',$data);
		}else{
			return 0;
		}
		

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
        $data = $request->except(['_token','_method']);
		$oldimg = DB::table('slider')->where('id',$id)->value('img');
		if(isset($data['img'])){
			$changeimg = true;
			unlink($oldimg);
			$newimg =  $request->file('img')->move('public\upload\slider',$data['title'].'.'.$request->file('img')->getclientoriginalextension());
			$data['img'] = $newimg;
		}else{
			$data['img'] = $oldimg;
		}
		if(DB::table('slider')->where('id',$id)->update($data)){
			return 1;
		}else{
			if(isset($changeimg)){
				return 1;
			}else{
				return 0;
			}
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
        //
    }
}
