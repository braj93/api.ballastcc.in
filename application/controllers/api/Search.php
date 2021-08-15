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
class Search extends REST_Controller {

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
		$this->load->model("search_model");
	}

	public function index_get() {
		$this->set_response($this->_response);
	}

	public function get_masters_post() {
		$this->_response["service_name"] = "search/get_masters";
		$moods = $this->song_model->moods();

		$data = [
			'moods' => $moods
		];
		$this->_response["data"] = $data;
		$this->_response["message"] = 'Masters data';
		$this->set_response($this->_response);
	}

	public function autosuggest_post() {
		$this->_response["service_name"] = "search/autosuggest";
		$session_key = $this->rest->key;
		$types = array('company', 'education', 'skill', 'location', 'composition_tag' , 'master_tag');
		$this->form_validation->set_rules('type', 'type', 'trim|required|in_list[' . implode($types, ",") . ']');
		$this->form_validation->set_rules('keyword', 'keyword', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {

			$type = safe_array_key($this->_data, "type", "");
			$user_id = safe_array_key($this->_data, "user_id", "");
			$keyword = safe_array_key($this->_data, "keyword", "");

			$entity_type = safe_array_key($this->_data, "entity_type", "");
			$entity_guid = safe_array_key($this->_data, "entity_guid", "");
			$entity_id = 0;

			if (!empty($entity_type) && !empty($entity_guid)) {
				$entity_id = get_guid_detail($entity_guid, $entity_type);
			}

			$this->_response["data"] = $this->search_model->autosuggest($type, $keyword, $entity_type, $entity_id);

			$this->set_response($this->_response);
		}
	}
}
