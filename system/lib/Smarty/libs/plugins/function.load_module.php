<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.load_module.php
 * Type:     function
 * Name:     load_module
 * Purpose:  outputs a modules plugins
 * -------------------------------------------------------------
 */
function smarty_function_load_module($params, &$smarty)
{
    $db = &$smarty->getRegisteredObject('db');
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    
    if (empty($params['module'])) $params['module'] = 'plugins';
    if (empty($params['plugin'])) $params['plugin'] = 'plugin';
    
    if (is_file($root_dir.'/custom/modules/' . $params['module'] . '/plugin.' . $params['plugin'] . '.php'))
        {
        require($root_dir.'/custom/modules/' . $params['module'] . '/plugin.' . $params['plugin'] . '.php');
        }
    else
        {
        if (is_file($root_dir.'/system/modules/' . $params['module'] . '/plugin.' . $params['plugin'] . '.php'))
            {
            require($root_dir.'/system/modules/' . $params['module'] . '/plugin.' . $params['plugin'] . '.php');
            }
        else echo 'Плагин <strong>'.$params['plugin'].'</strong> не найден';
        }
    unset($adodb, $errors, $security, $vars);
}
?>