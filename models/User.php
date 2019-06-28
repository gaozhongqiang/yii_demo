<?php

namespace app\models;
use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    public $repass;
    public $login_time;
    public $remember_me = true;
    public static function tableName()
    {
        return '{{%user}}';
    }
    //html中文显示
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'useremail' => '电子邮箱',
            'userpass' => '密码',
            'repass' => '确认密码',
        ];
    }
    public function rules()
    {
        return [
            ['username',    'required',  'message' => '用户名不能为空',    'on' => ['reg']],
            ['username',    'unique',   'message' => '用户已经被注册',    'on' => ['reg']],
            ['useremail',   'required',  'message' => '电子邮件不能为空',    'on' => ['reg']],
            ['useremail',   'unique',   'message' => '电子邮件已被注册',    'on' => ['reg']],
            ['useremail',   'email',   'message' => '电子邮件格式不正确',    'on' => ['reg']],
            ['userpass',    'required',  'message' => '用户密码不能为空',    'on' => ['reg']],
            ['repass',    'required',  'message' => '确认密码不能为空',    'on' => ['reg']],
            ['repass',    'compare',    'compareAttribute' => 'userpass',  'message' => '两次密码输入不一致',    'on' => ['reg']],
        ];
    }

    //关联表操作
    public function getProfile(){
        return $this->hasOne(Profile::className(),['userid' => 'userid']);
    }
    public function reg($data, $scenario = 'reg'){
        $this->scenario = $scenario;
        if($this->load($data) && $this->validate()){
            $this->userpass = pwd_encrypt($this->userpass);
            $this->createtime = time();
            if($this->save(false)){
                return true;
            }
            return false;
        }
        return false;
    }
}
