<?php
/**
 * Module:  Data
 * About:   Модель базы данных - Тип данных
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Data\Entities;

/**
 * @Entity
 * @Table(name="data_types")
 */
class DataType
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
    public $num;
    
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
    public $template;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $url;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $entity;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $active;
    
    public function __construct() { }
}