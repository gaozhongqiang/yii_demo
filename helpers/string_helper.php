<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/6/19
 * Time: 19:41
 * 字符串操作函数
 */
/**
 * 特殊字符串划分进行数据比较是否是合格的数据    ---20181224 gzq
 * @param string $str 字符串  如：1,2,3
 * @param string $delimiter 拼接符
 * @return array 错误信息 + 处理好的字符串
 */
if(!function_exists('string_delimiter_arr')){
    function string_delimiter_arr($str,$delimiter = ','){
        if(empty($str)){
            return array(-1,'参数为空了');
        }
        $str_arr = array_unique(array_filter(explode($delimiter,$str)));
        if(count($str_arr) != bcadd(substr_count($str,$delimiter),1)){
            return array(-1,'字符串格式错误');
        }
        return array(0,$str_arr);
    }
}