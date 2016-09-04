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
					<span class="ct-name j-client-name"><?=($client['id'] == 0 ? 'Новый' : $client['name'])?></span>
					<span class="ct-date"><?=unix_to_human($client['create_date'], 'd mnth %Y')?></span>
					<span class="ct-description"><?=$client['description']?></span>
					<span class="ct-cost"><span class="ctc-cost"><?=$client['data']['cost']?></span><span class="ctc-duration"> руб. / <?=$client['data']['duration']?> мин.</span></span>
				</h1>
				<? $this->load->view('services/client/edit', $this->stash); ?>
				<nav class="navbar navbar-default" role="navigation">
					<div class="container">
						<button type="button" class="btn btn-success navbar-btn" onclick="clients.save(clients.id)">Сохранить</button>
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