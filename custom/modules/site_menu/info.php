<?php
$module_name    = 'site_menu';
$module_title   = 'Меню сайта';
$module_version = '1.0';
$module_author  = 'Vlad Andreev <php.vlad@gmail.com>';

$module_tables = array(
'site_menu'=>array( 'id'=>array('type'=>'int', 'length'=>10, 'not_null'=>1, 'auto_increment'=>1),
                    'name'=>array('type'=>'text', 'not_null'=>1),
                    'title'=>array('type'=>'text', 'not_null'=>1),
                    'parent'=>array('type'=>'int', 'length'=>10, 'not_null'=>1),
                    'level'=>array('type'=>'int', 'length'=>10, 'not_null'=>1),
                    'url'=>array('type'=>'text', 'not_null'=>1),
                    'weight'=>array('type'=>'int', 'length'=>10, 'not_null'=>1),
                    'active'=>array('type'=>'int', 'length'=>1, 'not_null'=>1, 'default_value'=>'0')),
'site_menu_templates'=>array(   'id'=>array('type'=>'int', 'length'=>10, 'not_null'=>1, 'auto_increment'=>1),
                                'name'=>array('type'=>'text', 'not_null'=>1),
                                'title'=>array('type'=>'text', 'not_null'=>1),
                                'text'=>array('type'=>'text', 'not_null'=>1),
                                'active'=>array('type'=>'int', 'length'=>1, 'not_null'=>1, 'default_value'=>'0')) );

$module_data_types = array(
'site_menu_template'=>array('title'=>'Шаблон меню сайта',
                            'data_table'=>'site_menu_templates',
                            'fields'=>array('name'      =>array('title'=>'Идентификатор',
                                                                'type'=>'textbox'),
                                            'title'     =>array('title'=>'Название',
                                                                'type'=>'textbox'),
                                            'active'    =>array('title'=>'Активен',
                                                                'type'=>'checkbox'),
                                            'show_main' =>array('title'=>'Выводить на главную',
                                                                'type'=>'checkbox'),
                                            'caption'   =>array('title'=>'Описание',
                                                                'type'=>'textarea',
                                                                'options'=>'rows=5 class=textfield'),
                                            'text'      =>array('title'=>'Подробное описание',
                                                                'type'=>'textarea',
                                                                'options'=>'rows=17 cols=80'),
                                            'image'     =>array('title'=>'Изображение',
                                                                'type'=>'image'),
                                            'price'     =>array('title'=>'Цена',
                                                                'type'=>'textbox'))) );

$module_menu = array(
'content'=>array('childs'=>array('site_menu'=>array('title'=>'Меню сайта',
                                                    'page'=>'list',
                                                    'data'=>'',
                                                    'options'=>'',
                                                    'childs'=>'add,edit'))) );

$module_permissions = array(
array('title'=>'Меню сайта', 'page'=>'list', 'option'=>'list') );

?>