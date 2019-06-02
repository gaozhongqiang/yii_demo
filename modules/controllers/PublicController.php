<?php
/**
 * Created by PhpStorm.
 * User: gaozhongqiang
 * Date: 2019/6/2
 * Time: 10:27
 * 登陆
 */
namespace app\modules\controllers;
use app\modules\models\Admin;
use Yii;

class PublicController extends CommonController{
    //登陆
    public function actionLogin(){
        $model = new Admin();
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($model->login($post)){
                $this->redirect('default/index');

            }

        }
        return $this->render('login',['model' => $model]);
    }
    //找回密码
    public function actionSeekpassword(){
        $model = new Admin();
        return $this->render('seekpassword');
    }
}