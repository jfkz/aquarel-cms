<form action="" method="post">
    
    {if isset($errors) && count($errors) > 0}
    <ul style="color:red; border:1px solid red">
        {foreach from=$errors item=error}
        <li>{$error}</li>
        {/foreach}
    </ul><br/><br/>
    {/if}
    
    {if isset($messages) && count($messages) > 0}
    <ul style="color:green; border:1px solid green">
        {foreach from=$messages item=message}
        <li>{$message}</li>
        {/foreach}
    </ul><br/><br/>
    {/if}
    
    <label for="login">Имя:</label>
    <input type="text" name="login" value="{$users_registration_data.login|default:""}" /><br/><br/>
    
    <label for="email">E-mail:</label>
    <input type="text" name="email" value="{$users_registration_data.email|default:""}" /><br/><br/>
    
    <label for="pass">Пароль:</label>
    <input type="password" name="pass" value="" /><br/><br/>
    
    <label for="pass2">Пароль (еще раз):</label>
    <input type="password" name="pass2" value="" /><br/><br/>
    
    <label for="pass2">Город:</label>
    <input type="text" name="city" value="{$users_registration_data.city|default:""}" /><br/><br/>
    
    <input type="submit" value="Регистрация" />
</form>