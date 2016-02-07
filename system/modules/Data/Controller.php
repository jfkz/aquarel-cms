<?php
/**
 * Module:  Data
 * About:   Данные - контроллер
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Data;

class Controller extends \Control\ControllerAncestor
{
    public $name;
    public $title;
    public $table;
    public $module;
    public $fields = array();
    public $fields_db;
    public $fields_str;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->repDataType = $this->em->getRepository('Data\Entities\DataType');
        $this->repDataField = $this->em->getRepository('Data\Entities\DataField');
        $this->repDataTemplate = $this->em->getRepository('Data\Entities\DataTemplate');
        
        $live->_theme_control = true;
    }
    
    /*
     *  Actions
     */
    
    // Список типов данных
    public function listAction()
    {
        $form = new \Control\Forms;
        $form->setTitle('Типы данных');
        $form->setTemplate('list');
       
        $form->addHead(array(
            array(
                'width' => 1,
                'text '=> array(
                    'type'  => 'checkbox',
                    'id'    => 'all_check',
                    'action'=> 'all_check();',
                )
            ),
            array('width'=>1),
            array('width'=>500, 'align'=>'left', 'text'=>'Имя'),
            array('width'=>300, 'align'=>'left', 'text'=>'Идентификатор'),
            array('width'=>1),
            array('width'=>100)
        ));
        
        $rows = array();
        
        $data_types = $this->repDataType->findBy(
            array(),
            array('module' => 'ASC')
        );
        
        foreach ($data_types as $data_type)
        {
            $id         = $data_type->id;
            $data_name  = $data_type->name;
            $data_title = $data_type->title;
            
            $check = array(
                'type'  => 'checkbox',
                'id'    => $id,
                'class' => 'check_item',
                'style' => 'cursor:pointer',
            );
            
            $edit = array(
                'type'  => 'link_img_edit',
                'href'  => '#Data/edit/'. $id,
            );
            
            $delete = array(
                'type'  => 'link_img_delete',
                'action'=> "javascript: if (MsgOkCancel('Действительно удалить?')) { data_type_delete('$id'); }",
            );
            
            $delete_table = array(
                'type'       => 'checkbox',
                'cell_style' => 'font-size:7pt;line-height:1.1',
                'id'         => 'data_delete_table_'. $id,
                'style'      => 'float:left',
                'label'      => 'удалить<br/>таблицу'
            );
            
            $title  = array(
                'type'  => 'link',
                'href'  => '#Data/listFields/'. $data_name,
                'text'  => $data_title,
            );
            
            $name   = array(
                'type'  => 'link',
                'href'  => '#Data/listFields/'. $data_name,
                'text'  => $data_name,
            );
            
            $rows[] = array($check, $edit, $title, $name, $delete, $delete_table);
        }
        
        $form->addRows($rows);
        
        $output = $form->render();
        
        return $output;
    }
    
    // Форма добавления типа данных
    public function addAction()
    {
        $data = new \Data\Data;
        $data->setName('DataType');
        
        $fields = $data->getFields_str();
        
        $footer = array(
            array(
                'type'    => 'button',
                'id'      => 'ok',
                'value'   => 'Готово',
                'action'  => "data_type_add('{$fields}', 'list');"
            ),
            array(
                'type'    => 'button',
                'id'      => 'save',
                'value'   => 'Сохранить',
                'action'  => "data_type_add('{$fields}');"
            ),
            array(
                'type'    => 'button',
                'id'      => 'cancel',
                'value'   => 'Отмена',
                'href'    => '#Data/list'
            ),
        );
        
        $output = $data->addForm(array(
            'title' => 'Добавить тип данных',
            'footer' => $footer,
        ));
        
        return $output;
    }
    
    // Форма редактирования типа данных
    public function editAction($id = null)
    {
        if(empty($id))
            exit(json_encode(array('error' => 'Не указан идентификатор')));
        
        $data = new \Data\Data;
        $data->setName('DataType');
        
        $form = new \Control\Forms;
        $form->setTitle('Редактировать тип данных');
        $form->setTemplate('form');
        
        $item = $this->repDataType->find($id);
        
        $fields = $this->repDataField->findBy(
            array('data_name' => 'DataType', 'active' => 1),
            array('num' => 'ASC')
        );
        
        if(!empty($fields))
        {
            foreach ($fields as $field)
            {
                $field_name = $field->name;
                
                $field->id = 'Data_'. $field_name;
                $field->value = $item->$field_name;
                $field->caption = $field->title;
                
                if ($field->name != 'data_table')
                {
                    $form->addObject($field);
                }
                else
                {
                    $field->attributes = (isset($field->attributes) ? $field->attributes : '') ." onkeyup=data_change_table('". $item->data_table ."');";
                    
                    $form->addObject($field);
                    
                    $form->addObject(array(
                        'type'       => 'checkbox',
                        'id'         => 'Data_radio_create_new_table',
                        'value'      => '0',
                        'label'      => 'сохранить таблицу '. $item->data_table .' и создать новую таблицу',
                        'attributes' => "title='создать новую таблицу'",
                        'prefix'     => '<div id="div_choose_1" style="display:none">',
                        'sufix'      => '</div>',
                        'caption'    => ' '
                    ));
                }
            }
        }
        
        $form->addFooter(array(
            'type'    => 'button',
            'id'      => 'ok',
            'value'   => 'Готово',
            'action'  => "data_type_edit('{$id}', '". $data->getFields_str() ."', 'list');"
        ));
        
        $form->addFooter(array(
            'type'    => 'button',
            'id'      => 'save',
            'value'   => 'Сохранить',
            'action'  => "data_type_edit('{$id}', '". $data->getFields_str() ."', 'edit/{$id}');"
        ));
        
        $form->addFooter(array(
            'type'    => 'button',
            'id'      => 'cancel',
            'value'   => 'Отмена',
            'href'    => '#Data/list'
        ));
        
        $output = $form->render();
        
        return $output;
    }
    
    // Список полей типа данных
    public function listFieldsAction($data_name = null)
    {
        if(empty($data_name))
            exit(json_encode(array('error' => 'Не указан тип данных')));
        
        $conf = \Control\Core::conf();
        
        $data_type = $this->repDataType->findOneBy(array('name' => $data_name));
        
        $data_type_title  = $data_type->title;
        $data_module_name = $data_type->module;
        
        $form = new \Control\Forms;
        $form->setTitle('Типы данных: '. $data_type->title);
        $form->setTemplate('list');
        $form->addHead(array(
            array('width' => 30),
            array('align' => 'left', 'text' => 'Имя'),
            array('align' => 'left', 'text' => 'Идентификатор'),
            array('align' => 'left', 'text' => 'Тип'),
            array('width' => 30),
            array('width' => 30)
        ));
        
        $fields_edit = implode($conf->ac_sep, array('name', 'title', 'type'));
        
        $type_options = array();
        $field = $this->repDataField->findOneBy(array('name' => 'type', 'data_name' => 'DataField'));
        $field_options = explode(',', $field->options);
        foreach ($field_options as $option)
        {
            $option = explode('=', $option);
            $type_options[$option[0]] = $option[1];
        }
        
        $rows = array();
        
        $fields = $this->repDataField->findBy(
            array('data_name' => $data_name),
            array('num' => 'ASC')
        );
        foreach ($fields as $field)
        {
            $id     = $field->id;
            
            $edit   = array('type'    => 'link_img_edit',
                            'href'    => '#Data/editField/'. $id );
            
            $title  = array('type'    => 'textbox',
                            'id'      => $data_name .'_title_'. $id,
                            'value'   => $field->title);
            
            $name   = array('type'    => 'textbox',
                            'id'      => $data_name .'_name_'. $id,
                            'value'   => $field->name);
            
            $type   = array('type'    => 'select',
                            'id'      => $data_name .'_type_'. $id,
                            'value'   => $field->type,
                            'options' => $type_options);
            
            $save   = array('type'    => 'link_img_save',
                            'action'  => "data_field_edit('{$data_name}', '{$id}', '{$fields_edit}')" );
            
            $delete = array('type'    => 'link_img_delete',
                            'action'  => "javascript: if (MsgOkCancel('Действительно удалить?')) { data_field_delete('{$id}'); }" );
            
            $rows[] = array($edit, $title, $name, $type, $save, $delete);
        }
        
        $name  = array('type'   => 'textbox',
                       'id'     => 'Data_name');
        $title = array('type'   => 'textbox',
                       'id'     => 'Data_title');
        $type  = array('type'   => 'select',
                       'id'     => 'Data_type',
                       'options'=> $type_options);
        $add   = array('type'   => 'link_img_add',
                       'action' => "data_field_add('{$data_name}')" );
        $rows[] = array('', $title, $name, $type, $add, '');
        
        $form->addRows($rows);
        
        $form->addFooter(array('type'  => 'hidden',
                               'id'    => 'Data_data_name',
                               'value' => $data_name ));
        $form->addFooter(array('type'  => 'hidden',
                               'id'    => 'Data_active',
                               'value' => '1' ));
        $form->addFooter(array('type'  => 'hidden',
                               'id'    => 'Data_module',
                               'value' => $data_module_name ));
        
        $output = $form->render();
        
        return $output;
    }
    
    // Форма редактирования поля типа данных
    public function editFieldAction($id = null)
    {
        if(empty($id))
            exit(json_encode(array('error' => 'Не указан идентификатор')));
        
        $conf = \Control\Core::conf();
        
        $id = intval($id);
        $item = $this->repDataField->find($id);
        $data_type = $this->repDataType->findOneByName($item->data_name);
        $module = $this->em->getRepository('\Modules\Entities\Module')->findOneByName($data_type->module);
        
        $form = new \Control\Forms;
        $form->setTitle('Редактировать поле типа данных.');
        $form->setTemplate('form');
        $form->addHTML($module->title .' --> '. $data_type->title .' --> <strong>'. $item->title .'</strong>');
        
        $fields = array('options', 'attributes', 'default_value');
        
        $options = new \stdClass;
        $options->type          = 'textarea';
        $options->id            = $item->data_name .'_options_'. $id;
        $options->value         = $item->options;
        $options->attributes    = "rows='5' cols='30'";
        $options->caption       = 'Опции';
        
        $attributes = new \stdClass;
        $attributes->type       = 'textarea';
        $attributes->id         = $item->data_name .'_attributes_'. $id;
        $attributes->value      = $item->attributes;
        $attributes->attributes = "rows='5' cols='30'";
        $attributes->caption    = 'Аттрибуты';
        
        $form->addObject($options);
        $form->addObject($attributes);
        
        switch ($item->type)
        {
            case 'textarea':
            {
                $default_value = new \stdClass;
                $default_value->type        = 'textarea';
                $default_value->id          = $item->data_name .'_default_value_'. $id;
                $default_value->value       = $item->default_value;
                $default_value->attributes  = "rows='5' cols='30'";
                $default_value->caption     = 'Значение по умолчанию';
                
                $form->addObject($default_value);
                
                break;
            }
            case 'select':
            {
                $fields[] = 'status';
                
                $status = new \stdClass;
                $status->type       = 'select';
                $status->id         = $item->data_name .'_status_'. $id;
                $status->value      = $item->status;
                $status->options    = array('static' => 'статичный', 'db' => 'из базы');
                $status->caption    = 'Тип';
                
                $form->addObject($status);
                
                $default_value = new \stdClass;
                $default_value->type    = 'select';
                $default_value->id      = $item->data_name .'_default_value_'. $id;
                $default_value->value   = $item->default_value;
                $default_value->options = $item->options;
                $default_value->status  = $item->status;
                $default_value->caption = 'Значение по умолчанию';
                
                $form->addObject($default_value);
                
                break;
            }
            case 'checkbox_list':
            {
                $fields[] = 'status';
                
                $status = new \stdClass;
                $status->type    = 'select';
                $status->id      = $item->data_name .'_status_'. $id;
                $status->value   = $item->status;
                $status->options = array('static' => 'статичный', 'db' => 'из базы');
                $status->caption = 'Тип';
                
                $form->addObject($status);
                
                break;
            }
        }
        
        $fields = implode($conf->ac_sep, $fields);
        
        $form->addFooter(array(
            'type'    => 'button',
            'id'      => 'ok',
            'value'   => 'Готово',
            'action'  => "data_field_edit('". $item->data_name. "', '$id', '$fields', 'listFields/". $item->data_name ."');"
        ));
        
        $form->addFooter(array(
            'type'    => 'button',
            'id'      => 'save',
            'value'   => 'Сохранить',
            'action'  => "data_field_edit('". $item->data_name. "', '$id', '$fields', 'editField/{$id}');"
        ));
        
        $form->addFooter(array(
            'type'    => 'button',
            'id'      => 'cancel',
            'value'   => 'Отмена',
            'action'  => '#Data/listFields/'. $item->data_name
        ));
        
        $output = $form->render();
        
        return $output;
    }
    
    // Список шаблонов
    public function listTemplatesAction()
    {
        $conf = \Control\Core::conf();
        
        $form = new \Control\Forms;
        $form->setTitle('Типы данных. Шаблоны');
        $form->setTemplate('list');
        $form->addHead(array(
                        array('width'=>1),
                        array('width'=>500, 'align'=>'left', 'text'=>'Имя'),
                        array('width'=>300, 'align'=>'left', 'text'=>'Идентификатор'),
                        array('width'=>1)));
        
        $rows = array();
        
        $templates = $this->repDataTemplate->findBy(array(), array('name' => 'ASC'));
        foreach ($templates as $template)
        {
            $edit   = array('type'  => 'link_img_edit',
                            'href'  => '#Data/editTemplate/'. $template->id );
            
            $delete = array('type'  => 'link_img_delete',
                            'action'=> "javascript: if (MsgOkCancel('Действительно удалить?')) { data_template_delete('". $template->id ."'); }" );
            
            $title  = array('type'  => 'link',
                            'href'  => '#Data/editTemplate/'. $template->id,
                            'text'  => $template->title);
            
            $name   = array('type'  => 'link',
                            'href'  => '#Data/editTemplate/'. $template->id,
                            'text'  => $template->name);
            
            $rows[] = array($edit, $title, $name, $delete);
        }
        
        $form->addRows($rows);
        $output = $form->render();
        
        return $output;
    }
    
    // Форма добавления шаблона
    public function addTemplateAction()
    {
        $data = new \Data\Data;
        $data->setName('DataTemplate');
        
        $fields = $data->getFields_str();
        
        $footer = array(
            array(
                'type'    => 'button',
                'id'      => 'ok',
                'value'   => 'Готово',
                'action'  => "data_template_add('{$fields}', 'listTemplates');"
            ),
            array(
                'type'    => 'button',
                'id'      => 'save',
                'value'   => 'Сохранить',
                'action'  => "data_template_add('{$fields}');"
            ),
            array(
                'type'    => 'button',
                'id'      => 'cancel',
                'value'   => 'Отмена',
                'href'    => '#Data/listTemplates'
            ),
        );
        
        $output = $data->addForm(array(
            'title'  => 'Добавить шаблон',
            'footer' => $footer,
        ));
        
        return $output;
    }
    
    // Форма редактирования шаблона
    public function editTemplateAction($id)
    {
        $data = new \Data\Data;
        $data->setName('DataTemplate');
        
        $fields = $data->getFields_str();
        
        $footer = array(
            array(
                'type'    => 'button',
                'id'      => 'ok',
                'value'   => 'Готово',
                'action'  => "data_template_edit('{$id}', '{$fields}', 'listTemplates');"
            ),
            array(
                'type'    => 'button',
                'id'      => 'save',
                'value'   => 'Сохранить',
                'action'  => "data_template_edit('{$id}', '{$fields}');"
            ),
            array(
                'type'    => 'button',
                'id'      => 'cancel',
                'value'   => 'Отмена',
                'href'    => '#Data/listTemplates'
            ),
        );
        
        $output = $data->editForm(array('title' => 'Редактировать шаблон', 'footer' => $footer), $id);
        
        return $output;
    }
    
    // Список опций
    public function optionsAction($page = null)
    {
        $conf = \Control\Core::conf();
        $live = \Control\live::getInstance();
        
        $json = array();
        
        /*
        $qb = $this->em->createQueryBuilder();
        $qb->add('select', 'm')
            ->add('from', '\Menu\Entities\Menu m')
            ->add('where', $qb->expr()->andX(
                'm.module = ?1',
                $qb->expr()->orX(
                    'm.page = ?2',
                    $qb->expr()->like('m.childs', '?2')
                )
            ))
            ->setParameter(1, 'data')
            ->setParameter(2, $page)
        ;
        $info = $qb->getQuery()->getResult();
        
        if (!empty($info))
        {
            $options = $info[0]->options;
            echo $options .', ';
            
            $this->$options();
        }*/
        
        $json[] = array('title'=>'Добавить тип данных', 'href'=>'#Data/add');
        
        $childs = array();
        
        $data_types = $this->repDataType->findBy(array());
        
        if(!empty($data_types))
        {
            foreach($data_types as $data_type)
            {
                $childs_right = array();
                $childs_right[] = array('type'=>'link_img_list', 'href'=>'#Data/listFields/'. $data_type->name);
                $childs_right[] = array('type'=>'link_img_edit', 'href'=>'#Data/edit/'. $data_type->id);
                
                $childs_bottom = array();
                $data_fields = $this->repDataField->findBy(array('data_name' => $data_type->name));
                if(!empty($data_fields))
                {
                    foreach ($data_fields as $data_field)
                    {
                        $childs_bottom[] = array(
                            'title' => $data_field->title,
                            'href'=>'#Data/editField/'. $data_field->id
                        );
                    }
                }
                
                $childs_spoiler = array(
                    'id'    => 'data_options_type_'. $data_type->id, 
                    'items' => $childs_bottom
                );
                
                $childs[] = array(
                    'name'            => $data_type->name,
                    'title'           => $data_type->title,
                    'action'          => "acSlideToggle('data_options_type_". $data_type->id ."')",
                    'childs_right'    => $childs_right,
                    'childs_spoiler'  => $childs_spoiler
                );
            }
        }
        
        $json[] = array('title'=>'Список данных', 'href'=>'#Data/list', 'childs'=>$childs);
        
        $json[] = array('title'=>'');
        
        $json[] = array('title'=>'Добавить шаблон', 'href'=>'#Data/addTemplate');
        
        $childs = array();
        $data_templates = $this->repDataTemplate->findAll();
        if(!empty($data_templates))
        {
            foreach ($data_templates as $data_template)
            {
                $childs[] = array(
                    'name' => $data_template->name,
                    'title'=> (!empty($data_template->title)) ? $data_template->title : $data_template->name,
                    'href' => '#Data/editTemplate/'. $data_template->id
                );
            }
        }
        
        $json[] = array('title'=>'Список Шаблонов', 'href'=>'#Data/listTemplates', 'childs'=>$childs);
        
        // $json[] = array('title'=>'Настройки', 'href'    => '#Data/settings' );
        
        $output = json_encode($json);
        
        return $output;
    }
    
    // Сохранить
    public function saveAction($entity, $id = null)
    {
        $post_data = array();
        
        $fields = explode($this->conf->ac_sep, $_POST['fields']);
        $values = explode($this->conf->ac_sep, $_POST['values']);
        
        foreach($fields as $key => $field)
            $post_data[$field] = $values[$key];
        
        $model = '\Data\\'. $entity;
        
        $data = new $model;
        $data->save($post_data, $id);
    }
    
    // Удалить
    public function deleteAction($entity, $id)
    {
        $id = intval($id);
        
        $model = '\Data\\'. $entity;
        
        $data = new $model;
        $data->delete($id);
    }
    
    // Включить
    public function activateAction($entity, $id)
    {
        $id    = intval($id);
        $model = '\Data\\'. $entity;
        
        $data = new $model;
        $data->save(array('active' => 1), $id);
    }
    
    // Выключить
    public function disableAction($entity, $id)
    {
        $id    = intval($id);
        $model = '\Data\\'. $entity;
        
        $data = new $model;
        $data->save(array('active' => 0), $id);
    }
    
    /*
     *  Service methods
     */
    
    // Экранирование переменных
    public function quote_smart($value)
    {
        if (get_magic_quotes_gpc())
            $value = stripslashes($value);
        if (!is_numeric($value))
            $value = mysql_real_escape_string($value);
        
        $value = htmlspecialchars($value);
        
        return $value;
    }
}