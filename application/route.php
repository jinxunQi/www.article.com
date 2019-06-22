<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/*return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];*/
//think从thinkphp/library/目录下面找起
use think\Route;

//Route::get("login/[:id]",'admin/index/login'); //id参数可缺省,但对应的方法的参数必须也叫id,且id需要有默认值
//Route::get("user/:id",'admin/index/user');//id参数不可缺省,但对应的方法的参数必须也叫id

Route::get("logout",'admin/public/logout');

//测试方法的路径
Route::get('test','admin/test/test');
Route::get('admin/test/index','admin/test/index');
Route::get('admin/index/index','admin/index/index');

//定义网站根目录路由
Route::get("/",'admin/index/index');

//定义后台首页路由
Route::get('top','admin/index/top');
Route::get('left','admin/index/left');
Route::get('main','admin/index/main');

//定义后台登陆路由
Route::get('login','admin/public/login');
Route::post('login','admin/public/login');

//后台退出路由

Route::get('/admin/index/category','admin/index/category');

//Route::get('admin/test/index3','admin/test/index3');
//Route::post('admin/test/index3','admin/test/index3');
//Route::rule('admin/test/index3','admin/test/index3','get|post');

//Route::get('admin/test/index2','admin/test/index2');
//Route::any('admin/test/index3','admin/test/index3');

//路由分组
Route::group('admin',function (){
    //分类数据操作的相关路由
    Route::get('category/add','admin/category/add');//分类页路由
    Route::post('category/add','admin/category/add');//分类页路由
    Route::get('category/index','admin/category/index');//get和post方式一定要区分

    //文章数据操作的相关路由
    Route::get('article/add','admin/article/add');//展示添加文章的视图的路由
    Route::post('article/add','admin/article/add');//完成添加文章的路由
    Route::get('article/index','admin/article/index');//文章列表展示路由
    Route::get('article/upd','admin/article/upd');//文章编辑页展示路由
    Route::post('article/upd','admin/article/upd');//完成文章编辑页入库路由
    Route::get('article/del','admin/article/del');//删除文章数据路由

    Route::get('article/getContent','admin/article/getContent');//查看文章内容的路由

    Route::get('category/upd','admin/category/upd');
    Route::post('category/upd','admin/category/upd');

    Route::get('category/ajaxDel','admin/category/ajaxDel');

    //测试路由
    Route::get('test/index2','admin/test/index2');
    Route::any('test/index3','admin/test/index3');
    Route::any('test/index4','admin/test/index4');
    Route::any('test/model','admin/test/model');
});