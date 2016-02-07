<?php

$module = new \stdClass;

$module->name    = 'Settings';
$module->title   = 'Настройки';
$module->version = '1.0';
$module->author  = 'Vlad Andreev <php.vlad@gmail.com>';

$module->tables = array(
    'settings' => array(
        'id'         => array('type' => 'int', 'length' => 10, 'not_null' => 1, 'auto_increment' => 1),
        'name'       => array('type' => 'text', 'not_null' => 1),
        'title'      => array('type' => 'text', 'not_null' => 1),
        'caption'    => array('type' => 'text', 'not_null' => 1),
        'type'       => array('type' => 'text', 'not_null' => 1),
        'value'      => array('type' => 'text', 'not_null' => 1),
        'module'     => array('type' => 'text', 'not_null' => 1),
        'options'    => array('type' => 'text', 'not_null' => 1),
        'attributes' => array('type' => 'text', 'not_null' => 1),
        'status'     => array('type' => 'int', 'length' => 1, 'not_null' => 1, 'default' => '0'),
        'success'    => array('type' => 'int', 'length' => 1, 'not_null' => 1, 'default' => '0'),
    ),
);

$module->data_types = array(
    'Setting' => array(
        'title' => 'Настройка',
        'entity' => 'Setting',
    ),
);

$module->menu = array(
    array(
        'name' => 'constructor',
        'childs' => array(
            array(
                'name' => 'settings',
                'title' => 'Настройки',
                'page' => 'list',
                'weight' => 30,
            ),
        ),
    ),
);

$module->content = array(
    'UserPermission' => array(
        array(
            'title' => 'Настройки',
            'page' => 'list',
            'action' => 'save',
        ),
    ),
    'Setting' => array(
        array(
            'name' => 'site_theme',
            'type' => 'select',
            'title' => 'Тема сайта',
            'caption' => 'Тема сайта',
            'value' => 'distillate',
        ),
    )
);

return $module;