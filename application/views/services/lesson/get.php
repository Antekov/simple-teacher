<div class="week">
<? for ($day = 0; $day < 7; $day++) {
	$current_date = date('Y-m-d', strtotime($data['date_from'])+$day*24*3600);
?>
	<div class="w-day">
		<div class="wd-title">
			<span class="wdt-weekday"><?=unix_to_human($current_date, 'W')?></span>
			<span class="wdt-date"><?=unix_to_human($current_date, 'd.m')?></span>
		</div>
	<? for ($hour = 0; $hour < 24; $hour++) { ?>
		<div class="wd-hour" data-hour="<?=$hour?>" data-date="<?=$current_date?>">
			<div class="wdh-half"></div>
			<div class="wdh-half"></div>
		</div>
	<? } ?>
	</div>
<? } ?>
	<div class="timetable-lesson-items">
		<? foreach ($lessons as $lesson) { ?>
			<div class="tli-item" role="lesson" data-weekday="<?=date('w', strtotime($lesson['start_date']))?>" data-hour="<?=date('H', strtotime($lesson['start_date']))?>" data-minute="<?=date('i', strtotime($lesson['start_date']))?>"
				 data-duration="<?=$lesson['duration']?>" data-id="<?=$lesson['id']?>">
				<div class="tlii-status">
					<span class="ld-status ld-status-<?=$lesson['status']?>"></span>
				</div>
				<div class="tlii-data">
					<div class="tliid-time" >
						<span class="ld-time-hour"><?=unix_to_human($lesson['start_date'], 'H')?></span><span class="ld-time-minute"><?=unix_to_human($lesson['start_date'], 'i')?></span>
					</div>
					<div class="tliid-client" title="<?=$lesson['client_description']?>">
						<span class="cd-name" ><span class="cd-place cd-place-<?=$lesson['place']?>"></span> <?=$lesson['name']?></span>
						<br><span class="ldc-duration"><span class="j-duration"><?=($lesson['duration'])?></span> <span class="ld-currency">мин.</span></span>
					</div>
				</div>
				<div class="tlii-toolbar">
				<? if ($lesson['status'] == lesson_model::S_DRAFT) { ?>
					<button class="btn btn-success btn-block" onclick="lessons.status(<?=lesson_model::S_ACTIVE?>)"><i class="fa fa-play"></i> Назначить</button>
					<button class="btn btn-deafult btn-block" data-toggle="modal" data-target="#lessonEditModal" onclick="$('#lessonEditModal .modal-body').html(''); $.get('/services/lesson/edit/<?=$lesson['id']?>/<?=$lesson['client_id']?>', function(html) { $('#lessonEditModal .modal-body').html(html); })"><i class="fa fa-edit"></i> Правка</button>
				<? } ?>
				<? if ($lesson['status'] == lesson_model::S_ACTIVE) { ?>
					<? if ($lesson['start_date'] < date('Y-m-d H:i:s')) { ?>
					<button class="btn btn-success btn-block" onclick="lessons.status(<?=lesson_model::S_COMPLETE?>)"><i class="fa fa-check"></i> Проведено</button>
					<? } ?>
					<button class="btn btn-dismiss btn-block" onclick="lessons.status(<?=lesson_model::S_CANCELED?>)"><i class="fa fa-times"></i> Отменить</button>
					<button class="btn btn-deafult btn-block" data-toggle="modal" data-target="#lessonEditModal" onclick="$('#lessonEditModal .modal-body').html(''); $.get('/services/lesson/edit/<?=$lesson['id']?>/<?=$lesson['client_id']?>', function(html) { $('#lessonEditModal .modal-body').html(html); })"><i class="fa fa-edit"></i> Правка</button>
				<? } ?>
					<? if ($lesson['status'] == lesson_model::S_CANCELED) { ?>
						<button class="btn btn-success btn-block" onclick="lessons.status(<?=lesson_model::S_ACTIVE?>)"><i class="fa fa-play"></i> Назначить</button>
					<? } ?>


					<button class="btn btn-deafult btn-block" onclick="clients.open(<?=$lesson['client_id']?>)"><i class="fa fa-user"></i> Ученик</button>
				</div>
			</div>
		<? } ?>
	</div>
	<div class="timetable-busy-time-items">
		<?
		/*$busy_time = array(
			array(
				'begin_date' => '2016-09-07',
				'weekday' => 1,
				'start_time' => '12:30:00',
				'duration' => 90,
				'name' => 'Репетитор у Кати',
				//'color' => 'rgba(0,0,0,0.1)'
			),
			array(
				'begin_date' => '2016-09-14',
				'weekday' => 3,
				'start_time' => '11:00:00',
				'duration' => 90,
				'name' => 'Репетитор у Кати',
				//'color' => 'rgba(0,0,0,0.1)'
			),

			array(
				'begin_date' => '2016-09-07',
				'weekday' => 1,
				'start_time' => '15:00:00',
				'duration' => 80,
				'name' => 'Поиск у Вовы',
				//'color' => 'rgba(0,0,0,0.1)'
			),
			array(
				'begin_date' => '2016-09-07',
				'weekday' => 4,
				'start_time' => '15:00:00',
				'duration' => 80,
				'name' => 'Поиск у Вовы',
				//'color' => 'rgba(0,0,0,0.1)'
			),
			array(
				'begin_date' => '2016-09-07',
				'weekday' => 3,
				'start_time' => '14:00:00',
				'duration' => 140,
				'name' => 'Поиск у Вовы',
				//'color' => 'rgba(0,0,0,0.1)'
			),

			array(
				'begin_date' => '2016-09-07',
				'weekday' => 3,
				'start_time' => '11:00:00',
				'duration' => 60,
				'name' => 'Хореография у нас',
				//'color' => 'rgba(0,0,0,0.1)'
			),

			array(
				'begin_date' => '2016-09-07',
				'weekday' => 5,
				'start_time' => '11:00:00',
				'duration' => 60,
				'name' => 'Хореография у нас',
				//'color' => 'rgba(0,0,0,0.1)'
			),

			array(
				'begin_date' => '2016-09-12',
				'weekday' => 1,
				'start_time' => '15:30:00',
				'duration' => 60,
				'name' => 'Хореография у Кати',
				//'color' => 'rgba(0,0,0,0.1)'
			),

			array(
				'begin_date' => '2016-09-12',
				'weekday' => 4,
				'start_time' => '15:30:00',
				'duration' => 60,
				'name' => 'Хореография у Кати',
				//'color' => 'rgba(0,0,0,0.1)'
			),

			array(
				'begin_date' => '2016-09-12',
				'weekday' => 1,
				'start_time' => '11:30:00',
				'duration' => 60,
				'name' => 'Рисование у Кати',
				//'color' => 'rgba(0,0,0,0.1)'
			),


		);
		*/
		if (!empty($this->auth->user['data']['busy_time'])) {
		$busy_time = $this->auth->user['data']['busy_time'];
		foreach ($busy_time as $lesson) {
			$lesson['start_date'] = date('Y-m-d '.$lesson['start_time'], strtotime($data['date_from'])+$lesson['weekday']*24*3600);
			if (strtotime($lesson['start_date']) < strtotime($lesson['begin_date'])) {
				continue;
			}
			?>
			<div class="tli-item tbti-item" style="<?=(!empty($lesson['color']) ? 'background-color: '.$lesson['color'].';' : '')?>;" data-weekday="<?=date('w', strtotime($lesson['start_date']))?>" data-hour="<?=date('H', strtotime($lesson['start_date']))?>" data-minute="<?=date('i', strtotime($lesson['start_date']))?>"
				 data-duration="<?=$lesson['duration']?>" data-id="-1">
				<div class="tlii-data">
					<div class="tliid-time">
						<span class="ld-time-hour"><?=unix_to_human($lesson['start_date'], 'H')?></span><span class="ld-time-minute"><?=unix_to_human($lesson['start_date'], 'i')?></span>
					</div>
					<div class="tliid-client">
						<span class="cd-name"><?=$lesson['name']?></span>
						<br><span class="ldc-duration"><span class="j-duration"><?=($lesson['duration'])?></span> <span class="ld-currency">мин.</span></span>
					</div>
				</div>
			</div>
		<? } }?>
	</div>
</div>
<script>
	var minHour = 24;
	var maxHour = 0;
	var hourHeight = $('.wd-hour:eq(0)').height()+2;

	setElement = function(el) {
		var duration = $(el).data('duration');
		var weekday = $(el).data('weekday');

		if (weekday == 0) { weekday = 6 } else { weekday -= 1; }

		var hour = parseInt($(el).data('hour'));

		if (hour < minHour) { minHour = hour; }
		if (Math.round(hour + duration/60) > maxHour) {
			console.log(hour + duration/60);
			maxHour = Math.round(hour + duration/60); }

		var minute = $(el).data('minute');

		$(el).css({
			'margin-top':'calc('+(3*minute/60)+'em - 1px)',
			//height:'calc('+(3*duration/60)+'em + 1px)'
			height:hourHeight*duration/60,
			top:'',
			left: '',
			bottom: '',
			right: '',
			width: ''
		}).click(function() {
			var $el = $(this);
			if ($el.hasClass('active')) {
				$('.tli-item').removeClass('active');
				lessons.id = 0;
			} else {
				$('.tli-item').removeClass('active');
				$el.addClass('active');
				lessons.id = $el.data('id');
			}

		});

		$('.w-day').eq(weekday).find('.wd-hour').filter(function() { return ($(this).data('hour') == hour); }).before(el);
	};

	$('.tli-item').each(function(i, el) {
		setElement(el);
	}).draggable({

	}).resizable({
		minWidth: $('.wd-hour:eq(0)').width(),
		maxWidth: $('.wd-hour:eq(0)').width(),
		//disabled: true,
		start: function(event, ui) {
		},
		resize: function (event, ui) {
			var step = hourHeight / 60 * 10;
			ui.size.height = Math.round( ui.size.height / step ) * step;
			var duration = Math.round(ui.size.height / hourHeight * 60);

			if (duration < 60) {
				if (duration < 45) {
					duration = 45;
				}
				ui.size.height = hourHeight;
			}

			ui.element.data('newDuration', duration);
			$('.j-duration', ui.element).html(duration);
		},
		stop: function(event, ui) {
			var data = ui.element.data();
			var ui = ui;

			if (confirm('Изменить продолжительность занятия на ' + ui.element.data('duration') + ' мин?')) {
				$.post('/services/lesson/set/',
					{id: data.id, duration: data.newDuration},
					function (data) {
						if (data.status != 1) {
							var duration = ui.element.data('duration');

							if (duration < 60) {
								if (duration < 45) {
									duration = 45;
								}
								ui.size.height = hourHeight;
							} else {
								ui.element.css({height: Math.round(duration * hourHeight / 60)});
							}

							$('.j-duration', ui.element).html(duration);
						} else {
							ui.element.data('duration', ui.element.data('newDuration'));
						}
					}
				);
				ui.element.data('newDuration', null);
			} else {
				var duration = ui.element.data('duration');

				if (duration < 60) {
					if (duration < 45) {
						duration = 45;
					}
					ui.size.height = hourHeight;
				} else {
					ui.element.css({height: Math.round(duration * hourHeight / 60)});
				}

				$('.j-duration', ui.element).html(duration);
			}
		}
	});

	$('.w-day .wd-hour').droppable({
		accept: '[role="lesson"]',
		over: function(event, ui) {
			//console.log(event);
			//$(event.target).css({border: '3px solid #000'});
			var hour = $(event.target).data('hour');
			ui.draggable.find('.ld-time-hour').html(hour);
			//ui.draggable.find('.ld-time-minute').html($(event.target).offset().top - ui.draggable.offset().top);
			//ui.draggable.$(event.target).data('', )
			//hideHours(Math.min(hour-2, minHour), Math.max(hour+2,maxHour));
		},
		out: function(event, ui) {
			//$(event.target).css({border: ''});
		},
		drop: function(event, ui) {

			var data = ui.draggable.data();
			var hour = $(event.target).data('hour');
			var ui = ui;
			$.post('/services/lesson/set/',
				{id: data.id, start_date: $(event.target).data('date')+ ' ' + hour + ':00:00'},
				function(data) {
					if (data.status != 1) {
						alert('Error');


					} else {
						ui.draggable.data({'hour': hour, minute: '00'});
					}
					setElement(ui.draggable[0]);
				}
			);
		}
	});
	hideHours = function(minHour, maxHour) {
		console.log(minHour, maxHour);
		$('.w-day .wd-hour').show().filter(function () {
			return (parseInt($(this).data('hour')) < parseInt(minHour) || parseInt($(this).data('hour')) >= parseInt(maxHour));
		}).hide();
	}

	hideHours(minHour, maxHour);

</script>