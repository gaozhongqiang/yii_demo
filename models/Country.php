<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/29
 * Time: 17:31
 */
namespace app\models;
use yii\db\ActiveQuery;
class Country extends ActiveQuery{
    public static function tableName(){
        return "{{%country}}";
    }
}