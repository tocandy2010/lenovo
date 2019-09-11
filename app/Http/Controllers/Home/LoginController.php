<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

//使用類
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Crypt;

use DB;
use Cache;
use Mail;

//後台首頁控制器
class LoginController extends Controller
{	
    public function index()
	{		
		if(isset($_SERVER['HTTP_REFERER'])&&!empty($_SERVER['HTTP_REFERER'])){//進入登入畫面後記住上一個網址
			session(['prevPage'=>$_SERVER['HTTP_REFERER']]);
		}		
		return view('home.login.index');
	}
	
	public function check(Request $request)  //驗證登入
	{		
		
		$data = $request->except(['_token']);
		$ruler = [
			'account'=>'required|exists:users,account|alpha_num',
			'pass'=>'required|alpha_num',
			'vcode'=>'required'
		];
		
		$message = array(
			'account.required'=>'未輸入帳號',
			'account.alpha_num'=>'帳號輸入不合法文字',
			'account.exists'=>'無法識別此帳號，請再試一次',
			'pass.required'=>'未輸入密碼',
			'vcode.required'=>'未輸入驗證碼',
		);
		
		$validator = validator::make($data,$ruler,$message);		
		if($validator->passes()){
			
			if($data['vcode'] !== session('homevcode')){
				return back()->with('vcodeerror','驗證碼錯誤');						
			}
			$user = DB::table('users')->where(['account'=>$data['account']])->first();
			switch($user->status){
				case 0: return back()->with('accounterror','此帳號已遭停權');
						break;
				case 2: return back()->with('accounterror','尚未驗證此帳號，請至註冊信箱內點擊驗證');
						break;
				case 1: break;
				default:return back()->with('accounterror','錯誤!!!!');
			}						
			if($data['pass'] !== crypt::decrypt($user->pass)){
				return back()->with('passerror','密碼不正確，請再試一次');
			}
			isset($data['remember'])&&!empty($data['remember'])?Cache::put('remember',$data['account'],43200):Cache::forget('remember');
			session(['lenovoHomeUserInfo'=>['id'=>$user->id ,'account'=>$user->account,'name'=>$user->name]]); //設置session 判斷是否登入
			if(strpos(session('prevPage'),"/localhost/lenovo/public/home/login")){ //如果上一個網址中不是本網站則回首頁
				return redirect('home');
			}else{
				if(strpos(session('prevPage'),"/localhost/lenovo/public/home/reg")){
					return redirect('home');
				}else{	
					if(strpos(session('prevPage'),"/localhost/lenovo/public/home")){
						return redirect(session('prevPage'));
					}else{
						return redirect('home'); 
					}
				}
			}			
		}else{
			return back()->withInput()->withErrors($validator);
		}
	}

	public function vcode()  //產生驗證碼
    {
		$str = "abcdefghijkmnpqrstuvwxyz23456789abcdefghijkmnpqrstuvwxyz23456789ABCDEFGHJKLMNPQRSTUVWXYZ";
		$vcode = substr(str_shuffle($str),0,4);
		session(['homevcode'=>$vcode]);

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
	
	public function logout(Request $request)   //退出
	{ 
		$request->session()->forget('lenovoHomeUserInfo');		
		return redirect('http://localhost/lenovo/public/home');
	}
	
	public function forgetpass()  //加載忘記密碼頁面
	{
		return view('home.login.forgetpass');
	}
	
	public function forgetpasscheck(Request $request)  //驗證找回密碼表單資料
	{
		$data = $request->except(['_token']);
		$ruler = [
			'account'=>'required|exists:users,account|alpha_num',
			'email'=>'required',
			'tel'=>'required',
			'vcode'=>'required'
		];
		
		$message = array(
			'account.required'=>'未輸入帳號',
			'account.alpha_num'=>'帳號輸入不合法文字',
			'account.exists'=>'無法識別此帳號，請再試一次',
			'email.required'=>'未輸入註冊信箱',
			'tel.required'=>'未輸入手機',
			'vcode.required'=>'未輸入驗證碼',
		);
		
		$validator = validator::make($data,$ruler,$message);
		
		if($validator->passes()){
			if($data['vcode']!==session('homevcode')){
				return 2;
			}
			if( $user = DB::table('users')->where('account',$data['account'])->where('email',$data['email'])->where('tel',$data['tel'])->first()){
				$this->forgetpassmail($user->id,$user->name,$user->email,$user->token);
				return 1;
			}else{
				return 0;
			}
		}else{
			return $validator->getMessageBag()->getMessageBag();
		}
	}
	
	public function forgetpassmail($id,$name,$email,$token){  //發送找回密碼信		
		Mail::send('home.login.forgetpassmail',['id'=>$id,'name'=>$name,'email'=>$email,'token'=>$token,'email'=>$email],function($message)use($email){
			$message->to($email);  //收件者
			$message->subject('商城註冊會員密碼修改'); //標題
		});
	}
	
	public function savepass($id,$token)  //加載新密碼頁面
	{
		return view('home.login.savepass')->with('id',$id)->with('token',$token);
	}
	
	public function savepasscheck(Request $request)  //檢查新密碼並修改為新密碼
	{
		$data = $request->except('_token');
		$ruler = [
			'pass'=>'required|alpha_num|min:6|max:50',
			'repass'=>'required|same:pass',
		];
		
		$message = array(
			'pass.required'=>'未輸入密碼',
			'pass.alpha_num'=>'密碼輸入不合法文字',
			'pass.min'=>'密碼長度不足',
			'pass.max'=>'密碼超出長度限制50位數',
			'repass.required'=>'未輸入確認密碼',
			'repass.same'=>'2次密碼輸入不相同',
		);
		
		$validator = validator::make($data,$ruler,$message);
		
		if($validator->passes()){
			$user = DB::table('users')->where('id',$data['id'])->first();
			if($user->token !== $data['token']){
				return 2;
			}
			if(!$this->checkpass($data['pass'])){
				return 3;
			}
			$token = $this->token();
			$pass = Crypt::encrypt($data['pass']);
			if(DB::table('users')->where('id',$data['id'])->update(['token'=>$token,'pass'=>$pass])){
				return 1;
			}else{
				return 0;
			}
			
		}else{
			return $validator->getMessageBag()->getMessageBag();
		}
		
	}
	
	protected function token(){  //產生不重複token
		$rean = 'abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHJKLMNPQRSTUVWXYZ_';
		$token = substr(str_shuffle($rean),0,50);
		$token = !DB::table('users')->where('token',$token)->first()?$token:$this->token();
		return $token;
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
