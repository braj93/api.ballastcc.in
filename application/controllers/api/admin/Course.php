<?php

defined('BASEPATH') or exit('No direct script access allowed');

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
class Course extends REST_Controller
{

	var $_data = array();

	function __construct()
	{
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
		$this->load->model("uploads_model");
		$this->load->model("admin_model/chapter_model");
		$this->load->library('MY_Form_validation');
	}

	public function index_get()
	{
		$this->set_response($this->_response);
	}

	public function test_get()
	{
		echo 'Get Rest Controller of Course';
	}

	/**
	 * CREATE COURSE
	 */
	public function add_course_post()
	{
		$this->_response["service_name"] = "admin/addCourse";
		$user_id = $this->rest->user_id;

		$this->form_validation->set_rules('course_name', 'Course Name', 'trim|required|callback__check_unique_course');
		$this->form_validation->set_rules('description', 'Description', 'trim');
		$this->form_validation->set_rules('course_media_id', 'Course Media', 'trim|required');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$name = safe_array_key($this->_data, "course_name", "");
			$description = safe_array_key($this->_data, "description", "");
			$course_media_id = safe_array_key($this->_data, "course_media_id", "");
			$status = safe_array_key($this->_data, "status", "");
			$course_name = strtolower($name);
			if (!empty($course_media_id)) {
				$course_media_guid = safe_array_key($this->_data, "course_media_id", "");
				$media_data = $this->app->get_row('media', 'media_id, name', ['media_guid' => $course_media_guid]);
				$course_media_id = safe_array_key($media_data, "media_id", "");
				$media_id = $this->uploads_model->update_media_status($course_media_id, "ACTIVE");
			}
			$course_id = $this->course_model->create_course($course_name, $description, $course_media_id, $user_id, $status);
			$this->_response["message"] = 'Course created successfully';
			$this->set_response($this->_response);
		}
	}
	/**
	 * EDIT COURSE
	 */
	public function edit_course_post()
	{
		$this->_response["service_name"] = "admin/edit_course";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('course_id', 'Course Id', 'trim|required|callback__check_course_exist');
		$this->form_validation->set_rules('course_name', 'Name', 'trim|required|callback__check_unique_course');
		$this->form_validation->set_rules('course_media_id', 'Course Media', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$course_guid = safe_array_key($this->_data, "course_id", "");
			$course = $this->app->get_row('courses', 'course_id', ['course_guid' => $course_guid]);
			$course_id = safe_array_key($course, "course_id", "");
			$course_name = safe_array_key($this->_data, "course_name", "");
			$description = safe_array_key($this->_data, "description", "");
			$course_media_id = safe_array_key($this->_data, "course_media_id", "");
			$status = safe_array_key($this->_data, "status", "");
			if (!empty($course_media_id)) {
				$course_media_guid = safe_array_key($this->_data, "course_media_id", "");
				$media_data = $this->app->get_row('media', 'media_id, name', ['media_guid' => $course_media_guid]);
				$course_media_id = safe_array_key($media_data, "media_id", "");
				$media_id = $this->uploads_model->update_media_status($course_media_id, "ACTIVE");
			}
			$this->course_model->edit_course($course_id, $course_name, $description,$course_media_id, $user_id, $status);
			$this->_response['message'] = "course Updated successfully.";
			$this->set_response($this->_response);
		}
	}
	/**
	 * LIST COURSE
	 */
	public function courses_list_post()
	{
		$this->_response["service_name"] = "admin/courses_list";
		$this->form_validation->set_rules('keyword', 'keyword', 'trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			// $this->load->model("users_model");
			$user_id = $this->rest->user_id;
			$user = $this->app->get_row('users', 'user_type', ['user_id' => $user_id]);
			$user_type = safe_array_key($user, "user_type", "");
			$keyword = safe_array_key($this->_data, "keyword", "");
			$pagination = safe_array_key($this->_data, "pagination", []);
			$limit = safe_array_key($pagination, "limit", 10);
			$offset = safe_array_key($pagination, "offset", 0);
			$sort_by = safe_array_key($this->_data, "sort_by", []);
			$column_name = safe_array_key($sort_by, "column_name", 'course_name');
			$order_by = safe_array_key($sort_by, "order_by", 'acs');			
			$this->_response["data"] = $this->course_model->list($user_id,  $column_name, $order_by, $user_type,$keyword, $limit, $offset);
			$this->_response["counts"] = $this->course_model->list($user_id,  $column_name, $order_by, $user_type,$keyword, 0, 0);
			$this->set_response($this->_response);
		}
	}

	/**
	 * GET Course DETAILS BY ID API
	 */
	public function get_details_by_id_post()
	{
		$this->_response["service_name"] = "course/get_details_by_id";
		$this->form_validation->set_rules('course_id', 'Course Id', 'trim|required|callback__check_course_exist');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			// $campaign_guid = safe_array_key($this->_data, "campaign_id", "");
			// $campaign = $this->app->get_row('campaigns', 'campaign_id', ['campaign_guid' => $campaign_guid]);
			// $campaign_id = safe_array_key($campaign, "campaign_id", "");
			$user_id = $this->rest->user_id;
			$user = $this->app->get_row('users', 'user_type', ['user_id' => $user_id]);
			$user_type = safe_array_key($user, "user_type", "");

			$course_guid = safe_array_key($this->_data, "course_id", "");
			$course = $this->app->get_row('courses', 'course_id', ['course_guid' => $course_guid]);
			$course_id = safe_array_key($course, "course_id", "");
			$data = $this->course_model->get_details_by_id($course_id);
			$this->_response["data"] = $data;
			$this->_response["data"]['chapters'] = $this->chapter_model->get_chapters($course_id);
			$this->_response["message"] = "course details";
			$this->set_response($this->_response);
		}
	}



	public function _check_unique_course($str)
	{
		$course_guid = safe_array_key($this->_data, "course_id", "");
		if (!empty($course_guid)) {
			$rows = $this->app->get_rows('courses', 'course_guid', [
				'course_name' => strtolower($str),
				"course_guid !=" => $course_guid
			]);
		} else {
			$rows = $this->app->get_rows('courses', 'course_guid', [
				'course_name' => strtolower($str),
			]);
		}


		if (count($rows) > 0) {
			$this->form_validation->set_message('_check_unique_course', 'Course Name already in use.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	public function _check_course_exist($course_guid)
	{
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
