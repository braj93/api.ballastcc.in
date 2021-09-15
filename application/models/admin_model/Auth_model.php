<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Auth_model extends CI_Model {

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

    $admin = $this->app->get_row('admins', 'admin_id', [
        "email" => strtolower($email),
        "password" => md5($password),
    ]);

    return safe_array_key($admin, 'admin_id', "0");
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
    $this->db->where('admin_id', $user_id);
    $this->db->update('admins');
    return $session_id;
}

}
