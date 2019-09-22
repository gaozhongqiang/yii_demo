<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/8/8
 * Time: 17:34
 * 地址管理
 */
namespace app\controllers;
use app\models\Address;
use app\models\User;
use Yii;
class AddressController extends CommonController {
    public function actionAdd(){
        $this->isLogin();
        $loginName = Yii::$app->session['loginName'];
        $userId = User::find()->where("username = :name or useremail = :email",[':name' => $loginName,':email' => $loginName])->one()->userid;
        if(IsPost){
            $post = Yii::$app->request->post();
            $post['userid'] = $userId;
            $post['address'] = $post['address1'].$post['address2'];
            $data['Address'] = $post;
            $model = new Address();
            $model->load($data);
            $model->save();
        }
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
    public function actionDel(){
        $this->isLogin();
        $loginName = Yii::$app->session['loginName'];
        $userId = User::find()->where("username = :name or useremail = :email",[':name' => $loginName,':email' => $loginName])->one()->userid;
        $addressid = Yii::$app->response->getBehavior('addressid');
        if(!Address::find()->where('userid = :name or useremail = :email',[':name' => $userId,':email'=>$loginName])->one()){
            return $this->redirect($_SERVER['HTTP_REFERER']);
        }
        Address::deleteAll('address = :aid',[':aid' => $addressid]);
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}