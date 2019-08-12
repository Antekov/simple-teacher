<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('header', $this->stash);
$this->load->view('left_menu', $this->stash);
?>
<div class="body-container">
	<div class="content j-content">
		<?php $this->load->view('top_header', $this->stash); ?>

		<!-- Main component for a primary marketing message or call to action -->
		<div class="row">
			<div class="col-sm-5">
		<div class="jumbotron">
			<h1>Расписание</h1>

			<p>Основной раздел для управления расписанием занятий</p>

			<p>Позволяет:</p>

			<ul>
				<li>Содзавать занятия</li>
				<li>Редактировать: менять время...</li>
				<li>Просмативать</li>
</ul>

			<p>
				<a class="btn btn-lg btn-primary" href="/lesson" role="button">Расписание »</a>
			</p>
		</div>
</div>
		<div class="col-sm-5">
		<div class="jumbotron">
			<h1>Ученики</h1>

			<p>Основной раздел для управления расписанием занятий</p>

			<p>Позволяет:</p>

			<ul>
				<li>Содзавать занятия</li>
				<li>Редактировать: менять время...</li>
				<li>Просмативать</li>
</ul>

			<p>
				<a class="btn btn-lg btn-primary" href="/client" role="button">Ученики »</a>
			</p>
		</div>
</div>
</div>
</div>
	</div>
</div>



<?php
$this->load->view('footer');
?>
