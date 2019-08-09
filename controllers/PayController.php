<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/8/9
 * Time: 18:43
 */
namespace app\controllers;
use app\models\Pay;
use Yii;
class PayController extends CommonController{
    public $enableCsrfValidation = false;
    public function actionNotify(){
        if(IsPost){
            $post = Yii::$app->request->post();
            if(Pay::notify($post)){
                echo 'success';
                exit();
            }
            echo 'fail';
            exit();
        }
    }
    public function actionReturn(){
        $status = Yii::$app->request->get('trade_status');
        if($status == 'TRADE_SUCCESS'){
            $s = 'ok';
        }else{
            $s = 'no';
        }
        return $this->render('status',['status' => $s]);
    }
}