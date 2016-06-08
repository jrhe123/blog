<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\User;

class IndexController extends CommonController
{
    /*左导航栏*/
    public function index()
    {
        return view('admin.index');
    }

    /*info信息*/
    public function info()
    {
        return view('admin.info');
    }

    /*修改密码*/
    public function pass()
    {
        /*验证post表单*/
        if($input = Input::all()){
            //定义验证规则
            $rules = [
                'password'=>'required|between:6,20|confirmed',
            ];
            //定义错误返回信息
            $message = [
                'password.required'=>'新密码不能为空！',
                'password.between'=>'新密码必须在6-20位之间！',
                'password.confirmed'=>'新密码和确认密码不一致！',
            ];
            //验证模型
            $validator = Validator::make($input,$rules,$message);

            if($validator->passes()){
                /*验证通过，处理数据库*/
                $user = User::first();
                $_password = Crypt::decrypt($user->user_pass);
                if($input['password_o'] == $_password){
                    $user->user_pass = Crypt::encrypt($input['password']);
                    $user->update();
                    return back()->with('errors','密码修改成功！');
                }else{
                    return back()->with('errors','原密码错误！');
                }
            }else{
                /*输出错误信息*/
                //dd($validator->errors()->all());
                return back()->withErrors($validator);
            }
        }else{
            return view('admin.pass');
        }
    }
}
