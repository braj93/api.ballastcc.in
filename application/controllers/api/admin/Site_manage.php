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
class Site_manage extends REST_Controller {

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
		$this->load->model("admin_model/site_manage_model", "site_manage_model");
		$this->load->library('MY_Form_validation');
	}

	public function plans_get() {
		$this->_response["service_name"] = "admin/Site_manage/plans";
		$this->_response["data"] = $this->app->get_rows_with_order('pricing_plans', 'name, pricing_plan_guid AS id, stripe_pricing_plan_id', [], 'name', 'ACS');
		$this->set_response($this->_response);
	}

	public function get_plans_by_type_post() {
		$this->_response["service_name"] = "admin/Site_manage/get_plans_by_type";
		$this->form_validation->set_rules('type', 'type', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$type = safe_array_key($this->_data, "type", "");
			$this->_response["data"] = $this->app->get_rows_with_order('pricing_plans', 'name, pricing_plan_guid AS id, stripe_pricing_plan_id, base_price, type, status', ['type' => $type], 'base_price', 'ACS');
			$this->set_response($this->_response);
		}
		
	}

	public function get_master_plans_by_type_post() {
		$this->_response["service_name"] = "admin/Site_manage/get_master_plans_by_type";
		$this->form_validation->set_rules('type', 'type', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$type = safe_array_key($this->_data, "type", "");
			$this->_response["data"] = $this->app->get_rows_with_order('pricing_plans', 'name, pricing_plan_guid, pricing_plan_id AS id, stripe_pricing_plan_id, base_price, type, status', ['type' => $type], 'base_price', 'ACS');
			$this->set_response($this->_response);
		}
		
	}

	public function states_get() {
		$this->_response["service_name"] = "admin/Site_manage/states";
		$states = $this->app->get_rows('states', 'state_id AS id, state AS name');
		$this->_response["data"] = $states;
		$this->set_response($this->_response);
	}

	public function get_category_list_post() {
		$this->_response["service_name"] = "admin/Site_manage/get_category_list";
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
			$this->_response["data"] = $this->site_manage_model->get_category_list($keyword, $limit, $offset, $column_name, $order_by);
			$this->_response["counts"] = $this->site_manage_model->get_category_list($keyword, 0, 0, $column_name, $order_by);
			$this->set_response($this->_response);
		}
	}

	/**
	 * CREATE KNOWLEDGEBASE API
	 */
	public function create_category_post() {
		$this->_response["service_name"] = "admin/Site_manage/create_category";

		$this->form_validation->set_rules('name', 'Title', 'trim|required|callback__check_duplicate_category');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$name = safe_array_key($this->_data, "name", "");
			$this->site_manage_model->create_category($name);
			$this->_response["message"] = "Category added successfully";
			$this->set_response($this->_response);
		}
	}

	public function update_category_post() {
		$this->_response["service_name"] = "admin/Site_manage/update_category";

		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('category_id', 'Category ID', 'trim|required|callback__check_category');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$category_guid = safe_array_key($this->_data, "category_id", "");
			$category_data = $this->app->get_row('category_master', 'category_id', ['category_guid' => $category_guid]);
			$category_id = safe_array_key($category_data, 'category_id', "");
			$name = safe_array_key($this->_data, "name", "");
			$affected_rows_count = $this->site_manage_model->update_category($category_id, $name);
			$this->_response["message"] = "category updated successfully";
			$this->set_response($this->_response);
		}
	}

	public function get_category_details_by_id_post() {
		$this->_response["service_name"] = "admin/Site_manage/get_category_details_by_id";
		$this->form_validation->set_rules('category_id', 'category id', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$category_guid = safe_array_key($this->_data, "category_id", "");
			$category_data = $this->app->get_row('category_master', 'category_id', ['category_guid' => $category_guid]);
			$category_id = safe_array_key($category_data, 'category_id', "");
			$data = $this->site_manage_model->get_category_details_by_id($category_id);
			$this->_response["data"] = $data;
			$this->_response["message"] = "category details";
			$this->set_response($this->_response);
		}
	}

	/**
	 * CALLBACKS
	 */
	public function _check_category($str) {
		$where = [
			"category_guid" => $str,
		];
		$rows = $this->app->get_row('category_master', 'category_id', $where);
		if (count($rows) < 1) {
			$msg = $str . 'Category is not valid.';
			$this->form_validation->set_message('_check_category', $msg);
			return false;
		}
		return true;
	}

	public function _check_duplicate_category($str) {
		$where = [
			"name" => $str,
		];
		$rows = $this->app->get_row('category_master', 'category_id', $where);
		// print_r($rows);
		// print_r($where);
		// print_r(count($rows));
		// die();
		if (count($rows) > 0) {
			$msg = 'Category already exist.';
			$this->form_validation->set_message('_check_duplicate_category', $msg);
			return false;
		}
		return true;
	}

	public function get_plans_post() {
		$this->_response["service_name"] = "admin/Site_manage/get_plans";
		$this->form_validation->set_rules('type', 'type', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$type = safe_array_key($this->_data, "type", "");
			$data = $this->site_manage_model->get_plans($type);
			$this->_response["data"] = $data;
			$this->_response["message"] = "Success.";
			$this->set_response($this->_response);
		}
	}

	public function get_pricing_plans_post() {
		$this->_response["service_name"] = "admin/site_manage/get_pricing_plans";
		$this->form_validation->set_rules('keyword', 'keyword', 'trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$keyword = safe_array_key($this->_data, "keyword", "");
			$pagination = safe_array_key($this->_data, "pagination", []);
			$limit = safe_array_key($pagination, "limit", 10);
			$offset = safe_array_key($pagination, "offset", 0);
			$sort_by = safe_array_key($this->_data, "sort_by", []);
			$column_name = safe_array_key($sort_by, "column_name", 'first_name');
			$order_by = safe_array_key($sort_by, "order_by", 'acs');
			$this->_response["data"] = $this->site_manage_model->get_pricing_plans($keyword, $limit, $offset, $column_name, $order_by);
			$this->_response["counts"] = $this->site_manage_model->get_pricing_plans($keyword, 0, 0, $column_name, $order_by);
			$this->set_response($this->_response);
		}
	}

	public function get_pricing_plans_by_type_post() {
		$this->_response["service_name"] = "admin/site_manage/get_pricing_plans_by_type";
		$this->form_validation->set_rules('type', 'Plan Type', 'trim|required|in_list[AGENCY,NON_AGENCY]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$type = safe_array_key($this->_data, "type", "");
			$this->_response["data"] = $this->site_manage_model->get_pricing_plans_by_type($type);
			$this->set_response($this->_response);
		}
	}

	public function get_pricing_plan_details_post() {
		$this->_response["service_name"] = "admin/Site_manage/get_pricing_plan_details";
		$this->form_validation->set_rules('pricing_plan_guid', 'Pricing Plan Id', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$pricing_plan_guid = safe_array_key($this->_data, "pricing_plan_guid", "");
			$pricing_plan = $this->app->get_row('pricing_plans', 'pricing_plan_id', ['pricing_plan_guid' => $pricing_plan_guid]);
			$pricing_plan_id = safe_array_key($pricing_plan, 'pricing_plan_id', "");
			$data = $this->site_manage_model->get_pricing_plan_details($pricing_plan_id);
			$this->_response["data"] = $data;
			$this->_response["message"] = "category details";
			$this->set_response($this->_response);
		}
	}
	
	/**
	 * CALLBACKS
	 */
	public function _check_pricing_plan($str) {
		$where = [
			"pricing_plan_guid" => $str,
		];
		$rows = $this->app->get_row('pricing_plans', 'pricing_plan_id', $where);
		if (count($rows) < 1) {
			$msg = $str . 'Pricing Plan is not valid.';
			$this->form_validation->set_message('_check_pricing_plan', $msg);
			return false;
		}
		return true;
	}

	/**
	 * CREATE KNOWLEDGEBASE API
	 */
	public function create_pricing_plan_post() {
		$this->_response["service_name"] = "admin/Site_manage/create_pricing_plan";

		$this->form_validation->set_rules('name', 'Title', 'trim|required');
		$this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|greater_than[0]');
		$this->form_validation->set_rules('type', 'Plan Type', 'trim|required|in_list[AGENCY,NON_AGENCY]');
		$this->form_validation->set_rules('interval', 'Billing period', 'trim|required|in_list[day,week,month,year]');
		$this->form_validation->set_rules('note', 'Note', 'trim|required');
		$this->form_validation->set_rules('discount', 'Discount', 'trim|numeric|greater_than[0]');
		$this->form_validation->set_rules('target_plan_action_type', 'Plan Action Type', 'trim|in_list[RETIRED,MIGRATE]');
		$target_plan_action_type = safe_array_key($this->_data, "target_plan_action_type", "");
		if (!empty($target_plan_action_type)) {
			$this->form_validation->set_rules('target_plan_id', 'Plan Target Pricng Plan ID Required', 'trim|required');
		}

		$type = safe_array_key($this->_data, "type", "");
		if ($type == 'NON_AGENCY') {
			$this->form_validation->set_rules('campaign_limit', 'Campaign Limit', 'trim|required');
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$name = safe_array_key($this->_data, "name", "");
			$amount = safe_array_key($this->_data, "amount", 0);
			$type = safe_array_key($this->_data, "type", 'NON_AGENCY');
			$interval = safe_array_key($this->_data, "interval", '');
			$note = safe_array_key($this->_data, "note", '');
			$target_plan_action_type = safe_array_key($this->_data, "target_plan_action_type", '');
			$target_plan_guid = safe_array_key($this->_data, "target_plan_id", '');
			$old_pricing_plan = $this->app->get_row('pricing_plans', 'pricing_plan_id, stripe_pricing_plan_id, subscription_count', ['pricing_plan_guid' => $target_plan_guid]);
			$old_pricing_plan_id = safe_array_key($old_pricing_plan, 'pricing_plan_id', "");
			$old_stripe_pricing_plan_id = safe_array_key($old_pricing_plan, 'stripe_pricing_plan_id', "");
			$subscription_count = safe_array_key($old_pricing_plan, 'subscription_count', 0);
			$note = safe_array_key($this->_data, "note", '');
			$discount = safe_array_key($this->_data, "discount", "");
			$campaign_limit = safe_array_key($this->_data, "campaign_limit", NULL);
			$pricing_plan_amount = bcmul($amount, 100);
			$metadata = [
				'name' => $name,
				'note' => $note,
				'discount' => $discount,
			];
			if ($target_plan_action_type && $target_plan_action_type === 'RETIRED') {
				$create_stripe_pricing_plan_response = $this->app->create_stripe_pricing_plan($pricing_plan_amount, $interval, $metadata);
				$stripe_response_status = $create_stripe_pricing_plan_response["status"];
				if($stripe_response_status == "success"){
					$stripe_pricing_plan_id = $create_stripe_pricing_plan_response['subscription']['id'];
					$this->site_manage_model->create_pricing_plan($stripe_pricing_plan_id ,$amount, $name, $type, $interval, $note, $discount, $campaign_limit);
					$stripe_delete_response = $this->app->delete_stripe_pricing_plan($old_stripe_pricing_plan_id);
					$stripe_delete_response_status = $stripe_delete_response["status"];
					if($stripe_delete_response_status == "success"){
						$this->site_manage_model->retired_pricing_plan($old_pricing_plan_id, $subscription_count);
						$this->_response["message"] = 'New Pricing Plan added and selected Pricing Plan retired successfully.';
						$this->set_response($this->_response);
					} else {
						$this->_response["message"] = 'Something Went Wrong in Pricing plan retired.';
						$this->set_response($this->_response);
					}
				}else{
					$this->_response["message"] = "Stripe subscription create Error";
					$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
				}
			} else if ($target_plan_action_type && $target_plan_action_type === 'MIGRATE') {
				$stripe_response = $this->app->create_stripe_pricing_plan($pricing_plan_amount, $interval, $metadata);
				$stripe_response_status = $stripe_response["status"];

				if($stripe_response_status == "success"){
					$stripe_pricing_plan_id = $stripe_response['subscription']['id'];

					$new_pricing_plan_id = $this->site_manage_model->create_pricing_plan($stripe_pricing_plan_id ,$amount, $name, $type, $interval, $note, $discount, $campaign_limit);

					$stripe_delete_response = $this->site_manage_model->migrate_all_subscribers($old_pricing_plan_id, $stripe_pricing_plan_id, $new_pricing_plan_id);
					$stripe_delete_response = $this->app->delete_stripe_pricing_plan($old_stripe_pricing_plan_id);
					$stripe_delete_response_status = $stripe_delete_response["status"];

					if($stripe_delete_response_status == "success"){
						$this->site_manage_model->retired_pricing_plan($old_pricing_plan_id, 0);
						$this->_response["message"] = 'New Pricing Plan added and selected Pricing Plan retired successfully.';
						$this->set_response($this->_response);
						
					} else {
						$this->_response["message"] = 'Something Went Wrong in Pricing plan retired.';
						$this->set_response($this->_response);
					}

				}else{
					$this->_response["message"] = "Stripe subscription create Error";
					$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
				}
			} else {
				$stripe_response = $this->app->create_stripe_pricing_plan($pricing_plan_amount, $interval, $metadata);
				$stripe_response_status = $stripe_response["status"];
				if($stripe_response_status == "success"){
					$stripe_pricing_plan_id = $stripe_response['subscription']['id'];
					$this->site_manage_model->create_pricing_plan($stripe_pricing_plan_id ,$amount, $name, $type, $interval, $note, $discount , $campaign_limit);
					$this->_response["message"] = 'Pricing Plan Added Successfully.';
					$this->set_response($this->_response);
				}else{
					$this->_response["message"] = "Stripe subscription create Error";
					$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
				}
			}
			
		}
	}

	public function update_pricing_plan_post() {
		$this->_response["service_name"] = "admin/Site_manage/update_pricing_plan";

		$this->form_validation->set_rules('pricing_plan_id', 'Pricing Plan Id', 'trim|required|callback__check_pricing_plan');
		$this->form_validation->set_rules('name', 'Title', 'trim|required');
		$this->form_validation->set_rules('note', 'Note', 'trim|required');
		$this->form_validation->set_rules('discount', 'Discount', 'trim|numeric|greater_than[0]');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$pricing_plan_guid = safe_array_key($this->_data, "pricing_plan_id", "");
			$name = safe_array_key($this->_data, "name", "");
			$note = safe_array_key($this->_data, "note", "");
			$discount = safe_array_key($this->_data, "discount", "");
			$pricing_plan = $this->app->get_row('pricing_plans', '*', ['pricing_plan_guid' => $pricing_plan_guid]);
			$pricing_plan_id = safe_array_key($pricing_plan, 'pricing_plan_id', "");
			$stripe_pricing_plan_id = safe_array_key($pricing_plan, 'stripe_pricing_plan_id', "");
			$metadata = [
				'name' => $name,
				'note' => $note,
				'discount' => $discount
			];

			$stripe_response = $this->app->update_stripe_pricing_plan_metadata($stripe_pricing_plan_id, $metadata);
			$stripe_response_status = $stripe_response["status"];
			if($stripe_response_status == "success"){
				$this->site_manage_model->update_pricing_plan($pricing_plan_id, $name, $note, $discount);
				$this->_response["message"] = 'Pricing Plan Added Successfully.';
				$this->set_response($this->_response);
			}else{
				$this->_response["message"] = "Stripe subscription update Error";
				$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
			}
		}
	}

	public function _check_pricing_plan_and_subscriptions($str) {
		$where = [
			"pricing_plan_guid" => $str,
		];
		$rows = $this->app->get_row('pricing_plans', 'pricing_plan_id, subscription_count', $where);
		$pricing_plan_id = safe_array_key($this->_data, "pricing_plan_id", "");
		$subscription_count = (int)safe_array_key($this->_data, "subscription_count", 0);
		if (count($rows) < 1) {
			$msg = $str . 'Pricing Plan is not valid.';
			$this->form_validation->set_message('_check_pricing_plan_and_subscriptions', $msg);
			return false;
		} else {
			if ($subscription_count > 0) {
				$this->form_validation->set_message('_check_pricing_plan_and_subscriptions', 'Pricing plan has subscription(s)');
				return false;
			}
			
		}
		return true;
	}

	public function delete_pricing_plan_post() {
		$this->_response["service_name"] = "admin/Site_manage/delete_pricing_plan";

		$this->form_validation->set_rules('pricing_plan_id', 'Pricing Plan Id', 'trim|required|callback__check_pricing_plan_and_subscriptions');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$pricing_plan_guid = safe_array_key($this->_data, "pricing_plan_id", "");
			$name = safe_array_key($this->_data, "name", "");
			$note = safe_array_key($this->_data, "note", "");
			$discount = safe_array_key($this->_data, "discount", "");
			$pricing_plan = $this->app->get_row('pricing_plans', '*', ['pricing_plan_guid' => $pricing_plan_guid]);
			$pricing_plan_id = safe_array_key($pricing_plan, 'pricing_plan_id', "");
			$stripe_pricing_plan_id = safe_array_key($pricing_plan, 'stripe_pricing_plan_id', "");
			$stripe_delete_response = $this->app->delete_stripe_pricing_plan($stripe_pricing_plan_id);
			$stripe_delete_response_status = $stripe_delete_response["status"];
			if($stripe_delete_response_status == "success"){
				$this->site_manage_model->delete_pricing_plan($pricing_plan_id, $name, $note, $discount);
				$this->_response["message"] = 'Pricing Plan Deleted Successfully.';
				$this->set_response($this->_response);
			}else{
				$this->_response["message"] = "Stripe subscription delete Error";
				$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
			}
		}
	}

	public function change_pricing_plan_status_post() {
		$this->_response["service_name"] = "admin/site_manage/change_pricing_plan_status";
		$this->form_validation->set_rules('pricing_plan_id', 'Pricing Plan Id', 'trim|required');
		$this->form_validation->set_rules("status", "Status", "trim|required|in_list[ACTIVE,INACTIVE]");
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$pricing_plan_guid = safe_array_key($this->_data, "pricing_plan_id", "");
			$pricing_plan = $this->app->get_row('pricing_plans', 'pricing_plan_id', ['pricing_plan_guid' => $pricing_plan_guid]);
			$pricing_plan_id = safe_array_key($pricing_plan, 'pricing_plan_id', "");
			$status = safe_array_key($this->_data, "status", "");
			$this->site_manage_model->change_pricing_plan_status($pricing_plan_id, $status);
			$this->_response['message'] = "Status Updated successfully";
			$this->set_response($this->_response);
		}
	}
}