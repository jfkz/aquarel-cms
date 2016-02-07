<?php
/**
 * Module:  Data
 * About:   Модель базы данных - Поле типа данных
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Data\Entities;

/**
 * @Entity
 * @Table(name="data_fields")
 */
class DataField
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
    public $data_name;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $type;
    
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
    public $default_value;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $status;

    /**
     * @var integer
     * @Column(type="integer")
     */
    public $active;
    
    public function __construct() { }
}