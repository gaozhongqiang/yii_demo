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
        $this->layout = false;
        $model = new Admin();
        if(IsPost){
            $data = $this->method_post_value();
            if($model->login($data)){
                $this->redirect(['default/index']);
            }

        }
        return $this->render('login',['model' => $model]);
    }
    //找回密码
    public function actionSeekpassword(){
        $this->layout = false;
        $model = new Admin();
        if(IsPost){
            $data = $this->method_post_value();
            if($model->seekPass($data)){
                Yii::$app->session->setFlash('info', '电子邮件已经发送成功，请查收');
            }
        }
        return $this->render('seekpassword', ['model' => $model]);
    }
}