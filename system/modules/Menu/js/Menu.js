/** 
 * Module:  Menu
 * File:    menu.js
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 */

/* Загрузка верхнего меню */
function menu_display()            
{
    var hash = location.hash;
    if (hash.indexOf('#') != -1)
    {
        var path = hash.split('#')[1];
        if (path.indexOf('/') != -1)
        {
            path = path.split('/');
            
            var module = path[0];
            var page   = path[1];
        }
    }
    else
    {
        var module = '';
        var page   = '';
        
        $('#main_body').html('<br/><br/><br/><center>Добро пожаловать! Приятной работы :)</center>').css('width', '95%');
        $('#left_menu').html('');
    }
    
    $.ajax({
        type:     'post',
        dataType: 'json',
        url:      '/menu/display',
        data:     'module='+ module +'&page='+ page +'&theme_control=true',
        success:  function (menu) {
            if(menu && menu.length > 0)
                acJson.load_top_menu(menu);
        }
    });
}

/* Смена раздела меню */
function menu_changeTab(tab)
{
    // var speed = ''; // ''|'fast'|'slow' : (мгновенное переключение | приятно и довольно быстро | красиво, но мееееедленно..)
    // alert($('.top_menu .tab.active').attr('id'));
    
    $('.top_menu .tab.active').removeClass('active');
    $('.top_menu .lev0 .l.active').removeClass('active');
    
    $('#menu_tab_'+tab).addClass('active');
    $('#top_menu_'+tab).parent().parent().parent().addClass('active');
}

/* Сохранение изменений структуры меню панели управления в базе данных */
function menu_reorder(columns_ids)
{
    columns_ids = columns_ids.split(acSep);
    
    var columns = '';
    var j = 0;
    
    var ids     = new Array('');
    var parents = new Array('');
    var weights = new Array();
    
    for (i=0; i<columns_ids.length; i++)
    {
        columns = $('#column_'+ columns_ids[i] +' div');
        j = 0;
        
        $.each(columns, function(){
            if (this.id != 'sortHelper')
            {
                id = this.id;
                parent = columns_ids[i];
                weight = j;
                
                ids.push(id);
                parents.push(parent);
                weights.push(weight);
                
                j++;
            }
        });
    }
    
    ids     = ids.join(acSep);
    parents = parents.join(acSep);
    weights = weights.join(acSep);
    
    $.ajax({
        type: 'post',
        url:  '/menu/reorder',
        data: 'ids='+ ids +'&parents='+ parents +'&weights='+ weights +'&theme_control=true',
        complete: function (data) {
            if (data['responseText'] == '')
                load_options();
            else
                alert(data['responseText']);
        }
    });
}