<?php
/**
 * class Staff_model
 *
 * model for staff data
 * create date: 05.04.2013
 *
 */
class Client_model extends CI_Model
{
	var $fields = array('id', 'name', 'address', 'phones', 'description', 'email', 'skype', 'status', 'login', 'external_id', 'create_date', 'place');
	var $default_fields_values;
	var $is_admin = false;

	const S_DRAFT		= 0;
	const S_ACTIVE		= 1;
	const S_PAUSED		= 2;
	const S_COMPLETE	= 3;
	const S_CANCELED	= 4;

	static $statuses = array(
		0 => 'Необработан',
		1 => 'В работе',
		4 => 'Отменен',
		3 => 'Выполнен',
		2 => 'Приостановлен'
	);



	static $status_changes = array(
		client_model::S_DRAFT => array(
			client_model::S_ACTIVE => array(),
			client_model::S_CANCELED => array(),
			client_model::S_COMPLETE => array(),
			client_model::S_PAUSED => array(),
		),
		client_model::S_ACTIVE => array(
			client_model::S_CANCELED => array(),
			client_model::S_COMPLETE => array(),
			client_model::S_PAUSED => array(),
		),

		client_model::S_CANCELED => array(
			client_model::S_ACTIVE => array(),
		),
		client_model::S_COMPLETE => array(
			client_model::S_ACTIVE => array(),
			client_model::S_COMPLETE => array(),
		),

		client_model::S_PAUSED => array(
			client_model::S_ACTIVE => array(),
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

		$this->load->model('user_model');
		$this->load->model('finance_model');

		$this->default_fields_values = array(
			'id' => 0, 'name' => '', 'description' => '', 'address' => '', 'phones' => array(),
			'place' => 0,
			'sid' => md5(time().getmypid()),
			'login' => '', 'email' => '', 'skype' => '', 'external_id' => '',
			'status' => 0,
			'create_date' => date('d.m.Y'),
			'data' => array('cost' => '600', 'duration' => '90', 'tax' => '')
		);
	}

	public function get($data = array()) {
		if (isset($data['id'])) {
			$this->db->where_in('t.id',(array) $data['id']);
		}

		if (isset($data['parent_id'])) {
			$this->db->where('t.parent_id', intval($data['parent_id']));
		}

		if (isset($data['status'])) {
			$this->db->where_in('t.status', (array) $data['status']);
		}

		$this->db
			->select('t.*, f.amount AS tax_paid')
			->from(T_CLIENTS.' AS t')
			->join(T_FINANCES.' AS f', 'f.client_id = t.id AND f.type = 2', 'LEFT')
			->where('t.user_id', $this->auth->user['id'])
			->order_by('t.status ASC, t.create_date DESC, t.address, t.name');

		$clients = array_to_assoc($this->db->get()->result_array(), 'id');

		foreach ($clients as $id => $client) {
			$clients[$id]['data'] = json_decode($client['data'], true);
			$clients[$id]['phones'] = (array) json_decode($client['phones'], true);

			if (!empty($client['tax_paid'])) {
				$clients[$id]['data']['tax_paid'] = $client['tax_paid'];
			}

			//$vendors[$id]['phone'] = (array) json_decode($vendor['phone'], true);
			//$vendors[$id]['email'] = (array) json_decode($vendor['email'], true);
		}

		return $clients;
	}

	public function search($value) {
		$ids = array_keys(array_to_assoc(
			$this->db
				->select('id', false)
				->from(T_CLIENTS)
				->like('LOWER(name)', mb_strtolower($value))
				->or_like('LOWER(login)', mb_strtolower($value))
				->or_like('LOWER(email)', mb_strtolower($value))
				->or_like('LOWER(phone)', mb_strtolower($value))
				->get()->result_array(),
			'id'
		));

		return count($ids) ? $this->get(array('id' => $ids)) : array();
	}

	public function get_by_id($id)				{
		$clients = $this->get(array('id' => (string) $id));
		return reset($clients);
	}
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
		$country_codes = array(1=>'USA', 7=>'Russia', 380=>'Ukraine', 44=>'United Kingdom');

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
			$data['user_id'] = $this->auth->user['id'];
			$this->db->insert(T_CLIENTS, $data);
			$data['id'] = $this->db->insert_id();
		} else {
			$id = $data['id'];
			unset($data['id']);
			$this->db->where('id', $id)->update(T_CLIENTS, $data);
			$data['id'] = $id;
		}

		if (isset($all_data['data']['cost'])) {
			$this->save_data($data['id'], 'cost', $all_data['data']['cost']);
		}

		if (isset($all_data['data']['duration'])) {
			$this->save_data($data['id'], 'duration', $all_data['data']['duration']);
		}
		if (isset($all_data['data']['cost'])) {
			$this->save_data($data['id'], 'cost', $all_data['data']['cost']);
		}

		if (isset($all_data['data']['tax'])) {
			$this->save_data($data['id'], 'tax', $all_data['data']['tax']);
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
			$data = $user['data'];
			$data[$key] = $value;
			$this->db->where('id', $id)->update(T_CLIENTS, array('data' => json_encode_fixed($data)));
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