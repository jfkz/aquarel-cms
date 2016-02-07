<?php
define('SMARTY_DIR','../lib/smarty/libs/');
require_once(SMARTY_DIR . 'Smarty.class.php');

$smarty = new Smarty();

$smarty->template_dir = 'templates/';
$smarty->compile_dir = 'templates_c/';
$smarty->config_dir = 'configs/';
$smarty->cache_dir = 'cache/';

//$smarty->assign('name', 'Катруська');

//** раскомментируйте следующую строку для отображения отладочной консоли
//$smarty->debugging = true;

$smarty->display('index.tpl');
?> 