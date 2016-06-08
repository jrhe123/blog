<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Model\Category;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class CategoryController extends CommonController
{
    //get.admin/category
    /*全部分类列表*/
    public function index()
    {
        /*静态static方法输出*/
        //$categorys = Category::tree();

        /*传统输出*/
        $categorys = (new Category())->tree();
        return view('admin.category.index')->with('data',$categorys);
    }

    public function changeOrder()
    {
        /*Ajax post 接收表单*/
        $input = Input::all();
        $cate = Category::find($input['cate_id']);
        $cate->cate_order = $input['cate_order'];
        $result = $cate->update();
        if($result){
            $data = [
                'status'=>1,
                'msg'=>'分类排序更新成功！',
            ];
        }else{
            $data = [
                'status'=>0,
                'msg'=>'分类排序更新失败，请稍后重试！',
            ];
        }
        return $data;
    }

    //get.admin/category/create
    /*添加分类*/
    public function create()
    {
        $data = Category::where('cate_pid',0)->get();
        return view('admin.category.add',compact('data'));
    }

    //post.admin/category
    /*添加分类，处理提交表单*/
    public function store()
    {
        /*验证post表单*/
        if($input = Input::all()){
            //定义验证规则
            $rules = [
                'cate_name'=>'required',
            ];
            //定义错误返回信息
            $message = [
                'cate_name.required'=>'分类名称不能为空！',
            ];
            //验证模型
            $validator = Validator::make($input,$rules,$message);

            if($validator->passes()){
                /*验证通过，处理数据库*/
                $result = Category::create($input);
                if($result){
                    return redirect('admin/category');
                }else{
                    return back()->with('errors','数据填充失败，请稍后重试！');
                }
            }else{
                /*输出错误信息*/
                return back()->withErrors($validator);
            }
        }else{
            return view('admin.category.add');
        }

    }

    //get.admin/category/{category}/edit
    /*编辑分类*/
    public function edit($cate_id)
    {
        $field = Category::find($cate_id);
        $data = Category::where('cate_pid',0)->get();
        return view('admin.category.edit',compact('field','data'));
    }

    //put.admin/category/{category}
    /*更新分类*/
    public function update($cate_id)
    {
        $input = Input::except('_token','_method');
        $result = Category::where('cate_id',$cate_id)->update($input);
        if($result){
            return redirect('admin/category');
        }else{
            return back()->with('errors','分类信息更新失败，请稍后重试！');
        }
    }

    //get.admin/category/{category}
    /*显示单个分类信息*/
    public function show()
    {

    }

    //delete.admin/category/{category}
    /*删除单个分类*/
    public function destroy($cate_id)
    {
        $result = Category::where('cate_id',$cate_id)->delete();
        /*子分类往上一级*/
        Category::where('cate_pid',$cate_id)->update(['cate_pid'=>0]);
        if($result){
            $data = [
                'status'=>1,
                'msg'=>'分类删除成功！',
            ];
        }else{
            $data = [
                'status'=>0,
                'msg'=>'分类删除失败，请稍后重试！',
            ];
        }
        return $data;
    }




}
