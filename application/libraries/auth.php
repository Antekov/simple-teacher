<?php

class Auth {
	private $ci;
	public $user;
	public $data;

	public function __construct() {
		$this->ci =& get_instance();
		$this->ci->config->load();
		$this->ci->load->helper('url');

	}
	public function has_group($group) {
		return in_array($this->user['groups'],$group,true);
	}
	public function check_auth() {
		$uid = $this->ci->session->userdata('user_id');
		if ( $uid !== null ) {
			$this->user = $this->ci->db->get_where('users', array('id' => $uid) )->row_array();


			$this->user['data'] = json_decode($this->user['data'], true);
			/*
			$_grp = $this->ci->db->get_where('system_user_to_group', array('user_id' => $uid))->result_array();
			$groups = array();
			foreach ($_grp as $g) {
				$groups[] = $g['user_group_id'];
			}
			 
			 */
			//$this->user['groups'] = $groups;
			$this->data = new user_data($uid);
			if(!$this->ci->config->item('skip_acl')) {
				//$acl_access = $this->check_acl();
				$acl_access = true;	
				if( $acl_access ) {
					return;
				} else {
					if(!empty($this->user['home_url'])){
						redirect(uri_string());
					} else {
						redirect('/dashboard');
					}
					die(
						$this->ci->template->get('authentication/access_denied')
					);
					//show_error('acces denied');
				}
			} else {
				return;
			}
		}
		redirect( base_url().'/login');

	}

	public function check_acl() {
		if (true || in_array(1, $this->user['groups']) ) {
			return true; // Суперпользователю можно всё.
		}
		
		$re = $this->ci->db->query("SELECT ? LIKE CONCAT(m.url,'%') AS s FROM system_menu m
                                JOIN system_acl a ON 
                                ( m.id = a.menu_item AND a.user_group_id IN (".implode(', ',$this->user['groups'])."))  WHERE a.user_group_id  > 0",array('/'.uri_string()));		
		foreach ($re->result_array() as $r) {
			if ($r['s'] > 0) {
				return true;
			}
		}
		return false;

	}
	public function set_authenticated($uid) {

		$this->ci->session->set_userdata('user_id',$uid);
		//$this->ci->session->back_set($uid);
	}
	public function unset_authenticated() {
		$this->ci->session->unset_userdata('user_id');
	}
}

class user_data {
	private $data;
	private $up;
	private $te;
	private $ci;
	private $user_id;

	public function __construct($user_id) {
		$this->user_id = $user_id;
		$this->ci =& get_instance();
		$re = $this->ci->db->get_where('users',array( 'id' => $user_id ))->row_array();
		$this->data = array();
		$this->up = false;
		$this->te = false;
		if (isset($re) && is_array($re) && isset($re['data'])) {
			$this->te = true;
			$this->data = json_decode($re['data'], true);
		}
	}
	public function __get($name) {
		if ( !is_null($this->data) and array_key_exists($name, $this->data)) {
			return $this->data[$name];
		}
		return null;
	}
	public function __set($name, $value)
	{
		$this->data[$name] = $value;
		$this->up = true;
	}
	public function save() {
		log_message('debug','save user_data start');
		if ($this->up) {
			$this->ci->db->update('users', array('data' => json_encode_fixed($this->data) ), array( 'id' => $this->user_id ));
			log_message('debug','save user_data');
		}
	}
}