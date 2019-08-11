<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('header', $this->stash);
$this->load->view('left_menu', $this->stash); ?>
	<div class="body-container">
		<div class="content j-content">
			<? $this->load->view('top_header', $this->stash); ?>


			<? $this->load->view('modules/client/list', $this->stash); ?>


			<!-- Modal -->
			<div class="modal fade" id="clientEditModal" tabindex="-1" role="dialog" aria-labelledby="clientEditModalLabel"
			     aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
									aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="clientEditModalLabel">Редактировать ученика</h4>
						</div>
						<div class="modal-body"></div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" onclick="clients.save(clients.id);">Сохранить</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
$this->load->view('footer', $this->stash);
