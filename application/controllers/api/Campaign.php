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
class Campaign extends REST_Controller {

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
		$this->load->model("campaign_model");
		$this->load->library('MY_Form_validation');
	}

	public function index_get() {
		$this->set_response($this->_response);

	}

	public function _check_user_exist($user_guid) {
		if (!empty($user_guid)) {

			$user = $this->app->get_row('users', 'user_id, status', ['user_guid' => $user_guid]);

			if (!empty($user)) {
				if ($user['status'] != 'ACTIVE') {
					$this->form_validation->set_message('_check_user_exist', 'User is not Active.');
					return FALSE;
				}
			} else {
				$this->form_validation->set_message('_check_user_exist', 'User not found.');
				return FALSE;
			}
		}
		return TRUE;
	}

	public function _check_campaign_limit() {
		$session_key = $this->rest->key;
		if (!empty($session_key)) {
			$this->load->model("users_model");
			$added_by = $this->rest->user_id;
			$user_role_om = $this->users_model->get_user_role($added_by);
			$added_by_id = safe_array_key($user_role_om, "added_by", "");
			$user_role_om = safe_array_key($user_role_om, "role", "");
			if ($added_by_id && $user_role_om == 'TEAM') {
				$added_by = $added_by_id;
			}
			$user = $this->app->user_data($session_key);
			// $campaigns = $this->app->get_rows('campaigns', 'campaign_id', ['added_by' => $added_by]);
			// if(!empty($campaigns) && ($user['user_role'] === 'USER_INDIVIDUAL_TEAM' || $user['user_role'] === 'USER_INDIVIDUAL_OWNER') && $user['plan_name'] === 'ESSENTIAL' && count($campaigns) >= 1) {
			// 	$this->form_validation->set_message('_check_campaign_limit', 'Campaign limit reached.');
			// 	return FALSE;
			// } else if (!empty($campaigns) && ($user['user_role'] === 'USER_INDIVIDUAL_TEAM' || $user['user_role'] === 'USER_INDIVIDUAL_OWNER') && $user['plan_name'] === 'PRO' && count($campaigns) >= 5) {
			// 	$this->form_validation->set_message('_check_campaign_limit', 'Campaign limit reached.');
			// 	return FALSE;
			// }
			$campaign_limit = $this->app->getPlanCampaignLimit($added_by);
			$plan_name = $this->app->getPlanName($added_by);
			$can_add_campaign = $this->app->canAddCampaign($added_by, $user['user_role'], $plan_name, $campaign_limit);
			if($can_add_campaign === 'NO') {
				$this->form_validation->set_message('_check_campaign_limit', 'Campaign limit reached.');
				return FALSE;
			}
		}
		return TRUE;
	}

	public function _check_user_campaign_limit() {
		$user_guid = safe_array_key($this->_data, "user_id", "");
		$user = $this->app->get_row('users', 'user_id', ['user_guid' => $user_guid]);
		$added_by = safe_array_key($user, "user_id", "");
		if (!empty($added_by)) {
			$this->load->model("users_model");
			$user_role_om = $this->users_model->get_user_role($added_by);
			$added_by_id = safe_array_key($user_role_om, "added_by", "");
			$user_role_om = safe_array_key($user_role_om, "role", "");
			if ($added_by_id && $user_role_om == 'TEAM') {
				$added_by = $added_by_id;
			}
			$user = $this->app->user_detail_by_id($added_by);
			$campaign_limit = $this->app->getPlanCampaignLimit($added_by);
			$plan_name = $this->app->getPlanName($added_by);
			$can_add_campaign = $this->app->canAddCampaign($added_by, $user['user_role'], $plan_name, $campaign_limit);
			if($can_add_campaign === 'NO') {
				$this->form_validation->set_message('_check_campaign_limit', 'Campaign limit reached.');
				return FALSE;
			}
		}
		return TRUE;
	}

	public function add_campaign_post() {
		
		$user_id = $this->rest->user_id;
		$session_user = $this->app->get_row('users', 'user_id, user_type', ['user_id' => $user_id]);
		$user_type = safe_array_key($session_user, "user_type", "");
		$this->_response["service_name"] = "campaign/add_campaign";
		// $this->form_validation->set_rules('campaign_name', 'Name', 'trim|required|callback__check_campaign_limit');
		$this->form_validation->set_rules('campaign_goal', 'Campaign Goal', 'trim|required');
		$this->form_validation->set_rules('campaign_live_date', 'Campaign Go live Date', 'trim|required');
		$this->form_validation->set_rules('is_landing_page', 'landing page', 'trim|required|in_list[YES,NO]');
		$this->form_validation->set_rules('is_qr_code', 'QR Code', 'trim|required|in_list[YES,NO]');
		$this->form_validation->set_rules('is_call_tracking_number', 'Call Tracking Number', 'trim|required|in_list[YES,NO]');
		if($user_type === 'ADMIN'){
			$this->form_validation->set_rules('user_id', 'User', 'trim|required|callback__check_user_exist');
		}
		if($user_type === 'ADMIN'){
			$this->form_validation->set_rules('campaign_name', 'Name', 'trim|required|callback__check_user_campaign_limit');
		} else {
			$this->form_validation->set_rules('campaign_name', 'Name', 'trim|required|callback__check_campaign_limit');
		}
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$this->load->model("users_model");
			if($user_type === 'ADMIN'){
				$user_guid = safe_array_key($this->_data, "user_id", "");
				$user = $this->app->get_row('users', 'user_id', ['user_guid' => $user_guid]);
				$added_by = safe_array_key($user, "user_id", "");
			}else{
				$added_by = $this->rest->user_id;
				$user_role = $this->users_model->get_user_role($added_by);
				$added_by_id = safe_array_key($user_role, "added_by", "");
				$user_role = safe_array_key($user_role, "role", "");
				if ($added_by_id && $user_role == 'TEAM') {
					$added_by = $added_by_id;
				}
			}
			$campaign_name = safe_array_key($this->_data, "campaign_name", "");
			$campaign_goal = safe_array_key($this->_data, "campaign_goal", "");
			$campaign_live_date = safe_array_key($this->_data, "campaign_live_date", "");
			$is_landing_page = safe_array_key($this->_data, "is_landing_page", "NO");
			$is_qr_code = safe_array_key($this->_data, "is_qr_code", "NO");
			$is_call_tracking_number = safe_array_key($this->_data, "is_call_tracking_number", "NO");
			$campaign_id = $this->campaign_model->add_campaign($added_by, $campaign_name, $campaign_goal, $campaign_live_date, $is_landing_page, $is_qr_code, $is_call_tracking_number);
			$campaign = $this->app->get_row('campaigns', 'campaign_guid', ['campaign_id' => $campaign_id]);
			$this->_response['data'] = $campaign;
			$this->_response['message'] = "Added successfully.";
			$this->set_response($this->_response);
		}
	}

	public function campaign_list_post() {
		$this->_response["service_name"] = "campaign/campaign_list";
		$this->form_validation->set_rules('keyword', 'keyword', 'trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$this->load->model("users_model");
			$user_id = $this->rest->user_id;

			$user = $this->app->get_row('users', 'user_type', ['user_id' => $user_id]);
			$user_type = safe_array_key($user, "user_type", "");
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
			$this->_response["data"] = $this->campaign_model->list($user_id, $keyword, $limit, $offset, $column_name, $order_by, $user_type);
			$this->_response["counts"] = $this->campaign_model->list($user_id, $keyword, 0, 0, $column_name, $order_by, $user_type);
			$this->set_response($this->_response);
		}
	}

	public function edit_campaign_post() {
		$this->_response["service_name"] = "campaign/edit_campaign";
		$this->form_validation->set_rules('campaign_id', 'Campaign Id', 'trim|required|callback__check_campaign_exist');
		$this->form_validation->set_rules('campaign_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('campaign_goal', 'Campaign Goal', 'trim|required');
		$this->form_validation->set_rules('campaign_live_date', 'Campaign Go live Date', 'trim|required');
		$this->form_validation->set_rules('is_landing_page', 'landing page', 'trim|required|in_list[YES,NO]');
		$this->form_validation->set_rules('is_qr_code', 'QR Code', 'trim|required|in_list[YES,NO]');
		$this->form_validation->set_rules('is_call_tracking_number', 'Call Tracking Number', 'trim|required|in_list[YES,NO]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$campaign_guid = safe_array_key($this->_data, "campaign_id", "");
			$campaign = $this->app->get_row('campaigns', 'campaign_id', ['campaign_guid' => $campaign_guid]);
			$campaign_id = safe_array_key($campaign, "campaign_id", "");
			$campaign_name = safe_array_key($this->_data, "campaign_name", "");
			$campaign_goal = safe_array_key($this->_data, "campaign_goal", "");
			$campaign_live_date = safe_array_key($this->_data, "campaign_live_date", "");
			$is_landing_page = safe_array_key($this->_data, "is_landing_page", "NO");
			$is_qr_code = safe_array_key($this->_data, "is_qr_code", "NO");
			$is_call_tracking_number = safe_array_key($this->_data, "is_call_tracking_number", "NO");

			$campaign = $this->app->get_row('campaigns', 'qr_code_name, qr_code_url, campaign_template_id, tracking_number_json, tracking_number_id, number_type, area_code', ['campaign_id' => $campaign_id]);

			$qr_code_name = safe_array_key($campaign, "qr_code_name", NULL);
			$qr_code_url = safe_array_key($campaign, "qr_code_url", NULL);
			$campaign_template_id = safe_array_key($campaign, "campaign_template_id", NULL);
			$tracking_number_json = safe_array_key($campaign, "tracking_number_json", NULL);
			$tracking_number_id = safe_array_key($campaign, "tracking_number_id", NULL);
			$number_type = safe_array_key($campaign, "number_type", NULL);
			$area_code = safe_array_key($campaign, "area_code", NULL);

			if($is_qr_code == 'NO'){
				$qr_code_name = NULL;
				$qr_code_url = NULL;
			} 

			if ($is_landing_page == 'NO'){
				if ($campaign_template_id) {
					$this->campaign_model->delete_campaign_template($campaign_template_id);
				}
				$campaign_template_id = NULL;
			}

			if ($is_call_tracking_number == 'NO') {
				$tracking_number_json = NULL;
				$tracking_number_id = NULL;
				$number_type = NULL;
				$area_code = NULL;
			}

			$this->campaign_model->edit_campaign($campaign_id, $campaign_name, $campaign_goal, $campaign_live_date, $is_landing_page, $is_qr_code, $is_call_tracking_number, $qr_code_name,$qr_code_url, $campaign_template_id, $tracking_number_json, $tracking_number_id, $number_type, $area_code);

			// $this->campaign_model->edit_campaign($campaign_id, $campaign_name, $campaign_goal, $campaign_live_date, $is_landing_page, $is_qr_code, $is_call_tracking_number);
			$this->_response['message'] = "Updated successfully.";
			$this->set_response($this->_response);
		}
	}

	/**
	 * GET CRM CONTACT DETAILS BY ID API
	 */
	public function get_details_by_id_post() {
		$this->_response["service_name"] = "campaign/get_details_by_id";
		$this->form_validation->set_rules('campaign_id', 'Campaign Id', 'trim|required|callback__check_campaign_exist');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$campaign_guid = safe_array_key($this->_data, "campaign_id", "");
			$campaign = $this->app->get_row('campaigns', 'campaign_id', ['campaign_guid' => $campaign_guid]);
			$campaign_id = safe_array_key($campaign, "campaign_id", "");
			$data = $this->campaign_model->get_details_by_id($campaign_id);
			$this->_response["data"] = $data;
			$this->_response["message"] = "campaign details";
			$this->set_response($this->_response);
		}
	}

	public function _check_campaign_exist($campaign_guid) {
		if (!empty($campaign_guid)) {
			$organization_member = $this->app->get_row('campaigns', 'campaign_id', ['campaign_guid' => $campaign_guid]);
			
			if (empty($organization_member)) {
				$this->form_validation->set_message('_check_campaign_exist', 'Not valid ID.');
				return FALSE;
			}
		}
		return TRUE;
	}

	public function template_list_post() {
		$this->_response["service_name"] = "campaign/template_list";
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

			$this->_response["data"] = $this->campaign_model->template_list($keyword, $limit, $offset, $column_name, $order_by);
			$this->_response["counts"] = $this->campaign_model->template_list($keyword, 0, 0, $column_name, $order_by);
			$this->set_response($this->_response);
		}
	}

	public function _check_template_exist($template_guid) {
		if (!empty($template_guid)) {
			$template = $this->app->get_row('templates', 'template_id', ['template_guid' => $template_guid]);
			
			if (empty($template)) {
				$this->form_validation->set_message('_check_template_exist', 'Not valid ID.');
				return FALSE;
			}
		}
		return TRUE;
	}

	public function add_campaign_template_post() {
		$this->_response["service_name"] = "campaign/add_campaign_template";
		$this->form_validation->set_rules('campaign_id', 'Campaign Id', 'trim|required|callback__check_campaign_exist');
		$this->form_validation->set_rules('template_id', 'Template Id', 'trim|required|callback__check_template_exist');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$campaign_guid = safe_array_key($this->_data, "campaign_id", "");
			$campaign = $this->app->get_row('campaigns', 'campaign_id', ['campaign_guid' => $campaign_guid]);
			$campaign_id = safe_array_key($campaign, "campaign_id", "");
			$template_guid = safe_array_key($this->_data, "template_id", "");
			$template = $this->app->get_row('templates', 'template_id, default_values', ["template_guid" => $template_guid]);
			$template_id = safe_array_key($template, "template_id", "");
			$default_values = safe_array_key($template, "default_values", NULL);

			$campaign_template_id = $this->campaign_model->add_campaign_template($template_id, $default_values);
			$this->campaign_model->update_template($campaign_id, $campaign_template_id);
			$campaign_template = $this->app->get_row('campaign_templates', 'campaign_template_guid', ['campaign_template_id' => $campaign_template_id]);
			$campaign_template_guid = safe_array_key($campaign_template, "campaign_template_guid", "");
			$this->_response['message'] = "Template added successfully.";
			$this->_response['data'] = ["campaign_id" =>$campaign_guid];
			$this->set_response($this->_response);
		}
	}

	public function update_campaign_template_post() {
		$this->_response["service_name"] = "campaign/update_campaign_template";
		$this->form_validation->set_rules('campaign_id', 'Campaign Id', 'trim|required|callback__check_campaign_exist');
		$this->form_validation->set_rules('template_id', 'Template Id', 'trim|required|callback__check_template_exist');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$campaign_guid = safe_array_key($this->_data, "campaign_id", "");
			$campaign = $this->app->get_row('campaigns', 'campaign_id, campaign_template_id', ['campaign_guid' => $campaign_guid]);
			$campaign_id = safe_array_key($campaign, "campaign_id", "");
			$campaign_template_id = safe_array_key($campaign, "campaign_template_id", "");
			$template_guid = safe_array_key($this->_data, "template_id", "");
			$template = $this->app->get_row('templates', 'template_id, default_values', ["template_guid" => $template_guid]);
			$template_id = safe_array_key($template, "template_id", "");
			$default_values = safe_array_key($template, "default_values", NULL);
			$this->campaign_model->update_campaign_template($campaign_template_id, $template_id, $default_values);
			$campaign_template = $this->app->get_row('campaign_templates', 'campaign_template_guid', ['campaign_template_id' => $campaign_template_id]);
			$campaign_template_guid = safe_array_key($campaign_template, "campaign_template_guid", "");
			$this->_response['message'] = "Template updated successfully.";
			$this->_response['data'] = ["campaign_id" =>$campaign_guid];
			$this->set_response($this->_response);
		}
	}

	
	/**
	 * GET TEMPLATE DETAILS BY ID API
	 */
	public function get_template_details_by_campaign_id_post() {
		$this->_response["service_name"] = "campaign/get_template_details_by_campaign_id";
		$this->form_validation->set_rules('campaign_id', 'Campaign Id', 'trim|required|callback__check_campaign_exist');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$campaign_guid = safe_array_key($this->_data, "campaign_id", "");
			$campaign = $this->app->get_row('campaigns', 'campaign_id', ['campaign_guid' => $campaign_guid]);
			$campaign_id = safe_array_key($campaign, "campaign_id", "");
			$data = $this->campaign_model->get_template_details($campaign_id);
			$this->_response["data"] = $data;
			$this->_response["message"] = "campaign details";
			$this->set_response($this->_response);
		}
	}

	public function update_template_details_post() {
		$this->_response["service_name"] = "campaign/update_template_details";
		$this->form_validation->set_rules('campaign_id', 'Campaign Id', 'trim|required|callback__check_campaign_exist');
		$this->form_validation->set_rules('template_values', 'Template Values', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$campaign_guid = safe_array_key($this->_data, "campaign_id", "");
			$campaign = $this->app->get_row('campaigns', 'campaign_id, campaign_template_id', ['campaign_guid' => $campaign_guid]);
			$campaign_id = safe_array_key($campaign, "campaign_id", "");
			$campaign_template_id = safe_array_key($campaign, "campaign_template_id", "");
			$campaign_template = $this->app->get_row('campaign_templates', 'unique_string', ['campaign_template_id' => $campaign_template_id]);
			$unique_string = safe_array_key($campaign_template, "unique_string", "");
			$template_values = safe_array_key($this->_data, "template_values", "");
			$this->campaign_model->update_template_details($campaign_template_id, $template_values);
			if(!$unique_string){
				$unique_string = unique_random_string('campaign_templates', 'unique_string', [], 'alnum', 12);
				$this->campaign_model->update_url_string($campaign_template_id, $unique_string);
			}
			$this->_response['message'] = "Template updated successfully.";
			$this->set_response($this->_response);
		}
	}

	public function update_campaign_setting_post() {
		$this->_response["service_name"] = "campaign/update_campaign_setting";
		$this->form_validation->set_rules('campaign_id', 'Campaign Id', 'trim|required|callback__check_campaign_exist');
		$this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
		$this->form_validation->set_rules('email_receiver', 'Form Submission Email', 'trim|required');
		$this->form_validation->set_rules('page_url', 'Url', 'trim|required');
		// $this->form_validation->set_rules('custom_page_script', 'Script', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$campaign_guid = safe_array_key($this->_data, "campaign_id", "");
			$campaign = $this->app->get_row('campaigns', 'campaign_id, campaign_template_id', ['campaign_guid' => $campaign_guid]);
			$campaign_id = safe_array_key($campaign, "campaign_id", "");
			$campaign_template_id = safe_array_key($campaign, "campaign_template_id", "");
			$page_title = safe_array_key($this->_data, "page_title", "");
			$email_receiver = safe_array_key($this->_data, "email_receiver", "");
			$page_url = safe_array_key($this->_data, "page_url", "");
			$custom_page_script = safe_array_key($this->_data, "custom_page_script", "");
			$this->campaign_model->update_campaign_setting($campaign_template_id, $page_title, $email_receiver, $page_url, $custom_page_script);
			$this->_response['message'] = "Publish Successfully.";
			$this->set_response($this->_response);
		}
	}

	public function generate_qr_code_post() {
		// echo 'YES';die();
		$this->_response["service_name"] = "campaign/generate_qr_code";
		$this->form_validation->set_rules('campaign_id', 'Campaign Id', 'trim|required|callback__check_campaign_exist');
		$this->form_validation->set_rules('qr_code_url', 'QR Code Url', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$campaign_guid = safe_array_key($this->_data, "campaign_id", "");
			$campaign = $this->app->get_row('campaigns', 'campaign_id, campaign_template_id, is_landing_page, campaign_name', ['campaign_guid' => $campaign_guid]);
			$campaign_id = safe_array_key($campaign, "campaign_id", "");
			$campaign_template_id = safe_array_key($campaign, "campaign_template_id", "");
			$campaign_name = safe_array_key($campaign, "campaign_name", "");
			$is_landing_page = safe_array_key($campaign, "is_landing_page", "NO");
			$qr_code_url = safe_array_key($this->_data, "qr_code_url", "");
			if ($is_landing_page !== 'YES') {
				
				$redirect_url = $qr_code_url;
				$unique_string = unique_random_string('campaign_templates', 'unique_string', [], 'alnum', 12);
				$name = strtolower($campaign_name);
				$name = str_replace(' ', '-', $name);
				$qr_code_url = SITE_ROOT . '/' . $name . '/' . $unique_string;

				if (!$campaign_template_id) {
					$campaign_template_id = $this->campaign_model->add_campaign_qr_code($redirect_url, $unique_string);
					$this->campaign_model->update_template($campaign_id, $campaign_template_id);
				} else {
					$this->campaign_model->update_campaign_qr($campaign_template_id, $redirect_url, $unique_string);
				}
			}
			if ($qr_code_url) {
				$unique_string = unique_random_string('campaign_templates', 'unique_string', [], 'alnum', 12);
				if (parse_url($qr_code_url, PHP_URL_QUERY)) {
					$qr_code_url = $qr_code_url.'&&qr='.$unique_string;
				} else {
					if (strstr($qr_code_url, '?')) {
						$qr_code_url = substr($qr_code_url, 0, strpos($qr_code_url, '?'));
					}
   					$qr_code_url = trim($qr_code_url, '/');
					$qr_code_url = $qr_code_url.'?qr='.$unique_string;
				}
			}
			$qr_code_name = $this->app->create_qr_code($qr_code_url);
			$this->campaign_model->update_campaign_qr_code($campaign_id, $qr_code_name, $qr_code_url);
			$data = $this->campaign_model->get_details_by_id($campaign_id);
			$this->_response["data"] = $data;
			$this->_response['message'] = "QR code Updated Successfully.";
			$this->set_response($this->_response);
		}
	}

	public function get_campaign_details_by_id_post() {
		$this->_response["service_name"] = "campaign/get_campaign_details_by_id";
		$this->form_validation->set_rules('campaign_id', 'Campaign Id', 'trim|required|callback__check_campaign_exist');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$campaign_guid = safe_array_key($this->_data, "campaign_id", "");
			$campaign = $this->app->get_row('campaigns', 'campaign_id', ['campaign_guid' => $campaign_guid]);
			$campaign_id = safe_array_key($campaign, "campaign_id", "");
			$data = $this->campaign_model->get_campaign_details_by_id($campaign_id);
			$this->_response["data"] = $data;
			$this->_response["message"] = "campaign details";
			$this->set_response($this->_response);
		}
	}

	public function get_campaign_report_details_post() {
		$this->_response["service_name"] = "campaign/get_campaign_report_details";
		$this->form_validation->set_rules('campaign_id', 'Campaign Id', 'trim|required|callback__check_campaign_exist');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$campaign_guid = safe_array_key($this->_data, "campaign_id", "");
			$campaign = $this->app->get_row('campaigns', 'tracking_number_id, is_landing_page, is_qr_code, is_call_tracking_number', ['campaign_guid' => $campaign_guid]);
			$tracking_number_id = safe_array_key($campaign, "tracking_number_id", "");
			$is_landing_page = safe_array_key($campaign, "is_landing_page", "NO");
			$is_qr_code = safe_array_key($campaign, "is_qr_code", "");
			$is_call_tracking_number = safe_array_key($campaign, "is_call_tracking_number", "");
			$data = $this->campaign_model->get_campaign_report_details($campaign_guid, $tracking_number_id, $is_landing_page, $is_qr_code, $is_call_tracking_number);
			$this->_response["data"] = $data;
			$this->_response["message"] = "campaign details";
			$this->set_response($this->_response);
		}
	}

	public function create_call_tracking_number_post() {
		$this->_response["service_name"] = "campaign/create_call_tracking_number";
		$this->form_validation->set_rules('campaign_id', 'Campaign Id', 'trim|required|callback__check_campaign_exist');
		$this->form_validation->set_rules('destination_number', 'Destination Number', 'trim|required');
		$this->form_validation->set_rules('number_name', 'Name', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$campaign_guid = safe_array_key($this->_data, "campaign_id", "");
			$destination_number = safe_array_key($this->_data, "destination_number", "");
			$number_name = safe_array_key($this->_data, "number_name", "");
			$number_type = safe_array_key($this->_data, "number_type", "");
			$area_code = safe_array_key($this->_data, "area_code", "");

			$campaign = $this->app->get_row('campaigns', 'campaign_id, campaign_name, added_by, tracking_number_id', ['campaign_guid' => $campaign_guid]);
			$campaign_id = safe_array_key($campaign, "campaign_id", "");
			$campaign_name = safe_array_key($campaign, "campaign_name", "");
			$user_id = safe_array_key($campaign, "added_by", "");
			$old_tracking_number_id = safe_array_key($campaign, "tracking_number_id", NULL);


			$organization_member = $this->app->get_row('organization_members', 'organization_id', ['user_id' => $user_id]);
			$organization_id = safe_array_key($organization_member, "organization_id", "");

			$organization = $this->app->get_row('organizations', 'name, callrail_company_id', ['organization_id' => $organization_id]);
			$organization_name = safe_array_key($organization, "name", "");
			$callrail_company_id = safe_array_key($organization, "callrail_company_id", "");

			if ($callrail_company_id) {
				$callrail_company_id = $callrail_company_id; 
			} else {
				$call_tracking_company_json = $this->campaign_model->create_call_tracking_company($organization_name);

				if ($call_tracking_company_json['status']) {
					$callrail_company_id = $call_tracking_company_json['result']['id'];
					$this->campaign_model->update_call_tracking_company($organization_id, $callrail_company_id);
				} else {
					$this->_response["message"] = 'Something Went Wrong';
					$this->_response["errors"] = $call_tracking_company_json['result'];
					$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
				}
			}

			if ($number_type === 'TOLLFREE') {
				$toll_free = TRUE;
				$tracking_array = [
					"toll_free" => $toll_free
				];
			} else {
				$tracking_array = [
					"area_code" => $area_code
				];
			}

			// $number_name = $number_name. ' | '.$campaign_name;
			$tracking_number_json = $this->campaign_model->create_call_tracking_number($callrail_company_id, $destination_number, $number_name, $tracking_array);
			
			if ($tracking_number_json['status']) {
				$tracking_number_id = $tracking_number_json['result']['id'];
				$tracking_number_json_encode = json_encode($tracking_number_json);
				$this->campaign_model->disable_call_tracking_number($old_tracking_number_id);
				$this->campaign_model->update_call_tracking_number($campaign_id, $tracking_number_json_encode, $tracking_number_id, $number_type, $area_code);
				$this->_response['message'] = $tracking_number_json['message'];
				$this->_response['data'] = [];
				$this->set_response($this->_response);
			} else {
				$this->_response["message"] = $tracking_number_json['message'];
				$this->_response["errors"] = $tracking_number_json['result'];
				$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
			}
		}
	}

	public function update_template_default_values_post(){
		$this->_response["service_name"] = "campaign/update_template_default_values";
		$this->form_validation->set_rules('default_values', 'Default Values', 'trim|required');
		$this->form_validation->set_rules('template_id', 'Template id', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$default_values = safe_array_key($this->_data, "default_values", "");
			$template_guid = safe_array_key($this->_data, "template_id", "");

			$templates = $this->app->get_row('templates', 'template_id', ['template_guid' => $template_guid]);
			$template_id = safe_array_key($templates, "template_id", "");
			$this->campaign_model->update_template_default_values($template_id, $default_values);
			$this->_response["message"] = "Update Values";
			$this->set_response($this->_response);
		}
	}

	public function get_tracker_details_post(){
		$this->_response["service_name"] = "campaign/get_tracker_details";
		$this->form_validation->set_rules('campaign_id', 'Campaign id', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$campaign_guid = safe_array_key($this->_data, "campaign_id", "");
			$campaign = $this->app->get_row('campaigns', 'campaign_id, tracking_number_id', ['campaign_guid' => $campaign_guid]);
			$campaign_id = safe_array_key($campaign, "campaign_id", "");
			$tracking_number_id = safe_array_key($campaign, "tracking_number_id", NULL);
			if ($tracking_number_id) {
				$data = $this->campaign_model->get_tracker_details($tracking_number_id);
				$this->_response['data'] = $data['result'];
				$this->_response["message"] = "Detail Values";
				$this->set_response($this->_response);
			} else {
				$this->_response['data'] = (object) [];
				$this->_response["message"] = "Detail Values123";
				$this->set_response($this->_response);
			}
		}
	}

	public function update_tracking_number_post() {

		$this->_response["service_name"] = "campaign/update_tracking_number";
		$this->form_validation->set_rules('campaign_id', 'Campaign Id', 'trim|required|callback__check_campaign_exist');
		$this->form_validation->set_rules('destination_number', 'Destination Number', 'trim|required');
		$this->form_validation->set_rules('number_name', 'Name', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {

			$campaign_guid = safe_array_key($this->_data, "campaign_id", "");
			$destination_number = safe_array_key($this->_data, "destination_number", "");
			$number_name = safe_array_key($this->_data, "number_name", "");
			$number_type = safe_array_key($this->_data, "number_type", "");
			$area_code = safe_array_key($this->_data, "area_code", "");

			$campaign = $this->app->get_row('campaigns', 'campaign_id, campaign_name, added_by, tracking_number_id', ['campaign_guid' => $campaign_guid]);
			$campaign_id = safe_array_key($campaign, "campaign_id", "");
			$tracking_number_id = safe_array_key($campaign, "tracking_number_id", NULL);

			if ($number_type === 'TOLLFREE') {
				$toll_free = TRUE;
				$tracking_array = [
					"toll_free" => $toll_free
				];
			} else {
				$tracking_array = [
					"area_code" => $area_code
				];
			}

			$tracking_number_json = $this->campaign_model->update_tracking_number($tracking_number_id, $destination_number, $number_name, $tracking_array);
			
			if ($tracking_number_json['status']) {
				$tracking_number_id = $tracking_number_id;
				$tracking_number_json_encode = json_encode($tracking_number_json);
				$this->campaign_model->update_call_tracking_number($campaign_id, $tracking_number_json_encode, $tracking_number_id, $number_type, $area_code);
				$this->_response['message'] = $tracking_number_json['message'];
				$this->_response['data'] = [];
				$this->set_response($this->_response);
			} else {
				$this->_response["message"] = $tracking_number_json['message'];
				$this->_response["errors"] = $tracking_number_json['result'];
				$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
			}
		}

	}

	public function campaign_active_list_post() {
		$this->_response["service_name"] = "campaign/campaign_active_list";
		$this->form_validation->set_rules('keyword', 'keyword', 'trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$this->load->model("users_model");
			$user_id = $this->rest->user_id;

			$user = $this->app->get_row('users', 'user_type', ['user_id' => $user_id]);
			$user_type = safe_array_key($user, "user_type", "");
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

			$this->_response["data"] = $this->campaign_model->campaign_active_list($user_id, $keyword, $limit, $offset, $column_name, $order_by, $user_type);
			$this->_response["counts"] = $this->campaign_model->campaign_active_list($user_id, $keyword, 0, 0, $column_name, $order_by, $user_type);
			$this->set_response($this->_response);
		}
	}

	public function get_admin_dashboard_get() {
		$data = $this->campaign_model->get_dashboard();
		$this->_response["data"] = $data;
		$this->_response["message"] = "Dashboard details";
		$this->set_response($this->_response);
	}

	public function get_landing_page_ranking_list_post() {
		$this->_response["service_name"] = "campaign/get_landing_page_ranking_list";
		$this->form_validation->set_rules('keyword', 'keyword', 'trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;

			$user = $this->app->get_row('users', 'user_type', ['user_id' => $user_id]);
			$user_type = safe_array_key($user, "user_type", "");
			$keyword = safe_array_key($this->_data, "keyword", "");
			$pagination = safe_array_key($this->_data, "pagination", []);
			$limit = safe_array_key($pagination, "limit", 100);
			$offset = safe_array_key($pagination, "offset", 0);
			$sort_by = safe_array_key($this->_data, "sort_by", []);
			$column_name = safe_array_key($sort_by, "column_name", 'first_name');
			$order_by = safe_array_key($sort_by, "order_by", 'acs');

			$this->_response["data"] = $this->campaign_model->get_landing_page_ranking_list($user_id, $keyword, $limit, $offset, $column_name, $order_by, $user_type);
			// $this->_response["counts"] = $this->campaign_model->get_landing_page_ranking_list($user_id, $keyword, 0, 0, $column_name, $order_by, $user_type);
			$this->set_response($this->_response);
		}
	}

	public function get_user_dashboard_get() {
		$session_key = $this->rest->key;
		$user = $this->app->user_data($session_key);
		$user_id = $this->rest->user_id;
		if ($user['user_role'] == 'USER_INDIVIDUAL_TEAM') {
			$user_id = $user['added_by'];
		}
		$data = $this->campaign_model->get_dashboard($user_id);
		$this->_response["data"] = $data;
		$this->_response["message"] = "Dashboard details";
		$this->set_response($this->_response);
	}

	public function update_campaign_status_post() {
		$this->_response["service_name"] = "campaign/update_campaign_status";
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		$this->form_validation->set_rules('campaign_id', 'Compaign', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$status = safe_array_key($this->_data, "status", "");
			$campaign_guid = safe_array_key($this->_data, "campaign_id", "");
			$campaign = $this->app->get_row('campaigns', 'campaign_id', ['campaign_guid' => $campaign_guid]);
			$campaign_id = safe_array_key($campaign, "campaign_id", '');
			$this->campaign_model->update_campaign_status($campaign_id, $status);
			$this->_response["data"] = ['status' => $status];
			$this->_response["message"] = "Status Updated Successfully";
			$this->set_response($this->_response);
		}
	}
}