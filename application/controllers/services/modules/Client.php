<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->config->load('welcome');
		if ($this->config->item('auth')) {
			$this->auth->check_auth();
		}
		$this->load->model('client_model');
		$this->load->model('lesson_model');

		$this->stash['user'] = $this->auth->user;
		//$this->stash['js'] = 'fwctrl/connection.js';
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

		$json = array('success' => 1);
		$data = $this->input->get(NULL, true);
		try {
			$json['items'] = $this->connection_model->get($data);
			$json['total'] = $this->connection_model->total($data);
			$json['type'] = 'array';
			$json['data_type'] = 'connection';
		} catch (Exception $e) {
			$json['success'] = 0;
			$json['error'] = $e->getTraceAsString();
		}

		$this->stash['json'] = $json;
		$this->load->view('json', $this->stash);
	}

	public function edit($id) {
		if ($id) {
			$client = $this->client_model->get_by_id($id);
		} else {
			$client = array('id' => 0, 'name' => '', 'description' => '', 'address' => '', 'phones' => array(),
				'sid' => md5(time().getmypid()),
				'login' => '', 'email' => '',
				'status' => 0,
				'data' => array('delivery_id' => 0)
			);
		}

		$this->stash['client'] = $client;
		//$this->stash['lessons'] = $this->lesson_model->get(array('client_id' => $id, 'order_by' => 'o.delivery_date DESC'));
		//$this->stash['lesson_statuses'] = lesson_model::$statuses;

		//$this->stash['wrapper'] = 'popup';

		//$this->stash['document_header'] = lang($user['is_group'] ? 'Группа' : 'Пользователь');
		$this->load->view('services/client/edit', $this->stash);
	}
}