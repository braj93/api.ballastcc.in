<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Students_model extends CI_Model {

	public function __construct() {
		// Call the CI_Model constructor
		parent::__construct();
	}

	/**
	 *
	 * @param type $session_id
	 * @return type
	 */


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
	/** create_user
	 * @param type $name
	 * @param type $start_date
	 * @param type $end_date
	 * @param type $start
	 * @param type $end
	 * @return type
	 */
	public function create_batch($name,$medium, $start_date, $end_date,  $start, $end) {
		$this->db->insert('batches', [
			"batch_guid" => get_guid(),
			"name" => $name,
			"medium" => $medium,
			"start_date" => $start_date,
			"end_date" => $end_date,
			"start" => $start,
			"end" => $end,
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		]);
		$user_id = $this->db->insert_id();
		return $user_id;
	}
    
    	/**
	 *
	 * @param type $user_id
	 * @param type $name
	 * @param type $email
	 * @param type $work_mobile
	 * @return boolean
	 */
	public function update_batch($batch_id, $name,$medium, $start_date, $end_date,  $start, $end) {
		$batch = [
            "name" => $name,
            "medium" => $medium,
			"start_date" => $start_date,
			"end_date" => $end_date,
			"start" => $start,
			"end" => $end,
			"updated_at" => DATETIME,
		];

		$this->db->update('batches', $batch, array(
			'batch_id' => $batch_id,
		));
		return TRUE;
	}
    
}
