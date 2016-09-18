<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('header', $this->stash);
$this->load->view('left_menu', $this->stash); ?>
<div class="body-container">
	<div class="content j-content">
	<? $this->load->view('top_header', $this->stash); ?>
		<? $this->load->view('services/client/lesson/edit', $this->stash); ?>

<?
$this->load->view('footer');
?>