<?php
/**
 * PHP防止XSS攻击过滤函数
 * @param  string $val 需要过滤的数据
 * @return string      过滤后的数据
 */
function removeXSS($val){

    static $obj = null;

    if($obj === null){
        //修改插件的所在位置
        require '../extend/HTMLPurifier/HTMLPurifier.includes.php';

        $obj = new HTMLPurifier();
    }

    return $obj->purify($val);
}


?>