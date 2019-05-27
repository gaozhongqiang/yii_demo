<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/5/27
 * Time: 20:33
 * 商品页
 */
namespace app\controllers;
use yii\web\Controller;
class ProductController extends Controller{
    public function actionIndex(){
        return $this->render('index');
    }
}