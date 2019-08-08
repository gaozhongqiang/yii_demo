<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/8/8
 * Time: 17:41
 */
namespace app\models;
use yii\db\ActiveRecord;

class Cart extends ActiveRecord{
    public static function tableName()
    {
        return "{{%cart}}";
    }
}