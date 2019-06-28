<?php
/**
 * Created by PhpStorm.
 * User: gaozhongqiang
 * Date: 2019/6/2
 * Time: 11:10
 */

namespace app\modules\models;

use Codeception\Specify\Config;
use yii\db\ActiveRecord;
use Yii;

class Admin extends ActiveRecord
{
    public $rememberMe;
    public $admin_repass;

    public static function tableName()
    {
        return "{{%admin}}";
    }

    //用于字符串解释
    public function attributeLabels()
    {
        return [
            'admin_user' => '管理员账号',
            'admin_email' => '管理员邮箱',
            'admin_pass' => '管理员密码',
            'admin_repass' => '确认密码',
        ];
    }

    //规则
    public function rules()
    {
        return [
            ['admin_user', 'required', 'message' => '管理员账号不能为空', 'on' => ['login', 'seekpass', 'changepass', 'adminadd', 'changeemail']],
            ['admin_pass', 'required', 'message' => '管理员密码不能为空', 'on' => ['login', 'changepass', 'adminadd', 'changeemail']],
            ['rememberMe', 'boolean', 'on' => 'login'],
            ['admin_pass', 'validatePass', 'on' => ['login', 'changeemail']],
            ['admin_email', 'required', 'message' => '电子邮箱不能为空', 'on' => ['seekpass', 'adminadd', 'changeemail']],
            ['admin_email', 'email', 'message' => '电子邮箱格式不正确', 'on' => ['seekpass', 'adminadd', 'changeemail']],
            ['admin_email', 'unique', 'message' => '电子邮箱已被注册', 'on' => ['adminadd', 'changeemail']],
            ['admin_user', 'unique', 'message' => '管理员已被注册', 'on' => 'adminadd'],
            ['admin_email', 'validateEmail', 'on' => 'seekpass'],
            ['admin_repass', 'required', 'message' => '确认密码不能为空', 'on' => ['changepass', 'adminadd']],
            ['admin_repass', 'compare', 'compareAttribute' => 'admin_pass', 'message' => '两次密码输入不一致', 'on' => ['changepass', 'adminadd']],
        ];
    }


    //登陆
    public function login($data)
    {
        $this->setScenario('login');
        if ($this->load($data) && $this->validate()) {
            $liftTime = $this->rememberMe ? 24*86400 : 0;
            $session = Yii::$app->session;
            $state = $this->updateAll(['login_time' => time(), 'login_ip' => ip2long(Yii::$app->request->userIP)], 'admin_user=:admin_user', [':admin_user' => $this->admin_user]);
            if ($state) {
                $session['admin'] = [
                    'admin_user' => $this->admin_user,
                    'isLogin' => 1,
                    'expire_time' => time()+$liftTime
                ];
                return true;
            }
            return false;
        }
        return false;
    }
    //找回密码
    public function seekPass($data){
        $this->scenario = 'seekpass';
        if($this->load($data) && $this->validate()){
            $time = time();
            $token = create_token_find_pass($data['Admin']['admin_user'],$time);
            $mailer = Yii::$app->mailer->compose('seekpass',['admin_user' => $data['Admin']['admin_user'], 'time' => $time, 'token' => $token]);
            $mailer->setTo($data['Admin']['admin_email']);
            $mailer->setSubject('找回密码');
            if($mailer->send()){
                return true;
            }
        }
        return false;
    }
    //添加管理员
    public function add($data)
    {
        $this->scenario = 'adminadd';
        if ($this->load($data) && $this->validate()) {
            $this->admin_pass = pwd_encrypt($this->admin_pass);
            if ($this->save(false)) {
                return true;
            }
            return false;
        }
        return false;
    }
    //修改
    public function edit($data,$id){
        $this->scenario = 'edit';
        if($this->load($data)){
            $update_arr = array(
                'admin_user' => $data['admin_user'],
                'admin_email' => $data['admin_email'],
                'update_time' => time(),
            );
            !empty($data['admin_pass']) && $update_arr['admin_pass'] = pwd_encrypt($data['admin_pass']);
            return self::updateAll($update_arr,'id = :id',[':id' => $id]);
        }
        return false;
    }
    //修改电子邮箱
    public function changeEmail($data){
        $this->scenario = 'changeemail';
        if($this->load($data) && $this->validate()){
            return (bool)self::updateAll(['admin_email' => $this->admin_email],'admin_user = :user',[':user' => $this->admin_user]);
        }
        return false;
    }
    //修改密码
    public function changePass($data){
        $this->scenario = 'changepass';
        if($this->load($data) && $this->validate()){
            return (bool)self::updateAll(['admin_pass' => pwd_encrypt($this->admin_pass)],'admin_user = :user',[':user' => $this->admin_user]);
        }
        return false;
    }
    //验证密码
    public function validatePass()
    {
        if (!$this->hasErrors()) {
            $data = self::find()->where('admin_user = :user and admin_pass = :pass', [':user' => $this->admin_user, ':pass' => pwd_encrypt($this->admin_pass)])->one();
            if (is_null($data)) {
                $this->addError('admin_pass', '用户名或者密码错误');
            }
        }
    }

    //添加管理员判断用户名是否存在
    public function validateUser()
    {

        $data = self::find()->where('admin_user = :user', [':user' => $this->admin_user])->one();
        if (!is_null($data)) {
            $this->addError('admin_user', '用户名已存在');

        }


    }

    //添加管理员判断邮箱是否存在
    public function validateEmail()
    {

        $data = self::find()->where('admin_email = :email AND admin_user = :user', [':email' => $this->admin_email,':user' => $this->admin_user])->one();
        if (!is_null($data)) {
            $this->addError('admin_email', '邮箱已存在');

        }


    }

    //判断自己的邮箱是否存在
    public function validateUniqueEmail(){
        $data = self::find()->where('admin_email = :email AND id!=:id', [':email' => $this->admin_email,':id' => $this->id])->one();
        if (!is_null($data)) {
            $this->addError('admin_email', '邮箱已存在');

        }
    }

}