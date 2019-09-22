<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/8/12
 * Time: 19:41
 */
namespace app\modules\controllers;
use app\models\Order;
use Yii;
use yii\data\Pagination;

class OrderController extends CommonController {
    public function actionList(){
        $model = Order::find();
        $count = $model->count();
        $pageSize = $this->get_page_size('order');
        $pager = new Pagination(['totalCount' => $count,'pageSize' => $pageSize]);
        $data = $model->offset($pager->offset)->limit($pager->limit)->all();
        $data = Order::getDetail($data);
        return $this->render('list',['pager' => $pager,'orders' => $data]);
    }
    public function actionDetail(){
        $orderid = $this->method_get_value('orderid');
        $order = Order::find()->where('orderid = :oid',[':oid' => $orderid])->one();
        $data = Order::getData($order);
        return $this->render('detail',['order' => $data]);
    }
    public function actionSend(){
        $orderid = $this->method_get_value('orderid');
        $model = Order::find()->where('orderid = :oid',[':oid' => $orderid])->one();
        $model->scenario = 'send';
        if(IsPost){
            $post = $this->method_post_value();
            $model->status = Order::SENDED;
            if($model->load($post) && $model->save()){
                $this->set_flash_session('info','发货成功');
            }
        }
        return $this->render('send',['model' => $model]);
    }
}