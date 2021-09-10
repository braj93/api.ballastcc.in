<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Crm_model extends CI_Model {
	private $_batchImport;
	public function __construct() {
		// Call the CI_Model constructor
		parent::__construct();
	}

	public function setBatchImport($batchImport) {
		$this->_batchImport = $batchImport;
	}

	// save data
	public function importData() {
		$data = $this->_batchImport;
		$this->db->insert_batch('crm_contact', $data);
	}

	public function add_crm_contact($added_by, $source_type, $crm_contact_name, $crm_contact_email, $crm_contact_phone, $crm_contact_street, $crm_contact_city, $crm_contact_state, $crm_contact_zipcode, $note, $birthday_month, $birthday_year, $more_info) {
		$this->db->insert('crm_contact', [
			"crm_contact_guid" => get_guid(),
			'added_by' => $added_by,
			'source_type' => $source_type,
			'crm_contact_name' => $crm_contact_name,
			'crm_contact_email' => $crm_contact_email,
			'crm_contact_phone' => $crm_contact_phone,
			'crm_contact_street' => $crm_contact_street,
			'crm_contact_city' => $crm_contact_city,
			'crm_contact_state' => $crm_contact_state,
			'crm_contact_zipcode' => $crm_contact_zipcode,
			'note' => $note,
			'birthday_month' => $birthday_month,
			'birthday_year' => $birthday_year,
			'more_info' => $more_info,
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		]);
		return $this->db->insert_id();
	}

	public function add_crm($added_by, $source_type, $crm_contact_name, $crm_contact_email, $crm_contact_phone, $crm_contact_street, $crm_contact_city, $crm_contact_state, $crm_contact_zipcode, $note) {
		$this->db->insert('crm_contact', [
			"crm_contact_guid" => get_guid(),
			'added_by' => $added_by,
			'source_type' => $source_type,
			'crm_contact_name' => $crm_contact_name,
			'crm_contact_email' => $crm_contact_email,
			'crm_contact_phone' => $crm_contact_phone,
			'crm_contact_street' => $crm_contact_street,
			'crm_contact_city' => $crm_contact_city,
			'crm_contact_state' => $crm_contact_state,
			'crm_contact_zipcode' => $crm_contact_zipcode,
			'note' => $note,
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		]);
		return $this->db->insert_id();
	}

	public function edit_crm_contact($crm_contact_id, $source_type, $crm_contact_name, $crm_contact_email, $crm_contact_phone, $crm_contact_street, $crm_contact_city, $crm_contact_state, $crm_contact_zipcode, $note, $birthday_month, $birthday_year, $more_info) {
		$data = [
			'source_type' => $source_type,
			'crm_contact_name' => $crm_contact_name,
			'crm_contact_email' => $crm_contact_email,
			'crm_contact_phone' => $crm_contact_phone,
			'crm_contact_street' => $crm_contact_street,
			'crm_contact_city' => $crm_contact_city,
			'crm_contact_state' => $crm_contact_state,
			'crm_contact_zipcode' => $crm_contact_zipcode,
			'note' => $note,
			'birthday_month' => $birthday_month,
			'birthday_year' => $birthday_year,
			'more_info' => $more_info,
			"updated_at" => DATETIME,
		];

		$this->db->update('crm_contact', $data, array(
			'crm_contact_id' => $crm_contact_id,
		));
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
	}

	public function get_details_by_id($crm_contact_id) {
		$this->db->select('cc.crm_contact_guid, cc.crm_contact_name, cc.crm_contact_email');
		$this->db->select('IFNULL(u.first_name,"") AS name', FALSE);
		$this->db->select('IFNULL(u.user_guid,"") AS user_guid', FALSE);
		$this->db->select('IFNULL(sm.name,"") AS source_type', FALSE);
		$this->db->select('IFNULL(cc.crm_contact_phone,"") AS crm_contact_phone', FALSE);
		$this->db->select('IFNULL(cc.crm_contact_street,"") AS crm_contact_street', FALSE);
		$this->db->select('IFNULL(cc.crm_contact_city,"") AS crm_contact_city', FALSE);
		$this->db->select('IFNULL(cc.crm_contact_state,"") AS crm_contact_state_id', FALSE);
		$this->db->select('IFNULL(s.state,"") AS crm_contact_state', FALSE);
		$this->db->select('IFNULL(cc.crm_contact_zipcode,"") AS crm_contact_zipcode', FALSE);
		$this->db->select('IFNULL(cc.note,"") AS note', FALSE);
		$this->db->select('IFNULL(cc.birthday_month,"") AS birthday_month', FALSE);
		$this->db->select('IFNULL(cc.birthday_year,"") AS birthday_year', FALSE);
		$this->db->select('IFNULL(cc.more_info,"") AS more_info', FALSE);
		$this->db->select('IFNULL(cc.created_at,"") AS created_at', FALSE);
		$this->db->select('IFNULL(cc.updated_at,"") AS updated_at', FALSE);
		$this->db->from('crm_contact AS cc');
		$this->db->join('users AS u', 'u.user_id = cc.added_by', 'LEFT');
		$this->db->join('states AS s', 's.state_id = cc.crm_contact_state', 'LEFT');
		$this->db->join('source_master AS sm', 'sm.source_id = cc.source_type', 'LEFT');
		$this->db->where('crm_contact_id', $crm_contact_id);
		$query = $this->db->get();
		$reuslt = $query->row_array();
		$reuslt['birthday_year'] = (int)$reuslt['birthday_year'];
		return $reuslt;
	}

	public function list($user_id, $keyword = '', $limit = 0, $offset = 0, $column_name, $order_by) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('IFNULL(cc.crm_contact_guid,"") AS crm_contact_id', FALSE);
			$this->db->select('IFNULL(sm.name,"") AS source_type', FALSE);
			$this->db->select('IFNULL(cc.crm_contact_name,"") AS crm_contact_name', FALSE);
			$this->db->select('IFNULL(cc.crm_contact_email,"") AS crm_contact_email', FALSE);
			$this->db->select('IFNULL(cc.crm_contact_phone,"") AS crm_contact_phone', FALSE);
			$this->db->select('IFNULL(cc.crm_contact_street,"") AS crm_contact_street', FALSE);
			$this->db->select('IFNULL(cc.crm_contact_city,"") AS crm_contact_city', FALSE);
			$this->db->select('IFNULL(s.state,"") AS crm_contact_state', FALSE);
			$this->db->select('IFNULL(cc.crm_contact_zipcode,"") AS crm_contact_zipcode', FALSE);
			$this->db->select('IFNULL(cc.note,"") AS note', FALSE);
			$this->db->select('IFNULL(cc.created_at,"") AS created_at', FALSE);
			$this->db->select('IFNULL(cc.updated_at,"") AS updated_at', FALSE);
		} else {
			$this->db->select('COUNT(cc.crm_contact_id) as count', FALSE);
		}
		$this->db->from('crm_contact AS cc');
		$this->db->join('states AS s', 's.state_id = cc.crm_contact_state', 'LEFT');
		$this->db->join('source_master AS sm', 'sm.source_id = cc.source_type', 'LEFT');
		$this->db->where('cc.added_by', $user_id);
		$this->db->order_by('cc.created_at', 'desc');

		// if (!empty($filterBy)) {
		// 	$this->db->like('u.status', $filterBy);
		// }
		// echo $user_type;die();
		// $this->db->where('u.user_type', $user_type);
		// $this->db->where('u.status!= "DELETED"');

		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('cc.crm_contact_name', $keyword, 'both');
			$this->db->or_like('cc.crm_contact_email', $keyword, 'both');
			$this->db->group_end();
		}

		// if (!empty($filter_type)) {
		// 	$this->db->where('u.device_type_id', $filter_type);
		// }

		if (($column_name !== '') && ($order_by !== '')) {
			$this->db->order_by('cc.' . $column_name, $order_by);
		}
		$query = $this->db->get();
		$results = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				// $list = [];
				// foreach ($results as $key => $value) {
				// 	$list[$key]['user_id'] = $value['user_guid'];
				// 	$list[$key]['user_type'] = $value['user_type'];
				// 	$list[$key]['user_sub_type'] = $value['user_sub_type'];
				// 	$list[$key]['name'] = $value['name'];
				// 	$list[$key]['business_name'] = $value['business_name'];
				// 	$list[$key]['email'] = $value['email'];
				// 	$list[$key]['customer_stripe_id'] = $value['customer_stripe_id'];
				// 	$list[$key]['last_login_at'] = $value['last_login_at'];
				// 	$list[$key]['created_at'] = $value['created_at'];
				// 	$list[$key]['status'] = $value['status'];
				// 	$list[$key]['package'] = $value['pricing_plan_id'] != 0 ? get_detail_by_id($value['pricing_plan_id'], 'plan', 'name') : "--";
				// }
				return $results;
			} else {
				return [];
			}

		} else {
			return $query->row()->count;
		}
	}

	public function crm_user_contacts($user_id) {
		$this->db->select('cc.crm_contact_guid, cc.crm_contact_name, cc.crm_contact_email');
		$this->db->select('IFNULL(u.first_name,"") AS added_by_name', FALSE);
		$this->db->select('IFNULL(cc.crm_contact_phone,"") AS crm_contact_phone', FALSE);
		$this->db->select('IFNULL(cc.crm_contact_street,"") AS crm_contact_street', FALSE);
		$this->db->select('IFNULL(cc.crm_contact_city,"") AS crm_contact_city', FALSE);
		$this->db->select('IFNULL(cc.crm_contact_state,"") AS crm_contact_state', FALSE);
		$this->db->select('IFNULL(cc.crm_contact_zipcode,"") AS crm_contact_zipcode', FALSE);
		$this->db->select('IFNULL(cc.note,"") AS note', FALSE);
		$this->db->select('IFNULL(cc.created_at,"") AS created_at', FALSE);
		$this->db->select('IFNULL(s.state,"") AS state', FALSE);
		$this->db->select('IFNULL(sm.name,"") AS source', FALSE);
		$this->db->from('crm_contact AS cc');
		$this->db->join('users AS u', 'u.user_id = cc.added_by', 'LEFT');
		$this->db->join('states AS s', 's.state_id = cc.crm_contact_state', 'LEFT');
		$this->db->join('source_master AS sm', 'sm.source_id = cc.source_type', 'LEFT');
		$this->db->where('cc.added_by', $user_id);
		$this->db->order_by('cc.created_at', 'DESC');
		$query = $this->db->get();
		$results = $query->result_array();
		return $results;
	}

	public function crm_all_contacts() {
		$this->db->select('cc.crm_contact_guid, cc.crm_contact_name, cc.crm_contact_email');
		$this->db->select('IFNULL(u.first_name,"") AS added_by_name', FALSE);
		$this->db->select('IFNULL(cc.crm_contact_phone,"") AS crm_contact_phone', FALSE);
		$this->db->select('IFNULL(cc.crm_contact_street,"") AS crm_contact_street', FALSE);
		$this->db->select('IFNULL(cc.crm_contact_city,"") AS crm_contact_city', FALSE);
		$this->db->select('IFNULL(cc.crm_contact_state,"") AS crm_contact_state', FALSE);
		$this->db->select('IFNULL(cc.crm_contact_zipcode,"") AS crm_contact_zipcode', FALSE);
		$this->db->select('IFNULL(cc.note,"") AS note', FALSE);
		$this->db->select('IFNULL(cc.created_at,"") AS created_at', FALSE);
		$this->db->select('IFNULL(s.state,"") AS state', FALSE);
		$this->db->select('IFNULL(sm.name,"") AS source', FALSE);
		$this->db->from('crm_contact AS cc');
		$this->db->join('users AS u', 'u.user_id = cc.added_by', 'LEFT');
		$this->db->join('states AS s', 's.state_id = cc.crm_contact_state', 'LEFT');
		$this->db->join('source_master AS sm', 'sm.source_id = cc.source_type', 'LEFT');
		$this->db->order_by('cc.created_at', 'DESC');
		$query = $this->db->get();
		$results = $query->result_array();
		return $results;
	}

	public function crm_contacts($user_id, $keyword = '', $limit = 0, $offset = 0, $column_name, $order_by) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('cc.crm_contact_guid, cc.source_type, cc.crm_contact_name, cc.crm_contact_email');
			$this->db->select('IFNULL(u.first_name,"") AS name', FALSE);
			$this->db->select('IFNULL(u.user_id,"") AS added_by', FALSE);
			$this->db->select('IFNULL(cc.crm_contact_phone,"") AS crm_contact_phone', FALSE);
			$this->db->select('IFNULL(cc.crm_contact_street,"") AS crm_contact_street', FALSE);
			$this->db->select('IFNULL(cc.crm_contact_city,"") AS crm_contact_city', FALSE);
			$this->db->select('IFNULL(cc.crm_contact_state,"") AS crm_contact_state', FALSE);
			$this->db->select('IFNULL(cc.crm_contact_zipcode,"") AS crm_contact_zipcode', FALSE);
			$this->db->select('IFNULL(cc.note,"") AS note', FALSE);
			$this->db->select('IFNULL(cc.created_at,"") AS created_at', FALSE);
			$this->db->select('IFNULL(cc.updated_at,"") AS updated_at', FALSE);
		} else {
			$this->db->select('COUNT(cc.crm_contact_id) as count', FALSE);
		}
		$this->db->from('crm_contact AS cc');
		$this->db->join('users AS u', 'cc.added_by = u.user_id', 'LEFT');
		$this->db->order_by('cc.created_at', 'desc');

		// if (!empty($filterBy)) {
		// 	$this->db->like('u.status', $filterBy);
		// }
		// $this->db->where('u.user_type', $user_type);
		// $this->db->where('u.status!= "DELETED"');

		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('cc.crm_contact_name', $keyword, 'both');
			$this->db->or_like('cc.crm_contact_email', $keyword, 'both');
			$this->db->group_end();
		}

		// if (!empty($filter_type)) {
		// 	$this->db->where('u.device_type_id', $filter_type);
		// }

		if (($column_name !== '') && ($order_by !== '')) {
			$this->db->order_by('cc.' . $column_name, $order_by);
		}
		$query = $this->db->get();
		$results = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				return $results;
			} else {
				return [];
			}

		} else {
			return $query->row()->count;
		}
	}

	public function add_notes($user_id, $crm_contact_id, $note) {
		$this->db->insert('notes', [
			"note_guid" => get_guid(),
			'added_by' => $user_id,
			'contact_id' => $crm_contact_id,
			'note' => $note,
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		]);
		return $this->db->insert_id();
	}

	public function notes_list($user_id, $keyword = '', $limit = 0, $offset = 0, $column_name, $order_by, $crm_contact_id) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('IFNULL(n.note_guid,"") AS note_id', FALSE);
			$this->db->select('IFNULL(u.first_name,"") AS name', FALSE);
			$this->db->select('IFNULL(n.note,"") AS note', FALSE);
			$this->db->select('IFNULL(n.created_at,"") AS created_at', FALSE);
			$this->db->select('IFNULL(n.updated_at,"") AS updated_at', FALSE);
		} else {
			$this->db->select('COUNT(n.note_id) as count', FALSE);
		}
		$this->db->from('notes AS n');
		$this->db->join('users AS u', 'u.user_id = n.added_by', 'LEFT');
		$this->db->where('n.contact_id', $crm_contact_id);
		$this->db->order_by('n.created_at', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				return $results;
			} else {
				return [];
			}

		} else {
			return $query->row()->count;
		}
	}

	public function add_logs($user_id, $crm_contact_id, $type, $campaign_name = NULL, $message = NULL) {
		$this->db->insert('crm_logs', [
			"crm_log_guid" => get_guid(),
			'added_by' => $user_id,
			'contact_id' => $crm_contact_id,
			'type' => $type,
			'campaign_name' => $campaign_name,
			'message' => $message,
			"created_at" => DATETIME,
		]);
		return $this->db->insert_id();
	}

	public function logs_list($user_id, $keyword = '', $limit = 0, $offset = 0, $column_name, $order_by, $crm_contact_id) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('IFNULL(cl.crm_log_guid,"") AS log_id', FALSE);
			$this->db->select('IFNULL(cl.type,"") AS type', FALSE);
			$this->db->select('IFNULL(cl.campaign_name,"") AS campaign_name', FALSE);
			$this->db->select('IFNULL(cl.message,"") AS message', FALSE);
			$this->db->select('IFNULL(cl.created_at,"") AS created_at', FALSE);
		} else {
			$this->db->select('COUNT(cl.crm_log_id) as count', FALSE);
		}
		$this->db->from('crm_logs AS cl');
		$this->db->where('cl.contact_id', $crm_contact_id);
		$this->db->order_by('cl.created_at', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				return $results;
			} else {
				return [];
			}

		} else {
			return $query->row()->count;
		}
	}

	public function get_source_list($user_id) {
		$this->db->select('IFNULL(sm.source_guid,"") AS source_guid', FALSE);
		$this->db->select('IFNULL(sm.name,"") AS name', FALSE);
		$this->db->from('source_master AS sm');
		$this->db->where_in('sm.added_by', [0, $user_id]);
		$this->db->order_by('sm.added_by', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		return $results;
	}

	

	public function get_source_type_id($source_type, $user_id)
    {
		$source_type = isset($source_type) ? trim($source_type) : "";
		if(!empty($source_type))   
		{
			$source_id = $this->is_source_type_exist($source_type, $user_id);                    
			if (empty($source_id)){
				$this->db->insert('source_master', [
					"source_guid" => get_guid(),
					'name' => $source_type,
					'added_by' => $user_id,
					"created_at" => DATETIME,
					"updated_at" => DATETIME,
				]);
				$source_id = $this->db->insert_id();
				return $source_id;
			}else{
				return $source_id;
			}
		} 
	}
	
	public function is_source_type_exist($source_type, $user_id){
		$source_id = 0;
        $this->db->select('source_id');
        $this->db->from("source_master");
        // $this->db->where('name', $source_type);
		// $this->db->where('LOWER(name)', $source_type, FALSE);
		$this->db->where('name like binary', $source_type);
        $this->db->where('added_by', $user_id);
        $query = $this->db->get();
        if ($query->num_rows()) 
        {
            $source_id = $query->row()->source_id;
        }
        return $source_id; 
	}

	public function getStateId($name){
		$state_id = NULL;
        $this->db->select('state_id');
        $this->db->from("states");
		$this->db->where('state', $name);
        $query = $this->db->get();
        if ($query->num_rows()) 
        {
            $state_id = $query->row()->state_id;
        }
        return $state_id; 
	}
	

}
