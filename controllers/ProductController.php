<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/5/27
 * Time: 20:33
 * 商品页
 */
namespace app\controllers;
use app\models\Product;
use Yii;
use yii\data\Pagination;

class ProductController extends CommonController {
    //产品首页
    public function actionIndex(){
        $this->layout = 'home_title';
        $cid = Yii::$app->request->get('cateid');
        $where = "cateid = :cid and ison = '1'";
        $params = [':cid' => $cid];
        $model = Product::find()->where($where,$params);
        $count = $model->count();
        $pageSize = Yii::$app->params['pageSize']['frontproduct'];
        $pager = new Pagination(['totalCount' => $count,'pageSize' => $pageSize]);
        $all = $model->offset($pager->offset)->limit($pager->limit)->asArray()->all();
        $tui = $model->Where($where . ' and istui = \'1\'', $params)->orderby('createtime desc')->limit(5)->asArray()->all();
        $hot = $model->Where($where . ' and ishot = \'1\'', $params)->orderby('createtime desc')->limit(5)->asArray()->all();
        $sale = $model->Where($where . ' and issale = \'1\'', $params)->orderby('createtime desc')->limit(5)->asArray()->all();
        return $this->render("index", ['sale' => $sale, 'tui' => $tui, 'hot' => $hot, 'all' => $all, 'pager' => $pager, 'count' => $count]);
    }
    //单个产品页
    public function actionSingle(){
        $productid = Yii::$app->request->get("productid");
        $product = Product::find()->where('productid = :id', [':id' => $productid])->asArray()->one();
        return $this->render("detail", ['product' => $product]);
    }
}