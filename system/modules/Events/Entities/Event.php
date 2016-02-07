<?php
/**
 * Module:  Events
 * About:   Модель базы данных - Событие
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Events\Entities;

/**
 * @Entity
 * @Table(name="events")
 */
class Event
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
     * @var integer
     * @Column(type="integer")
     */
    public $active;
    
    public function __construct() { }
}