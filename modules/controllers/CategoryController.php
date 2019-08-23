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

class CategoryController extends CommonController{
    //列表
    public function actionList()
    {
        $model = new Category();
        $cates = $model->getTreeList();
        return $this->render('cates',['cates' => $cates]);
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
}