<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    require_once($root_dir.'/system/config.php');
    require_once($root_dir.'/system/db_connect.php');
    
    /**************************************************************************/
    
    echo "<a class='first-level' onclick=\"load_module_page('add_template')\">Добавить шаблон</a><br/>";
    echo "<a class='first-level' onclick=\"load_module_page('list')\">Шаблоны</a><br/>";
    echo "<a class='first-level'>Настройки</a><br/>";            
?>