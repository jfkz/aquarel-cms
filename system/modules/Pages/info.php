<?php

$module = new \stdClass;

$module->name    = 'Pages';
$module->title   = 'Страницы';
$module->version = '1.0';
$module->author  = 'Vlad Andreev <php.vlad@gmail.com>';

$module->tables = array(
    'pages' => array(
        'id' => array('type' => 'int', 'length' => 10, 'not_null' => 1, 'auto_increment' => 1),
        'parent' => array('type' => 'int', 'length' => 10, 'not_null' => 1, 'default_value' => '0'),
        'name' => array('type' => 'text', 'not_null' => 1),
        'title' => array('type' => 'text', 'not_null' => 1),
        'meta_title' => array('type' => 'text', 'not_null' => 1),
        'template' => array('type' => 'text', 'not_null' => 1),
        'text' => array('type' => 'text', 'not_null' => 1),
        'weight' => array('type' => 'int', 'length' => 10, 'not_null' => 1, 'default_value' => '0'),
        'show_in_menu' => array('type' => 'int', 'length' => 1, 'not_null' => 1, 'default_value' => '0'),
        'active' => array('type' => 'int', 'length' => 1, 'not_null' => 1, 'default_value' => '0')
    )
);

$module->data_types = array(
    'Page' => array(
        'title' => 'Страница',
        'entity' => 'Page',
        'fields' => array(
            'name' => array(
                'title' => 'Идентификатор',
                'type' => 'textbox'
            ),
            'title' => array(
                'title' => 'Заголовок',
                'type' => 'textbox'
            ),
            'meta_title' => array(
                'title' => 'Заголовок (title)',
                'type' => 'textbox'
            ),
            'template' => array(
                'title' => 'Шаблон',
                'type' => 'select',
                'options' => 'index=Главная,main=Основной',
                'status' => 'static'
            ),
            'text' => array(
                'title' => 'Текст',
                'type' => 'textarea',
                'attributes' => 'rows=17 cols=80 class=wysiwyg'
            ),
            'show_in_menu' => array(
                'title' => 'Показывать в меню',
                'type' => 'checkbox',
                'attributes' => 'class=checkbox'
            ),
            'active' => array(
                'title' => 'Включена',
                'type' => 'checkbox',
                'attributes' => 'class=checkbox'
            )
        )
    )
);

$module->menu = array(
    array(
        'name' => 'content',
        'childs' => array(
            array(
                'name' => 'pages',
                'title' => 'Страницы',
                'page' => 'list',
                'childs' => 'add,edit',
                'weight' => 1,
            )
        )
    )
);

$module->content = array(
    'UserPermission' => array(
        array('title' => 'Список страниц', 'page' => 'list', 'action' => 'list'),
        array('title' => 'Добавление страниц', 'page' => 'add', 'action' => 'add'),
        array('title' => 'Редактирование страниц', 'page' => 'edit', 'action' => 'edit'),
        array('title' => 'Редактирование наполнения страниц', 'page' => 'edit_content', 'action' => 'edit_content'),
        array('title' => 'Удаление страниц', 'page' => 'delete', 'action' => 'delete')
    ),
    'Page' => array(
        array(
            'name' => 'home',
            'title' => 'Главная',
            'text' => '<br/><br/><a href=&quot;/install.php&quot;>Импорт базы данных</a><br/><br/><a href=&quot;/control&quot;>Перейти в панель управления</a><br/><br/>',
            'template' => 'index',
        ),
        array(
            'name' => '404',
            'title' => '404',
            'text' => 'Страница не найдена',
            'template' => 'main',
        ),
    ),
);

return $module;