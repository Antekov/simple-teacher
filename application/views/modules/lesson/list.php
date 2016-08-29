<div class="">
	<table class="lesson list-table zebra">
		<tr>
			<th>Имя</th>
			<th>Телефон</th>
			<th>Адрес</th>
			<th>Описание</th>
			<th>Действия</th>
		</tr>
	<? foreach ($lessons as $lesson) { ?>
		<tr class="status-<?=$lesson['status']?>">
			<td onclick="lessons.open('<?=$lesson['id']?>')">
				<div class="lt-date"><?=unix_to_human($lesson['start_date'], 'd mnth %Y')?></div>
				<div class="lt-time"><?=unix_to_human($lesson['start_date'], 'H:i')?></div>
			</td>
			<td class=""><?=$lesson['name']?></td>
			<td><span class="status-title"><?=$lesson['status']?></td>

			<td class=""><?=$lesson['duration']?></td>
			<td class=""><?=$lesson['total_cost']?></td>
			<td class=""><?=$lesson['client_id']?></td>
			<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#clientEditModal" onclick="$('#clientEditModal .modal-body').html(''); $.get('/services/client/edit/<?=$lesson['id']?>/', function(html) { $('#clientEditModal .modal-body').html(html); })">
					...
				</button></td>
		</tr>
	<? } ?>
	</table>
	</div>

