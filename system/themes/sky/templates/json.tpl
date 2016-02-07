<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{$site_name}</title>
    <meta name="description" content="" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <!--[if IE7]>
    <style type="text/css"> BODY { behavior:url("{$template_dir}/csshover.htc"); } </style>
    <![endif]-->
    
    <link rel="stylesheet" type="text/css" href="{$template_dir}/style.css" />
    
    {$js_vars}
    
    <script type="text/javascript" src="/system/lib/jQuery/jquery-1.5.min.js"></script>
    <script type="text/javascript" src="/system/lib/jQuery/jquery.core.min.js"></script>
    <script type="text/javascript" src="/system/lib/jQuery/jquery.draggable.min.js"></script>
    <script type="text/javascript" src="/system/lib/jQuery/jquery.droppable.js"></script>
    <script type="text/javascript" src="/system/lib/jQuery/jquery.contextmenu.js"></script>
    <script type="text/javascript" src="/system/lib/jQuery/jquery.md5.min.js"></script>
    <script type="text/javascript" src="/system/lib/jQuery/jquery.blockUI.min.js"></script>
    <script type="text/javascript" src="/system/lib/jQuery/jquery.hashchange.min.js"></script>
    
    <script type="text/javascript" src="{$template_dir}/json.js"></script>
    
    <!-- wysiwyg -->
    {$wysiwyg_load}
    {$wysiwyg_filemanager_load}

    <!-- draggebleColumns (http://sonspring.com/journal/jquery-portlets/) -->
    <script type="text/javascript" src="/system/lib/js/portlets.interface.js"></script>
    
    <!-- draggebleTree (http://news.kg/wp-content/uploads/tree/d&d/) -->
    <script type="text/javascript" src="/system/lib/jQuery/jquery.simple.tree.min.js"></script>
    
    <!-- ajaxFileUpload -->
    <script type="text/javascript" src="/system/lib/js/fileupload/ajaxfileupload.min.js"></script>
    
    <!-- hot keys (http://www.openjs.com/scripts/events/keyboard_shortcuts/) -->
    <script type="text/javascript" src="/system/lib/js/shortcut.min.js"></script>
    
    <!-- fonts (http://cufon.shoqolate.com/) -->
    <script type='text/javascript' src='/system/lib/js/cufon-yui.js'></script>
    <script type='text/javascript' src='/system/lib/js/aller_400.font.js'></script>
    
    {$js_files}
    
    <script type="text/javascript" src="/system/javascripts/control.js"></script>
</head>
<body>
    <span id="current_item" style="display:none"></span>
    <span id="db_loading" style="display:none"></span>
    
    <div class="header">
        <div class="logo">
            <a href="/control"><img src="{$template_dir}/images/logo.png" alt="" /></a>
                <div class="site_name">
                    <div class="l"><div class="r"><div class="f"><a href="/" target="_blank">{$site_name}</a></div></div></div>
                </div>
            <h1><a href="/control"><font color="#656d71">AQUAREL</font><font color="#c7c7c7">CMS</font></a></h1>
        </div>
        
        <div class="top_menu" id="top_menu">Идет загрузка...</div>
        
        <div class="user_buttons" id="user_buttons">
            {if $user_login != ''}
                Здравствуйте, <span  class="name">{$user_login}</span><br/><a class="js-link" href="#users/edit_profile/{$user_login}">профиль</a> <a class="exit" onclick="users_logout()">выйти</a>
            {/if}
        </div>
    </div>
    
    <div class="left_menu" id="left_menu"></div>
    
    <div class="main_body" id="main_body"><center>Идет загрузка...</center></div>
    
    <br class="clear none" />
    
    <div class="footer">
        <div id="debug_info"></div>
        <div style="margin-right:12px;">Aquarel CMS v.{$cms_version}</div>
    </div>
    
    <div class="shop_hint_bar" id="loading">Идет загрузка...</div>
    
    <input type="hidden" id="uname" name="uname" value="{$user_login}" />
    
    <input type="hidden" id="current_module" name="current_module" value="" />
    <input type="hidden" id="current_page" name="current_page" value="" />
    <input type="hidden" id="current_page_data" name="current_page_data" value="" />
    <input type="hidden" id="current_options" name="current_options" value="" />
    <input type="hidden" id="option" name="option" value="" />
    
    <input type="hidden" id="wysiwyg" name="wysiwyg" value="{$wysiwyg}" />
    <input type="hidden" id="wysiwyg_skin" name="wysiwyg_skin" value="{$wysiwyg_skin}" />
    <input type="hidden" id="wysiwyg_theme" name="wysiwyg_theme" value="{$wysiwyg_theme}" />
    
    <input type="hidden" id="server_name" name="server_name" value="{$server_name}" />
</body>
</html>