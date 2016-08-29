<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('header', $this->stash);
$this->load->view('left_menu', $this->stash); ?>
<div class="container">
	<div class="content j-content">
	<? $this->load->view('top_header', $this->stash); ?>
		<div class="row">
			<div class="col-sm-10 col-sm-offset-1">
				<h1><span class="blue"><span id="user_name"><?=($lesson['id'] == 0 ? 'Новый' : $lesson['name'].'</span> <span class="c-gray small">/ '.'</span>')?></span>
					</h1>
				<form id="lesson_form" method="post" enctype="multipart/form-data">
					<input type="hidden" name="id" value="<?=$lesson['id']?>">
					<input type="hidden" name="client_id" value="<?=$lesson['client_id']?>">

					<div class="row">
						<div class="col-md-4">
							<div class="input-group input-group-lg">
								<span class="input-group-addon" id="basic-addon-name"><i class="fa fa-user"></i></span>
								<input type="text" class="form-control" value="<?=$lesson['name']?>" placeholder="Имя" aria-describedby="basic-addon-name">
							</div>
						</div>
						<div class="col-md-4">
							<div class="input-group input-group-lg">
								<span class="input-group-addon" id="basic-addon-email"><i class="fa fa-mail"></i></span>
								<input type="text" class="form-control" name="start_date" value="<?=$lesson['start_date']?>" placeholder="E-mail" aria-describedby="basic-addon-email">
							</div>
						</div>
						<div class="col-md-4">
							<div class="input-group input-group-lg">
								<span class="input-group-addon" id="basic-addon-skype"><i class="fa fa-skype"></i></span>
								<input type="text" class="form-control" name="duration" value="<?=$lesson['duration']?>" placeholder="Skype" aria-describedby="basic-addon-skype">
							</div>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-6">
							<div class="input-group ">
								<span class="input-group-addon" id="basic-addon-skype"><i class="fa fa-skype"></i> Комментарий</span>
								<input name="cost" class="form-control" value="<?=$lesson['cost']?>" >
							</div>
						</div>
						<div class="col-md-6">
							<div class="input-group ">
								<span class="input-group-addon" id="basic-addon-skype"><i class="fa fa-skype"></i> Адрес</span>
								<input name="total_cost" class="form-control" value="<?=$lesson['total_cost']?>" >
							</div>
						</div>
					</div>
				</form>
				<nav class="navbar navbar-default" role="navigation"
					<div class="container">
						<button type="button" class="btn btn-success navbar-btn" onclick="lessons.save(lessons.id)">Сохранить</button>
					</div>
				</nav>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	lessons.id = <?=$lesson['id']?>;
var default_title = '<?=($lesson['is_group'] ? 'Новый проект' : 'Новая задача')?>';

$('input[name=name]').bind('change keyup', function() {
	if ($(this).val() != '') {
		$('#user_name').html($(this).val());
	} else {
		$('#user_name').html(default_title);
	}
});


</script>
<?
$this->load->view('footer');
?>