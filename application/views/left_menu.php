<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<aside class="left-menu j-left-menu">
	<nav class="navbar navbar-default">
		<ul class="nav navbar-nav">
			<? if ($this->uri->segment(1,'') != 'dashboard') { ?><li><a href="/dashboard/"><i class="fa fa-home"></i> Главная</a></li><? } ?>
			<li><a href="/fwctrl/connection/"><i class="fa fa-clock-o"></i> Расписание</a></li>
			<li><a href="/client/"><i class="fa fa-users"></i> Ученики</a></li>
			<li><a href="#about"><i class="fa fa-list"></i> Материалы</a></li>
			<li><a href="#about"><i class="fa fa-money"></i> Финансы</a></li>
			<li><a href="#"><i class="fa fa-user"></i> Профиль</a></li>
			<li><a href="/logout"><i class="fa fa-clock-o"></i> Выход</a></li>
		</ul>
	</nav>
</aside>
