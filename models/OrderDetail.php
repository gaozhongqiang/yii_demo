<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/8/9
 * Time: 16:48
 */
namespace app\models;
use yii\db\ActiveRecord;

class OrderDetail extends ActiveRecord{
    public static function tableName()
    {
        return "{{%order_detail}}";
    }
    public function rules()
    {
        return [
            [['productid','productnum','price','orderid','createtime'],'required']
        ];
    }
    public function add($data){
        if($this->load($data) && $this->save()){
            return true;
        }
        return false;
    }
}