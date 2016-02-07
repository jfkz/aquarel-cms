<?php
/**
 * Module:  Events
 * About:   Модель базы данных - Событие
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Events\Entities;

/**
 * @Entity
 * @Table(name="monitor")
 */
class Monitor
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
    public $event;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $module;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $action;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $weight;
    
    public function __construct() { }
}