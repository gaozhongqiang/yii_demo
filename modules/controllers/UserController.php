<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/6/26
 * Time: 19:32
 * 添加用户
 */
namespace app\modules\controllers;
use app\models\User;
use Yii;
use yii\data\Pagination;
use app\models\Profile;
use yii\db\Exception;

class UserController extends CommonController{
    //列表
    public function actionUsers(){
        $model = User::find()->joinWith('profile');
        $count = $model->count();
        $pager = new Pagination(['totalCount' => $count,'pageSize' => $this->get_page_size('user')]);
        $users = $model->offset($pager->offset)->limit($pager->limit)->asArray()->all();
        $users = array_map(function ($value){
            $value['createtime'] = date_default('Y-m-d H:i:s',$value['createtime']);
            return $value;
        },$users);
        return $this->render('users',['users' => $users,'pager' => $pager]);
    }
    //加入用户
    public function actionReg(){
        $model = new User();
        if(IsPost){
            $data = $this->method_post_value();
            if($model->reg($data)){
                $this->set_flash_session('info','添加成功');
            }
        }
        $model->userpass = '';
        $model->repass = '';
        return $this->render('reg',['model' => $model]);
    }
    //删除
    public function actionDel(){
        try{
            $userid = $this->method_get_value('userid',1);

            if(empty($userid)){
                throw new \Exception('用户id空了');
            }
            $trans = Yii::$app->db->beginTransaction();
            if($obj = Profile::find()->where('userid = :id',[':id' => $userid])->one()){
                $res = Profile::updateAll(['del' => 1],'userid = :id',[':id' => $userid]);
                if(!$res){
                    throw new \Exception('附加表删除失败');
                }
            }
            if (!User::updateAll(['del' => 1],'userid = :id', [':id' => $userid])) {
                throw new \Exception('主表删除失败');
            }
            $trans->commit();
        }catch (\Exception $e){
            if(Yii::$app->db->getTransaction()){
                $trans->rollback();
            }
        }
        $this->redirect(['user/users']);
    }
}