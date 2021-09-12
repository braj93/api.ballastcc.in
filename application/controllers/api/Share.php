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
class Share extends REST_Controller {

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
		$this->load->model("share/share_model");
		$this->load->library('MY_Form_validation');
	}

	public function index_get() {
		$this->set_response($this->_response);

	}

	public function get_campaign_list_for_reporting_tikisites_get() {
		$this->_response["data"] = $this->share_model->get_campaign_list_for_reporting_tikisites();		
		$this->set_response($this->_response);	
	}

	public function get_users_list_for_reporting_tikisites_get() {
		$this->_response["data"] = $this->share_model->get_users_list_for_reporting_tikisites();		
		$this->set_response($this->_response);	
	}

	public function get_organization_list_for_reporting_tikisites_get() {
		$this->_response["data"] = $this->share_model->get_organization_list_for_reporting_tikisites();		
		$this->set_response($this->_response);	
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


	public function update_campaign_script_post() {
		$this->_response["service_name"] = "share/update_campaign_script";
		$this->form_validation->set_rules('campaign_guid', 'Campaign Id', 'trim|required|callback__check_campaign_exist');
		$this->form_validation->set_rules('trackingcode', 'Tracking Code', 'trim|required');
		$this->form_validation->set_rules('tracking', 'Tracking', 'trim|required|in_list[YES,NO]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$trackingcode = safe_array_key($this->_data, "trackingcode", NULL);
			$tracking = safe_array_key($this->_data, "tracking", "NO");
			$campaign_guid = safe_array_key($this->_data, "campaign_guid", "");
			$campaign = $this->app->get_row('campaigns', 'campaign_id, campaign_template_id', ['campaign_guid' => $campaign_guid]);
			$campaign_id = safe_array_key($campaign, "campaign_id", "");
			$campaign_template_id = safe_array_key($campaign, "campaign_template_id", "");
			if ($tracking == "NO") {
				$trackingcode = NULL;
			}
			$this->share_model->update_campaign_script($campaign_template_id, $trackingcode);
			$this->_response['message'] = "Tracking Code updated successfully.";
			$this->set_response($this->_response);
		}
	}
	
	public function get_users_list_post() {
		$this->_response["service_name"] = "share/get_users_list";
		$keyword = safe_array_key($this->_data, "keyword", "");
		$pagination = safe_array_key($this->_data, "pagination", []);
		$limit = safe_array_key($pagination, "limit", 10);
		$offset = safe_array_key($pagination, "offset", 0);
		$sort_by = safe_array_key($this->_data, "sort_by", []);
		$column_name = safe_array_key($sort_by, "column_name", 'first_name');
		$order_by = safe_array_key($sort_by, "order_by", 'acs');
		$filters = safe_array_key($this->_data, "filters", []);
		$status = safe_array_key($filters, "status", '');
		$this->load->model("users_model");
		$this->_response["data"] = $this->users_model->get_users_list( $limit, $offset, $column_name, $order_by, $status);
		$this->_response["counts"] = $this->users_model->get_users_list( 0, 0, $column_name, $order_by, $status);
		// $this->_response["data"] = $this->users_model->get_users_list($keyword, $limit, $offset, $column_name, $order_by, $status);
		// $this->_response["counts"] = $this->users_model->get_users_list($keyword, 0, 0, $column_name, $order_by, $status);
		$this->_response["message"] = "User List";
		$this->set_response($this->_response);
	}


	public function get_recent_users_list_get() {
		$this->_response["service_name"] = "share/get_recent_users_list";
		$this->load->model("users_model");
		$this->_response["data"] = $this->users_model->get_recent_users_list();
		$this->_response["message"] = "User List";
		$this->set_response($this->_response);
	}

	public function get_individual_pricing_plan_list_for_marketingtiki_get() {
		$type = 'NON_AGENCY';
		$this->_response["data"] = $this->share_model->get_pricing_plan_list_for_marketingtiki($type);		
		$this->set_response($this->_response);	
	}
	
	public function get_agency_pricing_plan_list_for_marketingtiki_get() {
		$type = 'AGENCY';
		$this->_response["data"] = $this->share_model->get_pricing_plan_list_for_marketingtiki($type);		
		$this->set_response($this->_response);	
	}
}