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
class Common extends REST_Controller {

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
		$this->load->model("common_model");
		$this->load->library('MY_Form_validation');
	}

	public function index_get() {
		$this->set_response($this->_response);

	}

    /**
 * LIST COURSE
 */
	public function courses_list_post() {
		$this->_response["service_name"] = "common/courses_list";
		$this->form_validation->set_rules('keyword', 'keyword', 'trim');
		$this->form_validation->set_rules('course_id', 'Course Id', 'trim|callback__check_course_exist');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			// $this->load->model("users_model");
			// $user_id = $this->rest->user_id;
			// $user = $this->app->get_row('users', 'user_type', ['user_id' => $user_id]);
			// $user_type = safe_array_key($user, "user_type", "");
			$course_id="";
			$course_guid = safe_array_key($this->_data, "course_id", "");
			if(!empty($course_guid)){
			$course = $this->app->get_row('courses', 'course_id', ['course_guid' => $course_guid]);
			$course_id = safe_array_key($course, "course_id", "");			
			}
			$keyword = safe_array_key($this->_data, "keyword", "");
			$pagination = safe_array_key($this->_data, "pagination", []);
			$limit = safe_array_key($pagination, "limit", 10);
			$offset = safe_array_key($pagination, "offset", 0);
			$sort_by = safe_array_key($this->_data, "sort_by", []);
			$column_name = safe_array_key($sort_by, "column_name", 'course_name');
			$order_by = safe_array_key($sort_by, "order_by", 'acs');
			$this->_response["data"] = $this->common_model->course_list($column_name, $order_by,$course_id,$keyword, $limit, $offset);
			$this->_response["counts"] = $this->common_model->course_list($column_name, $order_by,$course_id,$keyword, 0, 0);
			$this->set_response($this->_response);
		}
	}
	
    /**
 * LIST SUBJECT
 */
	public function subject_list_post() {
		$this->_response["service_name"] = "common/subject_list";
		$this->form_validation->set_rules('keyword', 'keyword', 'trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			// $this->load->model("users_model");
			// $user_id = $this->rest->user_id;
			// $user = $this->app->get_row('users', 'user_type', ['user_id' => $user_id]);
			// $user_type = safe_array_key($user, "user_type", "");
			$keyword = safe_array_key($this->_data, "keyword", "");
			$pagination = safe_array_key($this->_data, "pagination", []);
			$limit = safe_array_key($pagination, "limit", 10);
			$offset = safe_array_key($pagination, "offset", 0);
			$sort_by = safe_array_key($this->_data, "sort_by", []);
			$column_name = safe_array_key($sort_by, "column_name", 'subject_name');
			$order_by = safe_array_key($sort_by, "order_by", 'acs');
			$this->_response["data"] = $this->common_model->subject_list($column_name, $order_by,$keyword, $limit, $offset);
			$this->_response["counts"] = $this->common_model->subject_list($column_name, $order_by,$keyword, 0, 0);
			$this->set_response($this->_response);
		}
	}


	public function _check_course_exist($course_guid) {
		if (!empty($course_guid)) {
			$course_data = $this->app->get_row('courses', 'course_id', ['course_guid' => $course_guid]);
			
			if (empty($course_data)) {
				$this->form_validation->set_message('_check_course_exist', 'Not valid course ID.');
				return FALSE;
			}
		}
		return TRUE;
	}


}