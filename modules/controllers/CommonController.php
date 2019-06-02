<?php
/**
 * Created by PhpStorm.
 * User: gaozhongqiang
 * Date: 2019/6/2
 * Time: 10:21
 * 公共
 */
namespace app\modules\controllers;
use yii\web\Controller;
use yii;
class CommonController extends Controller{
    public function init()
    {
        $this->layout = false;
        /*if(Yii::$app->session['admin']['isLogin']!=1){
            $this->redirect('index');
        }*/
    }
}