<?php
/**
 * Created by PhpStorm.
 * User: gaozhongqiang
 * Date: 2019/6/2
 * Time: 11:10
 */
namespace app\modules\models;
use yii\db\ActiveRecord;
use Yii;

class Admin extends ActiveRecord {
    public $rememberMe;
    public static function tableName()
    {
        return "{{%admin}}";
    }
    //规则
    public function rules()
    {
        return [
            ['adminuser', 'required', 'message' => '管理员账号不能为空'],
            ['adminpass', 'required', 'message' => '管理员密码不能为空'],
            ['adminpass', 'validatePass'],
            ['rememberMe', 'boolean']
        ];
    }
    //场景
    public function scenarios()
    {
        return [
            'login' => ['adminuser','adminpass','rememberMe']//登陆参数
        ];
    }

    //登陆
    public function login($data){
        $this->setScenario('login');
        if($this->load($data) && $this->validate()){
            $liftTime = $this->rememberMe ? 86400 : 0;
            $session = Yii::$app->session;
            $state = $this->updateAll(['logintime' => time(),'loginip' => ip2long(Yii::$app->request->UserIp)],'adminuser=:adminuser',[':adminuser' => $this->adminuser]);
            if($state > 0){
                session_set_cookie_params($liftTime);
                $session['admin'] = [
                    'adminuser' => $this->adminuser,
                    'isLogin' => 1
                ];
                return true;
            }
            return  false;
        }
        return false;
    }
    //验证密码
    public function validatePass(){
        if(!$this->hasErrors()){
            $data = self::find()->where('adminuser = :adminuser and adminpass = :adminpass',[':adminuser' => $this->adminuser,':adminpass' => md5($this->adminpass)])->one();
            if(is_null($data)){
                $this->addError('adminpass','用户名或者密码错误');
            }
        }
    }

}