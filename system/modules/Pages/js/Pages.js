/**
 * Module:  pages
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

/* Изменение структуры страниц */
function pages_reorder(parent, id, pos)
{
    /* изменяем родителя и вес страницы */
    $.ajax({
        type: 'post',
        url:  '/Pages/reorder/'+ id,
        data: 'values='+ parent + acSep + pos +'&theme_control=true',
        complete: function (data) {
	    load_options();
	}
    });
}

/* Добавление страницы */
function pages_add()
{
    $.ajax({
        type: 'post',
        url:  '/Pages/add',
	data: 'theme_control=true',
        complete: function (data) {
	    load_options();
            load_module_page();
        }
    });
}