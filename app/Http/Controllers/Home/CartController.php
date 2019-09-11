<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

use DB;
use DOMDocument;
use DOMXPATH;
use Session;


class CartController extends Controller
{	
    public function index()
	{
		$totle = 0;  //購物車總價
		$count = 0;
		if(Session::has('shop')){
			$shop = session('shop');
			$data = DB::table('goods')->wherein('id',array_keys($shop))->orderby('price','desc')->get();
			foreach($shop as $k=>$v){
				foreach($data as $value){
					if($k == $value->id){
					$value->num = $v['goodsnumber'];
					}
				}					
			}
			$totle = $this->carpricetotle();
			$count = count($data);
			return view('home.cart.cart')->with('data',$data)->with('totle',$totle)->with('count',$count);
		}else{
			$nogoods = '購物車目前沒有任何商品';
		}
		return view('home.cart.cart')->with('nogoods',$nogoods)->with('totle',$totle)->with('count',$count);
	}
	
	public function addcart(Request $request)  //購物車內有相同商品的數量會合併
	{
		
		$data = $request->except('_token');
		$ruler = [
			'id'=>'Numeric|min:1',
			'goodsnumber'=>'Numeric|min:1'
		];
		
		$message = array(
			'id.numeric'=>'錯誤',
			'id.min'=>'錯誤',
			'goodsnumber.numeric'=>'請輸入數字',
			'goodsnumber.min'=>'最小購買量為1'
		);
		
		$validator = validator::make($data,$ruler,$message);
		if($validator->passes()){
			$goods = DB::table('goods')->where('id',$data['id'])->first();
			if(!$goods){
				return 0;
			}
			if(((int)$data['goodsnumber']) >$goods->number){
				return 2;
			}
			$arr = array();
			//$arr[$data['id']] = $data['goodsnumber'];
			$arr[$data['id']] = array('goodsnumber'=>$data['goodsnumber'],'price'=>$goods->price);
			$this->usecar($arr); //購物車商品計算
			return 1;
		}else{
			return $validator->getMessageBag()->getMessageBag();
		}
	}
	
	protected function usecar($goods)  //購物車商品計算
	{
		$data = session('shop');
		if($data){
			foreach($goods as $k=>$v){
				if(array_key_exists($k,$data)){
					$data[$k]['goodsnumber']+=$v['goodsnumber'];
				}else{
					$data[$k]['goodsnumber']=$v['goodsnumber'];					
				}
				$data[$k]['price']=$v['price'];
			}
			session(['shop'=>$data]);
			return true;
		}else{
			session(['shop'=>$goods]);
			return true;
		}
	}
	
	public function gadd(Request $request){  //購買頁面數量增加
		$data = $request->except('_token');
		$goods = DB::table('goods')->where('id',$data['id'])->first();
		$shop = $request->session()->get('shop');
		if(array_key_exists($data['id'],$shop)){
			if($goods->number <=$data['gadd']){
				$shop[$data['id']]['goodsnumber'] = $goods->number;					
				$request->session()->put('shop',$shop);
				return array('totle'=>$this->carpricetotle(),
				'money'=>$shop[$data['id']]['price']*$shop[$data['id']]['goodsnumber']);  //計算單項商品加總後金額
			}else{
				$shop[$data['id']]['goodsnumber']=$data['gadd'];
				$request->session()->put('shop',$shop);
				return array('totle'=>$this->carpricetotle(),
				'money'=>$shop[$data['id']]['price']*$shop[$data['id']]['goodsnumber']);
			}			
		}else{
			return 0;
		}
	}
	
	public function gless(Request $request)  //購買頁面數量減少
	{  
		$data = $request->except('_token');
		$shop = $request->session()->get('shop');
		if(array_key_exists($data['id'],$shop)){
			if(0 >=$data['gless']){
				$shop[$data['id']]['goodsnumber'] =1;					
				$request->session()->put('shop',$shop);
				return array('totle'=>$this->carpricetotle(),
				'money'=>$shop[$data['id']]['price']*$shop[$data['id']]['goodsnumber']);
			}else{
				$shop[$data['id']]['goodsnumber']=$data['gless'];
				$request->session()->put('shop',$shop);
				return array('totle'=>$this->carpricetotle(),
				'money'=>$shop[$data['id']]['price']*$shop[$data['id']]['goodsnumber']); //計算單項商品加總後金額
			}			
		}else{
			return 0;
		}
	}
	
	public function changegoods(Request $request)  //購物車內的商品onchange後判斷數量寫入購物車內
	{
		$data = $request->except('_token');
		$shop = $request->session()->get('shop');
		if(array_key_exists($data['id'],$shop)){
			$shop[$data['id']]['goodsnumber'] = $data['number'];
			$request->session()->put('shop',$shop);
			return array('totle'=>$this->carpricetotle(),
			'money'=>$shop[$data['id']]['price']*$shop[$data['id']]['goodsnumber']);
		}else{
			return 0;
		}
	}
	
	public function del(Request $request)   //刪除購物車單件商品
	{
		$data = $request->except('_token');
		$shop = $request->session()->get('shop');
		foreach($shop as $k=>$v){
			if($data['id'] == $k){
				unset($shop[$k]);
				$request->session()->put('shop',$shop);
				return array('totle'=>$this->carpricetotle(),'count'=>count($shop));
			}
		}
		return 0;
	}
	
	public function delall(Request $request)  //清空購物車所有商品
	{
		$request->session()->forget('shop');
		if(null == session('shop')){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function carpricetotle(){  //計算購物車內購買商品總金額
		$shop = session('shop');
		$totle = 0;
		if(isset($shop)&&!empty($shop)){
			foreach($shop as $v){
				$totle  += $v['goodsnumber']*$v['price'];
			}
			return $totle;
		}else{
			return 0;
		}
	}
	
	public function checkcar(Request $request){  //跳轉道結帳頁面
		return redirect('home/cart/settlement');
	}
	
	public function settlement(Request $request)  //加載結帳頁面
	{
		$goods = array();
		$totle = 0;
		$count = 0;
		$data = $request->session()->get('shop');
		
		foreach($data as $k=>$v){
			$goods[$k] = DB::table('goods')->where('id',$k)->first();
			$goods[$k]->money = $v['price']*$v['goodsnumber'];
			$goods[$k]->goodsnumber =$v['goodsnumber'];
			$totle += $v['price']*$v['goodsnumber'];
		}
		$count = count($goods);
		$user = DB::table('users')->where('id',session('lenovoHomeUserInfo.id'))->first();
		$city = $this->city();
		return view('home.cart.checkout')->with('goods',$goods)->with('count',$count)->with('totle',$totle)
		->with('user',$user)->with('city',$city);
	}
	
	public function checkorder(Request $request) //寫入訂單並扣除商品數量
	{
		$order = array();
		$data = $request->except('_token');
		if($data['method'] === '1' || $data['method'] === '2'){  //送貨方式
			$shop = $request->session()->get('shop');  //購物車			
			$user = $request->session()->get('lenovoHomeUserInfo');
			$userinfo = DB::table('users')->where('id',$user['id'])->first();
			if($userinfo->status!==1){  //驗證使用者帳號與狀態
					return back()->with('error','尚未登入會員或已遭停權');
				}
			$code = $this->ordercode();			
			if($data['method'] === '1'){
				$method = '貨到付款';
				if(isset($data['changeaddr'])&&!empty($data['changeaddr'])){
					$address = $data['changeaddr'];
				}else{					  
					$address = $userinfo->address;//如果沒有修改則使用註冊地址
				}
				
			}
			if($data['method'] === '2'){
				$method = '超商取貨';
				$sendaddress = $request->session()->get('sendaddress');  //送貨地址
				if(!isset($sendaddress)||empty($sendaddress)){
				return back()->with('error','未選擇商店門市');
				}
				$address = '門市代碼:'.$sendaddress['number'].',門市:'.$sendaddress['name'].'門市,地址:'.$sendaddress['address']; 
			}
			$time = time();
			foreach($shop as $k=>$v){
				$order['code'] = $code;
				$order['uid'] = $user['account'];
				$order['gid'] = $k;
				$order['price'] = $v['price']*$v['goodsnumber'];
				$order['number'] = $v['goodsnumber'];
				$order['time'] = $time;
				$order['money'] = 0;
				$order['address'] = $address;
				$order['method'] = $method;
				$order['statu'] = '1';
				if(!$goods = DB::table('goods')->where('id',$k)->first()){
					return back()->with('error','購買失敗');
				}
				if(!($v['goodsnumber'] < $goods->number)){  //檢查商品庫存是否足夠
					return back()->with('error','商品存貨不足');
				}
				
				if(!DB::table('orders')->insert($order)){  //將商品寫入資料庫
					return back()->with('error','購買失敗');
				}
				if(!DB::table('goods')->where('id',$k)->update(['number'=>($goods->number - $v['goodsnumber'])])){ //修改商品庫存
					return back()->with('error','購買失敗');
				}
				$request->session()->forget('shop');
				$request->session()->forget('sendaddress');				
			}
			return redirect('http://localhost/lenovo/public/home/orders');
		}else{
			return back()->with('error','未選擇寄送方式');
		}
	}
	
	public function orders(Request $request) //查看訂單
	{
		$ordersgstatus = DB::table('ordersgstatus')->get();
		$statusnumber = array();//將得到的狀態表放入此陣列
		foreach($ordersgstatus as $v){  //獲取所有狀態表
			$statusnumber[] = $v->number;
		}
		$status = isset($_GET['status'])&&!empty($_GET['status'])?$_GET['status']:'1';  //查詢訂單$_GET是否存在
		$status = in_array($status,$statusnumber)?$status:'1'; //查詢訂單$_GET的數字是否存在

		$user = $request->session()->get('lenovoHomeUserInfo');
		$data = DB::table('orders')->where('uid',$user['account'])->where('statu',$status)  //合併orders表和ordersgstatus
		->orderby('time','desc')
		->select('orders.*','ordersgstatus.status')->orderby('time','desc')
		->join('ordersgstatus','orders.statu','=','ordersgstatus.number')
		->paginate(15);
		$arr = array();
		$totle = array();
		foreach($data as $k=>$v){ //計算同一訂單商品數量及訂單資訊
			if(array_key_exists($v->code,$arr)){
				$arr[$v->code]->count+=1;
				if($v->comment === 1){  //判斷同訂單商品是否已經評價 如果$arr[$v->code]>0代表還有商品沒有平價
					$arr[$v->code]->comment+=1;
				}
			}
			if(!array_key_exists($v->code,$arr)){
				$v->count = 1;
				$arr[$v->code] = $v;
			}			
		}
		foreach($data as $key=>$value){  //計算訂單商品總價
				if(array_key_exists($value->code,$totle)){
					$totle[$value->code]['totle']+=$value->price*$value->number;
				}else{
					$totle[$value->code]['totle'] = $value->price*$value->number;
				}
		}
		$area = array_merge_recursive($totle,$arr);  //合併陣列($arr,$totle)
		$count = count($arr);  //計算訂單數量
		return view('home.cart.orders')->with('count',$count)->with('arr',$area)->with('ordersgstatus',$ordersgstatus)
		->with('status',$status)->with('data',$data);
	}
	
	public function ordersgoods($code){  //訂單內的商品
		$goods = DB::table('orders')->where('code',$code)
		->select('orders.*','goods.pic','goods.title')
		->join('goods','orders.gid','=','goods.id')
		->get();
		$count = count($goods); //商品數量
		$totle = 0;
		foreach($goods as $v){  //訂單總價
			$totle +=$v->price;
		}
		return view('home.cart.ordersgoods')->with('goods',$goods)->with('count',$count)->with('totle',$totle);
	}
	
	protected function ordercode(){  //產生不重複訂單編號
		$rean = 'abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHJKLMNPQRSTUVWXYZ_';
		$code = substr(str_shuffle($rean),0,15);
		$token = !DB::table('orders')->where('code',$code)->first()?$code:$this->ordercode();
		return $code;
	}
	
	protected function city()  //找所有城市
	{
		$dom = new DOMdocument("1.0","utf-8");

		$dom->load("public/address/taiwan.xml");

		$xpath = new DOMXPATH($dom);

		$sql = "//Col2[@name='City']";
		$res = $xpath->query($sql);
		$city = array();
		foreach($res as $v){  //找所有城市
			if(in_array($v->nodeValue,$city)){
				continue;
			}else{
				$city[] = $v->nodeValue;
			}
			
		}	
		return $city;
		/*echo $res->length;
		echo $res->item(4)->nodeValue."<br/>";
		echo $res->item(4)->nextSibling->nodeValue."<br/>";
		echo $res->item(4)->nextSibling->nextSibling->nodeValue;
		*/
	}
	
	public function town(Request $request)  //找所有鄉鎮市區
	{
		$city = $request->input('city');
		$dom = new DOMdocument("1.0","utf-8");

		$dom->load("public/address/taiwan.xml");

		$xpath = new DOMXPATH($dom);

		$sql = "//row[Col2='".$city."']/Col3";
		$res = $xpath->query($sql);
		$town = array();
		foreach($res as $v){  
			if(array_key_exists($v->nodeValue,$town)){
				continue;
			}else{
				$town[$v->nodeValue] = "<option value ='$v->nodeValue' >".$v->nodeValue."</option>";
			}
		}
		array_unshift($town,"<option>鄉鎮市區</option>");
		return implode($town);
	}
	
	public function street(Request $request)  //找所有街道
	{
		$data = $request->except('token');
		$dom = new DOMdocument("1.0","utf-8");

		$dom->load("public/address/taiwan.xml");

		$xpath = new DOMXPATH($dom);

		$sql = "//row[Col2[text()='".$data['city']."'] and Col3[text()='".$data['town']."']]/Col4";
		$res = $xpath->query($sql);
		$street = array();
		foreach($res as $v){  
			if(array_key_exists($v->nodeValue,$street)){
				continue;
			}else{
				$street[$v->nodeValue] = "<option value ='$v->nodeValue' >".$v->nodeValue."</option>";
			}
		}
		array_unshift($street,"<option>道路</option>");
		return implode($street);
	}
	
	public function choseshop(Request $request)  //指定便利商店取貨店家
	{
		$data = $request->only(['shopadd','shopnumber']);  //超商代號.
		if($shopinfo = DB::table('shop711')->where('number',$data['shopnumber'])->first()){
			$arr = array('number'=>$shopinfo->number,'name'=>$shopinfo->name,'address'=>$shopinfo->address);
			$request->session()->put('sendaddress',$arr);
			return 1;
		}else{
			return 0;
		}			
	}
	
	public function updateaddr(Request $request){ //貨到付款修改送貨地點
		$data = $request->input('newaddr');
		$request->session()->put('sendaddress',array('address'=>$data));
		$newaddr = $request->session()->get('sendaddress');
		if(isset($newaddr)&&!empty($newaddr)){
			return 1;
		}else{
			return 0;
		}
	}
}
