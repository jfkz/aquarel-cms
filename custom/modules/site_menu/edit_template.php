<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/system/plugins/control.forms.php');
    
    echo "<h1>Редактировать пункт меню</h1><br/>";
    
    echo control_form_edit('site_menu_template',$_POST['data']);
?>