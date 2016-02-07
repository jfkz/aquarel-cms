<?php
/**
 * Class:   User
 * About:   Модель базы данных - Разрешение для пользователей
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Users\Entities;

/**
 * @Entity
 * @Table(name="users_permissions")
 */
class UserPermission
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
    public $title;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $module;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $page;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $action;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $access_mask;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $active;
    
    public function __construct() { }
    
}