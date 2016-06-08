<?php

namespace App\Http\Controllers\Home;

use App\Http\Model\Article;
use App\Http\Model\Navs;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class CommonController extends Controller
{
    public function __construct()
    {
        /*导航栏*/
        $navs = Navs::all();
        /*点击量最高的5篇文章*/
        $hot_2 = Article::orderBy('art_view','desc')->take(5)->get();
        /*最新发布8篇文章*/
        $new = Article::orderBy('art_time','desc')->take(8)->get();
        /*公共传参*/
        View::share('navs',$navs);
        View::share('hot_2',$hot_2);
        View::share('new',$new);
    }
}
