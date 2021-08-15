<?php

class Users_manage_model extends CI_model {

	public function __construct() {
		parent::__construct();
	}

	// public function users_list($user_id, $keyword = '', $limit = 0, $offset = 0) {
	// 	if ($limit > 0 && $offset >= 0) {
	// 		$this->db->limit($limit, $offset);
	// 		$this->db->select('IFNULL(u.user_guid,"") AS user_guid', FALSE);
	// 		$this->db->select('CONCAT(u.business_name, " ",u.last_name) AS name, u.user_id');
	// 		$this->db->select('IFNULL(u.business_name,"") AS business_name', FALSE);
	// 		$this->db->select('IFNULL(u.email,"") AS email', FALSE);
	// 		$this->db->select('IFNULL(u.customer_stripe_id,"") AS customer_stripe_id', FALSE);
	// 		$this->db->select('IFNULL(u.last_login_at,"") AS last_login_at', FALSE);
	// 		$this->db->select('IFNULL(u.created_at,"") AS created_at', FALSE);
	// 		$this->db->select('IFNULL(u.status,"") AS status', FALSE);
	// 	} else {
	// 		$this->db->select('COUNT(u.user_id) as count', FALSE);
	// 	}

	// 	$this->db->from('users AS u');
	// 	$this->db->join('media AS m', 'm.media_id = u.profile_picture_id', 'LEFT');
	// 	$this->db->where('u.user_type!=', 'ADMIN');

	// 	if (!empty($keyword)) {
	// 		$this->db->group_start();
	// 		$this->db->like('u.first_name', $keyword, 'both');
	// 		$this->db->or_like('u.last_name', $keyword, 'both');
	// 		$this->db->group_end();
	// 	}

	// 	// if ($only_count == FALSE) {
	// 	// 	// $offset = ($page_no - 1) * $page_size;
	// 	// 	// $offset = $page_no;
	// 	// 	$this->db->limit($limit, $offset);
	// 	// }

	// 	$query = $this->db->get();

	// 	// if ($only_count) {
	// 	// 	return $query->num_rows();
	// 	// }
	// 	if (($limit > 0) && ($offset >= 0)) {
	// 		$results = $query->result_array();
	// 		$list = [];
	// 		if ($query->num_rows() > 0) {
	// 			foreach ($results as $key => $value) {
	// 				$result = [];
	// 				$result['user_id'] = $value['user_guid'];
	// 				$result['name'] = $value['name'];
	// 				$result['business_name'] = $value['business_name'];
	// 				$result['email'] = $value['email'];
	// 				$result['customer_stripe_id'] = $value['customer_stripe_id'];
	// 				$result['last_login_at'] = $value['last_login_at'];
	// 				$result['created_at'] = $value['created_at'];
	// 				$result['status'] = $value['status'];
	// 				// $profile_picture = '';
	// 				// if (!empty($value['profile_picture'])) {
	// 				// 	$profile_picture = s3_url($value['profile_picture']);
	// 				// }
	// 				// $result['profile_picture'] = $profile_picture;
	// 				// if ($value['address_present'] == '') {
	// 				// 	$result['present_address'] = (object) [];
	// 				// } else {
	// 				// 	$result['present_address'] = (array) json_decode($value['address_present']);
	// 				// }

	// 				// if ($value['address_permanent'] == '') {
	// 				// 	$result['permanent_address'] = (object) [];
	// 				// } else {
	// 				// 	$result['permanent_address'] = (array) json_decode($value['address_permanent']);
	// 				// }
	// 				$list[] = $result;
	// 			}
	// 		}
	// 		return $list;
	// 	} else {
	// 		return $query->row()->count;
	// 	}
	// }

	public function users_list($user_id, $keyword = '', $limit = 0, $offset = 0, $column_name, $order_by, $status, $pricing_plan_id, $start_date, $end_date) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('CONCAT(u.user_type, "_", IFNULL (o.organization_status, ""), "_" , IFNULL(om.role, "")) AS user_role');
			$this->db->select('u.user_type, u.user_type');
			$this->db->select('IFNULL(u.user_guid,"") AS user_guid', FALSE);
			$this->db->select('CONCAT(u.first_name, " ",u.last_name) AS name');
			$this->db->select('IFNULL(u.business_name,"") AS business_name', FALSE);
			$this->db->select('IFNULL(u.email,"") AS email', FALSE);
			$this->db->select('IFNULL(u.customer_stripe_id,"") AS customer_stripe_id', FALSE);
			$this->db->select('IFNULL(u.last_login_at,"") AS last_login_at', FALSE);
			$this->db->select('IFNULL(u.login_at,"") AS login_at', FALSE);
			$this->db->select('IFNULL(u.created_at,"") AS created_at', FALSE);
			$this->db->select('IFNULL(u.status,"") AS status', FALSE);
			$this->db->select('IFNULL(om.pricing_plan_id,"") AS pricing_plan_id', FALSE);
		} else {
			$this->db->select('COUNT(u.user_id) as count', FALSE);
		}
		$this->db->from('users AS u');
		$this->db->join('organization_members AS om', 'om.user_id = u.user_id', 'LEFT');
		$this->db->join('organizations AS o', 'o.organization_id = om.organization_id', 'LEFT');
		$this->db->join('user_plans AS up', 'up.user_id = u.user_id', 'LEFT');
		// $this->db->join('subscriptions AS s', 's.user_id = u.user_id', 'LEFT');
		$this->db->where('u.user_type!=', 'ADMIN');
		$this->db->order_by('u.created_at', 'desc');
		// $this->db->join('media AS m', 'm.media_id = u.media_id AND m.status="ACTIVE"', 'left');

		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('u.first_name', $keyword, 'both');
			$this->db->or_like('u.last_name', $keyword, 'both');
			$this->db->or_like('u.email', $keyword, 'both');
			$this->db->group_end();
		}

		if (!empty($status)) {
			$this->db->like('u.status', $status);
		}

		if (!empty($pricing_plan_id)) {
			$this->db->like('om.pricing_plan_id', $pricing_plan_id);
		}

		if (!empty($start_date) && !empty($end_date)) {
			$this->db->where('DATE(u.login_at) BETWEEN "' . $start_date . '"and"' . $end_date . '"');
		}

		if (($column_name !== '') && ($order_by !== '')) {
			$this->db->order_by('u.' . $column_name, $order_by);
		}
		$query = $this->db->get();
		$results = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				$list = [];
				foreach ($results as $key => $value) {
					$list[$key]['user_id'] = $value['user_guid'];
					$list[$key]['user_type'] = $value['user_type'];
					$list[$key]['user_role'] = $value['user_role'];
					$list[$key]['name'] = $value['name'];
					$list[$key]['business_name'] = $value['business_name'];
					$list[$key]['email'] = $value['email'];
					$list[$key]['customer_stripe_id'] = $value['customer_stripe_id'];
					$list[$key]['last_login_at'] = $value['last_login_at'];
					$list[$key]['login_at'] = $value['login_at'];
					$list[$key]['created_at'] = $value['created_at'];
					$list[$key]['status'] = $value['status'];
					$list[$key]['package'] = $value['pricing_plan_id'] != 0 ? get_detail_by_id($value['pricing_plan_id'], 'plan', 'name') : "--";
				}
				return $list;
			} else {
				return [];
			}

		} else {
			return $query->row()->count;
		}
	}

	public function invited_users_list($organization_id, $agency_user_id, $keyword, $limit, $offset, $column_name, $order_by) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('u.user_type');
			$this->db->select('IFNULL(u.user_guid,"") AS user_guid', FALSE);
			$this->db->select('CONCAT(u.first_name, " ",u.last_name) AS name');
			$this->db->select('IFNULL(u.business_name,"") AS business_name', FALSE);
			$this->db->select('IFNULL(u.email,"") AS email', FALSE);
			$this->db->select('IFNULL(u.customer_stripe_id,"") AS customer_stripe_id', FALSE);
			$this->db->select('IFNULL(u.last_login_at,"") AS last_login_at', FALSE);
			$this->db->select('IFNULL(u.created_at,"") AS created_at', FALSE);
			$this->db->select('IFNULL(u.status,"") AS status', FALSE);
			$this->db->select('IFNULL(om.pricing_plan_id,"") AS pricing_plan_id', FALSE);
		} else {
			$this->db->select('COUNT(u.user_id) as count', FALSE);
		}
		$this->db->from('organization_members AS om');
		$this->db->join('users AS u', 'u.user_id = om.user_id', 'LEFT');

		$this->db->where('u.user_id !=', $agency_user_id);
		// $this->db->where('u.user_type!=', 'AGENCY');
		$this->db->where('om.organization_id', $organization_id);
		$this->db->where('om.role', 'USER');
		$this->db->order_by('u.created_at', 'desc');

		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('u.first_name', $keyword, 'both');
			$this->db->or_like('u.last_name', $keyword, 'both');
			$this->db->or_like('u.email', $keyword, 'both');
			$this->db->group_end();
		}

		if (($column_name !== '') && ($order_by !== '')) {
			$this->db->order_by('u.' . $column_name, $order_by);
		}

		$query = $this->db->get();
		// echo $this->db->last_query();
		// die();
		$results = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				$list = [];
				foreach ($results as $key => $value) {
					$list[$key]['user_id'] = $value['user_guid'];
					$list[$key]['user_type'] = $value['user_type'];
					$list[$key]['name'] = $value['name'];
					$list[$key]['business_name'] = $value['business_name'];
					$list[$key]['email'] = $value['email'];
					$list[$key]['customer_stripe_id'] = $value['customer_stripe_id'];
					$list[$key]['last_login_at'] = $value['last_login_at'];
					$list[$key]['created_at'] = $value['created_at'];
					$list[$key]['status'] = $value['status'];
					$list[$key]['package'] = get_detail_by_id($value['pricing_plan_id'], 'plan', 'name');
				}
				return $list;
			} else {
				return [];
			}

		} else {
			return $query->row()->count;
		}
	}

	// public function invited_users_list($user_id, $agency_user_id, $keyword, $limit, $offset, $column_name, $order_by) {

	// 	if ($limit > 0 && $offset >= 0) {
	// 		$this->db->limit($limit, $offset);
	// 		$this->db->select('u.user_type');
	// 		$this->db->select('IFNULL(u.user_guid,"") AS user_guid', FALSE);
	// 		$this->db->select('CONCAT(u.first_name, " ",u.last_name) AS name');
	// 		$this->db->select('IFNULL(u.business_name,"") AS business_name', FALSE);
	// 		$this->db->select('IFNULL(u.email,"") AS email', FALSE);
	// 		$this->db->select('IFNULL(u.customer_stripe_id,"") AS customer_stripe_id', FALSE);
	// 		$this->db->select('IFNULL(u.last_login_at,"") AS last_login_at', FALSE);
	// 		$this->db->select('IFNULL(u.created_at,"") AS created_at', FALSE);
	// 		$this->db->select('IFNULL(u.status,"") AS status', FALSE);
	// 		// $this->db->select('IFNULL(up.pricing_plan_id,"") AS pricing_plan_id', FALSE);
	// 		$this->db->select('IFNULL(s.pricing_plan_id,"") AS pricing_plan_id', FALSE);
	// 	} else {
	// 		$this->db->select('COUNT(u.user_id) as count', FALSE);
	// 	}
	// 	$this->db->from('users AS u');
	// 	// $this->db->join('media AS m', 'm.media_id = u.profile_picture_id', 'LEFT');
	// 	$this->db->join('agency_invited_users AS aiu', 'aiu.user_id = u.user_id', 'LEFT');
	// 	$this->db->join('user_plans AS up', 'up.user_id = u.user_id', 'LEFT');
	// 	$this->db->join('subscriptions AS s', 's.user_id = u.user_id', 'LEFT');

	// 	$this->db->where('u.user_type!=', 'ADMIN');
	// 	$this->db->where('u.user_type!=', 'AGENCY');
	// 	$this->db->where('aiu.agency_id', $agency_user_id);
	// 	$this->db->order_by('u.created_at', 'desc');
	// 	// $this->db->join('media AS m', 'm.media_id = u.media_id AND m.status="ACTIVE"', 'left');
	// 	// if (!empty($searchKey)) {
	// 	//     $this->db->like('u.first_name', $searchKey, 'both');
	// 	//     $this->db->or_like('u.last_name', $searchKey, 'both');
	// 	//     $this->db->or_like('u.email', $searchKey, 'both');
	// 	// }
	// 	// if (!empty($filterBy)) {
	// 	// 	$this->db->like('u.status', $filterBy);
	// 	// }
	// 	// echo $user_type;die();
	// 	// $this->db->where('u.user_type', $user_type);
	// 	// $this->db->where('u.status!= "DELETED"');

	// 	if (!empty($keyword)) {
	// 		$this->db->group_start();
	// 		$this->db->like('u.first_name', $keyword, 'both');
	// 		$this->db->or_like('u.last_name', $keyword, 'both');
	// 		$this->db->or_like('u.email', $keyword, 'both');
	// 		$this->db->group_end();
	// 	}

	// 	// if (!empty($filter_type)) {
	// 	// 	$this->db->where('u.device_type_id', $filter_type);
	// 	// }

	// 	if (($column_name !== '') && ($order_by !== '')) {
	// 		$this->db->order_by('u.' . $column_name, $order_by);
	// 	}
	// 	$query = $this->db->get();
	// 	$results = $query->result_array();
	// 	if (($limit > 0) && ($offset >= 0)) {
	// 		if ($query->num_rows() > 0) {
	// 			$list = [];
	// 			foreach ($results as $key => $value) {
	// 				$list[$key]['user_id'] = $value['user_guid'];
	// 				$list[$key]['name'] = $value['name'];
	// 				$list[$key]['business_name'] = $value['business_name'];
	// 				$list[$key]['email'] = $value['email'];
	// 				$list[$key]['customer_stripe_id'] = $value['customer_stripe_id'];
	// 				$list[$key]['last_login_at'] = $value['last_login_at'];
	// 				$list[$key]['created_at'] = $value['created_at'];
	// 				$list[$key]['status'] = $value['status'];
	// 				$list[$key]['package'] = get_detail_by_id($value['pricing_plan_id'], 'plan', 'name');
	// 			}
	// 			return $list;
	// 		} else {
	// 			return [];
	// 		}

	// 	} else {
	// 		return $query->row()->count;
	// 	}
	// }

	public function send_invitation_to_user($organization_member_guid, $agency_user_guid, $plan_guid, $user_email) {
		$agency_user_id = get_detail_by_guid($agency_user_guid, 'user');
		$agency_user = $this->app->get_row('users', 'first_name, last_name', ['user_id' => $agency_user_id]);
		$first_name = safe_array_key($agency_user, "first_name", "");
		$last_name = safe_array_key($agency_user, "last_name", "");

		$this->load->helper('email');
		$email_template = "emailer/agency_invited_users";
		$subject = 'Welcome to Marketing Tiki';
		$name = 'User';
		$email = $user_email;
		$email_data = ["name" => $name, "organization_member_guid" => $organization_member_guid, "agency_user_guid" => $agency_user_guid, "plan_guid" => $plan_guid, "email" => $email, "agency_name" => $first_name . ' ' . $last_name];

		$message = $this->load->view($email_template, $email_data, TRUE);
		$body = str_replace( "\r\n.", "\r\n..", $message );
		$this->load->library('email');
		$this->email->from(SUPPORT_EMAIL, FROM_NAME);
		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($body);
		$this->email->send();
	}

	public function change_status($user_guid, $status) {
		$data = [
			"status" => $status,
			'updated_at' => DATETIME,
		];
		$this->db->update('users', $data, [
			'user_guid' => $user_guid,
		]);
		return true;
	}

	public function get_user_individual() {
		$this->db->select('u.user_type');
		$this->db->select('CONCAT(u.user_type, "_", IFNULL (o.organization_status, ""), "_" , IFNULL(om.role, "")) AS user_role', FALSE);
		$this->db->select('IFNULL(u.user_guid,"") AS user_guid', FALSE);
		$this->db->select('CONCAT(u.first_name, " ",u.last_name) AS name');

		$this->db->from('organization_members AS om');
		$this->db->join('organizations AS o', 'o.organization_id = om.organization_id', 'LEFT');
		$this->db->join('users AS u', 'u.user_id = om.user_id', 'LEFT');
		$this->db->where('u.user_type', 'USER');
		$this->db->order_by('u.first_name', 'ASC');
		$query = $this->db->get();
		// echo $this->db->last_query();
		// die();
		$results = $query->result_array();
		$users = [];
		foreach ($results as $value) {
			// if($value['user_role'] != 'USER_AGENCY_OWNER'){
			// 	$users[] = $value;
			// }
			if($value['user_role'] == 'USER_AGENCY_USER' || $value['user_role'] == 'USER_INDIVIDUAL_OWNER'){
				$users[] = $value;
			}
		}
		return $users;
	}

	public function send_reset_password_link($user_id) {
		$this->db->update('verifications', ['status' => 'EXPIRED'], ['user_id' => $user_id, 'verification_type' => 'RESET_PASSWORD_LINK', 'status' => 'ACTIVE']);
		$unique_code = get_guid();
		$this->db->insert('verifications', [
			"verification_guid" => $unique_code,
			"verification_type" => 'RESET_PASSWORD_LINK',
			"user_id" => $user_id,
			"code" => NULL,
			"created_at" => DATETIME,
		]);
		$this->load->helper('email');
		$user = $this->app->get_row('users', 'email, first_name, last_name', ['user_id' => $user_id]);
		$name = $user['first_name'] . ' ' . $user['last_name'];
		$email = $user['email'];
		$email_data = ["name" => $name, "unique_code" => $unique_code, "email" => $email];
		$subject = 'Reset Password';
		$email_template = "emailer/web_reset_password";
		$message = $this->load->view($email_template, $email_data, TRUE);
		$this->load->library('email');
		$this->email->from(SUPPORT_EMAIL, FROM_NAME);
		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
		// $this->load->model("notifications_model");
		// $parameters = [];
		// $this->notifications_model->save(15, 0, [$user_id], $user_id, $parameters);
	}

	public function user_detail_by_id($user_guid) {
		$this->db->select('u.user_type, u.user_sub_type');
		$this->db->select('CONCAT(u.user_type, "_", IFNULL (o.organization_status, ""), "_" , IFNULL(om.role, "")) AS user_role', FALSE);
		$this->db->select('IFNULL(u.user_guid,"") AS user_guid', FALSE);
		// $this->db->select('CONCAT(u.first_name, " ",u.last_name) AS name');
		$this->db->select('IFNULL(u.first_name,"") AS name', FALSE);
		$this->db->select('IFNULL(u.business_name,"") AS business_name', FALSE);
		$this->db->select('IFNULL(u.email,"") AS email', FALSE);
		$this->db->select('IFNULL(u.customer_stripe_id,"") AS customer_stripe_id', FALSE);
		$this->db->select('IFNULL(u.last_login_at,"") AS last_login_at', FALSE);
		$this->db->select('IFNULL(u.login_at,"") AS login_at', FALSE);
		$this->db->select('IFNULL(u.created_at,"") AS created_at', FALSE);
		$this->db->select('IFNULL(u.status,"") AS status', FALSE);
		$this->db->select('IFNULL(om.pricing_plan_id,"") AS pricing_plan_id', FALSE);
		$this->db->from('users AS u');
		$this->db->join('organization_members AS om', 'om.user_id = u.user_id', 'LEFT');
		$this->db->join('organizations AS o', 'o.organization_id = om.organization_id', 'LEFT');
		$this->db->join('user_plans AS up', 'up.user_id = u.user_id', 'LEFT');
		$this->db->where('u.user_guid', $user_guid);
		$query = $this->db->get();
		$result = $query->row_array();
		$result['user_id'] = $result['user_guid'];
		$result['user_type'] = $result['user_type'];
		$result['user_sub_type'] = $result['user_sub_type'];
		$result['user_role'] = $result['user_role'];
		$result['name'] = $result['name'];
		$result['business_name'] = $result['business_name'];
		$result['email'] = $result['email'];
		$result['customer_stripe_id'] = $result['customer_stripe_id'];
		$result['last_login_at'] = $result['last_login_at'];
		$result['login_at'] = $result['login_at'];
		$result['created_at'] = $result['created_at'];
		$result['status'] = $result['status'];
		$result['pricing_plan_guid'] = get_detail_by_id($result['pricing_plan_id'], 'plan', 'pricing_plan_guid');
		$result['package'] = $result['pricing_plan_id'] != 0 ? get_detail_by_id($result['pricing_plan_id'], 'plan', 'name') : "--";
		unset($result['pricing_plan_id']);
		return $result;
	}

	public function update_user($user_guid, $name, $business_name, $email) {
		$data = [
			"first_name" => $name,
			"email" => $email,
			"business_name" => $business_name,
			'updated_at' => DATETIME,
		];
		$this->db->update('users', $data, [
			'user_guid' => $user_guid,
		]);
		return true;
	}

	public function update_organization_user_email($user_id, $email) {
		$this->db->update('organization_members', ['email' => $email], [
			'user_id' => $user_id,
		]);
		return true;
	}

	public function update_organization_member_email($organization_member_id, $email) {
		$data = [
			"email" => $email
		];
		$this->db->update('organization_members', $data, [
			'organization_member_id' => $organization_member_id,
		]);
		return true;
	}

	public function send_invitation_to_member($organization_member_guid, $agency_user_id, $user_email) {
		$agency_user = $this->app->get_row('users', 'first_name, last_name', ['user_id' => $agency_user_id]);
		$first_name = safe_array_key($agency_user, "first_name", "");
		$last_name = safe_array_key($agency_user, "last_name", "");
		$this->load->helper('email');
		$email_template = "emailer/invited_member";
		$subject = $first_name . ' added you as a team member';
		$name = 'User';
		$email = $user_email;
		$email_data = ["name" => $name, "organization_member_guid" => $organization_member_guid, "email" => $email, "agency_name" => $first_name . ' ' . $last_name];

		$message = $this->load->view($email_template, $email_data, TRUE);
		$this->load->library('email');
		$this->email->from(SUPPORT_EMAIL, FROM_NAME);
		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
	}

	public function team_members_list($added_by, $keyword, $limit, $offset, $column_name, $order_by) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('u.user_type, u.user_sub_type');
			$this->db->select('IFNULL(u.user_guid,"") AS user_guid', FALSE);
			$this->db->select('CONCAT(u.first_name, " ",u.last_name) AS name');
			// $this->db->select('IFNULL(u.email,"") AS email', FALSE);
			$this->db->select('IFNULL(u.last_login_at,"") AS last_login_at', FALSE);
			$this->db->select('IFNULL(u.created_at,"") AS created_at', FALSE);
			$this->db->select('IFNULL(u.status,"") AS status', FALSE);
			$this->db->select('IFNULL(om.organization_member_guid,"") AS organization_member_guid', FALSE);
			$this->db->select('IFNULL(om.pricing_plan_id,"") AS pricing_plan_id', FALSE);
			$this->db->select('IFNULL(om.email,"") AS email', FALSE);
		} else {
			$this->db->select('COUNT(om.organization_member_id) as count', FALSE);
		}
		$this->db->from('organization_members AS om');
		$this->db->join('users AS u', 'u.user_id = om.user_id', 'LEFT');
		$this->db->where('om.added_by', $added_by);
		$this->db->where('om.role', 'TEAM');
		$this->db->order_by('u.created_at', 'desc');

		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('u.first_name', $keyword, 'both');
			$this->db->or_like('u.last_name', $keyword, 'both');
			$this->db->or_like('u.email', $keyword, 'both');
			$this->db->group_end();
		}

		if (($column_name !== '') && ($order_by !== '')) {
			$this->db->order_by('u.' . $column_name, $order_by);
		}

		$query = $this->db->get();
		// echo $this->db->last_query();
		// die();
		$results = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				$list = [];
				foreach ($results as $key => $value) {
					$list[$key]['user_id'] = $value['user_guid'];
					$list[$key]['user_type'] = $value['user_sub_type'];
					$list[$key]['name'] = $value['name'];
					$list[$key]['email'] = $value['email'];
					$list[$key]['organization_member_guid'] = $value['organization_member_guid'];
					$list[$key]['status'] = $value['status'];
					$list[$key]['last_login_at'] = $value['last_login_at'];
					$list[$key]['created_at'] = $value['created_at'];
				}
				return $list;
			} else {
				return [];
			}

		} else {
			return $query->row()->count;
		}
	}

	public function get_user_list($type = NULL) {
		$this->db->select('u.user_type, u.user_id');
		$this->db->select('CONCAT(u.user_type, "_", IFNULL (o.organization_status, ""), "_" , IFNULL(om.role, "")) AS user_role', FALSE);
		$this->db->select('IFNULL(u.user_guid,"") AS user_guid', FALSE);
		$this->db->select('CONCAT(u.first_name, " ",u.last_name) AS name');

		$this->db->from('organization_members AS om');
		$this->db->join('organizations AS o', 'o.organization_id = om.organization_id', 'LEFT');
		$this->db->join('users AS u', 'u.user_id = om.user_id', 'LEFT');
		$this->db->where('u.user_type', 'USER');
		$this->db->order_by('u.first_name', 'ASC');
		$query = $this->db->get();
		$results = $query->result_array();
		$users = [];
		foreach ($results as $value) {
			// $value['plan_name'] = $this->getPlanName($value['user_id']);
			$value['campaign_limit'] = $this->app->getPlanCampaignLimit($value['user_id']);
			$value['plan_name'] = $this->app->getPlanName($value['user_id']);
			$value['can_add_campaign'] = $this->app->canAddCampaign($value['user_id'], $value['user_role'], $value['plan_name'], $value['campaign_limit']);
			if($type == 'AGENCY'){
				if($value['user_role'] == 'USER_AGENCY_OWNER'){
					$users[] = $value;
				}
			// } else if ($type == 'CAMPAIGN') {
			// 	if($value['user_role'] != 'USER_AGENCY_OWNER'){
			// 		$users[] = $value;
			// 	}
			} else {
				if($value['user_role'] == 'USER_AGENCY_USER' || $value['user_role'] == 'USER_INDIVIDUAL_OWNER'){
					$users[] = $value;
				}
			}
			unset($value['user_id']);
		}
		return $users;
	}

	
	// public function canAddCampaignol($added_by, $user_role, $plan_name) {
	// 	$count = 0;
	// 	$this->db->select('COUNT(c.campaign_id) as count', FALSE);
	// 	$this->db->from('campaigns AS c');
	// 	$this->db->where('c.added_by', $added_by);
	// 	$query = $this->db->get();
	// 	$count = $query->row()->count;
	// 	if(($user_role === 'USER_INDIVIDUAL_TEAM' || $user_role === 'USER_INDIVIDUAL_OWNER') && $plan_name === 'ESSENTIAL' && $count >= 1) {
	// 		return "NO";
	// 	  } else if (($user_role === 'USER_INDIVIDUAL_TEAM' || $user_role === 'USER_INDIVIDUAL_OWNER') && $plan_name === 'PRO' && $count >= 5) {
	// 		return "NO";
	// 	  } else {
	// 		return "YES";
	// 	}
	// }

	// public function getPlanNameol($added_by) {
	// 	$this->db->select('IFNULL(pp.name, "") AS plan_name', FALSE);
	// 	$this->db->from('users AS u');
	// 	$this->db->join('organization_members AS om', 'om.user_id = u.user_id', 'LEFT');
	// 	$this->db->join('pricing_plans AS pp', 'om.pricing_plan_id = pp.pricing_plan_id', 'LEFT');
	// 	$this->db->where('u.user_id', $added_by);
	// 	$query = $this->db->get();
	// 	$user = $query->row_array();
	// 	$user['plan_name'] = strtoupper($user['plan_name']);
	// 	return $user['plan_name'];
	// }

	public function get_agency_billing_contacts($keyword, $limit, $offset, $column_name, $order_by) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('CONCAT(u.user_type, "_", IFNULL (o.organization_status, ""), "_" , IFNULL(om.role, "")) AS user_role');
			$this->db->select('IFNULL(u.user_id,"") AS user_id', FALSE);
			$this->db->select('IFNULL(u.user_guid,"") AS user_guid', FALSE);
			$this->db->select('CONCAT(u.first_name, " ",u.last_name) AS name');
			$this->db->select('IFNULL(u.business_name,"") AS business_name', FALSE);
			$this->db->select('IFNULL(u.email,"") AS email', FALSE);
			$this->db->select('IFNULL(u.created_at,"") AS created_at', FALSE);
			$this->db->select('IFNULL(u.status,"") AS status', FALSE);
			$this->db->select('IFNULL(up.plan_amount,"") AS plan_amount', FALSE);
			$this->db->select('IFNULL(om.pricing_plan_id,"") AS pricing_plan_id', FALSE);
			$this->db->select('IFNULL(o.organization_id,"") AS organization_id', FALSE);
		} else {
			$this->db->select('COUNT(u.user_id) as count', FALSE);
		}
		$this->db->from('users AS u');
		$this->db->join('user_plans AS up', 'up.user_id = u.user_id', 'LEFT');
		$this->db->join('organization_members AS om', 'om.user_id = u.user_id', 'LEFT');
		$this->db->join('organizations AS o', 'o.organization_id = om.organization_id', 'LEFT');
		$this->db->where('o.organization_status', 'AGENCY');
		$this->db->where('om.role', 'OWNER');
		$this->db->order_by('u.created_at', 'desc');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('u.first_name', $keyword, 'both');
			$this->db->or_like('u.last_name', $keyword, 'both');
			$this->db->or_like('u.email', $keyword, 'both');
			$this->db->group_end();
		}

		// if (!empty($status)) {
		// 	$this->db->like('u.status', $status);
		// }

		// if (!empty($pricing_plan_id)) {
		// 	$this->db->like('om.pricing_plan_id', $pricing_plan_id);
		// }

		// if (!empty($start_date) && !empty($end_date)) {
		// 	$this->db->where('DATE(u.login_at) BETWEEN "' . $start_date . '"and"' . $end_date . '"');
		// }

		if (($column_name !== '') && ($order_by !== '')) {
			$this->db->order_by('u.' . $column_name, $order_by);
		}
		$query = $this->db->get();
		$results = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				$list = [];
				foreach ($results as $key => $value) {
					$list[$key]['user_id'] = $value['user_guid'];
					$list[$key]['user_role'] = $value['user_role'];
					$list[$key]['name'] = $value['name'];
					$list[$key]['business_name'] = $value['business_name'];
					$list[$key]['email'] = $value['email'];
					$list[$key]['created_at'] = $value['created_at'];
					$list[$key]['status'] = $value['status'];
					$list[$key]['package'] = $value['pricing_plan_id'] != 0 ? get_detail_by_id($value['pricing_plan_id'], 'plan', 'name') : "--";
					$list[$key]['base_price'] = $value['pricing_plan_id'] != 0 ? get_detail_by_id($value['pricing_plan_id'], 'plan', 'base_price') : "--";
					$list[$key]['agency_users'] = $this->get_agency_users($value['user_id'], $value['organization_id']);
					$list[$key]['plan_amount'] = $value['plan_amount'];
				}
				return $list;
			} else {
				return [];
			}

		} else {
			return $query->row()->count;
		}
	}

	public function get_agency_billing_export_contacts($user_id) {
		// $this->db->select('CONCAT(u.user_type, "_", IFNULL (o.organization_status, ""), "_" , IFNULL(om.role, "")) AS user_role');
		$this->db->select('IFNULL(u.user_id,"") AS user_id', FALSE);
		// $this->db->select('IFNULL(u.user_guid,"") AS user_guid', FALSE);
		$this->db->select('CONCAT(u.first_name, " ",u.last_name) AS name');
		$this->db->select('IFNULL(u.business_name,"") AS business_name', FALSE);
		// $this->db->select('IFNULL(u.email,"") AS email', FALSE);
		$this->db->select('IFNULL(u.created_at,"") AS created_at', FALSE);
		// $this->db->select('IFNULL(u.status,"") AS status', FALSE);
		$this->db->select('IFNULL(up.plan_amount,"") AS plan_amount', FALSE);
		$this->db->select('IFNULL(om.pricing_plan_id,"") AS pricing_plan_id', FALSE);
		$this->db->select('IFNULL(o.organization_id,"") AS organization_id', FALSE); 
		$this->db->from('users AS u');
		$this->db->join('user_plans AS up', 'up.user_id = u.user_id', 'LEFT');
		$this->db->join('organization_members AS om', 'om.user_id = u.user_id', 'LEFT');
		$this->db->join('organizations AS o', 'o.organization_id = om.organization_id', 'LEFT');
		$this->db->where('o.organization_status', 'AGENCY');
		$this->db->where('om.role', 'OWNER');
		$this->db->where('u.user_id', $user_id);
		$this->db->order_by('u.created_at', 'desc');
		
		$query = $this->db->get();
		$result = $query->row_array();;
		$result['name'] = $result['name'];
		$result['business_name'] = $result['business_name'];
		$result['created_at'] = $result['created_at'];
		$result['package'] = $result['pricing_plan_id'] != 0 ? get_detail_by_id($result['pricing_plan_id'], 'plan', 'name') : "--";
		$result['base_price'] = $result['pricing_plan_id'] != 0 ? get_detail_by_id($result['pricing_plan_id'], 'plan', 'base_price') : "--";
		$result['agency_users'] = $this->get_agency_users($result['user_id'], $result['organization_id']);
		$result['plan_amount'] = $result['plan_amount'];
		return $result;
			
	}

	public function get_agency_users($user_id, $organization_id) {
		$this->db->select('u.user_type');
		$this->db->select('IFNULL(u.user_guid,"") AS user_guid', FALSE);
		$this->db->select('CONCAT(u.first_name, " ",u.last_name) AS name');
		$this->db->select('IFNULL(u.business_name,"") AS business_name', FALSE);
		$this->db->select('IFNULL(u.email,"") AS email', FALSE);
		$this->db->select('IFNULL(u.customer_stripe_id,"") AS customer_stripe_id', FALSE);
		$this->db->select('IFNULL(u.last_login_at,"") AS last_login_at', FALSE);
		$this->db->select('IFNULL(u.created_at,"") AS created_at', FALSE);
		$this->db->select('IFNULL(u.status,"") AS status', FALSE);
		$this->db->select('IFNULL(om.pricing_plan_id,"") AS pricing_plan_id', FALSE);
		$this->db->select('IFNULL(up.plan_amount,"") AS plan_amount', FALSE);
		
		// $this->db->from('organization_members AS om');
		// $this->db->join('users AS u', 'u.user_id = om.user_id', 'LEFT');
		// $this->db->where('u.user_id !=', $user_id);
		// $this->db->where('om.organization_id', $organization_id);
		// $this->db->where('om.role', 'USER');
		// $this->db->order_by('u.created_at', 'desc');

		$this->db->from('organization_members AS om');
		$this->db->join('user_plans AS up', 'up.user_id = om.user_id', 'LEFT');
		$this->db->join('users AS u', 'u.user_id = om.user_id', 'LEFT');

		$this->db->where('u.user_id !=', $user_id);
		$this->db->where('om.organization_id', $organization_id);
		$this->db->where('om.role', 'USER');
		$this->db->order_by('u.created_at', 'desc');


		$query = $this->db->get();
		$results = $query->result_array();
			if ($query->num_rows() > 0) {
				$list = [];
				foreach ($results as $key => $value) {
					// echo $value['plan_amount'];
					// echo $value['plan_amount'];

					// die();
					$plan_amount = (float)$value['plan_amount'];
					$list[$key]['plan_amount'] = $value['plan_amount'];
					$list[$key]['user_id'] = $value['user_guid'];
					$list[$key]['name'] = $value['name'];
					$list[$key]['business_name'] = $value['business_name'];
					$list[$key]['email'] = $value['email'];
					$list[$key]['created_at'] = $value['created_at'];
					$list[$key]['status'] = $value['status'];
					$list[$key]['package'] = $value['pricing_plan_id'] != 0 ? get_detail_by_id($value['pricing_plan_id'], 'plan', 'name') : "--";
					$list[$key]['base_price'] = $value['pricing_plan_id'] != 0 ? get_detail_by_id($value['pricing_plan_id'], 'plan', 'base_price') : "--";
					$list[$key]['billing_days'] = $this->get_billing_days($value['created_at']);
				}
				return $list;
			} else {
				return [];
			}
	}

	public function get_billing_days($date) {
		$signup_date = new DateTime($date);
		$current_day = $signup_date->format('d');
		$d = cal_days_in_month(CAL_GREGORIAN,$signup_date->format('m'),$signup_date->format('Y'));
		return $d - $current_day;
	}
}

?>