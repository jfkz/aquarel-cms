<div class="portlets">
    <h1>{$title}</h1>
    <div class="inner">
        
        {if isset($columns)}
        {foreach from=$columns key=column_key item=column}
        <div class="column" name="{$column.name}" id="column_{$column.id}">
            
            {foreach from=$column.portlets key=portlet_key item=portlet}
            <div class="portlet control_draggebleColumns_portlet" id="{$portlet.id}" >
                <div class="portlet-header">{$portlet.title}</div>
                <div class="portlet-content">
                    <a href="#Menu/edit/{$portlet.id}">
                        <img width="15" height="16" src="{$site.theme_path}/images/edit.png" title="редактировать" alt="редактировать" />
                    </a>
                    
                    <a onclick="javascript: if (MsgOkCancel('Действительно удалить {$portlet.title}?')) delete_item('menu', '{$portlet.id}');">
                        <img width="16" height="16" src="{$site.theme_path}/images/delete.png" title="удалить" alt="удалить" />
                    </a>
                    
                    {if $portlet.active}
                    <a onclick="disable_item('menu', '{$portlet.id}')">
                        <img width="16" height="16" src="{$site.theme_path}/images/active.png" title="выключить" alt="выключить" />
                    </a>
                    {else}
                    <a onclick="activate_item('menu', '{$portlet.id}')">
                        <img width="16" height="16" src="{$site.theme_path}/images/disable.png" title="включить" alt="включить" />
                    </a>
                    {/if}
                </div>
            </div>
            {/foreach}
            
        </div>
        {/foreach}
	{/if}
        
        <div style="width:100%; float:left">
            <input type="button" id="save" value="Сохранить (Ctrl + S)" onclick="menu_reorder('{$columns_ids}')" />
        </div>
        
    </div>
</div>
    
<style type="text/css">
    .column { width: 170px; float: left; }
    .portlet { margin: 0 1em 1em 0; }
    .portlet-header { margin: 0.3em; padding-bottom: 4px; padding-left: 0.2em; }
    .portlet-header .ui-icon { float: right; }
    .portlet-content { padding: 0.4em; }
    .ui-sortable-placeholder { border: 1px dotted black; visibility: visible !important; height: 50px !important; }
    .ui-sortable-placeholder * { visibility: hidden; }
</style>

<script type="text/javascript">
    $(function() {
	$(".column").sortable({
	    connectWith: ".column"
	});
        
	$(".portlet").addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
	    .find(".portlet-header")
	    .addClass("ui-widget-header ui-corner-all")
	    .prepend("<span class='ui-icon ui-icon-minusthick'></span>")
	    .end()
	    .find(".portlet-content");
        
	$(".portlet-header .ui-icon").click(function() {
	    $(this).toggleClass("ui-icon-minusthick").toggleClass("ui-icon-plusthick");
	    $(this).parents(".portlet:first").find(".portlet-content").toggle();
	});
        
	$(".column").disableSelection();
    });
</script>