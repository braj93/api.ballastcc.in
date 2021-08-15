<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic Notifications interaction methods
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 */
class Notifications extends REST_Controller {

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
		$this->load->model("notifications_model");
		$this->form_validation->set_data($this->_data);
	}

	public function index_get() {
		$this->_response["service_name"] = "notifications";
		$session_key = $this->rest->key;
		$this->_response["data"] = $this->app->user_data($session_key);
		$this->set_response($this->_response);
	}

	public function list_post() {
		$this->_response["service_name"] = "notifications/list";
		$session_key = $this->rest->key;
		$user_id = $this->rest->user_id;
		$pagination = [];
		if ($this->post('pagination')) {
			$pagination = $this->post('pagination');
		}

		$page_no = safe_array_key($pagination, 'page_no', 1);
		$page_size = safe_array_key($pagination, 'page_size', 10);

		$this->_response["data"] = $this->notifications_model->list($user_id, $page_no, $page_size);
		$this->_response["total_records"] = $this->notifications_model->list($user_id, $page_no, $page_size, TRUE);
		$this->set_response($this->_response);

	}

	public function mark_as_seen_post() {
		$this->_response["service_name"] = "notifications/mark_as_seen";
		$user_id = $this->rest->user_id;
		$this->notifications_model->mark_as_seen($user_id);
		$this->set_response($this->_response);
	}

	public function mark_as_read_post() {
		$this->_response["service_name"] = "notifications/mark_as_read";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('notification_id', 'notifcation id', 'trim|required|callback__check_notification_id');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$notification_guid = safe_array_key($this->_data, "notification_id", 0);
			$notification_id = get_guid_detail($notification_guid, 'notification');
			$this->notifications_model->mark_as_read($notification_id, $user_id);
			$this->set_response($this->_response);
		}
	}

	public function unread_count_post() {
		$this->_response["service_name"] = "notifications/unread_count";
		$user_id = $this->rest->user_id;
		$this->_response["total_records"] = $this->notifications_model->unread_count($user_id);
		$this->set_response($this->_response);
	}

	public function delete_post() {
		$this->_response["service_name"] = "notifications/list";
		$session_key = $this->rest->key;
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('notification_id', 'notification id', 'trim|required|callback__check_notification_id');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$notification_guid = safe_array_key($this->_data, "notification_id", 0);
			$this->notifications_model->delete($notification_guid, $user_id);
		}
		$this->set_response($this->_response);
	}

	public function _check_notification_id($notification_guid) {
		if (empty($notification_guid)) {
			$this->form_validation->set_message('_check_notification_id', 'Notification id is required.');
			return FALSE;
		}
		$notification_id = get_guid_detail($notification_guid, 'notification');
		if (empty($notification_id)) {
			$this->form_validation->set_message('_check_notification_id', 'Notification id is invalid.');
			return FALSE;
		}
		return TRUE;
	}

	public function get_notifications_setting_get() {
		$this->_response["service_name"] = "notifications/get_notifications_setting";
		$user_id = $this->rest->user_id;
		$rows = $this->app->get_row('users', 'send_push_notifications, send_email_notifications', ['user_id' => $user_id]);
		$this->_response["data"] = $rows;
		$this->set_response($this->_response);
	}

	public function update_notifications_setting_post() {
		$this->_response["service_name"] = "users/update_notifications_setting";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('send_push_notifications', 'send push notifications', 'trim|in_list[YES,NO]');
		$this->form_validation->set_rules('send_email_notifications', 'send push notifications', 'trim|in_list[YES,NO]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$send_push_notifications = safe_array_key($this->_data, "send_push_notifications", "");
			$send_email_notifications = safe_array_key($this->_data, "send_email_notifications", "");

			$this->notifications_model->update_notifications_setting($user_id, $send_push_notifications, $send_email_notifications);
			$this->_response["message"] = "Success.";
			$this->set_response($this->_response);
		}
	}
}
