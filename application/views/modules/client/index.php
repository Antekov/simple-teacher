<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('header', $this->stash);
$this->load->view('left_menu', $this->stash); ?>
	<div class="container">
		<div class="content j-content">
			<? $this->load->view('top_header', $this->stash); ?>

			<div data-bind='simpleGrid: gridViewModel'></div>
			<? $this->load->view('modules/client/list', $this->stash); ?>

			<!--
			<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
				Launch demo modal
			</button>
			-->
			<!-- Modal -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
			     aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
									aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Редактировать ученика</h4>
						</div>
						<div class="modal-body">
							dcdscs
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary">Save changes</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?
$this->load->view('footer', $this->stash);