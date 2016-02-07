<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/system/plugins/control.forms.php');
    
    echo "<h1>Добавить пункт меню</h1><br/>";
    
    echo control_form_add('control_menu');
?>