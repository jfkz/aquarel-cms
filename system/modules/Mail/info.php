<?php

$module = new \stdClass;

$module->name    = 'Mail';
$module->title   = 'Отправка писем';
$module->version = '1.0';
$module->author  = 'Vlad Andreev <php.vlad@gmail.com>';

$module->tables = array(
    'mail' => array(
        'id' => array('type' => 'int', 'length' => 10, 'not_null' => 1, 'auto_increment' => 1),
        'title' => array('type' => 'text', 'not_null' => 1),
        'subject' => array('type' => 'text', 'not_null' => 1),
        'text' => array('type' => 'text', 'not_null' => 1),
        'tags' => array('type' => 'text', 'not_null' => 1),
        'event' => array('type' => 'text', 'not_null' => 1),
        'active' => array('type' => 'int', 'length' => 1, 'not_null' => 1, 'default' => '1')
    )
);

$module->data_types = array(
    'Mail' => array(
        'title' => 'Письмо',
        'entity' => 'Mail',
        'fields' => array(
            'title' => array(
                'title' => 'Название',
                'type' => 'textbox'
            ),
            'subject' => array(
                'title' => 'Тема',
                'type' => 'textbox'
            ),
            'text' => array(
                'title' => 'Текст',
                'type' => 'textarea',
                'attributes' => 'rows=17 cols=80'
            ),
            'event' => array(
                'title' => 'Событие',
                'type' => 'select',
                'options' => 'a:4:{s:6:"module";s:6:"Events";s:6:"entity";s:5:"Event";s:5:"index";s:4:"name";s:5:"title";s:5:"title";}',
                'status' => 'db'
            ),
            'tags' => array(
                'title' => 'Теги',
                'type' => 'textarea',
                'options' => 'rows=5 class=textfield'
            ),
            'active' => array(
                'title' => 'Активно',
                'type' => 'checkbox'
            )
        )
    )
);

$module->menu = array(
    array(
        'name' => 'content',
        'childs' => array(
            array(
                'name' => 'mail',
                'title' => 'Письма',
                'page' => 'list',
                'childs' => 'add,edit',
                'weight' => 2,
            )
        )
    )
);

$module->content = array(
    'UserPermission' => array(
        array('title' => 'Список писем', 'page' => 'list'),
        array('title' => 'Добавление писем', 'page' => 'add', 'action' => 'add'),
        array('title' => 'Редактирование писем', 'page' => 'edit', 'action' => 'edit'),
        array('title' => 'Включение писем', 'action' => 'activate'),
        array('title' => 'Выключение писем', 'action' => 'disable'),
        array('title' => 'Удаление писем', 'action' => 'delete'),
    ),
    'Action' => array(
        array('name' => 'send', 'title' => 'Отправка письма'),
    ),
);

return $module;