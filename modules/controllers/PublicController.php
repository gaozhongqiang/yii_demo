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
use yii\base\Controller;

class PublicController extends Controller {
    //登陆
    public function actionLogin(){
        $this->layout = false;
        $model = new Admin();
        if(IsPost){
            $data = Yii::$app->request->post();
            if($model->login($data)){
                $this->redirect(['default/index']);
            }

        }
        echo md5(123456);
        return $this->render('login',['model' => $model]);
    }
    public function actionLogout()
    {
        Yii::$app->session->removeAll();
        if (!isset(Yii::$app->session['admin']['isLogin'])) {
            $this->redirect(['public/login']);
        }
        $this->goback();
    }
    //找回密码
    public function actionSeekpassword(){
        $this->layout = false;
        $model = new Admin();
        if(IsPost){
            $data = Yii::$app->request->post();
            if($model->seekPass($data)){
                Yii::$app->session->setFlash('info', '电子邮件已经发送成功，请查收');
            }
        }
        return $this->render('seekpassword', ['model' => $model]);
    }
}