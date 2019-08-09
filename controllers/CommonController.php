<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/8/8
 * Time: 17:35
 * 公共控制
 */
namespace app\controllers;
use app\models\Cart;
use app\models\Category;
use app\models\Product;
use app\models\User;
use yii\web\Controller;
use Yii;

class CommonController extends Controller{
    public function init()
    {
        $menu = Category::getMenu();
        $this->view->params['menu'] = $menu;
        $data = [
            'products' => []
        ];
        $total = 0;
        if(Yii::$app->session['isLogin']){
            $userId = User::find()->where("username = :name",[":name" => Yii::$app->session['LoginName']])->one()->userid;
            if(!empty($userId)){
                $carts = Cart::find()->where("userid = :uid",[':uid' => $userId])->asArray()->all();
                $products = Product::find()->where("productid in (:productids)",[':productids' => array_to_sql_str($carts,-1,2,'productid')])->asArray()->one();
                $products = array_set_key($products,'productid');
                $data['products'] = array_reduce($carts,function ($result,$item) use ($products,&$total){
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
                    $total = bcmul($item['price'],$item['productnum'],2);
                    return $result;
                },[]);
            }
        }
        $data['total'] = $total;
        $this->view->params['cart'] = $data;
    }
    public function isLogin(){
        if(Yii::$app->session['isLogin'] != 1){
            return $this->redirect('member/auth');
        }
        return true;
    }
    public function getUserId(){
        $loginname = Yii::$app->session['loginname'];
        return User::find()->where('username = :name or useremail = :email', [':name' => $loginname, ':email' => $loginname])->one()->userid;

    }
}