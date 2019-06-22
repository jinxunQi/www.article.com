<?php
namespace app\admin\controller;
use app\admin\model\User;
use think\Controller;
use think\Validate;

class PublicController extends Controller{

    public function login()
    {
        //判断是否是post请求
        if (request()->isPost()) {
            //接收参数
            $postData = input('post.');
            //验证数据是否合法(验证器去验证)
            //1.验证规则
            $rule = [
                //表单name名称 => 验证规则 (多个验证属性用竖线隔开)
                'username' => 'require|length:4,8',
                'password' => 'require',
                'captcha' => 'require|captcha'
            ];
            //2.验证的错误信息
            $message = [
                //表单name名称,规则名 => '提示相应的错误信息'
                'username.require' => '用户名必填',
                'username.length' => '用户名在4-8位之间',
                'password.require' => '密码必填',
                'captcha.require' => '验证码必填',
                'captcha.captcha' => '验证码错误'
            ];
            //3.实例化验证器对象,开始验证 对象中要传验证规则$rule和验证错误信息$message
            $validate = new Validate($rule,$message);
            //4.判断是否验证成功
            $result = $validate->batch()->check($postData);
            //成功 $result true          失败 $result false
            if (!$result) {
                //提示错误的信息
//                halt($validate->getError());
                //使用了批量验证batch(),返回的是一个错误信息的数组
                $this->error(implode(',',$validate->getError())); //这里利用error跳转回原来的登陆界面
            }
            //调用模型的方法checkUser,检测用户名和密码是否匹配
            $userModel = new User();
            $flag = $userModel->checkUser($postData['username'],$postData['password']);
            if ($flag) {
                //直接重定向到后台首页
                $this->redirect(url('admin/index/index'));
            }else{
                //提示用户用户名或者密码错误
                $this->error('用户名或者密码错误');
            }
        }

        return $this->fetch();
    }

    public function logout()
    {
        //清除session信息
//        session('user_id',null);//清除其中某个session数据
        session(null); //清除当前用户登陆的所有session信息
        return $this->redirect('/login'); //要加/因为重定向到网站根目录 然后login
    }
}


?>