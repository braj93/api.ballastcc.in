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
	 * @param type $first_name, 
	 * @param type $last_name, 
	 * @param type $father_name,  
	 * @param type $mother_name, 
	 * @param type $dob, 
	 * @param type $class, 
	 * @param type $board, 
	 * @param type $medium,
	 * @param type  $batch, 
	 * @param type $registration_date, 
	 * @param type $profile_id, 
	 * @param type $school, $address, 
	 * @param type $mobile,
	 * @param type $status
	 * @param type $email
	 * @return type
	 */
	public function create_student($reg_number, $reg_date, $first_name, $last_name, $father_name,  $mother_name, $dob, $subjects, $class_id, $board_id, $medium,  $total_fee, $remain_fee, $batch_id, $profile_id, $school, $address, $mobile,$alt_mobile, $email, $status) {
		$allSubjects = base64_encode(serialize($subjects));
		$this->db->insert('students', [
			"reg_number" => $reg_number,
			"reg_date" => $reg_date,
			"student_guid" => get_guid(),
			"first_name" => $first_name,
			"last_name" => $last_name,
			"father_name" => $father_name,
			"mother_name" => $mother_name,
			"dob" => $dob,
			"class" => $class_id,
			"subjects" => $allSubjects,
			"board" => $board_id,
			"medium" => $medium,
			"total_fee" => $total_fee,
			"remain_fee" => $total_fee,
			"batch" => $batch_id,
			"profile_id" => $profile_id,
			"school" => $school,
			"address" => $address,
			"mobile" => $mobile,
			"email" => $email,
			"status" => $status,
			"alt_mobile" => $alt_mobile,
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		]);
		$student_id = $this->db->insert_id();
		return $student_id;
	}
	
	// GET STUDENTS LIST

	public function get_student_list($keyword, $limit = 0, $offset = 0) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('s.reg_number, s.reg_number');
			$this->db->select('s.father_name, s.father_name');
			$this->db->select('s.mother_name, s.mother_name');
			$this->db->select('s.dob, s.dob');		
			$this->db->select('IFNULL(cl.name,"") AS class');
			$this->db->select('IFNULL(bo.name,"") AS board');
			$this->db->select('s.medium, s.medium');
			$this->db->select('s.total_fee, s.total_fee');
			$this->db->select('s.remain_fee, s.remain_fee');
			$this->db->select('IFNULL(ba.name,"") AS batch');
			$this->db->select('s.reg_date, s.reg_date');
			$this->db->select('s.subjects, s.subjects');
			$this->db->select('s.address, s.address');
			$this->db->select('s.mobile, s.mobile');
			$this->db->select('s.alt_mobile, s.alt_mobile');
			$this->db->select('IFNULL(s.student_guid,"") AS student_guid', FALSE);
			$this->db->select('CONCAT(s.first_name, " ",s.last_name) AS name');
			$this->db->select('IFNULL(s.email,"") AS email', FALSE);
			$this->db->select('IFNULL(s.school,"") AS school', FALSE);
			$this->db->select('IFNULL(s.created_at,"") AS created_at', FALSE);
			$this->db->select('IFNULL(s.status,"") AS status', FALSE);
		} else {
			$this->db->select('COUNT(s.student_id) as count', FALSE);
		}
		$this->db->from('students AS s');
		$this->db->join('classes AS cl', 'cl.class_id = s.class', 'LEFT');
		$this->db->join('boards AS bo', 'bo.board_id = s.board', 'LEFT');
		$this->db->join('batches AS ba', 'ba.batch_id = s.batch', 'LEFT');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('s.first_name', $keyword, 'both');
			$this->db->or_like('s.reg_number', $keyword, 'both');
			$this->db->or_like('cl.name', $keyword, 'both');
			$this->db->group_end();
		}
		$this->db->where('s.status!=', 'DELETED');
		$this->db->order_by('s.created_at', 'desc');

		$query = $this->db->get();
		$results = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				$list = [];
				foreach ($results as $key => $value) {
					$list[$key]['student_id'] = $value['student_guid'];
					$list[$key]['reg_number'] = $value['reg_number'];
					$list[$key]['name'] = $value['name'];
					$list[$key]['father_name'] = $value['father_name'];
					$list[$key]['mother_name'] = $value['mother_name'];
					$list[$key]['dob'] = $value['dob'];
					$list[$key]['class'] = $value['class'];
					$list[$key]['board'] = $value['board'];
					$list[$key]['medium'] = $value['medium'];
					$list[$key]['total_fee'] = $value['total_fee'];
					$list[$key]['remain_fee'] = $value['remain_fee'];
					$list[$key]['batch'] = $value['batch'];
					$list[$key]['reg_date'] = $value['reg_date'];
					$list[$key]['subjects'] = unserialize(base64_decode($value['subjects']));
					$list[$key]['address'] = $value['address'];
					$list[$key]['mobile'] = $value['mobile'];
					$list[$key]['alt_mobile'] = $value['alt_mobile'];
					$list[$key]['email'] = $value['email'];
					$list[$key]['school'] = $value['school'];
					$list[$key]['status'] = $value['status'];
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
	// GET STUDENT DETAILS BY ID
	public function get_details_by_id($student_id) {
		$this->db->select('s.reg_number, s.reg_number');
		$this->db->select('s.father_name, s.father_name');
		$this->db->select('s.mother_name, s.mother_name');
		$this->db->select('s.dob, s.dob');		
		$this->db->select('IFNULL(cl.name,"") AS class');
		$this->db->select('IFNULL(cl.class_guid,"") AS class_guid');
		$this->db->select('IFNULL(bo.name,"") AS board');
		$this->db->select('IFNULL(bo.board_guid,"") AS board_guid');
		$this->db->select('s.medium, s.medium');
		$this->db->select('s.total_fee, s.total_fee');
		$this->db->select('s.remain_fee, s.remain_fee');
		$this->db->select('IFNULL(ba.name,"") AS batch');
		$this->db->select('IFNULL(ba.batch_guid,"") AS batch_guid');
		$this->db->select('s.reg_date, s.reg_date');
		$this->db->select('s.address, s.address');
		$this->db->select('s.mobile, s.mobile');
		$this->db->select('s.alt_mobile, s.alt_mobile');
		$this->db->select('IFNULL(s.student_guid,"") AS student_guid', FALSE);
		$this->db->select('s.first_name, s.first_name');
		$this->db->select('s.last_name, s.last_name');
		$this->db->select('s.subjects, s.subjects');
		$this->db->select('IFNULL(s.email,"") AS email', FALSE);
		$this->db->select('IFNULL(s.school,"") AS school', FALSE);
		$this->db->select('IFNULL(s.created_at,"") AS created_at', FALSE);
		$this->db->select('IFNULL(s.status,"") AS status', FALSE);
		$this->db->from('students AS s');
		$this->db->join('classes AS cl', 'cl.class_id = s.class', 'LEFT');
		$this->db->join('boards AS bo', 'bo.board_id = s.board', 'LEFT');
		$this->db->join('batches AS ba', 'ba.batch_id = s.batch', 'LEFT');
		$this->db->where('s.student_id', $student_id);
		$query = $this->db->get();
		$result = $query->row_array();
		$result['subjects'] = unserialize(base64_decode($result['subjects']));;
		return $result;
	}

	 //  UPDATE STUDENT
    /**
     *
     * @param type $student_id
	 * @param type $first_name, 
	 * @param type $last_name, 
	 * @param type $father_name,  
	 * @param type $mother_name, 
	 * @param type $dob, 
	 * @param type $class, 
	 * @param type $board, 
	 * @param type $medium,
	 * @param type  $batch, 
	 * @param type $registration_date, 
	 * @param type $profile_id, 
	 * @param type $school, $address, 
	 * @param type $mobile,
	 * @param type $status
	 * @param type $email
     * @return boolean
     */
    public function update_student($student_id, $first_name, $last_name, $father_name,  $mother_name, $dob, $class, $board, $medium, $total_fee, $remain_fee, $batch, $registration_date, $profile_id, $school, $address, $mobile, $email, $status)
    {
        $batch = [
            "first_name" => $first_name,
			"last_name" => $last_name,
			"father_name" => $father_name,
			"mother_name" => $mother_name,
			"dob" => $dob,
			"class" => $class,
			"board" => $board,
			"medium" => $medium,
			"total_fee" => $total_fee,
			"remain_fee" => $remain_fee,
			"batch" => $batch,
			"registration_date" => $registration_date,
			"profile_id" => $profile_id,
			"school" => $school,
			"address" => $address,
			"mobile" => $mobile,
			"email" => $email,
			"status" => $status,
			"mobile" => $mobile,
            "updated_at" => DATETIME,
        ];

        $this->db->update('students', $batch, array(
            'student_id' => $student_id,
        ));
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
    }

    
}
