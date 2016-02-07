<?php
/**
 * Module:  Site_menu
 * About:   Выводит меню второго уровня
 * Author:  Vlad Andreev <php.vlad@gmail.com>

v.0.7

$root_dir = $_SERVER['DOCUMENT_ROOT'];
require($root_dir.'/system/config.php');
require($root_dir.'/system/db_connect.php');

$page = $params['page'];

if (!is_array($page))
    {
    $page = $db->Execute(sprintf("SELECT `id`,`name`,`title`,`parent` FROM {$db_prefix}pages WHERE `id`='%d'",$page))->fields;
    }

$url = $_SERVER['REQUEST_URI'];

if($page['parent_id'] == 0)
    {
    $parent_data = $page;
    $sql = sprintf("SELECT `name`,`title` FROM {$db_prefix}pages WHERE `parent`='%d' AND `show_in_menu`='1' ORDER BY `weight`",intval($page['id']));
    }
else
    {
    $parent_data = $db->Execute(sprintf("SELECT `name`,`title` FROM {$db_prefix}pages WHERE `id`='%d'",$page['parent']))->fields;
    $sql = sprintf("SELECT `name`,`title` FROM {$db_prefix}pages WHERE `parent`='%d' AND `show_in_menu`='1' ORDER BY `weight`",$page['parent']);
    }

$result = $db->Execute($sql);

if ($result && $result->RecordCount() > 0)
    {
    if (is_array($params['page'])) echo '<h2>'.$parent_data['title'].'</h2>';
    echo '<ul>
    ';
    
    if (strpos($url,'/partners/blog') !== false)
        {
        echo '<li';
        if ($page['name'] == '') echo ' class="active"';
        echo '><a href="/partners/blog">Все записи блога</a></li>';
        }
    elseif (strpos($url,'/blog') !== false)
        {
        echo '<li';
        if ($page['name'] == '') echo ' class="active"';
        echo '><a href="/blog">Все записи блога</a></li>';
        }
    
    while ($result && !$result->EOF)
        {
        $name = $result->fields['name'];
        $title = $result->fields['title'];
        
        echo '<li';
        if ($page['name'] == $name) echo ' class="active"';
        echo '><a href="/'.$parent_data['name'].'/'.$name.'">'.$title.'</a></li>
        ';
        
        $result->MoveNext();
        }
    
    echo '
    </ul>
    ';    
    }
    */

?>