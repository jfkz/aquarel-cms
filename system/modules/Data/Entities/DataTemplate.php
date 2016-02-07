<?php
/**
 * Module:  Data
 * About:   Модель базы данных - Тип данных
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Data\Entities;

/**
 * @Entity
 * @Table(name="data_templates")
 */
class DataTemplate
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
    public $fields;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $page_template;
    
    public function __construct() { }
}