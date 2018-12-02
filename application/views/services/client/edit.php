<form id="client_form" class="client-form" method="post" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?=$client['id']?>">
	<input type="hidden" class="data-duration" name="data[duration]" value="<?=$client['data']['duration']?>">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#info" data-toggle="tab">Информация</a></li>
		<li><a href="#lessons" data-toggle="tab">Занятия</a></li>
		<li><a href="#schedule" data-toggle="tab">Расписание</a></li>
	</ul>
	<div class="tab-content cf-tab-content">
		<div class="tab-pane active" id="info">
	<div class="row">
		<div class="col-md-4">
			<div class="field-title">Имя</div>
			<div class="input-group input-group-lg">

				<span class="input-group-addon" id="basic-addon-name"><i class="fa fa-user"></i></span>
				<input type="text" class="form-control" name="name" value="<?=$client['name']?>" placeholder="Имя" aria-describedby="basic-addon-name">
			</div>
		</div>
		<div class="col-md-4">
			<div class="field-title">Место занятий</div>
			<div class="input-group input-group-lg">
				<span class="input-group-addon" id="basic-addon-email"><i class="fa fa-map-marker"></i></span>
				<select type="text" class="form-control cf-field-place" name="place" value="<?=$client['place']?>" aria-describedby="basic-addon-email">
					<option value="0" <?=($client['place'] == 0 ? 'selected' : '')?> >Дистанционно</option>
					<option value="1" <?=($client['place'] == 1 ? 'selected' : '')?> >У ученика</option>
					<option value="2" <?=($client['place'] == 2 ? 'selected' : '')?> >У преподавателя</option>
					<option value="3" <?=($client['place'] == 3 ? 'selected' : '')?> >В офисе</option>
				</select>
			</div>
		</div>
	</div>
	<div class="row cf-only-place cf-only-place-0">
		<br>
		<div class="col-md-4 ">
			<div class="field-title">E-mail</div>
			<div class="input-group input-group-lg">
				<span class="input-group-addon" id="basic-addon-email"><i class="fa fa-envelope-o"></i></span>
				<input type="email" class="form-control" name="email" value="<?=$client['email']?>" placeholder="E-mail" aria-describedby="basic-addon-email">
			</div>
		</div>
		<div class="col-md-4">
			<div class="field-title">Skype</div>
			<div class="input-group input-group-lg">
				<span class="input-group-addon" id="basic-addon-skype"><i class="fa fa-skype"></i></span>
				<input type="text" class="form-control" name="skype" value="<?=$client['skype']?>" placeholder="Skype" aria-describedby="basic-addon-skype">
			</div>
		</div>

	</div>
	<br>
	<div class="row">
		<div class="col-md-6">
			<div class="field-title">Комментарий</div>
			<div class="input-group ">
				<span class="input-group-addon" id="basic-addon-skype"><i class="fa fa-file-text-o"></i></span>
				<input name="description" class="form-control" value="<?=$client['description']?>" >
			</div>
		</div>
		<div class="col-md-6 cf-only-place cf-only-place-0 cf-only-place-1">
			<div class="field-title">Адрес</div>
			<div class="input-group ">
				<span class="input-group-addon" id="basic-addon-skype"><i class="fa fa-location-arrow"></i></span>
				<input name="address" class="form-control" value="<?=$client['address']?>" >
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-4">
			<div class="field-title">Телефон</div>
				<span id="phones">
				<? if (empty($client['phones'])) {
					$client['phones'][0] = '';
				}
				foreach ($client['phones'] as $d => $phone) { ?>

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
			<div class="field-title">Номер заказа</div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-skype"></i></span>
				<input type="text" class="form-control" name="external_id" value="<?=$client['external_id']?>" placeholder="Номер на ВР">
				<div class="input-group-btn">
				<button type="button" class="btn btn-default" tabindex="-1"><a href="http://stavropol.repetitors.info/backoffice/p.php?o=<?=$client['external_id']?>" target="_blank">Открыть</a></button>
					</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="field-title">Дата создания</div>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon-skype"><i class="fa fa-calendar"></i></span>
				<input type="date" class="form-control" name="create_date" value="<?=date('Y-m-d', strtotime($client['create_date']))?>" placeholder="Дата создания" aria-describedby="basic-addon-skype">
			</div>
		</div>
		<div class="col-md-4">
			<div class="field-title">Ставка</div>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon-skype"><i class="fa fa-money"></i></span>
				<input type="number" class="form-control" name="data[cost]" value="<?=$client['data']['cost']?>" placeholder="Ставка" aria-describedby="basic-addon-skype">
				<div class="input-group-btn">
					<div class="input-group-btn">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"  tabindex="-1"> <span class="client-data-duration"><?=$client['data']['duration']?></span> мин. </button>
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
		<? if (!empty($client['external_id'])) { ?>
		<div class="col-md-4">
			<div class="field-title">Цена заказа</div>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon-skype"><i class="fa fa-times"></i></span>
				<input type="number" class="form-control" name="data[tax]" value="<?=(!empty($client['data']['tax']) ? $client['data']['tax'] : '')?>" placeholder="Цена заказа">
				<? if (empty($client['data']['tax_paid'])) { ?>
					<div class="input-group-btn">
						<button type="button" class="btn btn-default" tabindex="-1" onclick="clients.payTax();">Оплатить</button>
					</div>
				<? } else { ?>
					<span class="input-group-addon"><i class="fa fa-check"></i> Оплачено</span>
				<? } ?>
			</div>
		</div>
		<? } ?>
	</div>

			</div>
		<div class="tab-pane" id="lessons">
			<? $this->load->view('services/client/lesson/list', $this->stash); ?>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#lessonEditModal" onclick="$('#lessonEditModal .modal-body').html(''); $.get('/services/lesson/edit/0/<?=$client['id']?>', function(html) { $('#lessonEditModal .modal-body').html(html); })">
				<i class="fa fa-plus"></i> Добавить
			</button>
		</div>
		<div class="tab-pane" id="schedule">
			<? $this->load->view('services/client/schedule/list', $this->stash); ?>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#lessonEditModal" onclick="$('#lessonEditModal .modal-body').html(''); $.get('/services/lesson/edit/0/<?=$client['id']?>', function(html) { $('#lessonEditModal .modal-body').html(html); })">
				<i class="fa fa-plus"></i> Добавить
			</button>
		</div>
	</div>
</form>

<script type="text/javascript">
$(function() {
	clients.id = '<?=$client['id']?>';
});
</script>