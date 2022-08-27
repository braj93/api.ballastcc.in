<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Subject_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    /**
     *
     * @param type $session_id
     * @return type
     */


    /** create_user
     * @param type $subject_name, 
     * @param type $user_id, 
     * @param type $status
     * @return type
     */
    public function create_subject($subject_name, $course_id, $user_id, $status)
    {
        $this->db->insert('subjects', [
            "subject_guid" => get_guid(),
            "subject_name" => $subject_name,
            "course_id" => $course_id,
            "added_by" => $user_id,
            "status" => $status,
            "created_at" => DATETIME,
            "updated_at" => DATETIME,
        ]);
        $student_id = $this->db->insert_id();
        return $student_id;
    }

    /** create_user
     * @param type $subject_id, 
     * @param type $subject_name, 
     * @param type $user_id, 
     * @param type $status
     * @return type
     */
    public function edit_subject($subject_id, $course_id, $subject_name, $user_id, $status)
    {
        $data = [
            "course_id" => $course_id,
            "subject_name" => $subject_name,
            "added_by" => $user_id,
            "status" => $status,
            "updated_at" => DATETIME,
        ];

        $this->db->update('subjects', $data, array(
            'subject_id' => $subject_id,
        ));
        $affected_rows_count = $this->db->affected_rows();
        return $affected_rows_count;
    }

    /*
***  
GET SUBJECTS list
***
    */

    public function list($user_id, $keyword = '', $limit = 0, $offset = 0, $column_name, $order_by, $user_type)
    {
        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);
            
           
            $this->db->select('IFNULL(s.subject_guid,"") AS subject_guid', FALSE);
            $this->db->select('IFNULL(s.subject_name,"") AS subject_name', FALSE);
            $this->db->select('IFNULL(c.course_name,"") AS course_name', FALSE);
            $this->db->select('CONCAT(u.first_name, " ", IFNULL (u.last_name, "")) AS added_by', FALSE);
            $this->db->select('IFNULL(s.status,"") AS status', FALSE);
            $this->db->select('IFNULL(s.created_at,"") AS created_at', FALSE);
            $this->db->select('IFNULL(s.updated_at,"") AS updated_at', FALSE);
        } else {
            $this->db->select('COUNT(s.subject_id) as count', FALSE);
        }
        $this->db->from('subjects AS s');
        $this->db->join('users AS u', 'u.user_id = s.added_by', 'LEFT');
        $this->db->join('courses AS c', 'c.course_id = s.course_id', 'LEFT');
        $this->db->order_by('s.created_at', 'desc');

        // if (!empty($filterBy)) {
        // 	$this->db->like('u.status', $filterBy);
        // }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('s.subject_name', $keyword, 'both');
            $this->db->group_end();
        }

        if (($column_name !== '') && ($order_by !== '')) {
            $this->db->order_by('s.' . $column_name, $order_by);
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
                    $list[$key]['subject_guid'] = $value['subject_guid'];
                    $list[$key]['subject_name'] = $value['subject_name'];
                    $list[$key]['course_name'] = $value['course_name'];
                    $list[$key]['added_by'] = $value['added_by'];
                    $list[$key]['status'] = $value['status'];
                    $list[$key]['created_at'] = $value['created_at'];
                    $list[$key]['updated_at'] = $value['updated_at'];
                }
                return $list;
            } else {
                return [];
            }
        } else {
            return $query->row()->count;
        }
    }

    public function get_details_by_id($subject_id)
    {
        $this->db->select('IFNULL(s.subject_guid,"") AS subject_guid', FALSE);
        $this->db->select('IFNULL(s.subject_name,"") AS subject_name', FALSE);
        $this->db->select('IFNULL(c.course_name,"") AS course_name', FALSE);
        $this->db->select('CONCAT(u.first_name, " ", IFNULL (u.last_name, "")) AS added_by', FALSE);
        $this->db->select('IFNULL(s.status,"") AS status', FALSE);
        $this->db->select('IFNULL(s.created_at,"") AS created_at', FALSE);
        $this->db->select('IFNULL(s.updated_at,"") AS updated_at', FALSE);        
        $this->db->from('subjects AS s');
        $this->db->join('users AS u', 'u.user_id = s.added_by', 'LEFT');
        $this->db->join('courses AS c', 'c.course_id = s.course_id', 'LEFT');        
        $this->db->where('s.subject_id', $subject_id);
        $query = $this->db->get();
        $reuslt = $query->row_array();
        // $reuslt['string'] = unique_random_string('campaign_templates', 'unique_string', [], 'alnum', 12);
        return $reuslt;
    }
}
