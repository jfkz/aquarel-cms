<?php
/**
 * Class:   skyTheme
 * About:   Тема панели управления "Sky"
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

class skyTheme extends \Control\Theme
{
    
    public $blocks = array(
        'header',
        'left',
        'center',
        'right',
        'footer',
    );
    
    public function __construct()
    {
        $this->template_index = 'index';
        $this->template_default = 'index';
    }
    
}