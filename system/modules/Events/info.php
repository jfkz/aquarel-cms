<?php

$module = new \stdClass;

$module->name    = 'Events';
$module->title   = 'Управление событиями';
$module->version = '1.0';
$module->author  = 'Vlad Andreev <php.vlad@gmail.com>';

$module->tables = array(
    'events' => array(
        'id' => array('type' => 'int', 'length' =>10, 'not_null' =>1, 'auto_increment' =>1),
        'name' => array('type' => 'text', 'not_null' =>1),
        'title' => array('type' => 'text', 'not_null' =>1),
        'active' => array('type' => 'int', 'length' =>1, 'not_null' =>1, 'default' => '0')
    ),
    'actions' => array(
        'id' => array('type' => 'int', 'length' =>10, 'not_null' =>1, 'auto_increment' =>1),
        'name' => array('type' => 'text', 'not_null' =>1),
        'title' => array('type' => 'text', 'not_null' =>1),
        'module' => array('type' => 'text', 'not_null' =>1),
        'active' => array('type' => 'int', 'length' =>1, 'not_null' =>1, 'default' => '0')
    ),
    'monitor' => array(   
        'id' => array('type' => 'int', 'length' =>10, 'not_null' =>1, 'auto_increment' =>1),
        'event' => array('type' => 'text', 'not_null' =>1),
        'module' => array('type' => 'text', 'not_null' =>1),
        'action' => array('type' => 'text', 'not_null' =>1),
        'weight' => array('type' => 'int', 'length' =>10, 'not_null' =>1)
    )
);

$module->data_types = array(
    'Event' => array(
        'title' => 'Событие',
        'entity' => 'Event',
        'fields' => array(
            'name' => array(
                'title' => 'Идентификатор',
                'type' => 'textbox'
            ),
            'title' => array(
                'title' => 'Название',
                'type' => 'textbox'
            ),
            'active' => array(
                'title' => 'Активно',
                'type' => 'checkbox'
            )
        )
    ),
    'Action' => array(
        'title' => 'Действие',
        'entity' => 'Action',
        'fields' => array(
            'name' => array(
                'title' => 'Идентификатор',
                'type' => 'textbox'
            ),
            'title' => array(
                'title' => 'Название',
                'type' => 'textbox'
            ),
            'class' => array(
                'title' => 'Класс',
                'type' => 'textbox'
            ),
            'active' => array(
                'title' => 'Активно',
                'type' => 'checkbox'
            )
        )
    ),
    'Link' => array(
        'title' => 'Действие',
        'entity' => 'Action',
        'fields' => array(
            'event' => array(
                'title' => 'Событие',
                'type' => 'textbox'
            ),
            'class' => array(
                'title' => 'Класс',
                'type' => 'textbox'
            ),
            'action' => array(
                'title' => 'Действие',
                'type' => 'textbox'
            ),
            'weight' => array(
                'title' => 'Вес',
                'type' => 'textbox'
            )
        )
    )
);

$module->menu = array(
    array(
        'name' => 'control',
        'childs' => array(
            array(
                'name' => 'events',
                'title' => 'События',
                'page' => 'monitor',
                'childs' => 'list',
                'weight' => 20,
            )
        )
    )
);

$module->content = array(
    'UserPermission' => array(
        array(
            'title' => 'Список связей событие->действия',
            'page' => 'monitor',
        ),
        array(
            'title' => 'Списки событий и действий',
            'page' => 'list',
        )
    )
);

return $module;