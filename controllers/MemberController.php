<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/5/29
 * Time: 16:05
 * 用户登陆及注册
 */
namespace app\controllers;
use app\models\User;
use Yii;
class MemberController extends CommonController {
    //登陆及注册页
    public function actionAuth(){
        $this->layout = 'home_title';
        $model = new User;
        if(IsPost){
            $post = Yii::$app->request->post();
            if($model->login($post)){
                return $this->goBack(Yii::$app->request->referrer);
            }
        }
        return $this->render('auth',['model' => $model]);
    }
    //登陆
    public function actionLogout(){
        Yii::$app->session->remove('loginName');
        Yii::$app->session->remove('isLogin');
        if(!isset(Yii::$app->session['isLogin'])){
            return $this->goBack(Yii::$app->request->referrer);
        }
    }
    //注册
    public function actionReg(){
        $model = new User;
        if(IsPost){
            $post = Yii::$app->request->post();
            if($model->regByMail($post)){
                Yii::$app->session->setFlash('info','电子邮件发送成功');
            }
        }
        return $this->render('auth',['model' => $model]);
    }
    public function actionQQlogin(){
        require_once("../vendor/qqlogin/qqConnectAPI.php");
        $qc = new \QC();
        $qc->qq_login();
    }

}