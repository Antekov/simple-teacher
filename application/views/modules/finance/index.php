<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('header', $this->stash);
$this->load->view('left_menu', $this->stash);
?>
<div class="body-container">
	<div class="content j-content">
		<?php $this->load->view('top_header', $this->stash); ?>

	<div class="row">
		<div class="col-md-6">
		<table class="table-clients list-table zebra">
    <tr>
        <th>Имя</th>
        <th>Телефон</th>
        <th>Адрес</th>
        <th>Ставка</th>
        <!-- <th>Действия</th> -->
        
    </tr>
    <?php foreach ($monthly_finance as $row) { ?>
    <tr>
        <td class="">
            <span class="cd-short-date"><?=$row['year']?> <?=$row['month']?></span>

        </td>

        <td class=""><span class=""><?=$row['Profit']?></span></td>
        <td class=""><?=$row['Loss']?></td>
        <td><span class="cd-cost"><span><?=$row['Total Profit']?> <span
                        class="cd-currency">руб.</span></span></span></td>

        <!--
		<td>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#clientEditModal" onclick="$('#clientEditModal .modal-body').html(''); $.get('/services/client/edit/<?=$client['id']?>/', function(html) { $('#clientEditModal .modal-body').html(html); })">
				...
			</button></td>
-->
        
    </tr>
    <?php } ?>
</table>
</div>
<div class="col-md-6">
		<table class="table-clients list-table zebra">
    <tr>
        <th>Имя</th>
        <th>Телефон</th>
        <th>Адрес</th>
        <th>Ставка</th>
        <!-- <th>Действия</th> -->
        
    </tr>
    <?php foreach ($weekly_finance as $row) { ?>
    <tr>
        <td class="">
            <span class="cd-short-date"><?=$row['year']?> <?=$row['week']?></span>

        </td>

        <td class=""><span class=""><?=$row['Profit']?></span></td>
        <td class=""><?=$row['Loss']?></td>
        <td><span class="cd-cost"><span class="cdc-cost"><?=$row['Total Profit']?> <span
                        class="cd-currency">руб.</span></span></span></td>

        <!--
		<td>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#clientEditModal" onclick="$('#clientEditModal .modal-body').html(''); $.get('/services/client/edit/<?=$client['id']?>/', function(html) { $('#clientEditModal .modal-body').html(html); })">
				...
			</button></td>
-->
        
    </tr>
    <?php } ?>
</table>
</div>
</div>

	</div>
</div>



<?php
$this->load->view('footer');
?>
