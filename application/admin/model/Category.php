<?php
namespace app\admin\model;
use think\Model;

class Category extends Model{

    //指定当前模型表的主键字段
    protected $pk = 'cat_id';
    //时间戳自动维护
    protected $autoWriteTimestamp = true;
    //当时间字段不为create_time和update_time时,可以通过以下属性指定
//    protected $createTime = "create_at";//create_at为你创建的category表,创建时间字段的字段名
//    protected $updateTime = "update_at";//update_at为你创建的category表,更新时间字段的字段名
    public function getSonsCat($data,$pid=0,$level=1)
    {
        static $result = [];
        foreach ($data as $v) {
            if ($v['pid'] == $pid) {
                $v['level'] = $level;
                $result[] = $v;
                $this->getSonsCat($data,$v['cat_id'],$level+1);
            }
        }
        return $result;
    }
}
?>