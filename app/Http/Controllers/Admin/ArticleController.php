<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Article;
use App\Http\Model\Category;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ArticleController extends CommonController
{

    //get.admin/article
    /*全部文章列表*/
    public function index()
    {
        /*数据分页*/
        $data = Article::orderBy('art_id','desc')->paginate(10);
        return view('admin.article.index',compact('data'));
    }

    //get.admin/article/create
    /*添加文章*/
    public function create()
    {
        $data = (new Category())->tree();
        return view('admin.article.add',compact('data'));
    }

    //post.admin/article
    /*添加文章，处理提交表单*/
    public function store()
    {
        $input = Input::except('_token');
        $input['art_time'] = time();

        //定义验证规则
        $rules = [
            'art_title' => 'required',
            'art_content' => 'required',
        ];
        //定义错误返回信息
        $message = [
            'art_title.required' => '文章名称不能为空！',
            'art_content.required' => '文章内容不能为空！',
        ];
        //验证模型
        $validator = Validator::make($input, $rules, $message);

        if ($validator->passes()) {
            /*验证通过，处理数据库*/
            $result = Article::create($input);
            if ($result) {
                return redirect('admin/article');
            } else {
                return back()->with('errors', '数据填充失败，请稍后重试！');
            }
        } else {
            /*输出错误信息*/
            return back()->withErrors($validator);
        }
    }

    //get.admin/article/{article}/edit
    /*编辑文章*/
    public function edit($art_id)
    {
        $data = (new Category())->tree();
        $field = Article::find($art_id);
        return view('admin.article.edit',compact('data','field'));
    }

    //put.admin/article/{article}
    /*更新文章*/
    public function update($art_id)
    {
        $input = Input::except('_method','_token');
        $result = Article::where('art_id',$art_id)->update($input);
        if($result){
            return redirect('admin/article');
        }else{
            return back()->with('errors','文章信息更新失败，请稍后重试！');
        }
    }

    //get.admin/article/{article}
    public function show()
    {

    }

    //delete.admin/article/{article}
    public function destroy($art_id)
    /*删除文章*/
    {
        $result = Article::where('art_id',$art_id)->delete();
        if($result){
            $data = [
                'status'=>1,
                'msg'=>'文章删除成功！',
            ];
        }else{
            $data = [
                'status'=>0,
                'msg'=>'文章删除失败，请稍后重试！',
            ];
        }
        return $data;
    }
}
