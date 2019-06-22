<?php
namespace app\admin\controller;
use app\admin\model\Article;
use app\admin\model\Category;
use think\Validate;
class CategoryController extends CommonController{

    public function index()
    {
        $catModel = new Category();
        $data = $catModel
            ->field('t1.*,t2.cat_name p_name')
            ->alias('t1')
            ->join('category t2','t1.pid = t2.cat_id','left')
            ->select();

        //进行无限极分类处理(找子孙分类)
        $cats = $catModel->getSonsCat($data);
        //输出模板视图
        return $this->fetch('',['cats' => $cats]);
    }
    public function add()
    {
        $catModel = new Category();
        //判断是否是post请求
        if (request()->isPost()) {
            //接收post参数
            $postData = input('post.');
            //验证器验证数据 unique:category   unique:表名
            //验证规则
            $rule = [
                'cat_name' => 'require|unique:category',
                'pid' => 'require'
            ];
            //验证错误信息
            $message = [
                'cat_name.require' => '分类名称必填',
                'cat_name.unique' => '分类名称重复',
                'pid.require' => '必须选择一个分类'
            ];
            //实例化一个验证器对象
            $validate = new Validate($rule,$message);
            //验证是否通过
            $result = $validate->batch()->check($postData);
            if (!$result) {
                //没有验证通过
                //$validate->getError()  -->返回的是一个数组

                $this->error(implode(',',$validate->getError() ) );
            }
            //验证通过之后进行数据入库
            if ($catModel->save($postData)) {
                $this->success('入库成功',url('admin/category/index'));
            }else{
                $this->error('入库失败');
            }
        }

        //从category模型中查找数据回显
//        $catModel = new Category();
        //取出所有的分类,分配到模板中
        $data = $catModel->select()->toArray();
        //对分类数据进行递归处理(含有层级缩进关系)
        $cats = $catModel->getSonsCat($data);
        //渲染模版变量和分配变量
//        halt($cats);
        return $this->fetch('',['cats' => $cats]);
    }
    public function upd()
    {
        $catModel = new Category();
        //判断是否为post请求
        if (request()->isPost()) {
            //1.接收参数
            $postData = input('post.');
            //2.验证器验证(后面单独抽离出来)  -------->使用抽离出来的验证器验证
            $result = $this->validate($postData,'Category.upd',[],true);
            //3.判断验证是否成功
            if ($result!==true) {
                $this->error(implode(',',$result));
            }
            //4.判断编辑入库是否成功
            if ($catModel->update($postData)) {
                $this->success('编辑成功',url('admin/category/index'));
            }
                $this->error('编辑失败');
        }
        //接收参数cat_id,取出当前分类的数据
        $cat_id = input('cat_id');
        $catData = $catModel->find($cat_id); //通过cat_id查找符合的某条数据

        $data = $catModel->select();

        //无限极分类处理
        $cats = $catModel->getSonsCat($data);
//        halt($catData);
        return $this->fetch('',[
            'cats' => $cats,//这是分配变量是给下面的下拉列表显示的
            'catData' => $catData
        ]);
    }

    public function ajaxDel()
    {
        //判断是否是ajax请求
        if (request()->isAjax()) {
            //接收参数
            $cat_id = input('cat_id');
            //判断该分类下是否含有子分类,既查出来的分类表的pid是否等于传进来的$cat_id
            $where = [
//                'pid' => ['=',$cat_id]
                'pid' => $cat_id //等价上面的写法
            ];
            $result1 = Category::where($where)->find();
            if ($result1) {
                //表示含有子分类,不能删除,同时要响应数据给前端
                $response = ['code'=>-1,'message'=>'分类下含有子分类,无法删除'];
                return json($response); die; //tp内置的json编码助手函数
                //等价于echo json_encode($response);
            }
            //判断该分类下是否含有文章
            $result2 = Article::where(['cat_id'=>$cat_id])->find();
            if ($result2) {
                //表示该分类下含有文章，不能删除
                $response = ['code'=>-2,'message'=>'分类下含有文章,无法删除'];
                return json($response); die; //tp内置的json编码助手函数
                //等价于echo json_encode($response);
            }
            //满足前两者，进行删除操作，并判断是否删除成功
            if (Category::destroy($cat_id)) {
                //数据要响应回前端
                $response = ['code'=>200,'message'=>'删除成功'];
                return json($response); die; //tp内置的json编码助手函数
            }else{
                $response = ['code'=>-3,'message'=>'删除失败'];
                return json($response); die; //tp内置的json编码助手函数
            }
        }
    }
}
?>