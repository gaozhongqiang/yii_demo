<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/8/8
 * Time: 18:52
 *
 */
namespace app\models;
use yii\db\ActiveRecord;

class Address extends ActiveRecord{
    public static function tableName()
    {
        return "{{%address}}";
    }
    public function rules()
    {
        return [
            [['userid','firstname','lastname','address','email','telephone'],'required'],
            [['createtime','postcode'],'safe']
        ];
    }
}