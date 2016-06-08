<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CommonController extends Controller
{
    /*图片上传*/
    public function upload()
    {
        /*读取文件里信息，file方法*/
        $file = Input::file('Filedata');
        if($file->isValid()){
            /*上传文件的后缀*/
            $extension = $file->getClientOriginalExtension();
            /*移动临时文件，并重命名*/
            $newName = date('YmdHis').mt_rand(100,999).'.'.$extension;
            $path = $file->move(base_path().'/uploads',$newName);
            $filepath = 'uploads/'.$newName;
            return $filepath;
        }
    }
}
