<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    require_once($root_dir.'/system/config.php');
    require_once($root_dir.'/system/db_connect.php');
    
    //echo "<img src='{$template_dir}images/logo.ico' width='20' height='20' />";
    
    $menu_top = '';
    $tab_first = true;
    
    $code = $_POST['code'];
    $permissions = $db->Execute("SELECT permissions FROM control_users WHERE code='{$code}'")->fields['permissions'];
    $permissions = explode(',',$permissions);
    
    $result = $db->Execute("SELECT id,title FROM control_menu WHERE active=1 AND level=0 ORDER BY weight");
    
    while ($result && !$result->EOF)
        {
        $parent_id = $result->fields['id'];
        $parent_title = $result->fields['title'];
        
        $res = $db->Execute("SELECT page,title,module FROM control_menu WHERE active=1 AND parent='".$parent_id."' ORDER BY weight");
        $inner_tab = '';
        while ($res && !$res->EOF)
            {
            $module = $res->fields['module'];
            $title = $res->fields['title'];
            $page = $res->fields['page'];
            
            $permission = $db->Execute("SELECT id FROM control_users_permissions WHERE module='{$module}' AND page='{$page}'")->fields['id'];
            if (in_array($permission,$permissions)) $inner_tab.= "<a class='top_menu lev1' onclick=\"load_module_page('$page','','$module'); load_options('$module'); $('a.lev1').css({color:'#1E90FF'}); $(this).css({color:'black'});\"> $title </a>";
            $res->MoveNext();
            }
        
        if ($inner_tab != '')
            {
            if (!$tab_first)
                {
                $style_display = "";
                $style_color = "";
                }
            else
                {
                $style_color = " style='color:black'";
                $style_display = " style='display:block'";
                $tab_first = false;
                }
            
            $menu_top.= "<a class='top_menu lev0' onclick=\"control_menu_changeTab('{$parent_id}'); $('a.lev0').css({color:'#1E90FF'}); $(this).css({color:'black'});\"{$style_color}>{$parent_title}</a>\n";
            $inner_tabs[] = "\n<div id='control_menu_tab_{$parent_id}' class='control_menu_tab'{$style_display}>{$inner_tab}</div>\n";
            }
            
        $result->MoveNext();
        }
    //echo "<img src='{$template_dir}images/logo.ico' width='20' height='20' />";
    
    if (is_array($inner_tabs))
        {
        if (count($inner_tabs)>1) echo $menu_top;
        echo implode('',$inner_tabs);
        }
?>