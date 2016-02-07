<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/system/plugins/control.forms.php');
    
    echo "<h1>Добавить шаблон</h1><br/>";
    
    echo control_form_add('site_menu_template');
?>