<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

//使用類
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Crypt;

use DOMDocument;
use DOMXPATH;
use DB;
use Mail;

//後台首頁控制器
class RegController extends Controller
{	
    public function index()
	{		
		return view('home.Reg.index');
	}
	
	public function check(Request $request)  //註冊訊息驗證
	{
		$request->flash();
		$data = $request->except(['_token']);
		$ruler = [
			'account'=>'required|unique:users,account|alpha_num||min:6|max:50',
			'pass'=>'required|alpha_num|min:6|max:50',
			'repass'=>'required|same:pass',
			'vcode'=>'required',
			'name'=>'required',
			'tel'=>'required|min:10|max:10',
			'email'=>'required|unique:users,email|email',
			'address'=>'required'
		];
		
		$message = array(
			'account.required'=>'未輸入帳號',
			'account.alpha_num'=>'帳號輸入不合法文字',
			'account.unique'=>'帳號已被使用',
			'account.min'=>'帳號長度不足',
			'account.maz'=>'帳號過長',
			'pass.required'=>'未輸入密碼',
			'pass.min'=>'密碼長度不足',
			'pass.max'=>'密碼過長',
			'pass.alpha_num'=>'密碼輸入不合法文字',
			'repass.required'=>'未輸入確認密碼',
			'repass.same'=>'確認密碼與密碼不相同',
			'vcode.required'=>'未輸入驗證碼',
			'name.required'=>'未輸入姓名',
			'tel.required'=>'未輸入電話',
			'tel.min'=>'錯誤的電話號碼',
			'tel.max'=>'錯誤的電話號碼',
			'email.required'=>'未輸入信箱',
			'email.email'=>'請輸入一個信箱',
			'email.unique'=>'信箱已被使用',
			'address.required'=>'未輸入地址'
		);
		
		$validator = validator::make($data,$ruler,$message);
		
		if($validator->passes()){
			if($data['vcode'] !== session('regvcode')){
				return back()->with('vcodeerror','驗證碼錯誤');						
			}
			if(!$this->checkpass($data['pass'])){
				return back()->with('pass','密碼必須包含各一個大寫和小寫英文');
			}
			$data['pass'] = Crypt::encrypt($data['pass']);
			unset($data['repass']);
			unset($data['vcode']);
			$data['status'] = 2;
			$data['time'] = time();
			$data['token'] = $this->token();
			if($id = DB::table('users')->insertGetID($data)){	
				$this->regmail($id,$data['name'],$data['email'],$data['token']);  //發送註冊驗證郵件
			}else{
				return back()->with('regerror','註冊失敗');
			}
			$hrefemail = explode('@',$data['email'])[1];  //
			return redirect('home/login')->with('wait','註冊成功,請至註冊信箱開通帳號')->with('hrefemail',$hrefemail);
			
		}else{
			return back()->withInput()->withErrors($validator);
		}
	}
	
	public function vcode()  //產生驗證碼
    {
		$str = "abcdefghijkmnpqrstuvwxyz23456789abcdefghijkmnpqrstuvwxyz23456789ABCDEFGHJKLMNPQRSTUVWXYZ";
		$vcode = substr(str_shuffle($str),0,4);
		session(['regvcode'=>$vcode]);

		$ima = imagecreatetruecolor(60,25);
		$imb = imagecreatetruecolor(60,25);
		$imbbig = imagecreatetruecolor(80,40);

		$agray = imagecolorallocate($ima,rand(200,255),rand(200,255),rand(200,255));
		$bgray = imagecolorallocate($imb,200,200,200);
		$blueword = imagecolorallocate($ima,0,0,0);
		imagefill($ima,0,0,$agray);
		imagefill($imb,0,0,$bgray);
		

		imagestring($ima,5,10,4,$vcode,$blueword);
			$offset = rand(-3,3); //最大波動像素
			$round = rand(-2,2); //波動週期
		for($i = 0;$i<60;$i++){
			$posy = round(sin($i * $round * 2 * M_PI / 60) * $offset);   //文字扭曲公式
			imagecopy($imb,$ima,$i,$posy,$i,0,1,25);
		}
		imagecopyresampled($imbbig,$imb,0,0,0,0,80,40,60,25);

		ob_clean();
		header("content-type:image/jpeg");
		imagejpeg($imbbig);
		imagedestroy($ima);
		imagedestroy($imb);
		imagedestroy($imbbig);
    }
	
	
	public function regmail($id,$name,$email,$token){
		/*Mail::raw('親愛的用戶歡迎註冊本商城,請點擊連結已開通帳號',function($message){
			$message->to('tocandy2010@yahoo.com.tw');  //收件者
			$message->subject('商城註冊會員驗證'); //標題
		});*/		
		Mail::send('home.reg.regmail',['id'=>$id,'name'=>$name,'email'=>$email,'token'=>$token,'email'=>$email],function($message)use($email){
			$message->to($email);  //收件者
			$message->subject('商城註冊會員驗證'); //標題
		});
	}
	
	protected function token(){  //產生不重複token
		$rean = 'abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHJKLMNPQRSTUVWXYZ_';
		$token = substr(str_shuffle($rean),0,50);
		$token = DB::table('users')->where('token',$token)?$token:$this->token();
		return $token;
	}
	
	public function open($id,$token)  //驗證郵件連結後變更狀態及重新產生token
	{
		if(DB::table('users')->where([['id',$id],['token',$token]])->first()){
			$newtoken = $this->token();
			 if($data= DB::table('users')->where('id',$id)->update(['token'=>$newtoken,'status'=>1])){
				 return redirect('home/login');
			 }else{
				 echo '很抱歉您的驗證碼已過期';
			 }
		}else{
			echo '很抱歉您的驗證碼已過期';
		}
	}
	
	protected function checkpass($pass)  //檢查密碼是否有符合大小寫
	{
		$upword = 0;
		$lowerword = 0;
		$word = 'abcdefghijklmnopqrstuvwxyz';
		$lower = str_split($word);
		$up = str_split(strtoupper($word));
		$pass = str_split($pass);
		foreach($pass as $v){
			if(in_array($v,$lower)){
				$lowerword+=1;
			}
			if(in_array($v,$up)){
				$upword+=1;
			}
		}
		if($upword >0 && $lowerword > 0){
			return true;
		}else{
			return false;
		}
	}
}