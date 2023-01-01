<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Master_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    /** create_batch
     * @param type $name
     * @param type $start_date
     * @param type $end_date
     * @param type $start
     * @param type $end
     * @return type
     */
    // public function create_batch($name, $medium, $start_date, $end_date,  $start, $end, $status)
    public function create_batch($name, $start_date, $end_date,  $start, $end, $status)
    {
        $this->db->insert('batches', [
            "batch_guid" => get_guid(),
            "name" => $name,
            // "medium" => $medium,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "start" => $start,
            "end" => $end,
            "status" => $status,
            "created_at" => DATETIME,
            "updated_at" => DATETIME,
        ]);
        $user_id = $this->db->insert_id();
        return $user_id;
    }

    //  UPDATE BATCH
    /**
     *
     * @param type $batch_id
     * @param type $name
     * @param type $medium
     * @param type $start_date
     * @param type $end_date
     * @param type $status
     * @return boolean
     */
    public function update_batch($batch_id, $name, $medium, $start_date, $end_date,  $start, $end, $status)
    {
        $batch = [
            "name" => $name,
            "medium" => $medium,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "start" => $start,
            "end" => $end,
            "status" => $status,
            "updated_at" => DATETIME,
        ];

        $this->db->update('batches', $batch, array(
            'batch_id' => $batch_id,
        ));
        return TRUE;
    }

    //  DELETE BATCH
    /**
     *
     * @param type $batch_id
     * 
     */
    public function delete_batch($batch_id)
    {
        $this->db->update('batches', [
            'status' => 'DELETED'
        ], [
            'batch_id' => $batch_id,
        ]);
        // return TRUE;
    }


    /** CREATE CLASS
     * @param type $name
     */
    public function create_class($name, $status)
    {
        $this->db->insert('classes', [
            "class_guid" => get_guid(),
            "name" => $name,
            "status" => $status,
            "created_at" => DATETIME,
            "updated_at" => DATETIME,
        ]);
        $class_id = $this->db->insert_id();
        return $class_id;
    }

    //  UPDATE CLASS
    /**
     *
     * @param type $class_id
     * @param type $name
     * @param type $status
     * @return boolean
     */
    public function update_class($class_id, $name, $status)
    {
        $batch = [
            "name" => $name,
            "status" => $status,
            "updated_at" => DATETIME,
        ];

        $this->db->update('classes', $batch, array(
            'class_id' => $class_id,
        ));
        return TRUE;
    }

    //  DELETE CLASS
    /**
     *
     * @param type $batch_id
     * 
     */
    public function delete_class($class_id)
    {
        $this->db->update('classes', [
            'status' => 'DELETED'
        ], [
            'class_id' => $class_id,
        ]);
        // return TRUE;
    }

    /** CREATE BOARDS
     * @param type $name
     */
    public function create_board($name, $status)
    {
        $this->db->insert('boards', [
            "board_guid" => get_guid(),
            "name" => $name,
            "status" => $status,
            "created_at" => DATETIME,
            "updated_at" => DATETIME,
        ]);
        $class_id = $this->db->insert_id();
        return $class_id;
    }

    //  UPDATE BOARDS
    /**
     *
     * @param type $board_id
     * @param type $name
     * @param type $status
     * @return boolean
     */
    public function update_board($board_id, $name, $status)
    {
        $batch = [
            "name" => $name,
            "status" => $status,
            "updated_at" => DATETIME,
        ];

        $this->db->update('boards', $batch, array(
            'board_id' => $board_id,
        ));
        return TRUE;
    }

    //  DELETE CLASS
    /**
     *
     * @param type $batch_id
     * 
     */
    public function delete_board($board_id)
    {
        $this->db->update('boards', [
            'status' => 'DELETED'
        ], [
            'board_id' => $board_id,
        ]);
        // return TRUE;
    }

     /** CREATE subjects
     * @param type $name
     */
    public function create_subject($name, $status)
    {
        $this->db->insert('subjects', [
            "subject_guid" => get_guid(),
            "name" => $name,
            "status" => $status,
            "created_at" => DATETIME,
            "updated_at" => DATETIME,
        ]);
        $subject_id = $this->db->insert_id();
        return $subject_id;
    }

    //  UPDATE subjects
    /**
     *
     * @param type $board_id
     * @param type $name
     * @param type $status
     * @return boolean
     */
    public function update_subject($subject_id, $name, $status)
    {
        $batch = [
            "name" => $name,
            "status" => $status,
            "updated_at" => DATETIME,
        ];
        $this->db->update('subjects', $batch, array(
            'subject_id' => $subject_id,
        ));
        return TRUE;
    }

    //  DELETE SUBJECT
    /**
     *
     * @param type $subject_id
     * 
     */
    public function delete_subject($subject_id)
    {
        $this->db->update('subjects', [
            'status' => 'DELETED'
        ], [
            'subject_id' => $subject_id,
        ]);
        // return TRUE;
    }

    public function contact_submission_list($user_id, $column_name, $order_by, $user_type, $keyword = '', $limit = 0, $offset = 0)
	{        
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('IFNULL(c.contact_id,"") AS contact_id', FALSE);
			$this->db->select('IFNULL(c.contact_guid,"") AS contact_guid', FALSE);
			$this->db->select('IFNULL(c.first_name,"") AS first_name', FALSE);
			$this->db->select('IFNULL(c.last_name,"") AS last_name', FALSE);			
			$this->db->select('IFNULL(c.subject,"") AS subject', FALSE);			
			$this->db->select('IFNULL(c.email,"") AS email', FALSE);			
			$this->db->select('IFNULL(c.message,"") AS message', FALSE);			
			$this->db->select('IFNULL(c.seen,"") AS seen', FALSE);	
			$this->db->select('IFNULL(c.created_at,"") AS created_at', FALSE);
		} else {
			$this->db->select('COUNT(c.contact_id) as count', FALSE);
		}
		$this->db->from('contact_submission AS c');
		$this->db->order_by('c.created_at', 'desc');
		// if (!empty($filterBy)) {
		// 	$this->db->like('u.status', $filterBy);
		// }

		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('c.subject', $keyword, 'both');
			$this->db->group_end();
		}

		if (($column_name !== '') && ($order_by !== '')) {
			$this->db->order_by('c.' . $column_name, $order_by);
		}
		// if ($user_type != 'ADMIN') {
		// 	$this->db->where('c.added_by', $user_id);
		// }

		$query = $this->db->get();       
		$results = $query->result_array();       
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				$list = [];
				foreach ($results as $key => $value) {
					$list[$key]['contact_guid'] = $value['contact_guid'];
					$list[$key]['first_name'] = $value['first_name'];
					$list[$key]['last_name'] = $value['last_name'];
					$list[$key]['subject'] = $value['subject'];
					$list[$key]['email'] = $value['email'];					
					$list[$key]['message'] = $value['message'];
					$list[$key]['seen'] = $value['seen'];
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



    // public function get_dashboard($added_by = NULL){
      
		
    //     $result = [];
    //     $this->db->select('COUNT(s.student_id) as count', FALSE);
    //     $this->db->from('students AS s');
    //     $this->db->where('s.status!=', 'DELETED');
    //     $query = $this->db->get();
    //     $result['total_student']= $query->row()->count;
		
	// 	// $result['campaign_count'] = $query->num_rows();
	// 	$result['batch_count'] = $this->get_counts('batches', $added_by);
	// 	// $result['landing_page_count'] = $this->get_counts('LANDING', $added_by);
	// 	// $result['call_tracking_number_count'] = $this->get_counts('CALL', $added_by);
	// 	return $result;
	// }
    // public function get_counts($table, $added_by = NULL) {

	// 	$this->db->select('IFNULL(c.campaign_id,"") AS campaign_id', FALSE);
	// 	$this->db->from('campaigns AS c');
	// 	$this->db->where('c.status', 'ACTIVE');
	// 	if($type == 'QR') {
	// 		$this->db->where('c.is_qr_code', 'YES');
	// 	}
	// 	if($type == 'LANDING') {
	// 		$this->db->where('c.is_landing_page', 'YES');
	// 	}
	// 	if($type == 'CALL') {
	// 		$this->db->where('c.is_call_tracking_number', 'YES');
	// 	}
	// 	if ($added_by) {
	// 		$this->db->where('c.added_by', $added_by);
	// 	}
	// 	$query = $this->db->get();
	// 	$count = $query->num_rows();
	// 	return $count;
	// }
}
