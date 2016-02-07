<?php
/**
 *  Class:   Page
 *  About:   Модель базы данных - Страница
 *  Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Pages\Entities;

/**
 * @Entity
 * @Table(name="pages")
 */
class Page
{
    /**
     * @var integer
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    public $id;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $parent;
    
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
    public $meta_title;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $template;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $text;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $weight;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $show_in_menu;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $active;
    
    public function __construct() { }
    
}