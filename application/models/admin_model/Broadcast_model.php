<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Broadcast_model extends CI_Model {

	public $messages = array();

	public function __construct() {
		parent::__construct();

	}
	
	public function create_broadcast($user_id, $title, $message, $is_user_active, $is_user_inactive, $is_last_thirty_days_signed_up, $is_last_login, $last_login_from_date, $last_login_to_date, $is_agnecy, $agency, $is_non_agnecy, $non_agency, $broadcast_sent_type, $broadcast_sent_date, $broadcast_sent_time, $time_seconds, $is_agency_users, $is_agency_envitee_users, $is_individual_users, $scheduled_at) {
		$insert_array = [
			"broadcast_guid" => get_guid(),
			"user_id" => $user_id,
			"title" => $title,
			"message" => $message,
			"is_user_active" => $is_user_active,
			"is_user_inactive" => $is_user_inactive,
			"is_last_thirty_days_signed_up" => $is_last_thirty_days_signed_up,
			"is_last_login" => $is_last_login,
			"last_login_from_date" => $last_login_from_date,
			"last_login_to_date" => $last_login_to_date,
			"is_agnecy" => $is_agnecy,
			"agency" => $agency,
			"is_non_agnecy" => $is_non_agnecy,
			"non_agency" => $non_agency,
			"broadcast_sent_type" => $broadcast_sent_type,
			"broadcast_sent_date" => $broadcast_sent_date,
			"broadcast_sent_time" => $broadcast_sent_time,
			"time_seconds" => $time_seconds,
			"is_agency_users" => $is_agency_users,
			"is_agency_envitee_users" => $is_agency_envitee_users,
			"is_individual_users" => $is_individual_users,
			"scheduled_at" => $scheduled_at,
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		];
		$this->db->insert('broadcast', $insert_array);
		$broadcast_id = $this->db->insert_id();
		return $broadcast_id;
	}

	public function update_broadcast($broadcast_id, $title, $message, $is_user_active, $is_user_inactive, $is_last_thirty_days_signed_up, $is_last_login, $last_login_from_date, $last_login_to_date, $is_agnecy, $agency, $is_non_agnecy, $non_agency, $broadcast_sent_type, $broadcast_sent_date, $broadcast_sent_time, $time_seconds, $is_agency_users, $is_agency_envitee_users, $is_individual_users, $scheduled_at) {
		$update_array = [
			"title" => $title,
			"message" => $message,
			"is_user_active" => $is_user_active,
			"is_user_inactive" => $is_user_inactive,
			"is_last_thirty_days_signed_up" => $is_last_thirty_days_signed_up,
			"is_last_login" => $is_last_login,
			"last_login_from_date" => $last_login_from_date,
			"last_login_to_date" => $last_login_to_date,
			"is_agnecy" => $is_agnecy,
			"agency" => $agency,
			"is_non_agnecy" => $is_non_agnecy,
			"non_agency" => $non_agency,
			"broadcast_sent_type" => $broadcast_sent_type,
			"broadcast_sent_date" => $broadcast_sent_date,
			"broadcast_sent_time" => $broadcast_sent_time,
			"time_seconds" => $time_seconds,
			"is_agency_users" => $is_agency_users,
			"is_agency_envitee_users" => $is_agency_envitee_users,
			"is_individual_users" => $is_individual_users,
			"scheduled_at" => $scheduled_at,
			"updated_at" => DATETIME,
		];
		$this->db->update('broadcast', $update_array, [
			'broadcast_id' => $broadcast_id,
		]);
		return true;
	}

	/* Send Message */
	function new_message($user_id, $subject, $body) {
		$boradcast = [
			'broadcast_guid' => get_guid(),
			'user_id' => $user_id,
			'subject' => $subject,
			'body' => $body,
			'created_at' => DATETIME,
		];
		$this->db->insert('broadcast', $boradcast);
		$message_thread_id = $this->db->insert_id();
	}

	/* List All broadcast */
	public function get_broadcast_list($limit = 0, $offset = 0, $searchKey = '', $filterBy = '', $sortField = '', $sortOrder = '') {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('b.broadcast_guid, b.title, b.message, b.date, b.time, b.send_to, b.created_at, b.updated_at');
		} else {
			$this->db->select('COUNT(b.broadcast_id) as count', FALSE);
		}
		$this->db->from('broadcast AS b');
		if (!empty($searchKey)) {
			$this->db->like('b.title', $searchKey, 'both');
		}
		if (!empty($filterBy)) {
			$this->db->like('b.status', $filterBy);
		}
		if (($sortField !== '') && ($sortOrder !== '')) {
			$this->db->order_by('b.' . $sortField, $sortOrder);
		}

		$query = $this->db->get();
		$list = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				foreach ($list as $key => $row) {
					$list[$key]['send_to'] = json_decode($row['send_to']);
				}
				return $list;
			} else {
				return [];
			}

		} else {
			return $query->row()->count;
		}
	}

	/* Count All broadcast */
	public function broadcast_list_count($user_id, $user_type, $client_id, $filter_by) {
		$this->db->select('u.name, u.user_guid as user_id, b.broadcast_guid, b.subject, b.body, b.created_at');
		$this->db->from('broadcast as b');
		$this->db->join('users as u', 'u.user_id = b.user_id', 'LEFT');

		if ($user_type == 'ADMIN') {
			$this->db->where('b.user_id', $user_id);
		}

		if ($user_type == 'CLIENT') {
			$this->db->where('b.user_id', $user_id);
			$this->db->or_where('b.user_id', 1);
		}

		if ($user_type == 'CUSTOMER') {
			$this->db->where('b.user_id', $client_id);
		}

		return $this->db->get()->num_rows();
	}

	/* Get user_id by user_guid */
	public function get_client_id_of_customer($customer_user_id) {
		$this->db->select("client_id");
		$this->db->where("user_id", $customer_user_id);
		return $this->db->get("customers")->row()->client_id;
	}

	// public function send_message($user_id, $title, $message, $date, $time) {
	// 	$boradcast = [
	// 		'broadcast_guid' => get_guid(),
	// 		'user_id' => $user_id,
	// 		'title' => $title,
	// 		'message' => $message,
	// 		'date' => $date,
	// 		'time' => $time,
	// 		'created_at' => DATETIME,
	// 		'updated_at' => DATETIME,
	// 	];
	// 	$this->db->insert('broadcast', $boradcast);
	// 	return $this->db->insert_id();
	// }

	public function broadcast_list($user_id, $limit, $offset, $column_name, $order_by) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('IFNULL(b.broadcast_id,"") AS broadcast_id', FALSE);
			$this->db->select('IFNULL(b.broadcast_guid,"") AS broadcast_guid', FALSE);
			$this->db->select('CONCAT(u.first_name, " ",u.last_name) AS name');
			$this->db->select('IFNULL(b.title,"") AS title', FALSE);
			$this->db->select('IFNULL(b.message,"") AS message', FALSE);
			$this->db->select('IFNULL(b.created_at,"") AS created_at', FALSE);
			$this->db->select('IFNULL(b.scheduled_at,"") AS scheduled_at', FALSE);
			$this->db->select('IFNULL(b.broadcast_sent_type,"") AS broadcast_sent_type', FALSE);
			$this->db->select('IFNULL(b.status,"") AS status', FALSE);
			$this->db->select('IFNULL(b.updated_at,"") AS updated_at', FALSE);
		} else {
			$this->db->select('COUNT(b.broadcast_id) as count', FALSE);
		}
		$this->db->from('broadcast AS b');
		$this->db->join('users AS u', 'u.user_id = b.user_id', 'LEFT');

		$this->db->order_by('b.created_at', 'desc');
		if (($column_name !== '') && ($order_by !== '')) {
			$this->db->order_by('b.' . $column_name, $order_by);
		}
		$query = $this->db->get();
		$results = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				$list = [];
				foreach ($results as $key => $value) {
					$list[$key]['broadcast_id'] = $value['broadcast_guid'];
					$list[$key]['name'] = $value['name'];
					$list[$key]['title'] = $value['title'];
					$list[$key]['message'] = $value['message'];
					$list[$key]['broadcast_seen_count'] = $this->get_broadcast_seen_count($value['broadcast_id']);
					$list[$key]['scheduled_at'] = $value['scheduled_at'];
					$list[$key]['broadcast_sent_type'] = $value['broadcast_sent_type'];
					$list[$key]['status'] = $value['status'];
					$list[$key]['created_at'] = $value['created_at'];
					$list[$key]['updated_at'] = $value['updated_at'];
				}
				return $list;
			} else {
				return [];
			}

		} else {
			return $query->row()->count;
		}
	}

	public function get_broadcast_seen_count($broadcast_id){
		
			$this->db->select('bsu.broadcast_sent_user_id as broadcast_sent_user_id', FALSE);
			$this->db->from('broadcast_sent_users AS bsu');
			$this->db->where('bsu.broadcast_id', $broadcast_id);
			$this->db->where('bsu.status', 'SEEN');
			$query = $this->db->get();
			$count = $query->num_rows();
			return $count;
	}

	public function get_user_list($is_user_active, $is_user_inactive, $is_last_thirty_days_signed_up, $is_last_login, $last_login_from_date, $last_login_to_date, $is_agnecy, $agency, $is_non_agnecy, $non_agency, $is_agency_users, $is_agency_envitee_users, $is_individual_users)
	{
		$this->db->select('DISTINCT(u.user_id)', FALSE);
		$this->db->select('u.user_type, u.user_sub_type');
		$this->db->select('IFNULL(u.user_id,"") AS user_id', FALSE);
		$this->db->select('IFNULL(u.user_guid,"") AS user_guid', FALSE);
		$this->db->select('CONCAT(u.first_name, " ",u.last_name) AS name');
		$this->db->select('IFNULL(u.email,"") AS email', FALSE);
		$this->db->select('IFNULL(u.status,"") AS status', FALSE);
		$this->db->select('IFNULL(u.created_at,"") AS created_at', FALSE);
		$this->db->select('IFNULL(u.login_at,"") AS login_at', FALSE);
		$this->db->select('IFNULL(pp.pricing_plan_guid,"") AS pricing_plan_guid', FALSE);
		$this->db->from('users AS u');
		$this->db->join('organization_members AS om', 'om.user_id = u.user_id', 'LEFT');
		$this->db->join('pricing_plans AS pp', 'pp.pricing_plan_id = om.pricing_plan_id', 'LEFT');
		$this->db->where('u.user_type !=', 'ADMIN');

		$user_sub_type = [];
		if($is_agency_users == 'YES'){
			$user_sub_type[] = 'AGENCY';
		}

		if($is_agency_envitee_users == 'YES'){
			$user_sub_type[] = 'AGENCY_USER';
		}

		if($is_individual_users == 'YES'){
			$user_sub_type[] = 'USER';
		}

		if(!empty($user_sub_type)){
			$this->db->where_in('u.user_sub_type', $user_sub_type);
		}

		$status = [];
		if($is_user_active == 'YES'){
			$status[] = 'ACTIVE';
		}

		if($is_user_inactive == 'YES'){
			$status[] = 'BLOCKED';
		}
		if(!empty($status)){
			$this->db->where_in('u.status', $status);
		}

		if($is_agnecy == 'YES' && $is_non_agnecy == 'NO'){
			$agency_array = explode(",",$agency);
			if (!empty($agency_array)) {
				$this->db->where_in('pp.pricing_plan_guid', $agency_array);
			}
		}
		
		if($is_non_agnecy == 'YES' && $is_agnecy == 'NO'){
			$non_agency_array = explode(",",$non_agency);
			if (!empty($non_agency_array)) {
				$this->db->where_in('pp.pricing_plan_guid', $non_agency_array);
			}
		}

		if($is_non_agnecy == 'YES' && $is_agnecy == 'YES'){
			$agency_array = explode(",",$non_agency);
			$non_agency_array = explode(",",$agency);
			$merge_array = array_merge($agency_array, $non_agency_array);
			if (!empty($merge_array)) {
				$this->db->where_in('pp.pricing_plan_guid', $merge_array);
			}
			
			// if (!empty($non_agency_array)) {
			// 	$this->db->or_where_in('pp.pricing_plan_guid', $non_agency_array);
			// }
		}

		if($is_last_thirty_days_signed_up == 'YES'){
			// #todo
			$current_date_time = gmdate("Y-m-d 00:00:00");
			$current = new DateTime($current_date_time, new DateTimeZone("UTC"));
			$current_date = $current->format('Y-m-d 00:00:00');
			$date = strtotime($current_date);
			$date = strtotime("-30 day", $date);
			$date_before_thirty_days = date('Y-m-d 00:00:00', $date);
			$this->db->group_start();
			$this->db->where('u.created_at  BETWEEN "' . $date_before_thirty_days . '" AND "' . $current_date . '"', '', false);
			$this->db->group_end();
		}

		if ($is_last_login == 'YES' && $last_login_from_date && $last_login_to_date) {
			$last_login_from_date = $last_login_from_date . ' 00:00:00';
			$last_login_to_date = $last_login_to_date . ' 23:59:59';
			$this->db->group_start();
			$this->db->where('u.login_at  BETWEEN "' . $last_login_from_date . '" AND "' . $last_login_to_date . '"', '', false);
			$this->db->group_end();
		}
		
		$query = $this->db->get();
		// echo $this->db->last_query();
		// die();
		$user_list = $query->result_array();
		return $user_list;
	}

	// public function send_message($user_id, $broadcast_id, $user_list)
	// {
	// 	$broadcast = $this->app->get_row('broadcast', '*', ['broadcast_id' => $broadcast_id]);
	// 	$title = safe_array_key($broadcast, "title", "");
	// 	$broadcast_message = safe_array_key($broadcast, "message", "");
	// 	$this->load->helper('email');
	// 	$email_template = "emailer/web_broadcast";
	// 	$subject = $title;
	// 	$this->load->library('email');
	// 	foreach ($user_list as $key => $row) {
			
	// 		$email_data = ["name" => $row['name'], "email" => $row['email'], "broadcast_message" => $broadcast_message];
	// 		$message = $this->load->view($email_template, $email_data, TRUE);
	// 		$this->email->from(SUPPORT_EMAIL, FROM_NAME);
	// 		$this->email->to($row['email']);
	// 		$this->email->subject(ucfirst(strtolower($subject)));
	// 		$this->email->message($message);
	// 		$this->email->send();
	// 		$this->create_broadcast_sent_user($user_id, $row['user_id'], $broadcast_id);
	// 	}
	// }

	public function send_message($target_user_id, $broadcast_id)
	{
		$broadcast = $this->app->get_row('broadcast', 'title, message, user_id', ['broadcast_id' => $broadcast_id]);
		$user = $this->app->get_row('users', 'email, first_name, last_name', ['user_id' => $target_user_id]);
		$name = $user['first_name'].' '.$user['last_name'];
		$title = safe_array_key($broadcast, "title", "");
		$broadcast_message = safe_array_key($broadcast, "message", "");
		$sender_user_id = safe_array_key($broadcast, "user_id", "");
		$this->load->helper('email');
		$email_template = "emailer/web_broadcast";
		$subject = $title;
		$this->load->library('email');
		$email_data = ["name" => $name, "email" => $user['email'], "broadcast_message" => $broadcast_message];
		// print_r($name);
		// print_r($broadcast_message);
		// print_r($sender_user_id);
		// print_r($target_user_id);
		// print_r($email_data);
		// die();
		$message = $this->load->view($email_template, $email_data, TRUE);
		$this->email->from(SUPPORT_EMAIL, FROM_NAME);
		$this->email->to($user['email']);
		$this->email->subject(ucfirst(strtolower($subject)));
		$this->email->message($message);
		$this->email->send();
		$this->create_broadcast_sent_user($sender_user_id, $target_user_id, $broadcast_id);
	}

	public function create_broadcast_sent_user($sender_user_id, $receiver_user_id, $broadcast_id)
	{
		$insert_array = [
			"broadcast_sent_user_guid" => get_guid(),
			"sender_user_id" => $sender_user_id,
			"receiver_user_id" => $receiver_user_id,
			"broadcast_id" => $broadcast_id,
			"status" => "UNSEEN",
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		];
		$this->db->insert('broadcast_sent_users', $insert_array);
		$broadcast_sent_user_guid = $this->db->insert_id();
		// print_r($broadcast_sent_user_guid);
		// echo '<br>';
		// print_r($broadcast_id);
		// echo '<br>';
		// print_r($insert_array);
		// echo '<br>';
		// die();
		return $broadcast_sent_user_guid;
	}

	public function broadcast_list_by_user($receiver_user_id, $keyword = '', $limit = 0, $offset = 0, $column_name, $order_by) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			// $this->db->select('DISTINCT(b.broadcast_id)', FALSE);
			$this->db->select('IFNULL(bsu.broadcast_sent_user_guid,"") AS broadcast_sent_user_guid', FALSE);
			$this->db->select('IFNULL(b.title,"") AS title', FALSE);
			$this->db->select('IFNULL(b.broadcast_guid,"") AS broadcast_id', FALSE);
			$this->db->select('IFNULL(b.message,"") AS message', FALSE);
			$this->db->select('IFNULL(b.created_at,"") AS created_at', FALSE);
		} else {
			$this->db->select('COUNT(b.broadcast_id) as count', FALSE);
		}
		$this->db->from('broadcast_sent_users AS bsu');
		$this->db->join('broadcast AS b', 'b.broadcast_id = bsu.broadcast_id', 'LEFT');
		$this->db->order_by('b.created_at', 'desc');

		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('b.title', $keyword, 'both');
			// $this->db->or_like('b.crm_contact_email', $keyword, 'both');
			$this->db->group_end();
		}

		if (($column_name !== '') && ($order_by !== '')) {
			$this->db->order_by('b.' . $column_name, $order_by);
		}
		$this->db->where('bsu.receiver_user_id', $receiver_user_id);
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

	public function get_broadcast_detail($broadcast_id){
		$this->db->select('IFNULL(b.title,"") AS title', FALSE);
		$this->db->select('IFNULL(b.message,"") AS message', FALSE);
		$this->db->select('IFNULL(b.is_user_active,"") AS is_user_active', FALSE);
		$this->db->select('IFNULL(b.is_user_inactive,"") AS is_user_inactive', FALSE);
		$this->db->select('IFNULL(b.is_last_thirty_days_signed_up,"") AS is_last_thirty_days_signed_up', FALSE);
		$this->db->select('IFNULL(b.is_last_login,"") AS is_last_login', FALSE);
		$this->db->select('IFNULL(b.last_login_from_date,"") AS last_login_from_date', FALSE);
		$this->db->select('IFNULL(b.last_login_to_date,"") AS last_login_to_date', FALSE);
		$this->db->select('IFNULL(b.broadcast_sent_type,"") AS broadcast_sent_type', FALSE);
		$this->db->select('IFNULL(b.broadcast_sent_time,"") AS broadcast_sent_time', FALSE);
		$this->db->select('IFNULL(b.time_seconds,"") AS time_seconds', FALSE);
		$this->db->select('IFNULL(b.is_agency_users,"") AS is_agency_users', FALSE);
		$this->db->select('IFNULL(b.is_agency_envitee_users,"") AS is_agency_envitee_users', FALSE);
		$this->db->select('IFNULL(b.is_individual_users,"") AS is_individual_users', FALSE);
		$this->db->select('IFNULL(b.scheduled_at,"") AS scheduled_at', FALSE);
		$this->db->select('IFNULL(b.status,"") AS status', FALSE);
		$this->db->select('IFNULL(b.broadcast_sent_date,"") AS broadcast_sent_date', FALSE);
		$this->db->from('broadcast AS b');
		$this->db->where('b.broadcast_id', $broadcast_id);
		$query = $this->db->get();
		$broadcast = $query->row_array();
		return $broadcast;
	}

	public function get_broadcast_detail_by_id($user_id, $broadcast_id){
		$this->db->select('IFNULL(b.title,"") AS title', FALSE);
		$this->db->select('IFNULL(b.message,"") AS message', FALSE);
		$this->db->select('IFNULL(b.created_at,"") AS created_at', FALSE);
		$this->db->select('IFNULL(b.updated_at,"") AS updated_at', FALSE);
		$this->db->from('broadcast AS b');
		$this->db->where('b.broadcast_id', $broadcast_id);
		$query = $this->db->get();
		$broadcast = $query->row_array();
		return $broadcast;
	}

	public function update_broadcast_seen_status($broadcast_sent_user_id){
		$data = [
			"status" => "SEEN",
			"updated_at" => DATETIME,
		];
		$this->db->update('broadcast_sent_users', $data, [
			'broadcast_sent_user_id' => $broadcast_sent_user_id,
		]);
		return true;
	}

	public function update_broadcast_status($broadcast_id , $status){
		$data = [
			"status" => $status,
			"updated_at" => DATETIME,
		];
		$this->db->update('broadcast', $data, [
			'broadcast_id' => $broadcast_id,
		]);
		return true;
	}

}
