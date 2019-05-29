<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/5/29
 * Time: 16:05
 * 用户登陆及注册
 */
namespace app\controllers;
use yii\web\Controller;
class MemberController extends Controller{
    //登陆及注册页
    public function actionIndex(){
        $this->layout = 'home_title';
        return $this->render('index');
    }
    //登陆
    public function actionLogin(){

    }
    //注册
    public function actionRegister(){

    }
}