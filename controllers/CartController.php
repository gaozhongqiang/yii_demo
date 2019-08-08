<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/5/28
 * Time: 15:57
 * 购物车
 */
namespace app\controllers;
use app\models\Cart;
use app\models\Product;
use app\models\User;
use Yii;

class CartController extends CommonController {
    public function actionIndex(){
        $this->layout = 'home_no_title';
        $this->isLogin();
        $userId = User::find()->where('username = :name',[':name' => Yii::$app->session['loginName']])->one()->userid;
        $cart = Cart::find()->where('userid = :uid',[':uid' => $userId])->asArray()->all();
        $products = Product::find()->where('productid in (:pid)',[':pid' => array_to_sql_str($cart,-1,2,'productid')])->asArray()->all();
        $data = array_reduce($cart,function ($result,$item) use ($products){
            $item['cover'] = $item['title'] = 0;
            if(array_key_exists($item['productid'],$products)){
                $item['cover'] = $products[$item['productid']]['cover'];
                $item['title'] = $products[$item['productid']]['title'];
            }
            $result[] = [
                'cover' => $item['cover'],
                'title' => $item['title'],
                'productnum' => $item['productnum'],
                'price' => $item['price'],
                'productid' => $item['productid'],
                'cartid' => $item['cartid'],
            ];
        },array());
        return $this->render('index',['data' => $data]);
    }
    public function actionAdd(){
        $this->isLogin();
        $userId = User::find()->where('username = :name',[':name' => Yii::$app->session['loginName']])->one()->userid;
        if(IsPost){
            $post = Yii::$app->request->post();
            $num = $post['productnum'];
            $data['Cart'] = $post;
            $data['Cart']['userid'] = $userId;
        }
        if(IsGet){
            $productid = Yii::$app->request->get('productid');
            $model = Product::find()->where('productid = :pid',[':pid' => $productid])->one();
            $price = $model->issale ? $model->saleprice : $model->price;
            $num = 1;
            $data['Cart'] = ['productid' => $productid,'productnum' => $num,'price' => $price,'userid' => $userId];
        }
        if(!$model = Cart::find()->where('productid = :pid AND userid = :uid',[':pid' => $data['Cart']['productid'],':uid' => $data['Cart']['userid']])->one()){
            $model = new Cart();
        }else{
            $data['Cart']['productnum'] = $model->productnum + $num;
        }
        $data['Cart']['createtime'] = time();
        $model->load($data);
        $model->save();
        return $this->redirect(['cart/index']);
    }
    public function actionMod(){
        $cartid = Yii::$app->request->get('cartid');
        $productnum = Yii::$app->request->get('productnum');
        Cart::updateAll(['productnum' => $productnum],'cartid = :cid',[':cid' => $cartid]);
    }
    public function actionDel(){
        $cartid = Yii::$app->request->get('cartid');
        Cart::deleteAll('cartid = :cid',[':cid' => $cartid]);
        return $this->redirect(['cart/index']);
    }
}