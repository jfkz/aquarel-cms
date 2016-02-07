<?php
/**
 * Class:   User
 * About:   Модель - Пользователь
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

/**
 * @Entity
 * @Table(name="users")
 */
class User extends Model
{
    /**
     * @var integer
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    public $id;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $login;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $name;               // ToDo: Оставить только одно поле Login или Name
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $email;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $hash;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $salt;
    
    /**
     * @var integer
     * @Id @Column(type="integer")
     */
    public $group;
    
    /**
     * @var integer
     * @Id @Column(type="integer")
     */
    public $time_c;
    
    /**
     * @var integer
     * @Id @Column(type="integer")
     */
    public $time_u;
    
    /**
     * @var integer
     * @Id @Column(type="integer")
     */
    public $time_l;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $wysiwyg;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $wysiwyg_skin;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $wysiwyg_theme;
    
    /**
     * @var integer
     * @Id @Column(type="integer")
     */
    public $active;
    
    protected $conf;
    protected $em;
    protected $repository;
    
    public $authorised = false;
    public $have_access = false;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->repository = $this->em->getRepository('User');
        
        $this->load(array('login' => (!empty($_SESSION['user_login'])) ? $_SESSION['user_login'] : ''));
        
        // Если $login не задан, пробуем достать из сессии
        //if (empty($login))
        //    $login = (!empty($_SESSION['user_login'])) ? $_SESSION['user_login'] : '';
        
        //$this->login = $login;
        //$this->setData();
    }
    
    public function setData()
    {
        if (!empty($this->login))
        {
            $result = $this->db->Execute(sprintf("SELECT `id`, `login`, `name`, `email`, `hash`, `salt`, `group` FROM {$this->conf->db_prefix}users WHERE `login`='%s'", $this->login));
            if ($result)
            {
                $this->id    = $result->fields['id'];
                $this->login = $result->fields['login'];
                $this->email = $result->fields['email'];
                $this->hash  = $result->fields['hash'];
                $this->salt  = $result->fields['salt'];
                $this->group = $result->fields['group'];
            }
        }
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
        $db_prefix = core::conf()->db_prefix;
        $this->db = core::db();
        
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
    public function auth()
    {
        $conf = core::conf();
        $db   = core::db();
        $time = time();
        
        // пользователь авторизован
        if (!empty($_SESSION['user_login']) && !empty($_SESSION['user_hash']))
        {
            $input_login = $_SESSION['user_login'];
            $input_hash  = $_SESSION['user_hash'];
        }
        // пользователь неавторизован, ввел логин и пароль
        elseif(isset($_POST['user_login']) && isset($_POST['user_hash']))
        {
            $input_login = $_POST['user_login'];
            $input_hash  = $_POST['user_hash'];
        }
        else
        {
            return false;
        }
        
        $data = $this->repository->findOneBy(array('login' => $input_login));
        
        $hash = md5($user_data['id'].':'.$input_login.':'.$input_hash.':'.$user_data['salt']);
        if($user_data['hash'] == $hash)
        {
            $db->Execute(sprintf("UPDATE {$conf->db_prefix}users SET `time_l`='%d' WHERE `id`='%d'", time(), $user_data['id']));
            
            $_SESSION['user_login'] = $input_login;
            $_SESSION['user_hash']  = $input_hash;
            
            return true;
        }
        else
        {
            unset($_SESSION['user_hash']);
            return false;
        }
    }
    
    /* Проверка уровня доступа пользователя */
    public function access($handler)
    {
        if (!empty($handler['module']) && (!empty($handler['page']) || !empty($handler['option'])))
        {
            $where_sql = array(sprintf("`module`='%s'", $handler['module']));
            if (!empty($handler['option'])) $where_sql[] = sprintf("`option`='%s'", $handler['option']);
            if (!empty($handler['page']))   $where_sql[] = sprintf("`page`='%s'", $handler['page']);
            $where_sql = implode(" AND ",$where_sql);
            
            $permission = $this->db->Execute("SELECT `access_mask` FROM {$this->conf->db_prefix}users_permissions WHERE ".$where_sql."; ");
            
            if ($permission->RecordCount() == 0 || $permission->fields['access_mask'] & $this->group)
                return true;
        }
        
        return false;
    }
    
    /* Выход из системы */
    public function logout()
    {
        unset($_SESSION['user_hash']);
    }
}