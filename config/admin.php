<?php
/**
 * Created by PhpStorm.
 * User: gaozhongqiang
 * Date: 2019/6/2
 * Time: 10:11
 * 后台配置文件
 */
return [
    'title' => 'yii学习商城后台管理',//前台标题
    'js' =>  '/admin/js/',//前台js文件存储目录
    'css' =>  '/admin/css/',//前台js文件存储目录
    'images' =>  '/admin/img/',//前台js文件存储目录
    'pageSize' => [
        'manager' => 2,
        'user' => 2,
        'product' => 2,
        'order' => 2
    ],
    'defaultValue' => [
        'avatar' => '/admin/img/contact-img.png'
    ]
];