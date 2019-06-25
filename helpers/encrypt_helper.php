<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/6/20
 * Time: 19:50
 * 加密
 */
//密码加密
if(!function_exists('pwd_encrypt')){
    function pwd_encrypt($pwd){
        return md5(sha1(md5($pwd)));
    }
}
