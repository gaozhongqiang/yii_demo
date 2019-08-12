<?php
/**
 * Created by PhpStorm.
 * User: gaozhongqiang
 * Date: 2019/6/2
 * Time: 10:21
 * 公共
 */
namespace app\modules\controllers;
use yii\web\Controller;
use Yii;
class CommonController extends Controller{
    public function init()
    {
        $this->layout = 'common';
        if($this->get_admin_session('isLogin') != 1){
           return $this->redirect(['/admin/public/login']);
        }
    }
    public function set_flash_session($key,$value){
        Yii::$app->session->setFlash($key,$value);
    }
    //获取后台的key
    public function get_admin_session($key = ''){
        return Yii::$app->session['admin'][$key];
    }
    //get获取值
    public function method_get_value($key_name = '',$is_num = 0){
        if(empty($key_name)){
            return Yii::$app->request->get();
        }
        $data = Yii::$app->request->get($key_name);
        if(empty($is_num)){
            return preg_match("/^\s+|\s+$/","",filter_input_value($data));
        }
        return intval($data);
    }
    //post获取值
    public function method_post_value($key_name = '',$is_num = 0){
        if(empty($key_name)){
            return Yii::$app->request->post();
        }
        $data = Yii::$app->request->post($key_name);
        if(empty($is_num)){
            if(is_array($data)){
                foreach ($data as $key => $value){
                    $data[$key] =  preg_match("/^\s+|\s+$/","",filter_input_value($value));
                }
                return $data;
            }else{
                return preg_match("/^\s+|\s+$/","",filter_input_value($data));
            }
        }
        if(is_array($data)){
            foreach ($data as $key => $value){
                $data[$key] =  intval($value);
            }
            return $data;
        }
        return intval($data);
    }
    //获取后台普通配置
    public function get_config($field){
        return Yii::$app->params['admin'][$field];
    }
    //获取分页
    public function get_page_size($field){
        return Yii::$app->params['admin']['pageSize'][$field];
    }
}