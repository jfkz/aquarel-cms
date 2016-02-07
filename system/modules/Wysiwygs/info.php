<?php

$module = new \stdClass;

$module->name    = 'Wysiwygs';
$module->title   = 'Визуальный редактор';
$module->version = '1.0';
$module->author  = 'Vlad Andreev <php.vlad@gmail.com>';

$module->tables = array(
    'wysiwygs' => array(
        'id' => array('type' => 'int', 'length' => 10, 'not_null' => 1, 'auto_increment' => 1),
        'name' => array('type' => 'text', 'not_null' => 1),
        'title' => array('type' => 'text', 'not_null' => 1),
        'caption' => array('type' => 'text', 'not_null' => 1),
        'include_path' => array('type' => 'text', 'not_null' => 1),
        'include_filemanager_path' => array('type' => 'text', 'not_null' => 1),
        'skins' => array('type' => 'text', 'not_null' => 1),
        'default_skin' => array('type' => 'text', 'not_null' => 1),
        'themes' => array('type' => 'text', 'not_null' => 1),
        'default_theme' => array('type' => 'text', 'not_null' => 1),
        'active' => array('type' => 'int', 'length' => 1, 'not_null' => 1, 'default' => '0')
    )
);

$module->data_types = array(
    'Wysiwyg' => array(
        'title' => 'Wysiwyg',
        'entity' => 'Wysiwyg',
    ),
);

$module->menu = array(
    array(
        'name' => 'control',
        'childs' => array(
            array(
                'name' => 'wysiwygs',
                'title' => 'Визуальный редактор',
                'page' => 'settings',
                'weight' => 30,
            )
        )
    )
);

$module->content = array(
    'UserPermission' => array(
        array('title' => 'Настройки', 'page' => 'settings', 'action' => 'settings')
    ),
    'Setting' => array(
        array('name' => 'wysiwyg', 'type' => 'select', 'title' => 'wysiwyg-редактор', 'caption' => 'wysiwyg-редактор', 'value' => 'tinymce'),
        array('name' => 'wysiwyg_skin', 'type' => 'select', 'title' => 'Скин редактора', 'caption' => 'Скин редактора', 'value' => 'o2k7'),
        array('name' => 'wysiwyg_theme', 'type' => 'select', 'title' => 'Тема редактора', 'caption' => 'Тема редактора', 'value' => 'normal')
    ),
    'Wysiwyg' => array(
        array(
            'name' => 'tinymce',
            'title' => 'TinyMCE',
            'caption' => 'TinyMCE 3.2.7 (jQuery version)',
            'include_path' => 'TinyMCE.jquery/jscripts/tiny_mce/jquery.tinymce.js',
            'include_filemanager_path' => 'TinyMCE.jquery/jscripts/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php',
            'skins' => 'default=default,o2k7=o2k7,o2k7--silver=o2k7 (silver),o2k7--black=o2k7 (black)',
            'default_skin' => 'o2k7',
            'themes' => 'basic=Простой,normal=Средний,full=Полный',
            'default_theme' => 'normal',
            'active' => 1
        ),
        array(
            'name' => 'ckeditor',
            'title' => 'CKEditor',
            'caption' => 'CKEditor 3.1',
            'include_path' => 'CKEditor/ckeditor.js',
            'include_filemanager_path' => 'CKEditor/ajexFileManager/ajex.js',
            'skins' => 'kama=Kama,office2003=Office2003,v2=V2',
            'default_skin' => 'kama',
            'themes' => 'Basic=Простой,Normal=Средний,Full=Полный',
            'default_theme' => 'Normal',
            'active' => 1
        ),
        array(
            'name' => 'fckeditor',
            'title' => 'FCKeditor',
            'caption' => 'FCKeditor 2.6.5',
            'include_path' => 'fckeditor/fckeditor.js',
            'include_filemanager_path' => '',
            'skins' => 'default=default,office2003=Office2003,silver=silver',
            'default_skin' => 'default',
            'themes' => 'Basic=Простой,Normal=Средний,Full=Полный',
            'default_theme' => 'Normal',
            'active' => 1
        )
    )
);

return $module;