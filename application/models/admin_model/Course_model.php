<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Course_model extends CI_Model {

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
	 * @param type $name, 
	 * @param type $user_id, 
	 * @param type $status
	 * @return type
	 */
	public function create_course($name, $user_id, $status) {
		$this->db->insert('courses', [
            "course_guid" => get_guid(),
			"name" => $name,			
			"added_by" => $user_id,			
			"status" => $status,
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		]);
		$student_id = $this->db->insert_id();
		return $student_id;
	}

}