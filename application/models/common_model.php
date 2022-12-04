<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Common_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    public function get_subjects($course_id)
    {

        $this->db->select('s.subject_name as subject_name');
        $this->db->select('s.subject_guid as subject_id');
        $this->db->select('CONCAT(u.first_name, " ", IFNULL (u.last_name, "")) AS added_by', FALSE);
        $this->db->select('s.status as status');
        $this->db->select('s.created_at as created_at');

        $this->db->from('subjects AS s');
        $this->db->join('users AS u', 'u.user_id = s.added_by', 'LEFT');
        $this->db->where('s.status', "ACTIVE");
        $this->db->where('s.course_id', $course_id);
        $query = $this->db->get();
        // echo $this->db->last_query();die();
        $results = $query->result_array();
        return $results;
    }

    public function get_chapters($subject_id) {

		$this->db->select('c.chapter_name as chapter_name');
		$this->db->select('c.chapter_guid as chapter_id');
		$this->db->select('CONCAT(u.first_name, " ", IFNULL (u.last_name, "")) AS added_by', FALSE);
		$this->db->select('c.status as status');
		$this->db->select('c.created_at as created_at');

		$this->db->from('chapters AS c');
		$this->db->join('users AS u', 'u.user_id = c.added_by', 'LEFT');
        $this->db->where('c.status', "ACTIVE");
		$this->db->where('c.subject_id', $subject_id);
		$query = $this->db->get();
		// echo $this->db->last_query();die();
		$results = $query->result_array();
		return $results;
	}

    public function course_list($keyword = '', $limit = 0, $offset = 0, $column_name, $order_by,$course_id)
    {
        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);
            $this->db->select('IFNULL(c.course_id,"") AS course_id', FALSE);
            $this->db->select('IFNULL(c.course_guid,"") AS course_guid', FALSE);
            $this->db->select('IFNULL(c.course_name,"") AS course_name', FALSE);
            $this->db->select('IFNULL(m.name,"") AS media_name', FALSE);
            $this->db->select('CONCAT(u.first_name, " ", IFNULL (u.last_name, "")) AS added_by', FALSE);
            $this->db->select('IFNULL(c.status,"") AS status', FALSE);
            $this->db->select('IFNULL(c.created_at,"") AS created_at', FALSE);
            $this->db->select('IFNULL(c.updated_at,"") AS updated_at', FALSE);
        } else {
            $this->db->select('COUNT(c.course_id) as count', FALSE);
        }
        $this->db->from('courses AS c');
        $this->db->join('users AS u', 'u.user_id = c.added_by', 'LEFT');
        $this->db->join('media AS m', 'm.media_id = c.media', 'LEFT');
        $this->db->where('c.status', "ACTIVE");
         if (!empty($course_id)) {
        	$this->db->where('c.course_id', $course_id);
        }
        $this->db->order_by('c.created_at', 'desc');

        // if (!empty($filterBy)) {
        // 	$this->db->like('u.status', $filterBy);
        // }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('c.course_name', $keyword, 'both');
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
                    $list[$key]['course_guid'] = $value['course_guid'];
                    $list[$key]['course_name'] = $value['course_name'];
                    $list[$key]['media_url'] = $value['media_name'] ? site_url('/uploads/images/' . $value['media_name']) : "";
                    // $list[$key]['subjects'] = $this->get_subjects($value['course_id']);
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

    /*
***  
GET SUBJECTS list
***
    */

    public function subject_list($keyword = '', $limit = 0, $offset = 0, $column_name, $order_by)
    {
        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);


            $this->db->select('IFNULL(s.subject_id,"") AS subject_id', FALSE);
            $this->db->select('IFNULL(s.subject_guid,"") AS subject_guid', FALSE);
            $this->db->select('IFNULL(s.subject_name,"") AS subject_name', FALSE);
            $this->db->select('IFNULL(c.course_name,"") AS course_name', FALSE);
            $this->db->select('IFNULL(c.course_guid,"") AS course_guid', FALSE);
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
        $this->db->where('s.status', "ACTIVE");
        $this->db->order_by('s.created_at', 'desc');

        // if (!empty($filterBy)) {
        // 	$this->db->like('u.status', $filterBy);
        // }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('s.subject_name', $keyword, 'both');
            $this->db->or_like('c.course_name', $keyword, 'both');
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
                    $list[$key]['course_id'] = $value['course_guid'];
                    $list[$key]['chapters'] = $this->get_chapters($value['subject_id']);
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
}
