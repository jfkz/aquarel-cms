/**
 * Class:   jsonTemplate
 * File:    json.js
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

with(jsonTemplate = new Function){
    
    prototype.files      = new Array();
    prototype.images	 = new Array();
    
    prototype.img_loader = '<img src="'+acTpl+'images/loader.gif" alt="" />';
    prototype.img_add    = '<img width="15" height="15" src="'+acTpl+'/images/add.png" title="добавить" alt="добавить" />';
    prototype.img_edit   = '<img width="15" height="16" src="'+acTpl+'/images/edit.png" title="редактировать" alt="редактировать" />';
    prototype.img_save   = '<img width="15" height="15" src="'+acTpl+'/images/save.png" title="сохранить" alt="сохранить" />';
    prototype.img_delete = '<img width="16" height="16" src="'+acTpl+'/images/delete.png" title="удалить" alt="удалить" />';
    prototype.img_list   = '<img width="15" height="15" src="'+acTpl+'/images/list.png" title="список" alt="список" />';
    prototype.img_active = '<img width="16" height="16" src="'+acTpl+'/images/active.png" title="выключить" alt="выключить" />';
    prototype.img_disable= '<img width="16" height="16" src="'+acTpl+'/images/disable.png" title="включить" alt="включить" />';
    prototype.img_folder = '<img width="15" height="16" src="'+acTpl+'/images/folder.gif" title="открыть" alt="открыть" />';
    
    // вывод страницы выбранного модуля
    prototype.load_page = function(data){
	prototype.files  = new Array();
	prototype.images = new Array();
	
	var content = '';
	
	if (data.template && data.template != '') content = '<div class="'+data.template+'">';
	else content = '<div class="form">';
	
	if (data.title && data.title != '') content+= '<h1>'+data.title+'</h1>';
	content+= '<div class="inner">';
	
	content+= prototype.template(data);
	
	if (data.footer) $.each(data.footer,function() { content+= prototype.element(this); });
	
	content+= '</div></div>';
	$('#main_body').html(content);
	
	switch (data.template)
	    {
	    case 'tree': {
		var simpleTreeCollection = $('.control_draggebleTree').simpleTree({
		    autoclose: 		data.tree.properties.autoclose,
		    animate:		data.tree.properties.animate,
		    docToFolderConvert:	data.tree.properties.docToFolderConvert,
		    afterClick:    	function(node){ data.tree.properties.afterClick },
		    afterDblClick: 	function(node){ eval(data.tree.properties.afterDblClick) },
		    afterMove:     	function(destination, source, pos){ eval(data.tree.properties.afterMove) },
		    afterAjax:     	function() { } });
		break;
		}
	    case 'auth-form': {
		$("#users_login").keypress(function (e) { if (e.which == "13") $("#ok").click(); });
		$("#users_pass").keypress(function (e) { if (e.which == "13") $("#ok").click(); });
		}
	    case 'portlets': {
		/* Toggle Single Portlet */
                $('a.toggle').click(function() {
                    $(this).parent('div').next('div').toggle();
                    return false;
                    });
                /* Invert All Portlets */
                $('a#all_invert').click(function() {
                    $('div.portlet_content').toggle();
                    return false;
                    });
                /* Expand All Portlets */
                $('a#all_expand').click(function() {
                    $('div.portlet_content:hidden').show();
                    return false;
                    });
                /* Collapse All Portlets */
                $('a#all_collapse').click(function() {
                    $('div.portlet_content:visible').hide();
                    return false;
                    });
                /* Open All Portlets */
                $('a#all_open').click(function() {
                    $('div.portlet:hidden').show();
                    $('a#all_open:visible').hide();
                    $('a#all_close:hidden').show();
                    return false;
                    });
                /* Close All Portlets */
                $('a#all_close').click(function() {
                    $('div.portlet:visible').hide();
                    $('a#all_close:visible').hide();
                    $('a#all_open:hidden').show();
                    return false;
                    });
                /* Controls Drag + Drop */
                $('table.control_draggebleColumns td').Sortable({
		    accept: 'control_draggebleColumns_portlet',
		    helperclass: 'control_draggebleColumns_placeholder',
		    opacity: 0.8,
		    tolerance: 'intersect'
		    });
		break;
		}
	    }
	
	if (prototype.files != '')
	    {
	    $.each(prototype.files, function(i, id){
		var button = $('#'+id+'_button'), interval;
		new AjaxUpload(button,{
			action: '/system/lib/js/fileupload/upload.php', 
			name:   id,
			onSubmit : function(file, ext){
				$('#'+id+'_loader').show();
				this.disable();
				},
			onComplete: function(file, response){
				data = response.split(':');
				
				var file_path = data[1];
				var file_size = data[2];
				if (file_size < 1024) file_size = file_size + ' б';
				if (file_size > 1023) file_size = Math.round(file_size / 1024) + ' Кб';
				if (file_size > 1024 * 1024 - 1) file_size = Math.round(file_size / 1024 / 1024) + ' Мб';
				
				$('#'+id).val(data[0]);
				$('#'+id+'_main').html('<a href="'+file_path+'" target="_blank">'+file+'</a> ('+file_size+')<br/>');
				$('#'+id+'_main').parent().addClass('blue');
				
				$('#'+id+'_loader').hide();
				this.enable();
				}
			});
		});
	    }
	
	if (prototype.images != '')
	    {
	    $.each(prototype.images, function(i, id){
		var button = $('#'+id+'_button'), interval;
		new AjaxUpload(button,{
			action: '/system/lib/js/fileupload/upload.php', 
			name:   id,
			onSubmit : function(file, ext){
				$('#'+id+'_loader').show();
				this.disable();
				},
			onComplete: function(file, response){
				data = response.split(':');
				
				var file_path = data[1];
				var file_size = data[2];
				if (file_size < 1024) file_size = file_size + ' б';
				if (file_size > 1023) file_size = Math.round(file_size / 1024) + ' Кб';
				if (file_size > 1024 * 1024 - 1) file_size = Math.round(file_size / 1024 / 1024) + ' Мб';
				
				$('#'+id).val(data[0]);
				$('#'+id+'_main').html('<a href="'+file_path+'" target="_blank">'+file+'</a> ('+file_size+')<br/><a href="'+file_path+'" target="_blank"><img src="'+file_path+'?'+data.file_name+'" height="60" alt="'+file+'" /></a><br/>');
				$('#'+id+'_main img').attr('src',file_path+'?'+file);
				$('#'+id+'_main').parent().addClass('blue');
				
				$('#'+id+'_loader').hide();
				this.enable();
				}
			});
		});
	    }
	
	if ($('textarea.wysiwyg').attr('class') == 'wysiwyg' && $('#wysiwyg').val() != 'none') wysiwygs_load();
    }
    
    // вывод списка опций выбранного модуля
    prototype.load_options = function(options){
	var content = '';
	
	content+= '<h1> </h1><div class="inner">';
	
	// элементы первого уровня
	$.each(options,function(){ if(is_object(this)){
	    
	    if (this.type && this.type != ''){
		this.class_name = 'first-level';
		content+= prototype.element(this);
	    } else {
		content+= '\n<a class="first-level"';
		if (this.href) content+= ' href="'+this.href+'"';
		if (this.action) content+= ' onclick="'+this.action+'"';
		content+= '>'+this.title+'</a><br/>';
	    }
	    
	    // элементы второго уровня
	    if (this.childs) {
		$.each(this.childs,function(){
		    if (this.type && this.type != ''){
			this.class_name = 'second-level';
			content+= prototype.element(this);
		    } else {
			content+= '\n<a class="second-level"';
			if (this.href) content+= ' href="'+this.href+'"';
			if (this.action) content+= ' onclick="'+this.action+'"';
			content+= '>'+this.title+'</a>';
		    }
		    
		    // элементы третьего уровня (справа от родителя)
		    if (this.childs_right) {
			var childs_right = new Array();
			$.each(this.childs_right,function(){
			    
			    if (this.type && this.type != '') childs_right.push(prototype.element(this));
			    else
				{
				childs_right.push('<a');
				if (this.href) childs_right.push('href="'+this.href+'"');
				if (this.action) childs_right.push('onclick="'+this.action+'"');
				childs_right.push('>'+this.title+'</a>');
				}
			});
			content+= ' '+childs_right.join(' ');
		    }
		    
		    content+= '<br/>';
		    
		    // элементы третьего уровня (в спойлере, ниже родителя)
		    if (this.childs_spoiler) {
			content+= '\n<div id="'+this.childs_spoiler.id+'" style="display:none">';
			$.each(this.childs_spoiler.items, function(){
			    if (this.type && this.type != ''){
				this.class_name = 'third-level';
				content+= prototype.element(this);
			    } else {
				content+= '\n<a class="third-level"';
				if (this.href) content+= ' href="'+this.href+'"';
				if (this.action) content+= ' onclick="'+this.action+'"';
				content+= '>'+this.title+'</a><br/>';
			    }
			});
			content+= '</div>\n';
		    }
		});
	    }
	}});
	
	content+= '</div>';
	
	$('#left_menu').html(content);
    }
    
    // вывод верхнего меню
    prototype.load_top_menu = function(menu){
	var content = '';
	active_count = 0;
	
	// элементы первого уровня (выводим, если вкладок больше 1)
	content+= '<div class="lev0">';
	$.each(menu,function(){
	    content+= '<div class="l';
	    if (this.active) content+= ' active';
	    content+= '"><div class="r"><div class="f">';
	    content+= '<a id="top_menu_'+this.id+'" onclick="menu_changeTab(\''+this.id+'\');">'+this.title+'</a>';
	    content+= '</div></div>';
	    content+= '<div class="dash"><div class="inner"></div></div>\n';
	    content+= '</div>';
	});
	content+= '</div><br class="clear none" />\n';
	
	// элементы второго уровня
	$.each(menu,function(){ 
	    content+= '\n<div id="menu_tab_'+this.id+'" class="tab';
	    if (this.active) content+= ' active';
	    content+= '">\n';
	    
	    $.each(this.items,function(){
		content+= '<div class="l';
		if (this.active) { content+= ' active'; active_count = 1; }
		content+= '"><div class="r"><div class="f">';
		content+= '<a href="#'+this.href+'" id="top_menu_'+this.id+'">'+this.title+'</a>';
		content+= '</div></div></div>\n';
	    });
	    
	    content+= '</div>\n';
	});
	
	$('#top_menu').html(content);
	
	var lev0Width = 0;
	$.each($('.tab'),function(){
	    var tabWidth = $(this).width();
	    var tabNum = $(this).attr('id').split('_')[2];
	    
	    lev0Width+= $('#top_menu_'+ tabNum).parent().parent().parent().width() + 5;
	    
	    if(lev0Width > tabWidth)
		$(this).css('margin-left',(lev0Width - tabWidth)+'px');
	});
	
	if(active_count == 0) {
	    var j = 1;
	    while (!$('#top_menu_'+j).attr('id')) j++;
	    $('#top_menu_'+j).parent().parent().parent().addClass('active');
	    $('#menu_tab_'+j).addClass('active');
	}
    }
    
    /**
     * вспомогательные функции
     */
    
    // вывод элемента 
    prototype.element = function(el){
	var output = '';
	if (el && el.type && el.type != '')
	    {
	    switch(el.type)
		{
		case 'link':
		case 'link_img_add':
		case 'link_img_edit':
		case 'link_img_save':
		case 'link_img_delete':
		case 'link_img_active':
		case 'link_img_disable':
		case 'link_img_folder':
		case 'link_img_list': { output+= this.link(el); break; }
		case 'textbox': 
		case 'password':{ output+= this.inputtext(el); break; }
		case 'checkbox':
		case 'hidden':
		case 'button': { output+= this.input(el); break; }
		case 'file': { output+= this.file(el); break; }
		case 'image': { output+= this.image(el); break; }
		case 'textarea': { output+= this.textarea(el); break; }
		case 'select': { output+= this.select(el); break; }
		case 'checkbox_list': { output+= this.checkbox_list(el); break; }
		case 'table': { output+= this.table(el); break; }
		case 'html':
		default: { output+= el.text; }
		}
	    }
	else output = el;
	
	return output;
    }
    
    // элемент 'ссылка'
    prototype.link = function(el){
	var output = '<a';
	
	if (el.id && el.id != '')         output+= ' id=\"'+el.id+'\"';
	if (el.style && el.style != '')   output+= ' style=\"'+el.style+'\"';
        if (el.action && el.action != '') output+= ' onclick=\"'+el.action+'\"';
	if (el.href && el.href != '')     output+= ' href=\"'+el.href+'\"';
	if (el.class_name && el.class_name != '')   output+= ' class=\"'+el.class_name+'\"';
	if (el.attr && el.attr != '')     output+= ' '+el.attr;
	
	output+= '>';
	
	switch (el.type) {
	    case 'link_img_add':    { output+= prototype.img_add; break; }
	    case 'link_img_edit':   { output+= prototype.img_edit; break; }
	    case 'link_img_save':   { output+= prototype.img_save; break; }
	    case 'link_img_delete': { output+= prototype.img_delete; break; }
	    case 'link_img_list':   { output+= prototype.img_list; break; }
	    case 'link_img_active': { output+= prototype.img_active; break; }
	    case 'link_img_disable':{ output+= prototype.img_disable; break; }
	    case 'link_img_folder':{ output+= prototype.img_folder; break; }
	    default: output+= el.text;
	    }
	
	output+= '</a>';
	
	return output;
    }
    
    // элемент 'inputtext': текстовое поле, пароль
    prototype.inputtext = function(el){
	var output = '';
	if (el.prefix && el.prefix != '') output+= el.prefix;
	
	if (el.class_name && el.class_name != '') output+= '<div class=\"'+el.class_name+'\">';
	else output+= '<div class=\"textfield\">';
	output+= '<div class="left"><div class="right"><div class="fill">';
	
	output+= '<input type="'+el.type+'"';
	if (el.id && el.id != '')        output+= ' id=\"'+el.id+'\"';
	if (el.style && el.style != '')  output+= ' style=\"'+el.style+'\"';
	if (el.value && el.value != '')  output+= ' value=\"'+el.value+'\"';
	if (el.attributes && el.attributes != '')output+= ' '+el.attributes;
	if (el.action && el.action != '')output+= ' onclick=\"'+el.action+'\"';
	output+= ' />\n';
	
	output+= '</div></div></div></div>';
	
	if (el.sufix && el.sufix != '') output+= el.sufix;
	
	return output;
    }
    
    // элемент 'input': кнопка, флажок, скрытое поле
    prototype.input = function(el){
	var output = '';
	
	if (el.prefix && el.prefix != '')  output+= el.prefix;
	if (el.label && el.label != '')  output+= '<label for=\"'+el.id+'\">';
	
	output+= '<input type="'+el.type+'"';
	
	if (el.id && el.id != '')        output+= ' id=\"'+el.id+'\"';
	if (el.style && el.style != '')  output+= ' style=\"'+el.style+'\"';
	if (el.class_name && el.class_name != '')  output+= ' class=\"'+el.class_name+'\"';
	if (el.value && el.value != '')  output+= ' value=\"'+el.value+'\"';
	if (el.attributes && el.attributes != '')output+= ' '+el.attributes;
	if (el.action && el.action != '')output+= ' onclick=\"'+el.action+'\"';
	if (el.href && el.href != '')    output+= ' onclick=\"location.href=\''+el.href+'\';\"';
	if (el.type == 'checkbox' && el.value == '1') output+= ' checked';
	
	output+= ' />\n';
	
	if (el.label && el.label != '')  output+= el.label+'</label>';
	if (el.sufix && el.sufix != '')  output+= el.sufix;
	
	return output;
    }
    
    // элемент 'файл'
    prototype.file = function(el){
	var output = '';
	
	if (el.prefix && el.prefix != '')  output+= el.prefix;
	
	if (el.value && el.value != '') {
	    output+= '<div class="block_ajaxfileupload blue">';
	    
	    var file = el.value.split(':');
	    var filename = file[0];
	    var filepath = file[1];
	    var filesize = file[2];
	    if (filesize < 1024) filesize = (file[2]) + ' б';
	    if (filesize > 1023) filesize = Math.round(filesize / 1024) + ' Кб';
	    if (filesize > 1024*1024 - 1) filesize = Math.round(filesize / 1024 / 1024) + ' Мб';
	    
	    output+= '<span id="'+el.id+'_main"><a href="'+filepath+'" target="_blank">'+filename+'</a> ('+filesize+')<br/></span>';
	} else {
	    output+= '<div class="block_ajaxfileupload">';
	    output+= '<span id="'+el.id+'_main"></span>';
	}
	
	output+= '<button id="'+el.id+'_button" class="button">Загрузить файл</button>';
	output+= '<span id="'+el.id+'_loader" class="loader">'+this.img_loader+'</span><input type="hidden" id="'+el.id+'" value="" />';
	output+= '</div>';
	
	if (el.sufix && el.sufix != '')  output+= el.sufix;
	
	prototype.files.push(el.id);
	return output;
    }
    
    // элемент 'изображение'
    prototype.image = function(el){
	var output = '';
	
	if (el.prefix && el.prefix != '')  output+= el.prefix;
	
	if (el.value && el.value != '') {
	    output+= '<div class="block_ajaxfileupload blue">';
	    
	    var file = el.value.split(':');
	    var filename = file[0];
	    var filepath = file[1];
	    var filesize = file[2];
	    if (filesize < 1024) filesize = (file[2]) + ' б';
	    if (filesize > 1023) filesize = Math.round(filesize / 1024) + ' Кб';
	    if (filesize > 1024*1024 - 1) filesize = Math.round(filesize / 1024 / 1024) + ' Мб';
	    
	    output+= '<span id="'+el.id+'_main"><a href="'+filepath+'" target="_blank">'+filename+'</a> ('+filesize+')<br/><a href="'+filepath+'" target="_blank"><img src="'+filepath+'" height="60" alt="" /></a><br/></span>';
	} else {
	    output+= '<div class="block_ajaxfileupload">';
	    output+= '<span id="'+el.id+'_main"></span>';
	}
	
	output+= '<button id="'+el.id+'_button" class="button">Загрузить файл</button>';
	output+= '<span id="'+el.id+'_loader" class="loader">'+this.img_loader+'</span><input type="hidden" id="'+el.id+'" value="" />';
	output+= '</div>';
	
	if (el.sufix && el.sufix != '')  output+= el.sufix;
	
	prototype.images.push(el.id);
	return output;
    }
    
    // элемент 'выпадающий список'
    prototype.select = function(el){
	var output = '<select id="'+el.id+'"';
	
	if (el.style && el.style != '')  output+= ' style=\"'+el.style+'\"';
	if (el.class_name && el.class_name != '')  output+= ' class=\"'+el.class_name+'\"';
	if (el.attributes && el.attributes != '') output+= ' '+el.attributes;
	if (el.action && el.action != '')output+= ' onclick=\"'+el.action+'\"';
	
	output+= '>\n';
	
	if (is_string(el.options)) {
	    var options = el.options.split(',');
	    $.each(options, function(i,option){
		option = option.split('=');
		output+= '<option value="'+option[0]+'"';
		if (el.value == option[0]) output+= ' selected="selected"';
		output+= '>'+option[1]+'</option>';
		});
	    }
	    
        if (is_object(el.options)) {
	    $.each(el.options, function(i,option){
		output+= '<option value="'+i+'"';
		if (el.value == i) output+= ' selected="selected"';
		output+= '>'+option+'</option>';
		});
	    }
	    
        output+= '</select>\n';
	
	return output;
    }
    
    // элемент 'группа флажков'
    prototype.checkbox_list = function(el){
	var output = '<div class="control_checkbox_list" id="'+el.id+'" name="'+el.id+'">\n';
	
        if (el && el.items && el.items != '') $.each(el.items, function(i,el){
	    if (el.prefix && el.prefix != '')output+= el.prefix;
	    if (el.label && el.label != '')  output+= '<label for=\"'+el.id+'\">';
	    output+= '<input type="checkbox"';
	    if (el.id && el.id != '')        output+= ' id=\"'+el.id+'\"';
	    if (el.style && el.style != '')  output+= ' style=\"'+el.style+'\"';
	    if (el.class_name && el.class_name != '')  output+= ' class=\"'+el.class_name+'\"';
	    if (el.value && el.value != '')  output+= ' value=\"'+el.value+'\"';
	    if (el.attributes && el.attributes != '')output+= ' '+el.attributes;
	    output+= ' />';
	    if (el.label && el.label != '')  output+= el.label+'</label>';
	    if (el.sufix && el.sufix != '')  output+= el.sufix;
	    output+= '<br/>\n';
	    });
       
        output+= '</div>\n';
        
	return output;
    }
    
    // элемент 'многострочное поле для ввода'
    prototype.textarea = function(el){
	var output = '<textarea'; 
        
	if (el.id && el.id != '')        output+= ' id=\"'+el.id+'\" name=\"'+el.id+'\"';
	if (el.style && el.style != '')  output+= ' style=\"'+el.style+'\"';
	if (el.class_name && el.class_name != '')  output+= ' class=\"'+el.class_name+'\"';
	if (el.attributes && el.attributes != '')    output+= ' '+el.attributes;
	if (el.action && el.action != '')output+= ' onclick=\"'+el.action+'\"';
	
	output+= '>';
	if (el.value && el.value != '')    output+= el.value;
	output+= '</textarea>\n';
	
	return output;
    }
    
    // элемент 'таблица'
    prototype.table = function(table){
	var output = '';
	if (table.class_name == '') table.class_name = 'table_form';
	
        output = '<table cellspacing="0" cellpadding="0" ' + table.class_name + '' + table.style + '>\n';
        
        num_cols = table.rows.lenght;
        
        if (table.title != '') output+= '<tr class="title"><td colspan="' + num_cols + '">' + table.title + '</td></tr>';
        
        $.each(table.rows, function(i,row){
	    output+= '<tr class="'
            if (i == 0)
                {
                if (table.head) output+= 'head';
		else output+= 'odd';
                }
            else
                {
                if (i % 2 == 0) output+= 'even';
		else output+= 'odd';
                }
            if (row.class_name != '') output+= ' '+row.class_name;
            output+= '"';
            
            if (row.style != '') output+= ' style="' + row.style + '"';
            output+= '>';
	    
	    
            $.each(row, function(key,cell){
		if (i>0 || !table.head)
		    {
		    if (is_array(cell)) output+= '<td ' + cell[0] + '>' + cell[1] + '</td>';
		    else output+= '<td>' + cell + '</td>';
		    }
		else
		    {
		    if (is_array(cell)) output+= '<th ' + cell[0] + '>' + cell[1] + '</th>';
		    else output+='<th>' + cell + '</th>';
		    }
                });
	    
            output+= '</tr><tr class="sep"><td></td></tr>\n';
            });
        output+= '</table>\n';
	
	return output;
    }
    
    // элемент 'многострочное поле для ввода'
    prototype.treeBranches = function(branches){
	var output = '';
	if (is_array(branches))
	    {
	    output+= '<ul>\n';
	    $.each(branches, function(i, branch) {
		output+= '<li id="branch_'+branch['id']+'"><span>'+branch['title']+'</span>';
		if (is_array(branch['childs'])) output+= prototype.treeBranches(branch['childs']);
		output+= '</li>\n';
		});
	    output+= '</ul>\n';
	    }
	return output;
    }
    
    /**
     * шаблоны
     */
    
    prototype.template = function(data){
	var content = '';
	
	switch(data.template)
	    {
	    case 'form': {
		content+= '<table cellspacing="0" cellpadding="0" class="table_form">\n';
		
		// если у таблицы есть шапка, добавляем строку th
		if (data.head && data.head != '')
		    {
		    content+= '<tr>';
		    $.each(data.head, function(i, el){ content+= '<th>'+el+'</th>'; });
		    content+= '</tr>';
		    }
		    
		$.each(data, function(i,obj) { if (obj && obj.type && obj.type != '') {
		    content+= '<tr class="';
		    if (i % 2 == 0) content+= 'even';
		    else content+= 'odd';
		    content+= '">';
		    
		    if (obj.caption) content+= '<td><span class="caption">'+obj.caption+'</span></td><td>';
		    else content+= '<td colspan="2">';
		    content+= prototype.element(obj);
		    
		    content+= '</td></tr><tr class="sep"><td></td></tr>\n';
		    }});
		content+= '</table>\n';
		
		break;
		}
	    case 'list': {
		if (data.pager && data.pager != '') {
		    var pager = '<center>';
		    for(i=1;i<=data.pager.pages;i++){
			if (i != data.pager.active) pager+= ' <a onclick="window.scrollTo(0,0)" href="#'+data.pager.page+'/'+i+'">'+i+'</a> ';
			else pager+= ' <b>'+i+'</b> ';
		    }
		    pager+= '</center>';
		    
		    content+= pager;
		}
		
		content+= '<table cellspacing="0" cellpadding="0" class="table_list">\n';
		
		// если у таблицы есть шапка, добавляем строку th
		if (data.head && data.head != '')
		    {
		    content+= '<tr>';
		    $.each(data.head,function(i, col){
			content+= '<th';
			if (col.width && col.width != '') content+= ' width="'+col.width+'"';
			if (col.align && col.align != '') content+= ' align="'+col.align+'"';
			content+= '>';
			if (col.text && col.text != '') content+= prototype.element(col.text);
			content+= '</th>';
			});
		    content+= '</tr>';
		    }
		    
		$.each(data.rows, function(i,row) {
		    
		    if (row.cells) cells = row.cells;
		    else cells = row;
		    
		    content+= '<tr class="';
		    
		    if (row.class_name && row.class_name != '') content+= row.class_name;
		    else
			{
			if (i % 2 == 0) content+= 'even';
			else content+= 'odd';
			}
		    
		    content+= '">';
		    
		    $.each(cells, function(i,cell) {
			if (cell) {
			    content+= '<td';
			    
			    if (cell.cell_style && cell.cell_style != '') content+= ' style="'+cell.cell_style+'"';
			    
			    content+= '>'+prototype.element(cell)+'</td>';
			    }
			else content+= '<td></td>';
		    });
		    
		    content+= '</tr><tr class="sep"></tr>\n';
		    });
		content+= '</table>\n';
		
		if (pager && pager != '')  content+= pager;
		
		break;
		}
	    case 'tabs': {
		content+= '<div class="control_tabs">\n';
		
		$.each(data.tabs, function(i, tab) { if (tab && tab.title && tab.title != '') {
		    content+= '<a id="users_tab_'+i+'" class="';
		    
		    if (i==0) content+= 'active';
		    
		    content+= '" onclick="users_change_tab(\''+i+'\')">'+tab.title+'</a>';
		    }});
		
		$.each(data.tabs, function(i, tab) { if (tab && tab.template && tab.template != '') {
		    content+= '<div style="display:';
		    
		    if (i==0) content+= 'block';
		    else content+= 'none';
		    
		    content+= '" class="control_tab_inner" id="users_tab_inner_'+i+'">';
		    content+= prototype.template(tab);
		    content+= '</div>';
		    }});
		
		content+= '</div>\n';
		
		break;
		}
	    case 'tree': {
		content+= '<ul class="control_draggebleTree"><li class="root" id="branch_0"><span>root</span>\n';
		content+= prototype.treeBranches(data.tree.branches);
		content+= '</li></ul>\n';
		break;
		}
	    case 'auth-form': {
		$.each(data, function(i,obj) { if (obj && obj.type && obj.type != '') {
		    if (obj.caption) content+= obj.caption+'<br/>\n';
		    content+= prototype.element(obj)+'<br/>\n';
		    }});
		break;
		}
	    case 'portlets': {
		content+= '<table cellspacing="0" class="control_draggebleColumns" id="columns"><tr>';
		
		$.each(data.portlets.columns_title, function(i,title) { content+= '<th>'+title+'</th>'; });
		
		content+= '</tr><tr>';
		
		$.each(data.portlets.columns, function(key, column) { 
		    content+= '<td id="column_'+key+'">';
		    
		    $.each(column, function(i, element) { 
			content+= '<div class="control_draggebleColumns_portlet" id="'+element[2]+'">';
			
			// заголовок
			content+= '<div class="topper"><a href="#" class="toggle">'+element[0]+'</a></div>';
			
			// тело
			content+= '<div class="content" style="display: none;"><p>';
			if (is_array(element[1])) $.each(element[1], function(i, el) { content+= prototype.element(el); });
			else content+= prototype.element(element[1]);
			content+= '</p></div>';
			
			// подвал
			/*content+= <div class="bottom"> bottom </div>*/
			
			content+= '</div>';
			});
		    content+= '</td>';
		    });
		
		content+= '</tr></table>';
		
		break;
		}
	    case 'dragabbleTable': {
		content+= '<table class="control_draggebleTable" id="table-1">';
        
		var first = true;
		$.each(data.portlets.columns, function(i, column) {
		    content+= '<tr';
		    if (column.id && column.id != '') content+= ' id="'+column.id+'"';
		    
		    content+= ' class="';
		    if (first) content+= 'nodrop nodrag';
		    if (column.class_name && column.class_name != '') content+= ' '+column.class_name;
		    content+= '">';
		    
		    if (!first) content+= '<td class="handle" width="20"></td>';
		    else content+='<td></td>';
	    
		    
		    $.each(column, function(key, elem) {
			if (key != 'class' && key != 'style' && key != 'id')
			    {
			    if (!first || !params.th)
				{
				if (is_array(elem)) content+= '<td '+elem[0]+'>'+elem[1]+'</td>';
				else content+='<td>'+elem+'</td>';
				}
			    else
				{
				if (is_array(elem)) content+= '<th '+elem[0]+'>'+elem[1]+'</th>';
				else content+='<th>'+elem+'</th>';
				}
			    }
			});
		    if (first) first = false;
		    content+= '</tr>';
		    });
		    
		    content+= '</table>';
		    
                    $('table.control_draggebleTable').tableDnD({
                        dragHandle: 'handle',
                        onDrop: function(table, row)
                            {
                            var rows = table.tBodies[0].rows;
                            var new_order = '';
                            for (var i=0; i<rows.length; i++)
                                {
                                new_order += rows[i].id+'_';
                                }
                            control_menu_reorder(row.id,new_order);
                            }
                    });
		break;
		}
	    default: {
		$.each(data,function(i, el){
		    if (el.type && el.type != '') content+= prototype.element(el);
		    });
		}
	    }
	return content;
    }
}