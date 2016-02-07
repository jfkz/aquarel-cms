<?php

$module = new \stdClass;

$module->name    = 'Control';
$module->title   = 'Control';
$module->version = '1.0';
$module->author  = 'Vlad Andreev <php.vlad@gmail.com>';

$module->tables = array(
    'files_temp' => array(
        'id' => array('type' => 'int', 'length' =>10, 'not_null' =>1, 'auto_increment' =>1),
        'name' => array('type' => 'text', 'not_null' =>1),
        'user' => array('type' => 'text', 'not_null' =>1),
        'path' => array('type' => 'text', 'not_null' =>1),
        'size' => array('type' => 'int', 'length' =>10, 'not_null' =>1),
        'created' => array('type' => 'int', 'length' =>10, 'not_null' =>1),
    ),
);

$module->menu = array(
    array(
        'name' => 'constructor',
        'title' => 'Конструктор',
        'weight' => 1,
    ),
    array(
        'name' => 'control',
        'title' => 'Управление',
        'weight' => 2,
    ),
    array(
        'name' => 'content',
        'title' => 'Содержимое',
        'weight' => 3,
    ),
);

return $module;