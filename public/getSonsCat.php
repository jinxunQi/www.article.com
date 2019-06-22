<?php
header('Content-Type:text/html;charset=utf-8');

$arr = [
    ['cat_id'=>1,'cat_name'=>'体育','pid'=>0],//体育
    ['cat_id'=>2,'cat_name'=>'篮球','pid'=>1],
    ['cat_id'=>4,'cat_name'=>'足球','pid'=>1],
    ['cat_id'=>5,'cat_name'=>'娱乐','pid'=>0],
    ['cat_id'=>3,'cat_name'=>'NBA篮球','pid'=>2],
    ['cat_id'=>6,'cat_name'=>'腾讯娱乐','pid'=>5]
];
//实现无限极分类(找子孙)
//$i=0;
function getSonsCat($data,$pid=0,$level=1)
{
//    global $i;
    //$result = []; //如果这样定义,那么每次递归调用将会重新初始化一次$result
    static $result = [];//定义一个空数组用于接收无限极分类后的数据
    foreach ($data as $key => $v) {
//        echo $i++; //记录当前遍历循环的次数,用于对递归优化进行对比
        if ($v['pid']==$pid) {
            $v['level'] = $level;
            $result[] = $v;
            //删除已经使用过的元素
            unset($data[$key]); //这是对递归的优化,把上次递归过的数据移除
            getSonsCat($data,$v['cat_id'],$level+1);
        }
    }
    //返回结果
    return $result;
}
echo '<pre/>';
print_r(getSonsCat($arr));
?>