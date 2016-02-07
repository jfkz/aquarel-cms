<?php

$module = new \stdClass;

$module->name    = 'Modules';
$module->title   = 'Модули системы';
$module->version = '1.0';
$module->author  = 'Vlad Andreev <php.vlad@gmail.com>';

$module->tables = array(
    'modules' => array(
        'id' => array('type' => 'int', 'length' => 10, 'not_null' => 1, 'auto_increment' => 1),
        'name' => array('type' => 'text', 'not_null' => 1),
        'title' => array('type' => 'text', 'not_null' => 1),
        'caption' => array('type' => 'text', 'not_null' => 1),
        'module_group' => array('type' => 'text', 'not_null' => 1),
        'version' => array('type' => 'float', 'not_null' => 1),
        'install' => array('type' => 'int', 'length' => 1, 'not_null' => 1, 'default' => '0'), 
        'active' => array('type' => 'int', 'length' => 1, 'not_null' => 1, 'default' => '0')
    )
);

$module->menu = array(
    array(
        'name' => 'constructor',
        'childs' => array(
            array(
                'name' => 'modules',
                'title' => 'Модули системы',
                'page' => 'list',
                'weight' => 40,
            )
        )
    )
);

$module->content = array(
    'UserPermission' => array(
        array('title' => 'Список модулей', 'page' => 'list', 'action' => 'list'),
        array('title' => 'Установка модулей', 'action' => 'install'),
        array('title' => 'Удаление модулей', 'action' => 'uninstall'),
        array('title' => 'Активация модулей', 'action' => 'activate'),
        array('title' => 'Деактивация модулей', 'action' => 'deactivate'),
        array('title' => 'Определение группы модуля', 'action' => 'get_module_group')
    )
);

return $module;