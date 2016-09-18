<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('header', $this->stash);
$this->load->view('left_menu', $this->stash); ?>
<div class="body-container">
	<div class="content j-content">
	<? $this->load->view('top_header', $this->stash); ?>
		<div class="container">
		<div class="row">
			<div class="col-sm-10 col-sm-offset-1">
				<h1 class="client-title">
					<span class="ct-place j-client-place"><i class="fa fa-skype"></i></span>
					<span class="ct-name j-client-name"><?=($user['id'] == 0 ? 'Новый' : $user['name'])?></span>
					<span class="ct-description"><?=$user['description']?></span>
					<span class="ct-cost"><span class="ctc-cost"><?=$user['data']['cost']?></span><span class="ctc-duration"> руб. / <?=$user['data']['duration']?> мин.</span></span>
				</h1>
				<form id="client_form" class="client-form" method="post" enctype="multipart/form-data">
					<input type="hidden" name="id" value="<?=$user['id']?>">
					<input type="hidden" class="data-duration" name="data[duration]" value="<?=$user['data']['duration']?>">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#info" data-toggle="tab">Информация</a></li>
						<li><a href="#lessons" data-toggle="tab">Занятия</a></li>
						<li><a href="#shedule" data-toggle="tab">Расписание</a></li>
					</ul>
					<div class="tab-content cf-tab-content">
						<div class="tab-pane active" id="info">
							<div class="row">
								<div class="col-md-4">
									<div class="field-title">Имя</div>
									<div class="input-group input-group-lg">

										<span class="input-group-addon" id="basic-addon-name"><i class="fa fa-user"></i></span>
										<input type="text" class="form-control" name="name" value="<?=$user['name']?>" placeholder="Имя" aria-describedby="basic-addon-name">
									</div>
								</div>

							</div>
							<div class="row cf-only-place cf-only-place-0">
								<br>
								<div class="col-md-4 ">
									<div class="field-title">E-mail</div>
									<div class="input-group input-group-lg">
										<span class="input-group-addon" id="basic-addon-email"><i class="fa fa-envelope-o"></i></span>
										<input type="email" class="form-control" name="email" value="<?=$user['email']?>" placeholder="E-mail" aria-describedby="basic-addon-email">
									</div>
								</div>
								<div class="col-md-4">
									<div class="field-title">Skype</div>
									<div class="input-group input-group-lg">
										<span class="input-group-addon" id="basic-addon-skype"><i class="fa fa-skype"></i></span>
										<input type="text" class="form-control" name="skype" value="<?=$user['skype']?>" placeholder="Skype" aria-describedby="basic-addon-skype">
									</div>
								</div>

							</div>
							<br>
							<div class="row">
								<div class="col-md-6">
									<div class="field-title">Комментарий</div>
									<div class="input-group ">
										<span class="input-group-addon" id="basic-addon-skype"><i class="fa fa-file-text-o"></i></span>
										<input name="description" class="form-control" value="<?=$user['description']?>" >
									</div>
								</div>
								<div class="col-md-6 cf-only-place cf-only-place-0 cf-only-place-1">
									<div class="field-title">Адрес</div>
									<div class="input-group ">
										<span class="input-group-addon" id="basic-addon-skype"><i class="fa fa-location-arrow"></i></span>
										<input name="address" class="form-control" value="<?=$user['address']?>" >
									</div>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-4">
									<div class="field-title">Телефон</div>
				<span id="phones">
				<? if (empty($user['phones'])) {
					$user['phones'][0] = '';
				}
				foreach ($user['phones'] as $d => $phone) { ?>

					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-phone"></i></span>
						<input type="text" class="form-control" name="phones[<?=(intval($d).'' == $d ? '' : $d)?>]" placeholder="8 9XX XXX-XX-XX" value="<?=$this->user_model->format_phone($this->user_model->parse_phone($phone))?>">
						<div class="input-group-btn">
							<input type="text" class="btn btn-default phone-comment" tabindex="-1" value="<?=$d?>" style="<?=(intval($d).'' == $d ? 'display: none;' : '')?>">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" tabindex="-1">
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu pull-right" role="menu">
								<li><a href="#" onclick=""><i class="fa fa-plus"></i> Добавить</a></li>
								<li><a href="#" onclick=""><i class="fa fa-times"></i> Удалить</a></a></li>
								<li><a href="#" onclick="clients.form.addComment(this)"><i class="fa fa-comment"></i> Комментарий</a></li>
							</ul>
						</div>
					</div><!-- /input-group -->

				<? } ?>
				</span>
									<div id="new_phone" style="display: none">
										<input type="text" name="phones[]" style="width: 200px;" placeholder="+7 9XX XXX-XX-XX">
										<div class="btn btn-danger" onclick="$(this).parent().remove()">X</div>
									</div>

								</div>
								<div class="col-md-4">
									<div class="field-title">&nbsp;</div>
									<div class="btn btn-default" onclick="if ($('#phones div:last-child select').val() != '') { $('#new_phone').clone().appendTo($('#phones')).show(); }">+</div>
								</div>


								<div class="col-md-4">
									<div class="field-title">Ставка</div>
									<div class="input-group">
										<span class="input-group-addon" id="basic-addon-skype"><i class="fa fa-money"></i></span>
										<input type="number" class="form-control" name="data[cost]" value="<?=$user['data']['cost']?>" placeholder="Ставка" aria-describedby="basic-addon-skype">
										<div class="input-group-btn">
											<div class="input-group-btn">
												<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"  tabindex="-1"> <span class="client-data-duration"><?=$user['data']['duration']?></span> мин. </button>
												<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" tabindex="-1">
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu pull-right" role="menu">
													<li><a href="#" onclick="$('.client-data-duration').html($(this).data('value')); $('input.data-duration').val($(this).data('value'));" data-value="60">60 мин.</a></li>
													<li><a href="#" onclick="$('.client-data-duration').html($(this).data('value')); $('input.data-duration').val($(this).data('value'));" data-value="90">90 мин.</a></li>
													<li><a href="#" onclick="$('.client-data-duration').html($(this).data('value')); $('input.data-duration').val($(this).data('value'));" data-value="120">120 мин.</a></li>
													<li><a href="#" onclick="$('.client-data-duration').html($(this).data('value')); $('input.data-duration').val($(this).data('value'));" data-value="180">180 мин.</a></li>
												</ul>
											</div>
										</div>

									</div>

								</div>

							</div>

						</div>
						<div class="tab-pane" id="lessons">
							<? $this->load->view('services/client/lesson/list', $this->stash); ?>
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#lessonEditModal" onclick="$('#lessonEditModal .modal-body').html(''); $.get('/services/lesson/edit/0/<?=$user['id']?>', function(html) { $('#lessonEditModal .modal-body').html(html); })">
								<i class="fa fa-plus"></i> Добавить
							</button>
						</div>
						<div class="tab-pane" id="shedule">Расписание</div>
					</div>
				</form>

				<script type="text/javascript">
					$(function() {
						users.id = '<?=$user['id']?>';
					});
				</script>
				<nav class="navbar navbar-default" role="navigation">
					<div class="container">
						<button type="button" class="btn btn-success navbar-btn" onclick="users.save(users.id)">Сохранить</button>
					</div>
				</nav>
			</div>
		</div>
			</div>
	</div>
</div>
	<!-- Modal -->
	<div class="modal fade" id="lessonEditModal" tabindex="-1" role="dialog" aria-labelledby="lessonEditModalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="lessonEditModalLabel">Редактировать занятие</h4>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="lessons.save(lessons.id);" data-toggle="modal">Сохранить</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
				</div>
			</div>
		</div>
	</div>


<?
$this->load->view('footer');
?>