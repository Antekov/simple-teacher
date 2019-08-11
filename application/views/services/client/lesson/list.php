<div class="lesson-items">
	<?php foreach ($lessons as $lesson) { ?>
		<div class="li-item">
			<div class="lii-status">
				<span class="ld-status ld-status-<?=$lesson['status']?>"></span>
			</div>
			<div class="lii-client">
				<span class="cd-place cd-place-<?=$lesson['place']?>"></span>
				<span class="cd-name"><?=$lesson['name']?></span>
			</div>
			<div class="lii-data">
				<div class="liid-date">
					<span class="ld-date"><?=unix_to_human($lesson['start_date'], 'd mnth %Y')?></span>
					<div class="liidd-time">
						<span class="ld-time-hour"><?=unix_to_human($lesson['start_date'], 'H')?></span><span class="ld-time-minute"><?=unix_to_human($lesson['start_date'], 'i')?></span>
					</div>
				</div>
				<div class="liid-duration-cost">
					<span class="ld-cost">
						<span class="ldc-cost"><?=($lesson['cost'])?> <span class="ld-currency">руб.</span></span>
						<span class="ldc-duration"><?=($lesson['duration'])?> <span class="ld-currency">мин.</span></span>
					</span>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
<div class="">
	<table class="table-lesson list-table zebra">
		<tr>
			<th>Имя</th>
			<th>Телефон</th>
			<th>Адрес</th>
			<th>Описание</th>
			<th>Действия</th>
		</tr>
		<?php foreach ($lessons as $lesson) { ?>
			<tr class="status-<?=$lesson['status']?>">
				<td onclick="lessons.open('<?=$lesson['id']?>')">
					<span class="ld-date"><?=unix_to_human($lesson['start_date'], 'd mnth %Y')?></span>
					<span class="ld-time"><?=unix_to_human($lesson['start_date'], 'H:i')?></span>
				</td>
				<td class=""><?=$lesson['name']?></td>
				<td><span class="status-title"><?=$lesson['status']?></td>

				<td class=""><span class="ld-duration"><?=$lesson['duration']?></span></td>
				<td class=""><span class="ld_cost"><?=$lesson['cost']?></span></td>
				<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#lessonEditModal" onclick="$('#lessonEditModal .modal-body').html(''); $.get('/services/lesson/edit/<?=$lesson['id']?>/<?=$lesson['client_id']?>', function(html) { $('#lessonEditModal .modal-body').html(html); })">
						...
					</button></td>
			</tr>
		<?php } ?>
	</table>

</div>

