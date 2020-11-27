<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('header');
?>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="page-header">
				<h1>Регистрация</h1>
			</div>
			<div class="content ta-center">
				<form id="login_form" method="post" action="/registration?return=&random=<?=time()?>">
					<?php if (isset($error) && $error) { ?>
					<div class="alert alert-danger alert-dismissible" role="alert">
						<button type="button" class="close j-close-error" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<strong>Ошибка!</strong><br>Такой логин уже занят.
					</div>
					<?php } ?>
					<div class="input-group input-group-lg">
						<span class="input-group-addon" id="basic-addon-login"><i class="fa fa-user" aria-hidden="true"></i></span>
						<input type="text" class="form-control j-login" name="login" value="<?php echo isset($login) ? $login : '' ?>" placeholder="Логин" aria-describedby="basic-addon-login">
					</div>
					<br>
					<div class="input-group input-group-lg">
						<span class="input-group-addon" id="basic-addon-pass"><i class="fa fa-lock"></i></span>
						<input type="password" class="form-control j-pass" name="pass" value="<?php echo isset($pass) ? $pass : '' ?>" placeholder="Пароль" aria-describedby="basic-addon-pass">
                    </div>
                    <br>
                    <div class="input-group input-group-lg">
						<span class="input-group-addon" id="basic-addon-pass"><i class="fa fa-lock"></i></span>
						<input type="password" class="form-control j-pass2" name="pass2" value="<?php echo isset($pass) ? $pass : '' ?>" placeholder="Пароль еще раз" aria-describedby="basic-addon-pass">
					</div>
					<br>
					<input class="col-md-12 btn btn-info btn-lg j-submit disabled" type="submit" value="Создать аккаунт" onclick="return check();">
				</form>
				
			</div>
			<div class="clear"></div>
			
		</div>
		
	</div>
	
	
</div>
<script type="text/javascript">
check = function() {
    res = true;
    if ($('.j-login')[0].value == '') { res = false; }
    if ($('.j-pass')[0].value != $('.j-pass2')[0].value) { res = false; }

    if (res) {
        $('.j-submit').removeClass('disabled');
    } else {
        $('.j-submit').addClass('disabled');
    }
    $('.j-close-error').click();
    //console.log($('.j-login')[0].value);
    return res;
}

$('.j-login, .j-pass, .j-pass2').change(check).keyup(check).focus(check);
</script>

<?php
$this->load->view('footer');
?>