<form method="post" action="/login?return=<?php echo $return ?>" id="auth_form" onsubmit="g.submit('#auth_form', auth.check); return false;">
	<? if (isset($error) && $error) { ?> 
	<div class="error">Wrong login-password combination. Try again.</div>
	<? } ?>
	<h3><?=$user['fio']?></h3>
	<input type="hidden" name="login" value="<?=$user['login']?>" />
	<input type="hidden" name="json" value="1" />
	<input type="hidden" name="rr" value="<?=uniqid();?>" />
	<label>
		<div>Пароль</div>
		<input class="big" type="password" id="pass" size="10" name="pass" value="" />
	</label>
</form>