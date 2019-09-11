<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use DB;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		return view('admin.sys.config.index');
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
        $arr = $request->except(['_token']);
		
		if(isset($arr['img'])&&($arr['oldimg']!==$arr['img'])){
			$path = $request->file('img')->move('public\upload\config',$request->file('img')->getclientoriginalname());
			$arr['img'] = $path->getPathname();
			unlink($arr['oldimg']);
		}else{
			$arr['img'] = $arr['oldimg'];
		}		
		unset($arr['oldimg']);
		if(file_exists($arr['img'])){
			$data ="<?php \r\n return ".var_export($arr,true)." \r\n?>";
			file_put_contents('../config/web.php',$data);	
			return back();
		}else{
			return back()->with('error','操作失敗');
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
        //
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
        //
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
