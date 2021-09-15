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
class Crm extends REST_Controller {

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
		$this->load->model("crm_model");
		$this->load->library('MY_Form_validation');
	}

	public function index_get() {
		$this->set_response($this->_response);

	}

	public function add_post() {
		$this->_response["service_name"] = "Crm/add";
		$this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
		$this->form_validation->set_rules('source_type', 'Source', 'trim|required');
		$this->form_validation->set_rules('crm_contact_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('crm_contact_email', 'Email', 'trim|required|valid_email|callback__check_unique_email');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$this->load->model("users_model");
			$user_guid = safe_array_key($this->_data, "user_id", "");
			$added_by = get_detail_by_guid($user_guid, 'user');
			$source_type = safe_array_key($this->_data, "source_type", "Manual");
			$source_type_other = safe_array_key($this->_data, "source_type_other", "");
			$crm_contact_name = safe_array_key($this->_data, "crm_contact_name", "");
			$crm_contact_email = safe_array_key($this->_data, "crm_contact_email", "");
			$crm_contact_phone = safe_array_key($this->_data, "crm_contact_phone", "");
			$crm_contact_street = safe_array_key($this->_data, "crm_contact_street", "");
			$crm_contact_city = safe_array_key($this->_data, "crm_contact_city", "");
			$crm_contact_state = safe_array_key($this->_data, "crm_contact_state", "");
			$crm_contact_zipcode = safe_array_key($this->_data, "crm_contact_zipcode", "");
			$note = safe_array_key($this->_data, "note", "");
			$birthday_month = safe_array_key($this->_data, "birthday_month", "");
			$birthday_year = safe_array_key($this->_data, "birthday_year", "");
			$more_info = safe_array_key($this->_data, "more_info", "");

			$user_role = $this->users_model->get_user_role($added_by);
			$added_by_id = safe_array_key($user_role, "added_by", "");
			$user_role = safe_array_key($user_role, "role", "");
			if ($added_by_id && $user_role == 'TEAM') {
				$added_by = $added_by_id;
			}

			$sources = $this->app->get_rows('source_master', 'name', ['added_by' => 0]);
			$sources_array = [];
			foreach ($sources as $key => $value) {
				if(strtolower($value['name']) != 'other'){
					$sources_array[] = $value['name'];
				}
			}

			if (in_array(strtolower($source_type), $sources_array)){
				$source = $this->app->get_row('source_master', 'source_id', ['name' => $source_type]);
				$source_id = safe_array_key($source, "source_id", "");
			}else{
				$source_name = $source_type;
				if(strtolower($source_type) == 'other'){
					$source_name = $source_type_other;
				}
				$source_id = $this->crm_model->get_source_type_id(strtolower($source_name), $added_by);
			}
			$this->crm_model->add_crm_contact($added_by, $source_id, $crm_contact_name, $crm_contact_email, $crm_contact_phone, $crm_contact_street, $crm_contact_city, $crm_contact_state, $crm_contact_zipcode, $note, $birthday_month, $birthday_year, $more_info);
			$this->_response['message'] = "Added successfully.";
			$this->set_response($this->_response);
		}
	}

	public function list_post() {
		$this->_response["service_name"] = "Crm/list";
		$this->form_validation->set_rules('keyword', 'keyword', 'trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$this->load->model("users_model");
			// $user_id = $this->rest->user_id;
			// $user_type = get_detail_by_id($user_id, 'user', 'user_type');
			$user_guid = safe_array_key($this->_data, "user_id", "");
			$user_id = get_detail_by_guid($user_guid, 'user');
			$keyword = safe_array_key($this->_data, "keyword", "");
			$pagination = safe_array_key($this->_data, "pagination", []);
			$limit = safe_array_key($pagination, "limit", 10);
			$offset = safe_array_key($pagination, "offset", 0);
			$sort_by = safe_array_key($this->_data, "sort_by", []);
			$column_name = safe_array_key($sort_by, "column_name", 'first_name');
			$order_by = safe_array_key($sort_by, "order_by", 'acs');

			$user_role = $this->users_model->get_user_role($user_id);
			$added_by_id = safe_array_key($user_role, "added_by", "");
			$user_role = safe_array_key($user_role, "role", "");
			if ($added_by_id && $user_role == 'TEAM') {
				$user_id = $added_by_id;
			}
			$this->_response["data"] = $this->crm_model->list($user_id, $keyword, $limit, $offset, $column_name, $order_by);
			$this->_response["counts"] = $this->crm_model->list($user_id, $keyword, 0, 0, $column_name, $order_by);
			$this->set_response($this->_response);
		}
	}

	public function crm_contacts_post() {
		$this->_response["service_name"] = "Crm/crm_contacts";
		$this->form_validation->set_rules('keyword', 'keyword', 'trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_guid = safe_array_key($this->_data, "user_id", "");
			$user_id = get_detail_by_guid($user_guid, 'user');
			$keyword = safe_array_key($this->_data, "keyword", "");
			$pagination = safe_array_key($this->_data, "pagination", []);
			$limit = safe_array_key($pagination, "limit", 10);
			$offset = safe_array_key($pagination, "offset", 0);
			$sort_by = safe_array_key($this->_data, "sort_by", []);
			$column_name = safe_array_key($sort_by, "column_name", 'first_name');
			$order_by = safe_array_key($sort_by, "order_by", 'acs');

			$this->_response["data"] = $this->crm_model->crm_contacts($user_id, $keyword, $limit, $offset, $column_name, $order_by);
			$this->_response["counts"] = $this->crm_model->crm_contacts($user_id, $keyword, 0, 0, $column_name, $order_by);
			$this->set_response($this->_response);
		}
	}

	public function edit_crm_contact_post() {
		$this->_response["service_name"] = "crm/edit_crm_contact";
		$this->form_validation->set_rules('crm_contact_id', 'Contact Id', 'trim|required');
		$this->form_validation->set_rules('source_type', 'Source', 'trim|required');
		$this->form_validation->set_rules('crm_contact_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('crm_contact_email', 'Email', 'trim|required|valid_email');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$crm_contact_guid = safe_array_key($this->_data, "crm_contact_id", "");
			$crm_contact = $this->app->get_row('crm_contact', 'crm_contact_id, added_by', ['crm_contact_guid' => $crm_contact_guid]);
			$crm_contact_id = safe_array_key($crm_contact, "crm_contact_id", "");
			$added_by = safe_array_key($crm_contact, "added_by", "");
			$source_type = safe_array_key($this->_data, "source_type", "Manual");
			$source_type_other = safe_array_key($this->_data, "source_type_other", "");
			$crm_contact_name = safe_array_key($this->_data, "crm_contact_name", "");
			$crm_contact_email = safe_array_key($this->_data, "crm_contact_email", "");
			$crm_contact_phone = safe_array_key($this->_data, "crm_contact_phone", "");
			$crm_contact_street = safe_array_key($this->_data, "crm_contact_street", "");
			$crm_contact_city = safe_array_key($this->_data, "crm_contact_city", "");
			$crm_contact_state = safe_array_key($this->_data, "crm_contact_state", "");
			$crm_contact_zipcode = safe_array_key($this->_data, "crm_contact_zipcode", "");
			$note = safe_array_key($this->_data, "note", "");
			$birthday_month = safe_array_key($this->_data, "birthday_month", "");
			$birthday_year = safe_array_key($this->_data, "birthday_year", "");
			$more_info = safe_array_key($this->_data, "more_info", "");

			$sources = $this->app->get_rows('source_master', 'name', ['added_by' => 0]);
			$sources_array = [];
			foreach ($sources as $key => $value) {
				if(strtolower($value['name']) != 'other'){
					$sources_array[] = $value['name'];
				}
			}
			
			if (in_array(strtolower($source_type), $sources_array)){
				$source = $this->app->get_row('source_master', 'source_id', ['name' => $source_type]);
				$source_id = safe_array_key($source, "source_id", "");
			}else{
				$source_name = $source_type;
				if(strtolower($source_type) == 'other'){
					$source_name = $source_type_other;
				}
				$source_id = $this->crm_model->get_source_type_id(strtolower($source_name), $added_by);
			}

			$this->crm_model->edit_crm_contact($crm_contact_id, $source_id, $crm_contact_name, $crm_contact_email, $crm_contact_phone, $crm_contact_street, $crm_contact_city, $crm_contact_state, $crm_contact_zipcode, $note, $birthday_month, $birthday_year, $more_info);
			$this->_response['message'] = "Updated successfully.";
			$this->set_response($this->_response);
		}
	}

	/**
	 * GET CRM CONTACT DETAILS BY ID API
	 */
	public function get_details_by_id_post() {
		$this->_response["service_name"] = "crm/get_details_by_id";
		$this->form_validation->set_rules('crm_contact_id', 'contact id', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$crm_contact_guid = safe_array_key($this->_data, "crm_contact_id", "");
			$crm_contact = $this->app->get_row('crm_contact', 'crm_contact_id', ['crm_contact_guid' => $crm_contact_guid]);
			$crm_contact_id = safe_array_key($crm_contact, "crm_contact_id", "");
			$data = $this->crm_model->get_details_by_id($crm_contact_id);
			$this->_response["data"] = $data;
			$this->_response["message"] = "knowledgebase updated successfully";
			$this->set_response($this->_response);
		}
	}

	public function _check_unique_email($email) {
		$user_guid = safe_array_key($this->_data, "user_id", "");
		$added_by = get_detail_by_guid($user_guid, 'user');

		// $email_part = explode('@', $email);
		// $email_domain = end($email_part);
		// $email_domain = strtolower($email_domain);
		// if (in_array($email_domain, $this->app->disallowed_email_domains)) {
		// 	$this->form_validation->set_message('_check_unique_email', 'Only use corporate email address.');
		// 	return FALSE;
		// }

		$rows = $this->app->get_rows('crm_contact', 'crm_contact_guid', [
			'crm_contact_email' => strtolower($email),
			'added_by' => $added_by,
		]);

		if (count($rows) > 0) {
			$this->form_validation->set_message('_check_unique_email', 'Email already in use.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function download_sample_crm_file_get() {
		$this->_response["service_name"] = "CRM/download_sample_crm_file";
		$this->_response["data"] = site_url('assets/files/contact.xls');
		$this->set_response($this->_response);
	}

	public function add_notes_post() {
		$this->_response["service_name"] = "Crm/add_notes";
		$this->form_validation->set_rules('crm_contact_id', 'CRM Contact Id', 'trim|required');
		$this->form_validation->set_rules('note', 'Note', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$crm_contact_guid = safe_array_key($this->_data, "crm_contact_id", "");
			$crm_contact_id = get_detail_by_guid($crm_contact_guid, 'contact');
			$note = safe_array_key($this->_data, "note", "");
			$contact = $this->app->get_row('crm_contact', 'added_by', ['crm_contact_id' => $crm_contact_id]);
			$added_by = safe_array_key($contact, "added_by", "");
			$this->crm_model->add_notes($added_by, $crm_contact_id, $note);
			$this->_response['message'] = "Added successfully.";
			$this->set_response($this->_response);
		}
	}

	public function notes_list_post() {
		$this->_response["service_name"] = "Crm/notes_list";
		$this->form_validation->set_rules('crm_contact_id', 'CRM Contact Id', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			// $user_type = get_detail_by_id($user_id, 'user', 'user_type');
			$user_guid = safe_array_key($this->_data, "user_id", "");
			$user_id = get_detail_by_guid($user_guid, 'user');
			$keyword = safe_array_key($this->_data, "keyword", "");
			$pagination = safe_array_key($this->_data, "pagination", []);
			$limit = safe_array_key($pagination, "limit", 100000);
			$offset = safe_array_key($pagination, "offset", 0);
			$sort_by = safe_array_key($this->_data, "sort_by", []);
			$column_name = safe_array_key($sort_by, "column_name", '');
			$order_by = safe_array_key($sort_by, "order_by", '');

			$crm_contact_guid = safe_array_key($this->_data, "crm_contact_id", "");
			$crm_contact_id = get_detail_by_guid($crm_contact_guid, 'contact');

			$this->_response["data"] = $this->crm_model->notes_list($user_id, $keyword, $limit, $offset, $column_name, $order_by, $crm_contact_id);
			$this->_response["counts"] = $this->crm_model->notes_list($user_id, $keyword, 0, 0, $column_name, $order_by, $crm_contact_id);
			$this->set_response($this->_response);
		}
	}

	public function add_logs_post() {
		$this->_response["service_name"] = "Crm/add_logs";
		$this->form_validation->set_rules('crm_contact_id', 'CRM Contact Id', 'trim|required');
		$this->form_validation->set_rules('type', 'type', 'trim|in_list[CALL,EMAIL]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$crm_contact_guid = safe_array_key($this->_data, "crm_contact_id", "");
			$crm_contact_id = get_detail_by_guid($crm_contact_guid, 'contact');
			$type = safe_array_key($this->_data, "type", "");
			$crm_contact = $this->app->get_rows('crm_contact', 'added_by', ['crm_contact_id' => $crm_contact_id]);
			$added_by = safe_array_key($crm_contact, "added_by", "");
			$this->crm_model->add_logs($added_by, $crm_contact_id, $type);
			$this->_response['message'] = "Added successfully.";
			$this->set_response($this->_response);
		}
	}

	public function crm_logs_list_post() {
		$this->_response["service_name"] = "Crm/crm_logs_list";
		$this->form_validation->set_rules('crm_contact_id', 'CRM Contact Id', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			// $user_type = get_detail_by_id($user_id, 'user', 'user_type');
			$user_guid = safe_array_key($this->_data, "user_id", "");
			$user_id = get_detail_by_guid($user_guid, 'user');
			$keyword = safe_array_key($this->_data, "keyword", "");
			$pagination = safe_array_key($this->_data, "pagination", []);
			$limit = safe_array_key($pagination, "limit", 100000);
			$offset = safe_array_key($pagination, "offset", 0);
			$sort_by = safe_array_key($this->_data, "sort_by", []);
			$column_name = safe_array_key($sort_by, "column_name", '');
			$order_by = safe_array_key($sort_by, "order_by", '');

			$crm_contact_guid = safe_array_key($this->_data, "crm_contact_id", "");
			$crm_contact_id = get_detail_by_guid($crm_contact_guid, 'contact');

			$this->_response["data"] = $this->crm_model->logs_list($user_id, $keyword, $limit, $offset, $column_name, $order_by, $crm_contact_id);
			$this->_response["counts"] = $this->crm_model->logs_list($user_id, $keyword, 0, 0, $column_name, $order_by, $crm_contact_id);
			$this->set_response($this->_response);
		}
	}

	public function get_source_list_post() {
		$this->_response["service_name"] = "Crm/get_source_list";
		$this->form_validation->set_rules('keyword', 'keyword', 'trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_guid = safe_array_key($this->_data, "user_id", "");
			$user_id = get_detail_by_guid($user_guid, 'user');
			$this->_response["data"] = $this->crm_model->get_source_list($user_id);
			$this->set_response($this->_response);
		}
	}

}