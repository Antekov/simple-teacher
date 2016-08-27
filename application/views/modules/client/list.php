<table class="table-clients">
	<tr>
		<th>Имя</th>
		<th>Телефон</th>
		<th>Адрес</th>
		<th>Описание</th>
		<th>Действия</th>
	</tr>
<? foreach ($clients as $client) { ?>
	<tr>
		<td class=""><?=$client['name']?></td>
		<td class=""><?=$client['phones'][0]?></td>
		<td class=""><?=$client['address']?></td>
		<td class=""><?=$client['description']?></td>
		<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" onclick="$.get('/services/client/edit/<?=$client['id']?>/', function(html) { $('#myModal .modal-body').html(html); })">
				...
			</button></td>
	</tr>
<? } ?>
</table>

