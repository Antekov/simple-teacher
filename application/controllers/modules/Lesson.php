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

		$this->stash['js'] = array('modules/lessons.js');

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

		$this->stash['lessons'] = $this->lesson_model->get($data);
		$this->load->view('modules/lesson/index', $this->stash);
	}

	public function edit($id) {
		$this->stash['header_title'] = array(
			array('name' => '<i class="fa fa-bell-o"></i> Занятия')
		);


		if ($id) {
			$lesson = $this->lesson_model->get_by_id($id);
		} else {
			$lesson = array('id' => 0, 'user_id' => $this->auth->user['id'], 'description' => '', 'address' => '', 'phones' => array(),
				'sid' => md5(time().getmypid()),
				'login' => '', 'email' => '',
				'status' => 0,
				'data' => array('delivery_id' => 0)
			);
		}

		$this->stash['lesson'] = $lesson;
		//$this->stash['lessons'] = $this->lesson_model->get(array('client_id' => $id, 'order_by' => 'o.delivery_date DESC'));
		//$this->stash['lesson_statuses'] = lesson_model::$statuses;

		//$this->stash['wrapper'] = 'popup';

		$this->load->view('modules/lesson/edit', $this->stash);
	}
}

