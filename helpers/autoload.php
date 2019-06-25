<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/11
 * Time: 20:26
 */
$file_path = __DIR__ . '/../config/autoload_helper.php';
if(!file_exists($file_path)){
    echo '自动加载文件配置不存在';
    exit();
}
$helper_arr = require $file_path;

foreach ($helper_arr as $value){
    $file_path = APPPATH.'helpers'.DIRECTORY_SEPARATOR.$value.'_helper.php';
    if(file_exists($file_path)){
        require $file_path;
    }

}