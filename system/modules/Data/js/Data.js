/**
 * Module:  Data
 * File:    data.js
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

function data_type_add(fields, exit)
{
    field  = fields.split(acSep);                                               // разделяем строку с параметрами (поля в БД) на отдельные элементы и записываем их в массив
    values = new Array();                             				// создаем массив со значениями этих параметров
    
    for (i=0; i<field.length; i++)
        values.push(acHtmlValue('Data_'+ field[i]));
    
    values = values.join(acSep);                                                // записываем значения в строку для передачи серверу
    
    $.ajax({
        type: 'post',
        url:  '/Data/save/DataType',
        data: 'theme_control=true&fields='+ fields +'&values='+ values,
        complete: function (data) {
	    if (data['responseText'] == '')
	    {
		load_options();
		if (exit && exit != '') location.href = '#Data/'+ exit;
	    }
	    else
		alert(data['responseText']);
        }
    });
}

function data_type_edit(id, fields, exit)
{
    field  = fields.split(acSep);                                               // разделяем строку с параметрами (поля в БД) на отдельные элементы и записываем их в массив
    values = new Array();                             				// создаем массив со значениями этих параметров
    
    for (i=0; i<field.length; i++)
	values.push(acHtmlValue('Data_'+ field[i]));
    
    values = values.join(acSep);                                                // записываем значения в строку для передачи серверу
    
    var create_new_table = $("#Data_radio_create_new_table").attr('checked');
    
    $.ajax({
        type: 'post',
        url:  '/Data/save/DataType/'+ id,
        data: 'theme_control=true&fields='+ fields +'&values='+ values +'&create_new_table='+ create_new_table,
        complete: function (data) {
            if (data['responseText'] == '') load_options();
	    else alert(data['responseText']);
	    
            if (exit && exit != '') location.href = '#data/'+ exit;
        }
    });
}

function data_type_delete(id)
{
    var delete_table = ($('#Data_delete_table_'+ id).attr('checked')) ? 1 : 0;
    
    $.ajax({
        type: 'post',
        url:  '/Data/delete/DataType/'+ id,
        data: 'theme_control=true&delete_table='+ delete_table,
        complete: function (data) {
	    if (data['responseText'] == '')
	    {
		load_module_page();
		load_options();
	    }
	    else
		alert(data['responseText']);
	}
    });
}

function data_field_add(data_name)
{
    var field  = new Array('name', 'title', 'type', 'data_name', 'active', 'module');                                              
    var values = new Array();                         					// создаем массив со значениями этих параметров
    
    for (i=0; i<field.length; i++)
	values.push(acHtmlValue('Data_'+ field[i]));
    
    values = values.join(acSep);                                              	// записываем значения в строку для передачи серверу
    fields = field.join(acSep);
    
    $.ajax({
        type: 'post',
        url:  '/Data/save/DataField',
        data: 'theme_control=true&fields='+ fields +'&values='+ values,
        complete: function (data) {
	    if (data['responseText'] == '') load_module_page();
	    else alert(data['responseText']);
	}
    });
}

function data_field_edit(data_name, id, fields, exit_page)
{
    field  = fields.split(acSep);						// создаем массив с полями
    values = new Array();            						// создаем массив со значениями
    
    for (i=0; i<field.length; i++)
        values.push(acHtmlValue(data_name +'_'+ field[i] +'_'+ id));
    
    fields = field.join(acSep);                                                // записываем поля в строку для передачи серверу
    values = values.join(acSep);                                                // записываем значения в строку для передачи серверу
    
    $.ajax({
        type: 'post',
        url:  '/Data/save/DataField/'+ id,
        data: 'theme_control=true&fields='+ fields +'&values='+ values,
        complete: function (data) {
	    if (data['responseText'] == '') load_options();
	    else alert(data['responseText']);
	    
	    if (exit_page)
		location.href = '#data/'+ exit_page;
	}
    });
}

function data_field_delete(id)            
{
    $.ajax({
        type: 'post',
        url:  '/Data/delete/DataField/' + id,
	data: 'theme_control=true',
        complete: function (data) {
	    if (data['responseText'] == '') load_module_page();
	    else alert(data['responseText']);
	}
    });
}

function data_field_delete_check_item()
{
    switch($('#select_list_options').val())
    {
        case 'Удалить все':
        {
            for(i=0; i<$('input.check_item').length; i++)
                delete_field($('input.check_item')[i].id);
	    
            break;
        }
        case 'Удалить выбранные':
        {
            for(i=0; i<$('input.check_item').length; i++)
                if ($('input.check_item')[i].checked)
		    delete_field($('input.check_item')[i].id);
	    
            break;
        }
    }
}

function data_template_add(fields, exit)
{
    field  = fields.split(acSep);                                               // разделяем строку с параметрами (поля в БД) на отдельные элементы и записываем их в массив
    values = new Array();                             				// создаем массив со значениями этих параметров
    
    for (i=0;i<field.length;i++)
	values.push(acHtmlValue('Data_'+ field[i]));
    
    values = values.join(acSep);                                                // записываем значения в строку для передачи серверу
    
    $.ajax({
        type: 'post',
        url:  '/Data/save/DataTemplate',
        data: 'theme_control=true&fields='+ fields +'&values='+ values,
        complete: function (data) {
	    if (data['responseText'] == '')
	    {
		load_options();
		if (exit && exit != '') location.href = '#data/'+ exit;
	    }
	    else
		alert(data['responseText']);
        }
    });
}

function data_template_edit(id, fields, exit)
{
    field  = fields.split(acSep);                                               // разделяем строку с параметрами (поля в БД) на отдельные элементы и записываем их в массив
    values = new Array();                             				// создаем массив со значениями этих параметров
    
    for (i=0; i<field.length; i++)
	values.push(acHtmlValue('Data_'+ field[i]));
    
    values = values.join(acSep);                                                // записываем значения в строку для передачи серверу
    
    $.ajax({
        type: 'post',
        url:  '/Data/save/DataTemplate/'+ id,
        data: 'theme_control=true&fields='+ fields +'&values='+ values,
        complete: function (data) {
            if (data['responseText'] == '') load_options();
	    else alert(data['responseText']);
	    
            if (exit && exit != '') location.href = '#data/'+ exit;
        }
    });
}

function data_template_delete(id)
{
    var delete_table = ($('#Data_delete_table_'+ id).attr('checked')) ? 1 : 0;
    
    $.ajax({
        type: 'post',
        url:  '/Data/delete/DataTemplate/'+ id,
	data: 'theme_control=true',
        complete: function (data) {
	    if (data['responseText'] == '')
	    {
		load_module_page();
		load_options();
	    }
	    else
		alert(data['responseText']);
	}
    });
}
    
function data_change_table(table)
{
    if ($("#Data_data_table").val() == table)
	$("#div_choose_1").hide();
    else
	$("#div_choose_1").show();
}