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

		$this->stash['js'] = array('modules/lessons.js', 'modules/clients.js');

		$this->stash['statuses'] = client_model::$statuses;

		$this->filters = $this->auth->data->filters;
		$this->stash['user'] = $this->auth->user;
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

	public function index($filter = 'all') {
		$this->stash['header_title'] = array(
			array('name' => '<i class="fa fa-bell-o"></i> Занятия')
		);

		$this->stash['header_buttons'] = array(
			array('name' => '<i class="fa fa-plus"></i>', 'click' => 'lessons.open(0)', 'title' => 'Сохранить')
		);

		$filters = (isset($this->filters['lesson'])) ? $this->filters['lesson'] : array();

		$data = array();

		switch ($filter) {
			case 'all': {
				break;
			}
			case 'draft': {
				$data['status'] = array(0);
				break;
			}

			case 'paused': {
				$data['status'] = array(3);
				break;
			}

			case 'completed': {
				$data['status'] = array(2);
				break;
			}

			default: {
				$data['status'] = array(1);

				break;
			}
		}
		$this->stash['filters'] = $filters;

		$data['date_from'] = date('Y-m-d', time() - (24*3600*(date('w') - 1)));
		$data['date_to'] = date('Y-m-d', time() + (24*3600*(8 - date('w'))));

		$this->stash['lessons'] = $this->lesson_model->get($data);
		$this->load->view('modules/lesson/index', $this->stash);
	}



	public function edit($id) {
		$this->stash['header_title'] = array(
			array('name' => '<i class="fa fa-bell-o"></i> Занятия')
		);

		$this->stash['header_buttons'] = array(
			array('name' => '<i class="fa fa-save"></i>', 'click' => 'lessons.save(lessons.id)')
		);



		if ($id) {
			$lesson = $this->lesson_model->get_by_id($id);
			$client = $this->client_model->get_by_id($lesson['client_id']);
			$this->stash['client'] = $client;
		} else {
			$lesson = array('id' => 0, 'client_id' => 0,
				'place' => (!empty($client['place']) ? $client['place'] : 0),
				'start_date' => date("d.m.Y 00:00"),
				'cost' => (!empty($client['data']['cost']) ? $client['data']['cost'] : 800),
				'duration' => (!empty($client['data']['duration']) ? $client['data']['duration'] : 90),
				'status' => 1,
				'data' => array()
			);

			$this->stash['clients'] = $clients = $this->client_model->get(array('status' => 1));
		}

		$this->stash['lesson'] = $lesson;
		$this->stash['lesson_statuses'] = lesson_model::$statuses;



		$this->load->view('modules/lesson/edit', $this->stash);
	}
}

