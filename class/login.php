<?
define('ROOT_DIR_SERVER',dirname(dirname(dirname(__FILE__))));
define('DATA_DIR_SERVER',ROOT_DIR_SERVER.'/server_lib/data');
define('CLASS_DIR_SERVER',ROOT_DIR_SERVER.'/server_lib/class');

//include_once CLASS_DIR_SERVER.'/login.php';

/**
 * Проверка авторизации
 */
//if($showIndex) $is_logged = $login->check(false);
//else $is_logged = $login->check(true);

$is_logged = 'root';
?>