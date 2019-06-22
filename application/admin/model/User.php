<?php
namespace app\admin\model;
use think\Model;

class User extends Model {


    /**
     * @param $username 用户名
     * @param $password 密码(明文)
     * @return bool 成功 返回 true,失败 返回 false
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    //验证用户名和密码是否正确
    public function checkUser($username,$password)
    {

        $where = [
            'username' => $username,
            'password' => md5($password.config('password_salt')),
        ];
        //如果能从数据库中user表中找到匹配的用户信息,那么登陆成功
        $userInfo = $this->where($where)->find(); //$userInfo是一个数据对象
        if ($userInfo) {
            //用户信息存储到session中去
            session('user_id',$userInfo['user_id']);
            session('username',$userInfo['username']);
            return true;
        }else{
            return false;
        }
    }
}
