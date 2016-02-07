/**
 * Module:  Users
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

/* Авторизация пользователя */
function users_authorize()            
{
    var login = $('#users_login').val();
    var hash  = $.md5($('#users_pass').val());
    
    $.ajax({
        type:     'post',
	dataType: 'json',
        url:      '/Users/auth',
        data:     'user_login='+ login +'&user_hash='+ hash + '&theme_control=true',
        success:  function (json) {
	    if (json && json.auth && json.auth == true)
		window.location.reload(true);
	    else
		users_go_login();
	}
    });
}

/* Перенаправление на страницу авторизации */
function users_go_login()
{
    var login = $('#uname').val();
    
    $('#top_menu').html('');
    $('#user_buttons').html('');
    $('#main_body').css('width', '95%');
    
    load_module_page('login',login,'users');
}

/* Выход из системы */
function users_logout()
{
    $.ajax({
        type: 'post',
        url:  '/users/logout',
        complete: function (data) {
            location.href = '/control';
	}
    });
}

function users_change_tab(id)
{
    if ($("#users_tab_inner_"+ id).css('display') == 'none')
    {
        $("div.control_tab_inner").hide();
	$(".control_tabs a.active").removeClass();
	
        $("#users_tab_inner_"+ id).show();
	$("#users_tab_"+ id).addClass('active');
    }
}

function users_change_wysiwyg()
{
    var wysiwyg = $('#users_wysiwyg').val();
    var wysiwyg_skins = $('#users_'+ wysiwyg +'_skins').val().split(',');
    var wysiwyg_themes = $('#users_'+ wysiwyg +'_themes').val().split(',');
    
    $('#users_wysiwyg_skin').find('option').remove().end();
    jQuery.each(wysiwyg_skins, function(){
	var option = this.split('=');
	$('#users_wysiwyg_skin').append('<option value="'+ option[0] +'">'+ option[1] +'</option>');
    });
    $('#users_wysiwyg_skin').val( $('#users_'+ wysiwyg +'_default_skin').val() );
    
    $('#users_wysiwyg_theme').find('option').remove().end();
    jQuery.each(wysiwyg_themes, function(){
	var option = this.split('=');
	$('#users_wysiwyg_theme').append('<option value="'+ option[0] +'">'+ option[1] +'</option>');
    });
    $('#users_wysiwyg_theme').val('normal');
}

function users_profile_edit(params, exit, exit_data)
{
    param  = params.split(acSep);
    values = new Array($('#Users_'+ param[0]).val());                   	// создаем массив со значениями этих параметров
    
    /* проходим по всем элементам формы */
    for (i=1; i<param.length; i++)
    {
	/* не флажок */
	if ($('#Users_'+ param[i]).attr('type') != 'checkbox')
	{
	    /* группа флажков => ищем в группе отмеченные флажки и записываем их значения в строку через запятую => строку передаем в бд */
	    if ($('#Users_'+ param[i]).attr('class') == 'control_checkbox_list')
	    {
		value_mas = Array();
		$('#Users_'+ param[i] +' ul li input:checked').each(function(){ value_mas.push(this.value); });
		var new_value = value_mas.join(',');
	    }
	    else
	    {
		if ($('#users_'+ param[i]).attr('class') != 'wysiwyg' || $('#wysiwyg').val() == 'none')
		    var new_value = $('#Users_'+ param[i]).val();
		else
		    var new_value = wysiwygs_save('Users_'+ param[i]);
	    }
	}
	/* одиночный флажок - сохраняем значение 0 или 1 */
	else var new_value = ($('#Users_'+ param[i]).attr('checked')) ? 1 : 0;
	
	new_value = str_replace(new_value, '&', acCon);
	values.push(new_value);
    }
    
    values = values.join(acSep);                                            	// записываем значения в строку для передачи серверу
    $.ajax({type: 'post',
	    url:  '/Users/editProfile',
	    data: 'fields='+ params +'&values='+ values +'&theme_control',
	    complete: function (data) {
		if (data['responseText'] != '')
		    alert(data['responseText']);
		
		if (exit && exit != "")
		    location.href = '#Users/'+ exit +'/'+ exit_data;
	    }
    });
}

function users_group_edit(id, fields, exit)
{
    field  = fields.split(acSep);
    values = new Array();                   					// создаем массив со значениями этих параметров
    
    /* проходим по всем элементам формы */
    for (i=0; i<field.length; i++)
	values.push(acHtmlValue('users_'+ field[i]));
    
    permissions = Array();
    $('.Users_permissions').filter(':checked').each(function(){
	permissions.push($(this).attr('id'));
    });
    permissions = permissions.join(',');
    
    values = values.join(acSep);                                            	// записываем значения в строку для передачи серверу
    $.ajax({type: 'post',
	    url:  '/users/save/UserGroup/'+ id,
	    data: 'theme_control=true&fields='+ fields +'&values='+ values +'&permissions='+ permissions,
	    complete: function (data) {
		if (data['responseText'] != '')
		    alert(data['responseText']);
		if (exit && exit != "")
		    location.href = '#users/'+ exit;
	    }
    });
}