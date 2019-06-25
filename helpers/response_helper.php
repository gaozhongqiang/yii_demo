<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/20
 * Time: 20:23
 * 请求定义简化函数
 */

defined('IsAjax') or define('IsAjax',isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) == 'XMLHTTPREQUEST');
defined('IsGet') or define('IsGet',strtoupper($_SERVER['REQUEST_METHOD']) == 'GET');
defined('IsPost') or define('IsPost',strtoupper($_SERVER['REQUEST_METHOD']) == 'POST');
defined('IsOptions') or define('IsOptions',strtoupper($_SERVER['REQUEST_METHOD']) == 'OPTIONS');
defined('IsDelete') or define('IsDelete',strtoupper($_SERVER['REQUEST_METHOD']) == 'DELETE');
defined('IsPut') or define('IsPut',strtoupper($_SERVER['REQUEST_METHOD']) == 'PUT');
defined('IsPatch') or define('IsPatch',strtoupper($_SERVER['REQUEST_METHOD']) == 'PATCH');