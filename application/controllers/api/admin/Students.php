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
		$this->load->model("admin_model/students_model");
		$this->load->model("admin_model/master_model");
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
		// die();
		$this->_response["service_name"] = "admin/addStudent";
		$session_key = $this->rest->key;
		$this->form_validation->set_rules('reg_number', 'Registration Number', 'trim|required|callback__check_reg_number');
		$this->form_validation->set_rules('reg_date', 'Registration Date', 'trim|required');
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[2]|max_length[50]|callback__check_alpha_space');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[2]|max_length[50]|callback__check_alpha_space');
		$this->form_validation->set_rules('father_name', 'Father Name', 'trim|required|min_length[2]|max_length[50]|callback__check_alpha_space');
		$this->form_validation->set_rules('mother_name', 'Mother Name', 'trim|required|min_length[2]|max_length[50]|callback__check_alpha_space');
		$this->form_validation->set_rules('class', 'Class ID', 'trim|required');
		$this->form_validation->set_rules('board', 'Board ID', 'trim|required');
		$this->form_validation->set_rules('medium', 'Medium', 'trim|required');

		$subjects = safe_array_key($this->_data, "subjects", "");
		if (empty($subjects)) {
			$this->form_validation->set_rules('subjects', 'subjects', 'trim');
		} else {
			$this->form_validation->set_rules('subjects', 'subjects', 'trim|callback__check_subjects');
		}
		
		$this->form_validation->set_rules('total_fee', 'Total Fee', 'trim|required');
		$this->form_validation->set_rules('batch', 'Batch', 'trim|required');
		$this->form_validation->set_rules('school', 'School', 'trim|required');
		$this->form_validation->set_rules('address', 'Address', 'trim|required');
		$this->form_validation->set_rules('mobile', 'Mobile no.', 'trim|required');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		// print_r($this->form_validation->run());
		// die();
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$reg_number = safe_array_key($this->_data, "reg_number", "");
			$reg_date = safe_array_key($this->_data, "reg_date", "");
			$first_name = safe_array_key($this->_data, "first_name", "");
			$last_name = safe_array_key($this->_data, "last_name", "");
			$father_name = safe_array_key($this->_data, "father_name", "");
			$mother_name = safe_array_key($this->_data, "mother_name", "");
			$dob = safe_array_key($this->_data, "dob", "");
			$medium = safe_array_key($this->_data, "medium", "");
			$subjects = safe_array_key($this->_data, "subjects", array());
			$class_guid = safe_array_key($this->_data, "class", "");
			$board_guid = safe_array_key($this->_data, "board", "");
			$batch_guid = safe_array_key($this->_data, "batch", "");
			$class_id = get_detail_by_guid($class_guid, 'class');
			$board_id = get_detail_by_guid($board_guid, 'board');
			$batch_id = get_detail_by_guid($batch_guid, 'batch');
			$total_fee = safe_array_key($this->_data, "total_fee", "");
			$remain_fee = safe_array_key($this->_data, "remain_fee", "");
			$profile_id = safe_array_key($this->_data, "profile_id", "");
			$school = safe_array_key($this->_data, "school", "");
			$address = safe_array_key($this->_data, "address", "");
			$mobile = safe_array_key($this->_data, "mobile", "");
			$alt_mobile = safe_array_key($this->_data, "alt_mobile", "");
			$email = safe_array_key($this->_data, "email", "");
			$status = safe_array_key($this->_data, "status", "");

			$student_id = $this->students_model->create_student($reg_number, $reg_date, $first_name, $last_name, $father_name,  $mother_name, $dob, $subjects, $class_id, $board_id, $medium,  $total_fee, $remain_fee, $batch_id, $profile_id, $school, $address, $mobile,$alt_mobile, $email, $status);
			$user_id = $this->students_model->create_student_user($reg_number, $reg_date, $first_name, $last_name, $father_name,  $mother_name, $dob, $subjects, $class_id, $board_id, $medium,  $total_fee, $remain_fee, $batch_id, $profile_id, $school, $address, $mobile,$alt_mobile, $email, $status);
			$this->_response["message"] = 'Student registered successfully';
			$this->set_response($this->_response);
	
		}
	}
	/**
 * VALIDATION FOR SPECIAL CHARACTERS
 */
public function _check_alpha_space($str) {
	if (!empty($str)) {
		if (!preg_match("/^[a-zA-Z.'\s]+$/", $str)) {
			$this->form_validation->set_message('_check_alpha_space', 'This field can contain only alphabets, dot & apostrophe');
			return FALSE;
		} else {
			return true;
		}

	}
}
	/**
 * STUDENTS LIST
 */
public function get_student_list_post() {
	$this->_response["service_name"] = "admin/get_student_list";
	$keyword = safe_array_key($this->_data, "keyword", "");
	$pagination = safe_array_key($this->_data, "pagination", []);
	$limit = safe_array_key($pagination, "limit", 10);
	$offset = safe_array_key($pagination, "offset", 0);
	$sort_by = safe_array_key($this->_data, "sort_by", []);
	$column_name = safe_array_key($sort_by, "column_name", 'first_name');
	$order_by = safe_array_key($sort_by, "order_by", 'acs');
	$filters = safe_array_key($this->_data, "filters", []);
	$status = safe_array_key($filters, "status", '');
	// $this->load->model("users_model");
	$this->_response["data"] = $this->students_model->get_student_list( $keyword, $limit, $offset, $column_name, $order_by, $status);
	$this->_response["counts"] = $this->students_model->get_student_list($keyword, 0, 0, $column_name, $order_by, $status);
	$this->_response["message"] = "Student List";
	$this->set_response($this->_response);
}

/**
	 * GET CRM CONTACT DETAILS BY ID API
	 */
	public function get_details_by_id_post() {
		$this->_response["service_name"] = "students/get_details_by_id";
		$this->form_validation->set_rules('student_id', 'Student Id', 'trim|required|callback__check_student_exist');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$student_guid = safe_array_key($this->_data, "student_id", "");
			$students = $this->app->get_row('students', 'student_id', ['student_guid' => $student_guid]);
			$student_id = safe_array_key($students, "student_id", "");
			$data = $this->students_model->get_details_by_id($student_id);
			$this->_response["data"] = $data;
			$this->_response["message"] = "student details";
			$this->set_response($this->_response);
		}
	}

/**
 * STUDENT UPDATE
 */
public function edit_student_post() {
	$this->_response["service_name"] = "students/edit_student";
	$this->form_validation->set_rules('student_id', 'Student id', 'trim|required');
	$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[2]|max_length[50]|callback__check_alpha_space');
	$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[2]|max_length[50]|callback__check_alpha_space');
	$this->form_validation->set_rules('father_name', 'Father Name', 'trim|required|min_length[2]|max_length[50]|callback__check_alpha_space');
	$this->form_validation->set_rules('mother_name', 'Mother Name', 'trim|required|min_length[2]|max_length[50]|callback__check_alpha_space');
	$this->form_validation->set_rules('class', 'Class', 'trim|required');
	$this->form_validation->set_rules('board', 'Board', 'trim|required');
	$this->form_validation->set_rules('medium', 'Medium', 'trim|required');
	$this->form_validation->set_rules('total_fee', 'Total Fee', 'trim|required');
	$this->form_validation->set_rules('remain_fee', 'Remaining Fee', 'trim|required');
	$this->form_validation->set_rules('batch', 'Batch', 'trim|required');
	$this->form_validation->set_rules('registration_date', 'Registration Date', 'trim|required');
	$this->form_validation->set_rules('address', 'Address', 'trim|required');
	$this->form_validation->set_rules('mobile', 'Mobile no.', 'trim|required');
	$this->form_validation->set_rules('status', 'Status', 'trim|required');
	if ($this->form_validation->run() == FALSE) {
		$errors = $this->form_validation->error_array();
		$this->_response["message"] = current($errors);
		$this->_response["errors"] = $errors;
		$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
	} else {
		$student_guid = safe_array_key($this->_data, "student_id", "");
		$student_id = get_detail_by_guid($student_guid, 'student');
		$first_name = safe_array_key($this->_data, "first_name", "");
		$last_name = safe_array_key($this->_data, "last_name", "");
		$father_name = safe_array_key($this->_data, "father_name", "");
		$mother_name = safe_array_key($this->_data, "mother_name", "");
		$dob = safe_array_key($this->_data, "dob", "");
		$class = safe_array_key($this->_data, "class", "");
		$board = safe_array_key($this->_data, "board", "");
		$medium = safe_array_key($this->_data, "medium", "");
		$total_fee = safe_array_key($this->_data, "total_fee", "");
		$remain_fee = safe_array_key($this->_data, "remain_fee", "");
		$batch = safe_array_key($this->_data, "batch", "");
		$registration_date = safe_array_key($this->_data, "registration_date", "");
		$profile_id = safe_array_key($this->_data, "profile_id", "");
		$school = safe_array_key($this->_data, "school", "");
		$address = safe_array_key($this->_data, "address", "");
		$mobile = safe_array_key($this->_data, "mobile", "");
		$email = safe_array_key($this->_data, "email", "");
		$status = safe_array_key($this->_data, "status", "");
		$this->_response["data"] = $this->students_model->update_student($student_id, $first_name, $last_name, $father_name,  $mother_name, $dob, $class, $board, $medium, $total_fee, $remain_fee, $batch, $registration_date, $profile_id, $school, $address, $mobile, $email, $status);
		$this->_response["message"] = "Success.";
		$this->set_response($this->_response);
	}
}
/**
* CHECK UNIQUE EMAIL
*/
public function __student($str, $user_id) {
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

public function _check_subjects($subjects) {
	$subjects = safe_array_key($this->_data, "subjects", []);
	foreach ($subjects as $v) {
		$this->db->select("*");
		$this->db->where("subject_guid", $v["subject_guid"]);
		if ($this->db->get('subjects')->num_rows() == 0) {
			$this->form_validation->set_message('_check_subject', $v["subject_guid"] . ' subject has invalid entry');
			return FALSE;
		}
	}
	return TRUE;
}
public function _check_reg_number($reg_number) {
	
	$student_data = $this->app->get_row('students', 'COUNT(student_id) as count', ['reg_number' => $reg_number]);
	
	if ($student_data['count'] >= 1) {
	$this->form_validation->set_message('_check_reg_number', 'This registration number already exist in students list');
	return FALSE;
	}else{
		return TRUE;
	}
	
}
public function _check_student_exist($student_guid) {
	if (!empty($student_guid)) {
		$student_data = $this->app->get_row('students', 'student_id', ['student_guid' => $student_guid]);
		
		if (empty($student_data)) {
			$this->form_validation->set_message('_check_student_exist', 'Not Student valid ID.');
			return FALSE;
		}
	}
	return TRUE;
}



}