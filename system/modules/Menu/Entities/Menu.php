<?php
/**
 *  Name:    Menu
 *  Type:    Model
 *  About:   Модель базы данных - Пункт меню
 *  Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Menu\Entities;

/**
 * @Entity
 * @Table(name="menu")
 */
class Menu
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
    public $name;
    
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
    public $options;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $parent;
    
    /**
     * @var integer
     * @Column(type="integer", columnDefinition="DEFAULT 0")
     */
    public $weight;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $level;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $data;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $page;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $childs;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $active;
    
    public function __construct() {}
    
}