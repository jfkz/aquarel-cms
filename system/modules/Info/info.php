<?php

$module = new \stdClass;

$module->name    = 'Info';
$module->title   = 'Информационный модуль';
$module->version = '1.0';
$module->author  = 'Vlad Andreev <php.vlad@gmail.com>';

$module->menu = array(
    array(
        'name' => 'constructor',
        'childs' => array(
            array(
                'name'   => 'info',
                'title'  => 'Инфо-центр',
                'page'   => 'list',
                'weight' => 50,
            )
        )
    )
);

$module->content = array(
    'UserPermission' => array(
        array(
            'title'  => 'Информация о системе',
            'page'   => 'list',
            'action' => 'list',
        )
    )
);

return $module;