<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Course_model extends CI_Model
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

	public function update_media_status($media_id) {
		$this->db->update('media', ['status' => 'PENDING'], ['media_id' => $media_id]);
		return TRUE;
	}

	/** create_course
	 * @param type $name, 
	 * @param type $user_id, 
	 * @param type $status
	 * @return type
	 */
	public function create_course($course_name,$description,$course_media_id, $user_id, $status)
	{
		$this->db->insert('courses', [
			"course_guid" => get_guid(),
			"course_name" => $course_name,
			"description" => $description,
			"media" => $course_media_id,
			"added_by" => $user_id,
			"status" => $status,
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		]);
		$student_id = $this->db->insert_id();
		return $student_id;
	}

	/** edit_course
	 * @param type $course_id, 
	 * @param type $name, 
	 * @param type $user_id, 
	 * @param type $status
	 * @return type
	 */
	public function edit_course($course_id, $course_name, $description,$course_media_id, $user_id, $status)
	{
		$data = [
			"course_name" => $course_name,
			"description" => $description,
			"media" => $course_media_id,
			"added_by" => $user_id,
			"status" => $status,
			"updated_at" => DATETIME,
		];

		$this->db->update('courses', $data, array(
			'course_id' => $course_id,
		));
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
	}
	// public function get_subjects($course_id) {

	// 	$this->db->select('s.subject_name as subject_name');
	// 	$this->db->select('s.subject_guid as subject_id');
	// 	$this->db->select('CONCAT(u.first_name, " ", IFNULL (u.last_name, "")) AS added_by', FALSE);
	// 	$this->db->select('s.status as status');
	// 	$this->db->select('s.created_at as created_at');

	// 	$this->db->from('subjects AS s');
	// 	$this->db->join('users AS u', 'u.user_id = s.added_by', 'LEFT');
	// 	$this->db->where('s.course_id', $course_id);
	// 	$query = $this->db->get();
	// 	// echo $this->db->last_query();die();
	// 	$results = $query->result_array();
	// 	return $results;
	// }

	public function list($user_id, $keyword = '', $limit = 0, $offset = 0, $column_name, $order_by, $user_type)
	{
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('IFNULL(c.course_id,"") AS course_id', FALSE);
			$this->db->select('IFNULL(c.course_guid,"") AS course_guid', FALSE);
			$this->db->select('IFNULL(c.course_name,"") AS course_name', FALSE);
			$this->db->select('IFNULL(c.description,"") AS description', FALSE);
			$this->db->select('IFNULL(m.name,"") AS media_name', FALSE);
			$this->db->select('IFNULL(m.media_guid,"") AS media_id', FALSE);
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
					$list[$key]['description'] = $value['description'];
					$list[$key]['media_url'] = $value['media_name'] ? site_url('/uploads/images/' . $value['media_name']) : "";
					// $list[$key]['subjects'] = $this->get_subjects($value['course_id']);
					$list[$key]['media_id'] = $value['media_id'];
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

	public function get_details_by_id($course_id) {
			$this->db->select('IFNULL(c.course_guid,"") AS course_guid', FALSE);
			$this->db->select('IFNULL(c.course_name,"") AS course_name', FALSE);
			$this->db->select('IFNULL(c.description,"") AS description', FALSE);
			$this->db->select('IFNULL(m.name,"") AS media_name', FALSE);
			$this->db->select('IFNULL(m.media_guid,"") AS media_id', FALSE);
			$this->db->select('CONCAT(u.first_name, " ", IFNULL (u.last_name, "")) AS added_by');
			$this->db->select('IFNULL(c.status,"") AS status', FALSE);
			$this->db->select('IFNULL(c.created_at,"") AS created_at', FALSE);
			$this->db->select('IFNULL(c.updated_at,"") AS updated_at', FALSE);			
			$this->db->from('courses AS c');
		// $this->db->join('campaign_templates AS ct', 'ct.campaign_template_id = c.campaign_template_id', 'LEFT');
		// $this->db->join('media AS m', 'm.media_id = ct.preview_media_id', 'LEFT');
		$this->db->join('users AS u', 'u.user_id = c.added_by', 'LEFT');
		$this->db->join('media AS m', 'm.media_id = c.media', 'LEFT');
		$this->db->where('c.course_id', $course_id);
		$query = $this->db->get();
		$reuslt = $query->row_array();
		$reuslt['media_url'] = $reuslt['media_name'] ? site_url('/uploads/images/' . $reuslt['media_name']) : "";
		// $reuslt['string'] = unique_random_string('campaign_templates', 'unique_string', [], 'alnum', 12);
		return $reuslt;
	}


}
