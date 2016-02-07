<?php

$module = new \stdClass;

$module->name    = 'Router';
$module->title   = 'Маршрутизатор системы';
$module->version = '1.0';
$module->author  = 'Vlad Andreev <php.vlad@gmail.com>';

$module->tables = array(
    'routes' => array(
        'id' => array('type' => 'int', 'length' => 10, 'not_null' => 1, 'auto_increment' => 1),
        'route' => array('type' => 'text', 'not_null' => 1),
        'controller' => array('type' => 'text', 'not_null' => 1),
        'action' => array('type' => 'text', 'not_null' => 1),
        'template' => array('type' => 'text', 'not_null' => 1),
    ),
);

return $module;