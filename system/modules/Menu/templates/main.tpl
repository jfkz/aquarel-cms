
{if isset($menu)}
<h2>Menu:main.tpl</h2>
    <ul>
    {foreach from=$menu key=item_name item=item}
    <li><a href="{$item.link}">{$item.title}</a></li>
    {/foreach}
    </ul>
</div>
{/if}