<?php
/**
 * Class:   User
 * About:   Модель - Пользователь
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Users;

class User extends \Control\Model
{
    protected $repository;
    protected $repositoryPermission;
    
    /*
     * ToDo: Оставить только одно поле Login или Name
     */
    public $id;
    public $login;
    public $name;               
    public $email;
    public $hash;
    public $salt;
    public $group;
    public $time_c;
    public $time_u;
    public $time_l;
    public $wysiwyg;
    public $wysiwyg_skin;
    public $wysiwyg_theme;
    public $active;
    public $authorised = false;
    public $have_access = false;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->repository = $this->em->getRepository('Users\Entities\User');
        
        $login = (!empty($_SESSION['user_login'])) ? $_SESSION['user_login'] : '';
        if (!empty($login))
        {
            $data = $this->repository->findOneBy(array('login' => $login));
            $this->setData($data);
        }
        else
        {
            $this->group = 16;
        }
    }
    
    public function setData($data)
    {
        if (!empty($data))
        {
            $this->id    = $data->id;
            $this->login = $data->login;
            $this->email = $data->email;
            $this->hash  = $data->hash;
            $this->salt  = $data->salt;
            $this->group = $data->group;
        }
    }
    
    public function validateRegistration($data)
    {
        $errors = array();
        
        if(empty($data))
            $errors[] = 'Данные отсутствуют.';
        
        if (empty($data['login']) || empty($data['email']) || empty($data['pass']) || empty($data['pass2']) || empty($data['city']))
            $errors[] = 'Все поля обязательны для заполнения.';
        
        if(!$this->checkUniqueEmail($data['email']))
            $errors[] = 'Этот E-mail уже используется в системе.';
        
        if ($data['pass'] != $data['pass2'])
            $errors[] = 'Введенные пароли не совпадают.';
        
        return $errors;
    }
    
    public function prepareRegistration($data)
    {
        unset($data['pass2']);
        $data['group'] = 16;
        
        return $data;
    }
    
    // Cохранить
    public function save($data, $id = null)
    {
        if(!empty($id))
            $user = $this->find($id);
        
        if(empty($user))
        {
            $user = new \Users\Entities\User;
            
            $user->hash = '';
            $user->salt = $this->generateSalt();
            $user->group = 0;
            $user->active = 0;
            $user->wysiwyg = 'fckeditor';
            $user->wysiwyg_theme = 'Basic';
            $user->wysiwyg_skin = 'office2003';
            $user->time_c = time();
            $user->time_l = time();
            $user->time_u = time();
        }
        
        foreach($data as $field => $value)
            $user->$field = $value;
        
        $this->em->persist($user);
        $this->em->flush();
        
        if (!empty($data['pass']))
        {
            $user->hash = md5($user->id .':'. $user->login .':'. md5($data['pass']) .':'. $user->salt);
            
            $this->em->persist($user);
            $this->em->flush();
        }
        
        return $user->id;
    }
    
    public function get_additional_field($field)
    {
        return null;
        
        /*
        $user_additional_data = $this->em->getRepository('UsersAdditionalData');
        $data = $user_additional_data->find($this->id);
        return ($data) ? $data->$field : null;
        */
    }
    
    public function set_additional_field($field, $value)
    {
        $db_prefix = \Control\Core::conf()->db_prefix;
        $this->db = \Control\Core::db();
        
        if($this->db->Execute("SELECT `{$field}` FROM {$this->conf->db_prefix}users_additional_data WHERE `id`='{$this->id}'")->RecordCount() == 1)
            $this->db->Execute("UPDATE {$this->conf->db_prefix}users_additional_data SET `{$field}`='{$value}' WHERE `id`='{$this->id}'");
        else
            $this->db->Execute("INSERT INTO {$this->conf->db_prefix}users_additional_data SET `{$field}`='{$value}', `id`='{$this->id}'");
    }
    
    public function __get($field)
    {
        // return $this->get_additional_field($field);
    }
    
    public function __set($field, $value)
    {
        // $this->set_additional_field($field, $value);
    }
    
    /* Авторизация пользователя */
    public function auth($data = null)
    {
        $auth = false;
        
        if (empty($data))
            $data = $_POST;
        
        // пользователь авторизован
        if (!empty($_SESSION['user_login']) && !empty($_SESSION['user_hash']))
        {
            $input_login = $_SESSION['user_login'];
            $input_hash  = $_SESSION['user_hash'];
        }
        // пользователь неавторизован, ввел логин и пароль
        elseif(isset($data['user_login']) && isset($data['user_hash']))
        {
            $input_login = $data['user_login'];
            $input_hash  = $data['user_hash'];
        }
        
        if(!empty($input_login))
        {
            $user = $this->repository->findOneBy(array('login' => $input_login));
            if(empty($user))
                $user = $this->repository->findOneBy(array('email' => $input_login));
        }
        
        if(!empty($user))
        {
            $hash = md5($user->id .':'. $user->login .':'. $input_hash .':'. $user->salt);
            
            if($user->hash == $hash)
            {
                $user->time_l = time();
                $this->em->persist($user);
                $this->em->flush();
                
                $_SESSION['user_login'] = $user->login;
                $_SESSION['user_hash']  = $input_hash;
                
                $auth = true;
            }
            else
            {
                unset($_SESSION['user_login']);
                unset($_SESSION['user_hash']);
                
                $auth = false;
            }
        }
        
        return ($auth && !empty($user->id))
            ? $user->id
            : false
        ;
    }
    
    /* Проверка уровня доступа пользователя */
    public function access($handler)
    {
        $live = \Control\Live::getInstance();
        $access = false;
        
        $this->repositoryPermission = $this->em->getRepository('Users\Entities\UserPermission');
        
        if (!empty($handler['module']) && (!empty($handler['page']) || !empty($handler['action'])))
        {
            $handler['active'] = 1;
            
            if (!empty($handler['action']))
                $handler['action'] = str_replace('Action', '', $handler['action']);
            
            $permission = $this->repositoryPermission->findOneBy($handler);
            
            // permission not found
            if (empty($permission))
            {
                // access control method
                if($live->_theme_control)
                {
                    $handler_control = array(
                        'module' => 'Control',
                        'page' => 'index',
                    );
                    $permission_control = $this->repositoryPermission->findOneBy($handler_control);
                    
                    if(empty($permission_control) || ($permission_control->access_mask & $this->group))
                        $access = true;
                }
                else
                    $access = true;
            }
            elseif($permission->access_mask & $this->group)
                $access = true;
        }
        
        return $access;
    }
    
    /* Выход из системы */
    public function logout()
    {
        unset($_SESSION['user_login']);
        unset($_SESSION['user_hash']);
    }
    
    
    /*
     *  Accessorial functions
     */
    
    public function checkUniqueEmail($email)
    {
        $user = $this->repository->findOneBy(array('email' => $email));
        
        return empty($user);
    }
    
    private function generateSalt()
    {
        return rand(100000, 1000000);
    }
}