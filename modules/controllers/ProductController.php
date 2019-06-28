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
use yii\data\Pagination;

class ProductController extends CommonController{
    public $hot = ['不热卖', '热卖'];
    public $sale = ['不促销', '促销'];
    public $on = ['下架', '上架'];
    public $istui = ['不推荐', '推荐'];
    //列表
    public function actionList(){
        $model = Product::find()->where(Product::tableName().'.del = 0')->joinWith('category');
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
    }
}