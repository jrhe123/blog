<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Links;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class LinksController extends CommonController
{
    //get.admin/links
    /*全部友情链接列表*/
    public function index()
    {
        $data = Links::orderBy('link_order','asc')->get();
        return view('admin.links.index',compact('data'));
    }

    public function changeOrder()
    {
        /*Ajax post 接收表单*/
        $input = Input::all();
        $links = Links::find($input['link_id']);
        $links->link_order = $input['link_order'];
        $result = $links->update();
        if($result){
            $data = [
                'status'=>1,
                'msg'=>'友情链接排序更新成功！',
            ];
        }else{
            $data = [
                'status'=>0,
                'msg'=>'友情链接排序更新失败，请稍后重试！',
            ];
        }
        return $data;
    }

    //get.admin/links/create
    /*添加链接*/
    public function create()
    {
        return view('admin.links.add');

    }

    //post.admin/links
    /*添加链接，处理提交表单*/
    public function store()
    {
        $input = Input::except('_token');

        //定义验证规则
        $rules = [
            'link_name' => 'required',
            'link_url' => 'required',
        ];
        //定义错误返回信息
        $message = [
            'link_name.required' => '友情链接名称不能为空！',
            'link_url.required' => '友情链接URL不能为空！',
        ];
        //验证模型
        $validator = Validator::make($input, $rules, $message);

        if ($validator->passes()) {
            /*验证通过，处理数据库*/
            $result = Links::create($input);
            if ($result) {
                return redirect('admin/links');
            } else {
                return back()->with('errors', '友情链接填充失败，请稍后重试！');
            }
        } else {
            /*输出错误信息*/
            return back()->withErrors($validator);
        }

    }

    //get.admin/links/{links}/edit
    /*编辑链接*/
    public function edit($link_id)
    {
        $field = Links::find($link_id);
        return view('admin.links.edit',compact('field'));
    }

    //put.admin/links/{links}
    /*更新链接*/
    public function update($link_id)
    {
        $input = Input::except('_token','_method');
        $result = Links::where('link_id',$link_id)->update($input);
        if($result){
            return redirect('admin/links');
        }else{
            return back()->with('errors','友情链接信息更新失败，请稍后重试！');
        }
    }

    //get.admin/links/{links}
    public function show()
    {

    }

    //delete.admin/links/{links}
    /*删除链接*/
    public function destroy($link_id)
    {
        $result = Links::where('link_id',$link_id)->delete();
        if($result){
            $data = [
                'status'=>1,
                'msg'=>'友情链接删除成功！',
            ];
        }else{
            $data = [
                'status'=>0,
                'msg'=>'友情链接删除失败，请稍后重试！',
            ];
        }
        return $data;
    }
}
