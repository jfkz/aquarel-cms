    /* смена раздела меню */
    function control_menu_changeTab(tab)
        {
        //var speed = '';       // мгновенное переключение
        var speed = 'fast';     // приятно и довольно быстро
        //var speed = 'slow';   // красиво, но мееееедленно..
        
        $('.control_menu_tab').hide();
        $('#control_menu_tab_'+tab).show(speed);
        }
    
    /* заносим изменения в базу */
    function control_menu_reorder(columns_ids)
        {
        columns_ids = columns_ids.split('_');
        var columns = '';
        var j = 0;
        
        for (i=0; i<columns_ids.length; i++)
            {
            columns = $('#column_'+columns_ids[i]+' div');
            j = 0;
            $.each(columns, function(){
                if (this.id != 'sortHelper')
                    {
                    $("#debug_info").load(
                        "../system/ajax/ajax.db.php",
                        { option: 'save', table: 'control_menu', id: this.id, field: 'weight', new_value: j }
                        );
                    $("#debug_info").load(
                        "../system/ajax/ajax.db.php",
                        { option: 'save', table: 'control_menu', id: this.id, field: 'parent', new_value: columns_ids[i] }
                        );
                    j++;
                    }
                });
            }
        }