<?php
/**
 *  Name:    Users
 *  Type:    Controller
 *  About:   Пользователи системы
 *  Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Users;

class Controller extends \Control\ControllerAncestor
{
    private $repUser;
    private $repUserGroup;
    private $repUserPermission;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->repUser = $this->em->getRepository('Users\Entities\User');
        $this->repUserGroup = $this->em->getRepository('Users\Entities\UserGroup');
        $this->repUserPermission = $this->em->getRepository('Users\Entities\UserPermission');
    }
    
    
    /*
     *  Actions
     */
    
    // Список пользователей
    public function listAction()
    {
        $form = new \Control\Forms;
        $form->setTitle('Пользователи ситемы управления');
        $form->setTemplate('list');
        $form->addHead(array(array('width'=>1, 'text'=>array('type'=>'checkbox',
                                                             'id'=>'all_check',
                                                             'action'=>'all_check();')),
                             array('width'=>1),
                             array('width'=>400, 'align'=>'left', 'text'=>'Имя'),
                             array('width'=>400, 'align'=>'left', 'text'=>'Логин'),
                             array('width'=>1) ));
        
        $rows = array();
        $users = $this->repUser->findBy(array());
        foreach ($users as $user)
        {
            $check  = array('type'   => 'checkbox',
                            'id'     => $user->id,
                            'class'  => 'check_item',
                            'attr'   => "align='center'" );
            
            $edit   = array('type'   => 'link_img_edit',
                            'href'   => '#Users/edit/'. $user->id );
            
            $delete = array('type'   => 'link_img_delete',
                            'action' => "javascript: if (MsgOkCancel('Действительно удалить?')) { delete_item('User','". $user->id ."'); }" );
            
            $login  = array('type'   => 'link',
                            'href'   => '#Users/edit/'. $user->id,
                            'text'   => $user->login);
            
            $name   = array('type'   => 'link',
                            'href'   => '#Users/edit/'. $user->id,
                            'text'   => $user->name);
            
            $rows[] = array($check, $edit, $name, $login, $delete);
        }
        
        $form->addRows($rows);
        
        $form->addFooter(array('type'    => 'select',
                               'id'      => 'select_list_options',
                               'options' => array('delete_checked'=>'Удалить выбранные','delete_all'=>'Удалить все') ));
        $form->addFooter(array('type'    => 'link',
                               'text'    => 'Выполнить!',
                               'action'  => "javascript: if (MsgOkCancel('Действительно удалить?')) {delete_check_item('user');}" ));
        
        return $form->render();
    }
    
    // Список опций
    public function optionsAction()
    {
        $live = \Control\Live::getInstance();
        
        $json = array();
        
        if ($live->user->access(array('module'=>'users', 'action'=>'add')))
            $json[] = array('title'=>'Добавить пользователя', 'href'=>'#Users/add');
        
        $childs = array();
        $users = $this->repUser->findBy(array());
        foreach ($users as $user)
            $childs[] = array('title' => $user->name, 'href' => '#Users/edit/'. $user->id);
        
        if ($live->user->access(array('module'=>'users','action'=>'list')))
            $json[] = array('title'=>'Список пользователей', 'href'=>'#Users/list', 'childs'=>$childs);
        
        if (count($json) > 0)
            $json[] = array('title'=>' ');
        
        if ($live->user->access(array('module'=>'users', 'action'=>'add_group')))
            $json[] = array('title'=>'Добавить группу', 'href'=>'#Users/addGroup');
        
        if ($live->user->access(array('module'=>'users', 'action'=>'edit_group')))
        {
            $childs = array();
            $groups = $this->repUserGroup->findBy(array(),array('id' => 'ASC'));
            foreach ($groups as $group)
                $childs[] = array('title' => $group->title, 'href' => '#Users/editGroup/'. $group->id);
        }
        if ($live->user->access(array('module'=>'users', 'action'=>'list_groups')))
            $json[] = array('title'=>'Группы пользователей', 'href'=>'#Users/listGroups', 'childs'=>$childs);
        
        if (count($json) > 0)
            $json[] = array('title'=>' ');
        
        if ($live->user->access(array('module'=>'users', 'action'=>'add_permission')))
            $json[] = array('title'=>'Добавить разрешение', 'href'=>'#Users/addPermission');
        
        if ($live->user->access(array('module'=>'users','action'=>'list_permissions')))
            $json[] = array('title'=>'Список разрешений', 'href'=>'#Users/listPermissions');
        
        // $json[] = array('title'=>'Настройки', 'href'=>'#Users/settings');
        
        return json_encode($json);
    }
    
    // Форма добавления пользователя
    public function addAction()
    {
        $data = new \Data\Data;
        $data->setName('User');
        $output = $data->addForm(array('title' => 'Добавить пользователя'));
        
        return $output;
    }
    
    // Форма редактирования пользователя
    public function editAction($id = null)
    {
        $conf = \Control\Core::conf();
        
        $id = intval($id);
        
        $user = $this->repUser->find($id);
        
        if(empty($user))
            return 'Пользователь не найден';
        
        $fields_str = implode($conf->ac_sep, array('login','name','pass','pass2'));
        
        $wysiwyg = $this->em->getRepository('Wysiwygs\Entities\Wysiwyg')->findOneBy(
            array('name' => $user->wysiwyg)
        );
        
        $user_settings = array();
        
        $fields = $this->em->getRepository('Data\Entities\DataField')->findBy(
            array('data_name' => 'User', 'active' => 1),
            array('num' => 'ASC')
        );
        
        foreach ($fields as $field)
        {
            $field_name = $field->name;
            
            $field->id = 'Users_'. $field->name;
            $field->value = ($field->type != 'password') ? $user->$field_name : '';
            $field->caption = $field->title;
            
            if (in_array($field->name, array('wysiwyg', 'wysiwyg_skin', 'wysiwyg_theme')))
            {
                $field->module          = 'Users';
                $field->data_field_name = $field_name;
            }
            
            if ($field_name == 'wysiwyg')       $field->action  = 'users_change_wysiwyg()';
            //if ($field_name == 'wysiwyg_skin')  $field->options = $wysiwyg->skins;
            //if ($field_name == 'wysiwyg_theme') $field->options = $wysiwyg->themes;
            
            $user_settings[$field->name] = $field;
        }
        
        $form = new \Control\Forms;
        $form->setTitle('Редактировать пользователя');
        $form->setTemplate('tabs');
        
        $form->addTab('Личные данные');
        $form->setTabTemplate('form');
        $form->addTabObject($user_settings['login']);
        $form->addTabObject($user_settings['name']);
        $form->addTabObject($user_settings['pass']);
        $form->addTabObject($user_settings['pass2']);
        
        $form->addTab('Разрешения');
        $form->setTabTemplate('form');
        
        $form->addTab('Настройки');
        $form->setTabTemplate('form');
        $form->addTabObject($user_settings['wysiwyg']);
        $form->addTabObject($user_settings['wysiwyg_skin']);
        $form->addTabObject($user_settings['wysiwyg_theme']);
        
        $form->addFooter(array(
            'type'  => 'button',
            'id'    => 'ok',
            'value' => 'Готово',
            'action'=> "save_item('User', '$fields_str', '$id', 'list')"
        ));
        $form->addFooter(array(
            'type'  => 'button',
            'id'    => 'save',
            'value' => 'Сохранить',
            'action'=> "save_item('User', '$fields_str', '$id')"
        ));
        $form->addFooter(array(
            'type'  => 'button',
            'id'    => 'cancel',
            'value' => 'Отмена',
            'href'  => '#Users/list'
        ));
        
        $wysiwygs = $this->em->getRepository('Wysiwygs\Entities\Wysiwyg')->findBy(array('active' => 1));
        foreach ($wysiwygs as $wysiwyg)
        {
            $form->addFooter(array(
                'type'  => 'hidden',
                'id'    => 'users_'. $wysiwyg->name .'_skins',
                'value' => $wysiwyg->skins
            ));
            $form->addFooter(array(
                'type'  => 'hidden',
                'id'    => 'users_'. $wysiwyg->name .'_default_skin',
                'value' => ($user->wysiwyg == $wysiwyg->name) ? $user->wysiwyg_skin : $wysiwyg->default_skin
            ));
            $form->addFooter(array(
                'type'  => 'hidden',
                'id'    => 'users_'. $wysiwyg->name .'_themes',
                'value' => $wysiwyg->themes
            ));
        }
        
        return $form->render();
    }
    
    // Удалить
    public function deleteAction($entity, $id)
    {
        $id = intval($id);
        
        $model = '\Users\\'. $entity;
        
        $data = new $model;
        $data->delete($id);
    }
    
    // Список групп пользователей
    public function listGroupsAction()
    {
        $form = new \Control\Forms;
        $form->setTitle('Группы пользователей');
        $form->setTemplate('list');
        $form->addHead(array(
            array('width' => 1),
            array('width' => 400, 'align' => 'left', 'text' => 'Название'),
            array('width' => 1)
        ));
        
        $rows = array();
        $groups = $this->repUserGroup->findBy(array(), array('id' => 'ASC'));
        foreach ($groups as $group)
        {
            $edit = array(
                'type'   => 'link_img_edit',
                'href'   => '#Users/editGroup/'. $group->id
            );
            
            $title = array(
                'type'   => 'link',
                'href'   => '#Users/editGroup/'. $group->id,
                'text'   => $group->title
            );
            
            $delete = array(
                'type'   => 'link_img_delete',
                'action' => "javascript: if (MsgOkCancel('Действительно удалить?')) { delete_item('UserGroup','". $group->id ."'); }"
            );
            
            $rows[] = array($edit, $title, $delete);
        }
        
        $form->addRows($rows);
        
        return $form->render();
    }
    
    // Форма добавления разрешения
    public function addGroupAction()
    {
        $data = new \Data\Data;
        $data->setName('UserGroup');
        $output = $data->addForm(array('title' => 'Добавить разрешение'));
        
        return $output;
    }
    
    // Форма редактирования группы пользователей
    public function editGroupAction($id = null)
    {
        $conf = \Control\Core::conf();
        
        $id = intval($id);
        
        $group = $this->repUserGroup->find($id);
        
        if(empty($group))
            return 'Группа не найдена';
        
        $data = new \Data\Data;
        $data->setName('UserGroup');
        
        $form = new \Control\Forms;
        $form->setTitle('Редактировать группу пользователей');
        $form->setTemplate('tabs');
        
        $modules = $this->em->getrepository('Modules\Entities\Module')->findBy(array());
        $modules_data = array();
        foreach ($modules as $module)
            $modules_data[$module->name] = $module->title;
        
        $form->addTab('Общие данные');
        $form->setTabTemplate('form');
        
        $fields = $this->em->getrepository('Data\Entities\DataField')->findBy(
            array('data_name' => 'UserGroup', 'active' =>1),
            array('num' => 'ASC')
        );
        foreach ($fields as $field)
        {
            $field_name = $field->name;
            
            $field->id = 'users_'. $field->name;
            $field->value = $group->$field_name;
            $field->caption = $field->title;
            
            $form->addTabObject($field);
        }
        
        $form->addTab('Разрешения');
        $form->setTabTemplate('default');
        
        $modules = $this->em->getrepository('Modules\Entities\Module')->findBy(array('active' => 1));
        foreach ($modules as $module)
        {
            $permissions = $this->repUserPermission->findBy(
                array('module' => $module->name)
            );
            
            if (!empty($permissions))
            {
                $link = new \stdClass;
                $link->type     = 'link';
                $link->action   = "if ($('#module_permissions_". $module->name ."').css('display')=='block') $('#module_permissions_". $module->name ."').hide(); else $('#module_permissions_". $module->name ."').show();";
                $link->text     = $module->title;
                $link->style    = "padding:0; border-bottom:1px dashed grey; text-decoration:none";
                $form->addTabObject($link);
                
                $form->addTabHTML('<br/><ul id="module_permissions_'. $module->name .'" style="display:block; list-style:none">');
                foreach ($permissions as $permission)
                {
                    $form->addTabHTML('<li>');
                    
                    $perm = new \stdClass;
                    $perm->type    = 'checkbox';
                    $perm->id      = $permission->id;
                    $perm->value   = ($permission->access_mask & $id) ? 1 : 0;
                    $perm->label   = $permission->title;
                    $perm->attributes = 'class="checkbox Users_permissions"';
                    $form->addTabObject($perm);
                    
                    $form->addTabHTML('</li>');
                }
                
                $form->addTabHTML('</ul><br/>');
            }
        }
        
        $form->addFooter(array('type'  => 'button',
                               'id'    => 'ok',
                               'value' => 'Готово',
                               'action'=> "users_group_edit('$id','". $data->fields_str ."','listGroups')") );
        $form->addFooter(array('type'  => 'button',
                               'id'    => 'save',
                               'value' => 'Сохранить',
                               'action'=> "users_group_edit('$id','". $data->fields_str ."')") );
        $form->addFooter(array('type'  => 'button',
                               'id'    => 'cancel',
                               'value' => 'Отмена',
                               'action'=> '#Users/listGroups') );
            
        return $form->render();
    }
    
    // Список разрешений
    public function listPermissionsAction()
    {
        $form = new \Control\Forms;
        $form->setTitle('Разрешения для пользователей');
        
        $modules = $this->em->getrepository('Modules\Entities\Module')->findBy(array());
        foreach ($modules as $module)
        {
            $permissions = $this->repUserPermission->findBy(
                array('module' => $module->name)
            );
            
            if (!empty($permissions))
            {
                $link = new \stdClass;
                $link->type = 'link';
                $link->action = "if($('#module_permissions_". $module->name ."').css('display')=='block')$('#module_permissions_". $module->name ."').hide(); else $('#module_permissions_". $module->name ."').show();";
                $link->text = $module->title;
                $link->style = "border-bottom:1px dashed grey";
                $form->addObject($link);
                
                $form->addHTML('<br/><ul id="module_permissions_'. $module->name .'" style="display:block; list-style:none">');
                foreach ($permissions as $permission)
                {
                    $form->addHTML('<li>');
                    
                    $edit = new \stdClass;
                    $edit->type = 'link_img_edit';
                    $edit->href = '#Users/editPermission/'. $permission->id;
                    $form->addObject($edit);
                    
                    $delete = new \stdClass;
                    $delete->type = 'link_img_delete';
                    $delete->action = "javascript: if (MsgOkCancel('Действительно удалить?')) { delete_item('UserPermission','". $permission->id ."'); }";
                    $form->addObject($delete);
                    
                    $link = new \stdClass;
                    $link->type = 'link';
                    $link->href = '#Users/editPermission/'. $permission->id;
                    $link->text = $permission->title;
                    $form->addObject($link);
                    
                    $form->addHTML('</li>');
                }
                
                $form->addHTML('</ul><br/>');
            }
        }
        
        return $form->render();
    }
    
    // Форма добавления разрешения
    public function addPermissionAction()
    {
        $data = new \Data\Data;
        $data->setName('UserPermission');
        $output = $data->addForm(array('title' => 'Добавить разрешение'));
        
        return $output;
    }
    
    // Форма редактирования разрешения
    public function editPermissionAction($id)
    {
        $id = intval($id);
        
        if(empty($id))
            return 'Разрешение не найдено';
        
        $data = new \Data\Data;
        $data->setName('UserPermission');
        
        $output = $data->editForm(array('title' => 'Редактировать разрешение'), $id);
        
        return $output;
    }
    
    public function saveAction($data_name, $id = null)
    {
        $data = array();
        
        $fields = explode($this->conf->ac_sep, $_POST['fields']);
        $values = explode($this->conf->ac_sep, $_POST['values']);
        
        foreach($fields as $key => $field)
            $data[$field] = $values[$key];
        
        switch($data_name)
        {
            case 'User':
                if ((!empty($data['pass']) && empty($data['pass2'])) ||
                    (empty($data['pass']) && !empty($data['pass2'])))
                    return ('Пароль нужно ввести дважды');
                
                if ($data['pass'] != $data['pass2'])
                    return ('Введенные пароли не совпадают');
                
                $user = new \Users\User;
                $user->save($data, $id);
                
                break;
            case 'UserGroup':
                $permissions_active = (!empty($_POST['permissions']))
                    ? explode(',', $_POST['permissions'])
                    : array();
                ;
                
                $group = new \Users\UserGroup;
                $group->save($data, $permissions_active, $id);
                
                break;
            case 'UserPermission':
                $permission = new \Users\UserPermission;
                $permission->save($data, $id);
                
                break;
        }
    }
    
    // проверка авторизации
    public function authAction()
    {
        $live = \Control\Live::getInstance();
        
        $auth = $live->user->auth();
        $output = json_encode(array('auth' => $auth));
        
        return $output;
    }
    
    // Форма регистрации
    public function registrationAction()
    {
        $live = \Control\Live::getInstance();
        
        $live->_data_need_template = true;
        $live->_data_need_theme = true;
        
        $this->data['title'] = 'Регистрация';
        
        $messages = array();
        $errors = array();
        
        if(!empty($_POST))
        {
            $data = array(
                'login' => filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING),
                'name'  => filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING),
                'email' => filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING),
                'pass'  => filter_var(trim($_POST['pass']), FILTER_SANITIZE_STRING),
                'pass2' => filter_var(trim($_POST['pass2']), FILTER_SANITIZE_STRING),
                'city'  => filter_var(trim($_POST['city']), FILTER_SANITIZE_STRING),
            );
            
            $errors = $live->user->validateRegistration($data);
            
            if(empty($errors))
            {
                $data = $live->user->prepareRegistration($data);
                
                $user = new \Users\User;
                $user->save($data, $id);
                
                $messages[] = 'Регистрация прошла успешно';
            }
            
            $this->data['messages'] = $messages;
            $this->data['errors'] = $errors;
            $this->data['users_registration_data'] = $data;
        }
        
        return $this->data;
    }
    
    // Форма регистрации
    public function authorizationAction()
    {
        $live = \Control\Live::getInstance();
        
        $live->_data_need_template = true;
        $live->_data_need_theme = true;
        
        $this->data['title'] = 'Авторизация';
        
        $errors = array();
        
        if(!empty($_POST))
        {
            $data = array(
                'user_login' => filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING),
                'user_hash'  => filter_var(trim($_POST['pass']), FILTER_SANITIZE_STRING),
            );
            
            if (empty($data['user_login']) || empty($data['user_hash']))
                $errors[] = 'Все поля обязательны для заполнения';
            
            if(empty($errors))
            {
                $data['user_hash'] = md5($data['user_hash']);
                
                $user = new \Users\User;
                $user->auth($data);
            }
            
            $this->data['errors'] = $errors;
            $this->data['users_authorization_data'] = $data;
        }
        
        return $this->data;
    }
    
    // Форма авторизации
    public function loginAction()
    {
        $live = \Control\Live::getInstance();
        $live->_data_need_template = true;
        
        $this->data['user_login'] = (!empty($_SESSION['user_login'])) ? $_SESSION['user_login'] : '';
        
        return $this->data;
    }
    
    public function logoutAction()
    {
        $user = new \Users\User;
        $user->logout();
        
        header('location:/');
    }
    
    public function editProfileAction()
    {
        $conf = \Control\Core::conf();
        $db   = \Control\Core::db();
        $live = \Control\Live::getInstance();
        
        $user = $live->user;
        
        $time = time();
        
        $fields     = explode($conf->ac_sep, $_POST['fields']);
        $new_values = str_replace($conf->ac_con, '&', $_POST['new_values']);
        $new_values = explode($conf->ac_sep, $new_values);
        
        $values_sql = array();
        for ($i=0; $i<count($fields); $i++)
        {
            $field = data::quote_smart($fields[$i]);
            
            switch($field)
            {
                case 'pass':
                {
                    $pass = $new_values[$i];
                    break;
                }
                case 'pass2':
                {
                    $pass2 = $new_values[$i];
                    break;
                }
                default:
                {
                    $value = data::quote_smart($new_values[$i]);
                    $values_sql[] = sprintf("`%s`='%s'", $field, $value);
                }
            }
        }
        
        if (!empty($pass) && !empty($pass2))
        {
            // если изменился пароль, генерируем хэш
            if ($pass == $pass2)
            {
                $hash = $_SESSION['user_hash'] = md5($user->id .':'. $user->login .':'. md5($pass) .':'. $user->salt);
                $values_sql[] = sprintf("`hash`='%s'", $hash);
            }
            else
            {
                echo 'Пароли не совпадают.';
                exit;
            }
        }
        
        $values_sql = implode(',',$values_sql);
        $db->Execute("UPDATE {$conf->db_prefix}users SET {$values_sql} WHERE `id`='{$user->id}'");
    }
    
    public function get_auth_loginAction()
    {
        $time = time();
        if ($this->db->Execute("SELECT `time` FROM {$this->conf->db_prefix}users_logined WHERE `code`='{$this->code}'")->RecordCount() != 0)
        {
            $_SESSION['user_code'] = $this->code;
            $_SESSION['user_login'] = $this->login;
            $this->db->Execute("UPDATE {$this->conf->db_prefix}users_logined SET `time`='{$time}' WHERE `code`='{$this->code}'");
            return true;
        }
        else
        {
            $_SESSION['user_code'] = '';
            $_SESSION['user_login'] = '';
            return false;
        }
    }
    
    public function pageAction()
    {
        $time = time();
        if ($this->db->Execute("SELECT `time` FROM {$this->conf->db_prefix}users_logined WHERE `code`='{$this->code}'")->RecordCount() != 0)
        {
            $_SESSION['user_code'] = $this->code;
            $_SESSION['user_login'] = $this->login;
            $this->db->Execute("UPDATE {$this->conf->db_prefix}users_logined SET `time`='{$time}' WHERE `code`='{$this->code}'");
            return true;
        }
        else
        {
            $_SESSION['user_code'] = '';
            $_SESSION['user_login'] = '';
            return false;
        }
    }
    
    public function userinfoBlock()
    {
        $user = new \Users\User;
        
        $data = ($user->auth())
            ? array('login' => $user->login)
            : array()
        ;
        
        return $data;
    }
    
    public function userlinksBlock()
    {
        $user = new \Users\User;
        
        $data = ($user->auth())
            ? array('login' => $user->login)
            : array()
        ;
        
        return $data;
    }
}