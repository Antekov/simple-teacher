<?php
/**
* class Vendor_model
* 
* CRUD vendors
* create date: 05.04.2013
*
*/	
class Lesson_model extends CI_Model
{
	static $fields = array('id', 'client_id', 'place', 'duration', 'status', 'start_date', 'cost', 'data');
	
	const S_DRAFT		= 0;
	const S_ACTIVE		= 1;
	const S_COMPLETE	= 2;
	const S_CANCELED	= 3;
	const S_PAUSED		= 4;
	
	static $statuses = array(
		0 => 'Необработано',
		1 => 'Назначено',
		2 => 'Проведено',
		3 => 'Отменено',
		4 => 'Не оплачено'
	);
	
	static $status_changes = array(
		lesson_model::S_DRAFT => array(
			lesson_model::S_ACTIVE => array(),
			lesson_model::S_CANCELED => array(),
			lesson_model::S_COMPLETE => array(),
			lesson_model::S_PAUSED => array(),
		),
		lesson_model::S_ACTIVE => array(
			lesson_model::S_CANCELED => array(),
			lesson_model::S_COMPLETE => array(),
			lesson_model::S_PAUSED => array(),
		),
		
		lesson_model::S_CANCELED => array(
			lesson_model::S_ACTIVE => array(),
		),
		lesson_model::S_COMPLETE => array(
			lesson_model::S_ACTIVE => array(),
			lesson_model::S_COMPLETE => array(),
		),
		
		lesson_model::S_PAUSED => array(
			lesson_model::S_COMPLETE => array(),
		),
	);
	
	var $default_data = array();

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */		
	function __construct() {
		parent::__construct();
		$this->load->model('client_model');
		
		$this->default_data = array();
		
		//$this->load->model('order_log_model');
	}
	
	/**
	 * Список всех брендов, удовлетворяющих фильтру
	 *
	 * @access public
	 *
	 * @return array (vendors list)
	 */
	public function get($data = array()) {
		if (isset($data['id']))		{ $this->db->where_in('o.id',(array) $data['id']); }
		{ $this->db->where_in('o.user_id',(array) $this->auth->user['id']); }
		if (isset($data['status'])) { $this->db->where_in('o.status', (array) $data['status']); }
		if (isset($data['date_from'])) { $this->db->where('o.start_date >= ', $data['date_from']); }
		if (isset($data['date_to'])) { $this->db->where('o.start_date <= ', $data['date_to']); }
		if (isset($data['client_id'])) { $this->db->where('o.client_id', $data['client_id']); }

		if (isset($data['order_by'])) { $this->db->order_by($data['order_by']); } else { $this->db->order_by('o.start_date ASC'); }
		
		$this->db
			->select('o.*, lol.last_update AS status_date, lol.data AS status_data, COUNT(DISTINCT ol.id) AS comments_count, c.name, c.description AS client_description, c.phones, c.data AS client_data')
			->from(T_LESSONS.' AS o')
			->join(T_CLIENTS.' AS c', 'c.id = o.client_id', 'LEFT')
			->join(T_LESSONS_LOG.' AS ol', 'ol.lesson_id = o.id AND ol.type = 1', 'LEFT')
			->join(T_LESSONS_LOG.' AS lol', 'lol.lesson_id = o.id AND lol.type = 2 AND lol.comment LIKE \'status-%\'', 'LEFT')
			->group_by('o.id');
			
		$orders = array_to_assoc($this->db->get()->result_array(), 'id');
		
		foreach ($orders as $id => $order) {
			$orders[$id]['data'] = (array) json_decode($order['data'], true);
			$orders[$id]['client_data'] = (array) json_decode($order['client_data'], true);
			if (empty($orders[$id]['data'])) {
				$orders[$id]['data'] = $this->default_data;
			}

			$orders[$id]['client_cost'] = (!empty($orders[$id]['client_data']['cost']) ? $orders[$id]['client_data']['cost'] : 0);
			$orders[$id]['client_duration'] = (!empty($orders[$id]['client_data']['duration']) ? $orders[$id]['client_data']['duration'] : 60);
			
			//$orders[$id]['items'] = (array) json_decode($order['items'], true);

			/*
			if (isset($orders[$id]['data']['delivery_id']) && !empty($data['only_delivery']) && $orders[$id]['data']['delivery_id'] == 2) {
				unset($orders[$id]);
				continue;
			}
			/*
			if ($orders[$id]['status'] == lesson_model::S_ACTIVE && !empty($orders[$id]['ready_date']) && time() > strtotime($orders[$id]['ready_date'])) {
				$orders[$id]['is_late'] = true;
			} else {
				$orders[$id]['is_late'] = false;
			}
			 */
		}
		//echo $this->db->last_query();
		//exit;
		return $orders;
	}
	
	public function get_by_id($id) {
		$orders = $this->get(array('id' => $id));
		$order = (!empty($orders[$id]) ? $orders[$id] : array());
		
		if (!empty($order)) {
			
			
		}
		return $order;
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
		if (empty($data['id'])) {


			$client = $this->client_model->get_by_id($data['client_id']);
			$data['place'] = $client['place'];
			$data['client_cost'] = $client['data']['cost'];
			$data['client_duration'] = $client['data']['duration'];
		}
		$old_order = $this->get_by_id($data['id']);
		$data['data'] = (!empty($old_order) ? $old_order['data'] : $this->default_data);
		$data['cost'] = floatval(round($data['client_cost'] * floatval($data['duration']))/$data['client_duration']);
		

		
		foreach ($data as $field => $value) {
			if (!in_array($field, $this::$fields)) {
				unset($data[$field]);
			}
		}

		$data['data'] = json_encode($data['data']);
		
		if (empty($data['id'])) {
			$data['user_id'] = $this->auth->user['id'];
			$this->db->insert(T_LESSONS, $data);
			$data['id'] = $this->db->insert_id();
		} else {
			$id = $data['id'];
			unset($data['id']);
			$this->db->where('id', $id)->update(T_LESSONS, $data);
			$data['id'] = $id;
		}
		
		$order = $this->db->from(T_LESSONS)->where('id', $data['id'])->get()->row_array();
		
		if ($order['status'] == lesson_model::S_DRAFT) {
			/*
			 *
			 if (!empty($order['delivery_date']) && $order['delivery_date'] < date('Y-m-d 00:00:00', time()-24*3600)) {
				$this->status($order['id'], lesson_model::S_COMPLETE);
			}
			*/
		}
		
		$order = $this->get_by_id($data['id']);
		
		if (!empty($old_order)) {
			$order_id = $data['id'];
			
			
		}
		
		return $data;
	}
	
	public function status($id, $status) {
		$res = array(
			'status' => -1
		);
		
		$order = $this->get_by_id($id);
		
		if (!empty($order['id'])) {
			$old_status = $order['status'];
			
			if (isset(lesson_model::$status_changes[$order['status']][$status])) {
				$this->db->where('id', $id)->update(T_LESSONS, array('status' => $status));
				
				$log_data = array('old' => $old_status, 'new' => $status);
				//$this->order_log_model->log($id, order_log_model::EV_STATUS.$status, $log_data);

				if ($old_status == lesson_model::S_COMPLETE && $status != lesson_model::S_COMPLETE) {
					$this->db->where('lesson_id', $order['id'])->delete(T_FINANCES);
				}
				
				if ($status == lesson_model::S_COMPLETE) {
					//$this->finance_model->order($id);
					$this->db->where('lesson_id', $order['id'])->delete(T_FINANCES);
					$this->db->set(array(
						'user_id' => $order['user_id'],
						'type' => 1,
						'math' => 1,
						'amount' => $order['cost'],
						'description' => 'Оплата занятия #'.$order['id'],
						'lesson_id' => $order['id'],
						'client_id' => $order['client_id'],
						'payment_date' => date('Y-m-d H:i:s')
					))->insert(T_FINANCES);
				}


				
				$res['status'] = 1;
			} else {
				
			}
		} else {
			$res['message'] = 'Заказ не найден';
			
		}
		
		return $res;
	}
	

	public function get_deliveries() {
		return array(
			0 => array('name' => 'По городу', 'price' => 50),
			1 => array('name' => 'Бесплатная', 'price' => 0),
			2 => array('name' => 'Самовывоз', 'price' => 0),
			3 => array('name' => 'По Михайловску', 'price' => 30)
		);
	}
	
	public function delete($id) {
		$this->db->where('id', $id)->delete(T_LESSONS);
		$this->db->where('lesson_id', $id)->delete(T_FINANCES);
	}
	

    static function sort_items($a, $b) {
        return (intval($a['id']) > intval($b['id']) ? 1 :
            (intval($a['id']) < intval($b['id']) ? -1 :
                (floatval($a['count']) > floatval($b['count']) ? -1 :
                    (floatval($a['count']) < floatval($b['count']) ? 1 :
                        0))));
    }
}
?>