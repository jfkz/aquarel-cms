<?php

$module = new \stdClass;

$module->name    = 'Menu';
$module->title   = 'Меню панели управления';
$module->version = '1.0';
$module->author  = 'Vlad Andreev <php.vlad@gmail.com>';

$module->tables = array(
    'menu' => array(
        'id' => array('type' => 'int', 'length' => 10, 'not_null' => 1, 'auto_increment' => 1),
        'parent'=>array('type' => 'int', 'length' => 10, 'not_null' => 1, 'default' => '0'),
        'weight'=>array('type' => 'int', 'length' => 10, 'not_null' => 1, 'default' => '0'),
        'level'=>array('type' => 'int', 'length' => 10, 'not_null' => 1, 'default' => '0'),
        'name'=>array('type' => 'text', 'not_null' => 1),
        'title'=>array('type' => 'text', 'not_null' => 1),
        'module'=>array('type' => 'text', 'not_null' => 1),
        'data'=>array('type' => 'text', 'not_null' => 1),
        'page'=>array('type' => 'text', 'not_null' => 1),
        'options'=>array('type' => 'text', 'not_null' => 1),
        'childs'=>array('type' => 'text', 'not_null' => 1),
        'active'=>array('type' => 'int', 'length' => 1, 'not_null' => 1, 'default' => '0'),
    ),
);

$module->data_types = array(
    'Menu' => array(
        'title' => 'Пункт меню',
        'entity' => 'Menu',
        'fields'=> array(
            'name' => array(
                'title' => 'Идентификатор',
                'type' => 'textbox'
            ),
            'title' => array(
                'title' => 'Название',
                'type' => 'textbox',
            ),
            'active' => array(
                'title' => 'Активен',
                'type' => 'checkbox',
            ),
            'page' => array(
                'title' => 'страница',
                'type' => 'textbox',
            ),
            'parent' => array(
                'title' => 'Раздел меню',
                'type' => 'select',
                'options' => 'a:4:{s:6:"module";s:4:"Menu";s:6:"entity";s:4:"Menu";s:5:"index";s:2:"id";s:5:"title";s:5:"title";}',
                'default_value' => '11',
                'status' => 'db',
            ),
            'module' => array(
                'title' => 'Модуль',
                'type' => 'select',
                'options' => 'a:4:{s:6:"module";s:7:"Modules";s:6:"entity";s:6:"Module";s:5:"index";s:4:"name";s:5:"title";s:5:"title";}',
                'default_value' => 'users',
                'status' => 'db',
            ),
            'options' => array(
                'title' => 'Отдельный список опций',
                'type' => 'checkbox',
            ),
        ),
    ),
);

$module->menu = array(
    array(
        'name' => 'constructor',
        'childs' => array(
            array(
                'name' => 'menu',
                'title' => 'Меню',
                'page' => 'list',
                'childs' => 'add,edit',
                'weight' => 20,
            ),
        ),
    ),
);

$module->content = array(
    'UserPermission' => array(
        array('title' => 'Меню панели управления', 'page' => 'list', 'action' => 'list'),
        array('title' => 'Добавление пунктов меню', 'page' => 'add', 'action' => 'add'),
        array('title' => 'Редактирование пунктов меню', 'page' => 'edit', 'action' => 'edit'),
        array('title' => 'Изменение структуры меню', 'action' => 'reorder'),
        array('title' => 'Удаление пунктов меню', 'action' => 'delete'),
    )
);

return $module;