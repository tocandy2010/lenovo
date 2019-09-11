<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Crypt;

use DB;

class GoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$count = DB::table('goods')->count();
		$goods = DB::table('goods')->paginate(5);
		$goodsimg = DB::table('goodsimg')->get();
		return view('admin.goods.index')->with('goods',$goods)->with('goodsimg',$goodsimg)->with('count',$count);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() //添加
    {
		$data = DB::select('select types.*,concat(path,id) as t from types order by t');
		foreach($data as $v){   //將二級及三級分類的名稱加上 - 符號
			$v->size = count(explode(',',$v->t))-2;  //計算t欄以逗號區分後有幾個並加入$data內
			$v->name = str_repeat('─ ',$v->size).$v->name;
		}
		return view('admin.goods.add')->with('data',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)  //插入
    {
		//return var_dump($request->all());
		$request->flash();
		$imgs = $request->input('img');
		$data = $request->except(['_token']);
		
		$ruler = [
			'title'=>'required|max:50',
			'cid'=>'required|numeric',
			'info'=>'required|max:1000',
			'pic'=>'required',
			'price'=>'required|numeric|integer',
			'number'=>'required|numeric|integer',
			'text'=>'required|max:1000',
			'config'=>'required|max:1000',
			'img'=>'required'
		];
		
		$message = array(
			'title.required'=>'未輸入標題',
			'title.max'=>'文字已達上限50',
			'cid.required'=>'未選擇分類',
			'cid.numeric'=>'錯誤',
			'info.required'=>'未輸入簡介',
			'info.max'=>'文字已達上限1000',
			'pic.required'=>'未上傳封面圖',
			'img.required'=>'未上商品圖片',
			'price.required'=>'請輸入商品價格',
			'price.numeric'=>'價格為字數且為整數',
			'price.integer'=>'價格為字數且為整數',
			'number.required'=>'請輸入商品庫存',
			'number.numeric'=>'庫存為字數且為整數',
			'number.integer'=>'庫存為字數且為整數',
			'text.required'=>'未輸入詳細介紹',
			'text.max'=>'文字已達上限1000',
			'config.required'=>'未輸入配置訊息',
			'config.max'=>'文字已達上限1000',
		);
		$validator = validator::make($data,$ruler,$message);
		if($validator->passes()){
			unset($data['img']);
			$data['pic'] = 'public/upload/goods/pic/'.$data['pic'];
			$data['time'] = time();
			if($id = DB::table('goods')->insertgetid($data)){
				foreach($imgs as $v){
					$arr['goodsid'] = $id;
					$arr['img'] = 'public/upload/goods/img/'.$v;
					if(!DB::table('goodsimg')->insert($arr)){
						return back()->with('error','新增商品失敗');
					}
				}
				return redirect("admin/goods");
			}else{
				return back()->with('error','新增商品失敗');
			}
		}else{
			return back()->withInput()->withErrors($validator);
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
		$data = DB::select('select types.*,concat(path,id) as t from types order by t');
		foreach($data as $v){   //將二級及三級分類的名稱加上 - 符號
			$v->size = count(explode(',',$v->t))-2;  //計算t欄以逗號區分後有幾個並加入$data內
			$v->name = str_repeat('─ ',$v->size).$v->name;
		}
		$goods = DB::table('goods')->find($id);
		$goodsimg = DB::table('goodsimg')->get();
		return view('admin.goods.edit')->with('goods',$goods)->with('goodsimg',$goodsimg)->with('data',$data);
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
		
		$imgs = $request->input(['img']);
		
		$ruler = [
			'title'=>'required|max:50',
			'cid'=>'required|numeric',
			'info'=>'required|max:255',
			'price'=>'required|numeric|integer',
			'number'=>'required|numeric|integer',
			'text'=>'required|max:1000',
			'config'=>'required|max:255',
		];
		
		$message = array(
			'title.required'=>'未輸入標題',
			'title.max'=>'文字已達上限50',
			'cid.required'=>'未選擇分類',
			'cid.numeric'=>'錯誤',
			'info.required'=>'未輸入簡介',
			'info.max'=>'文字已達上限255',
			'price.required'=>'請輸入商品價格',
			'price.numeric'=>'價格為字數且為整數',
			'price.integer'=>'價格為字數且為整數',
			'number.required'=>'請輸入商品庫存',
			'number.numeric'=>'庫存為字數且為整數',
			'number.integer'=>'庫存為字數且為整數',
			'text.required'=>'未輸入詳細介紹',
			'text.max'=>'文字已達上限1000',
			'config.required'=>'未輸入配置訊息',
			'config.max'=>'文字已達上限255',
		);
		
		$validator = validator::make($data,$ruler,$message);
		if($validator->passes()){
			$olddata = DB::table('goods')->find($id);
			$oldimg = DB::table('goodsimg')->where('goodsid',$id)->get();
				if(!empty($data['pic'])){
					unlink($olddata->pic);
					$data['pic'] = 'public/upload/goods/pic/'.$data['pic'];
				}
				else{
					$data['pic'] = $olddata->pic;
				}
				if(!empty($data['img'][0])){
					foreach($oldimg as $v){
						unlink($v->img);
						DB::table('goodsimg')->where('goodsid',$id)->delete();
					}
				}
				unset($data['img']);
				$data['time'] = time();
				
			if(DB::table('goods')->where('id',$id)->update($data)){
				if(!empty($imgs[0])){
					foreach($imgs as $v){
						$arr['goodsid'] = $id;
						$arr['img'] = 'public/upload/goods/img/'.$v;
						if(!DB::table('goodsimg')->insert($arr)){
							return back()->with('error','新增商品失敗');
						}	
					}
				}
				
				return redirect("admin/goods");
			}else{
				return back()->with('error','新增商品失敗');
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
    public function destroy($id)  //刪除
    {
		if(DB::table('goods')->where('id',$id)->delete()){
			if(DB::table('goodsimg')->where('goodsid',$id)->delete()){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
    }
	
	public function uploadimg(Request $request)  //商品上傳
    {
		$allowext = array('jpg','jpeg','gif','bmp','png');
		foreach($_FILES['img']['type'] as $v){
			$ext = explode('/',$v);
			if(!in_array($ext[1],$allowext)){
				return 0;
			}
		}
		$data = $request->except(['_token']);
		if(count($data['img'])>0){
			for($i = 0;$i<=count($data['img'])-1;$i++){
				$name = $request->file('img')[$i]->getclientoriginalname();
				$pathname = $request->file('img')[$i]->move('public\upload\goods\img',$name);
				$path[] = $pathname->getPathname();
			}
			return $path;
		}else{
			return 0;
		}

    }
	
	public function uploadpic(Request $request)  //封面上傳
    {
		$ext = explode('/',$_FILES['pic']["type"]);
		$allowext = array('jpg','jpeg','gif','bmp','png');
		if(in_array($ext[1],$allowext)){
			$data = $request->except('_token');
			$name = $request->file('pic')->getclientoriginalname();
			$path = $request->file('pic')->move('public\upload\goods\pic',$name);
			return $path;
			if(file_exists($path)){
				return $path;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
    }
	
	public function cancelupload(Request $request)  //刪除以上傳圖片
    {
		$data = $request->except(['_token','_method']);
		$path = parse_url($data['img']);
		$unpath = str_replace("/lenovo/public/",'',$path['path']);
		if(file_exists($unpath)){
			unlink($unpath);
			return 1;
		}else{
			return 0;
		}

    }
	public function uploadchange(Request $request)
	{
		$data = $request->except(['_token']);
		$path = 'public/upload/goods/'.$data['path'].'/';
		$upload = explode(',',$data['arr']);
		if(count($upload)>0){
			for($i =0;$i<=count($upload)-1;$i++){
				if(file_exists($path.$upload[$i])){
					unlink("../".$path.$upload[$i]);
				}else{
					continue;
				}
			}
			return 1;
		}else{
			return 0;
		}
	}
	
}
