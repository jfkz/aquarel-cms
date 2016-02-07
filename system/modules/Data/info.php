<?php

$module = new \stdClass;

$module->name    = 'Data';
$module->title   = 'Данные';
$module->version = '1.0';
$module->author  = 'Vlad Andreev <php.vlad@gmail.com>';

$module->tables = array(
    'data_fields' => array(
        'id' => array('type' => 'int', 'length' =>10, 'not_null' =>1, 'auto_increment' =>1),
        'num' => array('type' => 'int', 'length' =>10, 'not_null' =>1),
        'name' => array('type' => 'text', 'not_null' =>1),
        'title' => array('type' => 'text', 'not_null' =>1),
        'module' => array('type' => 'text', 'not_null' =>1),
        'data_name' => array('type' => 'text', 'not_null' =>1),
        'type' => array('type' => 'text', 'not_null' =>1),
        'options' => array('type' => 'text', 'not_null' =>1),
        'attributes' => array('type' => 'text', 'not_null' =>1),
        'default_value' => array('type' => 'text', 'not_null' =>1),
        'status' => array('type' => 'text', 'not_null' =>1),
        'active' => array('type' => 'int', 'length' =>1, 'not_null' =>1, 'default' => '0')
    ),
    'data_templates' => array(
        'id' => array('type' => 'int', 'length' =>10, 'not_null' =>1, 'auto_increment' =>1),
        'name' => array('type' => 'text', 'not_null' =>1),
        'title' => array('type' => 'text', 'not_null' =>1),
        'text' => array('type' => 'text', 'not_null' =>1),
        'fields' => array('type' => 'text', 'not_null' =>1),
        'page_template' => array('type' => 'text', 'not_null' =>1)
    ),
    'data_types' => array(
        'id' => array('type' => 'int', 'length' =>10, 'not_null' =>1, 'auto_increment' =>1),
        'num' => array('type' => 'int', 'length' =>10, 'not_null' =>1),
        'name' => array('type' => 'text', 'not_null' =>1),
        'title' => array('type' => 'text', 'not_null' =>1),
        'module' => array('type' => 'text', 'not_null' =>1),
        'template' => array('type' => 'text', 'not_null' =>1),
        'url' => array('type' => 'text', 'not_null' =>1),
        'entity' => array('type' => 'text', 'not_null' =>1),
        'active' => array('type' => 'int', 'length' =>1, 'not_null' =>1, 'default' => '0')
    )
);

$module->data_types = array(
    'DataType' => array(
        'title' => 'Тип данных',
        'entity' => 'DataType',
        'fields' => array(
            'name' => array(
                'title' => 'Идентификатор',
                'type' => 'textbox'
            ),
            'title' => array(
                'title' => 'Название',
                'type' => 'textbox'
            ),
            'entity' => array(
                'title' => 'Сущность',
                'type' => 'textbox'
            ),
            'module' => array(
                'title' => 'Модуль',
                'type' => 'select',
                'options' => 'a:4:{s:6:"module";s:7:"Modules";s:6:"entity";s:6:"Module";s:5:"index";s:4:"name";s:5:"title";s:5:"title";}',
                'default_value' => 'users',
                'status' => 'db'
            ),
            'url' => array(
                'title' => 'Ссылка',
                'type' => 'textbox'
            ),
            'active' => array(
                'title' => 'Активен',
                'type' => 'checkbox'
            )
        )
    ),
    'DataField' => array(
        'title' => 'Поле',
        'entity' => 'DataField',
        'fields' => array(
            'name' => array(
                'title' => 'Идентификатор',
                'type' => 'textbox'
            ),
            'title' => array(
                'title' => 'Название',
                'type' => 'textbox'
            ),
            'type' => array(
                'title' => 'Тип',
                'type' => 'select',
                'options' => 'textbox=текстовое поле,password=пароль,textarea=многострочный текст,select=поле со списком,checkbox=флажок,checkbox_list=группа флажков,radio=переключатели,file=файл,image=изображение',
                'default_value' => 'textbox',
                'status' => 'static'
            ),
            'active' => array(
                'title' => 'Включен',
                'type' => 'checkbox',
                'attributes' => 'class=checkbox'
            )
        )
    ),
    'DataTemplate' => array(
        'title' => 'Данные. Шаблон',
        'entity' => 'DataTemplate',
        'fields' => array(
            'name' => array(
                'title' => 'Идентификатор',
                'type' => 'textbox'
            ),
            'title' => array(
                'title' => 'Название',
                'type' => 'textbox'
            ),
            'text' => array(
                'title' => 'Текст',
                'type' => 'textarea',
                'attributes' => 'rows=10 cols=60'
            ),
            'fields' => array(
                'title' => 'Поля',
                'type' => 'textbox'
            ),
            'page_template' => array(
                'title' => 'Шаблон страницы',
                'type' => 'textbox'
            )
        )
    )
);

$module->menu = array(
    array(
        'name' => 'constructor',
        'childs' => array(
            array(
                'name' => 'data',
                'title' => 'Данные',
                'page' => 'list',
                'childs' => 'add,edit,list_fields,edit_field',
                'weight' => 10,
            )
        )
    )
);

$module->content = array(
    'DataTemplate' => array(
        array(
            'name' => 'default_list',
            'title' => 'Список по умолчанию',
            'text' => '<ul>\r\n\{foreach from=\$items item=item\}\r\n    <li>\{\$item.title\}</li>\r\n\{/foreach\}\r\n</ul>',
            'fields' => 'title',
            'page_template' => 'main'
        ),
        array(
            'name' => 'page_list',
            'title' => 'Список страниц',
            'text' => '<ul>\r\n\{foreach from=\$items item=item\}\r\n    <li><a href=&quot;\{\$item.url\}&quot;>\{\$item.title\}</a></li>\r\n\{/foreach\}\r\n</ul>',
            'fields' => 'title,url',
            'page_template' => 'main'
        )
    ),
    'UserPermission' => array(
        array('title' => 'Список типов данных', 'page' => 'list', 'action' => 'list'),
        array('title' => 'Добавление типов данных', 'page' => 'add', 'action' => 'type_add'),
        array('title' => 'Редактирование типов данных', 'page' => 'edit', 'action' => 'type_edit'),
        array('title' => 'Удаление типов данных', 'page' => '', 'action' => 'type_delete'),
        array('title' => 'Список полей типов данных', 'page' => 'list_fields', 'action' => 'list_fields'),
        array('title' => 'Добавление полей типов данных', 'page' => '', 'action' => 'field_add'),
        array('title' => 'Редактирование полей типов данных', 'page' => 'edit_field', 'action' => 'field_edit'),
        array('title' => 'Удаление полей типов данных', 'page' => '', 'action' => 'field_delete'),
        array('title' => 'Список шаблонов', 'page' => 'list_templates', 'action' => ''),
        array('title' => 'Добавление шаблонов', 'page' => 'add_template', 'action' => 'template_add'),
        array('title' => 'Редактирование шаблонов', 'page' => 'edit_template', 'action' => 'template_edit'),
        array('title' => 'Удаление шаблонов', 'page' => '', 'action' => 'template_delete')
    )
);

return $module;