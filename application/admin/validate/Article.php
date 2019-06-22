<?php
namespace app\admin\validate;
use think\Validate;

class Article extends Validate{
    //定义验证器规则
    protected $rule = [
        'title' => 'require|unique:article',  //必填|不能重复
        'cat_id' => 'require' //必填
    ];
    //定义验证提示的错误信息
    protected $message = [
        'title.require' => '文章标题不能为空',
        'title.unique' => '文章重复',
        'cat_id.require' => '必须要选择一个文章分类'
    ];
    //定义验证场景
    protected $scene = [
        //使用场景   add场景使用title和cat_id的所有规则进行验证
        'add' => ['title','cat_id'],
        'upd' => ['title','cat_id']
    ];

}

?>