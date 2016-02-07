<?php
/**
 * Class:   Route
 * About:   Модель - Маршрут
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Router\Entities;

/**
 * @Entity
 * @Table(name="routes")
 */
class Route
{
    /**
     * @var integer
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $route;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $controller;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $action;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $template;
    
    public function __construct() {}
}