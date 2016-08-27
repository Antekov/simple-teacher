<?php
/**
 * class Staff_model
 *
 * model for staff data
 * create date: 05.04.2013
 *
 */
class User_model extends CI_Model
{
	var $fields = array('id', 'name', 'address', 'phones', 'description', 'email', 'parent_id', 'status', 'login', 'password', 'uniqueid');
	var $is_admin = false;

	const S_DRAFT		= 0;
	const S_ACTIVE		= 1;
	const S_COMPLETED	= 2;
	const S_PAUSED		= 3;

	var $statuses = array(
		0 => array(
			0 => 'Неактивирован',
			1 => 'Активный',
			2 => 'Удален',
			3 => 'Приостановлен'
		),
		1 => array(
			0 => 'Черновик',
			1 => 'В работе',
			2 => 'Условно выполнен',
			3 => 'Приостановлен'
		),
	);

	var $status_changes = array(
		user_model::S_DRAFT => array(
			user_model::S_ACTIVE => array(),
			user_model::S_PAUSED => array(),
		),
		user_model::S_ACTIVE => array(
			user_model::S_COMPLETED => array(),
			user_model::S_PAUSED => array(),
		),
		user_model::S_COMPLETED => array(
			user_model::S_ACTIVE => array(),
		),
		user_model::S_PAUSED => array(
			user_model::S_ACTIVE => array(),
			user_model::S_COMPLETED => array(),
		),
	);

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {
		parent::__construct();

		if (isset($this->auth->user['groups'])) {
			$this->is_admin = in_array('1', $this->auth->user['groups']);
		}
	}

	public function get($data = array()) {
		if (isset($data['id'])) {
			$this->db->where_in('t.id',(array) $data['id']);
		}

		if (isset($data['parent_id'])) {
			$this->db->where('t.parent_id', intval($data['parent_id']));
		}

		if (isset($data['is_group'])) {
			$this->db->where('t.is_group', intval($data['is_group']));
		}

		if (isset($data['status'])) {
			$this->db->where_in('t.status', (array) $data['status']);
		}

		$this->db
			->select('t.*, COUNT(DISTINCT t1.id) AS task_count')
			->from(T_USERS.' AS t')
			->join(T_USERS.' AS t1', 't.id = t1.parent_id', 'LEFT')
			->group_by('t.id')
			->order_by('t.address, t.name');

		$tasks = array_to_assoc($this->db->get()->result_array(), 'id');

		if (empty($data['not_tree'])) {

			$ids = array();
			foreach ($tasks as $task) {
				if (!isset($tasks[$task['parent_id']])) {
					$ids[] = $task['parent_id'];
				}
			}

			if (!empty($ids)) {
				$this->db
					->select('t.*, COUNT(DISTINCT t1.id) AS task_count')
					->from(T_USERS.' AS t')
					->join(T_USERS.' AS t1', 't.id = t1.parent_id', 'LEFT')
					->where_in('t.id', $ids)
					->group_by('t.id')
					->order_by('t.name');

				$tasks += array_to_assoc($this->db->get()->result_array(), 'id');
			}

			$this->docs = $tasks;
			$tasks = $this->get_tree();

			if (!empty($ids) && empty($data['with_parents'])) {
				foreach ($ids as $id) {
					unset($tasks[$id]);
				}
			}
		}

		foreach ($tasks as $id => $task) {
			$tasks[$id]['data'] = json_decode($task['data'], true);
			$tasks[$id]['phones'] = (array) json_decode($task['phones'], true);

			//$vendors[$id]['phone'] = (array) json_decode($vendor['phone'], true);
			//$vendors[$id]['email'] = (array) json_decode($vendor['email'], true);
		}

		return $tasks;
	}

	public function get_tree($parent_id = 0, $name = '', $level = 0) {
		$docs = array();
		if (count($this->docs)) {
			foreach ($this->docs as $id => $doc) {
				//if (!isset($docs['children'])) { $docs[$id]['children'] = array(); }

				if ($doc['parent_id'] == $parent_id) {
					$doc['parents'] = array($parent_id);
					$doc['full_title'] = $name.(empty($name) ? '' : ' / ').$doc['name'];
					$doc['level'] = $level;
					$doc['children'] = array();
					$doc['count_items'] = 0;

					// Удаляем из временного массива
					unset($this->docs[$id]);
					// Выбираем наследников
					$docs[$id] = $doc;

					$children = $this->get_tree($doc['id'], $doc['full_title'], $level+1);
					if (count($children)) {
						foreach ($children as $child_id => $child) {
							$docs[$child_id] = $child;
							array_push($docs[$child_id]['parents'], $parent_id);
							$docs[$id]['count_items'] += 1;
							$docs[$id]['children'] = array_merge($docs[$id]['children'], array($child_id), $docs[$child_id]['children']);
						}
					}

					$docs[$id]['children'] = array_values(array_unique($docs[$id]['children']));
				}
			}
		}
		return $docs;
	}

	public function search($value) {
		$ids = array_keys(array_to_assoc(
			$this->db
				->select('id', false)
				->from(T_SYSTEM_USERS)
				->like('LOWER(fio)', mb_strtolower($value))
				->or_like('LOWER(login)', mb_strtolower($value))
				->or_like('LOWER(email)', mb_strtolower($value))
				->or_like('LOWER(phone)', mb_strtolower($value))
				->get()->result_array(),
			'id'
		));

		return count($ids) ? $this->get(array('id' => $ids)) : array();
	}

	public function get_by_id($id)				{ return reset($this->get(array('id' => (string) $id))); }
	public function get_by_uniqueid($uniqueid)	{ return reset($this->get(array('uniqueid' => (string) $uniqueid))); }
	public function get_by_group_id($group_id)	{ return $this->get(array('id' => array_keys($this->get(array('group_id' => $group_id))), 'status' => 1)); }

	public function get_groups($data = array()) {
		if (!empty($data['id'])) { $this->db->where_in('ug.id', (array) $data['id']); }

		$this->db->order_by(isset($data['order_by']) ? $data['order_by'] : 'ug.id ASC');

		$groups = array_to_assoc(
			$this->db
				->select('ug.*, COUNT(u.id) as members')
				->from(T_SYSTEM_USER_GROUP.' AS ug')
				->join(T_SYSTEM_USERS_TO_GROUPS.' AS utg', 'utg.user_group_id = ug.id', 'LEFT')
				->join(T_SYSTEM_USERS.' AS u', 'u.id = utg.user_id AND u.status = 1', 'LEFT')
				->group_by('ug.id')->get()->result_array(),
			'id'
		);

		return $groups;
	}

	public function parse_phones($phones){
		if( is_array($phones)){
			$parsed_phones = array();
			foreach ($phones as $key=>$phone) {
				$phone_id = (isset($phone['phone_id'])) ? $phone['phone_id'] : $key;
				if( is_array($phone)) $phone = implode('', $phone);
				if( $phone != ''){
					$parsed_phones[$phone_id] = $this->parse_phone($phone);
				}
			}
			return $parsed_phones;
		}
		return array();
	}

	public function parse_phone($phone){
		$this->load->config('phones');
		$phoneCodes = $this->config->item('phone_codes');
		$country_codes = array(1=>'USA', 7=>'Russia', 380=>'Ukraine');

		$phone_chunks = preg_split('/[^\d\(\)\-\s+\+\.]/', $phone);
		$max_len = 0;
		$chunk_id = null;
		foreach ($phone_chunks as $id=>$p) {
			$pcs = trim(preg_replace("/[^\d]/", "", $p));
			if ( mb_strlen($pcs) > $max_len ) {
				$max_len = mb_strlen($pcs);
				$chunk_id = $id;
			}
		}
		if (is_null($chunk_id)) {
			// TODO: return undefited here
		}
		else {
			$phone =  trim(preg_replace("/[^\d]/", "", $phone_chunks[$chunk_id]));
		}


		//$_phone['phone_id'] = $phone['phone_id'];
		$_phone['country_code'] = 0;
		$_phone['phone'] = $phone;
		$_phone['is_mobile'] = 0;

		// заменяем 00 в начале номера на +
		if (mb_substr($phone, 0, 2)=="00") {
			$phone = mb_substr($phone, 2, strlen($phone)-2);
		}
		if (strlen($phone) == 11 && $phone[0] == 8 ) {
			// Скорее всего россия
			$phone = mb_substr($phone, 1, strlen($phone));
			$phone = '7'.$phone;
		}

		if (strlen($phone) == 10) {
			if( $phone[0] != '7' && $phone[0] != '0'){
				// Если в номере ровно 10 цифр, то предполагаем, что это российский номер и дописываем ему код страны 7
				$phone = '7'.$phone;
			}else if( $phone[0] == '0'){
				// Если в номере ровно 10 цифр и он начинается с 0, то предполагаем, что это украинский номер и дописываем ему код страны 380
				$phone = '38'.$phone;
			}
		}

		// Если в номере ровно 9 цифр, то предполагаем, что это украинский номер и дописываем ему код страны 380
		if (strlen($phone) == 9 && $phone[0] != '0') {
			$phone = '380'.$phone;
		}

		// Начнем с количества цифр
		if( strlen($phone) > 10 ){
			foreach( $country_codes as $code=>$country ){
				$codelen = strlen($code);
				if( mb_substr($phone, 0, $codelen) == $code ){
					// Код страны найден и он в начале
					$_phone['country_code'] = $code;
					$_phone['phone'] = mb_substr($phone, $codelen, strlen($phone));
					$_phone['is_mobile'] = $this->is_mobile_phone($_phone);
					break;
				}
			}

		}
		return $_phone;
	}

	private function is_mobile_phone($phone) {
		$res = 0;
		// Для России
		if ($phone['country_code'] == '7' && $phone['phone'][0] == '9' && strlen($phone['phone']) == 10) {
			$res = 1;
		} else
			// Для Украины
			if ($phone['country_code'] == '380') {
				if ($phone['phone'][0] == '9') {
					$res = 1;
				} else {
					$code = mb_substr($phone['phone'],0,2);
					$mobile_codes_UA = array('50' => 1, '63' => 1, '66' => 1, '67' => 1, '68' => 1);
					if (isset($mobile_codes_UA[$code])) {
						$res = 1;
					}
				}
			}
		return $res;
	}

	public function format_phone($phone) {
		if (!is_array($phone)) {
			$phone = $this->parse_phone($phone);
		}
		if( strlen($phone['phone']) < 10 ){
			$parts = array(4, 2, 2, 2);
		}else{
			if( strlen($phone['phone']) > 10 ){
				$parts = array(4, 3, 2, 2);
			} else {
				$parts = array(3, 3, 2, 2);
			}
		}
		$next = 0;
		foreach($parts as $part ){
			$_phone[] = mb_substr($phone['phone'], $next, $part);
			$next += $part;
		}
		foreach( $_phone as $key=>$v ){
			if( $v == '' && $key != 0){
				unset($_phone[$key]);
			}
		}
		if( $next < strlen($phone['phone']) ){
			$_phone[] = mb_substr($phone['phone'], $next, strlen($phone['phone']) );
		}
		if( strlen($phone['phone']) > 8 ){
			$formatted = '+'.$phone['country_code'].' '.$_phone[0].' ';
		}else{
			$formatted = $_phone[0];
		}
		unset($_phone[0]);
		$formatted .= implode('-', $_phone);
		return $formatted;
	}

	public function get_group_by_id($group_id) {
		$group = reset($this->get_groups(array('id' => $group_id)));

		if (isset($group['id'])) {
			$group['users'] = $this->get_by_group_id($group_id);
		}

		return $group;
	}

	public function save($data) {
		$all_data = $data;

		foreach ($data as $field => $value) {
			if (!in_array($field, $this->fields)) {
				unset($data[$field]);
			}
		}

		if ($data['id'] == 0) {
			$this->db->insert(T_USERS, $data);
			$data['id'] = $this->db->insert_id();
		} else {
			$id = $data['id'];
			unset($data['id']);
			$this->db->where('id', $id)->update(T_USERS, $data);
			$data['id'] = $id;
		}

		if (isset($all_data['data']['delivery_id'])) {
			$this->save_data($data['id'], 'delivery_id', $all_data['data']['delivery_id']);
		}
		/*
		if ($staff_groups !== false) {
			$this->db->where('user_id', $data['id'])->delete(T_SYSTEM_USERS_TO_GROUPS);
			$groups = $this->get_groups();

			foreach ($staff_groups as $group_id) {
				if (isset($groups[$group_id])) {
					$this->db->insert(T_SYSTEM_USERS_TO_GROUPS, array('user_id' => $data['id'], 'user_group_id' => $group_id));
				}
			}
		}
		*/
		return $data;
	}

	private function save_data($id, $key, $value) {
		$user = $this->get_by_id($id);

		if (!empty($user)) {
			$data[$key] = $value;
			$this->db->where('id', $id)->update(T_USERS, array('data' => json_encode_fixed($data)));
		}
	}

	public function set_unactive($user_id = NULL) {
		$this -> db -> where('id', $user_id);
		$this -> db -> update('system_users', array("status" => "2", "password" => ""));
	}

	public function get_group_members($group_id = NULL) {
		return $this->get_by_group_id($group_id);
	}

	public function get_dismiss_members() {
		return $this->get(array('status' => 2));
	}

	public function delete_user_from_groups($user_id) {
		$this -> db -> where('user_id', $user_id);
		$this -> db -> delete('system_user_to_group');
	}

	public function gen_pass($type = NULL, $lenght = NULL) {
		$pass = array();
		for ($i = 0; $i < $lenght; $i++) {
			$pass[] = chr(mt_rand(48, 57));
		}
		if ($type == 'rand') {
			for ($i = 0; $i < $lenght; $i++) {
				$pass[] = chr(mt_rand(97, 122));
			}
		}
		shuffle($pass);
		$password = "";
		for ($i = 0; $i < $lenght; $i++) {
			$password .= $pass[$i];
		}
		return $password;
	}

}
?>