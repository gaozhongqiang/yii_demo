<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/5/27
 * Time: 20:02
 * 前端首页
 */
namespace app\controllers;
use yii\web\Controller;
class IndexController extends Controller{
    //s商城首页
    public function actionIndex(){
        echo \yii\helpers\Url::to('index/index');
        exit();
        $this->layout = 'home_title';
        return $this->render('index');
    }
}