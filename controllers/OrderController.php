<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/5/28
 * Time: 16:00
 * 订单
 */
namespace app\controllers;
use app\models\Address;
use app\models\Cart;
use app\models\Order;
use app\models\OrderDetail;
use app\models\Pay;
use app\models\Product;
use app\models\User;
use Yii;
use yii\db\Exception;

class OrderController extends CommonController {
    //订单首页
    public function actionIndex(){
        $this->layout = 'layout2';
        $this->isLogin();
        $loginName = Yii::$app->session['loginName'];
        $userId = User::find()->where('username = :name and useremail = :email',[':name' => $loginName, ':email'=>$loginName])->one()->userid;
        $orders = Order::getProducts($userId);
        return $this->render('index',['orders' => $orders]);
    }
    public function actionCheck(){
        $this->layout = "layout1";
        $this->isLogin();
        $orderid = Yii::$app->request->get('orderid');
        $status = Order::find()->where('orderid = :oid',[':oid' => $orderid])->one()->status;
        if($status !=Order::CREATEORDER && $status != Order::CHECKORDER){
            return $this->redirect(['order/index']);
        }
        $userid = $this->getUserId();
        $addresses = Address::find()->where('userid = :uid',[':uid' => $userid])->asArray()->all();
        $details = OrderDetail::find()->where('orderid = :oid',[':oid' => $orderid])->asArray()->all();
        $data = [];
        foreach ($details as $detail){
            $model = Product::find()->where('productid = :pid',[':pid' => $detail['productid']])->one();
            $detail['title'] = $model->title;
            $detail['cover'] = $model->cover;
            $data[] = $detail;
        }
        $express = Yii::$app->params['express'];
        $expressPrice = Yii::$app->params['expressPrice'];
        return $this->render('check',['express' => $express,'expressPrice' => $expressPrice,'addresses' => $addresses,'products' => $data]);
    }
    public function actionAdd(){
        $this->isLogin();
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(IsPost){
                $post = Yii::$app->request->post();
                $orderModel = new Order;
                $orderModel->scenario = 'add';
                $userId = $this->getUserId();
                if(empty($userId)){
                    throw new \Exception();
                }
                $orderModel->userid = $userId;
                $orderModel->status = Order::CREATEORDER;
                $orderModel->createtime = time();
                if(!$orderModel->save()){
                    throw new \Exception();
                }
                $orderId = $orderModel->getPrimaryKey();
                foreach ($post['OrderDetail']as $product){
                    $model = new OrderDetail;
                    $product['orderid'] = $orderId;
                    $product['createtime'] = time();
                    $data['OrderDetail'] = $product;
                    if(!$model->save($data)){
                        throw new \Exception();
                    }
                    Cart::deleteAll('productid = :pid',[':pid' => $product['productid']]);
                    Product::updateAllCounters(['num' => -$product['productnum']],'productid = :pid',[':pid' => $product['productid']]);
                }
            }
            $transaction->commit();
        }catch (\Exception $exception){
            $transaction->rollBack();
            return $this->redirect(['cart/index']);
        }
        return $this->redirect(['order/check', 'orderid' => $orderId]);
    }
    public function actionConfirm(){
        try{
            $this->isLogin();
            if(IsPost){
                throw new \Exception();
            }
            $post = Yii::$app->request->post();
            $userid = $this->getUserId();
            if(empty($userid)){
                throw new \Exception();
            }
            $model = Order::find()->where('orderid = :oid and userid = :uid',[':oid' => $post['orderid'],':uid' => $userid])->one();
            if(empty($model)){
                throw new \Exception();
            }
            $model->scenario = 'update';
            $post['status'] = Order::CHECKORDER;
            $details = OrderDetail::find()->where('orderid = :oid',[':oid' => $post['orderid']])->all();
            $amount = 0;
            foreach ($details as $detail){
                $amount += $detail->productnum*$detail->price;
            }
            if($amount <= 0){
                throw new \Exception();
            }
            $express = Yii::$app->params['expressPrice'][$post['expressid']];
            if($express < 0){
                throw new \Exception();
            }
            $amount += $express;
            $post['amount'] = $amount;
            $data['Order'] = $post;
            if($model->load($data) && $model->save()){
                return $this->redirect(['order/pay', 'orderid' => $post['orderid'], 'paymethod' => $post['paymethod']]);
            }
        }catch (\Exception $exception){
            return $this->redirect(['index/index']);
        }
    }
    public function actionPay(){
        $this->isLogin();
        try{
            $orderid = Yii::$app->request->get('orderid');
            $paymethod = Yii::$app->request->get('paymethod');
            if(empty($orderid) || empty($paymethod)){
                throw new \Exception();
            }
            if($paymethod = 'alipay'){
                return Pay::alipay($orderid);
            }
        }catch (\Exception $exception){

        }
        return $this->redirect(['order/index']);
    }
    public function actionGetexpress(){
        $expressno = Yii::$app->request->get('expressno');
        $res = Express::search($expressno);
        echo $res;
        exit();
    }
    //订单详情页
    public function actionReceived(){
        $orderid = Yii::$app->request->get('orderid');
        $order = Order::find()->where('orderid = :oid', [':oid' => $orderid])->one();
        if (!empty($order) && $order->status == Order::SENDED) {
            $order->status = Order::RECEIVED;
            $order->save();
        }
        return $this->redirect(['order/index']);
    }
}