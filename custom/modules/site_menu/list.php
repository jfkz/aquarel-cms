<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    require_once($root_dir.'/system/config.php');
    require_once($root_dir.'/system/db_connect.php');
    require_once($root_dir.'/system/plugins/control.form_elements.php');
    
    $module_name = 'site_menu';
    $table_name = 'site_menu_templates';

/**************************************************************************/

    echo "<h1>Меню сайта</h1><br/>";
    
    $rows = array();
    $rows[] = array(
                    array("width='1'","<input id='all_check' name='all_check' onclick='all_check();' type='checkbox' />"),
                    array("width='1'",''),
                    array("align='left' width='500'",'Имя'),
                    array("align='left' width='300'",'Идентификатор'),
                    array("width='1'",''));
    
    $result = $db->Execute("SELECT id,name,title FROM {$table_name}");
    while ($result && !$result->EOF)
        {
        $id = $result->fields['id'];
        $name = $result->fields['name'];
        $title = $result->fields['title'];
        
        $check = "<input align='center' class='check_item' id='$id' type='checkbox' />";
        $edit = "<a style='cursor:pointer' onclick=\"load_module_page('edit_template','$id')\"><img title='редактировать' width='25' height='25' src='".$template_dir."images/edit.ico' /></a>";    
        $delete = "<a style='cursor:pointer' onclick=\"javascript: if (MsgOkCancel('Действительно удалить?')) { delete_item('$table_name','$id'); } \"><img title='удалить' width=25 height=25 src='".$template_dir."images/delete.ico' /></a>";
        $title = array("style='cursor:pointer;' onclick=\"load_module_page('edit_template','$id')\"",$title);
        $name = array("style='cursor:pointer;' onclick=\"load_module_page('edit_template','$id')\"",$name);
        
        $rows[] = array($check,$edit,$name,$title,$delete);
        $result->MoveNext();
        }
    
    $table_params = array('class'=>'table_list',
                          'params'=>"border='1' cellspacing='0' cellpadding='3'",
                          'th'=>true);
    echo _addTable($rows,$table_params);
    
    echo "<select id='select_list_options'>
          <option>Удалить выбранные</option>
          <option>Удалить все</option>
          </select>
          <a style='cursor:pointer' onclick=\"javascript: if (MsgOkCancel('Действительно удалить?')) {delete_check_item('$table_name');}\"> Выполнить!</a><br/>";
?>