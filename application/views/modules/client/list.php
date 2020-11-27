<table class="table-clients list-table zebra">
    <tr>
        <th>&nbsp;</th>
        <th>Имя</th>
        <th>Телефон</th>
        <th>Адрес</th>
        <th>Ставка</th>
        <!-- <th>Действия</th> -->
        <th>&nbsp;</th>
    </tr>
    <?php $i=1; foreach ($clients as $client) { ?>
    <tr>
        <td class="" onclick="clients.open('<?=$client['id']?>')">
            <span class="cd-place cd-place-<?=$client['place']?>"></span>
        </td>
        <td class="" onclick="clients.open('<?=$client['id']?>')">
            <a href="./edit/<?=$client['id']?>"><span class="cd-name"><?=$client['name']?></span></a><br>
            <span class="cd-short-date"><?=unix_to_human($client['create_date'], 'd mnth %Y')?></span>

        </td>

        <td class=""><?=(!empty($client['phones'][0]) ? $client['phones'][0] : '')?><br><span
                class="cd-description"><?=$client['description']?></span></td>
        <td class=""><?=$client['address']?></td>
        <td><span class="cd-cost"><span class="cdc-cost"><?=$client['data']['cost']?> <span
                        class="cd-currency">руб.</span></span><span
                    class="cdc-duration"><?=$client['data']['duration']?> <span
                        class="cd-currency">мин.</span></span></span></td>

        <!--
		<td>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#clientEditModal" onclick="$('#clientEditModal .modal-body').html(''); $.get('/services/client/edit/<?=$client['id']?>/', function(html) { $('#clientEditModal .modal-body').html(html); })">
				...
			</button></td>
-->
        <td>
            <span class="cd-status cd-status-<?=$client['status']?>"
                title="<?=$client_statuses[$client['status']]?>"></span>
        </td>
    </tr>
    <?php } ?>
</table>