<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<aside class="left-menu j-left-menu">
	<nav class="navbar navbar-default">
		<ul class="nav navbar-nav">
			<li class="<?=($this->uri->segment(1,'') == 'dashboard' ? 'open' : '')?>"><a href="/dashboard/"><i class="fa fa-home"></i> Главная</a></li>
			<li class="<?=($this->uri->segment(1,'') == 'lesson' ? 'open' : '')?>"><a href="/lesson/"><i class="fa fa-clock-o"></i> Расписание</a></li>
			<?php if ($this->uri->segment(1,'') == 'lesson') { ?>
				<li class=""><a href="#" onclick="lessons.fill_schedule(); $('.j-left-menu-button').click();"><i class="fa fa-calendar-o"></i> Заполнить расписание</a></li>
			<?php } ?>
			<li class="<?=($this->uri->segment(1,'') == 'client' ? 'open' : '')?>"><a href="/client/"><i class="fa fa-users"></i> Ученики</a></li>
			<!-- li class="<?=($this->uri->segment(1,'') == 'library' ? 'open' : '')?>"><a href="#about"><i class="fa fa-list"></i> Материалы</a></li> -->
			<li class="<?=($this->uri->segment(1,'') == 'finance' ? 'open' : '')?>"><a href="/finance/"><i class="fa fa-money"></i> Финансы</a></li>
			<!-- <li class="<?=($this->uri->segment(1,'') == 'user' ? 'open' : '')?>"><a href="#"><i class="fa fa-user"></i> Профиль</a></li> -->
			<li><a href="/logout"><i class="fa fa-clock-o"></i> Выход</a></li>
		</ul>
	</nav>
</aside>
