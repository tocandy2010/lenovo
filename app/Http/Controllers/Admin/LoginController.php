<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Crypt;

use DB;

class LoginController extends Controller
{

    public function index()  //登入頁面
    {
       return view('admin.login');
    }
	
	public function check(Request $request)  //核對帳號訊息
    {
		$request->flash();
		$data = $request->except(['_token']);
				
		$ruler = [
			'name'=>'required|exists:admin,name|alpha_num',
			'pass'=>'required|alpha_num',
			'vcode'=>'required'
		];
		
		$message = array(
			'name.required'=>'未輸入帳號',
			'name.alpha_num'=>'帳號輸入不合法文字',
			'name.exists'=>'無法識別此帳號，請再試一次',
			'pass.required'=>'未輸入密碼',
			'vcode.required'=>'未輸入驗證碼',
		);
		
		$validator = validator::make($data,$ruler,$message);
		if($validator->passes()){
			if($data['vcode'] !== session('vcode')){
				return back()->with('vcodeerror','驗證碼錯誤');						
			}
			if(!$user = DB::table('admin')->where(['name'=>$data['name'],'status'=>'0'])->first()){
				return back()->with('nameerror','帳號錯誤，或已遭停權');
			}
			if($data['pass'] !== crypt::decrypt($user->pass)){
				return back()->with('passerror','密碼不正確，請再試一次');
			}
			session(['lenovoAdminUserInfo'=>['id'=>$user->id ,'name'=>$user->name]]);
			DB::table('admin')->where('name',$user->name)->update(['count'=>($user->count+=1),'lasttime'=>time()]);
			return redirect('admin');
		}else{
			return back()->withInput()->withErrors($validator);
		}
    }
	public function vcode()  //產生驗證碼
    {
		$str = "abcdefghijkmnpqrstuvwxyz23456789abcdefghijkmnpqrstuvwxyz23456789ABCDEFGHJKLMNPQRSTUVWXYZ";
		$vcode = substr(str_shuffle($str),0,4);
		session(['vcode'=>$vcode]);

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
	
	public function logout(Request $request){
		$request->session()->forget('lenovoAdminUserInfo');
		return redirect('admin/login');
	}

}
