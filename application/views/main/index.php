<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('header', $this->stash);
?>
<div class="container">
	<div class="content j-content">

		<!-- Main component for a primary marketing message or call to action -->
		<div class="jumbotron">
			<h1>MAIN Navbar example</h1>

			<p>This example is a quick exercise to illustrate how the default, static and fixed to top navbar work. It
				includes the responsive CSS and HTML, so it also adapts to your viewport and device.</p>

			<p>To see the difference between static and fixed top navbars, just scroll.</p>

			<p>
				<a class="btn btn-lg btn-primary" href="/dashboard/" role="button">Dashboard »</a>
				<a class="btn btn-lg btn-default" href="/login/" role="button">Login »</a>
			</p>
		</div>

	</div>
</div>

<?
$this->load->view('footer');
?>
