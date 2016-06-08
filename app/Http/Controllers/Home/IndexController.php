<?php

namespace App\Http\Controllers\Home;

use App\Http\Model\Article;
use App\Http\Model\Category;
use App\Http\Model\Links;
use App\Http\Model\Navs;

class IndexController extends CommonController
{
    public function index()
    {
        /*点击量最高的6篇文章(推荐)*/
        $hot = Article::orderBy('art_view','desc')->take(6)->get();
        /*图文列表5篇（带分页）*/
        $data = Article::orderBy('art_time','desc')->paginate(5);
        /*友情链接*/
        $links = Links::orderBy('link_order','asc')->get();
        /*网站配置项*/

        return view('home.index',compact('hot','data','new','links','hot_2'));
    }

    /*前台文章列表页*/
    public function cate($cate_id)
    {
        /*分类信息*/
        $field = Category::find($cate_id);
        /*图文列表5篇（带分页）*/
        $data = Article::where('cate_id',$cate_id)->orderBy('art_time','desc')->paginate(4);
        /*当前分类的子分类*/
        $submenu = Category::where('cate_pid',$cate_id)->get();
        /*查看次数自增*/
        Category::where('cate_Id',$cate_id)->increment('cate_view',1);
        return view('home.list',compact('field','data','submenu'));
    }

    /*文章页*/
    public function article($art_id)
    {
        /*关联查询，关联分类信息*/
        $field = Article::Join('category','article.cate_id','=','category.cate_id')->where('art_id',$art_id)->first();
        /*上下篇文章*/
        $article['pre'] = Article::where('art_id','<',$art_id)->orderBy('art_id','desc')->first();
        $article['next'] = Article::where('art_id','>',$art_id)->orderBy('art_id','asc')->first();
        /*相关文章*/
        $data = Article::where('cate_id',$field->cate_id)->orderBy('art_time','desc')->take(6)->get();
        /*查看次数自增，默认次数1*/
        Article::where('art_id',$art_id)->increment('art_view',1);
        return view('home.new',compact('field','article','data'));
    }
}
