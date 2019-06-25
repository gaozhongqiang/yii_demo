<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/6/24
 * Time: 20:06
 * 普通工具
 */
//通过邮箱找回密码
if(!function_exists('create_token_find_pass')){
    function create_token_find_pass($admin_user,$time){
        return md5(md5($admin_user).base64_encode(Yii::$app->request->userIP).md5($time));
    }
}