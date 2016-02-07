<?php

$module = new \stdClass;

$module->name    = 'Users';
$module->title   = 'Пользователи системы';
$module->version = '1.0';
$module->author  = 'Vlad Andreev <php.vlad@gmail.com>';

$module->tables = array(
    'users' => array(
        'id' => array('type' => 'int', 'length' => 10, 'not_null' => 1, 'auto_increment' => 1),
        'login' => array('type' => 'text', 'not_null' => 1),
        'name' => array('type' => 'text', 'not_null' => 1),
        'email' => array('type' => 'text', 'not_null' => 1),
        'hash' => array('type' => 'text', 'not_null' => 1),
        'salt' => array('type' => 'text', 'not_null' => 1),
        'user_group' => array('type' => 'int', 'length' => 1, 'not_null' => 1),
        'wysiwyg' => array('type' => 'text', 'not_null' => 1),
        'wysiwyg_skin' => array('type' => 'text', 'not_null' => 1),
        'wysiwyg_theme' => array('type' => 'text', 'not_null' => 1),
        'city' => array('type' => 'text'),
        'time_c' => array('type' => 'int', 'length' => 10, 'not_null' => 1),
        'time_u' => array('type' => 'int', 'length' => 10, 'not_null' => 1),
        'time_l' => array('type' => 'int', 'length' => 10, 'not_null' => 1),
        'active' => array('type' => 'int', 'length' => 1, 'not_null' => 1, 'default' => 1),
    ),
    'users_additional_data' => array(
        'id' => array('type' => 'int', 'length' => 10, 'not_null' => 1),
    ),
    'users_groups' => array(
        'id' => array('type' => 'int', 'length' => 10, 'not_null' => 1, 'auto_increment' => 1),
        'name' => array('type' => 'text', 'not_null' => 1),
        'title' => array('type' => 'text', 'not_null' => 1),
    ),
    'users_logined' => array(
        'id' => array('type' => 'int', 'length' => 10, 'not_null' => 1, 'auto_increment' => 1),
        'code' => array('type' => 'text', 'not_null' => 1),
        'time' => array('type' => 'int', 'length' => 10, 'not_null' => 1, 'default' => 0),
    ),
    'users_permissions' => array(
        'id' => array('type' => 'int', 'length' => 10, 'not_null' => 1, 'auto_increment' => 1),
        'title' => array('type' => 'text', 'not_null' => 1),
        'module' => array('type' => 'text', 'not_null' => 1),
        'page' => array('type' => 'text', 'not_null' => 1),
        'action' => array('type' => 'text', 'not_null' => 1),
        'access_mask' => array('type' => 'int', 'length' => 10, 'not_null' => 1, 'default' => 1),
        'active' => array('type' => 'int', 'length' => 1, 'not_null' => 1, 'default' => 0),
    ),
);

$module->data_types = array(
    'User' => array(
        'title' => 'Пользователь системы',
        'entity' => 'User',
        'fields' => array(
            'name' => array(
                'title' => 'Имя',
                'type' => 'textbox'
            ),
            'login' => array(
                'title' => 'Логин',
                'type' => 'textbox'
            ),
            'pass' => array(
                'title' => 'Пароль',
                'type' => 'password'
            ),
            'pass2' => array(
                'title' => 'Повтор пароля',
                'type' => 'password'
            ),
            'email' => array(
                'title' => 'E-mail',
                'type' => 'textbox'
            ),
            'group' => array(
                'title' => 'Группа',
                'type' => 'select',
                'options' => 'a:4:{s:6:"module";s:5:"Users";s:6:"entity";s:9:"UserGroup";s:5:"index";s:2:"id";s:5:"title";s:5:"title";}',
                'status' => 'db'
            ),
            'wysiwyg' => array(
                'title' => 'визивиг',
                'type' => 'select',
                'options' => 'a:4:{s:6:"module";s:8:"Wysiwygs";s:6:"entity";s:7:"Wysiwyg";s:5:"index";s:4:"name";s:5:"title";s:5:"title";}',
                'default_value' => 'fckeditor',
                'status' => 'db'
            ),
            'wysiwyg_skin' => array(
                'title' => 'скин визивига',
                'type' => 'select',
                'options' => 'a:4:{s:6:"module";s:8:"Wysiwygs";s:6:"entity";s:7:"Wysiwyg";s:5:"index";s:4:"name";s:5:"title";s:5:"title";}',
                'status' => 'db'
            ),
            'wysiwyg_theme' => array(
                'title' => 'тема визивига',
                'type' => 'select',
                'options' => 'basic=Простой,normal=Средний,full=Полный',
                'status' => 'static'
            )
        )
    ),
    'UserGroup' => array(
        'title' => 'Группа пользователей системы',
        'entity' => 'UserGroup',
        'fields' => array(
            'name'  =>array(
                'title' => 'Идентификатор',
                'type' => 'textbox'
            ),
            'title' =>array(
                'title' => 'Название',
                'type' => 'textbox'
            )
        )
    ),
    'UserPermission' => array(
        'title' => 'Разрешение',
        'entity' => 'UserPermission',
        'fields' => array(
            'title' => array(
                'title' => 'Название',
                'type' => 'textbox'
            ),
            'module' => array(
                'title' => 'Модуль',
                'type' => 'select',
                'options' => 'a:4:{s:6:"module";s:7:"Modules";s:6:"entity";s:6:"Module";s:5:"index";s:4:"name";s:5:"title";s:5:"title";}',
                'default_value' => 'users',
                'status' => 'db'
            ),
            'page' => array(
                'title' => 'Страница',
                'type' => 'textbox'
            ),
            'action' => array(
                'title' => 'Действие',
                'type' => 'textbox'
            ),
            'active' => array(
                'title' => 'Включено',
                'type' => 'checkbox'
            )
        )
    )
);

$module->menu = array(
    array(
        'name' => 'control',
        'childs' => array(
            array(
                'name' => 'users',
                'title' => 'Пользователи',
                'page' => 'list',
                'childs' => 'add,edit,listGroups,addGroup,editGroup,listPermissions,addPermission,editPermission',
                'weight' => 10,
            )
        )
    )
);

$module->content = array(
    'User' => array(
        array(
            'login' => 'root',
            'name' => 'root',
            'email' => 'root@root',
            'pass' => 'root',
            'salt' => 'q4pa6',
            'active' => 1,
            'group' => 1,
            'wysiwyg' => 'fckeditor',
            'wysiwyg_skin' => 'office2003',
            'wysiwyg_theme' => 'Basic'
        )
    ),
    'UserGroup' => array(
        array(
            'id' => 1,
            'name' => 'system',
            'title' => 'Системные администраторы'
        ),
        array(
            'id' => 2,
            'name' => 'admin',
            'title' => 'Администраторы'
        ),
        array(
            'id' => 4,
            'name' => 'content',
            'title' => 'Контент-менеджеры'
        )
    ),
    'UserPermission' => array(
        array('title' => 'Список пользователей системы', 'page' => 'list', 'action' => 'list'),
        array('title' => 'Добавление пользователей', 'page' => 'add', 'action' => 'add'),
        array('title' => 'Редактирование данных пользователей', 'page' => 'edit', 'action' => 'edit'),
        array('title' => 'Редактирование личного профиля', 'page' => 'edit_profile', 'action' => 'profile_edit'),
        array('title' => 'Список групп пользователей системы', 'page' => 'list_groups', 'action' => ''),
        array('title' => 'Добавление групп пользователей', 'page' => 'add_group', 'action' => 'add_group'),
        array('title' => 'Редактирование групп пользователей', 'page' => 'edit_group', 'action' => 'edit_group'),
        array('title' => 'Удаление групп пользователей', 'action' => 'delete_group'),
        array('title' => 'Список разрешений', 'page' => 'list_permissions', 'action' => 'list_permissions'),
        array('title' => 'Добавление разрешений', 'page' => 'add_permission', 'action' => 'add_permission'),
        array('title' => 'Редактирование разрешений', 'page' => 'edit_permission', 'action' => 'edit_permission'),
        array('title' => 'Удаление пользователей, разрешений', 'page' => 'delete', 'action' => 'delete')
    )
);

return $module;