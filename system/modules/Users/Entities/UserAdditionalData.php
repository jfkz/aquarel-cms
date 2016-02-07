<?php
/**
 * Class:   UserAdditionalData
 * About:   Модель - Дополнительные данные пользователя
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

/**
 * @Entity
 * @Table(name="users_additional_data")
 */
class UserAdditionalData extends Model
{
    /**
     * @var integer
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
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
    
    public function __construct()
    {
        parent::__construct();
        
        $this->repository = $this->em->getRepository('UserAdditionalData');
        
        // Если $login не задан, пробуем достать из сессии
        //if (empty($login))
        //    $login = (!empty($_SESSION['user_login'])) ? $_SESSION['user_login'] : '';
        
        //$this->login = $login;
        //$this->setData();
    }
    
}