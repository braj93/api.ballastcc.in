<?php

defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Broadcast extends REST_Controller {

	var $_data = array();

	function __construct() {
		// Construct the parent class
		parent::__construct();

		$this->_data = $this->post();
		$this->_data['key'] = "value";
		$this->_response = [
			"status" => TRUE,
			"message" => "Success",
			"errors" => (object) [],
			"data" => (object) [],
		];
		$this->load->library('form_validation');
		$this->form_validation->set_data($this->_data);
		$this->load->model("admin_model/broadcast_model", "broadcast_model");
		$this->load->library('MY_Form_validation');
	}

	/* Create Broadcast Message */
	public function create_broadcast_post() {
		$this->_response["service_name"] = "admin/broadcast/create_broadcast";

		$this->form_validation->set_rules('title', 'Broadcast title', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('message', 'Broadcast message', 'trim|required');
		$this->form_validation->set_rules('date', 'Broadcast Date', 'trim|required');
		$this->form_validation->set_rules('time', 'Broadcast Time', 'trim|required');
		$send_to = safe_array_key($this->_data, "send_to", "");
		if (empty($send_to)) {
			$this->form_validation->set_rules('send_to', 'Broadcast Send to detail required', 'trim|required');
		}
		// else {
		// 	$this->form_validation->set_rules('send_to', 'Send to', 'trim');
		// }

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$title = safe_array_key($this->_data, "title", "");
			$message = safe_array_key($this->_data, "message", "");
			$date = safe_array_key($this->_data, "date", "");
			$time = safe_array_key($this->_data, "time", "");
			$send_to = safe_array_key($this->_data, "send_to", "");
			$send_to = json_encode($send_to);
			$broadcast_id = $this->broadcast_model->create_broadcast($title, $message, $date, $time, $send_to);
			$this->_response["message"] = "Message added successfully";
			$this->set_response($this->_response);
		}
	}

	/* Compose New Message */
	function send_post() {

		$this->form_validation->set_rules("subject", "Subject", "required");
		$this->form_validation->set_rules("body", "Message", "required");

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;

			$subject = safe_array_key($this->_data, 'subject', TRUE);
			$body = safe_array_key($this->_data, 'body', TRUE);
			$this->broadcast_model->new_message($user_id, $subject, $body);

			$this->_response["data"] = "";
			$this->_response["message"] = "Broadcast successfully sent.";
			$this->set_response($this->_response);
		}
	}

	/**
	 * GET LIST API
	 */
	public function get_broadcast_list_post() {
		$this->_response["service_name"] = "admin/broadcast/get_broadcast_list";
		$limit = 25;
		$offset = 0;
		$searchKey = '';
		$filterBy = '';
		$sortField = '';
		$sortOrder = '';
		if ($this->post('limit')) {
			$limit = $this->post('limit');
		}
		if ($this->post('offset')) {
			$offset = $this->post('offset');
		}
		if ($this->post('searchkey')) {
			$searchKey = $this->post('searchkey');
		}
		if ($this->post('filterBy')) {
			$filterBy = $this->post('filterBy');
		}

		$sort = safe_array_key($this->_data, "sort", []);
		if (isset($this->_data['sort']) && !empty($this->_data['sort']) && isset($this->_data['sort']) && !empty($this->_data['sort'])) {
			$sortField = safe_array_key($sort, 'sortBy', 'title');
			$sortOrder = safe_array_key($sort, 'sortOrder', 'ASC');
		}
		$list = $this->broadcast_model->get_broadcast_list($limit, $offset, $searchKey, $filterBy, $sortField, $sortOrder);
		$count = $this->broadcast_model->get_broadcast_list(0, 0, $searchKey, $filterBy, $sortField, $sortOrder);
		$this->_response["data"] = $list;
		$this->_response["total_items"] = $count;
		$this->set_response($this->_response);
	}

	// SEND BROADCAST MESSAGE
	function send_message_post() {
		$this->_response["service_name"] = "admin/broadcast/send_message";
		$this->form_validation->set_rules("title", "Title", "required");
		$this->form_validation->set_rules("message", "Message", "required");
		$is_last_login = safe_array_key($this->_data, "is_last_login", "NO");
		if ($is_last_login == 'YES') {
			$this->form_validation->set_rules('last_login_from_date', 'From date', 'trim|required');
			$this->form_validation->set_rules('last_login_to_date', 'To date', 'trim|required');
		}
		$is_agnecy = safe_array_key($this->_data, "is_agnecy", "NO");
		if ($is_agnecy == 'YES') {
			$this->form_validation->set_rules('agency', 'Select atleast one Agency', 'trim|required');
		}
		$is_non_agnecy = safe_array_key($this->_data, "is_non_agnecy", "NO");
		if ($is_non_agnecy == 'YES') {
			$this->form_validation->set_rules('non_agency', 'Select atleast one Non Agency', 'trim|required');
		}
		$broadcast_sent_type = safe_array_key($this->_data, "broadcast_sent_type", "NOW");
		if ($broadcast_sent_type == 'SCHEDULED') {
			$this->form_validation->set_rules('broadcast_sent_date', 'Date', 'trim|required');
			$this->form_validation->set_rules('broadcast_sent_time', 'Time', 'trim|required');
		}
		$this->form_validation->set_rules('scheduled_at', 'Date sent on', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$title = safe_array_key($this->_data, "title", "");
			$message = safe_array_key($this->_data, "message", "NO");
			$is_user_active = safe_array_key($this->_data, "is_user_active", "NO");
			$is_user_inactive = safe_array_key($this->_data, "is_user_inactive", "NO");
			$is_last_thirty_days_signed_up = safe_array_key($this->_data, "is_last_thirty_days_signed_up", "NO");
			$is_last_login = safe_array_key($this->_data, "is_last_login", "NO");
			$last_login_from_date = safe_array_key($this->_data, "last_login_from_date", "");
			$last_login_to_date = safe_array_key($this->_data, "last_login_to_date", "");
			$is_agnecy = safe_array_key($this->_data, "is_agnecy", "NO");
			$agency = safe_array_key($this->_data, "agency", "");
			$is_non_agnecy = safe_array_key($this->_data, "is_non_agnecy", "NO");
			$non_agency = safe_array_key($this->_data, "non_agency", "");

			// AFTER CREATE
			$broadcast_sent_type = safe_array_key($this->_data, "broadcast_sent_type", "NOW");
			$broadcast_sent_date = safe_array_key($this->_data, "broadcast_sent_date", "");
			$broadcast_sent_time = safe_array_key($this->_data, "broadcast_sent_time", "");
			$time_seconds = safe_array_key($this->_data, "time_seconds", "");
			$is_agency_users = safe_array_key($this->_data, "is_agency_users", "NO");
			$is_agency_envitee_users = safe_array_key($this->_data, "is_agency_envitee_users", "NO");
			$is_individual_users = safe_array_key($this->_data, "is_individual_users", "NO");
			$scheduled_at = safe_array_key($this->_data, "scheduled_at", "");

			$broadcast_id = $this->broadcast_model->create_broadcast($user_id, $title, $message, $is_user_active, $is_user_inactive, $is_last_thirty_days_signed_up, $is_last_login, $last_login_from_date, $last_login_to_date, $is_agnecy, $agency, $is_non_agnecy, $non_agency, $broadcast_sent_type, $broadcast_sent_date, $broadcast_sent_time, $time_seconds, $is_agency_users, $is_agency_envitee_users, $is_individual_users, $scheduled_at);
			
			if($broadcast_sent_type == 'NOW'){
			// 	$user_list = $this->broadcast_model->get_user_list($is_user_active, $is_user_inactive, $is_last_thirty_days_signed_up, $is_last_login, $last_login_from_date, $last_login_to_date, $is_agnecy, $agency, $is_non_agnecy, $non_agency, $is_agency_users, $is_agency_envitee_users, $is_individual_users);
			// 	$this->broadcast_model->send_message($user_id, $broadcast_id, $user_list);
			// $this->broadcast_model->update_broadcast_status($broadcast_id, 'SENT');

				$this->_response["data"] = "";
				$this->_response["message"] = "Message sent successfully.";
				$this->set_response($this->_response);
			}else {
				$this->_response["data"] = "";
				$this->_response["message"] = "Message added successfully.";
				$this->set_response($this->_response);
			}
			
		}
	}

	function update_broadcast_post() {
		$this->_response["service_name"] = "admin/broadcast/update_broadcast";
		$this->form_validation->set_rules('broadcast_id', 'Broadcast Id', 'trim|required|callback__check_broadcast_exist');
		$this->form_validation->set_rules("title", "Title", "required");
		$this->form_validation->set_rules("message", "Message", "required");
		$is_last_login = safe_array_key($this->_data, "is_last_login", "NO");
		if ($is_last_login == 'YES') {
			$this->form_validation->set_rules('last_login_from_date', 'From date', 'trim|required');
			$this->form_validation->set_rules('last_login_to_date', 'To date', 'trim|required');
		}
		$is_agnecy = safe_array_key($this->_data, "is_agnecy", "NO");
		if ($is_agnecy == 'YES') {
			$this->form_validation->set_rules('agency', 'Select atleast one Agency', 'trim|required');
		}
		$is_non_agnecy = safe_array_key($this->_data, "is_non_agnecy", "NO");
		if ($is_non_agnecy == 'YES') {
			$this->form_validation->set_rules('non_agency', 'Select atleast one Non Agency', 'trim|required');
		}
		$broadcast_sent_type = safe_array_key($this->_data, "broadcast_sent_type", "NOW");
		if ($broadcast_sent_type == 'SCHEDULED') {
			$this->form_validation->set_rules('broadcast_sent_date', 'Date', 'trim|required');
			$this->form_validation->set_rules('broadcast_sent_time', 'Time', 'trim|required');
		}
		$this->form_validation->set_rules('scheduled_at', 'Date sent on', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$broadcast_guid = safe_array_key($this->_data, "broadcast_id", "");
			$broadcast = $this->app->get_row('broadcast', 'broadcast_id', ['broadcast_guid' => $broadcast_guid]);
			$broadcast_id = safe_array_key($broadcast, "broadcast_id", "");
			$title = safe_array_key($this->_data, "title", "");
			$message = safe_array_key($this->_data, "message", "NO");
			$is_user_active = safe_array_key($this->_data, "is_user_active", "NO");
			$is_user_inactive = safe_array_key($this->_data, "is_user_inactive", "NO");
			$is_last_thirty_days_signed_up = safe_array_key($this->_data, "is_last_thirty_days_signed_up", "NO");
			$is_last_login = safe_array_key($this->_data, "is_last_login", "NO");
			$last_login_from_date = safe_array_key($this->_data, "last_login_from_date", "");
			$last_login_to_date = safe_array_key($this->_data, "last_login_to_date", "");
			$is_agnecy = safe_array_key($this->_data, "is_agnecy", "NO");
			$agency = safe_array_key($this->_data, "agency", "");
			$is_non_agnecy = safe_array_key($this->_data, "is_non_agnecy", "NO");
			$non_agency = safe_array_key($this->_data, "non_agency", "");

			// AFTER CREATE
			$broadcast_sent_type = safe_array_key($this->_data, "broadcast_sent_type", "NOW");
			$broadcast_sent_date = safe_array_key($this->_data, "broadcast_sent_date", "");
			$broadcast_sent_time = safe_array_key($this->_data, "broadcast_sent_time", "");
			$time_seconds = safe_array_key($this->_data, "time_seconds", "");
			$is_agency_users = safe_array_key($this->_data, "is_agency_users", "NO");
			$is_agency_envitee_users = safe_array_key($this->_data, "is_agency_envitee_users", "NO");
			$is_individual_users = safe_array_key($this->_data, "is_individual_users", "NO");
			$scheduled_at = safe_array_key($this->_data, "scheduled_at", "");

			$this->broadcast_model->update_broadcast($broadcast_id, $title, $message, $is_user_active, $is_user_inactive, $is_last_thirty_days_signed_up, $is_last_login, $last_login_from_date, $last_login_to_date, $is_agnecy, $agency, $is_non_agnecy, $non_agency, $broadcast_sent_type, $broadcast_sent_date, $broadcast_sent_time, $time_seconds, $is_agency_users, $is_agency_envitee_users, $is_individual_users, $scheduled_at);
			
			if($broadcast_sent_type == 'NOW'){
				$this->_response["data"] = "";
				$this->_response["message"] = "Message sent successfully.";
				$this->set_response($this->_response);
			}else {
				$this->_response["data"] = "";
				$this->_response["message"] = "Message added successfully.";
				$this->set_response($this->_response);
			}
			
		}
	}

	public function list_post() {
		$this->_response["service_name"] = "admin/broadcast/list";
		// $this->form_validation->set_rules('user_id', 'user id', 'trim');
		// if ($this->form_validation->run() == FALSE) {
		// 	$errors = $this->form_validation->error_array();
		// 	$this->_response["message"] = current($errors);
		// 	$this->_response["errors"] = $errors;
		// 	$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		// } else {
		$user_id = $this->rest->user_id;
		$user_type = get_detail_by_id($user_id, 'user', 'user_type');
		// $keyword = safe_array_key($this->_data, "keyword", "");
		$pagination = safe_array_key($this->_data, "pagination", []);
		$limit = safe_array_key($pagination, "limit", 10);
		$offset = safe_array_key($pagination, "offset", 0);
		$sort_by = safe_array_key($this->_data, "sort_by", []);
		$column_name = safe_array_key($sort_by, "column_name", '');
		$order_by = safe_array_key($sort_by, "order_by", '');
		$this->_response["data"] = $this->broadcast_model->broadcast_list($user_id, $limit, $offset, $column_name, $order_by);
		$this->_response["counts"] = $this->broadcast_model->broadcast_list($user_id, 0, 0, $column_name, $order_by);
		$this->set_response($this->_response);
		// }
	}

	public function broadcast_list_by_user_post() {
		$this->_response["service_name"] = "admin/broadcast/broadcast_list_by_user";
		$this->form_validation->set_rules('keyword', 'keyword', 'trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$keyword = safe_array_key($this->_data, "keyword", "");
			$pagination = safe_array_key($this->_data, "pagination", []);
			$limit = safe_array_key($pagination, "limit", 10);
			$offset = safe_array_key($pagination, "offset", 0);
			$sort_by = safe_array_key($this->_data, "sort_by", []);
			$column_name = safe_array_key($sort_by, "column_name", 'first_name');
			$order_by = safe_array_key($sort_by, "order_by", 'acs');

			$data = $this->broadcast_model->broadcast_list_by_user($user_id, $keyword, $limit, $offset, $column_name, $order_by);
			$counts = $this->broadcast_model->broadcast_list_by_user($user_id, $keyword, 0, 0, $column_name, $order_by);
			$this->_response["counts"] = $counts;
			$this->_response["data"] = $data;
			$this->_response["message"] = "List";
			$this->set_response($this->_response);
		}
	}

	public function get_broadcast_detail_post() {
		$this->_response["service_name"] = "admin/broadcast/get_broadcast_detail";
		$this->form_validation->set_rules('broadcast_id', 'Broadcast Id', 'trim|required|callback__check_broadcast_exist');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$broadcast_guid = safe_array_key($this->_data, "broadcast_id", "");
			$broadcast = $this->app->get_row('broadcast', 'broadcast_id', ['broadcast_guid' => $broadcast_guid]);
			$broadcast_id = safe_array_key($broadcast, "broadcast_id", "");
			$data = $this->broadcast_model->get_broadcast_detail($broadcast_id);
			$this->_response["data"] = $data;
			$this->_response["message"] = "List";
			$this->set_response($this->_response);
		}
	}

	public function get_broadcast_detail_by_id_post() {
		$this->_response["service_name"] = "admin/broadcast/get_broadcast_detail_by_id";
		$this->form_validation->set_rules('broadcast_sent_user_id', 'Broadcast Id', 'trim|required|callback__check_broadcast_sent_user_id_exist');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$broadcast_sent_user_guid = safe_array_key($this->_data, "broadcast_sent_user_id", "");
			$broadcast_sent_user = $this->app->get_row('broadcast_sent_users', 'broadcast_id', ['broadcast_sent_user_guid' => $broadcast_sent_user_guid]);
			$broadcast_id = safe_array_key($broadcast_sent_user, "broadcast_id", "");
			$data = $this->broadcast_model->get_broadcast_detail_by_id($user_id, $broadcast_id);
			$this->_response["data"] = $data;
			$this->_response["message"] = "List";
			$this->set_response($this->_response);
		}
	}

	public function update_broadcast_seen_status_post() {
		$this->_response["service_name"] = "admin/broadcast/update_broadcast_seen_status";
		$this->form_validation->set_rules('broadcast_sent_user_id', 'Broadcast Id', 'trim|required|callback__check_broadcast_sent_user_id_exist');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$broadcast_sent_user_guid = safe_array_key($this->_data, "broadcast_sent_user_id", "");
			$broadcast_sent_user = $this->app->get_row('broadcast_sent_users', 'broadcast_sent_user_id', ['broadcast_sent_user_guid' => $broadcast_sent_user_guid]);
			$broadcast_sent_user_id = safe_array_key($broadcast_sent_user, "broadcast_sent_user_id", "");
			$this->broadcast_model->update_broadcast_seen_status($broadcast_sent_user_id);
			$this->_response["message"] = "Status Updated";
			$this->set_response($this->_response);
		}
	}

	public function _check_broadcast_exist($broadcast_guid) {
		if ($broadcast_guid != "") {
			$broadcast = $this->app->get_row('broadcast', 'broadcast_id', ['broadcast_guid' => $broadcast_guid]);
			if (empty($broadcast)) {
				$this->form_validation->set_message('_check_broadcast_exist', 'Please provide correct id');
				return FALSE;
			}
		}
		return TRUE;
	}

	public function _check_broadcast_sent_user_id_exist($broadcast_sent_user_id) {
		if ($broadcast_sent_user_id != "") {
			$users_data = $this->app->get_row('broadcast_sent_users', 'broadcast_id', ['broadcast_sent_user_guid' => $broadcast_sent_user_id]);
			if (empty($users_data)) {
				$this->form_validation->set_message('_check_broadcast_sent_user_id_exist', 'Please provide correct id');
				return FALSE;
			}
		}
		return TRUE;
	}

}
?>