<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Site_manage_model extends CI_Model {

	public function __construct() {
		// Call the CI_Model constructor
		parent::__construct();
	}

	public function get_category_list($keyword, $limit, $offset, $column_name, $order_by) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('cm.category_guid, cm.name');
		} else {
			$this->db->select('COUNT(cm.category_id) as count', FALSE);
		}
		$this->db->from('category_master AS cm');
		$this->db->order_by('cm.name', 'asc');

		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('cm.name', $keyword, 'both');
			$this->db->group_end();
		}
		if (($column_name !== '') && ($order_by !== '')) {
			$this->db->order_by('cm.' . $column_name, $order_by);
		}
		$query = $this->db->get();
		$results = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				$list = [];
				foreach ($results as $key => $value) {
					$list[$key]['category_guid'] = $value['category_guid'];
					$list[$key]['name'] = $value['name'];
				}
				return $list;
			} else {
				return [];
			}

		} else {
			return $query->row()->count;
		}
	}

	public function create_category($name) {
		$insert_array = [
			"category_guid" => get_guid(),
			"name" => $name,
		];
		$this->db->insert('category_master', $insert_array);
		$category_id = $this->db->insert_id();
		return $category_id;
	}

	public function update_category($category_id, $name) {
		$update_array = [
			"name" => $name,
		];
		$this->db->update('category_master', $update_array, ['category_id' => $category_id]);
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
	}

	public function get_category_details_by_id($category_id) {
		$this->db->select('cm.category_guid, cm.name');
		$this->db->from('category_master AS cm');
		$this->db->where('category_id', $category_id);
		$query = $this->db->get();
		$category = $query->row_array();
		return $category;
	}

	public function get_plans($type) {
		$this->db->select('pp.pricing_plan_guid AS id, pp.name');
		$this->db->from('pricing_plans AS pp');
		$this->db->where('pp.type', $type);
		$this->db->order_by('name', 'ACS');
		$query = $this->db->get();
		$results = $query->result_array();
		return $results;
	}

	// public API for latest 10 users https://www.tikisites.com/webhost-api/index.php/api/share/get_users_list

	public function get_pricing_plans($keyword, $limit, $offset, $column_name, $order_by) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('IFNULL(p.pricing_plan_guid,"") AS pricing_plan_guid', FALSE);
			$this->db->select('IFNULL(p.stripe_pricing_plan_id,"") AS stripe_pricing_plan_id', FALSE);
			$this->db->select('IFNULL(p.type,"") AS type', FALSE);
			$this->db->select('IFNULL(p.name,"") AS name', FALSE);
			$this->db->select('IFNULL(p.base_price,"") AS base_price', FALSE);
			$this->db->select('IFNULL(p.subscription_count,"") AS subscription_count', FALSE);
			$this->db->select('IFNULL(p.status,"") AS status', FALSE);
			$this->db->select('IFNULL(p.is_archive,"") AS is_archive', FALSE);
			$this->db->select('IFNULL(p.discount,"") AS discount', FALSE);
			$this->db->select('IFNULL(p.created_at,"") AS created_at', FALSE);
			$this->db->select('IFNULL(p.updated_at,"") AS updated_at', FALSE);
			$this->db->select('p.interval');
		} else {
			$this->db->select('COUNT(p.pricing_plan_id) as count', FALSE);
		}
		$this->db->from('pricing_plans AS p');
		$this->db->where('p.is_archive', 'NO');
		$this->db->where('p.status !=', 'DELETED');
		$this->db->order_by('p.created_at', 'desc');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('p.name', $keyword, 'both');
			$this->db->or_like('p.stripe_pricing_plan_id', $keyword, 'both');
			$this->db->group_end();
		}

		if (($column_name !== '') && ($order_by !== '')) {
			$this->db->order_by('u.' . $column_name, $order_by);
		}
		$query = $this->db->get();
		$results = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				$list = [];
				foreach ($results as $key => $value) {
					$list[$key]['pricing_plan_guid'] = $value['pricing_plan_guid'];
					$list[$key]['stripe_pricing_plan_id'] = $value['stripe_pricing_plan_id'];
					$list[$key]['type'] = $value['type'];
					$list[$key]['name'] = $value['name'];
					$list[$key]['base_price'] = $value['base_price'];
					$list[$key]['subscription_count'] = (int)$value['subscription_count'];
					$list[$key]['status'] = $value['status'];
					$list[$key]['is_archive'] = $value['is_archive'];
					$list[$key]['discount'] = $value['discount'];
					$list[$key]['created_at'] = $value['created_at'];
					$list[$key]['updated_at'] = $value['updated_at'];
					$list[$key]['interval'] = $value['interval'];
				}
				return $list;
			} else {
				return [];
			}

		} else {
			return $query->row()->count;
		}
	}

	public function get_pricing_plans_by_type($type) {
		
		$this->db->select('IFNULL(p.pricing_plan_guid,"") AS pricing_plan_guid', FALSE);
		$this->db->select('IFNULL(p.name,"") AS name', FALSE);
		$this->db->from('pricing_plans AS p');
		$this->db->where('p.type', $type);
		$this->db->where('p.is_archive', 'NO');
		$query = $this->db->get();
		$results = $query->result_array();
		return $results;
	}

	public function create_pricing_plan($stripe_pricing_plan_id ,$base_price ,$name, $type, $interval, $note, $discount, $campaign_limit) {
		$insert_array = [
			"pricing_plan_guid" => get_guid(),
			"stripe_pricing_plan_id" => $stripe_pricing_plan_id,
			"base_price" => $base_price,
			"name" => $name,
			"type" => $type,
			"interval" => $interval,
			"note" => $note,
			"discount" => $discount,
			"campaign_limit" => $campaign_limit,
			"created_at" => DATETIME,
		];
		$this->db->insert('pricing_plans', $insert_array);
		$category_id = $this->db->insert_id();
		return $category_id;
	}

	public function retired_pricing_plan($pricing_plan_id, $subscription_count) {
		$this->db->update('pricing_plans', ['is_archive' => 'YES', 'subscription_count' => $subscription_count], ['pricing_plan_id' => $pricing_plan_id]);
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
	}

	public function update_pricing_plan($pricing_plan_id, $name, $note, $discount) {
		$update_array = [
			"name" => $name,
			"note" => $note,
			"discount" => $discount
		];
		$this->db->update('pricing_plans', $update_array, ['pricing_plan_id' => $pricing_plan_id]);
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
	}

	public function get_pricing_plan_details($pricing_plan_id) {
		$this->db->select('IFNULL(pp.pricing_plan_guid,"") AS pricing_plan_guid', FALSE);
		$this->db->select('IFNULL(pp.stripe_pricing_plan_id,"") AS stripe_pricing_plan_id', FALSE);
		$this->db->select('IFNULL(pp.type,"") AS type', FALSE);
		$this->db->select('IFNULL(pp.name,"") AS name', FALSE);
		$this->db->select('IFNULL(pp.base_price,"") AS base_price', FALSE);
		$this->db->select('IFNULL(pp.note,"") AS note', FALSE);
		$this->db->select('IFNULL(pp.discount,"") AS discount', FALSE);
		$this->db->select('pp.interval');
		$this->db->from('pricing_plans AS pp');
		$this->db->where('pricing_plan_id', $pricing_plan_id);
		$query = $this->db->get();
		$category = $query->row_array();
		return $category;
	}

	public function migrate_all_subscribers($old_pricing_plan_id, $stripe_pricing_plan_id, $new_pricing_plan_id) {
		$this->db->select('IFNULL(s.subscription_guid,"") AS subscription_guid', FALSE);
		$this->db->select('IFNULL(s.user_id,"") AS user_id', FALSE);
		$this->db->select('IFNULL(s.stripe_subscription_id,"") AS stripe_subscription_id', FALSE);
		$this->db->select('IFNULL(s.stripe_pricing_plan_id,"") AS stripe_pricing_plan_id', FALSE);
		$this->db->from('subscriptions AS s');
		$this->db->where('pricing_plan_id', $old_pricing_plan_id);
		$query = $this->db->get();
		$results = $query->result_array();
		$this->load->model("campaign_model");
		$this->load->model("users_model");
		$subscription_count = 0;
		foreach ($results as $key => $value) {
			$subscription_details = $this->users_model->update_plan($value['stripe_subscription_id'], $stripe_pricing_plan_id);
			if ($subscription_details['status'] == "success") {
				$subscription_count++;
				$this->users_model->update_user_plan($value['user_id'], $new_pricing_plan_id);
				$this->campaign_model->update_user_campaigns($value['user_id'], $new_pricing_plan_id);
				$this->users_model->update_subscription_count($new_pricing_plan_id, $subscription_count);
			}
		}
		return;
	}

	public function delete_pricing_plan($pricing_plan_id) {
		$this->db->update('pricing_plans', ['status' => 'DELETED'], ['pricing_plan_id' => $pricing_plan_id]);
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
	}

	public function change_pricing_plan_status($pricing_plan_id, $status) {
		$data = [
			"status" => $status,
			'updated_at' => DATETIME,
		];
		$this->db->update('pricing_plans', $data, [
			'pricing_plan_id' => $pricing_plan_id,
		]);
		return true;
	}
}
