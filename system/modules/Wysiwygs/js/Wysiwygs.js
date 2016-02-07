function wysiwygs_load()
    {
    var wysiwyg = $('#wysiwyg').val();
    var wysiwyg_skin = $('#wysiwyg_skin').val().split('--')[0];
    if ($('#wysiwyg_skin').val().split('--')[1] == 'undefined') var wysiwyg_skin_variant = "";
    else var wysiwyg_skin_variant = $('#wysiwyg_skin').val().split('--')[1];
    var wysiwyg_theme = $('#wysiwyg_theme').val();
    
    if (wysiwyg == 'tinymce')
        {
        $.each($('textarea.wysiwyg'),function(){
	    $(this).tinymce({
		// General options
		script_url : '/system/lib/TinyMCE.jquery/jscripts/tiny_mce/tiny_mce.js',
		mode : 'textareas',
		theme: wysiwyg_theme,
		skin : wysiwyg_skin,
		skin_variant: wysiwyg_skin_variant,
		
		file_browser_callback : 'tinyBrowser',
		
		/* Theme options
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,*/
		
		// Example content CSS (should be your site CSS)
		content_css : 'css/example.css',
		
		// Drop lists for link/image/media/template dialogs
		template_external_list_url : 'js/template_list.js',
		external_link_list_url : 'js/link_list.js',
		external_image_list_url : 'js/image_list.js',
		media_external_list_url : 'js/media_list.js',
		
		language : 'ru',
		
		// Replace values for the template plugin
		template_replace_values : {
		    username : 'Some User',
		    staffid : '991234'
		    }
		});
	});
        }
    if (wysiwyg == 'ckeditor')
        {
	var ckeditor_count = 0;
	$.each(CKEDITOR.instances, function(){ ckeditor_count++; });
	if (ckeditor_count > 0) $.each(CKEDITOR.instances, function(){ CKEDITOR.remove(this); });
	
	var editors = new Array();
	$.each($('textarea.wysiwyg'),function(){
	    var name = $(this).attr('name');
	    
	    editors[name] = CKEDITOR.replace( name,
		{
		toolbar : wysiwyg_theme, // файл /system/lib/CKEditor/config.js
		uiColor : '#9AB8F3',
		filebrowser : 'true',
		skin : wysiwyg_skin
		} );
	    
	    AjexFileManager.init({
		returnTo: 'ckeditor', // [ckeditor, tinymce, function] default=ckeditor
		path: '/system/lib/CKEditor/ajexFileManager/', // Определяется автоматически, но если вдруг не удается то можно прописать
		editor: editors[name], // Объект CKEDitor'a, нужен только для него
		width: '1000', // Ширина popup, default=1000
		height: '660', // Высота popup, default=660
		skin: 'dark', // [dark, light], default=dark
		lang: 'ru', // Язык, сейчас есть [ru, en], default=ru
		connector: 'php', // default=php,
		contextmenu: true // [true, false], default=true
	    });
	});
        
        }
    if (wysiwyg == 'fckeditor')
        {
	$.each($('textarea.wysiwyg'),function(){
	    
	    var oFCKeditor = new FCKeditor( $(this).attr('id') ) ;
	    oFCKeditor.BasePath = '/system/lib/fckeditor/';
	    oFCKeditor.Height   = $(this).css('height');
	    oFCKeditor.Width    = $(this).css('width');
	    oFCKeditor.Config['SkinPath'] = '/system/lib/fckeditor/editor/skins/' + wysiwyg_skin + '/'; //'http://'+$('#server_name').val()+':81'+
	    oFCKeditor.Config['CustomConfigurationsPath'] = '/system/lib/fckeditor/ac_config.js' ;
	    oFCKeditor.ToolbarSet = wysiwyg_theme; // файл /system/lib/fckeditor/ac_config.js
	    oFCKeditor.ReplaceTextarea() ;
	    });
        }
    }

function wysiwygs_save(id)
    {
    var wysiwyg = $('#wysiwyg').val();
    var new_val = '';
    
    if (wysiwyg == 'tinymce')
        {
        new_val = $('#'+id).html();
        }
    if (wysiwyg == 'ckeditor')
        {
        $.each(CKEDITOR.instances, function(){
            if (this.name == id) new_val = this.getData();
            });
        }
    if (wysiwyg == 'fckeditor')
        {
        $.each(FCKeditorAPI.Instances, function(){
            if (this.Name == id) new_val = this.GetData();
            });
        }
    return new_val;
    }

function wysiwygs_save_settings()
    {
    $('#debug_info').load(
            '../system/modules/wysiwygs/db.php',
            { option: 'save_settings', field: 'name', value: $('#wysiwygs_name').val() }
            );
    
    $('#wysiwygs_info').html('<br/>Для того, чтобы изменения вступили в силу, необходимо <a style="color:blue; text-decoration:underline" onclick="location.href = \'/control\'">обновить</a> страницу.<br/><br/>');
    }

function wysiwygs_change_tab()
    {
    var tab = $('#wysiwygs_name').val();
    
    if ($('#wysiwygs_tab_'+tab).css('display') == 'none')
        {
        $('div.control_wysiwygs_tab').hide();
        $('#wysiwygs_tab_'+tab).show();
        }
    }