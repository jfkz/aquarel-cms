<?php
/**
 *  Class:   Wysiwygs
 *  About:   Модель базы данных - Визивиг
 *  Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Wysiwygs\Entities;

/**
 * @Entity
 * @Table(name="wysiwygs")
 */
class Wysiwyg
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
    public $include_path;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $include_filemanager_path;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $skins;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $default_skin;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $themes;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $default_theme;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $active;
    
    public function __construct() { }
    
}