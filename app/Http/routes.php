<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*!!!*/
/*新版本不用重新声明中间件:web*/


Route::group(['middleware' => ['web']], function () {

    //网站首页
    Route::get('/','Home\IndexController@index');
    //前台文章列表页，文章分类显示
    Route::get('/cate/{cate_id}','Home\IndexController@cate');
    //文章页
    Route::get('/a/{art_id}','Home\IndexController@article');




    //登录页面 (get/post)
    Route::any('admin/login', 'Admin\LoginController@login');
    //登录页验证码
    Route::get('admin/code', 'Admin\LoginController@code');

});


Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware' => ['web','admin.login']], function () {

    //后台欢迎页面
    Route::get('/', 'IndexController@index');
    //后台info页面
    Route::get('info', 'IndexController@info');
    //清空session,退出
    Route::get('quit', 'LoginController@quit');
    //后台修改密码
    Route::any('pass', 'IndexController@pass');
    //资源路由：category
    Route::resource('category','CategoryController');
    //分类排序
    Route::post('cate/changeorder','CategoryController@changeOrder');
    //资源路由：article
    Route::resource('article','ArticleController');
    //上传缩略图
    Route::any('upload', 'CommonController@upload');
    //资源路由：links
    Route::resource('links','LinksController');
    //链接排序
    Route::post('links/changeorder','LinksController@changeOrder');
    //资源路由：navs
    Route::resource('navs','NavsController');
    //导航排序
    Route::post('navs/changeorder','NavsController@changeOrder');
    //资源路由：config
    Route::resource('config','ConfigController');
    //配置排序
    Route::post('config/changeorder','ConfigController@changeOrder');
    //快速修改配置
    Route::post('config/changecontent','ConfigController@changecontent');
    //数据库配置项，写入config文件
    Route::get('putfile', 'ConfigController@putFile');

});