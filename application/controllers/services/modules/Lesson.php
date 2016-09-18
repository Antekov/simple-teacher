<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lesson extends CI_Controller {

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
			$json['items'] = $this->lesson_model->get($data);
			$json['total'] = $this->lesson_model->total($data);
			$json['type'] = 'array';
			$json['data_type'] = 'connection';
		} catch (Exception $e) {
			$json['success'] = 0;
			$json['error'] = $e->getTraceAsString();
		}

		$this->stash['json'] = $json;
		$this->load->view('json', $this->stash);
	}

	private function filter_save($filter, $value) {
		if (in_array($filter, array('status'))) {
			if (isset($this->filters['lesson'][$filter][$value])) {
				unset($this->filters['lesson'][$filter][$value]);
				if (empty($this->filters['lesson'][$filter])) {
					unset($this->filters['lesson'][$filter]);
				}
			} else {
				$this->filters['lesson'][$filter][$value] = $value;
			}
		} else {
			$this->filters['lesson'][$filter] = $value;
		}

		$this->auth->data->filters = $this->filters;
		$this->auth->data->save();
	}

	public function filter($filter, $value) {
		$value = rawurldecode($value);
		$this->filter_save($filter, $value);
		$this->stash['json'] = array('status'=>1);
	}

	public function get() {
		$json = array('success' => 1);
		$data = $this->input->post(NULL, true);
		try {
			if (isset($data['week'])) {

				$data['date_from'] = date('Y-m-d', strtotime($data['week']));
				$data['date_to'] = date('Y-m-d 23:59:59', strtotime($data['week'])+24*3600*6);
			}

			$this->filter_save('date_from', date('Y-m-d', strtotime($data['date_from'])));
			$this->filter_save('date_to', date('Y-m-d', strtotime($data['date_to'])+1));

			$json['data'] = $this->stash['data'] = $data;
			$json['lessons'] = $this->stash['lessons'] = $this->lesson_model->get($data);

			$json['result'] = $this->load->view('/services/lesson/get', $this->stash, true);
			$json['type'] = 'array';
		} catch (Exception $e) {
			$json['success'] = 0;
			$json['error'] = $e->getTraceAsString();
		}

		header('Content-Type: application/json; charset=utf-8');
		echo json_encode_fixed($json);
		exit;
		$this->stash['json'] = $json;
		$this->load->view('json', $this->stash);
	}

	public function edit($id, $client_id = 0) {
		if (!empty($client_id)) {
			$client = $this->client_model->get_by_id($client_id);
		} else {
			$client = array();
		}

		if ($id) {
			$lesson = $this->lesson_model->get_by_id($id);
		} else {
			$lesson = array('id' => 0, 'client_id' => 0,
				'place' => (!empty($client['place']) ? $client['place'] : 0),
				'start_date' => date("d.m.Y 00:00"),
				'cost' => (!empty($client['data']['cost']) ? $client['data']['cost'] : 800),
				'duration' => (!empty($client['data']['duration']) ? $client['data']['duration'] : 90),
				'status' => 1,
				'data' => array()
			);
		}

		$this->stash['lesson'] = $lesson;
		$this->stash['lesson_statuses'] = lesson_model::$statuses;

		$this->stash['client'] = $client;


		$this->load->view('services/client/lesson/edit', $this->stash);
	}

	public function save() {
		$data = $this->input->post(null, true);

		if ($data !== false) {
			$data['start_date'] = (!empty($data['start_date']) ? date('Y-m-d H:i:00', strtotime($data['start_date'])) : date('Y-m-d H:00:00'));

			$data = $this->lesson_model->save($data);

			$this->stash['json'] = array(
				'status' => 1,
				'client_id' => $data['client_id'],
				'id' => $data['id']
			);
		} else {
			$this->stash['json'] = array('status' => 0);
		}

		$this->load->view('json', $this->stash);
	}

	public function set() {
		$data = $this->input->post(null, true);

		if ($data !== false && !empty($data['id']) && !empty($lesson = $this->lesson_model->get_by_id($data['id']))) {

			foreach ($data as $key => $value) {
				$lesson[$key] = $value;
			}

			$data = $this->lesson_model->save($lesson);

			$this->stash['json'] = array(
				'status' => 1,
				'client_id' => $data['client_id'],
				'id' => $data['id']
			);
		} else {
			$this->stash['json'] = array('status' => 0);
		}

		$this->load->view('json', $this->stash);
	}

	public function status($id, $status) {
		$this->stash['json'] = $this->lesson_model->status($id, $status);
		$this->load->view('json', $this->stash);
	}
}