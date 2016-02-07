<?php
/**
 * Class:   Module
 * About:   Модель - Модуль
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Modules\Entities;

/**
 * @Entity
 * @Table(name="modules")
 */
class Module
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
     * @Column(name="module_group", type="text")
     */
    public $group;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $version;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $install;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $active;
    
    public function __construct() {}
    
}