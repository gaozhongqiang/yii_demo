<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/6/26
 * Time: 19:38
 * 附加信息
 */
namespace app\models;
use yii\db\ActiveRecord;
class Profile extends ActiveRecord{
    public static function tableName()
    {
        return "{{%profile}}";
    }
}