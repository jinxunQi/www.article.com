<?php
//定义当前类所在的命名空间
namespace app\admin\controller;
//定义控制器名(和文件名要一致)
use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\Category;
use app\admin\model\Article;

class TestController extends Controller {

    #自动更新
    public function model()
    {
        echo md5('123456'.config('password_salt'));die;
        $category = new Category();
        $data = [
            'cat_name' => '大大球',
            'pid' => '10'
        ];
        dump($category->save($data));

    }

    /*#聚合函数
    public function model()
    {
        $articleModel = new Article();
        dump($articleModel->where('cat_id = 2')->count()); //4
        dump($articleModel->max('article_id')); //4
        dump($articleModel->min('article_id')); //1
        dump($articleModel->avg('article_id')); //2.5
        dump($articleModel->sum('article_id')); //10

    }*/
    /*public function model()
    {
        //联表查询出分类的父分类
        //join('其他表 别名','条件','类型')
        $data = Db::name('category')
            ->field('t1.*,t2.cat_name as p_name')
            ->alias('t1')
            ->join('tp_category t2','t1.pid=t2.cat_id','left')
            ->select();
        dump($data);
    }*/

    #Db操作类实现
   /* public function model()
   {
       //需求:联表查询出文章的所属分类
       //join('其他类 别名','条件','类型')
       $data = Db::table('tp_article')
               ->field('t1.*,t2.cat_name as p_name')
               ->alias('t1')
               ->join("tp_category t2",'t1.cat_id = t2.cat_id','left')               //要连表的表的别名前别加as 不然会报错
               ->select();

       dump($data);
   }*/

    /*public function model()
    {
        $category = new Category();
        $data = $category
            ->field('t1.*,t2.cat_name as p_name')
            ->alias('t1')
            ->join('tp_category t2','t1.pid=t2.cat_id','left')
            ->select()
            ->toArray();

        dump($data);
    }*/

    #join连表查询
    /*public function model()
    {
        $articleModel = new Article();
        $data = $articleModel
                ->field('t1.*,t2.cat_name as p_name')
                ->alias('t1')
                ->join("tp_category t2",'t1.cat_id = t2.cat_id','left')               //要连表的表的别名前别加as 不然会报错
                ->select()
                ->toArray();

        dump($data);
    }*/

    #limit限制取出的条数
    /*public function model()
    {
        $catModel = new Category();
        $data = $catModel->limit(2,2)
            ->select()
            ->toArray();  //SELECT `cat_name`,`pid`,count(cat_id) as count FROM `tp_category` GROUP BY `pid`
        dump($data);
    }*/

    /*public function model()
    {
        $catModel = new Category();
        $data = $catModel->field("cat_name,pid,count(cat_id) as count")
            ->group('pid')
            ->select()
            ->toArray();  //SELECT `cat_name`,`pid`,count(cat_id) as count FROM `tp_category` GROUP BY `pid`
        dump($data);
    }*/

    #连贯操作方法
//    public function model()
//    {
//        $category = new Category();
//        //表达式查询条件
//        $data = $category->field('cat_name,cat_id')
//            ->order('cat_id desc')
//            ->where('cat_id','>',"2")
//            ->where('pid','=','2')
//            ->buildSql();
//            //排查sql语句是否有错误时,可以在连贯方法后面调用 buildSql()获得组装的sql语句
//
//        //数组查询条件
////        $where = [
////            'cat_id'=>['>','2'],
////            'pid'=>['=','2']
////        ];
////        $data = $category->field('cat_name,cat_id')->order('cat_id desc')->where($where)->select();
//
//
//       //字符串查询条件
////        $data = $category->field('cat_name,cat_id')->order('cat_id desc')->where('cat_id > 2 and pid = 2')->select();
//
//        dump($data);
//    }

    #查询多条数据 all 和 select 一般常用select
//    public function model()
//    {
//        #all
////        $category = new Category();
////        dump($category->all());
////
//        ####toArray()便于查看数据
////        $category = Category::get(2)->toArray();
////        dump($category);
//
//        #select
////        $category = new Category();
////        $data = $category->where('cat_id = 2')->select();
////        dump($data);
//
//        $category = new Category();
//        $data = $category->field('cat_name,cat_id')
//            ->order('cat_id desc')
//            ->where('cat_id','>','2')
//            ->select()
//            ->toArray();
//        dump($data);
//
//    }

    #查询一条数据 get和find
//    public function model()
//    {
//        //get方式获取
//        //通过对象调用 -> 通过类调用 ::
////        $category = new Category();
////        $data = $category->get(2);
////        dump($data);
//        /*$data = Category::get(3);
//        dump($data);*/
//        //find方式获取 ,find与get的区别是,find支持连贯操作
//        /*$category = new Category();
//        $data = $category->find(5);
//        dump($data);*/
////        $data = Category::find(2);
////        dump($data);
//
//        /*//连贯操作
//        $data = Category::where('cat_id = 5')->find(); //find(5)表示获取主键id为5的那条数据
//        dump($data);
//        */
//
//
//         //get只能通过闭包来做上面的连贯
//
//        $category = Category::get(function($query){
//            $query->where('cat_id = 2');
//        });
////        dump($category);
//        dump($category->cat_id);
//        dump($category->cat_name);
//        dump($category->pid);
//        dump($category['cat_name']);
//    }

    #删除数据
    /*public function model()
    {
        //返回受影响的行数
//        dump(Category::destroy(9));//删除第九条数据   1
//        dump(Category::destroy('6,7,8'));//删除第九条数据   3

        //第二种方式
        $category = Category::get(1); //返回受影响的行数
        dump($category->delete());
    }*/

    #更新数据
    /*public function model()
    {
        //给Category数据表添加一条数据
        $catModel = new Category();
        $data = [
            'cat_name' => '破球',
            'pid' => 1,
            //更新cat_id = 9的数据
            'cat_id' => 9
        ];
        dump($catModel->update($data));
//        dump($catModel->save($data));
//        dump($catModel->cat_id);

    }*/
    /*public function model()
    {
        //给Category数据表添加多条数据
        $catModel = new Category();
        $data = [
            ['cat_name' => '羽毛球','pid' => 1],
            ['cat_name' => '乒乓球','pid' => 1],
            ['cat_name' => '台球','pid' => 1],
        ];
        dump($catModel->saveAll($data));
    }*/

    /*public function model()
    {
        //给Category数据表添加一条数据
        $catModel = new Category();
        $data = [
            'cat_name' => '水球',
            'fdasfdfads' => 'dsafdsfadsf',
            'pid' => 1,
            'cat_id' => 9
        ];
        dump($catModel->allowField(true)->save($data));
//        dump($catModel->save($data));
//        dump($catModel->cat_id);

    }*/
    public function index4()
    {
        //实例化Category模型对象
        //方式一,使用这种方式必须引用Category类,既第8行代码 ----->推荐使用这种方法
//        $catModel = new Category();
//        dump($catModel->get(1));
        //方式二,通过model()助手函数,使用这种方式,不需要引用Category类,既不用写第八行代码
//        $catModel = model('Category');
//        dump($catModel->get(2));
    }
    public function test()
    {
        //页面跳转
//        $this->success('成功跳转','/top','',3); //成功跳转
//        $this->error('跳转失败');//失败跳转一般不用写后面的参数,失败跳转会跳回上一个打开的页面

        //重定向
        $this->redirect('/top');//重定向,没有提示信息直接跳转

        $arr = ['name'=>'张三','age'=>19,'work'=>'teacher'];
//        dump($arr);//先当于pre + var_dump()
//        halt($arr);//先当于pre + var_dump()＋ｄｉｅ　既后面的代码会被终止执行

        //config()第二个参数写上,这配置一个文件信息
        echo config('admin_static') . '<br />';//config()获取配置项的信息,动态获取优先级最高,然后到模块--->应用---->convention默认(这个配置文件最好不要做修改) 一般而言,我们只需要使用应用以及模块下的config.php就足够了
        //第一个参数/top必须是在路由中已经定义好的,否则会报路由不存在的错误
        echo url('/top',['id'=>100,'name'=>'abc']);
        echo url('admin/index/index',['id'=>100,'name'=>'abc']);
    }
    public function index()
    {
        dump(Db::table("tp_category")->select());
//        return $this->fetch();
    }

    public function index3()
    {
        //接受表单数据
        dump(input('username','','strtoupper')); //第三个参数为参数过滤
        dump(input('email'));
        dump(input('ids/a')); //若获取的数据是数组形式,一定要加/a修饰符才能正确获取
        dump(input('post.'));


    }

    public function index2(Request $request)
    {
        //第一种方式
//        $request = Request::instance();
        //第二种方式,最简单,通过助手函数
//        $request = request();
        //第三种方式,依赖注入（闭包）的方式 34行代码
        dump($request->domain());//请求的域名
        dump($request->module());//请求的模块
        dump($request->controller());//请求的控制器
        dump($request->action());//请求的方法
        dump($request->isAjax());//请求是否为Ajax
        dump($request->isPOST());//请求是否为POST
        dump($request->isGET());//请求是否是GET
//        halt($request);
    }
}