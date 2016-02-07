<?php
/**
 * Class:   Setting
 * About:   Модель - Настройка
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Settings\Entities;

/**
 * @Entity
 * @Table(name="settings")
 */
class Setting
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
    public $caption;
 
    /**
     * @var string
     * @Column(type="text")
     */
    public $type;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $value;
    
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
     * @var string
     * @Column(type="text")
     */
    public $attributes;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $status;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $success;
    
    public function __construct() {}
    
}