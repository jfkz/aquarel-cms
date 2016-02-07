<?php
/**
 *  Name:    Mail
 *  About:   Модель базы данных - Письмо
 *  Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Mail\Entities;

/**
 * @Entity
 * @Table(name="mail")
 */
class Mail
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
    public $title;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $subject;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $text;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $tags;
    
    /**
     * @var string
     * @Column(type="text")
     */
    public $event;
    
    /**
     * @var integer
     * @Column(type="integer")
     */
    public $active;
    
    public function __construct($data = null) { }
}