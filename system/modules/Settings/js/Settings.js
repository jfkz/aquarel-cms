/** 
 * Module:  Settings
 * File:    settings.js
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 */

/* Сохранение настроек */
function settings_save(fields)
{
    field  = fields.split(acSep);                                               // разделяем строку с параметрами (поля в БД) на отдельные элементы и записываем их в массив
    values = new Array();                                  			// создаем массив со значениями этих параметров
    
    for (i=0; i<field.length; i++)
        values.push(acHtmlValue('Settings_'+ field[i]));
    
    values = values.join(acSep);                                                // записываем значения в строку для передачи серверу
    
    $.ajax({
        type: 'post',
        url:  'settings/save',
        data: 'theme_control=true&fields='+ fields +'&values='+ values,
        complete: function (data) {
            if (data['responseText'] != '')
		alert(data['responseText']);
        }
    });
}