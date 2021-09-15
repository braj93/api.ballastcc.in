<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Auth extends REST_Controller {

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
		$this->load->model("admin_model/auth_model");
		$this->load->library('MY_Form_validation');
	}

	public function index_get() {
		$this->set_response($this->_response);
	}

	public function test_get() {
		echo 'Get Rest Controller';
	}

/**
 * ADMIN LOGIN
 */
	public function login_post() {
		$this->_response["service_name"] = "admins/login";
		// $this->form_validation->set_rules('device_type', 'device type', 'trim|required|in_list[' . implode($this->app->device_types, ",") . ']');
		// $this->form_validation->set_rules('device_token', 'device token', 'trim');
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'password', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$this->load->model("admin_model/auth_model");

			$email = $this->_data['email'];
			$password = $this->_data['password'];
			$admin_id = $this->auth_model->check_login($email, $password);
			if ($admin_id == 0) {
				$this->_response["message"] = "Invalid email/ password or email not registered.";
				$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
				$this->auth_model->add_logs($this->_data);
			} else {
				$user = $this->app->get_row('admins', 'status', ['admin_id' => $admin_id]);
				if ($user['status'] == 'ACTIVE') {
					$device_type = safe_array_key($this->_data, "device_type", "web_browser");
					$device_type_id = array_search($device_type, $this->app->device_types);
					$device_token = safe_array_key($this->_data, "device_token", "");
					$ip_address = $this->input->ip_address();
					$session_id = $this->auth_model->create_session_key($admin_id, $device_type_id, $device_token, $ip_address);
					$this->_response["data"] = $this->app->user_data($session_id);
					$this->_response["success"] = true;
					
					$this->set_response($this->_response);
				} elseif ($user['status'] == 'PENDING') {
					$device_type = safe_array_key($this->_data, "device_type", "web_browser");
					$device_type_id = array_search($device_type, $this->app->device_types);
					$this->auth_model->send_user_verification($admin_id, $device_type);
					if (in_array($device_type_id, array("1"))) {
						$this->_response["message"] = "To get started, please click on the verification link sent to your registered email ID $email";
					} else {
						$this->_response["message"] = "To get started, please enter verification code sent to your registered email ID $email";
					}
					$row = $this->app->get_row('admins', 'user_guid', ['admin_id' => $admin_id]);
					$this->_response["data"] = $row;

					$this->set_response($this->_response, REST_Controller::HTTP_EXPECTATION_FAILED);
				} elseif ($user['status'] == 'BLOCKED' || $user['status'] == 'DELETED') {
					$this->_response["message"] = "This account is deactivated. Please contact to our admin";
					$this->set_response($this->_response, REST_Controller::HTTP_GONE);
					$this->auth_model->add_logs($this->_data);
				}
			}
		}
	}
/**
 * ADMIN LOGOUT
 */
public function logout_post() {
	$this->_response["service_name"] = "admin/logout";
	$session_key = $this->rest->key;
	$this->db->update('user_login_sessions', [
		"status" => 'LOGGED_OUT',
	], ['session_key' => $session_key]);
	$this->_response["message"] = "admin logged out successfully";
	$this->set_response($this->_response);
}

// public function logout_post() {
// 	$this->_response["message"] = $this->rest->key;
// 	$this->_response["service_name"] = "admin/logout";
// 	$session_key = $this->rest->key;
// 	$this->db->update('user_login_sessions', [
// 		"status" => 'LOGGED_OUT',
// 	], ['session_key' => $session_key]);
// 	$this->_response["message"] = "logged out successfully";
// }

}