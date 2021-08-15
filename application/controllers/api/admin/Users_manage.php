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
class Users_manage extends REST_Controller {

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
		$this->load->model("admin_model/users_manage_model", "users_manage_model");
		$this->form_validation->set_data($this->_data);
	}

	public function users_list_post() {
		$this->_response["service_name"] = "admin/users_manage/users_list";
		$this->form_validation->set_rules('keyword', 'keyword', 'trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			// $user_type = get_detail_by_id($user_id, 'user', 'user_type');
			$keyword = safe_array_key($this->_data, "keyword", "");
			$pagination = safe_array_key($this->_data, "pagination", []);
			$limit = safe_array_key($pagination, "limit", 10);
			$offset = safe_array_key($pagination, "offset", 0);
			$sort_by = safe_array_key($this->_data, "sort_by", []);
			$column_name = safe_array_key($sort_by, "column_name", 'first_name');
			$order_by = safe_array_key($sort_by, "order_by", 'acs');
			$filters = safe_array_key($this->_data, "filters", []);
			$status = safe_array_key($filters, "status", '');
			$package_guid = safe_array_key($filters, "package", '');
			$pricing_plan_id = get_detail_by_guid($package_guid, 'plan');
			$last_login_at = safe_array_key($this->_data, "last_login_at", []);
			$start_date = safe_array_key($last_login_at, "start", '');
			$end_date = safe_array_key($last_login_at, "end", '');
			$this->_response["data"] = $this->users_manage_model->users_list($user_id, $keyword, $limit, $offset, $column_name, $order_by, $status, $pricing_plan_id, $start_date, $end_date);
			$this->_response["counts"] = $this->users_manage_model->users_list($user_id, $keyword, 0, 0, $column_name, $order_by, $status, $pricing_plan_id, $start_date, $end_date);
			$this->set_response($this->_response);
		}
	}

	public function invited_users_list_post() {
		$this->_response["service_name"] = "admin/users_manage/invited_users_list";
		$this->form_validation->set_rules('agency_id', 'agency id', 'trim|required');
		$this->form_validation->set_rules('keyword', 'keyword', 'trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$agency_user_guid = safe_array_key($this->_data, "agency_id", "");
			$agency_user_id = get_detail_by_guid($agency_user_guid, 'user');
			$organization = $this->app->get_row('organizations', 'organization_id', ['user_id' => $agency_user_id]);
			$organization_id = safe_array_key($organization, "organization_id", "");
			$keyword = safe_array_key($this->_data, "keyword", "");
			$pagination = safe_array_key($this->_data, "pagination", []);
			$limit = safe_array_key($pagination, "limit", 10);
			$offset = safe_array_key($pagination, "offset", 0);
			$sort_by = safe_array_key($this->_data, "sort_by", []);
			$column_name = safe_array_key($sort_by, "column_name", 'first_name');
			$order_by = safe_array_key($sort_by, "order_by", 'acs');
			$this->_response["data"] = $this->users_manage_model->invited_users_list($organization_id, $agency_user_id, $keyword, $limit, $offset, $column_name, $order_by);
			$this->_response["counts"] = $this->users_manage_model->invited_users_list($organization_id, $agency_user_id, $keyword, 0, 0, $column_name, $order_by);
			$this->set_response($this->_response);
		}
	}

	public function invite_users_post() {
		$this->_response["service_name"] = "admin/users_manage/invite_users";
		$this->form_validation->set_rules('agency_id', 'agency id', 'trim|required');
		$this->form_validation->set_rules('user_email', 'user email', 'trim|required|valid_email');
		$this->form_validation->set_rules('plan_id', 'agency id', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$agency_user_guid = safe_array_key($this->_data, "agency_id", "");
			$plan_guid = safe_array_key($this->_data, "plan_id", "");
			$user_email = safe_array_key($this->_data, "user_email", "");
			$agency_user_id = get_detail_by_guid($agency_user_guid, 'user');

			$organization = $this->app->get_row('organizations', 'organization_id, organization_guid, organization_status', ['user_id' => $agency_user_id]);
			$organization_id = safe_array_key($organization, "organization_id", "");
			$organization_guid = safe_array_key($organization, "organization_guid", "");
			$organization_status = safe_array_key($organization, "organization_status", "");

			$plan_guid = safe_array_key($this->_data, "plan_id", "");
			$pricing_plan = $this->app->get_row('pricing_plans', 'pricing_plan_id, stripe_pricing_plan_id', ['pricing_plan_guid' => $plan_guid]);
			$stripe_pricing_plan_id = safe_array_key($pricing_plan, "stripe_pricing_plan_id", "");
			$pricing_plan_id = safe_array_key($pricing_plan, "pricing_plan_id", "");

			// PLAN_ID update organization table

			// ENUM = AGENCY

			// 		$this->db->select('u.user_id, u.user_guid, u.email');
			// 		$this->db->select('om.role');
			// 		$this->db->select('o.organization_status');
			// 		$this->db->from('organization_members AS om');
			// 		$this->db->join('organizations AS o', 'o.organization_id = om.organization_id', 'LEFT');
			// 		$this->db->join('users AS u', 'u.user_id = om.user_id', 'LEFT');
			// 		$this->db->where('om.user_id', $user_id);

			// 		if(AGENCY and  ROLE == USER){
			// 			AGENCY-OWNER
			// 		}
			$this->load->model("users_model");
			$organization_member_id = $this->users_model->create_organization_member(null, $organization_id, $pricing_plan_id, $user_email, 'INVITED', 'USER');

			$organization_member = $this->app->get_row('organization_members', 'organization_member_guid', ['organization_member_id' => $organization_member_id]);

			$organization_member_guid = safe_array_key($organization_member, 'organization_member_guid');

			$this->users_manage_model->send_invitation_to_user($organization_member_guid, $agency_user_guid, $plan_guid, $user_email);
			$this->set_response($this->_response);
		}
	}

	public function change_status_post() {
		$this->_response["service_name"] = "admin/users_manage/change_status";
		$this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
		$this->form_validation->set_rules("status", "Status", "trim|required|in_list[ACTIVE,PENDING,BLOCKED]");
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_guid = safe_array_key($this->_data, "user_id", "");
			$status = safe_array_key($this->_data, "status", "");
			$this->users_manage_model->change_status($user_guid, $status);
			$this->_response['message'] = "Updated successfully";
			$this->set_response($this->_response);
		}
	}

	public function get_user_individual_post() {
		$this->_response["service_name"] = "admin/users_manage/get_user_individual";
		// if ($this->form_validation->run() == FALSE) {
		// 	$errors = $this->form_validation->error_array();
		// 	$this->_response["message"] = current($errors);
		// 	$this->_response["errors"] = $errors;
		// 	$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		// } else {
			$this->_response["data"] = $this->users_manage_model->get_user_individual();
			$this->_response['message'] = "List";
			$this->set_response($this->_response);
		// }
	}

	public function reset_password_link_post() {
		$this->_response["service_name"] = "admin/users_manage/reset_password_link";
		$this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_guid = safe_array_key($this->_data, "user_id", "");
			$user_id = get_detail_by_guid($user_guid, 'user');
			$email = get_detail_by_guid($user_guid, 'user', 'email');
			// SEND PUSH NOTIFICATION
			$title = 'Tikisites';
			$body = 'Seems like you forgot your password...we got your back.';
			// push_notification($user_id, $title, $body);
			$this->users_manage_model->send_reset_password_link($user_id);
			$this->_response["message"] = "Sent Successfully.";
			$this->set_response($this->_response);
		}
	}

	public function user_detail_by_id_post() {
		$this->_response["service_name"] = "admin/users_manage/user_detail_by_id";
		$this->form_validation->set_rules('user_id', 'user id', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_guid = safe_array_key($this->_data, "user_id", "");
			// $user_id = get_detail_by_guid($user_guid, 'user');
			$this->_response["data"] = $this->users_manage_model->user_detail_by_id($user_guid);
			$this->_response["message"] = "Success.";
			$this->set_response($this->_response);
		}
	}

	public function update_user_post() {
		$this->_response["service_name"] = "admin/users_manage/update_user";
		$this->form_validation->set_rules('user_id', 'user id', 'trim|required');
		$this->form_validation->set_rules('name', 'name', 'trim|required');
		$this->form_validation->set_rules('business_name', 'business name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_guid = safe_array_key($this->_data, "user_id", "");
			$name = safe_array_key($this->_data, "name", "");
			$business_name = safe_array_key($this->_data, "business_name", "");
			$email = safe_array_key($this->_data, "email", "");
			$userid = get_detail_by_guid($user_guid, 'user');
			$this->users_manage_model->update_organization_user_email($userid, $email);
			$this->_response["data"] = $this->users_manage_model->update_user($user_guid, $name, $business_name, $email);
			$this->_response["message"] = "Success.";
			$this->set_response($this->_response);
		}
	}

	public function invite_member_post() {
		$this->_response["service_name"] = "admin/users_manage/invite_member";
		$this->form_validation->set_rules('agency_id', 'agency id', 'trim|required');
		$this->form_validation->set_rules('user_email', 'user email', 'trim|required|valid_email');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$agency_user_guid = safe_array_key($this->_data, "agency_id", "");
			$user_email = safe_array_key($this->_data, "user_email", "");
			$agency_user_id = get_detail_by_guid($agency_user_guid, 'user');
			$added_member = $this->app->get_row('organization_members', 'organization_id', ['user_id' => $agency_user_id]);
			$organization_id = safe_array_key($added_member, "organization_id", "");
			$this->load->model("users_model");

			$exist_member = $this->app->get_row('organization_members', 'organization_member_id', ['email' => $user_email, 'added_by' => $agency_user_id]);
			$organization_member_old_id = safe_array_key($exist_member, "organization_member_id", "");
			if ($organization_member_old_id) {
				$organization_member_id = $organization_member_old_id;
			} else {
				$organization_member_id = $this->users_model->create_organization_member(null, $organization_id, NULL, $user_email, 'INVITED', 'TEAM', $agency_user_id);
			}
			
			$organization_member = $this->app->get_row('organization_members', 'organization_member_guid', ['organization_member_id' => $organization_member_id]);

			$organization_member_guid = safe_array_key($organization_member, 'organization_member_guid');

			$this->users_manage_model->send_invitation_to_member($organization_member_guid, $agency_user_id, $user_email);
			$this->set_response($this->_response);
		}
	}

	public function _check_organization_member_id_exist($str) {
		if ($str != "") {
			$users_data = $this->app->get_rows('organization_members', 'organization_member_id', ['organization_member_guid' => $str]);
			if (empty($users_data)) {
				$this->form_validation->set_message('_check_organization_member_id_exist', 'Please provide correct id');
				return FALSE;
			}
		}
		return TRUE;
	}

	public function send_email_again_to_member_post() {
		$this->_response["service_name"] = "admin/users_manage/send_email_again_to_member";
		$this->form_validation->set_rules('organization_member_id', 'organization member id', 'trim|required|callback__check_organization_member_id_exist');
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$organization_member_guid = safe_array_key($this->_data, "organization_member_id", "");
			$email = safe_array_key($this->_data, "email", "");
			$organization_member = $this->app->get_row('organization_members', 'organization_member_id, email , added_by', ['organization_member_guid' => $organization_member_guid]);
			$organization_member_id = safe_array_key($organization_member, "organization_member_id", "");
			$added_by = safe_array_key($organization_member, "added_by", "");
			$this->load->model("users_model");
			$this->users_manage_model->update_organization_member_email($organization_member_id, $email);
			$this->users_manage_model->send_invitation_to_member($organization_member_guid, $added_by, $email);
			$this->set_response($this->_response);
		}
	}

	public function team_members_list_post() {
		$this->_response["service_name"] = "admin/users_manage/team_members_list";
		$this->form_validation->set_rules('user_id', 'User id', 'trim|required');
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
			$this->_response["data"] = $this->users_manage_model->team_members_list($user_id, $keyword, $limit, $offset, $column_name, $order_by);
			$this->_response["counts"] = $this->users_manage_model->team_members_list($user_id, $keyword, 0, 0, $column_name, $order_by);
			$this->set_response($this->_response);
		}
	}

	public function get_user_list_post() {
		$this->_response["service_name"] = "admin/users_manage/get_user_list";
		$this->form_validation->set_rules('type', 'Type', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$type = safe_array_key($this->_data, "type", NULL);
			$this->_response["data"] = $this->users_manage_model->get_user_list($type);
			$this->_response['message'] = "List";
			$this->set_response($this->_response);
		}
	}

	public function get_agency_billing_contacts_post() {
		$this->_response["service_name"] = "admin/users_manage/get_agency_billing_contacts";
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
			$this->_response["data"] = $this->users_manage_model->get_agency_billing_contacts($keyword, $limit, $offset, $column_name, $order_by);
			$this->_response["counts"] = $this->users_manage_model->get_agency_billing_contacts($keyword, 0, 0, $column_name, $order_by);
			$this->set_response($this->_response);
		}
	}
}

?>