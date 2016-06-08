<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Navs;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class NavsController extends CommonController
{
    //get.admin/navs
    /*全部友情自定义导航列表*/
    public function index()
    {
        $data = Navs::orderBy('nav_order','asc')->get();
        return view('admin.navs.index',compact('data'));
    }

    public function changeOrder()
    {
        /*Ajax post 接收表单*/
        $input = Input::all();
        $navs = Navs::find($input['nav_id']);
        $navs->nav_order = $input['nav_order'];
        $result = $navs->update();
        if($result){
            $data = [
                'status'=>1,
                'msg'=>'自定义导航排序更新成功！',
            ];
        }else{
            $data = [
                'status'=>0,
                'msg'=>'自定义导航排序更新失败，请稍后重试！',
            ];
        }
        return $data;
    }

    //get.admin/navs/create
    /*添加自定义导航*/
    public function create()
    {
        return view('admin.navs.add');

    }

    //post.admin/navs
    /*添加自定义导航，处理提交表单*/
    public function store()
    {
        $input = Input::except('_token');
        //定义验证规则
        $rules = [
            'nav_name' => 'required',
            'nav_url' => 'required',
        ];
        //定义错误返回信息
        $message = [
            'nav_name.required' => '自定义导航名称不能为空！',
            'nav_url.required' => '自定义导航URL不能为空！',
        ];
        //验证模型
        $validator = Validator::make($input, $rules, $message);

        if ($validator->passes()) {
            /*验证通过，处理数据库*/
            $result = Navs::create($input);
            if ($result) {
                return redirect('admin/navs');
            } else {
                return back()->with('errors', '自定义导航填充失败，请稍后重试！');
            }
        } else {
            /*输出错误信息*/
            return back()->withErrors($validator);
        }

    }

    //get.admin/navs/{navs}/edit
    /*编辑自定义导航*/
    public function edit($nav_id)
    {
        $field = Navs::find($nav_id);
        return view('admin.navs.edit',compact('field'));
    }

    //put.admin/navs/{navs}
    /*更新自定义导航*/
    public function update($nav_id)
    {
        $input = Input::except('_token','_method');
        $result = Navs::where('nav_id',$nav_id)->update($input);
        if($result){
            return redirect('admin/navs');
        }else{
            return back()->with('errors','自定义导航信息更新失败，请稍后重试！');
        }
    }

    //get.admin/navs/{navs}
    public function show()
    {

    }

    //delete.admin/navs/{navs}
    /*删除自定义导航*/
    public function destroy($nav_id)
    {
        $result = Navs::where('nav_id',$nav_id)->delete();
        if($result){
            $data = [
                'status'=>1,
                'msg'=>'自定义导航删除成功！',
            ];
        }else{
            $data = [
                'status'=>0,
                'msg'=>'自定义导航删除失败，请稍后重试！',
            ];
        }
        return $data;
    }
}
