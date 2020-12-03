<?php
/**
 *
 *
 */
class Finance_model extends CI_Model
{
	static $fields = array('id', 'type', 'math', 'amount', 'description', 'data', 'client_id', 'lesson_id', 'payment_date');

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {
		parent::__construct();
		$this->load->model('lesson_model');
		$this->load->model('client_model');
	}

	/**
	 * Список всех брендов, удовлетворяющих фильтру
	 *
	 * @access public
	 *
	 * @return array (vendors list)
	 */
	public function get($data = array()) {
		if (isset($data['id'])) {
			$this->db->where_in('f.id',(array) $data['id']);
		}

		if (isset($data['type'])) {
			$this->db->where_in('f.type', (array) $data['type']);
		}

		if (isset($data['math'])) {
			$this->db->where('f.math', intval($data['math']));
		}

		if (isset($data['client_id'])) {
			$this->db->where_in('f.client_id', (array) $data['order_id']);
		}

		if (isset($data['lesson_id'])) {
			$this->db->where_in('f.lesson_id', (array) $data['order_id']);
		}

		if (isset($data['description'])) {
			$this->db->like('f.description', $data['description'], 'both');
		}

		$this->db->where('f.user_id', $this->auth->user['id']);

		$this->db
			->select('f.*')
			->from(T_FINANCES.' AS f')
			->order_by('f.payment_date DESC');

		$payments = array_to_assoc($this->db->get()->result_array(), 'id');

		foreach ($payments as $id => $payment) {
			$payment[$id]['data'] = (!empty($payment['data']) ? json_decode($payment['data'], true) : array());
		}

		return $payments;
	}

	public function get_sum_by_type($data = array()) {
		$this->db
			->select('type, SUM(amount*math) AS total')
			->from(T_FINANCES.' AS f')
			->where('math', -1)
			->group_by('type')
			->order_by('f.payment_date DESC');

		$payments = array_to_assoc($this->db->get()->result_array(), 'type');

		foreach ($payments as $id => $payment) {
		}

		return $payments;
	}

	public function get_sum_by_math($data = array()) {
		$this->db
			->select('math, SUM(amount*math) AS total')
			->from(T_FINANCES.' AS f')
			->group_by('math')
			->order_by('f.payment_date DESC');

		$payments = array_to_assoc($this->db->get()->result_array(), 'math');

		foreach ($payments as $id => $payment) {
		}

		return $payments;
	}

	public function weekly_finance() {
		return $this->db->query('SELECT * FROM weekly_finance')->result_array();
	}

	public function monthly_finance() {
		return $this->db->query('SELECT * FROM monthly_finance')->result_array();
	}

	public function get_by_id($id) {
		$payments = $this->get(array('id' => $id));
		$payment = (!empty($payments[$id]) ? $payments[$id] : array());

		if (!empty($payment)) {
			/*
			$comments = (!empty($payment['comments_count']) ?
				$this->comment_model->get($id)
				:
				array());
			
			foreach($comments as $comment) {
				
			}
			
			$payment['comments'] = $comments;
			
			$payment['logs'] = $this->comment_model->get_log($id);
			
			$payment['parent'] = (!empty($payment['parent_id']) ? $this->get_by_id($payment['parent_id']) : array());
			 * */
		}
		return $payment;
	}

	/**
	 * Сохранение фабрики
	 *
	 * @access public
	 *
	 * @param array data (vendor data)
	 *
	 * @return integer (vendor id)
	 */
	public function save($data) {
		$all_data = $data;
		$old_task = $this->get_by_id($data['id']);

		foreach ($data as $field => $value) {
			if (!in_array($field, $this::$fields)) {
				unset($data[$field]);
			}
		}

		$data['user_id'] = $this->auth->user['id'];

		if (empty($data['id'])) {
			$data['id'] = NULL;
			$this->db->insert(T_FINANCES, $data);
			$data['id'] = $this->db->insert_id();
		} else {
			$id = $data['id'];
			unset($data['id']);
			$this->db->where('id', $id)->update(T_FINANCES, $data);
			$data['id'] = $id;
		}


		return $data;
	}

	public function add_tax_payment($data) {
		$data['id'] = 0;
		$data['type'] = 2;
		$data['math'] = -1;
		$data['lesson_id'] = null;
		if (empty($data['payment_date'])) $data['payment_date'] = date('Y-m-d H:i:s');

		$data['description'] = 'Оплата заказа клиента #'.$data['client_id'];

		return $this->save($data);
	}

	public function get_payment_types() {
		//'7 - заказ, 1 - корм, 2 - животные, 3 - материалы, 4 - работы, 5 - медпрепараты, 6 - накладные расходы',
		$types = array(
			1 => array('id' => 1, 'name' => 'Занятие', 'description' => '', 'parent_id' => 0, 'is_group' => 0),
			2 => array('id' => 2, 'name' => 'Оплата заказа', 'description' => '', 'parent_id' => 0, 'is_group' => 0),
			3 => array('id' => 3, 'name' => 'Транспорт', 'description' => '', 'parent_id' => 0, 'is_group' => 0),
			4 => array('id' => 4, 'name' => 'Материалы', 'description' => '', 'parent_id' => 0, 'is_group' => 0),
			5 => array('id' => 5, 'name' => 'Работы', 'description' => '', 'parent_id' => 0, 'is_group' => 0),
			6 => array('id' => 6, 'name' => 'Накладные расходы', 'description' => '', 'parent_id' => 0, 'is_group' => 0),

		);
		return $types;
	}

	/*
	public function status($id, $status) {
		$res = array(
			'status' => -1
		);

		$payment = $this->get_by_id($id);

		if (!empty($payment['id'])) {
			$old_status = $payment['status'];

			if (isset($this->status_changes[$payment['status']][$status])) {
				$this->db->where('id', $id)->update(T_TASKS, array('status' => $status));

				$log_data = array('old' => $old_status, 'new' => $status);
				$this->comment_model->log($id, comment_model::EV_STATUS.$status, $log_data);

				$res['status'] = $status;
			} else {

			}
		} else {
			$res['message'] = 'Задача не найдена';

		}

		return $res;
	}

	public function delete($id) {
		$this->db->where('id', $id)->delete(T_VENDORS);
	}



	/**
	 * avatar function.
	 * update avatar filename
	 *
	 * @access public
	 *
	 * @param integer id (vendor id)
	 * @param string filename (avatar filename)
	 *
	 * @return void
	 */
	public function avatar($id, $filename) {
		$this->save(array('id' => $id, 'avatar' => $filename));
	}

}
?>