<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Knowledgebase_model extends CI_Model {

	public function __construct() {
		// Call the CI_Model constructor
		parent::__construct();
	}

	public function create_knowledgebase($name, $status, $video_embed_url, $knowledgebase_media_id, $date, $description) {
		$insert_array = [
			"knowledgebase_guid" => get_guid(),
			"name" => $name,
			"status" => $status,
			"video_embed_url" => $video_embed_url,
			"knowledgebase_media_id" => $knowledgebase_media_id,
			"description" => $description,
			"created_at" => DATETIME,
		];
		$this->db->insert('knowledgebase', $insert_array);
		$knowledgebase_id = $this->db->insert_id();
		return $knowledgebase_id;
	}

	public function save_category($categories, $knowledgebase_id) {
		$this->db->where('knowledgebase_id', $knowledgebase_id);
		$this->db->delete('knowledgebase_category');
		foreach ($categories as $cat) {
			$this->db->insert('knowledgebase_category', [
				'knowledgebase_id' => $knowledgebase_id,
				'category_id' => $cat['id'],
			]);
		}
	}

	public function save_pricing_plans($pricing_plans, $knowledgebase_id) {
		$this->db->where('knowledgebase_id', $knowledgebase_id);
		$this->db->delete('knowledgebase_plan');
		foreach ($pricing_plans as $plan) {
			$this->db->insert('knowledgebase_plan', [
				'knowledgebase_id' => $knowledgebase_id,
				'pricing_plan_id' => $plan['id'],
			]);
		}
	}

	public function get_categories_by_id($pricing_plan_id) {
		$this->db->distinct();
		$this->db->select('c.name, c.category_id AS id, c.category_guid');
		$this->db->from('knowledgebase AS k');
		$this->db->join('knowledgebase_plan AS kp', 'k.knowledgebase_id = kp.knowledgebase_id');
		$this->db->join('knowledgebase_category AS kc', 'k.knowledgebase_id = kc.knowledgebase_id');
		$this->db->join('category_master AS c', 'c.category_id = kc.category_id');
		$this->db->where('kp.pricing_plan_id', $pricing_plan_id);
		$query = $this->db->get();
		$results = $query->result_array();
		// echo $this->db->last_query();die();
		return $results;
	}

	public function update_knowledgebase($knowledgebase_id, $name, $status, $video_embed_url, $knowledgebase_media_id, $date, $description) {
		$update_array = [
			"name" => $name,
			"status" => $status,
			"video_embed_url" => $video_embed_url,
			"knowledgebase_media_id" => $knowledgebase_media_id,
			"description" => $description,
			"updated_at" => DATETIME,
		];

		$this->db->update('knowledgebase', $update_array, ['knowledgebase_id' => $knowledgebase_id]);
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
	}

	public function knowledgebase_delete($knowledgebase_id, $delete_type = 'STATUS_DELETED') {
		if ($delete_type == 'STATUS_DELETED') {
			$this->db->update('knowledgebase', ['status' => 'DELETED'], ['knowledgebase_id' => $knowledgebase_id]);
		} else {
			$this->db->where('knowledgebase_id', $knowledgebase_id);
			$this->db->delete('knowledgebase');
		}
	}

	public function get_knowledgebase_details_by_id($knowledgebase_id) {
		$this->db->select('kb.name, kb.status, kb.knowledgebase_media_id, kb.created_at, kb.updated_at');
		$this->db->select('IFNULL(kb.video_embed_url,"") AS video_embed_url', FALSE);
		$this->db->select('IFNULL(kb.description,"") AS description', FALSE);
		$this->db->select('IFNULL(m.name,"") AS file_name', FALSE);
		$this->db->select('IFNULL(m.media_guid,"") AS media_guid', FALSE);
		$this->db->from('knowledgebase AS kb');
		$this->db->join('media AS m', 'kb.knowledgebase_media_id = m.media_id', 'LEFT');
		$this->db->where('knowledgebase_id', $knowledgebase_id);
		$query = $this->db->get();
		$knowledgebase = $query->row_array();
		$knowledgebase['categories'] = $this->get_kb_categories($knowledgebase_id);
		$knowledgebase['pricing_plans'] = $this->get_kb_pricing_plans($knowledgebase_id);
		if (!empty($knowledgebase['file_name'])) {
			$knowledgebase['file_name'] = site_url('/uploads/images/' . $knowledgebase['file_name']);
		}
		unset($knowledgebase['knowledgebase_media_id']);
		return $knowledgebase;
	}

	public function get_kb_pricing_plans($knowledgebase_id) {
		$this->db->select('pp.name');
		$this->db->select('pp.pricing_plan_id as id');
		$this->db->from('knowledgebase_plan AS kp');
		$this->db->join('pricing_plans AS pp', 'pp.pricing_plan_id = kp.pricing_plan_id');
		$this->db->where('kp.knowledgebase_id', $knowledgebase_id);
		$query = $this->db->get();
		$kb_pricing_plan_id = $query->result_array();
		return $kb_pricing_plan_id;
	}

	public function get_kb_categories($knowledgebase_id) {
		$this->db->select('kc.category_id as id, cm.name');
		$this->db->from('knowledgebase_category AS kc');
		$this->db->join('category_master AS cm', 'cm.category_id = kc.category_id');
		$this->db->where('kc.knowledgebase_id', $knowledgebase_id);
		$query = $this->db->get();
		$kb_categories = $query->result_array();
		// echo $this->db->last_query();die();
		return $kb_categories;
	}

	public function get_knowledgebase_list($limit = 0, $offset = 0, $keyword = '', $sort_field, $sort_order) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('kb.knowledgebase_guid AS knowledgebase_id, kb.knowledgebase_id AS knowledgebaseid, kb.name, kb.status, kb.created_at, kb.updated_at');
			$this->db->select('IFNULL(kb.video_embed_url,"") AS video_embed_url', FALSE);
			$this->db->select('IFNULL(kb.description,"") AS description', FALSE);
			$this->db->select('IFNULL(m.name,"") AS file_name', FALSE);
		} else {
			$this->db->select('COUNT(kb.knowledgebase_id) as count', FALSE);
		}
		$this->db->from('knowledgebase AS kb');
		$this->db->join('media AS m', 'kb.knowledgebase_media_id = m.media_id', 'LEFT');
		if (!empty($keyword)) {
			$this->db->like('kb.name', $keyword, 'both');
		}
		// if (!empty($filterBy)) {
		// 	$this->db->like('kb.status', $filterBy);
		// }
		if (($sort_field !== '') && ($sort_order !== '')) {
			$this->db->order_by('kb.' . $sort_field, $sort_order);
		} else {
			$this->db->order_by('kb.created_at', 'desc');
		}
		$query = $this->db->get();
		$list = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				foreach ($list as $key => $row) {
					$list[$key]['categories'] = $this->get_categories($list[$key]['knowledgebaseid']);
					unset($list[$key]['knowledgebaseid']);
				}
				return $list;
			} else {
				return [];
			}

		} else {
			return $query->row()->count;
		}
	}

	public function get_categories($knowledgebase_id) {

		$this->db->select('cm.name as category_name');

		$this->db->from('category_master AS cm');
		$this->db->join('knowledgebase_category AS kc', 'kc.category_id = cm.category_id');
		$this->db->where('kc.knowledgebase_id', $knowledgebase_id);
		$query = $this->db->get();
		// echo $this->db->last_query();die();
		$results = $query->result_array();
		return $results;
	}

	public function update_media_staus($media_id) {
		$this->db->update('media', ['status' => 'PENDING'], ['media_id' => $media_id]);
		return TRUE;
	}
	public function user_knowledgebase_list($user_id, $keyword = '', $limit = 0, $offset = 0, $column_name, $order_by, $categories, $category_id, $pricing_plan_id) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('DISTINCT(kb.knowledgebase_id)', FALSE);
			$this->db->select('IFNULL(kb.knowledgebase_guid,"") AS knowledgebase_guid', FALSE);
			$this->db->select('IFNULL(kb.knowledgebase_id,"") AS knowledgebase_id', FALSE);
			$this->db->select('IFNULL(kb.name,"") AS name', FALSE);
			// $this->db->select('IFNULL(cm.name,"") AS category', FALSE);
			$this->db->select('IFNULL(kb.video_embed_url,"") AS video_embed_url', FALSE);
			$this->db->select('IFNULL(m.name,"") AS media_name', FALSE);
			$this->db->select('IFNULL(kb.description,"") AS description', FALSE);
			$this->db->select('IFNULL(kb.created_at,"") AS created_at', FALSE);
			$this->db->select('IFNULL(kb.updated_at,"") AS updated_at', FALSE);
		} else {
			$this->db->select('DISTINCT(kb.knowledgebase_id)', FALSE);
			$this->db->select('COUNT(kb.knowledgebase_id) as count', FALSE);
		}
		$this->db->from('knowledgebase AS kb');
		$this->db->join('media AS m', 'm.media_id = kb.knowledgebase_media_id', 'LEFT');
		$this->db->join('knowledgebase_category AS kc', 'kc.knowledgebase_id = kb.knowledgebase_id', 'LEFT');
		$this->db->join('knowledgebase_plan AS kp', 'kp.knowledgebase_id = kb.knowledgebase_id', 'LEFT');
		$this->db->where('kb.status', 'ACTIVE');
		$this->db->order_by('kb.created_at', 'desc');

		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('kb.name', $keyword, 'both');
			$this->db->group_end();
		}

		if(!empty($pricing_plan_id)) {
			$this->db->where('kp.pricing_plan_id', $pricing_plan_id);
		}

		if (!empty($category_id)) {
			$this->db->where('kc.category_id', $category_id);
		} else {
			$this->db->where_in('kc.category_id', $categories);
		}

		if (($column_name !== '') && ($order_by !== '')) {
			$this->db->order_by('kb.' . $column_name, $order_by);
		}
		$query = $this->db->get();
		$results = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				$list = [];
				foreach ($results as $key => $value) {
					$list[$key]['knowledgebase_id'] = $value['knowledgebase_guid'];
					$list[$key]['name'] = $value['name'];
					$list[$key]['video_embed_url'] = $value['video_embed_url'];
					$list[$key]['media_name'] = $value['media_name'] ? site_url('/uploads/images/' . $value['media_name']) : "";
					$list[$key]['description'] = $value['description'];
					$list[$key]['created_at'] = $value['created_at'];
					$list[$key]['updated_at'] = $value['updated_at'];
					$list[$key]['is_url'] = $value['video_embed_url'] ? "TRUE" : "FALSE";
					$list[$key]['categories'] = $this->get_categories($value['knowledgebase_id']);
				}
				return $list;
			} else {
				return [];
			}

		} else {
			return $query->row()->count;
		}
	}

	public function get_user_knowledgebase_detail($knowledgebase_guid) {
		// $this->db->select('IFNULL(kb.knowledgebase_guid,"") AS knowledgebase_guid', FALSE);
		$this->db->select('IFNULL(kb.knowledgebase_id,"") AS knowledgebase_id', FALSE);
		$this->db->select('IFNULL(kb.name,"") AS name', FALSE);
		// $this->db->select('IFNULL(cm.name,"") AS category', FALSE);
		$this->db->select('IFNULL(kb.video_embed_url,"") AS video_embed_url', FALSE);
		$this->db->select('IFNULL(m.name,"") AS media_name', FALSE);
		$this->db->select('IFNULL(kb.description,"") AS description', FALSE);
		$this->db->select('IFNULL(kb.created_at,"") AS created_at', FALSE);
		$this->db->select('IFNULL(kb.updated_at,"") AS updated_at', FALSE);
		$this->db->from('knowledgebase AS kb');
		$this->db->join('media AS m', 'm.media_id = kb.knowledgebase_media_id', 'LEFT');
		// $this->db->join('category_master AS cm', 'cm.category_id = kb.category_id', 'LEFT');
		$this->db->where('kb.knowledgebase_guid', $knowledgebase_guid);
		$query = $this->db->get();
		$result = $query->row_array();
		$result['media_name'] = $result['media_name'] ? site_url('/uploads/images/' . $result['media_name']) : "";
		$result['is_url'] = $result['video_embed_url'] ? "TRUE" : "FALSE";
		$result['categories'] = $this->get_categories($result['knowledgebase_id']);
		unset($result['knowledgebase_id']);
		return $result;
	}

	public function related_stuff_list($user_id, $knowledgebase_guid, $category_ids, $limit = 0, $offset = 0, $column_name, $order_by) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('IFNULL(kb.knowledgebase_guid,"") AS knowledgebase_guid', FALSE);
			$this->db->select('IFNULL(kb.name,"") AS name', FALSE);
			// $this->db->select('IFNULL(cm.name,"") AS category', FALSE);
			$this->db->select('IFNULL(kb.video_embed_url,"") AS video_embed_url', FALSE);
			$this->db->select('IFNULL(m.name,"") AS media_name', FALSE);
			$this->db->select('IFNULL(kb.description,"") AS description', FALSE);
			$this->db->select('IFNULL(kb.created_at,"") AS created_at', FALSE);
			$this->db->select('IFNULL(kb.updated_at,"") AS updated_at', FALSE);
		} else {
			$this->db->select('COUNT(kb.knowledgebase_id) as count', FALSE);
		}
		$this->db->from('knowledgebase AS kb');
		$this->db->join('media AS m', 'm.media_id = kb.knowledgebase_media_id', 'LEFT');
		$this->db->join('knowledgebase_category AS kc', 'kc.knowledgebase_id = kb.knowledgebase_id', 'LEFT');
		// $this->db->join('category_master AS cm', 'cm.category_id = kb.category_id', 'LEFT');
		$this->db->where('kb.status', 'ACTIVE');
		$this->db->where('kb.knowledgebase_guid!=', $knowledgebase_guid);
		$this->db->where_in('kc.category_id', $category_ids);
		$this->db->order_by('kb.created_at', 'desc');

		// if (!empty($filterBy)) {
		// 	$this->db->like('u.status', $filterBy);
		// }
		// echo $user_type;die();
		// $this->db->where('u.user_type', $user_type);
		// $this->db->where('u.status!= "DELETED"');

		// if (!empty($keyword)) {
		// 	$this->db->group_start();
		// 	$this->db->like('kb.name', $keyword, 'both');
		// 	$this->db->group_end();
		// }

		// if (!empty($filter_type)) {
		// 	$this->db->where('u.device_type_id', $filter_type);
		// }

		if (($column_name !== '') && ($order_by !== '')) {
			$this->db->order_by('kb.' . $column_name, $order_by);
		}
		$query = $this->db->get();
		$results = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				$list = [];
				foreach ($results as $key => $value) {
					$list[$key]['knowledgebase_id'] = $value['knowledgebase_guid'];
					$list[$key]['name'] = $value['name'];
					// $list[$key]['category'] = $value['category'];
					$list[$key]['video_embed_url'] = $value['video_embed_url'];
					$list[$key]['media_name'] = $value['media_name'] ? site_url('/uploads/images/' . $value['media_name']) : "";
					$list[$key]['description'] = $value['description'];
					$list[$key]['created_at'] = $value['created_at'];
					$list[$key]['updated_at'] = $value['updated_at'];
					$list[$key]['is_url'] = $value['video_embed_url'] ? "TRUE" : "FALSE";
				}
				return $list;
			} else {
				return [];
			}

		} else {
			return $query->row()->count;
		}
	}

	public function change_status($knowledgebase_guid, $status) {
		$data = [
			"status" => $status,
			'updated_at' => DATETIME,
		];
		$this->db->update('knowledgebase', $data, [
			'knowledgebase_guid' => $knowledgebase_guid,
		]);
		return true;
	}

}
