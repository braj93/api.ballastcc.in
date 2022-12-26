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
class Users extends REST_Controller {

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
		$this->load->model("users_model");
		$this->load->library('MY_Form_validation');
	}

	public function index_get() {
		$this->set_response($this->_response);
	}

	public function test_get() {
		echo 'Get Rest Controller';
	}
/**
 * GET PROFILE DETAILS
 * 
 */

	public function profile_get() {
		$this->_response["service_name"] = "users/profile";
		$session_key = $this->rest->key;
		$this->_response["data"] = $this->app->user_data($session_key);
		$this->set_response($this->_response);

	}
	
	public function get_countries_get() {
		$this->_response["service_name"] = "users/get_countries";
		$countries_data = $this->app->get_rows('countries', 'id, sortname, name', []);
		if (empty($countries_data)) {
			$countries_data = [];
		}
		$this->_response["data"] = $countries_data;
		$this->set_response($this->_response);
	}

	public function profile_post() {
//S3_SETTING
		$this->_response["service_name"] = "users/profile";
		$this->form_validation->set_rules('user_id', 'user id', 'trim|callback__check_user_id_exist');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_guid = safe_array_key($this->_data, "user_id", "");
			$user_data = $this->app->get_row('users', 'user_id', ['user_guid' => $user_guid]);
			$user_id = safe_array_key($user_data, 'user_id', $this->rest->user_id);

			$user = $this->users_model->profile($user_id);
			if ($user_id == $this->rest->user_id) {
				$user['is_self'] = "YES";
			} else {
				$user['is_self'] = "NO";
			}
			$this->_response["data"] = $user;
			$this->set_response($this->_response);
		}
	}

	public function _check_user_id_exist($str) {
		if ($str != "") {
			$users_data = $this->app->get_rows('users', 'user_id', ['user_guid' => $str]);
			if (empty($users_data)) {
				$this->form_validation->set_message('_check_user_id_exist', 'Please provide correct user id');
				return FALSE;
			}
		}
		return TRUE;
	}
/**
 * UPDATE USER BASIC INFO
 */
	public function update_basic_info_post() {
		$this->_response["service_name"] = "users/update_basic_info";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('ethnicity', 'ethnicity', 'trim|required');
		$this->form_validation->set_rules('mobile', 'mobile', 'trim|required');
		$this->form_validation->set_rules('gender', 'gender', 'trim|required|in_list[MALE,FEMALE,OTHERS]');
		$this->form_validation->set_rules('present_address', 'present address', 'trim|callback__check_present_address');
		$this->form_validation->set_rules('permanent_address', 'permanent address', 'trim|callback__check_permanent_address');
		$this->form_validation->set_rules('is_same_as_present_address', 'is same as present address', 'trim|in_list[YES,NO]');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$master_guid = safe_array_key($this->_data, "ethnicity", "");
			$row = $this->app->get_row('masters', 'master_id', [
				'master_guid' => $master_guid,
			]);
			$ethnicity = safe_array_key($row, "master_id", "");
			$mobile = safe_array_key($this->_data, "mobile", "");
			$gender = safe_array_key($this->_data, "gender", "");
			$present_address_j = safe_array_key($this->_data, "present_address", "");
			$permanent_address_j = safe_array_key($this->_data, "permanent_address", "");
			$is_same_as_present_address = safe_array_key($this->_data, "is_same_as_present_address", "NO");
			$country = strtolower(safe_array_key($present_address_j, "country", ""));
			$state = strtolower(safe_array_key($present_address_j, "state", ""));
			$city = strtolower(safe_array_key($present_address_j, "city", ""));
			$present_address = json_encode($present_address_j);
			$permanent_address = json_encode($permanent_address_j);
			$this->users_model->update_basic_info($user_id, $ethnicity, $mobile, $gender, $present_address, $permanent_address, $is_same_as_present_address, $country, $state, $city);
			$this->set_response($this->_response);
		}
	}
/**
 * PRESENT ADDRESS VALIDATION
 */
	public function _check_present_address($str) {
		$present_address = safe_array_key($this->_data, "present_address", []);
		$country = safe_array_key($present_address, "country", "");
		$state = safe_array_key($present_address, "state", "");
		$city = safe_array_key($present_address, "city", "");
		if ($country == "") {
			$this->form_validation->set_message('_check_present_address', 'Country is required.');
			return FALSE;
		}
		if ($state == "") {
			$this->form_validation->set_message('_check_present_address', 'State is required.');
			return FALSE;
		}
		if ($city == "") {
			$this->form_validation->set_message('_check_present_address', 'City is required.');
			return FALSE;
		}
	}
/**
 * PERMANENT ADDRESS VALIDATION
 */
	public function _check_permanent_address($str) {
		$permanent_address = safe_array_key($this->_data, "permanent_address", []);
		$country = safe_array_key($permanent_address, "country", "");
		$state = safe_array_key($permanent_address, "state", "");
		$city = safe_array_key($permanent_address, "city", "");
		if ($country == "") {
			$this->form_validation->set_message('_check_permanent_address', 'Country is required.');
			return FALSE;
		}
		if ($state == "") {
			$this->form_validation->set_message('_check_permanent_address', 'State is required.');
			return FALSE;
		}
		if ($city == "") {
			$this->form_validation->set_message('_check_permanent_address', 'City is required.');
			return FALSE;
		}
	}

	public function resend_account_verification_post() {
		$this->_response["service_name"] = "users/resend_account_verification";
		$this->form_validation->set_rules('device_type', 'device type', 'trim|required|in_list[' . implode($this->app->device_types, ",") . ']');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback__should_exist_email_with_pending');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {			
			
			$this->load->model("users_model");

			$device_type = safe_array_key($this->_data, "device_type", "web_browser");
			$email = safe_array_key($this->_data, "email", "");
			$user = $this->app->get_row('users', 'user_id', ['email' => $email]);
			$user_id = $user['user_id'];

			// SEND PUSH NOTIFICATION
			$title = 'Tikisites';
			$body = 'Hi Teammate! We sent you a new email confirmation.';
			push_notification($user_id, $title, $body);

			$this->users_model->send_user_verification($user_id, $device_type);
			$this->_response["message"] = "We have sent you a email with link to activate account.";
			$this->_response["success"] = true;

			$this->set_response($this->_response);
		}
	}

	public function check_pricing_plan_status_post() {
		$this->_response["service_name"] = "users/check_pricing_plan_status";
		$this->form_validation->set_rules('pricing_plan_id', 'Pricing Plan Id', 'trim|required|callback__check_pricing_plan');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$pricing_plan_id = safe_array_key($this->_data, "pricing_plan_id", "");
			$pricing_plan = $this->app->get_row('pricing_plans', 'pricing_plan_id, stripe_pricing_plan_id, name, base_price', ['pricing_plan_guid' => $pricing_plan_id]);
			$stripe_pricing_plan_id = safe_array_key($pricing_plan, "stripe_pricing_plan_id", "");
			$plan_name = safe_array_key($pricing_plan, "name", "");
			$amount = safe_array_key($pricing_plan, "base_price", "");
			$stripe_pricing_plan_id = safe_array_key($pricing_plan, "stripe_pricing_plan_id", "");
			$pricing_plan_response = $this->app->check_pricing_plan_status(STRIPE_SKEY, $stripe_pricing_plan_id);
			if ($pricing_plan_response['status'] == "success") {
				$status = $pricing_plan_response['pricing_plan_response']['active'];
				if($status){
					$data = [
						"status" => $pricing_plan_response['pricing_plan_response']['active'],
						"amount" => $amount,
						"plan_name" => $plan_name,
					];
					$this->_response["data"] = $data;
					$this->_response["message"] = 'Pricing plan is active';
					$this->_response["success"] = true;
					$this->set_response($this->_response);
				}else{
					$this->_response["message"] = "Pricing plan is not active";
					$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
				}
			}else{
				$this->_response["message"] = "Plan Id invalid Error";
			   $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		   }
		}
	}

	public function update_device_id_post() {
		$this->_response["service_name"] = "users/update_device_id";
		$this->form_validation->set_rules('unique_device_id', 'Unique Device Id', 'trim|required');
		$this->form_validation->set_rules('new_device_id', 'New Device Id', 'trim|required');
	
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$unique_device_id = safe_array_key($this->_data, "unique_device_id", "");
			$new_device_id = safe_array_key($this->_data, "new_device_id", "");
			$session_key = $this->rest->key;
			$this->users_model->update_device_id($session_key, $new_device_id, $unique_device_id);
			$this->_response["message"] = 'Token Updated';
			$this->set_response($this->_response);
		}
	}

/**
 * USER REGISTERATION
 */

public function register_members_post() {
	$this->_response["service_name"] = "users/register";
	$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[2]|max_length[50]|callback__check_alpha_space');
	$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[2]|max_length[50]|callback__check_alpha_space');
	$this->form_validation->set_rules('mobile', 'mobile', 'trim');
	$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback__unique_email');
	$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[8]|max_length[12]');
	if ($this->form_validation->run() == FALSE) {
		$errors = $this->form_validation->error_array();
		$this->_response["message"] = current($errors);
		$this->_response["errors"] = $errors;
		$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
	} else {
		$first_name = safe_array_key($this->_data, "first_name", "");
		$last_name = safe_array_key($this->_data, "last_name", "");
		$mobile = safe_array_key($this->_data, "mobile", "");
		$email = safe_array_key($this->_data, "email", "");
		$password = safe_array_key($this->_data, "password", "");
		$user_id = $this->users_model->create_user($first_name, $last_name, $mobile,  $email, $password);
		$this->_response["message"] = 'You have registered successfully';
		$this->set_response($this->_response);

	}
}

	public function calculate_ammount($price){	
		return (1*(float)$price);
	}

	public function _check_pricing_plan($pricing_plan_guid) {
		if (!empty($pricing_plan_guid)) {

			$pricing_plan = $this->app->get_row('pricing_plans', 'status, pricing_plan_id', ['pricing_plan_guid' => $pricing_plan_guid]);

			if (!empty($pricing_plan)) {
				if ($pricing_plan['status'] != 'ACTIVE') {
					$this->form_validation->set_message('_check_pricing_plan', 'Plan is not active.');
					$this->users_model->add_logs($this->_data);
					return FALSE;
				}
			} else {
				$this->form_validation->set_message('_check_pricing_plan', 'Invalid plan id or not found.');
				return FALSE;
			}
		}
		return TRUE;
	}


	public function email_and_phone_validate_post(){
		$this->form_validation->set_rules('email', "Email", "trim|required|callback__unique_email");
		// $this->form_validation->set_rules('phone', "Phone", "trim|required|callback__unique_phone");

		if($this->form_validation->run() == FALSE){
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		}
		else{	
			$this->set_response($this->_response);	
		}	
	}

	public function get_email_by_id_post(){
		$this->_response["service_name"] = "users/get_email_by_id";
		$this->form_validation->set_rules('organization_member_id', 'Id', 'trim|required|callback__check_organization_member_exist');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$organization_member_guid = safe_array_key($this->_data, "organization_member_id", "");
			$organization_member = $this->app->get_row('organization_members', 'email, organization_member_id', ['organization_member_guid' => $organization_member_guid]);
			$email = safe_array_key($organization_member, "email", "");
			// $email = "";
			if ($email) {
				$this->_response["success"] = true;
				$this->_response["data"] = ["email" => $email];
				$this->_response["message"] = "Email Id";
				$this->set_response($this->_response);
			} else {
				$this->_response["message"] = 'Email not found';
				$this->_response["errors"] = [];
				$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
			}
		}
	}

	public function register_team_member_post() {
		$this->_response["service_name"] = "users/register_team_member";
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|max_length[50]|callback__check_alpha_space');
		$this->form_validation->set_rules('organization_member_id', 'Agency Id', 'trim|required|callback__check_organization_member_exist');
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback__unique_check_email|callback__unique_email');
		$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[8]|max_length[12]');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$first_name = safe_array_key($this->_data, "name", "");
			$last_name = safe_array_key($this->_data, "last_name", "");
			$business_name = safe_array_key($this->_data, "business_name", "");
			$user_sub_type = safe_array_key($this->_data, "user_sub_type", 'USER');
			$email = safe_array_key($this->_data, "email", "");
			$password = safe_array_key($this->_data, "password", "");
			$device_type = safe_array_key($this->_data, "device_type", "web_browser");
			$device_type_id = array_search($device_type, $this->app->device_types);
			$organization_member_guid = safe_array_key($this->_data, "organization_member_id", "");
			
			$organization_member = $this->app->get_row('organization_members', 'organization_id, organization_member_id, added_by', ['organization_member_guid' => $organization_member_guid]);

			$organization_id= safe_array_key($organization_member, "organization_id", "");
			$organization_member_id= safe_array_key($organization_member, "organization_member_id", "");
			
			$added_by= safe_array_key($organization_member, "added_by", "");
			$user = $this->app->get_row('users', 'business_name', ['user_id' => $added_by]);
			$business_name= safe_array_key($user, "business_name", "");

			$user_id = $this->users_model->create_user($first_name, $last_name, $user_sub_type, $business_name, $email, $password, $device_type_id);
				
			$this->users_model->update_organization_member($user_id, $organization_member_id, $email, $organization_id, NULL);

			$row = $this->app->get_row('users', 'user_guid', ['user_id' => $user_id]);
			$this->users_model->send_team_member_signup_success_email($user_id, $device_type);
			
			$this->_response["success"] = true;
			$this->_response["data"] = $row;
			$this->_response["message"] = "Your subscription has been confirmed.Check your email for further details.";
			
			$this->set_response($this->_response);
		}
	}
	
	public function register_post() {
		// print_r('EXPORT');
		// die();
		$this->_response["service_name"] = "users/register";
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|max_length[50]|callback__check_alpha_space');
		$this->form_validation->set_rules('organization_member_id', 'Agency Id', 'trim|required|callback__check_organization_member_exist');
		// $this->form_validation->set_rules('organization_id', 'Agency Id', 'trim|required|callback__check_organization_exist');
		$this->form_validation->set_rules('pricing_plan_id', 'Plan Id', 'trim|required|callback__check_plan_exist');
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback__unique_check_email|callback__unique_email');
		$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[8]|max_length[12]');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$first_name = safe_array_key($this->_data, "name", "");
			$last_name = safe_array_key($this->_data, "last_name", "");
			$business_name = safe_array_key($this->_data, "business_name", "");
			$user_sub_type = safe_array_key($this->_data, "user_sub_type", 'USER');
			$email = safe_array_key($this->_data, "email", "");
			$password = safe_array_key($this->_data, "password", "");
			$device_type = safe_array_key($this->_data, "device_type", "web_browser");
			$device_type_id = array_search($device_type, $this->app->device_types);
			$pricing_plan_guid = safe_array_key($this->_data, "pricing_plan_id", "");
			$organization_member_guid = safe_array_key($this->_data, "organization_member_id", "");
			
			$organization_member = $this->app->get_row('organization_members', 'organization_id, organization_member_id', ['organization_member_guid' => $organization_member_guid]);

			$organization_id= safe_array_key($organization_member, "organization_id", "");
			$organization_member_id= safe_array_key($organization_member, "organization_member_id", "");

			$pricing_plan = $this->app->get_row('pricing_plans', 'pricing_plan_id, base_price', ['pricing_plan_guid' => $pricing_plan_guid]);
			$pricing_plan_id = safe_array_key($pricing_plan, "pricing_plan_id", "");
			$base_price = safe_array_key($pricing_plan, "base_price", "");
			$organization = $this->app->get_row('organizations', 'user_id', ['organization_id' => $organization_id]);
			$organization_user_id = safe_array_key($organization, "user_id", "");
			$plan_amount = $this->users_model->get_plan_amount($organization_id, $organization_user_id, $base_price);
			$user_id = $this->users_model->create_user($first_name, $last_name, $user_sub_type, $business_name, $email, $password, $device_type_id);
			$user_plan_id = $this->users_model->create_user_plan($user_id ,NULL ,NULL ,NULL ,NULL ,NULL, $plan_amount);
			$this->users_model->update_organization_member($user_id, $organization_member_id, $email, $organization_id, $pricing_plan_id);

			$row = $this->app->get_row('users', 'user_guid', ['user_id' => $user_id]);
			$this->users_model->send_user_signup_success_email($user_id, $device_type);
			
			$this->_response["success"] = true;
			$this->_response["data"] = $row;
			$this->_response["message"] = "Your subscription has been confirmed.Check your email for further details.";
			
			$this->set_response($this->_response);
		}
	}

	public function save_contact_form_post() {
		$this->_response["service_name"] = "users/save_contact_form";
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[2]|max_length[50]|callback__check_alpha_space');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[2]|max_length[50]|callback__check_alpha_space');		
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$first_name = safe_array_key($this->_data, "first_name", "");
			$last_name = safe_array_key($this->_data, "last_name", "");
			$email = safe_array_key($this->_data, "email", "");
			$subject = safe_array_key($this->_data, "subject", "");
			$message = safe_array_key($this->_data, "message", "");
			$user_id = $this->users_model->submit_contact_form($first_name, $last_name, $email,$subject,  $message);
			$this->_response["message"] = 'Your response recorded successfully';
			$this->set_response($this->_response);
	
		}
	}

	// public function register_post() {
	// 	$this->_response["service_name"] = "users/register";
	// 	//$this->form_validation->set_rules('device_type', 'device type', 'trim|required|in_list[' . implode($this->app->device_types, ",") . ']');
	// 	$this->form_validation->set_rules('name', 'First Name', 'trim|required|min_length[2]|max_length[50]|callback__check_alpha_space');
	// 	$this->form_validation->set_rules('agency_id', 'Agency Id', 'trim|required|callback__check_agency_exist');
	// 	// $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|max_length[50]|callback__check_alpha_space');
	// 	// $this->form_validation->set_rules('type', 'Signup type', 'trim|required|max_length[50]|callback__check_alpha_space');
	// 	$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback__unique_email');
	// 	$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[8]|max_length[12]');

	// 	if ($this->form_validation->run() == FALSE) {
	// 		$errors = $this->form_validation->error_array();
	// 		$this->_response["message"] = current($errors);
	// 		$this->_response["errors"] = $errors;
	// 		$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
	// 	} else {
	// 		$first_name = safe_array_key($this->_data, "name", "");
	// 		$last_name = safe_array_key($this->_data, "last_name", "");
	// 		$business_name = safe_array_key($this->_data, "business_name", "");
	// 		$user_sub_type = safe_array_key($this->_data, "user_sub_type", 'USER');
	// 		$email = safe_array_key($this->_data, "email", "");
	// 		$password = safe_array_key($this->_data, "password", "");
	// 		$device_type = safe_array_key($this->_data, "device_type", "web_browser");
	// 		$device_type_id = array_search($device_type, $this->app->device_types);
	// 		$agency_id = safe_array_key($this->_data, "agency_id", "");

	// 		$agency_user = $this->app->get_row('users', 'user_id', ['user_guid' => $agency_id]);
	// 		$agency_user_id = safe_array_key($agency_user, "user_id", "");

	// 		$pricing_plan = $this->app->get_row('user_plans', 'pricing_plan_id', ['user_id' => $agency_user_id]);
	// 		$pricing_plan_id = safe_array_key($pricing_plan, "pricing_plan_id", "");

	// 		$user_id = $this->users_model->create_user($first_name, $last_name, $user_sub_type, $business_name, $email, $password, $device_type_id);

	// 		$user_plan_id = $this->users_model->create_user_plan($user_id, $pricing_plan_id ,NULL ,NULL ,NULL ,NULL ,NULL ,NULL ,NULL);

	// 		$agency_invited_user_id = $this->users_model->create_agency_invite_user($agency_user_id, $user_id);

	// 		$row = $this->app->get_row('users', 'user_guid', ['user_id' => $user_id]);
	// 		$this->users_model->send_user_signup_success_email($user_id, $device_type);
	// 		// if (!empty($user_id)) {
	// 		// 	$this->load->model("notifications_model");
	// 		// 	$parameters = array();
	// 		// 	$this->notifications_model->save(1, 0, array($user_id), $user_id, $parameters);
	// 		// }
			
	// 		$this->_response["success"] = true;
	// 		$this->_response["data"] = $row;
	// 		$this->_response["message"] = "Registration successful.";
			
	// 		$this->set_response($this->_response);
	// 	}
	// }

	// public function _check_agency_exist($user_guid) {
	// 	if (!empty($user_guid)) {

	// 		$user = $this->app->get_row('users', 'status', ['user_guid' => $user_guid]);

	// 		if (!empty($user)) {
	// 			if ($user['status'] != 'ACTIVE') {
	// 				$this->form_validation->set_message('_check_agency_exist', 'Agency is not active.');
	// 				return FALSE;
	// 			}
	// 		} else {
	// 			$this->form_validation->set_message('_check_agency_exist', 'Agency not found.');
	// 			return FALSE;
	// 		}
	// 	}
	// 	return TRUE;
	// }
	public function _check_plan_exist($pricing_plan_id) {
		if (!empty($pricing_plan_id)) {

			$plan = $this->app->get_row('pricing_plans', 'status', ['pricing_plan_guid' => $pricing_plan_id]);

			if (!empty($plan)) {
				if ($plan['status'] != 'ACTIVE') {
					$this->form_validation->set_message('_check_plan_exist', 'Plan is not active.');
					return FALSE;
				}
			} else {
				$this->form_validation->set_message('_check_plan_exist', 'Plan not found.');
				return FALSE;
			}
		}
		return TRUE;
	}

	public function _check_organization_exist($user_guid) {
		if (!empty($user_guid)) {
			$user = $this->app->get_row('users', 'user_id, status', ['user_guid' => $user_guid]);
			if (!empty($user)) {
				if ($user['status'] == 'ACTIVE') {
					$organization = $this->app->get_row('organizations', 'organization_status, status', ['user_id' => $user['user_id']]);

					if (!empty($organization)) {
						if ($organization['organization_status'] != 'AGENCY') {
							$this->form_validation->set_message('_check_organization_exist', 'Ageny can not invite.');
							return FALSE;
						}
					}
				}else{
					$this->form_validation->set_message('_check_organization_exist', 'Agency is not active.');
					return FALSE;
				}
			}else{
				$this->form_validation->set_message('_check_organization_exist', 'Not found.');
				return FALSE;
			}
		}
		return TRUE;
	}

	public function _check_organization_member_exist($organization_member_guid) {
		if (!empty($organization_member_guid)) {
			$organization_member = $this->app->get_row('organization_members', 'organization_member_id', ['organization_member_guid' => $organization_member_guid]);
			
			if (empty($organization_member)) {
				$this->form_validation->set_message('_check_organization_member_exist', 'Not valid ID.');
				return FALSE;
			}
		}
		return TRUE;
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

	public function _unique_check_email($str) {
		$rows1 = [];

		$email_part = explode('@', $str);
		$email_domain = end($email_part);
		$email_domain = strtolower($email_domain);
		if (in_array($email_domain, $this->app->disallowed_email_domains)) {
			$this->form_validation->set_message('_unique_email', 'Only use corporate email address.');
			return FALSE;
		}

		$rows1 = $this->app->get_rows('organization_members', 'organization_member_id', ['email' => strtolower($str)]);
		
		if (count($rows1) === 0) {
			$this->form_validation->set_message('_unique_check_email', 'Please sign up with correct email address.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
/**
 * VERIFY POST
 */
	public function verify_post() {
		$this->_response["service_name"] = "users/verify";

		$this->form_validation->set_rules('device_type', 'device type', 'trim|required|in_list[' . implode($this->app->device_types, ",") . ']');
		$this->form_validation->set_rules('device_token', 'device token', 'trim');
		$this->form_validation->set_rules('verify_code', 'verify code', 'trim|required|callback__check_activation_code');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$this->load->model("users_model");

			$device_type = safe_array_key($this->_data, "device_type", "web_browser");
			$device_type_id = array_search($device_type, $this->app->device_types);
			$verify_code = safe_array_key($this->_data, "verify_code", "");
			//$user_id = $this->users_model->set_user_verified_by_verify_code($device_type_id, $verify_code);

			// $device_token = safe_array_key($this->_data, "device_token", "");
			// $ip_address = $this->input->ip_address();
			// $this->send_email_user_verify($user_id);
			// $session_id = $this->users_model->create_session_key($user_id, $device_type_id, $device_token, $ip_address);
			// $this->_response["data"] = $this->app->user_data($session_id);
			$this->_response["success"] = true;
			$this->set_response($this->_response);
		}
	}

	public function send_email_user_verify($user_id) {

		// SEND NOTIFICATION
		$this->load->model("notifications_model");
		$parameters = [];
		$this->notifications_model->save(14, 0, [$user_id], $user_id, $parameters);

		// SEND PUSH NOTIFICATION
		$title = 'Tikisites';
		$body = 'You just got verified! Start building your profile now.';
		$user_guid = get_detail_by_id($user_id, 'user', 'user_guid');
		$extra_data = ['type' => 'user', 'id' => $user_guid];
		push_notification($user_id, $title, $body, $extra_data);

		// SEND EMAIL
		$user_email = get_detail_by_id($user_id, 'user', 'email');
		$this->load->helper('email');
		$email_template = "emailer/user_confirm";
		$subject = 'Welcome to Marketing Tiki - User Confirmed!';
		$member = 'Teammate!';
		$email_data = array("member" => $member);
		$email = $user_email;

		$message = $this->load->view($email_template, $email_data, TRUE);
		$this->load->library('email');
		$this->email->from(SUPPORT_EMAIL, FROM_NAME);
		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();

	}

/**
 * CHECK ACTIVATION CODE
 */
	public function _check_activation_code($verification_guid) {
		if (!empty($verification_guid)) {
			$device_type = safe_array_key($this->_data, "device_type", "web_browser");
			$user_guid = safe_array_key($this->_data, "user_id", "");
			$row = $this->app->get_row('users', 'user_id', ['user_guid' => $user_guid]);

			$device_type_id = array_search($device_type, $this->app->device_types);
			if (in_array($device_type_id, array("1"))) {
				$verification = $this->app->get_row('verifications', 'status, user_id', ['verification_guid' => $verification_guid]);
			} else {
				$verification = $this->app->get_row('verifications', 'status, user_id', ['code' => $verification_guid]);
			}

			if (!empty($verification)) {
				if ($verification['status'] != 'ACTIVE') {
					$this->form_validation->set_message('_check_activation_code', 'The activation link is expired or no longer valid. If your account is not activated, please login to receive an email with new activation link.');
					$this->users_model->add_logs($this->_data);
					return FALSE;
				}
			} else {
				$this->form_validation->set_message('_check_activation_code', 'The activation link is expired or no longer valid. If your account is not activated, please login to receive an email with new activation link.');
				$this->users_model->add_logs($this->_data);
				return FALSE;
			}

		}
		return TRUE;
	}
/**
 * CHEKC VERIFY CODE
 */
	public function _check_verify_code($verification_guid) {
		if (!empty($verification_guid)) {

			$device_type = safe_array_key($this->_data, "device_type", "web_browser");
			$device_type_id = array_search($device_type, $this->app->device_types);
			if (in_array($device_type_id, array("1"))) {
				$verification = $this->app->get_row('verifications', 'status', ['verification_guid' => $verification_guid]);
			} else {
				$verification = $this->app->get_row('verifications', 'status', ['code' => $verification_guid]);
			}

			if (!empty($verification)) {
				if ($verification['status'] != 'ACTIVE') {
					$this->form_validation->set_message('_check_verify_code', 'The verification code is expired or no longer valid.');
					return FALSE;
				}
			} else {
				$this->form_validation->set_message('_check_verify_code', 'The verification code is not valid.');
				return FALSE;
			}
		}
		return TRUE;
	}
/**
 * USER LOGIN
 */
	public function login_post() {
		$this->_response["service_name"] = "users/login";
		// $this->form_validation->set_rules('device_type', 'device type', 'trim|required|in_list[' . implode($this->app->device_types, ",") . ']');
		// $this->form_validation->set_rules('device_token', 'device token', 'trim');
		$this->form_validation->set_rules('email', 'email', 'trim|required');
		$this->form_validation->set_rules('password', 'password', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$this->load->model("users_model");

			$email = $this->_data['email'];
			$password = $this->_data['password'];
			$user_id = $this->users_model->check_login($email, $password);
			if ($user_id == 0) {
				$this->_response["message"] = "Invalid email/ password or email not registered.";
				$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
				$this->users_model->add_logs($this->_data);
			} else {
				$user = $this->app->get_row('users', 'status', ['user_id' => $user_id]);
				if ($user['status'] == 'ACTIVE') {
					$device_type = safe_array_key($this->_data, "device_type", "web_browser");
					$device_type_id = array_search($device_type, $this->app->device_types);
					$device_token = safe_array_key($this->_data, "device_token", "");
					$ip_address = $this->input->ip_address();
					$session_id = $this->users_model->create_session_key($user_id, $device_type_id, $device_token, $ip_address);
					$this->_response["data"] = $this->app->user_data($session_id);
					$this->_response["success"] = true;
					
					$this->set_response($this->_response);
				} elseif ($user['status'] == 'PENDING') {
					$device_type = safe_array_key($this->_data, "device_type", "web_browser");
					$device_type_id = array_search($device_type, $this->app->device_types);
					$this->users_model->send_user_verification($user_id, $device_type);
					if (in_array($device_type_id, array("1"))) {
						$this->_response["message"] = "To get started, please click on the verification link sent to your registered email ID $email";
					} else {
						$this->_response["message"] = "To get started, please enter verification code sent to your registered email ID $email";
					}
					$row = $this->app->get_row('users', 'user_guid', ['user_id' => $user_id]);
					$this->_response["data"] = $row;

					$this->set_response($this->_response, REST_Controller::HTTP_EXPECTATION_FAILED);
				} elseif ($user['status'] == 'BLOCKED' || $user['status'] == 'DELETED') {
					$this->_response["message"] = "This account is deactivated. Please contact to our admin";
					$this->set_response($this->_response, REST_Controller::HTTP_GONE);
					$this->users_model->add_logs($this->_data);
				}
			}
		}
	}
/**
 * USER LOGOUT
 */
	public function logout_post() {
		$this->_response["service_name"] = "users/logout";
		$session_key = $this->rest->key;
		$this->db->update('user_login_sessions', [
			"status" => 'LOGGED_OUT',
		], ['session_key' => $session_key]);
		$this->_response["message"] = "logged out successfully";
		$this->set_response($this->_response);
	}
/**
 * USER CHANGE PASSWORD
 */
	public function change_password_post() {
		$this->_response["service_name"] = "users/change_password";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('old_password', 'old password', 'trim|required|callback__check_old_password');
		$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[8]|max_length[12]');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$password = safe_array_key($this->_data, "password", NULL);

			$this->load->model("users_model");
			$this->users_model->change_password($user_id, $password);
			$this->_response["message"] = "Password changed successful.";
			$this->set_response($this->_response);
		}
	}
/**
 * CHECK OLD PASSWORD VALIDATION
 */
	public function _check_old_password($str) {
		$user_id = $this->rest->user_id;
		$where = [
			"user_id" => $user_id,
			"password" => md5($str),
		];
		$rows = $this->app->get_rows('users', 'user_id', $where);
		if (count($rows) < 1) {
			$this->form_validation->set_message('_check_old_password', 'Old password does not match.');
			return FALSE;
		} else {
			$password = safe_array_key($this->_data, "password", NULL);
			if ($str == $password) {
				$this->form_validation->set_message('_check_old_password', 'Old password & New password cannot be same.');
				return FALSE;
			}
			return TRUE;
		}
	}
/**
 * USER FORGOT PASSWORD
 */
	public function forgot_password_post() {
		// print_r($this->_data);die();
		$this->_response["service_name"] = "users/forgot_password";
		// $this->form_validation->set_rules('device_type', 'device type', 'trim|required|in_list[' . implode($this->app->device_types, ",") . ']');
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback__should_exist_email');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$device_type = safe_array_key($this->_data, "device_type", "web_browser");
			$email = safe_array_key($this->_data, "email", "");
			$user = $this->app->get_row('users', 'user_id, user_guid', ['email' => $email]);
			$user_id = $user['user_id'];
			$user_guid = safe_array_key($user, "user_guid", "");
			// SEND PUSH NOTIFICATION
			$title = 'Tikisites';
			$body = 'Seems like you forgot your password...we got your back.';
			// push_notification($user_id, $title, $body);

			$this->users_model->send_reset_password($user_id, $device_type);
			$this->_response["data"] = ['user_guid' => $user_guid];
			$this->_response["message"] = "Instructions to change your password have been sent to your registered email address.";
			$this->set_response($this->_response);
		}
	}
/**
 * EXIST EMAIL
 */
	public function _should_exist_email($str) {
		$this->load->model("users_model");

		$where = [
			"email" => strtolower($str),
		];
		
		
		$row = $this->app->get_row('users', 'user_id, status', $where);
		
		$status = safe_array_key($row, 'status', '');
		if ($status == "PENDING") {
			$device_type = safe_array_key($this->_data, "device_type", "web_browser");
			$this->users_model->send_user_verification($row['user_id'], $device_type);
			$this->form_validation->set_message('_should_exist_email', 'To get started, please click on the verification link sent to your registered email ID ' . strtolower($str));
			return FALSE;
		} elseif ($status == "BLOCKED" || $status == 'DELETED') {
			$this->form_validation->set_message('_should_exist_email', 'This account is deactivated. Please contact support@example.com');
			return FALSE;
		} elseif ($status == "") {
			$this->form_validation->set_message('_should_exist_email', 'Email not registered');
			return FALSE;
		}
		return TRUE;
	}
/**
 * EXIST EMAIL WIHT PENDING
 */
	public function _should_exist_email_with_pending($str) {
		$where = [
			"email" => $str,
			"status" => "PENDING",
		];
		$rows = $this->app->get_rows('users', 'user_id', $where);
		if (empty($rows)) {
			$this->form_validation->set_message('_should_exist_email_with_pending', '{field} does not exists.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
/**
 * USER RESET PASSWORD
 */
	public function reset_password_post() {
		$this->_response["service_name"] = "users/reset_password";

		// $this->form_validation->set_rules('device_type', 'device type', 'trim|required|in_list[' . implode($this->app->device_types, ",") . ']');
		// $this->form_validation->set_rules('password_reset_code', 'password reset code', 'trim|required|callback__check_password_reset_code');
		$this->form_validation->set_rules('password_reset_code', 'password reset code', 'trim|required');
		$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[8]|max_length[12]');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$this->load->model("users_model");

			$device_type = safe_array_key($this->_data, "device_type", "web_browser");
			$password_reset_code = safe_array_key($this->_data, "password_reset_code", "");
			$device_type_id = array_search($device_type, $this->app->device_types);
			$password = safe_array_key($this->_data, "password", "");
			$this->users_model->reset_user_password_by_password_reset_code($device_type_id, $password_reset_code, $password);
			$this->_response['message'] = "Your Account has been updated with your new password.";
			$this->set_response($this->_response);
		}
	}
/**
 * CHECK RESET PASSWORD CODE
 */
	public function check_reset_password_code_post() {
		$this->_response["service_name"] = "users/check_reset_password_code";
		$this->form_validation->set_rules('password_reset_code', 'password reset code', 'trim|required|callback__check_password_reset_code');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$this->load->model("users_model");
			$this->set_response($this->_response);
		}
	}
/**
 * CHECK PASSWORD RESET CODE
 */
	public function _check_password_reset_code($verification_guid) {
		if (!empty($verification_guid)) {
			$device_type = safe_array_key($this->_data, "device_type", "web_browser");
			$device_type_id = array_search($device_type, $this->app->device_types);

			if (in_array($device_type_id, array("1"))) {
				$verification = $this->app->get_row('verifications', 'status, user_id', ['verification_guid' => $verification_guid]);
			} else {
				$verification = $this->app->get_row('verifications', 'status, user_id', ['code' => $verification_guid]);
			}
			if (!empty($verification)) {
				if ($verification['status'] != 'ACTIVE') {
					$this->form_validation->set_message('_check_password_reset_code', 'The password reset code is expired or no longer valid.');
					$this->users_model->add_logs($this->_data);
					return FALSE;
				}

			} else {
				$this->form_validation->set_message('_check_password_reset_code', 'The password reset code is not valid.');
				$this->users_model->add_logs($this->_data);
				return FALSE;
			}

		}
		return TRUE;
	}
/**
 * UPDATE USER DETAILS
 */
	public function update_post() {
		$this->_response["service_name"] = "users/update";
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|max_length[50]|callback__check_alpha_space');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			//$device_token = safe_array_key($this->_data, "device_token", "");
			//$this->db->update('user_login_sessions', ["device_token" => $device_token], ["session_key" => $session_key]);
			$this->set_response($this->_response);
		}
	}
/**
 * COMPLETE USER PROFILE
 */
	public function complete_profile_post() {
		$this->_response["service_name"] = "users/complete_profile";
		$session_key = $this->rest->key;
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('is_work_in_sports_industry', 'Is work in sports industry', 'trim|required');
		$this->form_validation->set_rules('is_looking_for_job', 'Is looking for job', 'trim|required');
		$this->form_validation->set_rules('open_for_all_locations', 'Open for all locations', 'trim|required|in_list[YES,NO]');
		$this->form_validation->set_rules('open_for_all_departments', 'Open for all departments', 'trim|required|in_list[YES,NO]');
		$this->form_validation->set_rules('open_for_all_job_types', 'Open for all job types', 'trim|required|in_list[YES,NO]');

		$open_for_all_locations = safe_array_key($this->_data, "open_for_all_locations", "NO");
		if ($open_for_all_locations == 'NO') {
			$this->form_validation->set_rules('preferred_job_locations', 'Preferred job locations', 'trim|callback__check_preferred_job_locations');
		}

		$open_for_all_departments = safe_array_key($this->_data, "open_for_all_departments", "NO");
		if ($open_for_all_departments == 'NO') {
			$this->form_validation->set_rules('departments', 'Departments', 'trim|callback__check_departments');
		}

		$open_for_all_job_types = safe_array_key($this->_data, "open_for_all_job_types", "NO");
		if ($open_for_all_job_types == 'NO') {
			$this->form_validation->set_rules('user_job_types', 'User job types', 'trim|callback__check_user_job_types');
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$is_work_in_sports_industry = safe_array_key($this->_data, "is_work_in_sports_industry", "");
			$is_looking_for_job = safe_array_key($this->_data, "is_looking_for_job", "");
			$preferred_job_locations = safe_array_key($this->_data, "preferred_job_locations", []);
			$user_job_types = safe_array_key($this->_data, "user_job_types", []);
			$departments = safe_array_key($this->_data, "departments", []);

			$this->users_model->save_preferred_job_locations($user_id, $preferred_job_locations);
			$this->users_model->save_user_job_types($user_id, $user_job_types);
			$this->users_model->save_departments($user_id, $departments);
			$this->users_model->complete_profile($user_id, $is_work_in_sports_industry, $is_looking_for_job, $open_for_all_departments);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

/**
 * CHECK USER PREFERRED JOB LOCATIONS
 */
	public function _check_preferred_job_locations($str) {
		$preferred_job_locations = safe_array_key($this->_data, "preferred_job_locations", []);
		if (empty($preferred_job_locations)) {
			$this->form_validation->set_message('_check_preferred_job_locations', 'Please enter atleast 1 location.');
			return FALSE;
		}
	}
/**
 * CHECK USER JOB TYPES
 */
	public function _check_user_job_types($str) {
		$user_job_types = safe_array_key($this->_data, "user_job_types", []);
		if (empty($user_job_types)) {
			$this->form_validation->set_message('_check_user_job_types', 'Please enter atleast 1 job type.');
			return FALSE;
		}
	}
/**
 * CHECK USER DEPARTMENTS
 */
	public function _check_departments($str) {
		$departments = safe_array_key($this->_data, "departments", []);
		if (empty($departments)) {
			$this->form_validation->set_message('_check_departments', 'Please enter atleast 1 department.');
			return FALSE;
		}
		foreach ($departments as $key => $value) {
			$row = $this->app->get_row('masters', 'master_id', ['master_guid' => $value]);
			if (empty($row)) {
				$this->form_validation->set_message('_check_departments', 'Invalid department.');
				return FALSE;
			}
		}
	}
/**
 * CHECK USER VALUES
 */
	public function _check_values($str) {
		$values = safe_array_key($this->_data, "values", []);
		if (empty($values)) {
			$this->form_validation->set_message('_check_values', 'Please select atleast 1 value.');
			return FALSE;
		}
	}
/**
 * CHECK USER SKILLS
 */
	public function _check_skills_old($str) {
		$skills = safe_array_key($this->_data, "skills", []);
		if (empty($skills)) {
			$this->form_validation->set_message('_check_skills', 'Please add atleast 1 skill.');
			return FALSE;
		}

		if (!empty($skills)) {
			foreach ($skills as $key => $skill) {
				$id = safe_array_key($skill, 'id', "");
				$name = safe_array_key($skill, 'name', "");
				$sub_skills = safe_array_key($skill, 'sub_skills', []);
				if (empty($id)) {
					$this->form_validation->set_message('_check_skills', 'ID is required at position (' . ($key + 1) . ').');
					return FALSE;
				}
				if (empty($name)) {
					$this->form_validation->set_message('_check_skills', 'Name is required at position (' . ($key + 1) . ').');
					return FALSE;
				}
				if (!empty($sub_skills)) {
					foreach ($sub_skills as $k => $sub_skill) {
						$sub_id = safe_array_key($sub_skill, 'id', "");
						$sub_name = safe_array_key($sub_skill, 'name', "");
						if (empty($sub_id)) {
							$this->form_validation->set_message('_check_skills', 'Sub skill ID is required at position (' . ($k + 1) . ').');
							return FALSE;
						}
						if (empty($sub_name)) {
							$this->form_validation->set_message('_check_skills', 'Sub skill Name is required at position (' . ($k + 1) . ').');
							return FALSE;
						}
					}
				}
			}
		}
	}

	public function _check_skills($str) {
		$skills = safe_array_key($this->_data, "skills", []);
		// print_r($skills);die;
		if (empty($skills)) {
			$this->form_validation->set_message('_check_skills', 'Please add atleast 1 skill.');
			return FALSE;
		}

		if (!empty($skills)) {
			$temp_skill_names = [];
			$temp_sub_names = [];
			foreach ($skills as $key => $skill) {
				// $id = safe_array_key($skill, 'id', "");
				$name = safe_array_key($skill, 'name', "");
				$sub_skills = safe_array_key($skill, 'sub_skills', []);
				// if (empty($id)) {
				// 	$this->form_validation->set_message('_check_skills', 'ID is required at position (' . ($key + 1) . ').');
				// 	return FALSE;
				// }
				if (empty($name)) {
					$this->form_validation->set_message('_check_skills', 'Name is required at position (' . ($key + 1) . ').');
					return FALSE;
				}

				if (in_array($name, $temp_skill_names)) {
					$this->form_validation->set_message('_check_skills', 'Skill Name is duplicate.');
					return FALSE;
				} else {
					$temp_skill_names[] = strToLower($name);
				}

				if (!empty($sub_skills)) {
					foreach ($sub_skills as $k => $sub_skill) {
						// $sub_id = safe_array_key($sub_skill, 'id', "");
						$sub_name = safe_array_key($sub_skill, 'name', "");

						// if (empty($sub_id)) {
						// 	$this->form_validation->set_message('_check_skills', 'Sub skill ID is required at position (' . ($k + 1) . ').');
						// 	return FALSE;
						// }
						if (empty($sub_name)) {
							$this->form_validation->set_message('_check_skills', 'Sub skill Name is required at position (' . ($k + 1) . ').');
							return FALSE;
						}

						if (in_array($sub_name, $temp_sub_names)) {
							$this->form_validation->set_message('_check_skills', 'Sub skill Name is duplicate.');
							return FALSE;
						} else {
							$temp_sub_names[] = strToLower($sub_name);
						}

						// if (strToLower($name) == strToLower($sub_name)) {
						// 	$this->form_validation->set_message('_check_skills', 'Skill and subskill must be different at position (' . ($k + 1) . ').');
						// 	return FALSE;
						// }

					}
				}
			}

			if (!empty(array_intersect($temp_skill_names, $temp_sub_names))) {
				$this->form_validation->set_message('_check_skills', 'Skill and subskill must be different.');
				return FALSE;
			}
			// print_r($temp_skill_names);

			// print_r($temp_sub_names);
			// var_dump(!empty(array_intersect($temp_skill_names, $temp_sub_names)));
			// die;
		}
		return TRUE;
	}

/**
 * ADD USER VALUE
 */
	public function add_values_post() {
		$this->_response["service_name"] = "users/add_values";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('values', 'Values', 'trim|callback__check_values');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$values = safe_array_key($this->_data, 'values', []);
			$this->users_model->add_values($user_id, $values);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

	/**
	 * ADD USER SKILLS
	 */
	public function add_skills_post() {
		$this->_response["service_name"] = "users/add_skills";
		$user_id = $this->rest->user_id;

		$this->form_validation->set_rules('skills', 'Skills', 'trim|callback__check_skills');
		// $this->form_validation->set_rules('skills', 'Skills', 'trim');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$skills = safe_array_key($this->_data, 'skills', []);
			// print_r($skills);die;
			$this->users_model->add_skills($user_id, $skills);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

// /**
	//  * CHEKC SKILLS
	//  */
	// 		public function _check_skills($str) {
	// 			$skills = safe_array_key($this->_data, 'skills', []);
	// 			print_r($skills);die();
	// 			if (empty($skills)) {
	// 					$this->form_validation->set_message('_check_skills', 'Country is required.');
	// 					return FALSE;
	// 			}
	// 		}
	/**
	 * ADD USER EDUCATION
	 */
	public function add_education_post() {
		$this->_response["service_name"] = "users/add_education";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('degree', 'degree', 'trim|required|min_length[2]|max_length[100]');
		$this->form_validation->set_rules('organisation_id', 'organisation id', 'trim|required|callback__should_exist_organisation');
		$this->form_validation->set_rules('in_progress', 'in progress', 'trim|required|in_list[YES,NO]');
		$this->form_validation->set_rules('start_date', 'start date', 'trim|required');

		$in_progress = safe_array_key($this->_data, "in_progress", "NO");
		$member_type = 'STUDENT';
		if ($in_progress == 'NO') {
			$this->form_validation->set_rules('end_date', 'end date', 'trim|required');
			$member_type = 'ALUMNI';
			$this->form_validation->set_rules('gpa', 'gpa', 'trim|required|min_length[1]|max_length[10]');
		}
		$this->form_validation->set_rules('student_id', 'student id', 'trim|min_length[3]|max_length[50]');
		$this->form_validation->set_rules('awards', 'Awards', 'trim|callback__check_awards');
		$this->form_validation->set_rules('honours', 'Honours', 'trim|callback__check_honours');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {

			$row1 = $this->app->get_row('users', 'email', ['user_id' => $user_id]);
			$email = safe_array_key($row1, "email", "");
			$degree = safe_array_key($this->_data, "degree", "");
			$organisation_guid = safe_array_key($this->_data, "organisation_id", "");
			$row2 = $this->app->get_row('organisations', 'organisation_id', ['organisation_guid' => $organisation_guid]);
			$organisation_id = safe_array_key($row2, "organisation_id", "");
			$start_date = safe_array_key($this->_data, "start_date", "");
			$end_date = safe_array_key($this->_data, "end_date", "");
			$gpa = safe_array_key($this->_data, "gpa", "");
			$student_id = safe_array_key($this->_data, "student_id", "");
			$awards = safe_array_key($this->_data, "awards", []);
			$honours = safe_array_key($this->_data, "honours", []);
			// $row3 = $this->app->get_row('organisation_members', 'organisation_member_guid', [
			// 	'user_id' => $user_id,
			// 	'organisation_id' => $organisation_id
			// ]);
			// $organisation_member_guid = safe_array_key($row3, 'organisation_member_guid', '');
			$result = (object) [];
			if ($in_progress == 'YES') {
				$gpa = NULL;
				$end_date = '0000-00-00';
			}
			// if (!empty($organisation_member_guid)) {
			// 	$this->users_model->update_education($organisation_member_guid, $degree, $in_progress, $start_date, $end_date, $gpa, $student_id, $member_type);
			// }else{
			// 	$result = $this->users_model->save_education($user_id, $email, $organisation_id, $degree, $in_progress, $start_date, $end_date, $gpa, $student_id, $member_type);
			// }
			$result = $this->users_model->save_education($user_id, $email, $organisation_id, $degree, $in_progress, $start_date, $end_date, $gpa, $student_id, $member_type);
			$this->users_model->add_awards($user_id, $awards, $organisation_id);
			$this->users_model->add_honours($user_id, $honours, $organisation_id);
			$this->_response['message'] = "Success";
			$this->_response['data'] = $result;
			$this->set_response($this->_response);
		}
	}

/**
 * UPDATE USER EDUCATION
 */
	public function update_education_post() {
		$this->_response["service_name"] = "users/update_education";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('organisation_member_id', 'organisation member id', 'trim|required|callback__should_exist_organisation_member');
		$this->form_validation->set_rules('organisation_id', 'organisation id', 'trim|required|callback__should_exist_organisation');
		$this->form_validation->set_rules('degree', 'degree', 'trim|required|min_length[2]|max_length[100]');
		$this->form_validation->set_rules('organisation_id', 'organisation id', 'trim|required|callback__should_exist_organisation');
		$this->form_validation->set_rules('in_progress', 'in progress', 'trim|required|in_list[YES,NO]');
		$this->form_validation->set_rules('start_date', 'start date', 'trim|required');

		$in_progress = safe_array_key($this->_data, "in_progress", "NO");
		$member_type = 'STUDENT';
		if ($in_progress == 'NO') {
			$this->form_validation->set_rules('end_date', 'end date', 'trim|required');
			$member_type = 'ALUMNI';
			$this->form_validation->set_rules('gpa', 'gpa', 'trim|required|min_length[1]|max_length[10]');
		}
		$this->form_validation->set_rules('student_id', 'student id', 'trim|min_length[3]|max_length[50]');
		$this->form_validation->set_rules('awards', 'Awards', 'trim|callback__check_awards');
		$this->form_validation->set_rules('honours', 'Honours', 'trim|callback__check_honours');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {

			$row1 = $this->app->get_row('users', 'email', ['user_id' => $user_id]);
			$email = safe_array_key($row1, "email", "");
			$degree = safe_array_key($this->_data, "degree", "");
			$organisation_guid = safe_array_key($this->_data, "organisation_id", "");
			$organisation_member_guid = safe_array_key($this->_data, "organisation_member_id", "");
			$row2 = $this->app->get_row('organisations', 'organisation_id', ['organisation_guid' => $organisation_guid]);
			$organisation_id = safe_array_key($row2, "organisation_id", "");
			$start_date = safe_array_key($this->_data, "start_date", "");
			$end_date = safe_array_key($this->_data, "end_date", "");
			$gpa = safe_array_key($this->_data, "gpa", "");
			$student_id = safe_array_key($this->_data, "student_id", "");
			$awards = safe_array_key($this->_data, "awards", []);
			$honours = safe_array_key($this->_data, "honours", []);
			// $row3 = $this->app->get_row('organisation_members', 'organisation_member_guid', [
			// 	'user_id' => $user_id,
			// 	'organisation_id' => $organisation_id
			// ]);
			// $organisation_member_guid = safe_array_key($row3, 'organisation_member_guid', '');
			// print_r($organisation_member_guid);die();
			$result = (object) [];
			if ($in_progress == 'YES') {
				$gpa = NULL;
				$end_date = '0000-00-00';
			}

			$this->users_model->update_education($organisation_member_guid, $organisation_id, $degree, $in_progress, $start_date, $end_date, $gpa, $student_id, $member_type);

			$this->users_model->add_awards($user_id, $awards, $organisation_id);
			$this->users_model->add_honours($user_id, $honours, $organisation_id);
			$this->_response['message'] = "Success";
			$this->_response['data'] = $result;
			$this->set_response($this->_response);
		}
	}

/**
 * DELETE USER EDUCATION
 */
	public function delete_education_post() {
		$this->_response["service_name"] = "users/delete_education";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('organisation_member_id', 'organisation member id', 'trim|required|callback__should_exist_organisation_member');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$organisation_member_guid = safe_array_key($this->_data, "organisation_member_id", "");
			$row = $this->app->get_row('organisation_members', 'organisation_id', [
				'organisation_member_guid' => $organisation_member_guid,
			]);
			$organisation_id = safe_array_key($row, 'organisation_id', '');
			$this->users_model->delete_education($organisation_member_guid);
			$this->users_model->delete_awards($user_id, $organisation_id);
			$this->users_model->delete_honours($user_id, $organisation_id);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

/**
 * USER CAN ADD UNIVERSITY
 */
	public function add_university_post() {
		$this->_response["service_name"] = "users/add_university";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]|max_length[100]');
		$this->form_validation->set_rules('website', 'Website', 'trim|required|valid_url_format|prep_url|url_exists|callback__ckeck_unique');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$name = safe_array_key($this->_data, "name", "");
			$website = safe_array_key($this->_data, "website", "");
			$organisation_guid = $this->fix_domain_name($website);
			$row = $this->app->get_row('organisation_types_master', 'organisation_type_id', [
				'organisation_type_guid' => 'university',
			]);
			$organisation_type_id = safe_array_key($row, "organisation_type_id", "");
			$organisation_id = $this->users_model->add_university($user_id, $organisation_guid, $organisation_type_id, $name, $website);
			$row2 = $this->app->get_row('organisations', 'organisation_guid', [
				'organisation_id' => $organisation_id,
			]);
			$this->_response['data'] = $row2;
			$this->_response['message'] = "University added successfully.";
			$this->set_response($this->_response);
		}
	}

/**
 * USER ADD PROFESSIONAL EXPERIENCE
 */
	public function add_professional_experience_post() {
		$this->_response["service_name"] = "users/add_professional_experience";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('organisation_id', 'organisation id', 'trim|required|callback__should_exist_organisation');
		// $this->form_validation->set_rules('position', 'position', 'trim|required|callback__should_exist_designation');
		$this->form_validation->set_rules('position', 'position', 'trim|required');
		// $this->form_validation->set_rules('location', 'location', 'trim|callback__check_location');
		$this->form_validation->set_rules('location', 'location', 'trim');
		$this->form_validation->set_rules('currently_working', 'in progress', 'trim|required|in_list[YES,NO]');
		$this->form_validation->set_rules('start_date', 'start date', 'trim|required');

		$currently_working = safe_array_key($this->_data, "currently_working", "NO");
		if ($currently_working == 'NO') {
			$this->form_validation->set_rules('end_date', 'end date', 'trim|required');
		}
		$this->form_validation->set_rules('official_email', 'official email', 'trim|valid_email|min_length[5]|max_length[100]');
		$this->form_validation->set_rules('responsibilities', 'Responsibilities', 'trim|callback__check_responsibilities');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$organisation_guid = safe_array_key($this->_data, "organisation_id", "");
			$row1 = $this->app->get_row('organisations', 'organisation_id', ['organisation_guid' => $organisation_guid]);
			$organisation_id = safe_array_key($row1, "organisation_id", "");

			$position = safe_array_key($this->_data, "position", "");
			// $designation_guid = safe_array_key($this->_data, "position", "");
			// $row2 = $this->app->get_row('masters', 'master_id', ['master_guid' => $designation_guid]);
			// $designation_id = safe_array_key($row2, "master_id", "");

			$start_date = safe_array_key($this->_data, "start_date", "");
			$end_date = safe_array_key($this->_data, "end_date", "");
			$official_email = safe_array_key($this->_data, "official_email", "");
			$responsibilities = safe_array_key($this->_data, "responsibilities", []);

			// $row3 = $this->app->get_row('organisation_members', 'organisation_member_guid', [
			// 	'user_id' => $user_id,
			// 	'organisation_id' => $organisation_id
			// ]);

			$row4 = $this->app->get_row('users', 'email', ['user_id' => $user_id]);
			$email = safe_array_key($row4, "email", "");

			$location = safe_array_key($this->_data, "location", NULL);
			$country = strtolower(safe_array_key($location, "country", NULL));
			$state = strtolower(safe_array_key($location, "state", NULL));
			$city = strtolower(safe_array_key($location, "city", NULL));
			// print_r($country);die();
			if ($currently_working == 'YES') {
				$end_date = '0000-00-00';
			}
			// if (!empty($row3)) {
			// 	$organisation_member_guid = safe_array_key($row3, 'organisation_member_guid', '');
			// 	$this->users_model->update_professional_experience($organisation_member_guid, $designation_id, $currently_working, $start_date, $end_date, $official_email, $country, $state, $city);
			// }else{
			// 	$this->users_model->add_professional_experience($user_id, $email, $organisation_id, $designation_id, $currently_working, $start_date, $end_date, $official_email, $country, $state, $city);
			// }
			$designation_id = $this->users_model->add_designation($position);
			$this->users_model->add_professional_experience($user_id, $email, $organisation_id, $designation_id, $currently_working, $start_date, $end_date, $official_email, $country, $state, $city);
			$this->users_model->add_responsibilities($user_id, $responsibilities, $organisation_id);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}
/**
 * USER UPDATE PROFESSIONAL EXPERIENCE
 */
	public function update_professional_experience_post() {
		$this->_response["service_name"] = "users/update_professional_experience";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('organisation_member_id', 'organisation member id', 'trim|required|callback__should_exist_organisation_member');
		$this->form_validation->set_rules('organisation_id', 'organisation id', 'trim|required|callback__should_exist_organisation');
		$this->form_validation->set_rules('position', 'position', 'trim|required|callback__should_exist_designation');
		// $this->form_validation->set_rules('location', 'location', 'trim|callback__check_location');
		$this->form_validation->set_rules('location', 'location', 'trim');
		$this->form_validation->set_rules('currently_working', 'in progress', 'trim|required|in_list[YES,NO]');
		$this->form_validation->set_rules('start_date', 'start date', 'trim|required');

		$currently_working = safe_array_key($this->_data, "currently_working", "NO");
		if ($currently_working == 'NO') {
			$this->form_validation->set_rules('end_date', 'end date', 'trim|required');
		}
		$this->form_validation->set_rules('official_email', 'official email', 'trim|valid_email|min_length[5]|max_length[100]');
		$this->form_validation->set_rules('responsibilities', 'Responsibilities', 'trim|callback__check_responsibilities');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$organisation_member_guid = safe_array_key($this->_data, "organisation_member_id", "");
			$organisation_guid = safe_array_key($this->_data, "organisation_id", "");
			$row1 = $this->app->get_row('organisations', 'organisation_id', ['organisation_guid' => $organisation_guid]);
			$organisation_id = safe_array_key($row1, "organisation_id", "");

			$position = safe_array_key($this->_data, "position", "");
			// $designation_guid = safe_array_key($this->_data, "position", "");
			// $row2 = $this->app->get_row('masters', 'master_id', ['master_guid' => $designation_guid]);
			// $designation_id = safe_array_key($row2, "master_id", "");

			$start_date = safe_array_key($this->_data, "start_date", "");
			$end_date = safe_array_key($this->_data, "end_date", "");
			$official_email = safe_array_key($this->_data, "official_email", "");
			$responsibilities = safe_array_key($this->_data, "responsibilities", []);

			$row4 = $this->app->get_row('users', 'email', ['user_id' => $user_id]);
			$email = safe_array_key($row4, "email", "");

			$location = safe_array_key($this->_data, "location", "");
			$country = strtolower(safe_array_key($location, "country", ""));
			$state = strtolower(safe_array_key($location, "state", ""));
			$city = strtolower(safe_array_key($location, "city", ""));

			if ($currently_working == 'YES') {
				$end_date = '0000-00-00';
			}
			$designation_id = $this->users_model->add_designation($position);
			$this->users_model->update_professional_experience($organisation_member_guid, $organisation_id, $designation_id, $currently_working, $start_date, $end_date, $official_email, $country, $state, $city);
			$this->users_model->add_responsibilities($user_id, $responsibilities, $organisation_id);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

/**
 * USER DELETE PROFESSIONAL EXPERIENCE
 */
	public function delete_professional_experience_post() {
		$this->_response["service_name"] = "users/delete_professional_experience";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('organisation_member_id', 'organisation member id', 'trim|required|callback__should_exist_organisation_member');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$organisation_member_guid = safe_array_key($this->_data, "organisation_member_id", "");
			$row = $this->app->get_row('organisation_members', 'organisation_id', [
				'organisation_member_guid' => $organisation_member_guid,
			]);
			$organisation_id = safe_array_key($row, 'organisation_id', '');
			$this->users_model->delete_professional_experience($organisation_member_guid);
			$this->users_model->delete_responsibilities($user_id, $organisation_id);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

/**
 * CHEKC LOCATION
 */
	public function _check_location($str) {
		$location = safe_array_key($this->_data, "location", []);
		$country = safe_array_key($location, "country", "");
		$state = safe_array_key($location, "state", "");
		$city = safe_array_key($location, "city", "");
		if ($country == "") {
			$this->form_validation->set_message('_check_location', 'Country is required.');
			return FALSE;
		}
		if ($state == "") {
			$this->form_validation->set_message('_check_location', 'State is required.');
			return FALSE;
		}
		if ($city == "") {
			$this->form_validation->set_message('_check_location', 'City is required.');
			return FALSE;
		}
	}
/**
 * USER CAN ADD COMPANY
 */
	public function add_company_post() {
		$this->_response["service_name"] = "users/add_company";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]|max_length[100]');
		$this->form_validation->set_rules('website', 'Website', 'trim|required|valid_url_format|prep_url|url_exists|callback__ckeck_unique');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$name = safe_array_key($this->_data, "name", "");
			$website = safe_array_key($this->_data, "website", "");
			$organisation_guid = $this->fix_domain_name($website);
			$row = $this->app->get_row('organisation_types_master', 'organisation_type_id', [
				'organisation_type_guid' => 'company',
			]);
			$organisation_type_id = safe_array_key($row, "organisation_type_id", "");
			$organisation_id = $this->users_model->add_company($user_id, $organisation_guid, $organisation_type_id, $name, $website);
			$row2 = $this->app->get_row('organisations', 'organisation_guid', [
				'organisation_id' => $organisation_id,
			]);
			$this->_response['data'] = $row2;
			$this->_response['message'] = "Company added successfully.";
			$this->set_response($this->_response);
		}
	}
/**
 * CHECK UNIQUE ORGANISATION
 */
	public function _ckeck_unique($str, $organisation_id = "") {
		$website = safe_array_key($this->_data, "website", "");
		$organisation_guid = $this->fix_domain_name($website);
		$organisation_data = $this->app->get_rows('organisations', 'organisation_id, organisation_guid', ['organisation_id !=' => $organisation_id]);
		foreach ($organisation_data as $value) {
			if ($organisation_guid === strtolower($value['organisation_guid'])) {
				$this->form_validation->set_message('_ckeck_unique', $str . ' is already exist.');
				return FALSE;
			}
		}
		return TRUE;
	}
/**
 * GET GUID FROM DOMAIN NAME
 */
	public function fix_domain_name($url) {
		$strToLower = strtolower(trim($url));
		$httpPregReplace = preg_replace('/^http:\/\//i', '', $strToLower);
		$httpsPregReplace = preg_replace('/^https:\/\//i', '', $httpPregReplace);
		$wwwPregReplace = preg_replace('/^www\./i', '', $httpsPregReplace);
		$explodeToArray = explode('/', $wwwPregReplace);
		$finalDomainName = trim($explodeToArray[0]);
		$guid = str_ireplace(".", "_", $finalDomainName);
		return $guid;
	}
/**
 * VALIDATONS FOR AWARDS
 */
	public function _check_awards($str) {
		$awards = safe_array_key($this->_data, "awards", []);
		// if (empty($awards)) {
		// 		$this->form_validation->set_message('_check_awards', 'Please select atleast 1.');
		// 		return FALSE;
		// } else
		if (count($awards) > 3) {
			$this->form_validation->set_message('_check_awards', 'Select maximum 3 only.');
			return FALSE;
		}
	}
/**
 * VALIDATIONS FOR HONOURS
 */
	public function _check_honours($str) {
		$honours = safe_array_key($this->_data, "honours", []);
		// if (empty($honours)) {
		// 		$this->form_validation->set_message('_check_honours', 'Please select atleast 1.');
		// 		return FALSE;
		// } else
		if (count($honours) > 3) {
			$this->form_validation->set_message('_check_honours', 'Select maximum 3 only.');
			return FALSE;
		}
	}
/**
 * VALIDATIONS FOR RESPONSIBILITIES
 */
	public function _check_responsibilities($str) {
		$responsibilities = safe_array_key($this->_data, "responsibilities", []);
		// if (!empty($responsibilities)) {
		// 	foreach ($responsibilities as $key => $value) {
		// 		print_r($value);
		// 		$row =
		// 	}
		// 	die();
		// 		$this->form_validation->set_message('_check_responsibilities', 'Please select valid responsibility.');
		// 		return FALSE;
		// } else

		if (count($responsibilities) > 3) {
			$this->form_validation->set_message('_check_responsibilities', 'Select maximum 3 only.');
			return FALSE;
		}
	}
/**
 * ADD USER VOLUNTEERING
 */
	public function add_volunteering_post() {
		$this->_response["service_name"] = "users/add_volunteering";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('organisation_id', 'organisation id', 'trim|required|callback__should_exist_organisation');
		// $this->form_validation->set_rules('organisation_name', 'organisation id', 'trim|required|min_length[3]|max_length[100]|callback__check_alpha_space');
		// $this->form_validation->set_rules('position', 'position', 'trim|required|callback__should_exist_designation');
		$this->form_validation->set_rules('position', 'position', 'trim|required');
		// $this->form_validation->set_rules('location', 'location', 'trim|callback__check_location');
		$this->form_validation->set_rules('location', 'location', 'trim');
		$this->form_validation->set_rules('currently_working', 'in progress', 'trim|required|in_list[YES,NO]');
		$this->form_validation->set_rules('start_date', 'start date', 'trim|required');

		$currently_working = safe_array_key($this->_data, "currently_working", "NO");
		if ($currently_working == 'NO') {
			$this->form_validation->set_rules('end_date', 'end date', 'trim|required');
		}

		$this->form_validation->set_rules('responsibilities', 'Responsibilities', 'trim|callback__check_responsibilities');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {

			$organisation_guid = safe_array_key($this->_data, "organisation_id", "");
			$row1 = $this->app->get_row('organisations', 'organisation_id', ['organisation_guid' => $organisation_guid]);
			$organisation_id = safe_array_key($row1, "organisation_id", "");
			// $organisation_name = safe_array_key($this->_data, "organisation_name", "");

			// $designation_guid = safe_array_key($this->_data, "position", "");
			// $row2 = $this->app->get_row('masters', 'master_id', ['master_guid' => $designation_guid]);
			// $designation_id = safe_array_key($row2, "master_id", "");

			$designation = safe_array_key($this->_data, "position", "");
			$start_date = safe_array_key($this->_data, "start_date", "");
			$end_date = safe_array_key($this->_data, "end_date", "");
			if ($currently_working == 'YES') {
				$end_date = '0000-00-00';
			}
			$responsibilities = safe_array_key($this->_data, "responsibilities", []);

			// $row3 = $this->app->get_row('user_volunteerings', 'volunteering_id', [
			// 	'user_id' => $user_id
			// ]);

			$row4 = $this->app->get_row('users', 'email', ['user_id' => $user_id]);
			$email = safe_array_key($row4, "email", "");

			$location = safe_array_key($this->_data, "location", "");
			$country = strtolower(safe_array_key($location, "country", ""));
			$state = strtolower(safe_array_key($location, "state", ""));
			$city = strtolower(safe_array_key($location, "city", ""));
			// $address_1 = strtolower(safe_array_key($location, "address_1", ""));
			// $address_2 = strtolower(safe_array_key($location, "address_2", ""));
			// if (!empty($row3)) {
			// 	$volunteering_id = safe_array_key($row3, 'volunteering_id', '');
			// 	$this->users_model->update_volunteering($user_id, $volunteering_id, $organisation_name, $designation, $currently_working, $start_date, $end_date, $country, $state, $city);
			// }else{
			// 	$volunteering_id = $this->users_model->add_volunteering($user_id, $organisation_name, $designation, $currently_working, $start_date, $end_date, $country, $state, $city);
			// }
			// if ($currently_working == 'YES') {
			// 	$end_date = '0000-00-00';
			// }
			$volunteering_id = $this->users_model->add_volunteering($user_id, $email, $organisation_id, $designation, $currently_working, $start_date, $end_date, $country, $state, $city);
			$this->users_model->add_volunteering_responsibilities($user_id, $responsibilities, $organisation_id);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

/**
 * USER UPDATE VOLUNTEERING
 */
	public function update_volunteering_post() {
		$this->_response["service_name"] = "users/update_volunteering";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('organisation_member_id', 'organisation member id', 'trim|required|callback__should_exist_organisation_member');
		$this->form_validation->set_rules('organisation_id', 'organisation id', 'trim|required|callback__should_exist_organisation');
		// $this->form_validation->set_rules('position', 'position', 'trim|required|callback__should_exist_designation');
		$this->form_validation->set_rules('position', 'position', 'trim|required');
		// $this->form_validation->set_rules('location', 'location', 'trim|callback__check_location');
		$this->form_validation->set_rules('location', 'location', 'trim');
		$this->form_validation->set_rules('currently_working', 'in progress', 'trim|required|in_list[YES,NO]');
		$this->form_validation->set_rules('start_date', 'start date', 'trim|required');

		$currently_working = safe_array_key($this->_data, "currently_working", "NO");
		if ($currently_working == 'NO') {
			$this->form_validation->set_rules('end_date', 'end date', 'trim|required');
		}
		$this->form_validation->set_rules('responsibilities', 'Responsibilities', 'trim|callback__check_responsibilities');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$organisation_member_guid = safe_array_key($this->_data, "organisation_member_id", "");
			$organisation_guid = safe_array_key($this->_data, "organisation_id", "");
			$row1 = $this->app->get_row('organisations', 'organisation_id', ['organisation_guid' => $organisation_guid]);
			$organisation_id = safe_array_key($row1, "organisation_id", "");

			// $designation_guid = safe_array_key($this->_data, "position", "");
			// $row2 = $this->app->get_row('masters', 'master_id', ['master_guid' => $designation_guid]);
			// $designation_id = safe_array_key($row2, "master_id", "");
			$designation = safe_array_key($this->_data, "position", "");

			$start_date = safe_array_key($this->_data, "start_date", "");
			$end_date = safe_array_key($this->_data, "end_date", "");
			$official_email = safe_array_key($this->_data, "official_email", "");
			$responsibilities = safe_array_key($this->_data, "responsibilities", []);

			$row4 = $this->app->get_row('users', 'email', ['user_id' => $user_id]);
			$email = safe_array_key($row4, "email", "");

			$location = safe_array_key($this->_data, "location", "");
			$country = strtolower(safe_array_key($location, "country", ""));
			$state = strtolower(safe_array_key($location, "state", ""));
			$city = strtolower(safe_array_key($location, "city", ""));

			if ($currently_working == 'YES') {
				$end_date = '0000-00-00';
			}
			$this->users_model->update_volunteering($organisation_member_guid, $organisation_id, $designation, $currently_working, $start_date, $end_date, $country, $state, $city);
			$this->users_model->add_volunteering_responsibilities($user_id, $responsibilities, $organisation_id);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

/**
 * USER DELETE VOLUTEERING
 */
	public function delete_volunteering_post() {
		$this->_response["service_name"] = "users/delete_volunteering";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('organisation_member_id', 'organisation member id', 'trim|required|callback__should_exist_organisation_member');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$organisation_member_guid = safe_array_key($this->_data, "organisation_member_id", "");
			$row = $this->app->get_row('organisation_members', 'organisation_id', [
				'organisation_member_guid' => $organisation_member_guid,
			]);
			$organisation_id = safe_array_key($row, 'organisation_id', '');
			$this->users_model->delete_volunteering($organisation_member_guid);
			$this->users_model->delete_volunteering_responsibilities($user_id, $organisation_id);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

/**
 * ADD USER AS AN ATHLETE OLD
 */
	public function add_as_an_athlete_post_old() {
		$this->_response["service_name"] = "users/add_as_an_athlete";
		$user_id = $this->rest->user_id;
		// $this->form_validation->set_rules('represent_as_an_athlete', 'represent as an athlete', 'trim|required|in_list[YES,NO]');

		// $represent_as_an_athlete = safe_array_key($this->_data, "represent_as_an_athlete", "NO");
		// if ($represent_as_an_athlete == 'YES') {
		$this->form_validation->set_rules('organisation_name', 'organisation name', 'trim|required|min_length[3]|max_length[100]|callback__check_alpha_space');
		$this->form_validation->set_rules('category', 'category', 'trim|required|callback__check_user_represent_as_an_athlete');
		// }

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {

			$organisation_name = safe_array_key($this->_data, "organisation_name", "");
			$category = safe_array_key($this->_data, "category", "");
			// $this->users_model->update_user_represent_as_an_athlete($user_id, $represent_as_an_athlete);

			// if ($represent_as_an_athlete == 'YES') {
			$this->users_model->add_as_an_athlete($user_id, $organisation_name, $category);
			// }
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

/**
 * ADD USER AS AN ATHLETE
 */
	public function add_as_an_athlete_post() {
		$this->_response["service_name"] = "users/add_as_an_athlete";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('organisation_id', 'organisation id', 'trim|required|callback__should_exist_organisation_id');
		$this->form_validation->set_rules('athlete_category_id', 'athlete category id', 'trim|required|callback__should_exist_in_master');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$organisation_guid = safe_array_key($this->_data, "organisation_id", "");
			$row1 = $this->app->get_row('organisations', 'organisation_id', ['organisation_guid' => $organisation_guid]);
			$organisation_id = safe_array_key($row1, "organisation_id", "");
			$athlete_category_guid = safe_array_key($this->_data, "athlete_category_id", "");
			$row2 = $this->app->get_row('masters', 'master_id', ['master_guid' => $athlete_category_guid]);
			$athlete_category_id = safe_array_key($row2, "master_id", "");
			$row3 = $this->app->get_row('users', 'email', ['user_id' => $user_id]);
			$email = safe_array_key($row3, "email", "");
			$this->users_model->add_as_an_athlete($user_id, $email, $organisation_id, $athlete_category_id);
			$represent_as_an_athlete = "YES";
			$this->users_model->update_user_represent_as_an_athlete($user_id, $represent_as_an_athlete);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}
/**
 * CHECK MASTER ID EXIST OR NOT
 */
	public function _should_exist_in_master($master_guid) {
		$where = [
			"master_guid" => $master_guid,
		];
		$rows = $this->app->get_rows('masters', 'master_id', $where);
		if (count($rows) < 1) {
			$this->form_validation->set_message('_should_exist_in_master', '{field} is not exists');
			return FALSE;
		} else {
			return TRUE;
		}
	}

/**
 * CHECK USER REPRESENT AS AN ATHLETE
 */
	public function _check_user_represent_as_an_athlete() {
		$user_id = $this->rest->user_id;
		$row = $this->app->get_row('users', 'represent_as_an_athlete', ['user_id' => $user_id]);
		$represent_as_an_athlete = safe_array_key($row, 'represent_as_an_athlete', '');
		if ($represent_as_an_athlete == 'NO') {
			$this->form_validation->set_message('_check_user_represent_as_an_athlete', 'Cannot perform this action, first check YES represent as an athlete.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

/**
 * ADD USER AS AN ATHLETE
 */
	public function update_as_an_athlete_post() {
		$this->_response["service_name"] = "users/update_as_an_athlete";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('organisation_member_id', 'organisation member id', 'trim|required|callback__should_exist_organisation_member');
		$this->form_validation->set_rules('organisation_id', 'organisation id', 'trim|required|callback__should_exist_organisation_id');
		$this->form_validation->set_rules('athlete_category_id', 'athlete category id', 'trim|required|callback__should_exist_in_master');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$organisation_member_guid = safe_array_key($this->_data, "organisation_member_id", "");
			$organisation_guid = safe_array_key($this->_data, "organisation_id", "");
			$row1 = $this->app->get_row('organisations', 'organisation_id', ['organisation_guid' => $organisation_guid]);
			$organisation_id = safe_array_key($row1, "organisation_id", "");
			$athlete_category_guid = safe_array_key($this->_data, "athlete_category_id", "");
			$row2 = $this->app->get_row('masters', 'master_id', ['master_guid' => $athlete_category_guid]);
			$athlete_category_id = safe_array_key($row2, "master_id", "");
			$this->users_model->update_as_an_athlete($organisation_member_guid, $organisation_id, $athlete_category_id);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

/**
 * DELETE USER AS AN ATHLETE
 */
	public function delete_as_an_athlete_post() {
		$this->_response["service_name"] = "users/delete_as_an_athlete";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('organisation_member_id', 'organisation member id', 'trim|required|callback__should_exist_organisation_member');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$organisation_member_guid = safe_array_key($this->_data, "organisation_member_id", "");
			$row = $this->app->get_row('organisation_members', 'organisation_id', [
				'organisation_member_guid' => $organisation_member_guid,
			]);
			$organisation_id = safe_array_key($row, 'organisation_id', '');
			$this->users_model->delete_user_athlete($organisation_member_guid);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

/**
 * UPDATE USER AS AN ATHLETE OLD
 */
	public function update_as_an_athlete_post_old() {
		$this->_response["service_name"] = "users/update_as_an_athlete";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('athlete_id', 'athlete id', 'trim|required|callback__should_exist_athlete');
		$this->form_validation->set_rules('organisation_name', 'organisation name', 'trim|required|min_length[3]|max_length[100]|callback__check_alpha_space');
		$this->form_validation->set_rules('category', 'category', 'trim|required|callback__check_user_represent_as_an_athlete');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$athlete_guid = safe_array_key($this->_data, "athlete_id", "");
			$organisation_name = safe_array_key($this->_data, "organisation_name", "");
			$category = safe_array_key($this->_data, "category", "");
			$this->users_model->update_as_an_athlete($athlete_guid, $organisation_name, $category);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}
/**
 * DELETE USER AS AN ATHLETE OLD
 */
	public function delete_as_an_athlete_post_old() {
		$this->_response["service_name"] = "users/delete_as_an_athlete";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('athlete_id', 'athlete id', 'trim|required|callback__should_exist_athlete');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$athlete_guid = safe_array_key($this->_data, "athlete_id", "");
			$this->users_model->delete_user_athlete($athlete_guid);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

/**
 * CHECK ATHLETE ID EXIST OR NOT
 */
	public function _should_exist_athlete($athlete_guid) {
		$where = [
			"athlete_guid" => $athlete_guid,
		];
		$rows = $this->app->get_rows('user_athletes', 'athlete_id', $where);
		if (count($rows) < 1) {
			$this->form_validation->set_message('_should_exist_athlete', '{field} is not exists');
			return FALSE;
		} else {
			return TRUE;
		}
	}

/**
 * USER ADD PERSONAL DEVELOPMENT OLD
 */
	public function add_personal_development_post_old() {
		$this->_response["service_name"] = "users/add_personal_development";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('certificate_name', 'certificate name', 'trim|required|min_length[3]|max_length[100]|callback__check_alpha_space');
		$this->form_validation->set_rules('year_of_completion', 'year of completion', 'trim|required|numeric');
		$this->form_validation->set_rules('organisation_name', 'organisation name', 'trim|required|min_length[3]|max_length[100]|callback__check_alpha_space');
		$this->form_validation->set_rules('responsibilities', 'Responsibilities', 'trim|callback__check_responsibilities');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$certificate_name = safe_array_key($this->_data, "certificate_name", "");
			$year_of_completion = safe_array_key($this->_data, "year_of_completion", "");
			$organisation_name = safe_array_key($this->_data, "organisation_name", "");
			$responsibilities = safe_array_key($this->_data, "responsibilities", []);
			$personal_development_id = $this->users_model->add_personal_development($user_id, $certificate_name, $year_of_completion, $organisation_name);
			$this->users_model->add_personal_development_responsibilities($user_id, $responsibilities, $personal_development_id);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

/**
 * USER ADD PERSONAL DEVELOPMENT
 */
	public function add_personal_development_post() {
		$this->_response["service_name"] = "users/add_personal_development";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('certificate_name', 'certificate name', 'trim|required|min_length[3]|max_length[100]|callback__check_alpha_space');
		$this->form_validation->set_rules('year_of_completion', 'year of completion', 'trim|required|numeric');
		$this->form_validation->set_rules('organisation_id', 'organisation id', 'trim|required|callback__should_exist_organisation_id');
		$this->form_validation->set_rules('responsibilities', 'Responsibilities', 'trim|callback__check_responsibilities');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$certificate_name = safe_array_key($this->_data, "certificate_name", "");
			$year_of_completion = safe_array_key($this->_data, "year_of_completion", "");
			$organisation_guid = safe_array_key($this->_data, "organisation_id", "");
			$organisation_id = get_guid_detail($organisation_guid, 'organisation');
			$email = get_detail_by_id($user_id, 'user', 'email');
			$responsibilities = safe_array_key($this->_data, "responsibilities", []);
			$this->users_model->add_personal_development($user_id, $email, $certificate_name, $year_of_completion, $organisation_id);
			$this->users_model->add_personal_development_responsibilities($user_id, $responsibilities, $organisation_id);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

/**
 * UPDATE PERSONAL DEVELOPMENT
 */
	public function update_personal_development_post_old() {
		$this->_response["service_name"] = "users/update_personal_development";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('personal_development_id', 'personal development id', 'trim|required|callback__should_exist_personal_development');
		$this->form_validation->set_rules('certificate_name', 'certificate name', 'trim|required|min_length[3]|max_length[100]|callback__check_alpha_space');
		$this->form_validation->set_rules('year_of_completion', 'year of completion', 'trim|required|numeric');
		$this->form_validation->set_rules('organisation_name', 'organisation name', 'trim|required|min_length[3]|max_length[100]|callback__check_alpha_space');
		$this->form_validation->set_rules('responsibilities', 'Responsibilities', 'trim|callback__check_responsibilities');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$personal_development_guid = safe_array_key($this->_data, "personal_development_id", "");
			$row = $this->app->get_row('user_personal_developments', 'personal_development_id', ['personal_development_guid' => $personal_development_guid]);
			$personal_development_id = safe_array_key($row, "personal_development_id", "");
			$certificate_name = safe_array_key($this->_data, "certificate_name", "");
			$year_of_completion = safe_array_key($this->_data, "year_of_completion", "");
			$organisation_name = safe_array_key($this->_data, "organisation_name", "");
			$responsibilities = safe_array_key($this->_data, "responsibilities", []);
			$this->users_model->update_personal_development($personal_development_guid, $certificate_name, $year_of_completion, $organisation_name);
			$this->users_model->add_personal_development_responsibilities($user_id, $responsibilities, $personal_development_id);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

/**
 * UPDATE PERSONAL DEVELOPMENT
 */
	public function update_personal_development_post() {
		$this->_response["service_name"] = "users/update_personal_development";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('organisation_member_id', 'organisation member id', 'trim|required|callback__should_exist_organisation_member');
		$this->form_validation->set_rules('certificate_name', 'certificate name', 'trim|required|min_length[3]|max_length[100]|callback__check_alpha_space');
		$this->form_validation->set_rules('year_of_completion', 'year of completion', 'trim|required|numeric');
		$this->form_validation->set_rules('organisation_id', 'organisation id', 'trim|required|callback__should_exist_organisation_id');
		$this->form_validation->set_rules('responsibilities', 'Responsibilities', 'trim|callback__check_responsibilities');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$organisation_member_guid = safe_array_key($this->_data, "organisation_member_id", "");
			$organisation_guid = safe_array_key($this->_data, "organisation_id", "");
			$organisation_id = get_guid_detail($organisation_guid, 'organisation');

			$certificate_name = safe_array_key($this->_data, "certificate_name", "");
			$year_of_completion = safe_array_key($this->_data, "year_of_completion", "");
			$responsibilities = safe_array_key($this->_data, "responsibilities", []);
			$this->users_model->update_personal_development($organisation_member_guid, $certificate_name, $year_of_completion, $organisation_id);
			$this->users_model->add_personal_development_responsibilities($user_id, $responsibilities, $organisation_id);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

/**
 * DELETE PERSONAL DEVELOPMENT OLD
 */
	public function delete_personal_development_post_old() {
		$this->_response["service_name"] = "users/delete_personal_development";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('personal_development_id', 'personal development id', 'trim|required|callback__should_exist_personal_development');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$personal_development_guid = safe_array_key($this->_data, "personal_development_id", "");
			$row = $this->app->get_row('user_personal_developments', 'personal_development_id', ['personal_development_guid' => $personal_development_guid]);
			$personal_development_id = safe_array_key($row, "personal_development_id", "");
			$this->users_model->delete_personal_development($personal_development_guid);
			$this->users_model->delete_personal_development_responsibilities($user_id, $personal_development_id);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

/**
 * DELETE PERSONAL DEVELOPMENT
 */
	public function delete_personal_development_post() {
		$this->_response["service_name"] = "users/delete_personal_development";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('organisation_member_id', 'organisation member id', 'trim|required|callback__should_exist_organisation_member');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$organisation_member_guid = safe_array_key($this->_data, "organisation_member_id", "");
			$organisation_id = get_guid_detail($organisation_member_guid, 'organisation_member', 'organisation_id');
			$this->users_model->delete_personal_development($organisation_member_guid);
			$this->users_model->delete_personal_development_responsibilities($user_id, $organisation_id);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

/**
 * CHECK PERSONAL DEVELOPMENT ID EXIST OR NOT
 */
	public function _should_exist_personal_development($personal_development_guid) {
		$where = [
			"personal_development_guid" => $personal_development_guid,
		];
		$rows = $this->app->get_rows('user_personal_developments', 'personal_development_id', $where);
		if (count($rows) < 1) {
			$this->form_validation->set_message('_should_exist_personal_development', '{field} is not exists');
			return FALSE;
		} else {
			return TRUE;
		}
	}
/**
 * SUPEROWNER MAKE OWNER TO MEMBER
 */
	public function superowner_make_owner_post() {
		$this->_response["service_name"] = "users/superowner_make_owner";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('organisation_id', 'organisation id', 'trim|required|callback__check_superowner');
		$this->form_validation->set_rules('user_id', 'user id', 'trim|required|callback__check_user');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$organisation_guid = safe_array_key($this->_data, "organisation_id", "");
			$row1 = $this->app->get_row('organisations', 'organisation_id, name', ['organisation_guid' => $organisation_guid]);
			$organisation_id = safe_array_key($row1, "organisation_id", "");
			$organisation_name = safe_array_key($row1, "name", "");

			$user_guid = safe_array_key($this->_data, "user_id", "");
			$row2 = $this->app->get_row('users', 'user_id', ['user_guid' => $user_guid]);
			$member_user_id = safe_array_key($row2, "user_id", "");

			$this->users_model->make_as_owner($member_user_id, $organisation_id);
			$this->send_to_owner($user_id, $member_user_id, $organisation_name);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}
/**
 * EMAIL SEND TO MEMBER WHO BECOMES OWNER
 */
	public function send_to_owner($user_id, $member_user_id, $organisation_name) {
		$this->load->helper('email');
		$superowner_data = $this->app->get_row('users', 'first_name, last_name', ['user_id' => $user_id]);
		$member_data = $this->app->get_row('users', 'email, first_name, last_name', ['user_id' => $member_user_id]);
		$email_template = "emailer/notify_to_owner";
		$subject = 'You have been make owner';
		$superowner = $superowner_data['first_name'] . ' ' . $superowner_data['last_name'];
		$member = $member_data['first_name'] . ' ' . $member_data['last_name'];
		$email_data = array("member" => $member, "superowner" => $superowner, 'organisation' => $organisation_name);
		$email = $member_data['email'];

		$message = $this->load->view($email_template, $email_data, TRUE);
		$this->load->library('email');
		$this->email->from(SUPPORT_EMAIL, FROM_NAME);
		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
	}
/**
 * VALIDATON FOR SUPEROWNER
 */
	public function _check_superowner($str) {
		$user_id = $this->rest->user_id;
		$organisation_guid = safe_array_key($this->_data, "organisation_id", "");
		$row1 = $this->app->get_row('organisations', 'organisation_id', [
			'organisation_guid' => $organisation_guid,
			'status' => 'ACTIVE',
		]);

		if (empty($row1)) {
			$this->form_validation->set_message('_check_superowner', 'Please enter valid organisation.');
			return FALSE;
		}
		$organisation_id = safe_array_key($row1, "organisation_id", "");
		$row2 = $this->app->get_row('organisation_members', 'email', [
			'user_id' => $user_id,
			'organisation_id' => $organisation_id,
			'role' => 'SUPEROWNER',
		]);

		if (empty($row2)) {
			$this->form_validation->set_message('_check_superowner', 'You are not a superowner of this organisation');
			return FALSE;
		}

		return TRUE;
	}
/**
 * VALIDATION FOR VALID USER ID
 */
	public function _check_user($str) {
		$user_guid = safe_array_key($this->_data, "user_id", "");

		$organisation_guid = safe_array_key($this->_data, "organisation_id", "");
		$row1 = $this->app->get_row('organisations', 'organisation_id', ['organisation_guid' => $organisation_guid]);

		$organisation_id = safe_array_key($row1, "organisation_id", "");

		$row1 = $this->app->get_row('users', 'user_id', ['user_guid' => $user_guid]);
		$member_user_id = safe_array_key($row1, "user_id", "");
		$row2 = $this->app->get_row('organisation_members', 'email', [
			'user_id' => $member_user_id,
			'organisation_id' => $organisation_id,
		]);

		if (empty($row2)) {
			$this->form_validation->set_message('_check_user', 'This user is not a member of this organisation');
			return FALSE;
		}

		return TRUE;
	}
/**
 * SUPEROWNER AND OWNER CAN VERIFY USER ASSOCIATION
 */
	public function verify_user_association_post() {
		$this->_response["service_name"] = "users/verify_user_association";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('organisation_id', 'organisation id', 'trim|required|callback__check_permission_to_action');
		$this->form_validation->set_rules('user_id', 'user id', 'trim|required|callback__check_user');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$organisation_guid = safe_array_key($this->_data, "organisation_id", "");
			$row1 = $this->app->get_row('organisations', 'organisation_id, name', ['organisation_guid' => $organisation_guid]);
			$organisation_id = safe_array_key($row1, "organisation_id", "");
			$organisation_name = safe_array_key($row1, "name", "");

			$user_guid = safe_array_key($this->_data, "user_id", "");
			$row2 = $this->app->get_row('users', 'user_id', ['user_guid' => $user_guid]);
			$member_user_id = safe_array_key($row2, "user_id", "");

			$this->users_model->verify_association($member_user_id, $organisation_id);
			$this->send_to_member($user_id, $member_user_id, $organisation_name);
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}
/**
 * EMAIL SEND TO MEMBER
 */
	public function send_to_member($user_id, $member_user_id, $organisation_name) {
		$this->load->helper('email');
		$superowner_data = $this->app->get_row('users', 'first_name, last_name', ['user_id' => $user_id]);
		$member_data = $this->app->get_row('users', 'email, first_name, last_name', ['user_id' => $member_user_id]);
		$email_template = "emailer/associatin_verified";
		$subject = 'Your association has been verified';
		$superowner = $superowner_data['first_name'] . ' ' . $superowner_data['last_name'];
		$member = $member_data['first_name'] . ' ' . $member_data['last_name'];
		$email_data = array("member" => $member, "superowner" => $superowner, 'organisation' => $organisation_name);
		$email = $member_data['email'];

		$message = $this->load->view($email_template, $email_data, TRUE);
		$this->load->library('email');
		$this->email->from(SUPPORT_EMAIL, FROM_NAME);
		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
	}
/**
 * VERIFICATION ACTION CHECK PERMISSION OF USER
 */
	public function _check_permission_to_action($str) {
		$user_id = $this->rest->user_id;
		$organisation_guid = safe_array_key($this->_data, "organisation_id", "");
		$row1 = $this->app->get_row('organisations', 'organisation_id', [
			'organisation_guid' => $organisation_guid,
			'status' => 'ACTIVE',
		]);

		if (empty($row1)) {
			$this->form_validation->set_message('_check_permission_to_action', 'Please enter valid organisation.');
			return FALSE;
		}
		$organisation_id = safe_array_key($row1, "organisation_id", "");
		$row2 = $this->app->get_row('organisation_members', 'email', [
			'user_id' => $user_id,
			'organisation_id' => $organisation_id,
			'role !=' => 'MEMBER',
		]);

		if (empty($row2)) {
			$this->form_validation->set_message('_check_permission_to_action', 'You are not a superowner or owner of this organisation');
			return FALSE;
		}

		return TRUE;
	}

/**
 * DELETE USER VOLUNTEERING
 */
	public function delete_volunteering_post_old() {
		$this->_response["service_name"] = "users/delete_volunteering";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('volunteering_id', 'volunteering id', 'trim|required|callback__should_exist_volunteering');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$volunteering_guid = safe_array_key($this->_data, "volunteering_id", "");
			$row = $this->app->get_row('user_volunteerings', 'volunteering_id', ['volunteering_guid' => $volunteering_guid]);
			$volunteering_id = safe_array_key($row, "volunteering_id", "");
			$this->users_model->delete_volunteering($volunteering_guid);
			$this->users_model->delete_volunteering_responsibilities($user_id, $volunteering_id);
			$this->_response["message"] = "Deleted successfully.";
			$this->set_response($this->_response);
		}
	}
/**
 * CHECK VOLUNTEERING ID EXIST OR NOT
 */
	public function _should_exist_volunteering($volunteering_guid) {
		$where = [
			"volunteering_guid" => $volunteering_guid,
		];
		$rows = $this->app->get_rows('user_volunteerings', 'volunteering_id', $where);
		if (count($rows) < 1) {
			$this->form_validation->set_message('_should_exist_volunteering', '{field} is not exists');
			return FALSE;
		} else {
			return TRUE;
		}
	}

/**
 * Edit USER VOLUNTEERING
 */
	public function edit_volunteering_post() {
		$this->_response["service_name"] = "users/edit_volunteering";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('volunteering_id', 'volunteering_id', 'trim|required|callback__should_exist_volunteering');
		$this->form_validation->set_rules('organisation_name', 'organisation id', 'trim|required|min_length[3]|max_length[100]|callback__check_alpha_space');
		$this->form_validation->set_rules('position', 'position', 'trim|required');
		$this->form_validation->set_rules('location', 'location', 'trim|callback__check_location');
		$this->form_validation->set_rules('currently_working', 'in progress', 'trim|required|in_list[YES,NO]');
		$this->form_validation->set_rules('start_date', 'start date', 'trim|required');

		$currently_working = safe_array_key($this->_data, "currently_working", "NO");
		if ($currently_working == 'NO') {
			$this->form_validation->set_rules('end_date', 'end date', 'trim|required');
		}

		$this->form_validation->set_rules('responsibilities', 'Responsibilities', 'trim|callback__check_responsibilities');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {

			$volunteering_guid = safe_array_key($this->_data, "volunteering_id", "");
			$organisation_name = safe_array_key($this->_data, "organisation_name", "");

			$designation = safe_array_key($this->_data, "position", "");
			$start_date = safe_array_key($this->_data, "start_date", "");
			$end_date = safe_array_key($this->_data, "end_date", "");
			$responsibilities = safe_array_key($this->_data, "responsibilities", []);

			$row3 = $this->app->get_row('user_volunteerings', 'volunteering_id', [
				'user_id' => $user_id, 'volunteering_guid' => $volunteering_guid,
			]);
			$volunteering_id = safe_array_key($row3, "volunteering_id", "");
			$location = safe_array_key($this->_data, "location", "");
			$country = strtolower(safe_array_key($location, "country", ""));
			$state = strtolower(safe_array_key($location, "state", ""));
			$city = strtolower(safe_array_key($location, "city", ""));
			$address_1 = strtolower(safe_array_key($location, "address_1", ""));
			$address_2 = strtolower(safe_array_key($location, "address_2", ""));
			$this->users_model->update_volunteering($user_id, $volunteering_guid, $organisation_name, $designation, $currently_working, $start_date, $end_date, $country, $state, $city, $address_1, $address_2);
			$this->users_model->add_volunteering_responsibilities($user_id, $responsibilities, $volunteering_id);
			$this->_response['message'] = "Updated successfully.";
			$this->set_response($this->_response);
		}
	}

	/**
	 * CHECK DESIGNATION ID EXIST OR NOT
	 */
	public function _should_exist_designation($master_guid) {
		$where = [
			"master_guid" => $master_guid,
		];
		$rows = $this->app->get_rows('masters', 'master_id', $where);
		if (count($rows) < 1) {
			$this->form_validation->set_message('_should_exist_designation', '{field} is not exists');
			return FALSE;
		} else {
			return TRUE;
		}
	}

/**
 * CHECK ORGANISATION ID EXIST OR NOT FOR ATHLETE
 */
	public function _should_exist_organisation_id($organisation_guid) {
		if (empty($organisation_guid)) {
			$this->form_validation->set_message('_should_exist_organisation_id', '{field} is required.');
			return FALSE;
		}
		$where = [
			"organisation_guid" => $organisation_guid,
			"status" => 'ACTIVE',
		];
		$rows = $this->app->get_rows('organisations', 'organisation_id', $where);
		if (count($rows) < 1) {
			$this->form_validation->set_message('_should_exist_organisation_id', '{field} is not exists or inactive.');
			return FALSE;
		}
		return TRUE;
	}

/**
 * CHECK ORGANISATION ID EXIST OR NOT
 */
	public function _should_exist_organisation($organisation_guid) {
		if (empty($organisation_guid)) {
			$this->form_validation->set_message('_should_exist_organisation', '{field} is required.');
			return FALSE;
		}
		$where = [
			"organisation_guid" => $organisation_guid,
			"status" => 'ACTIVE',
		];
		$rows = $this->app->get_rows('organisations', 'organisation_id', $where);
		if (count($rows) < 1) {
			$this->form_validation->set_message('_should_exist_organisation', '{field} is not exists or inactive');
			return FALSE;
		} else {
			return TRUE;
		}
	}

/**
 * CHECK ORGANISATION MEMBER ID EXIST OR NOT
 */
	public function _should_exist_organisation_member($organisation_member_guid) {
		$where = [
			"organisation_member_guid" => $organisation_member_guid,
		];
		$rows = $this->app->get_rows('organisation_members', 'organisation_member_id', $where);
		if (count($rows) < 1) {
			$this->form_validation->set_message('_should_exist_organisation_member', '{field} is not exists');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function toggle_support_post() {
		$this->_response["service_name"] = "users/toggle_support";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('organisation_id', 'organisation id', 'trim|required|callback__should_exist_organisation');
		$this->form_validation->set_rules('type', 'Type', 'trim|required|in_list[ORGANISATION,PROGRAM,EVENT]');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$organisation_guid = safe_array_key($this->_data, "organisation_id", "");
			$type = safe_array_key($this->_data, "type", "");
			$organisation_id = get_guid_detail($organisation_guid, 'organisation');

			$user_support_data = $this->app->get_row('user_supports', 'user_support_guid', [
				'user_id' => $user_id,
				'type' => $type,
				'type_id' => $organisation_id,
			]);

			$row = $this->app->get_row('organisation_members', 'user_id, email', [
				'organisation_id' => $organisation_id,
				'role' => 'SUPEROWNER',
			]);
			$superowner_email = safe_array_key($row, 'email', '');
			$receiver_user_id = safe_array_key($row, 'user_id', '');

			if (is_array($user_support_data) == FALSE) {
				$user_support_id = $this->users_model->create_user_support($user_id, $type, $organisation_id);
				if ($superowner_email) {
					if (!empty($receiver_user_id)) {
						$this->user_support_send_email_to_superowner($superowner_email, $user_id, $receiver_user_id, $organisation_id);
					}
				}
				$this->_response["message"] = 'Support.';
			} else {
				$user_support_guid = safe_array_key($user_support_data, 'user_support_guid', '');
				$this->users_model->delete_user_support($user_support_guid);
				$this->_response["message"] = 'Unsupport.';
			}

			$this->set_response($this->_response);
		}
	}

	public function user_support_send_email_to_superowner($superowner_email, $sender_user_id, $receiver_user_id, $organisation_id) {
		// SEND NOTIFICATION
		$this->load->model("notifications_model");
		$parameters = [];
		$parameters[0]['refrence_id'] = $organisation_id;
		$parameters[0]['type'] = 'organisation';
		$this->notifications_model->save(25, $sender_user_id, [$receiver_user_id], $organisation_id, $parameters);

		// SEND PUSH NOTIFICATION
		$title = 'Tikisites';
		$body = 'You just got a new supporter. Make sure to keep them happy by sharing your content and invite them to your events.';
		// $user_guid = get_detail_by_id($user_id, 'user', 'user_guid');
		// $extra_data = ['type' => 'user', 'id' => $user_guid];
		push_notification($receiver_user_id, $title, $body);

		// //SEND EMAIL
		// $this->load->helper('email');
		// $email_template = "emailer/support_organisation";
		// $subject = 'Tikisites - A user supported your organisation';
		// $name = 'Teammate';
		// $email_data = array("name" => $name);
		// $email = $superowner_email;

		// $message = $this->load->view($email_template, $email_data, TRUE);
		// $this->load->library('email');
		// $this->email->from(SUPPORT_EMAIL, FROM_NAME);
		// $this->email->to($email);
		// $this->email->subject($subject);
		// $this->email->message($message);
		// $this->email->send();
	}

	public function toggle_follow_post() {
		$this->_response["service_name"] = "users/toggle_follow";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('organisation_id', 'organisation id', 'trim|required|callback__should_exist_organisation');
		$this->form_validation->set_rules('type', 'Type', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {

			$organisation_guid = safe_array_key($this->_data, "organisation_id", "");
			$type = safe_array_key($this->_data, "type", "");

			$organisation_data = $this->app->get_row('organisations', 'organisation_id', [
				'organisation_guid' => $organisation_guid,
			]);

			if (is_array($organisation_data) == TRUE) {
				$organisation_id = safe_array_key($organisation_data, 'organisation_id', '');
				$user_following_data = $this->app->get_row('user_followings', 'user_following_guid', [
					'user_id' => $user_id,
					'type' => $type,
					'type_id' => $organisation_id,
				]);

				$row = $this->app->get_row('organisation_members', 'email', [
					'organisation_id' => $organisation_id,
					'role' => 'SUPEROWNER',
				]);
				$superowner_email = safe_array_key($row, 'email', '');

				if (is_array($user_following_data) == FALSE) {
					$user_following_id = $this->users_model->create_user_following($user_id, $type, $organisation_id);
					if ($superowner_email) {
						$this->user_follow_send_email_to_superowner($superowner_email);
					}
					$this->_response["message"] = 'Followed.';
				} else {
					$user_following_guid = safe_array_key($user_following_data, 'user_following_guid', '');
					$this->users_model->delete_user_following($user_following_guid);
					$this->_response["message"] = 'Unfollowed.';
				}

				$this->set_response($this->_response);
			}
		}
	}

	public function user_follow_send_email_to_superowner($superowner_email) {
		$this->load->helper('email');
		$email_template = "emailer/follow_organisation";
		$subject = 'Tikisites - A user followed your organisation';
		$name = 'Teammate';
		$email_data = array("name" => $name);
		$email = $superowner_email;

		$message = $this->load->view($email_template, $email_data, TRUE);
		$this->load->library('email');
		$this->email->from(SUPPORT_EMAIL, FROM_NAME);
		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
	}

/**
 * PROGRAM FOLLOWERS
 */
	public function program_interest_post() {
		$this->_response["service_name"] = "users/program_intrest";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('program_id', 'program id', 'trim|required|callback__should_exist_program_id');
		$this->form_validation->set_rules('type', 'Type', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$program_guid = safe_array_key($this->_data, "program_id", "");
			$type = safe_array_key($this->_data, "type", "");
			$data = $this->app->get_row('organisation_programs', 'program_id', [
				'program_guid' => $program_guid,
			]);

			if (is_array($data) == TRUE) {
				$program_id = safe_array_key($data, 'program_id', '');
				$user_following_data = $this->app->get_row('user_followings', 'user_following_guid', [
					'user_id' => $user_id,
					'type' => $type,
					'type_id' => $program_id,
				]);

				if (is_array($user_following_data) == FALSE) {
					$user_following_id = $this->users_model->create_user_following($user_id, $type, $program_id);
					$this->_response["message"] = 'Intrested.';

				} else {
					$user_following_guid = safe_array_key($user_following_data, 'user_following_guid', '');
					$this->users_model->delete_user_following($user_following_guid);
					$this->_response["message"] = 'Unfollowed.';
				}

				$this->set_response($this->_response);
			}
		}
	}

/**
 * CHECK PROGRAM ID EXIST OR NOT
 */
	public function _should_exist_program_id($program_guid) {
		if (empty($program_guid)) {
			$this->form_validation->set_message('_should_exist_program_id', '{field} is required.');
			return FALSE;
		}

		$organisation_id = get_guid_detail($program_guid, 'program', 'organisation_id');
		$where = [
			"organisation_id" => $organisation_id,
			"status" => 'ACTIVE',
		];
		$row = $this->app->get_row('organisations', 'organisation_id', $where);

		if (empty($row)) {
			$this->form_validation->set_message('_should_exist_program_id', 'Organisation is inactive.');
			return FALSE;
		}

		$row = $this->app->get_row('organisation_programs', 'program_id', ["program_guid" => $program_guid]);
		if (count($row) < 1) {
			$this->form_validation->set_message('_should_exist_program_id', '{field} is not exists');
			return FALSE;
		}
		return TRUE;
	}

/**
 * EVENT FOLLOWERS
 */
	public function event_interest_post() {
		$this->_response["service_name"] = "users/event_intrest";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('event_id', 'event id', 'trim|required|callback__should_exist_event_id');
		$this->form_validation->set_rules('type', 'Type', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$event_guid = safe_array_key($this->_data, "event_id", "");
			$type = safe_array_key($this->_data, "type", "");
			$data = $this->app->get_row('organisation_events', 'event_id', [
				'event_guid' => $event_guid,
			]);

			if (is_array($data) == TRUE) {
				$event_id = safe_array_key($data, 'event_id', '');
				$user_following_data = $this->app->get_row('user_followings', 'user_following_guid', [
					'user_id' => $user_id,
					'type' => $type,
					'type_id' => $event_id,
				]);

				if (is_array($user_following_data) == FALSE) {
					$user_following_id = $this->users_model->create_user_following($user_id, $type, $event_id);
					$this->_response["message"] = 'Intrested.';

				} else {
					$user_following_guid = safe_array_key($user_following_data, 'user_following_guid', '');
					$this->users_model->delete_user_following($user_following_guid);
					$this->_response["message"] = 'Unfollowed.';
				}

				$this->set_response($this->_response);
			}
		}
	}

/**
 * CHECK EVENT ID EXIST OR NOT
 */
	public function _should_exist_event_id($event_guid) {
		if (empty($event_guid)) {
			$this->form_validation->set_message('_should_exist_event_id', '{field} is required.');
			return FALSE;
		}
		$organisation_id = get_guid_detail($event_guid, 'event', 'organisation_id');
		$where = [
			"organisation_id" => $organisation_id,
			"status" => 'ACTIVE',
		];
		$row = $this->app->get_row('organisations', 'organisation_id', $where);

		if (empty($row)) {
			$this->form_validation->set_message('_should_exist_event_id', 'Organisation is inactive.');
			return FALSE;
		}
		$where = [
			"event_guid" => $event_guid,
		];
		$row = $this->app->get_row('organisation_events', 'event_id', $where);
		if (count($row) < 1) {
			$this->form_validation->set_message('_should_exist_event_id', '{field} is not exists');
			return FALSE;
		}
		return TRUE;
	}
/**
 * REPRESENT AS AN ATHLETE
 */
	public function represent_as_an_athlete_post() {
		$this->_response["service_name"] = "users/represent_as_an_athlete";
		$user_id = $this->rest->user_id;
		$this->form_validation->set_rules('represent_as_an_athlete', 'represent as an athlete', 'trim|required|in_list[YES,NO]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$represent_as_an_athlete = safe_array_key($this->_data, "represent_as_an_athlete", "NO");
			$this->users_model->update_user_represent_as_an_athlete($user_id, $represent_as_an_athlete);
			// if ($represent_as_an_athlete == 'NO') {
			// 	$this->users_model->delete_athlete($user_id);
			// }
			$this->_response['message'] = "Success";
			$this->set_response($this->_response);
		}
	}

/**
 * UPDATE DEVICE TOKEN
 */
	public function update_device_token_post() {
		$this->_response["service_name"] = "users/update_device_token";
		$this->form_validation->set_rules('device_type', 'device type', 'trim|required|in_list[' . implode($this->app->device_types, ",") . ']');
		$this->form_validation->set_rules('device_token', 'device token', 'trim|required');
		$session_key = $this->rest->key;
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			// $session_key = $this->input->server('HTTP_SESSION_KEY');
			$device_type = safe_array_key($this->_data, "device_type", "web_browser");
			$device_type_id = array_search($device_type, $this->app->device_types);
			$device_token = safe_array_key($this->_data, "device_token", "");
			$this->users_model->update_device_token($session_key, $device_type_id, $device_token);
			$this->_response["data"] = [];
			$this->_response["message"] = "Device Token Updated Successfully.";
			$this->set_response($this->_response);
		}
	}

	public function check_push_notification_post() {
		$this->_response["service_name"] = "users/check_push_notification";
		$this->form_validation->set_rules('user_id', 'user id', 'trim|callback__check_user_id_exist');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_guid = safe_array_key($this->_data, "user_id", "");
			$user_id = get_guid_detail($user_guid, 'user');
			$data = push_notification($user_id, 'message for body', 'message for title');
			$this->_response["data"] = json_decode($data);
			$this->_response["message"] = "Success.";
			$this->set_response($this->_response);
		}
	}

	public function delete_account_post() {
		$this->_response["service_name"] = "users/delete_account";
		$this->form_validation->set_rules('password', 'User Id', 'trim|required|callback__check_password');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$email = get_detail_by_id($user_id, 'user', 'email');
			// 	$this->notify_user($email);
			$this->users_model->account_delete($user_id);
			$this->_response['message'] = "Account deleted successfully!";
			$this->set_response($this->_response);
		}
	}

	public function _check_password($password) {
		$user_id = $this->rest->user_id;
		if (empty($password)) {
			$this->form_validation->set_message('_check_password', '{field} is required.');
			return FALSE;
		}
		$exist_password = get_detail_by_id($user_id, 'user', 'password');

		if ($exist_password !== md5($password)) {
			$this->form_validation->set_message('_check_password', 'Please enter valid password.');
			return FALSE;
		}

		return TRUE;
	}

	public function get_invoce_list_post() {
		$this->_response["service_name"] = "users/get_invoce_list";
		$this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_guid = safe_array_key($this->_data, "user_id", "");
			$user = $this->app->get_row('users', 'user_id', ['user_guid' => $user_guid]);
			$user_id = safe_array_key($user, "user_id", "");
			$subscription = $this->app->get_row('subscriptions', 'stripe_subscription_id, stripe_pricing_plan_id, pricing_plan_id', ['user_id' => $user_id]);
			$stripe_subscription_id = safe_array_key($subscription, "stripe_subscription_id", "");
			$invoices_list = $this->users_model->get_invoices($stripe_subscription_id);
			if ($invoices_list['status'] == "success") {
				$this->_response["data"] = $invoices_list['invoices'];
				$this->_response['message'] = "Subscription Details!";
				$this->set_response($this->_response);
			} else {
				$this->_response["data"] = [];
				$this->_response['message'] = "Subscription Details!";
				$this->set_response($this->_response);
			}
		}
	}

	public function get_subscription_details_post() {
		$this->_response["service_name"] = "users/get_subscription_details";
		$this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_guid = safe_array_key($this->_data, "user_id", "");
			$user = $this->app->get_row('users', 'user_id', ['user_guid' => $user_guid]);
			$user_id = safe_array_key($user, "user_id", "");
			$subscription = $this->app->get_row('subscriptions', 'stripe_subscription_id, stripe_pricing_plan_id, pricing_plan_id', ['user_id' => $user_id]);
			$stripe_subscription_id = safe_array_key($subscription, "stripe_subscription_id", "");
			$subscription_details = $this->users_model->get_subscription_details($stripe_subscription_id);
			if ($subscription_details['status'] == "success") {
				$upcoming_invoice_details = $this->users_model->get_upcoming_invoice($stripe_subscription_id);
				if ($upcoming_invoice_details['status'] == "success"){
					$upcoming_invoice = $upcoming_invoice_details['upcoming_invoice'];
				}else{
					$upcoming_invoice = (object) [];
				}
				$data = [
					"subscription" => $subscription_details['subscription'],
					"upcoming_invoice" => $upcoming_invoice
				];
				$this->_response["data"] = $data;
				$this->_response['message'] = "Subscription Details!";
				$this->set_response($this->_response);
			} else {
				$this->_response["message"] = 'No data Found';
				$this->_response["errors"] = [];
				$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
			}
		}
	}

	public function _check_plan_id_exist($str) {
		if ($str != "") {
			$pricing_plan = $this->app->get_rows('pricing_plans', 'pricing_plan_id', ['stripe_pricing_plan_id' => $str]);
			if (empty($pricing_plan)) {
				$this->form_validation->set_message('_check_plan_id_exist', 'Please provide correct plan id');
				return FALSE;
			}
		}
		return TRUE;
	}

	public function update_plan_post() {
		$this->_response["service_name"] = "users/update_plan";
		$this->form_validation->set_rules('user_id', 'user id', 'trim|required|callback__check_user_id_exist');
		$this->form_validation->set_rules('plan_id', 'Plan id', 'trim|required|callback__check_plan_id_exist');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$this->load->model("campaign_model");
			$plan_id = safe_array_key($this->_data, "plan_id", "");
			$user_guid = safe_array_key($this->_data, "user_id", "");
			$user_data = $this->app->get_row('users', 'user_id', ['user_guid' => $user_guid]);
			$pricing_plan = $this->app->get_row('pricing_plans', 'pricing_plan_id, subscription_count', ['stripe_pricing_plan_id' => $plan_id]);
			$pricing_plan_id = safe_array_key($pricing_plan, 'pricing_plan_id', '');

			$subscription_count = safe_array_key($pricing_plan, "subscription_count", 0);
			$subscription_count = (int)$subscription_count + 1;

			$user_id = safe_array_key($user_data, 'user_id', $this->rest->user_id);
			$subscription = $this->app->get_row('subscriptions', 'stripe_subscription_id, stripe_pricing_plan_id, pricing_plan_id', ['user_id' => $user_id]);
			$old_pricing_plan_id = safe_array_key($subscription, "pricing_plan_id", "");
			
			$old_pricing_plan = $this->app->get_row('pricing_plans', 'pricing_plan_id, subscription_count', ['pricing_plan_id' => $old_pricing_plan_id]);
			$old_subscription_count = safe_array_key($old_pricing_plan, "subscription_count", 0);
			if($old_subscription_count > 0){
				$old_subscription_count = (int)$old_subscription_count - 1;
			}
			if ($old_pricing_plan_id !== $pricing_plan_id) {
				$stripe_subscription_id = safe_array_key($subscription, "stripe_subscription_id", "");
				$subscription_details = $this->users_model->update_plan($stripe_subscription_id, $plan_id);
				if ($subscription_details['status'] == "success") {
					$this->users_model->update_user_plan($user_id, $pricing_plan_id);
					$this->campaign_model->update_user_campaigns($user_id, $pricing_plan_id);
					$this->users_model->update_subscription_count($pricing_plan_id, $subscription_count);
					$this->users_model->update_subscription_count($old_pricing_plan_id, $old_subscription_count);
					$this->_response['message'] = "Update Successfully!";
					$this->set_response($this->_response);
				} else {
					$this->_response["message"] = 'Something Went Wrong';
					$this->_response["errors"] = [];
					$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
				}
			} else {
				$this->_response["message"] = 'Same Plan Already Activated';
				$this->_response["errors"] = [];
				$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
			}
		}
	}

	public function send_help_email_post() {
		$this->_response["service_name"] = "users/send_help_email";
		$this->form_validation->set_rules('business_name', 'Company Name ', 'trim|required');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$this->load->model("users_model");

			$device_type = safe_array_key($this->_data, "device_type", "web_browser");
			$device_type_id = array_search($device_type, $this->app->device_types);
			$business_name = safe_array_key($this->_data, "business_name", "");
			$name = safe_array_key($this->_data, "name", "");
			$email = safe_array_key($this->_data, "email", "");
			$phone = safe_array_key($this->_data, "phone", "");
			$message = safe_array_key($this->_data, "message", "");

			$this->send_help_email($business_name, $name, $email, $phone, $message, $device_type_id);
			$this->_response["success"] = true;
			$this->_response["message"] = 'Message sent successfully.';
			$this->set_response($this->_response);
		}
	}

	public function send_help_email($business_name, $name, $email, $phone, $contact_message, $device_type_id) {

		// SEND EMAIL
		$this->load->helper('email');
		$email_template = "emailer/web_help";
		$subject = 'Query From '. $name;
		$email_data = array("business_name" => $business_name, "name" => $name, "email" => $email, "phone" => $phone, "contact_message" => $contact_message,);
		$message = $this->load->view($email_template, $email_data, TRUE);
		$this->load->library('email');
		$this->email->from(NOREPLY_EMAIL, $name);
		$this->email->to(SUPPORT_EMAIL);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();

	}
}