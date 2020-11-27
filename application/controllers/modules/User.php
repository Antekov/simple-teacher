<?php
class User extends CI_Controller {

	function __construct() {
		$this->load->model('user_model');
		$this->load->model('order_model');
		parent::__construct();
		$this->stash['js'] = array('users.js');

		$this->stash['statuses'] = $this->user_model->statuses;

		$this->filters = $this->auth->data->filters;
	}

	public function index($filter = 'active') {
		$this->stash['document_header'] = lang('Люди');

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
		$this->stash['users'] = $this->user_model->get($data);
	}

	public function get() {
		global $CFG;
		$CFG->config['compress_output'] = true;

		$this->stash['brands'] = $this->catalogue_model->get_brands();
		$this->stash['json'] = array('result' => $this->template->get());
	}

	public function id($id) {
		if ($id) {
			$user = $this->user_model->get_by_id($id);
		} else {
			redirect('/user/edit/0/');
		}

		$this->stash['user'] = $user;
		$this->stash['orders'] = $this->order_model->get(array('user_id' => $id, 'order_by' => 'o.delivery_date DESC'));
		$this->stash['order_statuses'] = 'sdcsdc';//order_model::statuses;

		//$this->stash['content_type'] = $content_type;

		$this->stash['document_header'] = lang($user['is_group'] ? 'Группа' : 'Пользователь');
	}

	public function edit($id, $parent_id = 0) {
		$is_group = $this->input->get('is_group', true);

		if ($id) {
			$user = $this->user_model->get_by_id($id);
		} else {
			$user = array('id' => 0, 'name' => '', 'description' => '', 'address' => '', 'phones' => array(),
				'sid' => md5(time().getmypid()),
				'login' => '', 'email' => '',
				'status' => 0,
				'is_group' => (!empty($is_group) ? 1 : 0),
				'task_count' => 0, 'parent_id' => $parent_id,
				'data' => array('delivery_id' => 0)
			);
		}

		$this->stash['user'] = $user;
		$this->stash['orders'] = $this->order_model->get(array('user_id' => $id, 'order_by' => 'o.delivery_date DESC'));
		$this->stash['order_statuses'] = order_model::$statuses;
		$this->stash['items'] = $this->catalog_model->get();
		$this->stash['deliveries'] = $this->order_model->deliveries;
		//$this->stash['wrapper'] = 'popup';

		$this->stash['document_header'] = lang($user['is_group'] ? 'Группа' : 'Пользователь');
	}

	public function add_comment() {
		$data = $this->input->post(null, false);

		$this->load->model('comment_model');

		if ($data !== false) {
			$data['user_id'] = $this->auth->user['id'];
			$data['type'] = 1;
			$data = $this->comment_model->save($data);

			$this->stash['comment'] = $this->comment_model->get_by_id($data['id']);
			$this->stash['users'] = $this->user_model->get();

			$this->stash['json'] = array(
				'status' => 1,
				'id' => $data['id'],
				'result' => $this->template->get('comment')
			);
		} else {
			$this->stash['json'] = array('status' => 0);
		}
	}

	public function status($id, $status) {
		$this->stash['json'] = array('status' => 0);

		$task = $this->task_model->get_by_id($id);

		if (!empty($task['id'])) {
			$res = $this->task_model->status($id, $status);

			if ($res['status'] == $status) {
				$this->stash['json'] = array(
					'status' => 1,
				);
			} else {
				$this->stash['json']['message'] = 'd fer ihfierhf: '.$res['status'];
			}
		}
	}

	public function save($force = 0) {
		$data = $this->input->post(null, true);

		if ($data !== false) {
			$data['is_group'] = (!empty($data['is_group']) ? 1 : 0);

			// Убираем пустых телефоны
			foreach ($data['phones'] as $i => $phone) {
				if (empty($phone)) { unset($data['phones'][$i]); }
			}

			$data['phones'] = json_encode_fixed($data['phones']);

			$data = $this->user_model->save($data);

			$this->stash['json'] = array(
				'status' => 1,
				'id' => $data['id']
			);
		} else {
			$this->stash['json'] = array('status' => 0);
		}
	}

	public function delete($id) {
		if (!empty($id)) {
			$brand = reset($this->catalogue_model->get_brands(array('id' => $id)));

			if (empty($brand['count'])) {
				$this->catalogue_model->brand_delete($id);
				$this->stash['json'] = array('status' => 1);
				return;
			} else {
				$this->stash['json'] = array('status' => 2);
				return;
			}
		}

		$this->stash['json'] = array('status' => 0);
	}
}
