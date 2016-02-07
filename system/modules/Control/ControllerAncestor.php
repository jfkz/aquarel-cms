<?php
/**
 * Class:   Controller
 * About:   Класс-предок контроллеров
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Control;

class ControllerAncestor
{
    protected $conf;
    protected $em;
    protected $data = array();
    protected $_use_theme = false;
    
    public function __construct()
    {
        $this->conf = Core::conf();
        $this->em   = Core::em();
    }
}