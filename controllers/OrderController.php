<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/5/28
 * Time: 16:00
 * 订单
 */
namespace app\controllers;
use yii\web\Controller;
class OrderController extends Controller{
    //订单首页
    public function actionIndex(){
        $this->layout = 'home_no_title';
        return $this->render('index');
    }
    //订单详情页
    public function actionDetail(){
        $this->layout = 'home_no_title';
        return $this->render('detail');
    }
}