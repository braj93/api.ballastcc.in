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
class Students extends REST_Controller {

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
		$this->load->model("students_model");
		$this->load->library('MY_Form_validation');
	}

	public function index_get() {
		$this->set_response($this->_response);
	}

	public function test_get() {
		echo 'Get Rest Controller';
	}

	/**
 * STUDENT REGISTERATION
 */
	public function add_student_post() {
		// print_r('you are on controller');
		// die();
		$this->_response["service_name"] = "users/addStudent";
		$session_key = $this->rest->key;
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|max_length[50]|callback__check_alpha_space');
		$this->form_validation->set_rules('father_name', 'Name', 'trim|required|min_length[2]|max_length[50]|callback__check_alpha_space');
		$this->form_validation->set_rules('mother_name', 'Name', 'trim|required|min_length[2]|max_length[50]|callback__check_alpha_space');
		$this->form_validation->set_rules('mobile', 'mobile', 'trim|required');
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback__unique_email');
		$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[8]|max_length[12]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$first_name = safe_array_key($this->_data, "name", "");
			$last_name = safe_array_key($this->_data, "last_name", "");
			$mobile = safe_array_key($this->_data, "mobile", "");
			$email = safe_array_key($this->_data, "email", "");
			$password = safe_array_key($this->_data, "password", "");
			$user_id = $this->users_model->create_student($first_name, $last_name, $mobile,  $email, $password);
			$this->_response["message"] = 'You have registered successfully';
			$this->set_response($this->_response);
	
		}
	}
	/**
 * VALIDATION FOR SPECIAL CHARACTERS
 */
public function _check_alpha_space($str) {
	if (!empty($str)) {
		if (!preg_match("/^[a-zA-Z.'\s]+$/", $str)) {
			$this->form_validation->set_message('_check_alpha_space', 'The Name field can contain only alphabets, dot & apostrophe');
			return FALSE;
		} else {
			return true;
		}

	}
}
	/**
 * BATCH REGISTERATION
 */
public function add_batch_post() {
	$this->_response["service_name"] = "users/addbatch";
	$session_key = $this->rest->key;
	$this->form_validation->set_rules('name', 'Batch Name', 'trim|required|min_length[2]|max_length[50]');
	$this->form_validation->set_rules('medium', 'Batch medium', 'trim|required');
	$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
	$this->form_validation->set_rules('end_date', 'End Date', 'trim|required');
	$this->form_validation->set_rules('start', 'Start Time', 'trim|required');
	$this->form_validation->set_rules('end', 'End Time', 'trim|required');
	if ($this->form_validation->run() == FALSE) {
		$errors = $this->form_validation->error_array();
		$this->_response["message"] = current($errors);
		$this->_response["errors"] = $errors;
		$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
	} else {
		$name = safe_array_key($this->_data, "name", "");
		$medium = safe_array_key($this->_data, "medium", "");
		$start_date = safe_array_key($this->_data, "start_date", "");
		$end_date = safe_array_key($this->_data, "end_date", "");
		$start = safe_array_key($this->_data, "start", "");
		$end = safe_array_key($this->_data, "end", "");
		// $mobile = safe_array_key($this->_data, "mobile", "");
		// $email = safe_array_key($this->_data, "email", "");
		// $password = safe_array_key($this->_data, "password", "");
		$user_id = $this->students_model->create_batch($name,$medium, $start_date, $end_date,  $start, $end);
		$this->_response["message"] = 'You have created new batch successfully';
		$this->set_response($this->_response);

	}
}
public function get_batches_get() {
	$this->_response["service_name"] = "students/get_batches";
	$batches_data = $this->app->get_rows('batches', 'batch_id,batch_guid,name,start_date,end_date,start,end,medium', []);
	if (empty($batches_data)) {
		$batches_data = [];
	}
	$this->_response["data"] = $batches_data;
	$this->set_response($this->_response);
}

public function edit_batch_post() {
	$this->_response["service_name"] = "students/edit_batches";
	$this->form_validation->set_rules('batch_id', 'batch id', 'trim|required');
	$this->form_validation->set_rules('name', 'Batch Name', 'trim|required|min_length[2]|max_length[50]');
	$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
	$this->form_validation->set_rules('end_date', 'End Date', 'trim|required');
	$this->form_validation->set_rules('start', 'Start Time', 'trim|required');
	$this->form_validation->set_rules('end', 'End Time', 'trim|required');
	if ($this->form_validation->run() == FALSE) {
		$errors = $this->form_validation->error_array();
		$this->_response["message"] = current($errors);
		$this->_response["errors"] = $errors;
		$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
	} else {
		$batch_guid = safe_array_key($this->_data, "batch_id", "");
		$name = safe_array_key($this->_data, "name", "");
		$start_date = safe_array_key($this->_data, "start_date", "");
		$end_date = safe_array_key($this->_data, "end_date", "");
		$start = safe_array_key($this->_data, "start", "");
		$end = safe_array_key($this->_data, "end", "");

		// $name = safe_array_key($this->_data, "name", "");
		// $business_name = safe_array_key($this->_data, "business_name", "");
		// $email = safe_array_key($this->_data, "email", "");
		$batch_id = get_detail_by_guid($batch_guid, 'batch');
		// $this->users_manage_model->update_organization_user_email($userid, $email);
		// print_r([$batch_id, $name, $start_date, $end_date,  $start, $end]);
		// die();
		$this->_response["data"] = $this->students_model->update_batch($batch_id, $name, $start_date, $end_date,  $start, $end);
		$this->_response["message"] = "Success.";
		$this->set_response($this->_response);
	}
}
/**
* CHECK UNIQUE EMAIL
*/
public function _unique_email($str, $user_id) {
	$rows1 = [];

	$email_part = explode('@', $str);
	$email_domain = end($email_part);
	$email_domain = strtolower($email_domain);
	if (in_array($email_domain, $this->app->disallowed_email_domains)) {
		$this->form_validation->set_message('_unique_email', 'Only use corporate email address.');
		return FALSE;
	}

	if (!empty($user_id)) {
		$rows1 = $this->app->get_rows('users', 'user_id', ['email' => strtolower($str), 'user_id != ' => $user_id]);
	} else {
		$rows1 = $this->app->get_rows('users', 'user_id', ['email' => strtolower($str)]);
	}

	if (count($rows1) > 0) {
		$this->form_validation->set_message('_unique_email', 'Email already in use.');
		return FALSE;
	} else {
		return TRUE;
	}
}


}