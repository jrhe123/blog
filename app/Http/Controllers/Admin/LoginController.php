<?php

namespace App\Http\Controllers\Admin;
use App\Http\Model\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;

require_once 'resources/org/code/Code.class.php';

class LoginController extends CommonController
{
    /*登录*/
    public function login()
    {
        /*验证是否post提交*/
        if($input = Input::all()){
            $code = new \Code;
            $_code = $code->get();
            if(strtoupper($input['code'])!=$_code){
                return back()->with('msg','验证码错误');
            }
            /*当前只有admin用户*/
            $user = User::first();
            if($user->user_name != $input['user_name']
                || Crypt::decrypt($user->user_pass) != $input['user_pass']){
                return back()->with('msg','用户名或者密码错误！');
            }
            /*session保存用户信息*/
            session(['user'=>$user]);
            return redirect('admin');

        }else {
            return view('admin.login');
        }
    }

    /*验证码*/
    public function code()
    {
        $code = new \Code;
        $code->make();
    }

    /*退出登录*/
    public function quit()
    {
        session(['user'=>null]);
        return redirect('admin/login');
    }



}
