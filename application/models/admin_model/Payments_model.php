<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Payments_model extends CI_Model {

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
	 * @param type $student_id, 
	 * @param type $amount, 
	 * @param type $type,  
	 * @param type $pay_date, 
	 * @return type
	 */
	public function pay_fees($student_id, $amount, $type, $pay_date) {
		$this->db->insert('payments', [
			"pay_guid" => get_guid(),
			"pay_by" => $student_id,
			"amount" => $amount,
			"type" => $type,
			"paid_date" => $pay_date,
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		]);
		$student_id = $this->db->insert_id();
		return $student_id;
	}
    

    	 //  UPDATE STUDENT RMAINING FEE
    /**
     *
     * @param type $student_id
	 * @param type $remain_fee, 
     * @return boolean
     */
    public function update_remain_fee($student_id, $remain_fee)
    {
        $this->db->update('students',array(
            'remain_fee' => $remain_fee) , array(
            'student_id' => $student_id,
        ));
        return TRUE;
    }

    	// GET STUDENTS LIST

	public function get_Fee_list($limit = 0, $offset = 0) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);			
			$this->db->select('s.father_name, s.father_name');			
			$this->db->select('IFNULL(cl.name,"") AS class');
			$this->db->select('s.medium, s.medium');
			$this->db->select('s.total_fee, s.total_fee');
			$this->db->select('s.remain_fee, s.remain_fee');
			$this->db->select('IFNULL(ba.name,"") AS batch');
			$this->db->select('s.mobile, s.mobile');
			$this->db->select('IFNULL(p.pay_guid,"") AS pay_guid', FALSE);
			$this->db->select('IFNULL(p.amount,"") AS amount', FALSE);
			$this->db->select('IFNULL(p.type,"") AS type', FALSE);
			// $this->db->select('IFNULL(s.student_guid,"") AS student_guid', FALSE);
			$this->db->select('CONCAT(s.first_name, " ",s.last_name) AS name');
            $this->db->select('IFNULL(p.paid_date,"") AS paid_date', FALSE);
            $this->db->select('IFNULL(p.created_at,"") AS created_at', FALSE);
		} else {
			$this->db->select('COUNT(p.pay_id) as count', FALSE);
		}
		$this->db->from('payments AS p');
		$this->db->join('students AS s', 's.student_id = p.pay_by', 'LEFT');
		$this->db->join('classes AS cl', 'cl.class_id = s.class', 'LEFT');
		$this->db->join('batches AS ba', 'ba.batch_id = s.batch', 'LEFT');
		$this->db->where('s.status!=', 'DELETED');
		$this->db->order_by('s.created_at', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				$list = [];
				foreach ($results as $key => $value) {
					$list[$key]['pay_id'] = $value['pay_guid'];
					$list[$key]['name'] = $value['name'];
					$list[$key]['father_name'] = $value['father_name'];
					$list[$key]['class'] = $value['class'];
					$list[$key]['fee_paid'] = $value['amount'];
					$list[$key]['pay_mode'] = $value['type'];
					$list[$key]['medium'] = $value['medium'];
					$list[$key]['total_fee'] = $value['total_fee'];
					$list[$key]['remain_fee'] = $value['remain_fee'];
					$list[$key]['batch'] = $value['batch'];
					$list[$key]['mobile'] = $value['mobile'];
					$list[$key]['paid_date'] = $value['paid_date'];
					// $list[$key]['created_at'] = $value['created_at'];
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
		public function get_details_by_id($pay_id) {
			$this->db->select('p.amount, p.amount');
			$this->db->select('p.type, p.type');
			$this->db->select('p.paid_date, p.paid_date');
			$this->db->select('IFNULL(s.reg_number, p.pay_id) AS bill_no');
			$this->db->select('s.father_name, s.father_name');
			$this->db->select('s.mother_name, s.mother_name');
			$this->db->select('s.dob, s.dob');		
			$this->db->select('IFNULL(cl.name,"") AS class');
			$this->db->select('IFNULL(cl.class_guid,"") AS class_guid');
			$this->db->select('IFNULL(bo.name,"") AS board');
			$this->db->select('IFNULL(bo.board_guid,"") AS board_guid');
			$this->db->select('s.reg_number, s.reg_number');
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
			$this->db->from('payments AS p');

			$this->db->join('students AS s', 's.student_id = p.pay_by', 'LEFT');
			$this->db->join('classes AS cl', 'cl.class_id = s.class', 'LEFT');
			$this->db->join('boards AS bo', 'bo.board_id = s.board', 'LEFT');
			$this->db->join('batches AS ba', 'ba.batch_id = s.batch', 'LEFT');
			$this->db->where('s.student_id', $pay_id);
			$query = $this->db->get();
			$result = $query->row_array();
			// $result['subjects'] = unserialize(base64_decode($result['subjects']));;
			return $result;
		}


    
}
