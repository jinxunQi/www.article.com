<?php
namespace app\admin\controller;
use think\Controller;

class CommonController extends Controller{
    #控制器初始化操作
    public function _initialize()
    {
        if (!session('user_id')) {
            //表示没有session用户数据,需要登陆操作
            $this->success('您先要进行登陆操作!','/login');
        }
    }

    //文件上传方法封装
    public function uploadImg($fileName)
    {
        $ori_img = '';//存储原图的路径
        $thumb_img = '';//存储缩略图的路径

        //判断是否有文件上传
        if ($file = request()->file($fileName)) {
            //定义上传文件的目录(./相对于入口文件index.php)
            $uploadDir = './upload/';

            //文件上传的规则验证
            $condition = [
                'size' => 1024*1024*2,  //大小为字节
                'ext' => 'jpg,png,gif,jpeg'
            ];
            $info = $file->validate($condition)->move($uploadDir); //上传图片移动到框架目录下  ----->临时上传目录改为$uploadDir
            if ($info) {
                //成功,获取上传的目录文件信息,用于存储到数据库中
                $ori_img = $info->getSaveName();
                //进行缩略图生成
                //1.实例化图像类,打开出来处理的原图图片路径
                //$postData['ori_img']    20190618/dsfadsfdaf.png
                //$postData['ori_img']    20190618/thumb_dsfadsfdaf.png
                $image = \think\Image::open('./upload/'.$ori_img);//打开原图
                $arr_path = explode('\\',$ori_img);
                $thumb_img = $arr_path[0].'/thumb_'.$arr_path[1];
                //2.生成缩略图并进行保存起来
                $image->thumb(150,150,2)->save('./upload/'.$thumb_img);

                return ['ori_img'=>$ori_img,'thumb_img'=>$thumb_img];
            }else{
                //表示上传失败,提示上传的错误的信息
                $this->error($file->getError());
            }

        }
    }
    
}