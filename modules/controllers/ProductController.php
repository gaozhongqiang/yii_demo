<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/6/27
 * Time: 19:25
 * 商品列表
 */
namespace app\modules\controllers;
use app\models\Product;
use app\models\Category;
use crazyfd\qiniu\Qiniu;
use yii\data\Pagination;

class ProductController extends CommonController{
    public $hot = ['不热卖', '热卖'];
    public $sale = ['不促销', '促销'];
    public $on = ['下架', '上架'];
    public $istui = ['不推荐', '推荐'];
    //列表
    public function actionList(){
        $model = Product::find();
        $pager = new Pagination(['totalCount' => $model->count(),'pageSize' => $this->get_page_size('product')]);
        $data = $model->offset($pager->offset)->limit($pager->limit)->asArray()->all();
        $data = array_map(function ($value){
            $value['cover_small'] = $value['cover'].'-coversmall';
            $value['hot'] = $this->hot[$value['hot']];
            $value['issale'] = $this->sale[$value['issale']];
            $value['ison'] = $this->on[$value['ison']];
            $value['istui'] = $this->istui[$value['istui']];
            return $value;
        },$data);
        return $this->render('list', ['pager' => $pager,'products' => $data]);
    }
    //添加
    public function actionAdd(){
        $model = new Product();
        $cate = new Category;
        $list = $cate->getOptions();
        unset($list[0]);
        if(IsPost){
            $data = $this->method_post_value();
            $pics = $this->upload();
            if(!$pics){
                $model->addError('cover','封面不能为空');
            }else{
                $data['Product']['cover'] = $pics['cover'];
                $data['Product']['pics'] = $pics['pics'];
            }
            if($pics && $model->add($data)){
                $this->set_flash_session('info','添加成功');
            }else{
                $this->set_flash_session('info','添加失败');
            }
        }
        return $this->render('add',[
            'model' => $model,
            'opts' => $list,
            'hot' => $this->hot,
            'sale' => $this->sale,
            'on' => $this->on,
            'istui' => $this->istui]);
    }
    //上传
    private function upload(){
        if($_FILES['Product']['error']['cover'] > 0){
            return false;
        }
        $qiniu = new Qiniu(Product::AK,Product::SK,Product::DOMAIN,Product::BUCKET);
        $key = uniqid();
        $qiniu->uploadFile($_FILES['Product']['tmp_name']['cover'],$key);
        $cover = $qiniu->getLink($key);
        $pics = [];
        foreach ($_FILES['Product']['tmp_name']['cover'] as $k => $file){
            if ($_FILES['Product']['error']['pics'][$k] > 0) {
                continue;
            }
            $key = uniqid();
            $qiniu->uploadFile($file,$key);
            $pics[$key] = $qiniu->getLink($key);
        }
        return ['cover'=> $cover,'pics' => $pics];
    }
    public function actionMod(){
        $cate = new Category;
        $list = $cate->getOptions();
        $productid = $this->method_get_value('productid');
        $model = Category::find()->where('productid = :pid',[':pid' => $productid])->one();
        if(IsPost){
            $post = $this->method_post_value();
            $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
            $post['Product']['cover'] = $model->cover;
            if ($_FILES['Product']['error']['cover'] == 0) {
                $key = uniqid();
                $qiniu->uploadFile($_FILES['Product']['tmp_name']['cover'], $key);
                $post['Product']['cover'] = $qiniu->getLink($key);
                $qiniu->delete(basename($model->cover));

            }
            $pics = [];
            foreach($_FILES['Product']['tmp_name']['pics'] as $k => $file) {
                if ($_FILES['Product']['error']['pics'][$k] > 0) {
                    continue;

                }
                $key = uniqid();
                $qiniu->uploadfile($file, $key);
                $pics[$key] = $qiniu->getlink($key);

            }
            $post['Product']['pics'] = json_encode(array_merge((array)json_decode($model->pics, true), $pics));
            if ($model->load($post) && $model->save()) {
                $this->set_flash_session('info', '修改成功');

            }
        }
        return $this->render('add', ['model' => $model, 'opts' => $list]);
    }
    public function actionRemovepic(){
        $key = $this->method_get_value('key');
        $productid = $this->method_get_value('productid');
        $model = Product::find()->where('productid = :pid', [':pid' => $productid])->one();
        $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
        $qiniu->delete($key);
        $pics = json_decode($model->pics, true);
        unset($pics[$key]);
        Product::updateAll(['pics' => json_encode($pics)], 'productid = :pid', [':pid' => $productid]);
        return $this->redirect(['product/mod', 'productid' => $productid]);
    }
    public function actionDel(){
        $productid = $this->method_get_value('productid');
        $model = Product::find()->where('productid = :pid', [':pid' => $productid])->one();
        $key = basename($model->cover);
        $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
        $qiniu->delete($key);
        $pics = json_decode($model->pics, true);
        foreach($pics as $key=>$file) {
            $qiniu->delete($key);
        }
        Product::deleteAll('productid = :pid',[':pid' => $productid]);
        return $this->redirect(['product/list']);
    }
    public function actionOn()
    {
        $productid = $this->method_get_value("productid");
        Product::updateAll(['ison' => '1'], 'productid = :pid', [':pid' => $productid]);
        return $this->redirect(['product/list']);
    }
    public function actionOff()
    {
        $productid = $this->method_get_value("productid");
        Product::updateAll(['ison' => '0'], 'productid = :pid', [':pid' => $productid]);
        return $this->redirect(['product/list']);
    }
}