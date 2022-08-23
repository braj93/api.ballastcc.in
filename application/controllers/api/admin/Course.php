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
class Course extends REST_Controller {

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
		$this->load->model("admin_model/course_model");
		$this->load->model("admin_model/master_model");
		$this->load->library('MY_Form_validation');
	}

	public function index_get() {
		$this->set_response($this->_response);
	}

	public function test_get() {
		echo 'Get Rest Controller of Course';
	}

    /**
 * STUDENT REGISTERATION
 */
	public function add_course_post() {
		// print_r('you are on controller');
		// die();
		$this->_response["service_name"] = "admin/addCourse";
		$session_key = $this->rest->key;
        $user = $this->app->user_data($session_key);
		$user_id = $this->rest->user_id;
		$session_user = $this->app->get_row('users', 'user_id, user_type', ['user_id' => $user_id]);
		$user_type = safe_array_key($session_user, "user_type", "");

		$this->form_validation->set_rules('name', 'Course Name', 'trim|required|callback__check_unique_course');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$name = safe_array_key($this->_data, "name", "");
			
			$status = safe_array_key($this->_data, "status", "");

			$course_id = $this->course_model->create_course( $name,$user_id, $status);
			$this->_response["message"] = 'Course created successfully';
			$this->set_response($this->_response);
	
		}
	}
	public function _check_unique_course($name) {
		$user_guid = safe_array_key($this->_data, "user_id", "");
		$added_by = get_detail_by_guid($user_guid, 'user');

		// $email_part = explode('@', $email);
		// $email_domain = end($email_part);
		// $email_domain = strtolower($email_domain);
		// if (in_array($email_domain, $this->app->disallowed_email_domains)) {
		// 	$this->form_validation->set_message('_check_unique_email', 'Only use corporate email address.');
		// 	return FALSE;
		// }

		$rows = $this->app->get_rows('courses', 'course_guid', [
			'name' => strtolower($name),
			'added_by' => $added_by,
		]);

		if (count($rows) > 0) {
			$this->form_validation->set_message('_check_unique_course', 'Course Name already in use.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
}