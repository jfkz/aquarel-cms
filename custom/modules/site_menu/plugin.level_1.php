<?php
/**
 * Module:  Site_menu
 * About:   Выводит меню первого уровня
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 
 v.0.7

$root_dir = $_SERVER['DOCUMENT_ROOT'];
require($root_dir.'/system/config.php');
require($root_dir.'/system/db_connect.php');

$current_url = explode('?',$_SERVER['REQUEST_URI']);
$current_url = $current_url[0];

$page = $params['page'];

echo '<ul>';

$first = true;
$result = $db->Execute("SELECT `id`,`name`,`title` FROM {$db_prefix}pages WHERE `parent`='0' AND `show_in_menu`='1' ORDER BY `weight`");
while ($result && !$result->EOF)
    {
    $classes = array();
    if ($first)
        {
        $classes[] = 'first';
        $first = false;
        }
    
    if ($page['id'] == $result->fields['id'])
        {
        $classes[] = 'active';
        $href = '';
        }
    elseif ($page['parent_id'] == $result->fields['id'])
        {
        $classes[] = 'active';
        $href = ' href="/'.$result->fields['name'].'"';
        }
    else
        $href = ' href="/'.$result->fields['name'].'"';
    
    echo '<li class="'.implode(' ',$classes).'"><a'.$href.'>'.$result->fields['title'].'</a></li>';
    
    $result->MoveNext();
    }

echo '</ul>';
**/
?>