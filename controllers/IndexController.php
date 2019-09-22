<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/5/27
 * Time: 20:02
 * 前端首页
 */
namespace app\controllers;
class IndexController extends CommonController {
    //s商城首页
    public function actionIndex(){
        $this->layout = 'home_title';
        return $this->render('index');
    }
}