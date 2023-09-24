<form id="client_form" class="client-form" method="post" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?=$client['id']?>">
	<input type="hidden" class="data-duration" name="data[duration]" value="<?=$client['data']['duration']?>">
	<input type="hidden" name="status" value="<?=$client['status']?>">
	<input type="hidden" name="place" value="<?=$client['place']?>">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#info" data-toggle="tab">Информация</a></li>
		<li><a href="#lessons" data-toggle="tab">Занятия</a></li>
		<li><a href="#schedule" data-toggle="tab">Расписание</a></li>
	</ul>
	<div class="tab-content cf-tab-content">
		<div class="tab-pane active" id="info">
			<div class="row cf-row">
				<div class="col-md-6">
					<div class="field-title">Имя</div>
					<div class="input-group input-group-lg">
						<!-- <span class="input-group-addon" id="basic-addon-name"><i class="fa fa-user"></i></span> -->
						<input type="text" class="form-control" name="name" value="<?=$client['name']?>" placeholder="Имя" aria-describedby="basic-addon-name">
						<div class="input-group-btn">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"  tabindex="-1">
								<span class="client-status cd-place cd-place-<?=$client['place']?>"><?=client_model::$places[$client['place']]?></span></button>
							<ul class="dropdown-menu" role="menu">
								<?php foreach(client_model::$places as $place => $place_name) { 
									?>
								<li>
									<a herf="#" onclick="clients.setPlace(<?=$place?>);" data-value="<?=$place?>">
										<span class="cd-place cd-place-<?=$place?>" title="<?=$place_name?>"><?=$place_name?></span>
									</a>
								</li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="field-title">Ставка</div>
					<div class="input-group input-group-lg">
						
						
						<input type="number" class="form-control" name="data[cost]" value="<?=$client['data']['cost']?>" placeholder="Ставка" aria-describedby="basic-addon-skype">
						<div class="input-group-btn">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"  tabindex="-1"> <span class="client-data-duration"><?=$client['data']['duration']?></span> мин. </button>
							
							<ul class="dropdown-menu pull-right" role="menu">
								<li><a href="#" onclick="$('.client-data-duration').html($(this).data('value')); $('input.data-duration').val($(this).data('value'));" data-value="60">60 мин.</a></li>
								<li><a href="#" onclick="$('.client-data-duration').html($(this).data('value')); $('input.data-duration').val($(this).data('value'));" data-value="90">90 мин.</a></li>
								<li><a href="#" onclick="$('.client-data-duration').html($(this).data('value')); $('input.data-duration').val($(this).data('value'));" data-value="120">120 мин.</a></li>
								<li><a href="#" onclick="$('.client-data-duration').html($(this).data('value')); $('input.data-duration').val($(this).data('value'));" data-value="180">180 мин.</a></li>
							</ul>
							
						</div>
							
					</div>
					
				</div>
				<div class="col-md-3">
					<div class="field-title">Номер заказа</div>
					<div class="input-group input-group-lg">
						<input type="text" class="form-control" name="external_id" value="<?=$client['external_id']?>" placeholder="Номер на ВР">
						<div class="input-group-btn">
						<button type="button" class="btn btn-default" tabindex="-1"><a href="https://profi.ru/backoffice/n.php?o=<?=$client['external_id']?>" target="_blank">Открыть</a></button>
							</div>
					</div>
				</div>
				
				<div class="col-md-12">
					<div class="field-title">Комментарий / Статус</div>
					<div class="input-group input-group-lg">
						
						<input name="description" class="form-control" value="<?=$client['description']?>" >
						<div class="input-group-btn">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"  tabindex="-1">
								<span class="client-status cd-status cd-status-<?=$client['status']?>"><?=client_model::$statuses[$client['status']]?></span></button>
							<ul class="dropdown-menu pull-right" role="menu">
								<?php foreach(client_model::$statuses as $status => $status_name) { 
									if (!isset(client_model::$status_changes[$client['status']][$status])) continue;
									?>
								<li>
									<a herf="#" onclick="clients.setStatus(<?=$status?>);" data-value="<?=$status?>">
										<span class="cd-status cd-status-<?=$status?>" title="<?=$status_name?>"><?=$status_name?></span>
									</a>
								</li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="row cf-row cf-row-contacts">
				<div class="col-md-3">
					<div class="field-title">Телефон</div>
						<span id="phones">
						<?php if (empty($client['phones'])) {
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
										<li><a href="#" onclick="if ($('#phones div:last-child select').val() != '') { $('#new_phone').clone().appendTo($('#phones')).show(); }"><i class="fa fa-plus"></i> Добавить</a></li>
										<li><a href="#" onclick=""><i class="fa fa-times"></i> Удалить</a></a></li>
										<li><a href="#" onclick="clients.form.addComment(this)"><i class="fa fa-comment"></i> Комментарий</a></li>
									</ul>
								</div>
							</div><!-- /input-group -->

						<?php } ?>
						</span>
					<div id="new_phone" style="display: none">
						<input type="text" name="phones[]" style="width: 200px;" placeholder="+7 9XX XXX-XX-XX">
						<div class="btn btn-danger" onclick="$(this).parent().remove()">X</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="field-title">Skype</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon-skype"><i class="fa fa-skype"></i></span>
						<input type="text" class="form-control" name="skype" value="<?=$client['skype']?>" placeholder="Skype" aria-describedby="basic-addon-skype">
					</div>
				</div>
				<div class="col-md-3">
					<div class="field-title">E-mail</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon-email"><i class="fa fa-envelope-o"></i></span>
						<input type="email" class="form-control" name="email" value="<?=$client['email']?>" placeholder="E-mail" aria-describedby="basic-addon-email">
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
			<div class="row cf-row cf-row-cost">
				<?php if (!empty($client['external_id'])) { ?>
				<div class="col-md-3">
					<div class="field-title">Цена заказа</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon-skype"><i class="fa fa-times"></i></span>
						<input type="number" class="form-control" name="data[tax]" value="<?=(isset($client['data']['tax']) ? $client['data']['tax'] : '')?>" placeholder="Цена заказа">
						<?php if (empty($client['data']['tax_paid'])) { ?>
							<div class="input-group-btn">
								<button type="button" class="btn btn-default" tabindex="-1" onclick="clients.payTax();">Оплатить</button>
							</div>
						<?php } else { ?>
							<span class="input-group-addon"><i class="fa fa-check"></i> Оплачено</span>
						<?php } ?>
					</div>
				</div>
				<?php } ?>
				<div class="col-md-2">
					<div class="field-title">Дата создания</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon-skype"><i class="fa fa-calendar"></i></span>
						<input type="date" class="form-control" name="create_date" value="<?=date('Y-m-d', strtotime($client['create_date']))?>" placeholder="Дата создания" aria-describedby="basic-addon-skype">
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane" id="lessons">
			<?php $this->load->view('services/client/lesson/list', $this->stash); ?>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#lessonEditModal" onclick="$('#lessonEditModal .modal-body').html(''); $.get('/services/lesson/edit/0/<?=$client['id']?>', function(html) { $('#lessonEditModal .modal-body').html(html); })">
				<i class="fa fa-plus"></i> Добавить
			</button>
		</div>
		<div class="tab-pane" id="schedule">
			<div class="row">
				<div class="col-md-4 col-sm-4">
					<div id="schedules">
					<?php if (!empty($client['data']['schedule'])) {
						foreach ($client['data']['schedule'] as $schedule) {
							$weekdays = ['ВС', 'ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ'];
							$parts = explode(',', $schedule); 
						?>
						<div >
							<div class="input-group">
								<input type="hidden" name="data[schedule][]" value="<?=$schedule?>">
								<div class=""><span class="cs-weekday"><?=$weekdays[$parts[0]]?></span> <span class="cs-time"><?=$parts[1]?></span></div>
								<div class="input-group-btn">
									<div class="btn btn-danger" onclick="$(this).parent().parent().remove()"><i class="fa fa-times"></i></div>
								</div>
							</div>
						</div>
					<?php }} ?>
					</div>
				</div>
			</div>
			<div id="new_schedule" style="display: none">
				<div class="input-group">
					<input type="hidden" value="">
					<div class=""><span class="cs-weekday"></span> <span class="cs-time"></span></div>
					<div class="input-group-btn">
						<div class="btn btn-danger" onclick="$(this).parent().parent().remove()"><i class="fa fa-times"></i></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2 col-sm-4">
					<div class="input-group">
						<div class="input-group-addon dropdown-toggle j-schedule-weekday" data-toggle="dropdown" tabindex="-1" data-value=""> ... </div>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#" onclick="$('.j-schedule-weekday').text($(this).text()); $('.j-schedule-weekday').data('value', $(this).data('value'));" data-value="1">ПН</a></li>
							<li><a href="#" onclick="$('.j-schedule-weekday').text($(this).text()); $('.j-schedule-weekday').data('value', $(this).data('value'));" data-value="2">ВТ</a></li>
							<li><a href="#" onclick="$('.j-schedule-weekday').text($(this).text()); $('.j-schedule-weekday').data('value', $(this).data('value'));" data-value="3">СР</a></li>
							<li><a href="#" onclick="$('.j-schedule-weekday').text($(this).text()); $('.j-schedule-weekday').data('value', $(this).data('value'));" data-value="4">ЧТ</a></li>
							<li><a href="#" onclick="$('.j-schedule-weekday').text($(this).text()); $('.j-schedule-weekday').data('value', $(this).data('value'));" data-value="5">ПТ</a></li>
							<li><a href="#" onclick="$('.j-schedule-weekday').text($(this).text()); $('.j-schedule-weekday').data('value', $(this).data('value'));" data-value="6">СБ</a></li>
							<li><a href="#" onclick="$('.j-schedule-weekday').text($(this).text()); $('.j-schedule-weekday').data('value', $(this).data('value'));" data-value="0">ВС</a></li>
						</ul>
						
						<input class="form-control j-schedule-time" type="time" value="00:00"/>
						<div class="input-group-btn">
							<input type="text" class="btn btn-default phone-comment" tabindex="-1" value="<?=$d?>" style="<?=(intval($d).'' == $d ? 'display: none;' : '')?>">
							<button type="button" class="btn btn-default" tabindex="-1" onclick="
							if ($('.j-schedule-time').val() != '00:00' && $('.j-schedule-weekday').data('value') !== '') {
								 const weekdays = ['ВС', 'ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ'];
								 let el = $('#new_schedule').clone();
								 el.appendTo($('#schedules')).show();
								 $('input', el).attr('name', 'data[schedule][]').val($('.j-schedule-weekday').data('value') + ',' + $('.j-schedule-time').val());
								 $('.cs-weekday', el).text(weekdays[$('.j-schedule-weekday').data('value')]);
								 $('.cs-time', el).text($('.j-schedule-time').val());
								 $('.j-schedule-weekday').data('value', '').text('...');
								 $('.j-schedule-time').val('00:00');
								}">
								<i class="fa fa-plus"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript">
$(function() {
	clients.id = '<?=$client['id']?>';
});
</script>