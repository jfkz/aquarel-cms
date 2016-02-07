<?php
/**
 * Class:   Block
 * About:   Модель - Блок
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Blocks\Entities;

/**
 * @Entity
 * @Table(name="blocks")
 */
class Block
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
    public $text;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $module;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $block;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $page;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $position;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $weight;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $active;
    
    public function __construct() {}
}