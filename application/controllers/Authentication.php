<?php
defined('BASEPATH') OR exit('No direct script access allowed');



/* Google App Client Id */
define('CLIENT_ID', '232716993254-eov94nsibthjm2b7flk2bljpv2716l7p.apps.googleusercontent.com');

/* Google App Client Secret */
define('CLIENT_SECRET', 'PoYvVeaWivnM1MwhA8XXfD6D');

/* Google App Redirect Url */

define('CLIENT_REDIRECT_URL', 'http://'.$_SERVER['SERVER_NAME'].'/authentication/gauth/');

// $client_id, $redirect_uri & $client_secret come from the settings
// $code is the code passed to the redirect url
function GetAccessToken($client_id, $redirect_uri, $client_secret, $code) {	
	$url = 'https://www.googleapis.com/oauth2/v4/token';			

	$curlPost = 'client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code='. $code . '&grant_type=authorization_code';
	$ch = curl_init();		
	curl_setopt($ch, CURLOPT_URL, $url);		
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
	curl_setopt($ch, CURLOPT_POST, 1);		
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);	
	$data = json_decode(curl_exec($ch), true);
	$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);		
	if($http_code != 200) 
		throw new Exception('Error : Failed to receieve access token');
	
	return $data;
}

// $access_token is the access token you got earlier
function GetUserProfileInfo($access_token) {	
	$url = 'https://www.googleapis.com/oauth2/v2/userinfo?fields=name,email,gender,id,picture,verified_email';	
	
	$ch = curl_init();		
	curl_setopt($ch, CURLOPT_URL, $url);		
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
	$data = json_decode(curl_exec($ch), true);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);		
	if($http_code != 200) 
		throw new Exception('Error : Failed to get user information');
		
	return $data;
}

class Authentication extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('url');  
	}

	public function gauth() {

		echo "GAUTH";
		if ( $this->input->get('code') ) {
			// Google passes a parameter 'code' in the Redirect Url
			try {
				// Get the access token 
				$data = GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL, CLIENT_SECRET, $this->input->get('code', TRUE));

				// Access Token
				$access_token = $data['access_token'];
				
				// Get user information
				$user_info = GetUserProfileInfo($access_token);

				$users = $this->db->select('*')->from('users')->where(array('email' => $user_info['email']))->get()->result_array();

				if (count($users) == 0)	 {
					$this->db->insert('users', array(
						'login' => $user_info['email'],
						'email' => $user_info['email'],
						'name' => $user_info['name'],
						'password' => '',
						'parent_id' => 1
					));
				}

				$users = $this->db->select('*')->from('users')->where(array('email' => $user_info['email']))->get()->result_array();

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
			catch(Exception $e) {
				echo $e->getMessage();
				exit();
			}	
		}

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
	
	public function registration() {
		$this->output->enable_profiler(FALSE);
		$this->stash['return'] = $this->input->get('return');
		$this->stash['error'] = false;

		if ( $this->input->post('login') ) {
			$login = $this->input->post('login', TRUE);
			$password = $this->input->post('pass', TRUE);
			$password2 = $this->input->post('pass2', TRUE);

			$users = $this->db->select('*')->from('users')->where(array('login' => $login))->get()->result_array();

			if (count($users) > 0)	 {
				$this->auth->unset_authenticated();
				$this->stash['login'] = $this->input->post('login');
				$this->stash['pass'] = $this->input->post('pass');
				$this->stash['error'] = true;
				if(true || $this->input->post('json') ){
					$this->stash['json'] = array('status'=>0 );
				}
			    
			} else {
				$this->db->insert('users', array(
					'login' => $login,
					'password' => md5($password),
					'parent_id' => 1
				));

				$users = $this->db->select('*')->from('users')->where(array('login' => $login, 'password' => md5($password)))->get()->result_array();
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
			}
		}
		
		$this->load->view('authentication/registration', $this->stash);
	}

	private function check_auth_level(){
		$ip = ( !isset($_SERVER['REMOTE_ADDR1']) ) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR1'];
		if( in_array( $ip, $this->config->item('inner_ip')) ){
			return true;
		}
		return false;
	}
}