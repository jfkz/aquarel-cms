/* Установка модуля */
function modules_install(name)            
{
    $.ajax({
        type: 'post',
        url:  '/modules/install/'+ name,
        complete: function (data) {
            if (data['responseText'] != '')
                alert(data['responseText']);
            
            menu_display();
            load_options();
            load_module_page();
        },
        success: function (){
            $.blockUI({ message: '<br/><br/>Идет загрузка...<br/><br/><br/>' });
        }
    });
}

/* Удаление модуля */
function modules_uninstall(name)
{
    $.ajax({
        type: 'post',
        url:  '/modules/uninstall/'+ name,
        complete: function (data) {
            if (data['responseText'] != '')
                alert(data['responseText']);
            
            menu_display();
            load_options();
            load_module_page();
        },
        success: function (){
            $.blockUI({ message: '<br/><br/>Идет загрузка...<br/><br/><br/>' });
        }
    });
}

/* Включение модуля */
function modules_activate(name)
{
    $.ajax({
        type: 'post',
        url:  '/modules/activate/'+ name,
        complete: function (data) {
            if (data['responseText'] != '')
                alert(data['responseText']);
            
            menu_display();
            load_options();
            load_module_page();
        }
    });
}

/* Выключение модуля */
function modules_deactivate(name)
{
    $.ajax({
        type: 'post',
        url:  '/modules/disable/'+ name,
        complete: function (data) {
            if (data['responseText'] != '')
                alert(data['responseText']);
            
            menu_display();
            load_options();
            load_module_page();
        }
    });
}