<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Crypt;

use DB;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$types = array('code'=>'訂單編號','account'=>'會員帳號','tel'=>'手機');  //搜索項目
		$search = $request->only(['types','search']);
		$type = isset($search['types'])&&!empty($search['types'])?$search['types']:false;
		$searchs = isset($search['search'])&&!empty($search['search'])?$search['search']:false;
		if($this->search($search,$types)){
			$data = DB::table('orders')
			->where($search['types'],$search['search'])
			->orderby('time','desc')
			->select('orders.code','orders.statu','orders.address','orders.money','orders.method','orders.time','users.account','users.tel','users.email','ordersgstatus.status')
			->join('users','orders.uid','=','users.account')
			->join('ordersgstatus','orders.statu','=','ordersgstatus.number')
			->get()->groupby('code');
		}else{
			$data = DB::table('orders')->select('orders.code','orders.statu','orders.address','orders.money','orders.method','orders.time','users.account','users.tel','users.email','ordersgstatus.status')
			->orderby('time','desc')
			->join('users','orders.uid','=','users.account')
			->join('ordersgstatus','orders.statu','=','ordersgstatus.number')
			->get()->groupby('code');
		}
		$arr = array();
		foreach($data as $k=>$v){
			foreach($v as $key=>$value){
				$arr[$k] = $value;
			}
		}
		$count = count($data);
		return view('admin.orders.index')->with('arr',$arr)->with('count',$count)->with('types',$types)->with('type',$type)->with('searchs',$searchs);
    }
	
	protected function search($search,$types)
	{
		if(!isset($search['types'])||empty($search['types'])){
			return false;
		}
		if(!array_key_exists($search['types'],$types)){
			return false;
		}
		if(!isset($search['search'])||empty($search['search'])){
			return false;
		}
		return true;
		
	}
	
	public function list()  //訂單清冊
	{	
		if(isset($_GET['orders']) && !empty($_GET['orders'])){
			$data = DB::table('orders')->where('code',$_GET['orders'])->select('orders.code','orders.price','orders.number','goods.title','goods.pic')
			->join('goods','orders.gid','=','goods.id')
			->get();
			$count = count($data);
			$total = array('number'=>0,'price'=>0);
			$orders = DB::table('orders')->where('code',$_GET['orders'])->get();
			return view('admin.orders.list')->with('data',$data)->with('count',$count)->with('total',$total)->with('orders',$_GET['orders']);
		}else{
			return redirect('admin/orders');
		}
		
	}
	public function ordersgstatus(Request $request)  //訂單狀態修改(如果POST 請求存在則修改 否則展示修改頁面)
	{	
		if($_POST){			
			$data = $request->except(['_token']);
			if(DB::update('update orders set statu = '.$data['statu'].' where code = "'.$data['code'].'"' )){
				return redirect('admin/orders');
			}else{
				return back()->with('error','修改失敗');
			}
		}else{
			$statu = $request->input('status');
			$code = $request->input('code');
			if(isset($statu) && !empty($statu) || isset($code) && !empty($code)){
				$status = DB::table('ordersgstatus')->get();
				return view('admin.orders.edit')->with('status',$status); 
			}else{
				return redirect('admin/orders');
			}
		}
	}	
}
