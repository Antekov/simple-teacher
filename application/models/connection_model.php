<?
class connection_model extends CI_Model {
	function __construct(){
		parent::__construct();
	}

	function get($data = array()) {
		if (isset($data['start']) && isset($data['limit'])) {
			$this->db->limit($data['limit'],$data['start']);
		}

		if (isset($data['id'])) {
			$this->db->where('id', intval($data['id']));
		}

		return $this->db->get('connection')->result_array();
	}


	function total($data = array()) {
		return $this->db->count_all_results('connection');
	}
}