acJson = new jsonTemplate();

$(window).bind('hashchange', function(e) {
    var hash = location.hash;
    
    if (hash.indexOf('#') != -1) {
	var path = hash.split('#')[1];
	
	if (path.indexOf('/') != -1) {
	    path = path.split('/');
	    var module = path[0];
	    var page   = path[1];
	    var data   = path;
	    
	    if (path.length > 2)
		data.splice(0, 2).join('/');
	    else
		data = '';
	    
	    $('#current_page_data').val(data);
	    
	    load_module_page(page, data, module);
	    load_options(module, page);
	    
	    // если существует пункт меню для данной страницы, делаем его активным
	    if ($('#top_menu_'+ module +'_'+ page).attr('id'))
	    {
		if ($('.tab .l.active').find('a').attr('id') != 'top_menu_'+ module +'_'+ page)
		{
		    $('.top_menu .tab.active').removeClass('active');
		    $('.top_menu .tab .active').removeClass('active');
		    $('.top_menu .lev0 .active').removeClass('active');
		    var active_tab = $('#top_menu_'+ module +'_'+ page).parent().parent().parent().parent().attr('id').split('_')[2];
		    $('#top_menu_'+ active_tab).parent().parent().parent().addClass('active');
		    $('#top_menu_'+ module +'_'+ page).parent().parent().parent().addClass('active').parent().addClass('active');
		}
	    }
	    // иначе ищем родительский пункт меню, для данной страницы
	    else
	    { 
		$.ajax({
		    type:     'post',
		    dataType: 'json',
		    url:      '/menu/pageInfo',
		    data:     'module='+ module +'&page='+ page +'&theme_control=true',
		    success:  function (json) {
			if (json && json.main_page && json.main_page != '' && $('.tab .l.active').find('a').attr('id') != 'top_menu_'+ module +'_'+ json.main_page)
			{
			    if($('#top_menu_'+ module +'_'+ json.main_page).attr('id'))
			    {
				$('.top_menu .tab.active').removeClass('active');
				$('.top_menu .tab .active').removeClass('active');
				$('.top_menu .lev0 .active').removeClass('active');
				var active_tab = $('#top_menu_'+ module +'_'+ json.main_page).parent().parent().parent().parent().attr('id').split('_')[2];
				$('#top_menu_'+ active_tab).parent().parent().parent().addClass('active');
				$('#top_menu_'+ module +'_'+ json.main_page).parent().parent().parent().addClass('active').parent().addClass('active');
			    }
			}
		    }
		});
	    }
	}
    }
});

$.ajax({
    type:     'post',
    dataType: 'json',
    url:      '/users/auth',
    data:     'theme_control=true',
    success:  function (json) {
	if (json && json.auth && json.auth == true)
	{
	    menu_display(); // загрузка верхнего меню
	    $(window).trigger('hashchange');
	}
	else
	{
	    users_go_login(); // переход на страницу авторизации
	}
    }
});
    
$(document).ready(function(){
    Cufon.replace(['.logo h1']);
    
    $('#loading').ajaxStart(function(){
	$.blockUI({ message: '<br/><br/>Идет загрузка...<br/><br/><br/>' });
    });         
    
    $('#loading').ajaxStop(function(){
	$.unblockUI();
    });
});

// загрузка страницы модуля
function load_module_page(page, data, module) {
    if (module == '' || !module) module = $('#current_module').val();
    else $('#current_module').val(module);
    
    if (page == '' || !page) page = $('#current_page').val();
    else $('#current_page').val(page);
    
    if (data == '' || !data) data = $('#current_page_data').val();
    else $('#current_page_data').val(data);
    
    var jsonResponse = false;
    
    $.ajax({
	type:     'post',
	dataType: 'json',
	url:      '/'+ module +'/'+ page +'/'+ data,
	data:     'theme_control=true',
	success:  function (json) {
	    if (json)
	    {
		jsonResponse = true;
		
		if (json.error && json.error != '')
		{
		    switch(json.error)
		    {
			case 'auth_error':
			{
			    //users_go_login();
			    break;
			}
			case 'access_denied':
			{
			    alert('У вас нет доступа к этому разделу');
			    break;
			}
			default:
			{
			    $('#main_body').html('<div class="form"><h1>Ошибка</h1><div class="inner" style="color:red"><br/>'+ json.error +'</div></div>');
			}
		    }
		}
		else
		{
		    acJson.load_page(json);
		}
	    }
	    else
	    {
		// No Response
		$('#main_body').html('Сервер ничего не ответил на этот запрос');
	    }
	},
	complete: function (data) {
	    if(!jsonResponse)
		$('#main_body').html(data['responseText']);
	}
    });
}

// загрузка списка опций выбранного раздела
function load_options(module, page) {
    if (module == '' || !module) module = $('#current_module').val();
    else $('#current_module').val(module);
    
    if (!page) page = '';
    
    $('#main_body').css('width', '75%');
    
    $.ajax({
	type:     'post',
	dataType: 'json',
	url:      '/'+ module +'/options/'+ page,
	data:     'module='+ module +'&page='+ page + '&theme_control=true',
	success:  function (options) {
	    if (!options.error || options.error == '') acJson.load_options(options);
	}
    });
}

// Сохранение изменений и добавление новой информации
function save_item(data_name, params, id, exit, exit_data) {
    module    = $('#current_module').val();
    param     = params.split(acSep);
    values    = new Array();                 // создаем массив со значениями этих параметров
    
    var el_id = '';
    
    /* проходим по всем элементам формы */
    for (i=0; i<param.length; i++)
    {
	el_id = module +'_'+ param[i];
	
	/* не флажок */
	if ($('#'+ el_id).attr('type') != 'checkbox')
	{
	    /* группа флажков => ищем в группе отмеченные флажки и записываем их значения в строку через запятую => строку передаем в бд */
	    if ($('#'+ el_id).attr('class') == 'control_checkbox_list')
	    {
		value_mas = Array();
		
		$('#'+ el_id +' input:checked').each(function(){
		    value_mas.push(this.value);
		});
		
		var new_value = value_mas.join(',');
	    }
	    else
	    {
		if ($('#'+ el_id).attr('class') != 'wysiwyg' || $('#wysiwyg').val() == 'none')
		    var new_value = encodeURIComponent($('#'+ el_id).val());
		else
		    var new_value = encodeURIComponent(wysiwygs_save(el_id));
	    }
	}
	/* одиночный флажок - сохраняем значение 0 или 1 */
	else
	    var new_value = ($('#'+ el_id +':checked').attr('id')) ? 1 : 0;
	
	values.push(new_value);
    }
    
    // Формирование адреса запроса
    var url = '/'+ module +'/save/'+ data_name;
    if(id && id > 0)
	url+= '/'+ id;
    
    // Запись значений в строку для передачи серверу
    values = values.join(acSep);
    $.ajax({type: 'post',
	url:  url,
	data: 'fields='+ params +'&values='+ values +'&theme_control=true',
	complete: function (data) {
	    if (exit && exit != '')
	    {
		if (exit_data && exit_data != '' && exit_data != '1')
		    location.href = '#'+ module +'/'+ exit +'/'+ exit_data;
		else
		    location.href = '#'+ module +'/'+ exit;
	    }
	    else
	    {
		if (data['responseText'] == '')
		    load_options();
		else
		    alert(data['responseText']);
	    }
	}
    });
}

// удаление выбранного элемента
function delete_item(data_name, id, options, exit_page) {
    if (options == '' || !options) options = '1';
    
    module    = $('#current_module').val();
    page      = $('#current_page').val();
    page_data = $('#current_page_data').val();
    
    if (page_data == 1) page_data = '';
    else page_data = '/'+ page_data;
    
    $.ajax({
        type: 'post',
        url:  '/'+ module +'/delete/'+ data_name +'/'+ id,
	data: 'theme_control=true',
        complete: function (data) {
	    if (data['responseText'] == '')
	    {
		if (exit_page == '' || !exit_page) load_module_page();
		else location.href = '#'+ module +'/'+ exit_page;
		
		if (options == '1') load_options();
	    }
	    else
		alert(data['responseText']);
	}
    });
}

function delete_check_item(data_name){
    switch($('#select_list_options').val())
    {
        case 'delete_all':
        {
            for(i=0;i<$('input.check_item').length;i++)
                delete_item(data_name, $('input.check_item')[i].id);
            
            break;
        }
        case 'delete_checked':
        {
            for(i=0;i<$('input.check_item').length;i++)
                if ($('input.check_item')[i].checked)
		    delete_item(data_name, $('input.check_item')[i].id);
            
            break;
        }
    }
}

// активация выбранного элемента
function activate_item(data_name, id, options, exit_page) {
    if (options == '' || !options) options = '1';
    
    module    = $('#current_module').val();
    page      = $('#current_page').val();
    page_data = $('#current_page_data').val();
    
    if (page_data == 1) page_data = '';
    else page_data = '/'+ page_data;
    
    $.ajax({
        type: 'post',
        url:  '/'+ module +'/activate/'+ data_name +'/'+ id,
	data: 'theme_control=true',
        complete: function (data) {
	    if (data['responseText'] == '')
	    {
		if (exit_page == '' || !exit_page) load_module_page();
		else location.href = '#'+ module +'/'+ exit_page;
		
		if (options == '1') load_options();
	    }
	    else
		alert(data['responseText']);
	}
    });
}

// деактивация выбранного элемента
function disable_item(data_name, id, options, exit_page) {
    if (options == '' || !options) options = '1';
    
    module    = $('#current_module').val();
    page      = $('#current_page').val();
    page_data = $('#current_page_data').val();
    
    if (page_data == 1) page_data = '';
    else page_data = '/'+ page_data;
    
    $.ajax({
        type: 'post',
        url:  '/'+ module +'/disable/'+ data_name +'/'+ id,
	data: 'theme_control=true',
        complete: function (data) {
	    if (data['responseText'] == '')
	    {
		if (exit_page == '' || !exit_page)
		    load_module_page();
		else
		    location.href = '#'+ module +'/'+ exit_page;
		
		if (options == '1')
		    load_options();
	    }
	    else
		alert(data['responseText']);
	}
    });
}

// включение/выключение чекбоксов в списке элементов выбранного модуля(content, css, templates)
function all_check() {                      
    che = document.getElementById('all_check').checked;
    
    for(i=0; i<$('input.check_item').length; i++)
	$('input.check_item')[i].checked = che;
};
    
// переключение блоков в модуле "content"
function view_block(id, count) {
    $('a.select_block').css({color:'#1E90FF'});
    $('#select_block_'+ id).css({color:'black'});
    
    for(i=1; i<=count; i++)
	$('#cont_block_'+i).hide();
    
    $('#cont_block_'+ id).show();
}

// переключение между элементами главного меню
function view_top_menu_item(id) {        
    $('a.top_menu').css({color:'#1E90FF'});
    $('#page_'+ id).css({color:'black'});
}
    
shortcut.add('Ctrl+S', function() {
    $('#save').click().ajaxStart(function(){
	$.blockUI({ message: '<br/><br/>Идет загрузка...<br/><br/><br/>' });
    });
    
    return false;
});

function selChange(seln, param) {
    selNum = seln.choose_on_form.selectedIndex; 
    
    Isel = seln.choose_on_form.options[selNum].value; 
    
    document.getElementById(param).value = Isel;
}

function MsgOkCancel(msg) {
    var fRet;
    
    fRet = confirm(msg);
    
    return fRet;
}
    
/*** functions ***/

/* function htmlspecialchars(str) { return $('<span>').text(str).html(); }*/

function str_replace(subject, search, replace) {
    var result = '';
    
    if (subject.length > 1)
	result = subject.split(search).join(replace);
    else
    {
	if (subject == search)
	    result = replace;
	else
	    result = subject;
    }
    
    return result;
}

function is_array(v) {
    return Object.prototype.toString.apply(v) === '[object Array]';
}

function is_object(v) {
    return Object.prototype.toString.apply(v) === '[object Object]';
}

function is_string(v) {
    return Object.prototype.toString.apply(v) === '[object String]';
}

function acHtmlValue(id,type) {
    if (type == '' || !type)
    {
	if ($('#'+ id).attr('type'))
	    type = $('#'+ id).attr('type');
	else
	    type = $('#'+ id).attr('tag');
    }
    
    switch (type)
    {
	case 'checkbox':
	    var value = ($('#'+ id).attr('checked')) ? 1 : 0;
	    break;
	case 'radio':
	    var value = $(':radio[name='+ id +']').filter(':checked').val();
	    break;
	default:
	    var value = encodeURIComponent($('#'+ id).val());
    }
    
    return value;
}

function acToggle(id) {
    $('#'+ id).toggle();
}

function acSlideToggle(id,speed) {
    if (speed == '' || !speed)
	speed = 'fast';
    
    $('#'+ id).slideToggle(speed);
}