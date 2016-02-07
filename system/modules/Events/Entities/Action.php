<?php
/**
 * Module:  Events
 * About:   Модель базы данных - Действие
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Events\Entities;

/**
 * @Entity
 * @Table(name="actions")
 */
class Action
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
     * @var integer
     * @Column(type="integer")
     */
    public $active;
    
    public function __construct() { }
}