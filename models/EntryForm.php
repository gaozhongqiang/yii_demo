<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/29
 * Time: 16:58
 */
namespace app\models;
use yii\base\Model;
class EntryForm extends Model{
    public $name;
    public $email;
    public $table = 'entry';
    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            ['email', 'email'],
        ];
    }
}