<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/6/27
 * Time: 16:52
 * 分类管理
 */
namespace app\modules\controllers;
use app\models\Category;
use Yii;
use yii\web\Response;

class CategoryController extends CommonController{
    //列表
    public function actionList()
    {
        $page = $this->method_get_value('page',1,1);
        $perpage = $this->method_get_value('per_page',1,1);
        $model = new Category();
        //$cates = $model->getTreeList();
        $data = $model->getPrimaryCate();
        return $this->render('cates',['pager' => $data['pager'],'page' => $page,'perpage' => $perpage]);
    }
    //添加
    public function actionAdd(){
        $model = new Category();
        if(IsPost){
            $data = $this->method_post_value();
            if($model->add($data)){
                $this->set_flash_session('info','添加成功');
            }

        }
        $model->title = '';
        return $this->render('add',['model' => $model,'list' => $model->getOptions()]);
    }
    //修改
    public function actionEdit(){
        $cateid = $this->method_get_value('cateid',1);
        if(empty($cateid)){
            $cateid = $this->method_post_value('cateid',1);
        }

        $data = Category::find()->where('cateid = :id',[':id' => $cateid])->one();
        if(empty($data)){
            $this->set_flash_session('info','数据不存在');
            $this->redirect(['category/list']);
        }
        if(IsPost){
            $model = new Category();
            $data1 = $this->method_post_value();
            if($model->load($data1) && $model->save()){
                $this->set_flash_session('info','修改成功');
            }
        }
        $list = $data->getOptions();
        return $this->render('add', ['model' => $data, 'list' => $list]);
    }
    //删除
    public function actionDel(){
        try{
            $cateid = $this->method_get_value('cateid',1);
            if(empty($cateid)){
                throw new \Exception('数据为空，无法删除');
            }
            $data = Category::find()->where('cateid = :id ', [':id' => $cateid])->one();
            if(empty($data)){
                throw new \Exception('数据不存在，无法删除');
            }
            $data = Category::find()->where('parentid = :id ', [':id' => $cateid])->one();
            if(!empty($data)){
                throw new \Exception('存在子类，无法删除');
            }
            if(!Category::deleteAll('cateid = :id', [':id' => $cateid])){
                throw new \Exception('删除失败');
            }
            $this->set_flash_session('info','删除成功');
        }catch (\Exception $e){
            $this->set_flash_session('info',$e->getMessage());
        }
        return $this->redirect(['category/list']);
    }
    public function actionTree(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Category();
        $data = $model->getPrimaryCate();
        if(!empty($data)){
            return $data['data'];
        }
        return [];
    }
    public function actionRename(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(!IsPost){
            throw new \yii\web\MethodNotAllowedHttpException('Access denied');
        }
        $new_text = $this->method_post_value('new_text');
        $old = $this->method_get_value('old');
        $id = $this->method_post_value('id',1);
        if(empty($id) || empty($new_text)){
            return ['code' => -1,'message' => '参数错误'];
        }
        if($new_text == $old){
            return ['code' => 0,'message' => 'ok','data' => []];
        }
        $model = Category::findOne($id);
        $model->scenario = 'rename';
        $model->title = $new_text;
        if($model -> save()){
            return ['code' => 0,'message' => 'ok','data' => []];
        }
        return ['code' => 1,'message' => '失败','data' => []];
    }
}