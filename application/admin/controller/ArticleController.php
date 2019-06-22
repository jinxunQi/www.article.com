<?php
namespace app\admin\controller;
use app\admin\model\Article;
use app\admin\model\Category;

class ArticleController extends CommonController{

    //文章添加
    public function add()
    {
        $artModel = new Article();
        //判断是否是post提交的数
        if (request()->isPost()) {
            //1.接收参数
            $postData = input('post.');
//            halt($postData);
            //2.利用单独的验证器对数据进行验证
            $result = $this->validate($postData,'Article.add',[],true);
            //3.判断验证是否成功
            if ($result !==true ) {
                //表示验证不通过
                $this->error(implode(',',$result));//把错误信息组转换成字符串
            }
            //判断是否有文件上传
            if ($file = request()->file('img')) {
                //定义上传文件的目录(./相对于入口文件index.php)
                $uploadDir = './upload/';

                //文件上传的规则验证
                $condition = [
                    'size' => 1024*1024*2,  //大小为字节
                    'ext' => 'jpg,png,gif,jpeg'
                ];
                $info = $file->validate($condition)->move($uploadDir); //上传图片移动到框架目录下
                if ($info) {
                    //成功,获取上传的目录文件信息,用于存储到数据库中
                    $postData['ori_img'] = $info->getSaveName();
                    //进行缩略图生成
                    //1.实例化图像类,打开出来处理的原图图片路径
                    //$postData['ori_img']    20190618/dsfadsfdaf.png
                    //$postData['ori_img']    20190618/thumb_dsfadsfdaf.png
                    $image = \think\Image::open('./upload/'.$postData['ori_img']);//打开原图
                    $arr_path = explode('\\',$postData['ori_img']);
                    $thumb_path = $arr_path[0].'/thumb_'.$arr_path[1];
                    //2.生成缩略图并进行保存起来
                    $image->thumb(150,150,2)->save('./upload/'.$thumb_path);
                    //3.保存缩略图的路径到数据表字段
                    $postData['thumb_img'] = $thumb_path;//只需保存缩略图名即可,后面可以拼接成路径

                }else{
                    //表示上传失败,提示上传的错误的信息
                    $this->error($file->getError());
                }

            }

            //4.判断是否入库成功
            if ($artModel->save($postData)) {
                $this->success('入库成功!',url('admin/article/index'));
            }else{
                $this->error('入库失败');
            }
        }

        //查询分类,回显分类下拉
        $catModel = new Category();
        $datas = $catModel->select();
        //无限极处理  分类
        $cats = $catModel->getSonsCat($datas);

        //渲染以及分配模板变量
        return $this->fetch('',[
            'cats'=>$cats
        ]);
    }
    //文章列表页
    public function index()
    {

        //查询文章数据表数据回显
        $artModel = new Article();
        //联表查询出文章的所属分类
        $arts = $artModel->field('a.*,c.cat_name')
            ->alias('a')
            ->join('tp_category c','a.cat_id=c.cat_id','left')
//            ->select();
            ->order('a.article_id desc')
            ->paginate(3); //这是tp自带的分页
        //渲染以及分配模板变量
        return $this->fetch('',[
            'arts' => $arts
        ]);
    }
    //文章列表页编辑功能
    public function upd()
    {
        $artModel = new Article();

        //判断是否post提交数据
        if (request()->isPost()) {
            //1.接收post提交的数据
            $postData = input('post.');
            //2.验证器验证提交信息
            $result = $this->validate($postData,'Article.upd',[],true);
            //3.判断验证信息是否通过
            if ($result!==true) {
                //验证失败
                $this->error(implode(',',$result));//提示错误信息
            }
            //验证成功之后,进行文件上传和缩略图的缩放
            $path = $this->uploadImg('img');
            if ($path) {
                //更新上传图片后,原来文章的原图和缩略图都要删除
                //获取到图片的原图路径和缩略图路径
                $oldData = $artModel->find($postData['article_id']);
                if ($oldData['ori_img']) {
                    unlink('./upload/'.$oldData['ori_img']);
                    unlink('./upload/'.$oldData['thumb_img']);
                }
                //把原图路径和缩略图路径信息写进$postData数组中
                $postData['ori_img'] = $path['ori_img'];
                $postData['thumb_img'] = $path['thumb_img'];
            }

            //4.判断是否入库成功
            if ($artModel->update($postData)) {
                $this->success('编辑成功!',url('admin/article/index'));
            }else{
                $this->error('编辑失败!');
            }
        }
        //接收编辑提交的article_id参数
        $article_id = input('article_id');

        //取出当前文章的数据
        $art = $artModel->find($article_id);

        //取出所有分类(无限极)
        $catModel = new Category();
        $cats = $catModel->getSonsCat( $catModel->select()->toArray() );

        //渲染模板,并分配模板变量
        return $this->fetch('',[
            'art' => $art,
            'cats' => $cats

        ]);

    }
    //文章列表页删除功能
    public function del()
    {
        //接收article_id参数
        $article_id = input('article_id');

        //
        $oldObj = Article::get($article_id);
        //先删除图片数据
        if ($oldObj['ori_img']) {
            //删除该条文章数据下的图片文件  缩略图
            unlink('./upload/'.$oldObj['ori_img']);
            unlink('./upload/'.$oldObj['thumb_img']);
        }
        //判断是否删除成功
        if ($oldObj->delete()) {
            $this->success('删除成功',url('/admin/article/index'));
        }else{
            $this->error('删除失败');
        }

    }
    //文章内容查看功能
    public function getContent()
    {
        //1.判断是否为ajax请求
        if (request()->isAjax()) {
        //2.接受ajax请求传递的article_id，并且获取内容（获取content字段的值,减少搜索压力）
            $article_id = input('article_id');
            $content = Article::where(['article_id'=>$article_id])->value('content');
        //3.返回json格式数据
//            return json($content);
            return json(["content"=>$content]);
        }
    }

}

?>