<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Users_model extends CI_Model {

	public function __construct() {
		// Call the CI_Model constructor
		parent::__construct();
	}

	/**
	 *
	 * @param type $session_id
	 * @return type
	 */
	public function check_login($email, $password) {

		$user = $this->app->get_row('users', 'user_id', [
			"email" => strtolower($email),
			"password" => md5($password),
		]);

		return safe_array_key($user, 'user_id', "0");
	}

	/**
	 * Insert in site log table
	 */
	public function add_logs($input) {
		$inp_pass = safe_array_key($input, 'password', '');
		$input['password'] = str_repeat("*", strlen($inp_pass));
		// $input['password'] = str_repeat("*", strlen($input['password']));
		// $input['password'] = md5($input['password']);
		$this->db->insert('site_logs', [
			"site_log_guid" => get_guid(),
			"uri" => $this->uri->uri_string(),
			"input" => json_encode($input),
			"ip_address" => $this->input->ip_address(),
			"created_at" => DATETIME,
		]);
		$site_log_id = $this->db->insert_id();
		return $site_log_id;
	}

	/**
	 * @param type $user_id
	 * @param type $device_type_id
	 * @param type $device_token
	 * @param type $ip_address
	 * @return type
	 */
	public function create_session_key($user_id, $device_type_id, $device_token, $ip_address) {

		$session_id = get_guid();
		$this->db->insert('user_login_sessions', [
			'session_key' => $session_id,
			'user_id' => $user_id,
			'device_type_id' => $device_type_id,
			'device_token' => !empty($device_token) ? $device_token : NULL,
			'ip_address' => $ip_address,
			"created_at" => DATETIME,
			"last_used_at" => DATETIME,

		]);

		//update user last_login_at
		$this->db->set('last_login_at', 'login_at', FALSE);
		$this->db->set('login_at', DATETIME);
		$this->db->where('user_id', $user_id);
		$this->db->update('users');
		return $session_id;
	}

	/** create_user
	 * @param type $name
	 * @param type $email
	 * @param type $password
	 * @return type
	 */
	public function create_user($first_name, $last_name, $mobile,  $email, $password) {

		$email = strtolower($email);
		$this->db->insert('users', [
			"user_guid" => get_guid(),
			"first_name" => $first_name,
			"last_name" => $last_name,
			"mobile" => $mobile,
			// "user_sub_type" => $user_sub_type,
			// "business_name" => $business_name,
			"status" => "ACTIVE",
			"email" => $email,
			"password" => md5($password),
			// "device_type_id" => $device_type_id,
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		]);
		$user_id = $this->db->insert_id();
		return $user_id;
	}
	// public function create_user($first_name, $last_name, $user_sub_type, $business_name, $email, $password, $device_type_id) {

	// 	$email = strtolower($email);
	// 	$this->db->insert('users', [
	// 		"user_guid" => get_guid(),
	// 		"first_name" => $first_name,
	// 		"last_name" => $last_name,
	// 		"user_sub_type" => $user_sub_type,
	// 		"business_name" => $business_name,
	// 		"status" => "ACTIVE",
	// 		"email" => $email,
	// 		"password" => md5($password),
	// 		"device_type_id" => $device_type_id,
	// 		"created_at" => DATETIME,
	// 		"updated_at" => DATETIME,
	// 	]);
	// 	$user_id = $this->db->insert_id();
	// 	return $user_id;
	// }
	/**
	 *
	 * @param type $user_id
	 * @param type $device_type
	 */
	public function send_user_signup_success_email($user_id, $device_type) {
		$this->load->helper('email');
		$user = $this->app->get_row('users', 'email, first_name, last_name', ['user_id' => $user_id]);
		$email = $user['email'];
		$email_data = array("name" => $user['first_name'] . ' ' . $user['last_name'], "unique_code" => "", "email" => $email);

		switch ($device_type) {
		case 'web_browser':
			$subject = 'Signup Success';
			$email_template = "emailer/web_registration_success";
			break;
		case 'ios':
		case 'android':
			$subject = 'Signup Success';
			$email_template = "emailer/device_registration";
			break;
		}

		$message = $this->load->view($email_template, $email_data, TRUE);
		$body = str_replace( "\r\n.", "\r\n..", $message );
		$this->load->library('email');
		$this->email->from(SUPPORT_EMAIL, FROM_NAME);
		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($body);
		$this->email->send();
	}

	/**
	 *
	 * @param type $user_id
	 * @param type $device_type
	 */
	public function send_team_member_signup_success_email($user_id, $device_type) {
		$this->load->helper('email');
		$user = $this->app->get_row('users', 'email, first_name, last_name', ['user_id' => $user_id]);
		$email = $user['email'];
		$email_data = array("name" => $user['first_name'] . ' ' . $user['last_name'], "unique_code" => "", "email" => $email);

		switch ($device_type) {
		case 'web_browser':
			$subject = 'Signup Success';
			$email_template = "emailer/web_team_member_registration_success";
			break;
		case 'ios':
		case 'android':
			$subject = 'Signup Success';
			$email_template = "emailer/device_registration";
			break;
		}
		$message = $this->load->view($email_template, $email_data, TRUE);
		$this->load->library('email');
		$this->email->from(SUPPORT_EMAIL, FROM_NAME);
		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
	}

	/**
	 *
	 * @param type $user_id
	 * @param type $device_type
	 */
	public function send_user_verification($user_id, $device_type) {
		$this->load->helper('email');
		$user = $this->app->get_row('users', 'email, first_name, last_name', ['user_id' => $user_id]);
		$email_data = array("name" => $user['first_name'] . ' ' . $user['last_name'], "unique_code" => "");
		$email = $user['email'];

		switch ($device_type) {
		case 'web_browser':
			$this->db->update('verifications', ['status' => 'EXPIRED'], ['user_id' => $user_id, 'verification_type' => 'ACCOUNT_VERIFICATION_LINK', 'status' => 'ACTIVE']);
			// $unique_code = get_guid();
			$unique_code = unique_random_string('verifications', 'code', ['status' => 'ACTIVE'], 'numeric', 5);
			$this->db->insert('verifications', [
				"verification_guid" => $unique_code,
				"verification_type" => 'ACCOUNT_VERIFICATION_LINK',
				"user_id" => $user_id,
				"code" => $unique_code,
				"created_at" => DATETIME,
			]);

			$email_data['unique_code'] = $unique_code;
			$subject = 'Welcome to Marketing Tiki - Activate Account';
			$email_template = "emailer/web_registration";
			break;
		case 'ios':
		case 'android':
			$this->db->update('verifications', ['status' => 'EXPIRED'], ['user_id' => $user_id, 'verification_type' => 'ACCOUNT_VERIFICATION_CODE', 'status' => 'ACTIVE']);
			$unique_code = unique_random_string('verifications', 'code', ['status' => 'ACTIVE'], 'numeric', 5);
			$this->db->insert('verifications', [
				"verification_guid" => get_guid(),
				"verification_type" => 'ACCOUNT_VERIFICATION_CODE',
				"user_id" => $user_id,
				"code" => $unique_code,
				"created_at" => DATETIME,
			]);

			$email_data['unique_code'] = $unique_code;
			$subject = 'Welcome to Marketing Tiki - Activate Account';
			$email_template = "emailer/device_registration";
			break;
		}

		$message = $this->load->view($email_template, $email_data, TRUE);
		$this->load->library('email');
		$this->email->from(SUPPORT_EMAIL, FROM_NAME);
		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
	}

	/**
	 *
	 * @param type $user_id
	 * @param type $device_type
	 */
	public function send_reset_password($user_id, $device_type) {
		$this->load->helper('email');
		$user = $this->app->get_row('users', 'email, first_name, last_name', ['user_id' => $user_id]);
		// $email_template = "emailer/web_reset_password";
		$name = $user['first_name'] . ' ' . $user['last_name'];
		$email = $user['email'];
		$email_data = array("name" => $name, "unique_code" => "", "email" => $email );

		switch ($device_type) {
		case 'web_browser':
			$this->db->update('verifications', ['status' => 'EXPIRED'], ['user_id' => $user_id, 'verification_type' => 'RESET_PASSWORD_LINK', 'status' => 'ACTIVE']);
			$unique_code = get_guid();
			// $unique_code = unique_random_string('verifications', 'code', ['status' => 'ACTIVE'], 'numeric', 5);
			$this->db->insert('verifications', [
				"verification_guid" => $unique_code,
				"verification_type" => 'RESET_PASSWORD_LINK',
				"user_id" => $user_id,
				"code" => NULL,
				"created_at" => DATETIME,
			]);

			$email_data['unique_code'] = $unique_code;
			$subject = 'Reset Password';
			$email_template = "emailer/web_reset_password";
			break;
		case 'ios':
		case 'android':
			$this->db->update('verifications', ['status' => 'EXPIRED'], ['user_id' => $user_id, 'verification_type' => 'RESET_PASSWORD_CODE', 'status' => 'ACTIVE']);
			$unique_code = unique_random_string('verifications', 'code', ['status' => 'ACTIVE'], 'numeric', 5);
			$this->db->insert('verifications', [
				"verification_guid" => get_guid(),
				"verification_type" => 'RESET_PASSWORD_CODE',
				"user_id" => $user_id,
				"code" => $unique_code,
				"created_at" => DATETIME,
			]);
			$email_data['unique_code'] = $unique_code;
			$subject = 'Reset Password';
			$email_template = "emailer/device_reset_password";
			break;
		}

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

	/**
	 *
	 * @param type $device_type_id
	 * @param type $verify_code
	 * @return type
	 */
	public function set_user_verified_by_verify_code($device_type_id, $verify_code) {
		if (in_array($device_type_id, array("1"))) {
			$verification = $this->app->get_row('verifications', 'verification_id, user_id', ['verification_guid' => $verify_code]);
		} else {
			$verification = $this->app->get_row('verifications', 'verification_id, user_id', ['code' => $verify_code]);
		}
		$this->db->update('users', ['status' => 'ACTIVE', 'updated_at' => DATETIME], ['user_id' => $verification['user_id']]);
		$this->db->update('verifications', ['status' => 'USED', 'used_at' => DATETIME], ['verification_id' => $verification['verification_id']]);
		return $verification['user_id'];
	}

	/**
	 *
	 * @param type $verify_code
	 * @return type
	 */
	public function email_verify($verify_code, $email_type = '') {
		$verification = $this->app->get_row('verifications', 'verification_id, verification_type, verification_target, user_id', ['code' => $verify_code]);
		if (count($verification) > 0 && !empty($email_type)) {
			/* if ($verification['verification_type'] == "EMAIL_VERIFICATION_CODE") {
				              $this->db->update('users', ['work_email_status' => 'ACTIVE', 'updated_at' => DATETIME], ['work_email' => $verification['verification_target']]);
				              $this->db->update('user_emails', ['status' => 'ACTIVE', 'updated_at' => DATETIME], ['email' => $verification['verification_target']]);
				              } else {
				              $this->db->update('user_emails', ['status' => 'ACTIVE', 'updated_at' => DATETIME], ['user_email_id' => $verification['user_id']]);
				              } *
			*/
			if ($email_type == 'Work') {
				$this->db->update('users', ['work_email_status' => 'ACTIVE', 'work_email' => $verification['verification_target'], 'updated_at' => DATETIME], ['user_id' => $verification['user_id']]);

				$this->db->update('mandate_candidates', ['user_id' => $verification['user_id']], ['user_id' => NULL, 'email' => $verification['verification_target']]);

				$this->db->update('mandate_collaborators', ['user_id' => $verification['user_id']], ['user_id' => NULL, 'email' => $verification['verification_target']]);
			}
			if ($email_type == 'Personal') {

				$where = [
					'email' => $verification['verification_target'],
					"type" => "PERSONAL",
				];

				$row = $this->app->get_row('user_emails', 'user_email_id', $where);
				if (count($row) > 0) {
					$this->db->update('user_emails', ['status' => 'ACTIVE', 'updated_at' => DATETIME], ['user_email_id' => $row['user_email_id']]);
				} else {
					$personal_emails_data = [
						"status" => 'ACTIVE',
						"user_id" => $verification['user_id'],
						"type" => 'PERSONAL',
						'email' => $verification['verification_target'],
						"created_at" => DATETIME,
						"updated_at" => DATETIME,
					];
					$this->db->insert('user_emails', $personal_emails_data);
				}

				$this->db->update('mandate_candidates', ['user_id' => $verification['user_id']], ['user_id' => NULL, 'email' => $verification['verification_target']]);

				$this->db->update('mandate_collaborators', ['user_id' => $verification['user_id']], ['user_id' => NULL, 'email' => $verification['verification_target']]);
			}
			$this->db->update('verifications', ['status' => 'USED', 'used_at' => DATETIME], ['verification_id' => $verification['verification_id']]);
		}
	}

	public function set_personal_email($email, $user_id) {
		$where = [
			'email' => $email,
			'type' => "PERSONAL",
			'user_id' => $user_id,
			'status' => 'ACTIVE',
		];

		$row = $this->app->get_row('user_emails', 'user_email_id', $where);
		if (count($row) > 0) {
			$this->db->update('users', ['personal_email' => $email, 'updated_at' => DATETIME], ['user_id' => $user_id]);
		}
	}

	public function delete_personal_email($email, $user_id) {
		$this->db->delete("user_emails", [
			'email' => $email,
			'user_id' => $user_id,
		]);
	}

	/**
	 * [update_email_status description]
	 * @param  [int] $user_id    [User ID]
	 * @param  string $email_type [Email Type]
	 */
	protected function update_email_status($user_id, $email_type = 'LOGIN') {
		$this->db->update('user_emails', ['status' => 'ACTIVE', 'updated_at' => DATETIME], ['user_id' => $user_id, 'type' => $email_type]);
	}

	/**
	 *
	 * @param type $device_type_id
	 * @param type $password_reset_code
	 * @param type $password
	 */
	public function reset_user_password_by_password_reset_code($device_type_id, $password_reset_code, $password) {
		if (in_array($device_type_id, array("1"))) {
			$verification = $this->app->get_row('verifications', 'verification_id, user_id', ['verification_guid' => $password_reset_code]);
		} else {
			$verification = $this->app->get_row('verifications', 'verification_id, user_id', ['code' => $password_reset_code]);
		}

		$this->db->update('users', ["password" => md5($password)], ["user_id" => $verification['user_id']]);
		$this->db->update('verifications', ['status' => 'USED', 'used_at' => DATETIME], ['verification_id' => $verification['verification_id']]);
		$this->reset_password_update_success($verification['user_id']);
	}

	public function reset_password_update_success($user_id) {
		// SEND NOTIFICATION
		$this->load->model("notifications_model");
		$parameters = [];
		$this->notifications_model->save(16, 0, [$user_id], $user_id, $parameters);

		// SEND PUSH NOTIFICATION
		$title = 'Password Reset';
		$body = 'Your password has been successfully reset.';
		push_notification($user_id, $title, $body);

		// SEND EMAIL
		$this->load->helper('email');
		$user = $this->app->get_row('users', 'email, first_name, last_name', ['user_id' => $user_id]);
		$email_template = "emailer/updated_password_success";
		$subject = 'Password Updated Successfully.';
		$name = $user['first_name'] . ' ' . $user['last_name'];
		$email_data = array("name" => $name);
		$email = $user['email'];

		$message = $this->load->view($email_template, $email_data, TRUE);
		$this->load->library('email');
		$this->email->from(SUPPORT_EMAIL, FROM_NAME);
		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
	}

	/**
	 *
	 * @param int $user_id
	 * @param string $password
	 */
	public function change_password($user_id, $password) {
		$this->db->update('users', ["password" => md5($password)], ["user_id" => $user_id]);
	}

	/**
	 *
	 * @param type $user_id
	 * @param type $name
	 * @param type $email
	 * @param type $work_mobile
	 * @return boolean
	 */
	public function update_user($user_id, $name, $email, $work_mobile) {
		$user = [
			"name" => $name,
			"email" => $email,
			"mobile" => $work_mobile,
			"updated_at" => DATETIME,
		];

		$this->db->update('users', $user, array(
			'user_id' => $user_id,
		));
		return TRUE;
	}

	/**
	 * [profile Used to get user details]
	 * @param  [string] $session_key [User session key]
	 * @return [array]              [User details]
	 */
	public function profile($user_id) {
//S3_SETTING
		$this->db->select('u.user_id, u.user_guid, u.email');
		$this->db->select('IFNULL(u.first_name,"") AS first_name', FALSE);
		$this->db->select('IFNULL(u.last_name,"") AS last_name', FALSE);
		$this->db->select('IFNULL(mp.name,"") AS profile', FALSE);
		$this->db->select('IFNULL(mc.name,"") AS cover', FALSE);
		$this->db->select('IFNULL(u.dob,"") AS dob', FALSE);
		$this->db->select('IFNULL(u.bio,"") AS bio', FALSE);
		$this->db->select('IFNULL(u.gender,"") AS gender', FALSE);
		$this->db->select('IFNULL(u.status,"") AS status', FALSE);
		$this->db->select('IFNULL(u.last_login_at,"") AS last_login_at', FALSE);
		$this->db->select('IFNULL(u.mobile,"") AS mobile', FALSE);
		$this->db->select('IFNULL(u.country,"") AS country', FALSE);
		$this->db->select('IFNULL(u.state,"") AS state', FALSE);
		$this->db->select('IFNULL(u.city,"") AS city', FALSE);
		$this->db->select('IFNULL(u.present_address,"") AS address_present', FALSE);
		$this->db->select('IFNULL(u.permanent_address,"") AS address_permanent', FALSE);

		$this->db->from('users AS u');
		$this->db->join('media AS mp', 'mp.media_id = u.profile_picture_id', 'LEFT');
		$this->db->join('media AS mc', 'mc.media_id = u.cover_picture_id', 'LEFT');
		$this->db->where('u.user_id', $user_id);
		$query = $this->db->get();
		$user = $query->row_array();

		// $row = $this->app->get_row('masters', 'master_guid AS id, name', ['master_id' => $user['ethnicity_id']]);

		// $user['address'] = json_decode($user['present_address']);
		if ($user['address_present'] == '') {
			$user['present_address'] = (object) [];
		} else {
			$user['present_address'] = (array) json_decode($user['address_present']);
		}

		if ($user['address_permanent'] == '') {
			$user['permanent_address'] = (object) [];
		} else {
			$user['permanent_address'] = (array) json_decode($user['address_permanent']);
		}
		$profile_picture = '';
		$cover_picture = '';
		if (!empty($user['profile'])) {
			// $profile_picture = site_url('/uploads/' . $user['profile']);
			$profile_picture = s3_url($user['profile']);
		}
		if (!empty($user['cover'])) {
			// $cover_picture = site_url('/uploads/' . $user['cover']);
			$cover_picture = s3_url($user['cover']);

		}
		$user['profile_picture'] = $profile_picture;
		$user['cover_picture'] = $cover_picture;
		unset($user['user_id']);
		unset($user['profile']);
		unset($user['cover']);
		return $user;
	}

	public function get_sub_skills($parent_id, $user_id) {
		$this->db->select('m.name, m.master_guid, m.master_id');
		$this->db->from('user_skills AS us');
		$this->db->join('masters AS m', 'm.master_id = us.skill_id');
		$this->db->where('us.user_id', $user_id);
		$this->db->where('us.parent_skill_id', $parent_id);
		$query = $this->db->get();
		$data = $query->result_array();

		$user_sub_skills = [];

		foreach ($data as $key => $value) {
			$sub_skills['id'] = $value['master_guid'];
			$sub_skills['name'] = $value['name'];
			$user_sub_skills[] = $sub_skills;
		}
		return $user_sub_skills;
	}

	public function get_user_sub_skills($user_id) {
		$this->db->select('m.name, m.parent_id');
		$this->db->from('user_skills AS us');
		$this->db->join('masters AS m', 'm.master_id = us.skill_id');
		$this->db->where('us.user_id', $user_id);
		$this->db->where('us.parent_skill_id !=', 0);
		$query = $this->db->get();
		$data = $query->result_array();

		$user_sub_skills = [];
		foreach ($data as $key => $skill) {
			// print_r($skill);die();
			$row = $this->app->get_row('masters', 'name', ['master_id' => $skill['parent_id']]);
			$parent_skill = safe_array_key($row, 'name', '');

			$user_sub_skills[] = [
				'parent_skill' => $parent_skill,
				'sub_skill' => $skill['name'],
			];
		}
		return $user_sub_skills;
	}

	public function get_user_athletes_old($user_id) {
		$this->db->distinct();
		$this->db->select('ua.athlete_guid, ua.organisation_name, ua.category AS athlete_category');
		$this->db->from('user_athletes AS ua');
		$this->db->where('ua.user_id', $user_id);
		$query = $this->db->get();
		$data = $query->result_array();

		$user_athletes = [];
		foreach ($data as $key => $value) {
			$user_athletes[] = $value;
		}
		return $user_athletes;
	}

	public function get_user_athletes($user_id) {
		$this->db->select('om.organisation_member_guid, om.organisation_id, om.athlete_category_id, om.created_at, om.updated_at, om.is_approved');
		$this->db->select('o.name AS organisation_name, o.organisation_guid AS id, o.status');
		$this->db->select('m.name AS position');
		$this->db->select('mc.name AS cover_photo');
		$this->db->select('ml.name AS logo');
		$this->db->select('otm.name AS organisation_type');
		$this->db->from('organisation_members AS om');
		$this->db->where('om.user_id', $user_id);
		// $this->db->where('o.organisation_type_id', 1);
		$this->db->join('organisations AS o', 'o.organisation_id = om.organisation_id', 'LEFT');
		$this->db->join('masters AS m', 'm.master_id = om.athlete_category_id', 'LEFT');
		$this->db->join('media AS mc', 'mc.media_id = o.cover_photo_id', 'LEFT');
		$this->db->join('media AS ml', 'ml.media_id = o.logo_id', 'LEFT');
		$this->db->join('organisation_types_master AS otm', 'otm.organisation_type_id = o.organisation_type_id', 'LEFT');
		$this->db->where('om.member_type', 'ATHLETE');
		$query = $this->db->get();
		$data = $query->result_array();
		$cover_photo = '';
		$logo = '';
		$user_athletes = [];

		foreach ($data as $key => $value) {
			$result = [];
			$result['cover_photo'] = site_url('webhost/assets/image/dummy-cover-photo.png');
			if ($value['organisation_type'] == 'COMPANY') {
				$result['logo'] = site_url('webhost/assets/image/dummy_company.png');
			} elseif ($value['organisation_type'] == 'UNIVERSITY') {
				$result['logo'] = site_url('webhost/assets/image/dummy_university.png');
			}
			// $result['logo'] = site_url('webhost/assets/image/dummy-logo.png');
			if (!empty($value['cover_photo'])) {
				// $cover_photo = site_url('/webhost/uploads/' . $value['cover_photo']);
				$cover_photo = s3_url($value['cover_photo']);
				$result['cover_photo'] = $cover_photo;
			}
			if (!empty($value['logo'])) {
				// $logo = site_url('/webhost/uploads/' . $value['logo']);
				$logo = s3_url($value['logo']);
				$result['logo'] = $logo;
			}
			$result['organisation_member_id'] = $value['organisation_member_guid'];
			$result['organisation_status'] = $value['status'];
			$result['organisation_name'] = $value['organisation_name'];
			$result['organisation_id'] = $value['id'];
			$result['athlete_category_id'] = $this->app->get_row('masters', 'master_guid AS id, name', ['master_id' => $value['athlete_category_id']]);
			$result['is_approved'] = $value['is_approved'];
			$result['created_at'] = $value['created_at'];
			$result['updated_at'] = $value['updated_at'];
			$user_athletes[] = $result;
		}
		return $user_athletes;
	}

	public function get_personal_development_responsibilities($user_id, $organisation_id) {
		$this->db->select('m.master_guid AS id, m.name');
		$this->db->from('organisation_member_personal_development_responsibilities AS ompdr');
		$this->db->where('ompdr.organisation_id', $organisation_id);
		$this->db->where('ompdr.user_id', $user_id);
		$this->db->join('masters AS m', 'm.master_id = ompdr.responsibility_id', 'LEFT');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	public function get_personal_development_responsibilities_old($user_id, $user_personal_development_id) {
		// $this->db->distinct();
		$this->db->select('updr.personal_development_responsibility_id AS id');
		$this->db->select('updr.responsibility AS name');
		$this->db->from('user_personal_development_responsibilities AS updr');
		$this->db->where('updr.personal_development_id', $user_personal_development_id);
		$this->db->where('updr.user_id', $user_id);
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	public function get_volunteering_responsibilities_old($user_id, $user_volunteering_id) {
		// $this->db->distinct();
		$this->db->select('uvr.volunteering_responsibility_id AS id');
		$this->db->select('uvr.responsibility AS name');
		$this->db->from('user_volunteering_responsibilities AS uvr');
		$this->db->where('uvr.volunteering_id', $user_volunteering_id);
		$this->db->where('uvr.user_id', $user_id);
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	public function get_volunteering_responsibilities($user_id, $organisation_id) {
		$this->db->select('m.master_guid AS id, m.name');
		$this->db->from('organisation_member_volunteering_responsibilities AS omvr');
		$this->db->where('omvr.organisation_id', $organisation_id);
		$this->db->where('omvr.user_id', $user_id);
		$this->db->join('masters AS m', 'm.master_id = omvr.responsibility_id', 'LEFT');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	public function get_responsibilities($user_id, $organisation_id) {
		$this->db->select('m.master_guid AS id, m.name');
		$this->db->from('organisation_member_responsibilities AS omr');
		$this->db->where('omr.organisation_id', $organisation_id);
		$this->db->where('omr.user_id', $user_id);
		$this->db->join('masters AS m', 'm.master_id = omr.responsibility_id', 'LEFT');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	public function get_awards($user_id, $organisation_id) {
		$this->db->select('m.master_guid AS id, m.name');
		$this->db->from('organisation_member_awards AS oma');
		$this->db->where('oma.organisation_id', $organisation_id);
		$this->db->where('oma.user_id', $user_id);
		$this->db->join('masters AS m', 'm.master_id = oma.award_id', 'LEFT');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	public function get_honours($user_id, $organisation_id) {
		$this->db->select('m.master_guid AS id, m.name');
		$this->db->from('organisation_member_honours AS omh');
		$this->db->where('omh.organisation_id', $organisation_id);
		$this->db->where('omh.user_id', $user_id);
		$this->db->join('masters AS m', 'm.master_id = omh.honour_id', 'LEFT');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	public function update_sport_story($user_id, $sport_story) {
		$array = [
			"sport_story" => $sport_story,
			"updated_at" => DATETIME,
		];
		$this->db->update('users', $array, array(
			'user_id' => $user_id,
		));
		return TRUE;
	}

	public function update_basic_info($user_id, $ethnicity, $mobile, $gender, $present_address, $permanent_address, $is_same_as_present_address, $country, $state, $city) {
		$array = [
			"ethnicity" => $ethnicity,
			"mobile" => $mobile,
			"gender" => $gender,
			"present_address" => $present_address,
			"permanent_address" => $permanent_address,
			"country" => $country,
			"state" => $state,
			"city" => $city,
			"is_same_as_present_address" => $is_same_as_present_address,
			"updated_at" => DATETIME,
		];
		$this->db->update('users', $array, array(
			'user_id' => $user_id,
		));
		return TRUE;
	}

	/**
	 * [get_personal_email description]
	 * @param  [int] $user_id [User id]
	 * @return [array]          [array of personal emails]
	 */
	public function get_personal_email($user_id, $default_personal_email = NULL) {
		$where = [
			"user_id" => $user_id,
			"type" => "PERSONAL",
		];
		$rows = $this->app->get_rows('user_emails', 'user_email_id, email, status', $where);
		$personal_email = array("email" => "", "status" => "");
		if (count($rows) > 0) {
			$personal_email = $rows;
		}
		return $rows;
	}

	/**
	 * [send_email_verification description]
	 * @param  [int] $user_email_id [user email id]
	 */
	public function send_email_verification($email, $user_id) {
		$this->load->helper('email');
		$this->db->update('verifications', ['status' => 'EXPIRED'], [
			'user_id' => $user_id,
			'verification_target' => $email,
			'verification_type' => 'EMAIL_VERIFICATION_CODE',
			'status' => 'ACTIVE',
		]);

		$unique_code = unique_random_string('verifications', 'code', ['status' => 'ACTIVE'], 'numeric', 5);

		$this->db->insert('verifications', [
			"verification_guid" => get_guid(),
			'verification_target' => $email,
			"verification_type" => 'EMAIL_VERIFICATION_CODE',
			"user_id" => $user_id,
			"code" => $unique_code,
			"created_at" => DATETIME,
		]);

		$subject = 'Email Verification';
		$user = $this->app->get_row('users', 'name', ['user_id' => $user_id]);

		$email_data['name'] = $user['name'];
		$email_data['unique_code'] = $unique_code;
		$email_template = "emailer/email_verification";
		$message = $this->load->view($email_template, $email_data, TRUE);

		$this->load->library('email');
		$this->email->from(SUPPORT_EMAIL, FROM_NAME);
		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
	}

	/**
	 * [get_emails used to get user verified emails]
	 * @param  [int] $user_id [User id]
	 * @return [array]          [array of emails]
	 */
	public function get_emails($user_id) {
		$where = [
			"user_id" => $user_id,
			"status" => "ACTIVE",
		];
		$rows = $this->app->get_rows('user_emails', 'user_email_id, email, type', $where);
		$user_emails = [];
		if (count($rows) > 0) {
			$user_emails = $rows;
		}
		return $user_emails;
	}

	/**
	 * [update_login_email used to update user login email]
	 * @param  [type] $user_id       [description]
	 * @param  [type] $user_email_id [description]
	 * @return [type]                [description]
	 */
	public function update_login_email($user_id, $user_email_id) {
		if (!empty($user_email_id)) {
			$this->db->update('user_emails', [
				'type' => 'PERSONAL'], [
				'user_id' => $user_id,
				'type' => 'LOGIN',
			]);

			$this->db->update('user_emails', [
				'type' => 'LOGIN', 'updated_at' => DATETIME], [
				'user_id' => $user_id,
				'user_email_id' => $user_email_id,
			]);
		}
	}

	/**
	 * [delete delete user account]
	 * @param  [type] $user_id [description]
	 * @return [type]          [description]
	 */
	public function delete($user_id) {
		$this->db->update('users', [
			'status' => 'DELETED'], [
			'user_id' => $user_id,
		]);
	}

	// public function add_company($user_id, $name, $website) {
	// 	$guid = get_guid();
	// 	$album_array = [
	// 		"user_id" => $user_id,
	// 		"user_album_guid" => $guid,
	// 		"name" => $name,
	// 		"description" => $description,
	// 		"media_id" => $media_id,
	// 		"cover_media_id" => $cover_media_id,
	// 		"status" => 'ACTIVE',
	// 		"created_at" => DATETIME,
	// 		"updated_at" => DATETIME
	// 	];

	// 	$this->db->insert("user_albums", $album_array);
	// 	$user_album_id = $this->db->insert_id();
	// 	return $user_album_id;
	//    }

	public function save_preferred_job_locations($user_id, $preferred_job_locations) {
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_preferred_job_locations');

		foreach ($preferred_job_locations as $preferred_job_location) {
			$exist_data = $this->app->get_row('user_preferred_job_locations', 'job_location_id', [
				'user_id' => $user_id,
				"country" => strtolower($preferred_job_location['country']),
				"state" => strtolower($preferred_job_location['state']),
				"city" => strtolower($preferred_job_location['city']),
			]);

			if (!$exist_data['job_location_id']) {
				$this->db->insert('user_preferred_job_locations', [
					'user_id' => $user_id,
					"country" => strtolower($preferred_job_location['country']),
					"state" => strtolower($preferred_job_location['state']),
					"city" => strtolower($preferred_job_location['city']),
				]);
			}
		}
		return TRUE;
	}

	public function save_user_job_types($user_id, $user_job_types) {
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_job_types');

		foreach ($user_job_types as $user_job_type) {
			$job_type_data = $this->app->get_row('masters', 'master_id', ['master_guid' => $user_job_type]);
			$job_type_id = $job_type_data['master_id'];

			$this->db->insert('user_job_types', [
				'job_type_id' => $job_type_id,
				'user_id' => $user_id,
			]);
		}
		return TRUE;
	}

	public function save_departments($user_id, $departments) {
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_departments');

		foreach ($departments as $department) {
			$department_data = $this->app->get_row('masters', 'master_id', ['master_guid' => $department]);
			$department_id = $department_data['master_id'];

			$this->db->insert('user_departments', [
				'department_id' => $department_id,
				'user_id' => $user_id,
			]);
		}
		return TRUE;
	}

	public function add_values_old($user_id, $values) {
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_values');

		foreach ($values as $value) {
			$values_data = $this->app->get_row('masters', 'master_id', ['master_guid' => $value]);
			$value_id = $values_data['master_id'];

			$this->db->insert('user_values', [
				'value_id' => $value_id,
				'user_id' => $user_id,
			]);
		}
		return TRUE;
	}

	public function add_values($user_id, $values) {
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_values');

		foreach ($values as $value) {
			$value_id = $this->get_value_id($value);
			// $values_data = $this->app->get_row('masters', 'master_id', ['master_guid' => $value]);
			// $value_id = $values_data['master_id'];

			$row = $this->app->get_row('user_values', 'user_id', [
				'value_id' => $value_id,
				'user_id' => $user_id,
			]);
			if (empty($row)) {
				$this->db->insert('user_values', [
					'value_id' => $value_id,
					'user_id' => $user_id,
				]);
			}
		}
		return TRUE;
	}

	public function get_value_id($value) {
		$this->db->select('m.master_id, m.name');
		$this->db->from('masters AS m');
		$this->db->where('type', 'VALUE');
		$query = $this->db->get();
		$result = $query->result_array();

		$temp_value_id = NULL;
		foreach ($result as $key => $val) {
			if (strtolower($val['name']) == strtolower($value)) {
				$temp_value_id = $val['master_id'];
			}
		}

		if ($temp_value_id == NULL) {
			$master_name = strtolower($value);
			$name = ucwords($master_name);
			$master_data = [
				"master_guid" => url_title($value, "dash", TRUE),
				"name" => $name,
				"type" => 'VALUE',
			];
			$this->db->insert("masters", $master_data);
			$master_id = $this->db->insert_id();
			return $master_id;
		} else {
			return $temp_value_id;
		}
	}

	public function add_skills_old($user_id, $skills) {
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_skills');

		foreach ($skills as $skill) {
			$skills_data = $this->app->get_row('masters', 'master_id', ['master_guid' => $skill['id']]);
			$skill_id = safe_array_key($skills_data, 'master_id', '');
			if (!empty($skill_id)) {
				$this->db->insert('user_skills', [
					'skill_id' => $skill_id,
					'user_id' => $user_id,
				]);
				$sub_skills = safe_array_key($skill, 'sub_skills', []);
				foreach ($sub_skills as $sub_skill) {
					$sub_skills_data = $this->app->get_row('masters', 'master_id', ['master_guid' => $sub_skill['id']]);
					$sub_skill_id = $sub_skills_data['master_id'];

					$this->db->insert('user_skills', [
						'skill_id' => $sub_skill_id,
						'user_id' => $user_id,
						'parent_skill_id' => $skill_id,
					]);
				}
			}

		}
		return TRUE;
	}

	public function add_skills($user_id, $skills) {
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_skills');

		foreach ($skills as $skill) {
			$skill_id = $this->get_skill_id($skill['name']);
			if (!empty($skill_id)) {
				$this->db->insert('user_skills', [
					'skill_id' => $skill_id,
					'user_id' => $user_id,
				]);
				$sub_skills = safe_array_key($skill, 'sub_skills', []);
				foreach ($sub_skills as $sub_skill) {
					$sub_skill_id = $this->get_sub_skill_id($sub_skill['name'], $skill_id);
					$this->db->insert('user_skills', [
						'skill_id' => $sub_skill_id,
						'user_id' => $user_id,
						'parent_skill_id' => $skill_id,
					]);
				}
			}
		}
		return TRUE;
	}

	public function get_skill_id($skill) {
		$this->db->select('m.master_id, m.name');
		$this->db->from('masters AS m');
		$this->db->where('type', 'SKILL');
		$query = $this->db->get();
		$result = $query->result_array();

		$temp_skill_id = NULL;
		foreach ($result as $key => $value) {
			if (strtolower($value['name']) == strtolower($skill)) {
				$temp_skill_id = $value['master_id'];
			}
		}

		if ($temp_skill_id == NULL) {
			$master_name = strtolower($skill);
			$name = ucwords($master_name);
			$master_data = [
				"master_guid" => url_title($skill, "dash", TRUE),
				"name" => $name,
				"type" => 'SKILL',
			];
			$this->db->insert("masters", $master_data);
			$master_id = $this->db->insert_id();
			return $master_id;
		} else {
			return $temp_skill_id;
		}
	}

	public function get_sub_skill_id($sub_skill, $skill_id) {
		$this->db->select('m.master_id, m.name');
		$this->db->from('masters AS m');
		$this->db->where('type', 'SKILL');
		$query = $this->db->get();
		$result = $query->result_array();

		$temp_sub_skill_id = NULL;
		foreach ($result as $key => $value) {
			if (strtolower($value['name']) == strtolower($sub_skill)) {
				$temp_sub_skill_id = $value['master_id'];
			}
		}

		if ($temp_sub_skill_id == NULL) {
			$master_name = strtolower($sub_skill);
			$name = ucwords($master_name);
			$master_data = [
				"master_guid" => url_title($sub_skill, "dash", TRUE),
				"name" => $name,
				"type" => 'SKILL',
				"parent_id" => $skill_id,
			];
			$this->db->insert("masters", $master_data);
			$master_id = $this->db->insert_id();
			return $master_id;
		} else {
			return $temp_sub_skill_id;
		}
	}

	public function complete_profile($user_id, $is_work_in_sports_industry, $is_looking_for_job, $open_for_all_departments) {
		$array = [
			"is_work_in_sports_industry" => $is_work_in_sports_industry,
			"is_looking_for_job" => $is_looking_for_job,
			"open_for_all_departments" => $open_for_all_departments,
			"is_onboarding_done" => "YES",
			"updated_at" => DATETIME,
		];
		$this->db->update('users', $array, array(
			'user_id' => $user_id,
		));
		return TRUE;
	}

	public function save_education($user_id, $email, $organisation_id, $degree, $in_progress, $start_date, $end_date, $gpa, $student_id, $member_type) {
		$this->db->insert('organisation_members', [
			"organisation_member_guid" => get_guid(),
			'organisation_id' => $organisation_id,
			'user_id' => $user_id,
			'email' => $email,
			'degree' => $degree,
			'in_progress' => $in_progress,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'gpa' => $gpa,
			'student_id' => $student_id,
			'role' => 'MEMBER',
			'member_type' => $member_type,
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		]);

		$result = (object) [];
		$organisation_member_id = $this->db->insert_id();
		$row = $this->app->get_row('organisation_members', 'organisation_member_guid', ['organisation_member_id' => $organisation_member_id]);
		$result = $row;
		return $result;
		// return $organisation_id;
	}

	public function update_education($organisation_member_guid, $organisation_id, $degree, $in_progress, $start_date, $end_date, $gpa, $student_id, $member_type) {
		$data = [
			'organisation_id' => $organisation_id,
			'degree' => $degree,
			'in_progress' => $in_progress,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'gpa' => $gpa,
			'student_id' => $student_id,
			'member_type' => $member_type,
			"updated_at" => DATETIME,
		];

		$this->db->update('organisation_members', $data, array(
			'organisation_member_guid' => $organisation_member_guid,
		));
		return TRUE;
	}

	public function delete_education($organisation_member_guid) {
		$this->db->where('organisation_member_guid', $organisation_member_guid);
		$this->db->delete('organisation_members');
		return TRUE;
	}

	public function add_university($user_id, $organisation_guid, $organisation_type_id, $name, $website) {
		$data = [
			"organisation_guid" => $organisation_guid,
			"organisation_type_id" => $organisation_type_id,
			"name" => $name,
			"website" => $website,
			"created_by" => $user_id,
			"updated_by" => $user_id,
			"status" => 'ACTIVE',
			"is_expired" => 'NO',
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		];

		$this->db->insert("organisations", $data);
		$organisation_id = $this->db->insert_id();

		for ($i = 1; $i <= 3; $i++) {
			$data2 = [
				'organisation_job_spot_guid' => get_guid(),
				'organisation_id' => $organisation_id,
				'job_spot_id' => $i,
				'available_spots' => 30,
				'created_at' => DATETIME,
				'updated_at' => DATETIME,
			];
			$this->db->insert('organisation_job_spots', $data2);
		}

		return $organisation_id;
	}

	public function add_designation($designation) {
		$result = $this->app->get_rows('masters', 'master_id, name', ['type' => 'DESIGNATION']);
		$designation_id = NULL;
		foreach ($result as $key => $value) {
			if (strtolower($value['name']) == strtolower($designation)) {
				$designation_id = $value['master_id'];
			}
		}

		if ($designation_id == NULL) {
			$master_name = strtolower($designation);
			$name = ucwords($master_name);
			$master_data = [
				"master_guid" => url_title($designation, "dash", TRUE),
				"name" => $name,
				"type" => 'DESIGNATION',
			];
			$this->db->insert("masters", $master_data);
			$master_id = $this->db->insert_id();
			return $master_id;
		} else {
			return $designation_id;
		}
	}

	public function add_professional_experience($user_id, $email, $organisation_id, $designation_id, $currently_working, $start_date, $end_date, $official_email, $country, $state, $city) {
		$this->db->insert('organisation_members', [
			"organisation_member_guid" => get_guid(),
			'organisation_id' => $organisation_id,
			'user_id' => $user_id,
			'email' => $email,
			'designation' => $designation_id,
			'in_progress' => $currently_working,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'official_email' => $official_email,
			'country' => $country,
			'state' => $state,
			'city' => $city,
			'role' => 'MEMBER',
			'member_type' => 'STAFF',
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		]);

		$organisation_id = $this->db->insert_id();
		return $organisation_id;
	}

	public function update_professional_experience($organisation_member_guid, $organisation_id, $designation_id, $currently_working, $start_date, $end_date, $official_email, $country, $state, $city) {
		$data = [
			'organisation_id' => $organisation_id,
			'designation' => $designation_id,
			'in_progress' => $currently_working,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'official_email' => $official_email,
			'country' => $country,
			'state' => $state,
			'city' => $city,
			"updated_at" => DATETIME,
		];

		$this->db->update('organisation_members', $data, array(
			'organisation_member_guid' => $organisation_member_guid,
		));
		return TRUE;
	}

	public function delete_professional_experience($organisation_member_guid) {
		$this->db->where('organisation_member_guid', $organisation_member_guid);
		$this->db->delete('organisation_members');
		return TRUE;
	}

	public function add_company($user_id, $organisation_guid, $organisation_type_id, $name, $website) {
		$data = [
			"organisation_guid" => $organisation_guid,
			"organisation_type_id" => $organisation_type_id,
			"name" => $name,
			"website" => $website,
			"created_by" => $user_id,
			"updated_by" => $user_id,
			"status" => 'ACTIVE',
			"is_expired" => 'NO',
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		];

		$this->db->insert("organisations", $data);
		$organisation_id = $this->db->insert_id();
		for ($i = 1; $i <= 3; $i++) {
			$data2 = [
				'organisation_job_spot_guid' => get_guid(),
				'organisation_id' => $organisation_id,
				'job_spot_id' => $i,
				'available_spots' => 30,
				'created_at' => DATETIME,
				'updated_at' => DATETIME,
			];
			$this->db->insert('organisation_job_spots', $data2);
		}
		return $organisation_id;
	}

	public function add_awards($user_id, $awards, $organisation_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('organisation_id', $organisation_id);
		$this->db->delete('organisation_member_awards');

		foreach ($awards as $award) {
			$award_id = $this->get_award_id($award);
			$row = $this->app->get_row('organisation_member_awards', 'user_id', [
				'award_id' => $award_id,
				'user_id' => $user_id,
				'organisation_id' => $organisation_id,
			]);

			if (empty($row)) {
				$this->db->insert('organisation_member_awards', [
					'award_id' => $award_id,
					'user_id' => $user_id,
					'organisation_id' => $organisation_id,
				]);
			}
		}
		return TRUE;
	}

	public function get_award_id($award) {
		$this->db->select('m.master_id, m.name');
		$this->db->from('masters AS m');
		$this->db->where('type', 'AWARD');
		$query = $this->db->get();
		$result = $query->result_array();

		$temp_award_id = NULL;
		foreach ($result as $key => $value) {
			if (strtolower($value['name']) == strtolower($award)) {
				$temp_award_id = $value['master_id'];
			}
		}

		if ($temp_award_id == NULL) {
			$master_name = strtolower($award);
			$name = ucwords($master_name);
			$master_data = [
				"master_guid" => url_title($award, "dash", TRUE),
				"name" => $name,
				"type" => 'AWARD',
			];
			$this->db->insert("masters", $master_data);
			$master_id = $this->db->insert_id();
			return $master_id;
		} else {
			return $temp_award_id;
		}
	}

	public function delete_awards($user_id, $organisation_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('organisation_id', $organisation_id);
		$this->db->delete('organisation_member_awards');
		return TRUE;
	}

	public function add_honours($user_id, $honours, $organisation_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('organisation_id', $organisation_id);
		$this->db->delete('organisation_member_honours');

		foreach ($honours as $honour) {
			$honour_id = $this->get_honour_id($honour);
			$row = $this->app->get_row('organisation_member_honours', 'user_id', [
				'honour_id' => $honour_id,
				'user_id' => $user_id,
				'organisation_id' => $organisation_id,
			]);

			$this->db->insert('organisation_member_honours', [
				'honour_id' => $honour_id,
				'user_id' => $user_id,
				'organisation_id' => $organisation_id,
			]);
		}
		return TRUE;
	}

	public function get_honour_id($honour) {
		$this->db->select('m.master_id, m.name');
		$this->db->from('masters AS m');
		$this->db->where('type', 'HONOUR');
		$query = $this->db->get();
		$result = $query->result_array();

		$temp_honour_id = NULL;
		foreach ($result as $key => $value) {
			if (strtolower($value['name']) == strtolower($honour)) {
				$temp_honour_id = $value['master_id'];
			}
		}

		if ($temp_honour_id == NULL) {
			$master_name = strtolower($honour);
			$name = ucwords($master_name);
			$master_data = [
				"master_guid" => url_title($honour, "dash", TRUE),
				"name" => $name,
				"type" => 'HONOUR',
			];
			$this->db->insert("masters", $master_data);
			$master_id = $this->db->insert_id();
			return $master_id;
		} else {
			return $temp_honour_id;
		}
	}

	public function delete_honours($user_id, $organisation_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('organisation_id', $organisation_id);
		$this->db->delete('organisation_member_honours');
		return TRUE;
	}

	public function add_responsibilities($user_id, $responsibilities, $organisation_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('organisation_id', $organisation_id);
		$this->db->delete('organisation_member_responsibilities');

		foreach ($responsibilities as $responsibility) {
			$responsibility_id = $this->get_responsibility_id($responsibility);
			$row = $this->app->get_row('organisation_member_responsibilities', 'user_id', [
				'responsibility_id' => $responsibility_id,
				'user_id' => $user_id,
				'organisation_id' => $organisation_id,
			]);

			$this->db->insert('organisation_member_responsibilities', [
				'responsibility_id' => $responsibility_id,
				'user_id' => $user_id,
				'organisation_id' => $organisation_id,
			]);
		}
		return TRUE;
	}

	public function get_responsibility_id($responsibility) {
		$this->db->select('m.master_id, m.name');
		$this->db->from('masters AS m');
		$this->db->where('type', 'RESPONSIBILITY');
		$query = $this->db->get();
		$result = $query->result_array();

		$temp_responsibility_id = NULL;
		foreach ($result as $key => $value) {
			if (strtolower($value['name']) == strtolower($responsibility)) {
				$temp_responsibility_id = $value['master_id'];
			}
		}

		if ($temp_responsibility_id == NULL) {
			$master_name = strtolower($responsibility);
			$name = ucwords($master_name);
			$master_data = [
				"master_guid" => url_title($responsibility, "dash", TRUE),
				"name" => $name,
				"type" => 'RESPONSIBILITY',
			];
			$this->db->insert("masters", $master_data);
			$master_id = $this->db->insert_id();
			return $master_id;
		} else {
			return $temp_responsibility_id;
		}
	}

	public function delete_responsibilities($user_id, $organisation_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('organisation_id', $organisation_id);
		$this->db->delete('organisation_member_responsibilities');
		return TRUE;
	}

	public function add_volunteering_old($user_id, $organisation_name, $designation, $currently_working, $start_date, $end_date, $country, $state, $city, $address_1, $address_2) {
		$this->db->insert('user_volunteerings', [
			"volunteering_guid" => get_guid(),
			'user_id' => $user_id,
			'organisation_name' => $organisation_name,
			'designation' => $designation,
			'currently_working' => $currently_working,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'country' => $country,
			'state' => $state,
			'city' => $city,
			'address_1' => $address_1,
			'address_2' => $address_2,
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		]);

		$volunteering_id = $this->db->insert_id();
		return $volunteering_id;
	}

	public function add_volunteering($user_id, $email, $organisation_id, $designation, $currently_working, $start_date, $end_date, $country, $state, $city) {
		$this->db->insert('organisation_members', [
			"organisation_member_guid" => get_guid(),
			'organisation_id' => $organisation_id,
			'user_id' => $user_id,
			'email' => $email,
			// 'designation' => $designation_id,
			'volunteering_position' => $designation,
			'in_progress' => $currently_working,
			'start_date' => $start_date,
			'end_date' => $end_date,
			// 'official_email' => $official_email,
			'country' => $country,
			'state' => $state,
			'city' => $city,
			'role' => 'MEMBER',
			'member_type' => 'VOLUNTEER',
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		]);

		$organisation_id = $this->db->insert_id();
		return $organisation_id;
	}

	public function add_volunteering_responsibilities($user_id, $responsibilities, $organisation_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('organisation_id', $organisation_id);
		$this->db->delete('organisation_member_volunteering_responsibilities');

		foreach ($responsibilities as $responsibility) {
			$responsibility_id = $this->get_responsibility_id($responsibility);
			$row = $this->app->get_row('organisation_member_volunteering_responsibilities', 'user_id', [
				'responsibility_id' => $responsibility_id,
				'user_id' => $user_id,
				'organisation_id' => $organisation_id,
			]);

			$this->db->insert('organisation_member_volunteering_responsibilities', [
				'responsibility_id' => $responsibility_id,
				'user_id' => $user_id,
				'organisation_id' => $organisation_id,
			]);
		}
		return TRUE;
	}

	public function update_volunteering($organisation_member_guid, $organisation_id, $designation, $currently_working, $start_date, $end_date, $country, $state, $city) {
		$data = [
			'organisation_id' => $organisation_id,
			// 'designation' => $designation_id,
			'volunteering_position' => $designation,
			'in_progress' => $currently_working,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'country' => $country,
			'state' => $state,
			'city' => $city,
			"updated_at" => DATETIME,
		];

		$this->db->update('organisation_members', $data, array(
			'organisation_member_guid' => $organisation_member_guid,
		));
		return TRUE;
	}

	public function delete_volunteering($organisation_member_guid) {
		$this->db->where('organisation_member_guid', $organisation_member_guid);
		$this->db->delete('organisation_members');
		return TRUE;
	}

	public function delete_volunteering_responsibilities($user_id, $organisation_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('organisation_id', $organisation_id);
		$this->db->delete('organisation_member_volunteering_responsibilities');
		return TRUE;
	}

	public function update_volunteering_old($user_id, $volunteering_guid, $organisation_name, $designation, $currently_working, $start_date, $end_date, $country, $state, $city, $address_1, $address_2) {
		$data = [
			'organisation_name' => $organisation_name,
			'designation' => $designation,
			'currently_working' => $currently_working,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'country' => $country,
			'state' => $state,
			'city' => $city,
			'address_1' => $address_1,
			'address_2' => $address_2,
			"updated_at" => DATETIME,
		];

		$this->db->update('user_volunteerings', $data, array(
			'volunteering_guid' => $volunteering_guid,
			'user_id' => $user_id,
		));
		return TRUE;
	}

	public function add_volunteering_responsibilities_old($user_id, $responsibilities, $volunteering_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('volunteering_id', $volunteering_id);
		$this->db->delete('user_volunteering_responsibilities');

		foreach ($responsibilities as $responsibility) {
			// $responsibilities_data = $this->app->get_row('masters', 'master_id', ['master_guid' => $responsibility]);
			// $responsibility_id = $responsibilities_data['master_id'];
			$this->db->insert('user_volunteering_responsibilities', [
				// 'volunteering_responsibility_guid' => url_title($responsibility, "dash", TRUE),
				'responsibility' => $responsibility,
				'user_id' => $user_id,
				'volunteering_id' => $volunteering_id,
			]);
		}
		return TRUE;
	}

	public function update_user_represent_as_an_athlete($user_id, $represent_as_an_athlete) {
		$data = [
			'represent_as_an_athlete' => $represent_as_an_athlete,
			"updated_at" => DATETIME,
		];
		$this->db->update('users', $data, array(
			'user_id' => $user_id,
		));
		return TRUE;
	}

	public function add_as_an_athlete_old($user_id, $organisation_name, $category) {
		$this->db->insert('user_athletes', [
			"athlete_guid" => get_guid(),
			'user_id' => $user_id,
			'organisation_name' => $organisation_name,
			'category' => $category,
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		]);

		$athlete_id = $this->db->insert_id();
		return $athlete_id;
	}

	public function add_as_an_athlete($user_id, $email, $organisation_id, $athlete_category_id) {
		$this->db->insert('organisation_members', [
			"organisation_member_guid" => get_guid(),
			'organisation_id' => $organisation_id,
			'user_id' => $user_id,
			'email' => $email,
			'athlete_category_id' => $athlete_category_id,
			'role' => 'MEMBER',
			'member_type' => 'ATHLETE',
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		]);

		$organisation_member_id = $this->db->insert_id();
		return $organisation_member_id;
	}

	public function update_as_an_athlete($organisation_member_guid, $organisation_id, $athlete_category_id) {
		$data = [
			'organisation_id' => $organisation_id,
			'athlete_category_id' => $athlete_category_id,
			"updated_at" => DATETIME,
		];

		$this->db->update('organisation_members', $data, array(
			'organisation_member_guid' => $organisation_member_guid,
		));
		return TRUE;
	}

	public function update_as_an_athlete_old($athlete_guid, $organisation_name, $category) {
		$data = [
			'organisation_name' => $organisation_name,
			'category' => $category,
			"updated_at" => DATETIME,
		];
		$this->db->update('user_athletes', $data, array(
			'athlete_guid' => $athlete_guid,
		));
		return TRUE;
	}

	public function delete_user_athlete($organisation_member_guid) {
		$this->db->where('organisation_member_guid', $organisation_member_guid);
		$this->db->delete('organisation_members');
		return TRUE;
	}

	public function delete_user_athlete_old($athlete_guid) {
		$this->db->where('athlete_guid', $athlete_guid);
		$this->db->delete('user_athletes');
	}

	public function add_personal_development_old($user_id, $certificate_name, $year_of_completion, $organisation_name) {
		$this->db->insert('user_personal_developments', [
			"personal_development_guid" => get_guid(),
			'user_id' => $user_id,
			'certificate_name' => $certificate_name,
			'year_of_completion' => $year_of_completion,
			'organisation_name' => $organisation_name,
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		]);

		$personal_development_id = $this->db->insert_id();
		return $personal_development_id;
	}

	public function add_personal_development($user_id, $email, $certificate_name, $year_of_completion, $organisation_id) {
		$this->db->insert('organisation_members', [
			"organisation_member_guid" => get_guid(),
			'organisation_id' => $organisation_id,
			'user_id' => $user_id,
			'email' => $email,
			'certificate_name' => $certificate_name,
			'year_of_completion' => $year_of_completion,
			'role' => 'MEMBER',
			'member_type' => 'PERSONAL_DEVELOPMENT',
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		]);

		$organisation_member_id = $this->db->insert_id();
		return $organisation_member_id;
	}

	public function update_personal_development_old($personal_development_guid, $certificate_name, $year_of_completion, $organisation_name) {
		$data = [
			'certificate_name' => $certificate_name,
			'year_of_completion' => $year_of_completion,
			'organisation_name' => $organisation_name,
			"updated_at" => DATETIME,
		];
		$this->db->update('user_personal_developments', $data, array(
			'personal_development_guid' => $personal_development_guid,
		));
		return TRUE;
	}

	public function update_personal_development($organisation_member_guid, $certificate_name, $year_of_completion, $organisation_id) {
		$data = [
			'organisation_id' => $organisation_id,
			'certificate_name' => $certificate_name,
			'year_of_completion' => $year_of_completion,
			"updated_at" => DATETIME,
		];

		$this->db->update('organisation_members', $data, array(
			'organisation_member_guid' => $organisation_member_guid,
		));
		return TRUE;
	}

	public function delete_personal_development($organisation_member_guid) {
		$this->db->where('organisation_member_guid', $organisation_member_guid);
		$this->db->delete('organisation_members');
		return TRUE;
	}

	public function delete_personal_development_old($personal_development_guid) {
		$this->db->where('personal_development_guid', $personal_development_guid);
		$this->db->delete('user_personal_developments');
	}

	public function add_personal_development_responsibilities($user_id, $responsibilities, $organisation_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('organisation_id', $organisation_id);
		$this->db->delete('organisation_member_personal_development_responsibilities');

		foreach ($responsibilities as $responsibility) {
			$responsibility_id = $this->get_responsibility_id($responsibility);
			$row = $this->app->get_row('organisation_member_personal_development_responsibilities', 'user_id', [
				'responsibility_id' => $responsibility_id,
				'user_id' => $user_id,
				'organisation_id' => $organisation_id,
			]);

			$this->db->insert('organisation_member_personal_development_responsibilities', [
				'responsibility_id' => $responsibility_id,
				'user_id' => $user_id,
				'organisation_id' => $organisation_id,
			]);
		}
		return TRUE;
	}

	public function add_personal_development_responsibilities_old($user_id, $responsibilities, $personal_development_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('personal_development_id', $personal_development_id);
		$this->db->delete('user_personal_development_responsibilities');

		foreach ($responsibilities as $responsibility) {
			// $responsibilities_data = $this->app->get_row('masters', 'master_id', ['master_guid' => $responsibility]);
			// $responsibility_id = $responsibilities_data['master_id'];

			$this->db->insert('user_personal_development_responsibilities', [
				'responsibility' => $responsibility,
				'user_id' => $user_id,
				'personal_development_id' => $personal_development_id,
			]);
		}
		return TRUE;
	}

	public function delete_personal_development_responsibilities($user_id, $organisation_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('organisation_id', $organisation_id);
		$this->db->delete('organisation_member_personal_development_responsibilities');
		return TRUE;
	}

	public function delete_personal_development_responsibilities_old($user_id, $personal_development_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('personal_development_id', $personal_development_id);
		$this->db->delete('user_personal_development_responsibilities');
		return TRUE;
	}

	public function make_as_owner($member_user_id, $organisation_id) {
		$data = [
			'role' => 'OWNER',
			'is_approved' => 'YES',
			'updated_at' => DATETIME,
		];

		$this->db->update('organisation_members', $data, array(
			'organisation_id' => $organisation_id,
			'user_id' => $member_user_id,
		));
		return TRUE;
	}

	public function verify_association($member_user_id, $organisation_id) {
		$data = [
			'is_approved' => 'YES',
			'updated_at' => DATETIME,
		];

		$this->db->update('organisation_members', $data, array(
			'organisation_id' => $organisation_id,
			'user_id' => $member_user_id,
		));
		return TRUE;
	}

	public function delete_volunteering_old($volunteering_guid) {
		$this->db->where('volunteering_guid', $volunteering_guid);
		$this->db->delete("user_volunteerings");
	}

	public function delete_volunteering_responsibilities_old($user_id, $volunteering_id) {
		$this->db->where('volunteering_id', $volunteering_id);
		$this->db->where('user_id', $user_id);
		$this->db->delete("user_volunteering_responsibilities");
	}

	public function create_user_following($user_id, $type, $_id) {
		$user_following_guid = get_guid();
		$user_following_data = array(
			"user_following_guid" => $user_following_guid,
			"user_id" => $user_id,
			"type" => $type,
			"type_id" => $_id,
			"created_at" => DATETIME,
		);
		$this->db->insert('user_followings', $user_following_data);
		$user_following_id = $this->db->insert_id();
		return $user_following_id;
	}

	public function delete_user_following($user_following_guid) {
		$this->db->where('user_following_guid', $user_following_guid);
		$this->db->delete('user_followings');
	}

	public function create_user_support($user_id, $type, $_id) {
		$this->db->insert('user_supports', [
			"user_support_guid" => get_guid(),
			"user_id" => $user_id,
			"type" => $type,
			"type_id" => $_id,
			"created_at" => DATETIME,
		]);
		$user_support_id = $this->db->insert_id();
		return $user_support_id;
	}

	public function delete_user_support($user_support_guid) {
		$this->db->where('user_support_guid', $user_support_guid);
		$this->db->delete('user_supports');
	}

	public function update_expire_all_star_membership_reminder($user_guid, $days) {
		$data = [
			'expire_notified_' . $days => 'YES',
			'expire_notified_' . $days . '_at' => DATETIME,
			'updated_at' => DATETIME,
		];
		$this->db->update('users', $data, ['user_guid' => $user_guid]);
	}

	public function update_device_token($session_key, $device_type_id, $device_token) {
		$data = [
			'device_type_id' => $device_type_id,
			'device_token' => $device_token,
		];
		$this->db->update('user_login_sessions', $data, ['session_key' => $session_key]);
	}

	public function account_delete($user_id) {
		$this->db->trans_start();
		$this->db->delete("jobs", ['user_id' => $user_id]);
		$this->db->delete("media", ['user_id' => $user_id]);
		$this->db->delete("organisation_events", ['user_id' => $user_id]);
		$this->db->delete("organisation_members", ['user_id' => $user_id]);
		$this->db->delete("organisation_member_awards", ['user_id' => $user_id]);
		$this->db->delete("organisation_member_honours", ['user_id' => $user_id]);
		$this->db->delete("organisation_member_responsibilities", ['user_id' => $user_id]);
		$this->db->delete("organisation_member_volunteering_responsibilities", ['user_id' => $user_id]);
		$this->db->delete("organisation_programs", ['user_id' => $user_id]);
		$this->db->delete("user_applied_jobs", ['user_id' => $user_id]);
		$this->db->delete("user_athletes", ['user_id' => $user_id]);
		$this->db->delete("user_departments", ['user_id' => $user_id]);
		$this->db->delete("user_followings", ['user_id' => $user_id]);
		$this->db->delete("user_jobs", ['user_id' => $user_id]);
		$this->db->delete("user_job_types", ['user_id' => $user_id]);
		$this->db->delete("user_personal_developments", ['user_id' => $user_id]);
		$this->db->delete("user_personal_development_responsibilities", ['user_id' => $user_id]);
		$this->db->delete("user_preferred_job_locations", ['user_id' => $user_id]);
		$this->db->delete("user_skills", ['user_id' => $user_id]);
		$this->db->delete("user_values", ['user_id' => $user_id]);
		$this->db->delete("user_volunteerings", ['user_id' => $user_id]);
		$this->db->delete("users", ['user_id' => $user_id]);
		$this->db->trans_complete();
	}

	public function create_transaction($stripe_transaction_id, $user_plan_id, $user_id, $amount, $transaction_details, $status) {
		$data = [
			'payment_gatway_transaction_id' => $stripe_transaction_id,
			'user_plan_id' => $user_plan_id,
			'user_id' => $user_id,
			'amount' => $amount,
			'transaction_details' => $transaction_details,
			'transaction_date' => DATETIME,
			'status' => $status,
		];
		$this->db->insert('transactions', $data);
		$transaction_id = $this->db->insert_id();
		return $transaction_id;
	}

	// public function create_user_subscription($subscription_id, $pricing_plan_id, $user_id, $subscription_details, $subscription_status) {
	// 	$data = [
	// 		'user_subscription_guid' => get_guid(),
	// 		'user_id' => $user_id,
	// 		'subscription_id' => $subscription_id,
	// 		'pricing_plan_id' => $pricing_plan_id,
	// 		'subscription_details' => $subscription_details,
	// 		'subscription_status' => $subscription_status,
	// 		'created_at' => DATETIME
	// 	];
	// 	$this->db->insert('user_subscriptions', $data);
	// 	$transaction_id = $this->db->insert_id();
	// 	return $transaction_id;
	// }

	public function create_user_subscription($user_id, $stripe_subscription_id, $stripe_pricing_plan_id, $pricing_plan_id, $subscription_details, $subscription_status) {
		$data = [
			'subscription_guid' => get_guid(),
			'user_id' => $user_id,
			'stripe_subscription_id' => $stripe_subscription_id,
			'stripe_pricing_plan_id' => $stripe_pricing_plan_id,
			'pricing_plan_id' => $pricing_plan_id,
			'subscription_details' => $subscription_details,
			'subscription_status' => $subscription_status,
			'created_at' => DATETIME
		];
		$this->db->insert('subscriptions', $data);
		$transaction_id = $this->db->insert_id();
		return $transaction_id;
	}

	// public function create_user_plan($user_id, $pricing_plan_id ,$stripe_token_id ,$payment_card ,$billing_address ,$city ,$state ,$zip_code ,$country) {
	// 	$data = [
	// 		'user_plan_guid' => get_guid(),
	// 		'user_id' => $user_id,
	// 		'pricing_plan_id' => $pricing_plan_id,
	// 		'stripe_token_id' => $stripe_token_id,
	// 		'payment_card' => $payment_card,
	// 		'billing_address' => $billing_address,
	// 		'city' => $city,
	// 		'state' => $state,
	// 		'zip_code' => $zip_code,
	// 		'country' => $country,
	// 		'created_at' => DATETIME,
	// 		'updated_at' => DATETIME
	// 	];
	// 	$this->db->insert('user_plans', $data);
	// 	$user_plan_id = $this->db->insert_id();
	// 	return $user_plan_id;
	// }
	public function create_user_plan($user_id ,$billing_address ,$city ,$state ,$zip_code ,$country, $plan_amount = NULL) {
		$data = [
			'user_plan_guid' => get_guid(),
			'user_id' => $user_id,
			'billing_address' => $billing_address,
			'city' => $city,
			'state' => $state,
			'zip_code' => $zip_code,
			'country' => $country,
			'plan_amount' => $plan_amount,
			'created_at' => DATETIME,
			'updated_at' => DATETIME
		];
		$this->db->insert('user_plans', $data);
		$user_plan_id = $this->db->insert_id();
		return $user_plan_id;
	}

	public function create_organization($user_id, $business_name, $status, $organization_status, $stripe_customer_id) {
		$data = [
			'organization_guid' => get_guid(),
			'user_id' => $user_id,
			'name' => $business_name,
			'status' => $status,
			'organization_status' => $organization_status,
			'stripe_customer_id' => $stripe_customer_id,
			'created_at' => DATETIME,
			'updated_at' => DATETIME
		];
		$this->db->insert('organizations', $data);
		$user_plan_id = $this->db->insert_id();
		return $user_plan_id;
	}
	public function create_organization_member($user_id, $organization_id, $pricing_plan_id, $email, $status, $role, $added_by = NULL) {
		$data = [
			'organization_member_guid' => get_guid(),
			'user_id' => $user_id,
			'added_by' => $added_by,
			'organization_id' => $organization_id,
			'pricing_plan_id' => $pricing_plan_id,
			'email' => $email,
			'status' => $status,
			'role' => $role,
			'created_at' => DATETIME,
			'updated_at' => DATETIME
		];
		$this->db->insert('organization_members', $data);
		$organization_member_id = $this->db->insert_id();
		return $organization_member_id;
	}

	public function update_organization_member($user_id, $organization_member_id, $email, $organization_id, $pricing_plan_id) {
		$data = [
			'user_id' => $user_id,
			'pricing_plan_id' => $pricing_plan_id,
			'status' => 'ACCEPTED',
			'updated_at' => DATETIME,
		];
		// $this->db->update('organization_members', $data, ['email' => $email, 'organization_id' => $organization_id]);
		$this->db->update('organization_members', $data, ['organization_member_id' => $organization_member_id]);
	}

	// public function create_agency_invite_user($agency_user_id, $user_id) {
	// 	$data = [
	// 		'agency_invited_user_guid' => get_guid(),
	// 		'agency_id' => $agency_user_id,
	// 		'user_id' => $user_id,
	// 		'created_at' => DATETIME,
	// 		'updated_at' => DATETIME
	// 	];
	// 	$this->db->insert('agency_invited_users', $data);
	// 	$agency_invited_user_id = $this->db->insert_id();
	// 	return $agency_invited_user_id;
	// }

	public function get_users_list($limit = 0, $offset = 0) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('u.user_sub_type, u.user_sub_type');
			$this->db->select('u.user_type, u.user_type');
			$this->db->select('IFNULL(u.user_guid,"") AS user_guid', FALSE);
			$this->db->select('CONCAT(u.first_name, " ",u.last_name) AS name');
			$this->db->select('IFNULL(u.email,"") AS email', FALSE);
			$this->db->select('IFNULL(u.created_at,"") AS created_at', FALSE);
			$this->db->select('IFNULL(u.status,"") AS status', FALSE);
		} else {
			$this->db->select('COUNT(u.user_id) as count', FALSE);
		}
		$this->db->from('users AS u');
		$this->db->where('u.user_type!=', 'ADMIN');
		$this->db->order_by('u.created_at', 'desc');
		
		$query = $this->db->get();
		$results = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				$list = [];
				foreach ($results as $key => $value) {
					$list[$key]['user_id'] = $value['user_guid'];
					$list[$key]['first_letter'] = ucfirst($value['name'][0]);
					$list[$key]['name'] = $value['name'];
					$list[$key]['email'] = $value['email'];
					$list[$key]['created_at'] = $value['created_at'];
					$list[$key]['created_at_time_ago'] = time_ago($value['created_at']);
				}
				return $list;
			} else {
				return [];
			}

		} else {
			return $query->row()->count;
		}
	}


	public function get_recent_users_list() {
		$this->db->limit(10, 0);
		$this->db->select('u.user_sub_type, u.user_sub_type');
		$this->db->select('u.user_type, u.user_type');
		$this->db->select('IFNULL(u.user_guid,"") AS user_guid', FALSE);
		$this->db->select('CONCAT(u.first_name, " ",u.last_name) AS name');
		$this->db->select('IFNULL(u.email,"") AS email', FALSE);
		$this->db->select('IFNULL(u.created_at,"") AS created_at', FALSE);
		$this->db->select('IFNULL(u.status,"") AS status', FALSE);
		$this->db->from('users AS u');
		$this->db->where('u.user_type!=', 'ADMIN');
		$this->db->order_by('u.created_at', 'DESC');
		
		$query = $this->db->get();
		$results = $query->result_array();
		if ($query->num_rows() > 0) {
			$list = [];
			foreach ($results as $key => $value) {
				$list[$key]['user_id'] = $value['user_guid'];
				$list[$key]['first_letter'] = ucfirst($value['name'][0]);
				$list[$key]['name'] = trim($value['name']);
				$list[$key]['email'] = $value['email'];
				$list[$key]['created_at'] = $value['created_at'];
				$list[$key]['created_at_time_ago'] = time_ago($value['created_at']);
			}
			return $list;
		} else {
			return [];
		}
	}


	/**
	 *
	 * @param type $user_id
	 * @param type $device_type
	 */
	public function send_retail_contact_email($name,$email,$phone,$contact_message, $to_email, $cc_email) {
		$this->load->helper('email');
		$subject ="Retail.";
		$email_data = array("name" => $name, "email" => $email, "phone" => $phone, "contact_message" => $contact_message);
		$email_template = "emailer/web_retail_contact";
		$message = $this->load->view($email_template, $email_data, TRUE);
		$this->load->library('email');
		$this->email->from(SUPPORT_EMAIL, FROM_NAME);
		$this->email->to($to_email);
		$this->email->cc($cc_email);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
	}

	public function get_upcoming_invoice($stripe_subscription_id) {

		try{
			\Stripe\Stripe::setApiKey(STRIPE_SKEY);
			// $stripe_response = \Stripe\Invoice::upcoming(["customer" => $customer]);
			$stripe_response = \Stripe\Invoice::upcoming(["subscription" => $stripe_subscription_id]);
			return ['status' => "success", 'upcoming_invoice' =>  $stripe_response, 'error' => null];
		}
	
		catch(\Stripe\Error\Card $e){
				$body = $e->getJsonBody();
				$err  = $body['error'];
						
		}

		catch (\Stripe\Error\RateLimit $e) {
		// Too many requests made to the API too quickly
				$body = $e->getJsonBody();
				$err  = $body['error'];
			
		} catch (\Stripe\Error\InvalidRequest $e) {
		// Invalid parameters were supplied to Stripe's API
				$body = $e->getJsonBody();
				$err  = $body['error'];
			
		} catch (\Stripe\Error\Authentication $e) {
		// Authentication with Stripe's API failed
		// (maybe you changed API keys recently)
				$body = $e->getJsonBody();
				$err  = $body['error'];
			
		} catch (\Stripe\Error\ApiConnection $e) {
		// Network communication with Stripe failed
				$body = $e->getJsonBody();
				$err  = $body['error'];
			

		} catch (\Stripe\Error\Base $e) {
		// Display a very generic error to the user, and maybe send
		// yourself an email
				$body = $e->getJsonBody();
				$err  = $body['error'];
			
		} catch (Exception $e) {
		// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
				$err  = $body['error'];
				
		}
		return ['status'=>'failed', 'error' => $err, 'charge' => null];
	}

	public function get_invoices($subscription_id) {

		try{
			\Stripe\Stripe::setApiKey(STRIPE_SKEY);
			$stripe_response = \Stripe\Invoice::all(["subscription" => $subscription_id]);

			return ['status' => "success", 'invoices' =>  $stripe_response, 'error' => null];
		 }
	 
		  catch(\Stripe\Error\Card $e){
				 $body = $e->getJsonBody();
				   $err  = $body['error'];
						 
		  }
 
		  catch (\Stripe\Error\RateLimit $e) {
			// Too many requests made to the API too quickly
				 $body = $e->getJsonBody();
				 $err  = $body['error'];
				
		 } catch (\Stripe\Error\InvalidRequest $e) {
		   // Invalid parameters were supplied to Stripe's API
				 $body = $e->getJsonBody();
				 $err  = $body['error'];
			 
		 } catch (\Stripe\Error\Authentication $e) {
		   // Authentication with Stripe's API failed
		   // (maybe you changed API keys recently)
				 $body = $e->getJsonBody();
				 $err  = $body['error'];
				
		 } catch (\Stripe\Error\ApiConnection $e) {
		   // Network communication with Stripe failed
				 $body = $e->getJsonBody();
				 $err  = $body['error'];
			 
 
		 } catch (\Stripe\Error\Base $e) {
		   // Display a very generic error to the user, and maybe send
		   // yourself an email
				 $body = $e->getJsonBody();
				 $err  = $body['error'];
			 
		 } catch (Exception $e) {
		   // Something else happened, completely unrelated to Stripe
				 $body = $e->getJsonBody();
				   $err  = $body['error'];
				   
		 }
		 return ['status'=>'failed', 'error' => $err, 'charge' => null];
	}

	public function get_subscription_details($stripe_subscription_id) {

		try{
			\Stripe\Stripe::setApiKey(STRIPE_SKEY);
			$stripe_response = \Stripe\Subscription::retrieve($stripe_subscription_id,[]);
			return ['status' => "success", 'subscription' =>  $stripe_response, 'error' => null];
		 }
	 
		  catch(\Stripe\Error\Card $e){
				 $body = $e->getJsonBody();
				   $err  = $body['error'];
						 
		  }
 
		  catch (\Stripe\Error\RateLimit $e) {
			// Too many requests made to the API too quickly
				 $body = $e->getJsonBody();
				 $err  = $body['error'];
				
		 } catch (\Stripe\Error\InvalidRequest $e) {
		   // Invalid parameters were supplied to Stripe's API
				 $body = $e->getJsonBody();
				 $err  = $body['error'];
			 
		 } catch (\Stripe\Error\Authentication $e) {
		   // Authentication with Stripe's API failed
		   // (maybe you changed API keys recently)
				 $body = $e->getJsonBody();
				 $err  = $body['error'];
				
		 } catch (\Stripe\Error\ApiConnection $e) {
		   // Network communication with Stripe failed
				 $body = $e->getJsonBody();
				 $err  = $body['error'];
			 
 
		 } catch (\Stripe\Error\Base $e) {
		   // Display a very generic error to the user, and maybe send
		   // yourself an email
				 $body = $e->getJsonBody();
				 $err  = $body['error'];
			 
		 } catch (Exception $e) {
		   // Something else happened, completely unrelated to Stripe
				 $body = $e->getJsonBody();
				   $err  = $body['error'];
				   
		 }
		 return ['status'=>'failed', 'error' => $err, 'charge' => null];
	}

	public function get_user_role($user_id) {
		$this->db->select('IFNULL(om.role,"") AS role', FALSE);
		$this->db->select('IFNULL(om.added_by,"") AS added_by', FALSE);
		$this->db->from('users AS u');
		$this->db->join('organization_members AS om', 'om.user_id = u.user_id');
		$this->db->where('u.user_id', $user_id);
		$query = $this->db->get();
		$user = $query->row_array();
		unset($user['user_id']);
		$user['user_id'] = '';
		return $user;
	}

	public function update_plan($stripe_subscription_id, $plan_id) {
		try{
			\Stripe\Stripe::setApiKey(STRIPE_SKEY);
			$subscription = \Stripe\Subscription::retrieve($stripe_subscription_id);

			$subscription_update = \Stripe\Subscription::update($stripe_subscription_id, [
				'cancel_at_period_end' => false,
				'proration_behavior' => 'create_prorations',
					'items' => [
						[
						'id' => $subscription->items->data[0]->id,
						'price' => $plan_id,
						],
					],
			]);

			return ['status' => "success", 'subscription' =>  $subscription_update, 'error' => null];
		 }
	 
		  catch(\Stripe\Error\Card $e){
				 $body = $e->getJsonBody();
				   $err  = $body['error'];
						 
		  }
 
		  catch (\Stripe\Error\RateLimit $e) {
			// Too many requests made to the API too quickly
				 $body = $e->getJsonBody();
				 $err  = $body['error'];
				
		 } catch (\Stripe\Error\InvalidRequest $e) {
		   // Invalid parameters were supplied to Stripe's API
				 $body = $e->getJsonBody();
				 $err  = $body['error'];
			 
		 } catch (\Stripe\Error\Authentication $e) {
		   // Authentication with Stripe's API failed
		   // (maybe you changed API keys recently)
				 $body = $e->getJsonBody();
				 $err  = $body['error'];
				
		 } catch (\Stripe\Error\ApiConnection $e) {
		   // Network communication with Stripe failed
				 $body = $e->getJsonBody();
				 $err  = $body['error'];
			 
 
		 } catch (\Stripe\Error\Base $e) {
		   // Display a very generic error to the user, and maybe send
		   // yourself an email
				 $body = $e->getJsonBody();
				 $err  = $body['error'];
			 
		 } catch (Exception $e) {
		   // Something else happened, completely unrelated to Stripe
				 $body = $e->getJsonBody();
				   $err  = $body['error'];
				   
		 }
		 return ['status'=>'failed', 'error' => $err, 'charge' => null];
	}

	public function update_user_plan($user_id, $pricing_plan_id) {
		$this->db->update('organization_members', ['pricing_plan_id' => $pricing_plan_id, 'updated_at' => DATETIME], ['user_id' => $user_id]);
		$this->db->update('subscriptions', ['pricing_plan_id' => $pricing_plan_id], ['user_id' => $user_id]);
	}

	public function get_plan_amount($organization_id, $user_id, $base_price) {
		$this->db->select('IFNULL(pp.name,"") AS name', FALSE);
		$this->db->select('IFNULL(pp.pricing_plan_guid,"") AS pricing_plan_guid', FALSE);
		$this->db->select('IFNULL(pp.discount,"") AS discount', FALSE);
		$this->db->select('IFNULL(pp.base_price,"") AS base_price', FALSE);
		$this->db->from('organization_members AS om');
		$this->db->join('pricing_plans AS pp', 'om.pricing_plan_id = pp.pricing_plan_id');
		$this->db->where('om.organization_id', $organization_id);
		$this->db->where('om.user_id', $user_id);
		$query = $this->db->get();
		$result = $query->row_array();
		$plan_amount = 0;
		$discount = $result['discount'];
		// $plan_amount = $result['base_price'] - ($result['base_price'] * ($discount / 100));
		$plan_amount = $base_price - ($base_price * ($discount / 100));
		return $plan_amount;
	}

	public function update_subscription_count($pricing_plan_id , $subscription_count) {
		$this->db->update('pricing_plans', ['subscription_count' => $subscription_count, 'updated_at' => DATETIME], ['pricing_plan_id' => $pricing_plan_id]);
	}
}
