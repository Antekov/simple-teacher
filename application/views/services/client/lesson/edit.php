<?php if (!empty($client)) { ?>
<div class="row">
	<div class="col-sm-12">
		<h1 class="client-title">
			<span class="ct-place j-client-place"><i class="fa fa-skype"></i></span>
			<span class="ct-name j-client-name"><?=($client['id'] == 0 ? 'Новый' : $client['name'])?></span>
			<span class="ct-description"><?=$client['description']?></span>
			<span class="ct-cost"><span class="ctc-cost"><?=$client['data']['cost']?></span><span class="ctc-duration"> руб. / <?=$client['data']['duration']?> мин.</span></span>
		</h1>
	</div>
</div>
<?php } ?>
<form id="lesson_form" class="lesson-form" method="post" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?=$lesson['id']?>">
	<?php if (!empty($client)) { ?>
		<input type="hidden" name="client_id" value="<?=$client['id']?>">
		<input type="hidden" name="client_cost" value="<?=$client['data']['cost']?>">
		<input type="hidden" name="client_duration" value="<?=$client['data']['duration']?>">
	<?php } ?>
	<input type="hidden" name="place" value="<?=$lesson['place']?>">
	<input type="hidden" name="start_date" value="<?=$lesson['start_date']?>">
	<input type="hidden" name="cost" value="<?=$lesson['cost']?>">
	<input type="hidden" name="duration" class="data-duration" value="<?=$lesson['duration']?>">
	<input type="hidden" name="return_url" value="/lesson/">

			<div class="row">
				<?php if (empty($client) && !empty($clients)) { ?>
					<div class="col-md-6 col-md-offset-3">
						<div class="field-title">Клиент</div>
						<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="fa fa-user"></i></span>
							<select class="form-control" name="client_id" value="" placeholder="" autofocus required>
								<option value="">...</option>
								<?php foreach ($clients as $client) { ?>
									<option value="<?=$client['id']?>"><?=$client['name'].' / '.$client['description']?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				<?php } ?>
				<div class="col-md-6 col-md-offset-3">
					<div class="field-title">Дата</div>
					<div class="input-group input-group-lg">
						<span class="input-group-addon" id="basic-addon-skype"><i class="fa fa-calendar"></i></span>
						<input type="date" class="form-control lf-date-input" value="<?=unix_to_human($lesson['start_date'], 'Y-m-d')?>" placeholder="" autofocus>
					</div>
				</div>
				<div class="col-md-6 col-md-offset-3">
					<div class="field-title">Время</div>
					<div class="input-group input-group-lg">
						<span class="input-group-addon" id="basic-addon-skype"><i class="fa fa-clock-o"></i></span>
						<input type="time" class="form-control lf-time-input" value="<?=unix_to_human($lesson['start_date'], 'H:i')?>" placeholder="Дата создания">
					</div>
				</div>
			</div>
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="field-title">Продолжительность</div>
			<div class="input-group input-group-lg">
				<span class="input-group-addon" id="basic-addon-skype"><i class="fa fa-circle-o"></i></span>
				<input type="number" class="form-control data-duration" value="<?=$lesson['duration']?>" placeholder="Продолжительность">
				<div class="input-group-btn input-group-lg">
					<div class="input-group-btn">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" tabindex="-1">
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu pull-right" role="menu">
							<li><a href="#" onclick="$('input.data-duration').val($(this).data('value'));" data-value="60">1 ч. (60 мин.)</a></li>
							<li><a href="#" onclick="$('input.data-duration').val($(this).data('value'));" data-value="90">1,5 ч. (90 мин.)</a></li>
							<li><a href="#" onclick="$('input.data-duration').val($(this).data('value'));" data-value="120">2 ч. (120 мин.)</a></li>
							<li><a href="#" onclick="$('input.data-duration').val($(this).data('value'));" data-value="150">2,5 ч. (150 мин.)</a></li>
							<li><a href="#" onclick="$('input.data-duration').val($(this).data('value'));" data-value="180">3 ч. (180 мин.)</a></li>
						</ul>
					</div>
				</div>

			</div>
		</div>
	</div>






</form>

<script type="text/javascript">
	$(function() {
		lessons.id = '<?=$lesson['id']?>';
		lessons.init_form();
	});

	/*
			$('[name="start_date1"]').click(function() {
			$(this).datepicker('dialog', $(this).val(), function() {}, {
			dateFormat: 'dd.mm.yy HH:ii',
			monthNames: [ "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь" ],
			dayNamesMin: [ "Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб" ],
			firstDay: 1
		});
	});
	*/
</script>