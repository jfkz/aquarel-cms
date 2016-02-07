{if isset($login)}
Здравствуйте, {$login}<br/>
<a href="/users/logout">Выйти</a>
{else}
<a href="/users/authorization">Авторизация</a><br/>
<a href="/users/registration">Регистрация</a>
{/if}