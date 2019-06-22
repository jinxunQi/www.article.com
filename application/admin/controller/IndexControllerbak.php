<?php
namespace app\admin\controller;

use think\Controller;

class IndexControllerbak extends Controller
{

    public function category()
    {
        return $this->fetch();
    }

    public function index()
    {
        $name = '窃格拉斯';
        $age = 100;
        $users = [
            ['username'=>'王宝强','age'=>100],
            ['username'=>'王大强','age'=>80],
            ['username'=>'王中强','age'=>70],
            ['username'=>'王小强','age'=>60],
            ['username'=>'王强','age'=>20]
        ];
//        return $this->fetch(); //fetch不填参数,默认访问与方法(当前操作名)同名的模版文件
//        $this->assign('name',$name);
//        $this->assign('age',$age);
        return $this->fetch('',[
            'name' => $name,
            'age' => $age,
            'users' => $users
        ]);//推荐使用这种方式
    }

    public function login($id=0)
    {
        return 'admin/index/login'.$id;
    }

    public function user($id)
    {
        return 'admin/index/user'.$id;
    }
    public function logout()
    {
        return 'admin/index/logout';
    }

    public function index2()
    {
        return 'admin/index/index2';
    }
}
