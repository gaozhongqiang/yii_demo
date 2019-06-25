<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/11
 * Time: 20:39
 */
/**********************************************************************
 * date函数重写，加入默认值
 ***********************************************************************/
if(!function_exists('date_default')){
    function date_default($format, $timestamp, $default = '---'){
        if(empty($timestamp)){
            return $default;
        }
        return date($format,$timestamp);
    }
}