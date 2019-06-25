<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/6/20
 * Time: 20:12
 * 过滤函数
 */
//过滤input数据
if(!function_exists('filter_input_value')){
    function filter_input_value($data){
        return addslashes(stripslashes(htmlspecialchars($data,ENT_QUOTES)));
    }
}