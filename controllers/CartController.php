<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/5/28
 * Time: 15:57
 * 购物车
 */
namespace app\controllers;
use yii\web\Controller;
class CartController extends Controller{
    public function actionIndex(){
        $this->layout = 'home_no_title';
        return $this->render('index');
    }
}