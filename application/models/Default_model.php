<?php
class default_model extends CI_Model {
    private $ci;
	function __construct(){
		parent::__construct();
		$this->ci =& get_instance();
		if (is_object($this->auth->data)) {
			//$this->check_set_lang();
			$lang = $this->auth->data->lang;
			if (!is_null($lang)) {
				set_default_lang($lang);				
				$this->ci->stash['lang'] = $lang;
			} else {
				set_default_lang('en');
                $this->ci->stash['lang'] = $lang;
			}
		}
		
		$this->ci->stash['config'] =& $this->ci->config;

		//$this->ci->stash['version'] = $this->ci->stash['config']->config['version'];
		
		if( is_array( $this->auth->user ) ){
			$this->ci->stash['auth_user'] 	= $this->auth->user;
		}
		$this->ci->stash['js'] = array();

		date_default_timezone_set('Europe/Moscow');
	}
	private function check_set_lang() {
		if ($this->input->get('lang')) {
			$l = $this->input->get('lang');
			$this->auth->data->lang = $l;
			$this->auth->data->save();
			$u = uri_string();
			$rt = preg_replace('/\?lang=.{2}/','',$u);
			redirect($rt);
			if (false) {

			}
		}
	}
	


}