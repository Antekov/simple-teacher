<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('header');
?>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="page-header">
				<h1>Авторизация</h1>
			</div>
			<div class="content ta-center">
				<form id="login_form" method="post" action="/login?return=&random=<?=time()?>">
					<? if (isset($error) && $error) { ?>
					<div class="alert alert-danger alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<strong>Ошибка!</strong><br>Неверная комбинация логин / пароль.
					</div>
					<? } ?>
					<div class="input-group input-group-lg">
						<span class="input-group-addon" id="basic-addon-login"><i class="fa fa-user" aria-hidden="true"></i></span>
						<input type="text" class="form-control" name="login" value="<? echo isset($login) ? $login : '' ?>" placeholder="Логин" aria-describedby="basic-addon-login">
					</div>
					<br>
					<div class="input-group input-group-lg">
						<span class="input-group-addon" id="basic-addon-pass"><i class="fa fa-lock"></i></span>
						<input type="password" class="form-control" name="pass" value="" placeholder="Пароль" aria-describedby="basic-addon-pass">
					</div>
					<br>
					<input class="col-md-12 btn btn-info btn-lg" type="submit" value="Войти">
					<br>
					<br>
					<br>
					<div class="ta-center">
						<a href="/registration"><i class="fa fa-user-plus" aria-hidden="true"></i> Создать аккаунт</a>
					</div>
				</form>
				
			</div>
			<div class="clear"></div>
			
		</div>
		
	</div>
	
	
</div>

<?
$this->load->view('footer');
?>