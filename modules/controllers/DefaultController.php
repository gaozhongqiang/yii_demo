<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/6/10
 * Time: 20:05
 * 默认登陆页
 */
namespace app\modules\controllers;
class DefaultController extends CommonController{
    protected $except = ['index'];
    public function actionIndex(){
        $this->layout = 'common';
        return $this->render('index');
    }
}