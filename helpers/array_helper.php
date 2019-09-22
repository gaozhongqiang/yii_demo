<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/6/11
 * Time: 20:31
 * 数组辅助函数
 */
/**************************************************************
设置一个数组的二维数组中的key的值作为这个数组中的key返回
指定keyname对应的值是不一样的如果一样的话就会被覆盖
 **************************************************************/
if(!function_exists('array_set_key')){
    function array_set_key($arr,$keyname){
        if(empty($arr)){
            return array();
        }
        $newArr=array();
        foreach($arr as $key => $value){
            if(isset($value[$keyname])){
                $newArr[$value[$keyname]]=$value;
            }
        }
        return $newArr;
    }
}
/***********************************************************************************************************************
 * 根据数组的键去除数组中的某个值
 * 传入参数：可以是一维数组或二维数组
 * $arr:
 * 一维数组
 * array(3)
 *          {["id"]=>string(2) "22"
 *          ["tag_name"]=>string(11) "14日首发"
 *          ["color"]=>string(7) "#FF6600"}
 * }
 * 二维数组
 * array(1) {
 *      [0]=>array(3)
 *          {["id"]=>string(2) "22"
 *          ["tag_name"]=>string(11) "14日首发"
 *          ["color"]=>string(7) "#FF6600"}
 * }
 * $k:要删除的key      id
 * 输入结果：
 * 一维数组：
 * array(2)
 *          ["tag_name"]=>string(11) "14日首发"
 *          ["color"]=>string(7) "#FF6600"}
 * }
 *二维数组
 * array(1) {
 *      [0]=>array(2)
 *          ["tag_name"]=>string(11) "14日首发"
 *          ["color"]=>string(7) "#FF6600"}
 * }
 ***********************************************************************************************************************/
if(!function_exists('array_del_key')){
    function array_del_key($arr,$k){
        if(empty($arr) || empty($k)){
            return $arr;
        }
        if(count($arr)==count($arr,1)){//处理一维数组
            unset($arr[$k]);
            return $arr;
        }else {
            $out=array();
            foreach ($arr as $key => $value) {
                unset($value[$k]);
                $out[$key] = $value;
            }
            return $out;
        }
    }
}
/***********************************************************************************
 * array_column升级函数设置默认值
 ***********************************************************************************/
if(!function_exists('array_column_default')){
    function array_column_default($input,$column_key,$index_key = NULL,$default = array()){
        if(empty($input)){
            return $default;
        }
        return array_column($input,$column_key,$index_key);
    }
}
/*************************************************************************************
 * 以数组中相同的值作为键返回一个大数组
 * @param array $arr 要处理的数组
 * @param string $key 处理的键
 * @param string $retentionArr 需要保留的key
 * @param array $list_arr 自动组合的值  ---20181015 gzq 扩充
 * @param string $operate_key 处理的键 获取到的key使用array_column 处理
 * @return array 处理好的数组
 **********************************************************************************/
if(!function_exists('array_set_small_value_to_key')){
    function array_set_small_value_to_key($arr,$k,$retentionArr=array(),$list_arr = array(),$operate_key = ""){
        if(empty($arr) || !is_string($k)){
            if(empty($list_arr)){
                return $arr;
            }else{
                $out_arr = array();
                foreach ($list_arr as $key => $value){
                    $out_arr[$value] = array_key_exists($value,$arr) && !empty($newArr[$value]) ?
                        (empty($operate_key) ? $newArr[$value] : array_column($newArr[$value],$operate_key)) : array();
                }
                return $out_arr;
            }
        }
        $newArr=$tempArr=array();
        foreach ($arr as $key=>$value){
            if(!array_key_exists($k,$value)){
                continue;
            }
            if(!isset($newArr[$value[$k]])){
                if(!empty($retentionArr)){
                    foreach ($retentionArr as $key1=>$value1){
                        $tempArr[$value1]=$value[$value1];
                    }
                    $newArr[$value[$k]]=array($tempArr);
                }else{
                    $newArr[$value[$k]]=array($value);
                }
            }else{
                if(!empty($retentionArr)){
                    foreach ($retentionArr as $key1=>$value1){
                        $tempArr[$value1]=$value[$value1];
                    }
                    $newArr[$value[$k]][]=$tempArr;
                }else{
                    $newArr[$value[$k]][]=$value;
                }
            }
        }
        if(empty($list_arr) || !is_array($list_arr) || empty($newArr)){
            return $newArr;
        }else{
            $out_arr = array();
            foreach ($list_arr as $key => $value){
                $out_arr[$value] = array_key_exists($value,$newArr) && !empty($newArr[$value]) ?
                    (empty($operate_key) ? $newArr[$value] : array_column($newArr[$value],$operate_key)) : array();
            }
            return $out_arr;
        }
    }
}
/*****************************************************************************************************************************************
将一个数组转换成sql的IN形式，这个数组可以是一个一维数组也可以是一个二维数组，对于一维数组keyname是空的
$arr				要转换成sql in形式的
$default		表示如果$arr为空的时候IN里面内容的默认值，事实上这个默认值是需要的不然就会出现如mid IN ('')这种情况
$is_linear	表示是一维数组还是二维数组，对于一维数组中keyname是无效的，而keyname是对于二维数组才有效
$keyname		是对于二维数组，对于一维数组是无效的
 ******************************************************************************************************************************************/
if(!function_exists('array_to_sql_str')){
    function array_to_sql_str($arr,$default=-1,$is_linear=1,$keyname=""){
        if(empty($arr)){
            return "'{$default}'";
        }
        $line_arr=$is_linear==2 && !empty($keyname) ? array_column_default($arr,$keyname) : $arr;
        return "'".implode("','",$line_arr)."'";
    }
}
