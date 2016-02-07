<?php
/**
 * Class:   User
 * About:   Модель базы данных - группа пользователей
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 *
 *
 * @GeneratedValue(strategy="AUTO")
 * 
 **/

namespace Users\Entities;

/**
 * @Entity
 * @Table(name="users_groups")
 */
class UserGroup
{
    /**
     * @var integer
     * @Id @Column(type="integer")
     */
    public $id;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $name;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $title;
    
    public function __construct() { }
}