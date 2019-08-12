<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance extends CI_Controller {

	public function __construct() {

		parent::__construct();
		$this->config->load('welcome');
		if ($this->config->item('auth')) {
			$this->auth->check_auth();
		}
		$this->load->model('client_model');
		$this->load->model('lesson_model');
        $this->load->model('user_model');
        $this->load->model('finance_model');

		// $this->stash['js'] = array('modules/users.js', 'modules/clients.js', 'modules/lessons.js');

		$this->stash['client_statuses'] = client_model::$statuses;

		$this->filters = $this->auth->data->filters;
		$this->id = $this->auth->user['id'];
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	public function index() {
		$this->stash['header_title'] = array(
			array('name' => '<i class="fa fa-money"></i> Финансы')
		);

		$this->stash['header_buttons'] = array(
			//array('name' => '<i class="fa fa-plus"></i>', 'click' => 'clients.open(0)')
		);

		$data = array();

		$this->stash['user'] = $this->user_model->get_by_id($this->id);
		$this->stash['lessons'] = $this->lesson_model->get();
		$this->load->view('modules/finance/index', $this->stash);
	}

	
}

