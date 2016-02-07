<?php

$module = new \stdClass;

$module->name    = 'Blocks';
$module->title   = 'Блоки';
$module->version = '1.0';
$module->author  = 'Vlad Andreev <php.vlad@gmail.com>';

$module->tables = array(
    'blocks' => array(
        'id' => array('type' => 'int', 'length' => 10, 'not_null' => 1, 'auto_increment' => 1),
        'name' => array('type' => 'text', 'not_null' => 1),
        'title' => array('type' => 'text', 'not_null' => 1),
        'block' => array('type' => 'text', 'not_null' => 1),
        'text' => array('type' => 'text', 'not_null' => 1),
        'page' => array('type' => 'text', 'not_null' => 1),
        'module' => array('type' => 'text', 'not_null' => 1),
        'position' => array('type' => 'text', 'not_null' => 1),
        'weight' => array('type' => 'int', 'length' => 10, 'not_null' => 1, 'default' => '0'),
        'active' => array('type' => 'int', 'length' => 1, 'not_null' => 1, 'default' => '0'),
    )
);

$module->data_types = array(
    'Block' => array(
        'title' => 'Блок',
        'entity' => 'Block',
        'fields' => array(
            'name' => array(
                'title' => 'Идентификатор',
                'type' => 'textbox'
            ),
            'title' => array(
                'title' => 'Название',
                'type' => 'textbox'
            ),
            'position' => array(
                'title' => 'Область вывода',
                'type' => 'textbox'
            ),
            'module' => array(
                'title' => 'Модуль',
                'type' => 'select',
                'options' => 'a:4:{s:6:"module";s:7:"Modules";s:6:"entity";s:6:"Module";s:5:"index";s:4:"name";s:5:"title";s:5:"title";}',
                'default_value' => 'users',
                'status' => 'db'
            ),
            'weight' => array(
                'title' => 'Вес',
                'type' => 'textbox'
            ),
            'active' => array(
                'title' => 'Активен',
                'type' => 'checkbox'
            )
        )
    )
);

return $module;