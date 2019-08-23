<?php
/**
 * Created by PhpStorm.
 * User: gaozhongqiang
 * Date: 2019/6/2
 * Time: 10:18
 * 首页
 */
namespace app\modules\controllers;
use app\modules\models\Admin;
use Yii;
use yii\data\Pagination;

class ManageController extends CommonController{
    //列表
    public function actionManagers(){
        $model = Admin::find();
        $count = $model->count();
        $pageSize = Yii::$app->params['admin']['pageSize']['manager'];
        $pager = new Pagination(['totalCount' => $count, 'PageSize' => $pageSize]);
        $managers = $model->offset($pager->offset)->limit($pager->limit)->asArray()->all();
        $managers = array_map(function ($value){
            $value['login_time'] = date_default('Y-m-d H:i:s',$value['logintime']);
            $value['create_time'] = date_default('Y-m-d H:i:s',$value['createtime']);
            $value['login_ip'] = ip2long($value['loginip']);
            return $value;
        },$managers);

        return $this->render('managers',['managers' => $managers,'pager' => $pager]);
    }
    //添加
    public function actionAdd(){
        $model = new Admin();
        if(IsPost){
            $post = $this->method_post_value();
            if($model->reg($post)){
                Yii::$app->session->setFlash('info','添加成功');
            }else{
                Yii::$app->session->setFlash('info','添加失败');
            }
        }
        $model->adminpass = '';
        $model->repass = '';
        return $this->render('add',['model' => $model]);
    }
    //修改
    public function actionEdit(){
        $id = $this->method_get_value('id',1);
        if(empty($id)){
            $id = $this->method_get_value('id',1);
        }
        if(empty($id)){
            $this->redirect(['manage/managers']);
        }
        $admin_data = Admin::find()->where('id = :id',[':id' => $id])->asArray()->one();
        if(empty($admin_data)){
            $this->redirect(['manage/managers']);
        }
        $model = new Admin();
        if(IsPost){
            $data = $this->method_post_value('Admin');
            if($model->edit($data,$id)){
                Yii::$app->session->setFlash('info','修改成功');
                $this->redirect(['manage/managers']);
            }else{
                Yii::$app->session->setFlash('info','修改失败');
            }
        }


        return $this->render('edit',['admin_data' => $admin_data,'model' => $model]);
    }
    //删除
    public function actionDel(){
        $id = $this->method_get_value('id');
        if(empty($id)){
            Yii::$app->session->setFlash('info','删除失败');
            $this->redirect(['manage/managers']);
        }
        $model = new Admin();
        if($model->deleteAll('adminid = :id',[':id' => $id])){
            Yii::$app->session->setFlash('info','删除成功');
        }else{
            Yii::$app->session->setFlash('info','删除失败');
        }
        $this->redirect(['manage/managers']);
    }
    //电子邮箱找回密码
    public function actionMailchangepass(){
        $time = $this->method_get_value('timestamp',1);
        $admin_user = $this->method_get_value('admin_user');
        $token = $this->method_get_value('token');

        $my_token = create_token_find_pass($admin_user,$time);
        if($token != $my_token){
            $this->redirect(['public/login']);
            Yii::$app->end();
        }
        if(time() - $time > 300){
            $this->redirect(['public/login']);
            Yii::$app->end();
        }
        $model = new Admin();
        if(IsPost){
            $post = $this->method_post_value();
            if($model->changePass($post)){
                Yii::$app->session->setFlash('info','密码修改成功');
            }
        }
        $model->admin_user = $admin_user;
        return $this->render('mailchangepass',['model' => $model]);
    }
    //个人信息管理
    public function actionChangeemail(){
        $model = Admin::find()->where('admin_user = :user',[':user' => $this->get_admin_session('admin_user')])->one();
        if(IsPost){
            $data = $this->method_post_value();
            if(!$model->changeEmail($data)){
                Yii::$app->session->setFlash('info','修改失败');
            }else{
                Yii::$app->session->setFlash('info','修改成功');
                $this->redirect(['manage/managers']);
            }

        }
        $model->admin_pass = "";
        return $this->render('changeemail',['model' => $model]);
    }
    //修改密码
    public function actionChangepass(){
        $model = Admin::find()->where('admin_user = :user',[':user' => $this->get_admin_session('admin_user')])->one();
        if(IsPost){
            $data = $this->method_post_value();
            if($model->changePass($data)){
                Yii::$app->session->setFlash('info', '修改成功');
            }
        }
        $model->admin_pass = '';
        $model->admin_repass = '';
        return $this->render('changepass', ['model' => $model]);
    }
}