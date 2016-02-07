<?php
/**
 * Class:   User
 * About:   Модель базы данных - Пользователь
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Users\Entities;

/**
 * @Entity
 * @Table(name="users")
 */
class User
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
     * @Column(name="user_group", type="integer")
     */
    public $group;
    
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
     * @var string
     * @Column(type="text")
     */
    public $city;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $time_c;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $time_u;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $time_l;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $active;
    
    public function __construct() { }
    
}