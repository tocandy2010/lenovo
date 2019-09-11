<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

use DB;

class AdsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$data = DB::table('ads')->paginate(5);
		$count = DB::table('ads')->count();
		return view('admin.sys.ads.index')->with('data',$data)->with('count',$count);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		
        return view('admin.sys.ads.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$request->flash();
		$data = $request->except(['_token']);
		
        $ruler = [
		'title'=>'required|max:100',
		'img'=>'required',
		'href'=>'required|active_url',
		'sort'=>'required|numeric',
		];
		
		$message = array(
		'title.required'=>'廣告名稱未填',
		'title.max'=>'廣告名稱為1~100個字',
		'img.required'=>'未上傳廣告圖片',
		'href.required'=>'未輸入廣告連結',
		'href.active_url'=>'無效的連結',
		'sort.required'=>'未輸入廣告順序',
		'sort.numeric'=>'廣告順序為一個數字'
		);
		
		$validator = validator::make($data,$ruler,$message);
		if($validator->passes()){
			$path = $request->file('img')->move('public\upload\ads\img',$this->filename('img').'.jpeg');
			$data['img'] = $path;
			$dh = opendir('public/upload/ads/show/');
			while($file = readdir($dh)){
				if($file == '.' || $file == '..' ){
					continue;
				}
				unlink('public/upload/ads/show/'.$file);
			}
			if(DB::table('ads')->insert($data)){
				return redirect('admin/sys/ads');
			}else{
				return 0;
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
    public function edit($id)
    {
		$data = DB::table('ads')->find($id);
        return view('admin.sys.ads.edit')->with('data',$data);
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
		$olddata = DB::table('ads')->find($id);
		$data = $request->except(['_token','_method']);
		$ruler = [
			'title'=>'required|max:100',
			'href'=>'required|active_url',
			'sort'=>'required|numeric',
		];
		
		$message = array(
			'title.required'=>'廣告名稱未填',
			'title.max'=>'廣告名稱為1~100個字',
			'href.required'=>'未輸入廣告連結',
			'href.active_url'=>'無效的連結',
			'sort.required'=>'未輸入廣告順序',
			'sort.numeric'=>'廣告順序為一個數字'
		);
		$validator = validator::make($data,$ruler,$message);
		if($validator->passes()){
			if(isset($data['img']) && !empty($data['img'])){
				$path = $request->file('img')->move('public\upload\ads\img',$this->filename('img').'.jpeg');
				$data['img'] = $path;
				unlink($olddata->img);
			}
			$dh = opendir('public/upload/ads/show/');
			while($file = readdir($dh)){
				if($file == '.' || $file == '..' ){
					continue;
				}
				unlink('public/upload/ads/show/'.$file);
			}
			if(DB::table('ads')->where('id',$id)->update($data)){
				return redirect('admin/sys/ads');
			}else{
				return back()->with('error',"修改操做失敗");
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
       if(DB::table('ads')->where('id',$id)->delete()){
		   return 1;
	   }else{
		   return 0;
	   }
    }
	
	public function upimg(Request $request)
    {
       $allowext = array('jpg','jpeg','gif','png','bmp');
	   $ext = explode('/',$_FILES['img']['type']);
	   if(in_array($ext[1],$allowext)){
		   $path = $request->file('img')->move('public\upload\ads\show',$this->filename('show').'.jpeg');
		   if(file_exists($path)){
			   return $path;
		   }else{
			   return 0;
		   }
	   }
    }
	
	private function filename($path){ //參數1 為public/upload/ads/
		$rand = "adcdefghijklinopqrstuvwxzy1234567890ABCDEFGHIJKLMNOPQRSTUVWZZY1234567890";
		$filename = substr(str_shuffle($rand),0,10);
		if(!file_exists('public/upload/ads/'.$path.'/'.$filename.'.jpeg')){
			return $filename;
		}else{
			return $this->filename();
		}
	}
}
