<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('url');  
	}
	
	public function login() {
		log_message('debug','begin login');
		$this->output->enable_profiler(FALSE);
		$this->stash['return'] = $this->input->get('return');
		$this->stash['error'] = false;

		if ( $this->input->post('login') ) {
			$login = $this->input->post('login', TRUE);
			$password = $this->input->post('pass', TRUE);

				$users = $this->db->select('*')->from('users')->where(array('login' => $login, 'password' => md5($password)))->get()->result_array();

			if (count($users) == 1)	 {
			    $user = $users[0];
				$this->auth->set_authenticated($user['id']);
				if( $this->stash['return'] == ''){
					header('Location: /dashboard/');
					return;
				}
				if ($this->input->post('json') or $this->input->get('json')) {
					$this->stash['json'] = array('status'=>1, 'redirect'=>$this->stash['return'] );
				} else {
					redirect( $this->stash['return'] );
				}
			} else {
				$this->auth->unset_authenticated();
				$this->stash['login'] = $this->input->post('login');
				$this->stash['pass'] = $this->input->post('pass');
				$this->stash['error'] = true;
				if(true || $this->input->post('json') ){
					$this->stash['json'] = array('status'=>0 );
				}
			}
		}
		
		$this->load->view('authentication/login', $this->stash);
	}
	
	public function logout() {
		$this->auth->unset_authenticated();
		redirect('login');
	}
	
	private function check_auth_level(){
		$ip = ( !isset($_SERVER['REMOTE_ADDR1']) ) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR1'];
		if( in_array( $ip, $this->config->item('inner_ip')) ){
			return true;
		}
		return false;
	}
}