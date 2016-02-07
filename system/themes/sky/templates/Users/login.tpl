<div class="auth-form">
    <div class="inner">
	Логин<br/>
	<div class="bigtext">
	    <div class="left">
		<div class="right">
		    <div class="fill">
			<input type="textbox" name="users_login" id="users_login" value="{$user_login|default:""}" />
		    </div>
		</div>
	    </div>
	</div><br/>
	
	Пароль<br/>
	<div class="bigtext">
	    <div class="left">
		<div class="right">
		    <div class="fill">
			<input type="password" name="users_pass" id="users_pass" />
		    </div>
		</div>
	    </div>
	</div><br/><br/>
	
	<input type="button" id="ok" value="Войти" onclick="users_authorize();" /><br/>
    </div>
</div>

<script type="text/javascript">
    $("#users_login").keypress(function (e) { if (e.which == "13") $("#ok").click(); });
    $("#users_pass").keypress(function (e) { if (e.which == "13") $("#ok").click(); });
</script>