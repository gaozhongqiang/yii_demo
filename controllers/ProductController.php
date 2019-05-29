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
    //产品首页
    public function actionIndex(){
        $this->layout = 'home_title';
        return $this->render('index');
    }
    //单个产品页
    public function actionSingle(){
        $this->layout = 'home_title';
        return $this->render('detail');
    }
}