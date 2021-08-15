<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Campaign_model extends CI_Model {
	public function __construct() {
		// Call the CI_Model constructor
		parent::__construct();
	}

	public function add_campaign($user_id, $campaign_name, $campaign_goal, $campaign_live_date, $is_landing_page, $is_qr_code, $is_call_tracking_number) {
		$this->db->insert('campaigns', [
			"campaign_guid" => get_guid(),
			"added_by" => $user_id,
			"campaign_name" => $campaign_name,
			"campaign_goal" => $campaign_goal,
			"status" => 'INACTIVE',
			"campaign_live_date" => $campaign_live_date,
			"is_landing_page" => $is_landing_page,
			"is_qr_code" => $is_qr_code,
			"is_call_tracking_number" => $is_call_tracking_number,
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		]);
		return $this->db->insert_id();
	}

	public function edit_campaign($campaign_id, $campaign_name, $campaign_goal, $campaign_live_date, $is_landing_page, $is_qr_code, $is_call_tracking_number, $qr_code_name, $qr_code_url, $campaign_template_id, $tracking_number_json, $tracking_number_id, $number_type, $area_code) {
		$data = [
			"campaign_name" => $campaign_name,
			"campaign_goal" => $campaign_goal,
			"campaign_live_date" => $campaign_live_date,
			"is_landing_page" => $is_landing_page,
			"is_qr_code" => $is_qr_code,
			"is_call_tracking_number" => $is_call_tracking_number,
			"qr_code_name" => $qr_code_name,
			"qr_code_url" => $qr_code_url,
			"campaign_template_id" => $campaign_template_id,
			"tracking_number_json" => $tracking_number_json,
			"tracking_number_id" => $tracking_number_id,
			"number_type" => $number_type,
			"area_code" => $area_code,
			"updated_at" => DATETIME,
		];

		$this->db->update('campaigns', $data, array(
			'campaign_id' => $campaign_id,
		));
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
	}

	public function delete_campaign_template($campaign_template_id) {
		$this->db->where('campaign_template_id', $campaign_template_id);
		$this->db->delete('campaign_templates');
		return TRUE;
	}

	public function get_details_by_id($campaign_id) {
		$this->db->select('IFNULL(c.campaign_guid,"") AS campaign_guid', FALSE);
		$this->db->select('IFNULL(c.campaign_name,"") AS campaign_name', FALSE);
		$this->db->select('IFNULL(c.campaign_goal,"") AS campaign_goal', FALSE);
		$this->db->select('IFNULL(c.status,"") AS status', FALSE);
		$this->db->select('IFNULL(c.campaign_live_date,"") AS campaign_live_date', FALSE);
		$this->db->select('IFNULL(c.is_landing_page,"") AS is_landing_page', FALSE);
		$this->db->select('IFNULL(c.is_qr_code,"") AS is_qr_code', FALSE);
		$this->db->select('IFNULL(c.is_call_tracking_number,"") AS is_call_tracking_number', FALSE);
		$this->db->select('IFNULL(c.qr_code_name,"") AS qr_code_name', FALSE);
		$this->db->select('IFNULL(c.qr_code_url,"") AS qr_code_url', FALSE);
		$this->db->select('IFNULL(c.number_type,"") AS number_type', FALSE);
		$this->db->select('IFNULL(c.tracking_number_id,"") AS tracking_number_id', FALSE);
		$this->db->select('IFNULL(c.area_code,"") AS area_code', FALSE);
		$this->db->select('IFNULL(c.created_at,"") AS created_at', FALSE);
		$this->db->select('IFNULL(ct.campaign_template_guid,"") AS campaign_template_guid', FALSE);
		$this->db->select('IFNULL(ct.page_title,"") AS page_title', FALSE);
		$this->db->select('IFNULL(ct.email_receiver,"") AS email_receiver', FALSE);
		$this->db->select('IFNULL(ct.page_url,"") AS page_url', FALSE);
		$this->db->select('IFNULL(ct.page_script,"") AS page_script', FALSE);
		$this->db->select('IFNULL(ct.custom_page_script,"") AS custom_page_script', FALSE);
		$this->db->select('IFNULL(ct.unique_string,"") AS unique_string', FALSE);
		$this->db->select('IFNULL(ct.template_values,"") AS template_values', FALSE);
		$this->db->select('IFNULL(t.template_guid,"") AS template_guid', FALSE);
		$this->db->select('IFNULL(m.name,"") AS media_name', FALSE);
		$this->db->from('campaigns AS c');
		$this->db->join('campaign_templates AS ct', 'ct.campaign_template_id = c.campaign_template_id', 'LEFT');
		$this->db->join('media AS m', 'm.media_id = ct.preview_media_id', 'LEFT');
		$this->db->join('templates AS t', 't.template_id = ct.template_id', 'LEFT');
		$this->db->where('c.campaign_id', $campaign_id);
		$query = $this->db->get();
		$reuslt = $query->row_array();
		if ($reuslt['media_name']) {
			$reuslt['preview_url'] = site_url('uploads/' . $reuslt['media_name']);
		} else {
			$reuslt['preview_url'] = '';
		}

		if ($reuslt['qr_code_name']) {
			$reuslt['qr_code_image_url'] = site_url('uploads/qr_codes/' . $reuslt['qr_code_name']);
		} else {
			$reuslt['qr_code_image_url'] = '';
		}
		// $reuslt['string'] = unique_random_string('campaign_templates', 'unique_string', [], 'alnum', 12);
		return $reuslt;
	}

	public function list($user_id, $keyword = '', $limit = 0, $offset = 0, $column_name, $order_by, $user_type) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('IFNULL(c.campaign_id,"") AS campaign_id', FALSE);
			$this->db->select('IFNULL(c.campaign_guid,"") AS campaign_guid', FALSE);
			$this->db->select('IFNULL(c.campaign_name,"") AS campaign_name', FALSE);
			$this->db->select('IFNULL(c.campaign_goal,"") AS campaign_goal', FALSE);
			$this->db->select('IFNULL(c.status,"") AS status', FALSE);
			$this->db->select('IFNULL(c.campaign_live_date,"") AS campaign_live_date', FALSE); 
			$this->db->select('IFNULL(c.is_landing_page,"") AS is_landing_page', FALSE);
			$this->db->select('IFNULL(c.is_call_tracking_number,"") AS is_call_tracking_number', FALSE);
			$this->db->select('IFNULL(c.is_qr_code,"") AS is_qr_code', FALSE);
			$this->db->select('IFNULL(c.tracking_data,"") AS tracking_data', FALSE);
			$this->db->select('IFNULL(ct.campaign_template_guid,"") AS campaign_template_guid', FALSE);
			$this->db->select('IFNULL(ct.template_values,"") AS template_values', FALSE);
			$this->db->select('IFNULL(ct.page_title,"") AS page_title', FALSE);
			$this->db->select('IFNULL(ct.email_receiver,"") AS email_receiver', FALSE);
			$this->db->select('IFNULL(ct.page_url,"") AS page_url', FALSE);
			$this->db->select('IFNULL(ct.page_script,"") AS page_script', FALSE);
			$this->db->select('IFNULL(c.created_at,"") AS created_at', FALSE);
			$this->db->select('IFNULL(c.updated_at,"") AS updated_at', FALSE);
			$this->db->select('IFNULL(m.name,"") AS media_name', FALSE);
		} else {
			$this->db->select('COUNT(c.campaign_id) as count', FALSE);
		}
		$this->db->from('campaigns AS c');
		$this->db->join('campaign_templates AS ct', 'ct.campaign_template_id = c.campaign_template_id', 'LEFT');
		$this->db->join('media AS m', 'm.media_id = ct.preview_media_id', 'LEFT');
		$this->db->order_by('c.created_at', 'desc');

		// if (!empty($filterBy)) {
		// 	$this->db->like('u.status', $filterBy);
		// }

		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('c.campaign_name', $keyword, 'both');
			$this->db->group_end();
		}

		if (($column_name !== '') && ($order_by !== '')) {
			$this->db->order_by('c.' . $column_name, $order_by);
		}
		if ($user_type != 'ADMIN') {
			$this->db->where('c.added_by', $user_id);
		}
		
		$query = $this->db->get();
		$results = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				$list = [];
				foreach ($results as $key => $value) {
					$list[$key]['campaign_guid'] = $value['campaign_guid'];
					$list[$key]['campaign_name'] = $value['campaign_name'];
					$list[$key]['campaign_goal'] = $value['campaign_goal'];
					$list[$key]['status'] = $value['status'];
					$list[$key]['campaign_live_date'] = $value['campaign_live_date'];
					$list[$key]['is_landing_page'] = $value['is_landing_page'];
					$list[$key]['is_call_tracking_number'] = $value['is_call_tracking_number'];
					$list[$key]['is_qr_code'] = $value['is_qr_code'];
					$list[$key]['campaign_template_guid'] = $value['campaign_template_guid'];
					$list[$key]['template_values'] = $value['template_values'];
					$list[$key]['page_title'] = $value['page_title'];
					$list[$key]['email_receiver'] = $value['email_receiver'];
					$list[$key]['page_url'] = $value['page_url'];
					$list[$key]['page_script'] = $value['page_script'];
					if ($value['media_name']) {
						$list[$key]['preview_url'] = site_url('uploads/' . $value['media_name']);
					} else {
						$list[$key]['preview_url'] = '';
					}
					if ($value['tracking_data']) {
						$tracking_data = json_decode($value['tracking_data'], true); 
						$list[$key]['tracking_data'] = $tracking_data;
						$list[$key]['total_leads'] = $this->getTotalLeads($list[$key]['tracking_data']);
					} else {
						$list[$key]['tracking_data'] = '';
						$list[$key]['total_leads'] = 0;
					}
					$list[$key]['qr_leads'] = $this->get_campaign_leads($value['campaign_id'], TRUE);
					$list[$key]['landing_page_leads'] = $this->get_campaign_leads($value['campaign_id'], FALSE);
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
	
	public function getTotalLeads($tracking_data) {
		$landing_page_leads = safe_array_key($tracking_data['landing_page'], "leads", 0);
		$qr_code_leads = safe_array_key($tracking_data['qr_code'], "leads", 0);
		$total_leads = (int)$landing_page_leads + (int)$qr_code_leads;
		return $total_leads;
	}

	public function template_list($keyword = '', $limit = 0, $offset = 0, $column_name, $order_by) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('IFNULL(t.template_guid,"") AS template_guid', FALSE);
			$this->db->select('IFNULL(t.name,"") AS name', FALSE);
			$this->db->select('IFNULL(t.template_unique_name,"") AS template_unique_name', FALSE);
			$this->db->select('IFNULL(t.template_image_name,"") AS template_image_name', FALSE);
			$this->db->select('IFNULL(t.default_values,"") AS default_values', FALSE);
			$this->db->select('IFNULL(t.description,"") AS description', FALSE);
			$this->db->select('IFNULL(t.created_at,"") AS created_at', FALSE);
			$this->db->select('IFNULL(t.updated_at,"") AS updated_at', FALSE);
		} else {
			$this->db->select('COUNT(t.template_id) as count', FALSE);
		}
		$this->db->from('templates AS t');
		// $this->db->order_by('name', 'ASC');
		$this->db->order_by('created_at', 'ASC');

		// if (!empty($filterBy)) {
		// 	$this->db->like('u.status', $filterBy);
		// }

		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('t.name', $keyword, 'both');
			$this->db->group_end();
		}

		if (($column_name !== '') && ($order_by !== '')) {
			$this->db->order_by('t.' . $column_name, $order_by);
		}
		$query = $this->db->get();
		// echo $this->db->last_query();die();
		$results = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				$list = [];
				foreach ($results as $key => $value) {
					$list[$key]['template_guid'] = $value['template_guid'];
					$list[$key]['name'] = $value['name'];
					$list[$key]['template_unique_name'] = $value['template_unique_name'];
					$list[$key]['default_values'] = $value['default_values'];
					$list[$key]['description'] = $value['description'];
					$list[$key]['image_url'] = site_url('assets/templates/' . $value['template_image_name']);
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

	public function add_campaign_qr_code($redirect_url, $unique_string) {
		$data = [
			"campaign_template_guid" => get_guid(),
			"page_url" => $redirect_url,
			"unique_string" => $unique_string,
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		];

		$this->db->insert('campaign_templates', $data);

		return $this->db->insert_id();
	}

	public function update_campaign_qr($campaign_template_id, $redirect_url, $unique_string) {
		$data = [
			"page_url" => $redirect_url,
			"unique_string" => $unique_string
		];

		$this->db->update('campaign_templates', $data, array(	
			'campaign_template_id' => $campaign_template_id,
		));
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
	}

	public function add_campaign_template($template_id, $template_values) {
		$data = [
			"campaign_template_guid" => get_guid(),
			"template_id" => $template_id,
			"template_values" => $template_values,
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		];

		$this->db->insert('campaign_templates', $data);

		return $this->db->insert_id();
	}

	public function update_template($campaign_id, $campaign_template_id) {
		$data = [
			"campaign_template_id" => $campaign_template_id,
		];

		$this->db->update('campaigns', $data, array(
			'campaign_id' => $campaign_id,
		));
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
	}

	public function update_campaign_template($campaign_template_id, $template_id, $template_values) {
		$data = [
			"template_id" => $template_id,
			"template_values" => $template_values
		];

		$this->db->update('campaign_templates', $data, array(	
			'campaign_template_id' => $campaign_template_id,
		));
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
	}

	// public function update_default_values($template_id, $default_values) {
	// 	$data = [
	// 		"default_values" => $default_values
	// 	];

	// 	$this->db->update('templates', $data, array(	
	// 		'template_id' => $template_id,
	// 	));
	// 	$affected_rows_count = $this->db->affected_rows();
	// 	return $affected_rows_count;
	// }

	public function get_template_details($campaign_id) {
		$this->db->select('IFNULL(ct.template_values,"") AS template_values', FALSE);
		$this->db->select('IFNULL(c.campaign_name,"") AS campaign_name', FALSE);
		$this->db->select('IFNULL(c.is_landing_page,"") AS is_landing_page', FALSE);
		$this->db->select('IFNULL(c.is_qr_code,"") AS is_qr_code', FALSE);
		$this->db->select('IFNULL(c.is_call_tracking_number,"") AS is_call_tracking_number', FALSE);
		$this->db->select('IFNULL(t.name,"") AS template_name', FALSE);
		$this->db->select('IFNULL(t.template_unique_name,"") AS template_unique_name', FALSE);
		$this->db->select('IFNULL(t.default_values,"") AS default_values', FALSE);
		$this->db->from('campaign_templates AS ct');
		$this->db->join('campaigns AS c', 'ct.campaign_template_id = c.campaign_template_id', 'LEFT');
		$this->db->join('templates AS t', 't.template_id = ct.template_id', 'LEFT');
		$this->db->where('c.campaign_id', $campaign_id);
		$query = $this->db->get();
		$reuslt = $query->row_array();
		$reuslt['default_values'] = json_decode($reuslt['default_values']);
		return $reuslt;
	}

	public function update_template_details($campaign_template_id, $template_values) {
		$data = [
			"template_values" => $template_values,
		];

		$this->db->update('campaign_templates', $data, array(	
			'campaign_template_id' => $campaign_template_id,
		));
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
	}

	public function update_campaign_setting($campaign_template_id, $page_title, $email_receiver, $page_url, $custom_page_script) {
		$data = [
			"page_title" => $page_title,
			"email_receiver" => $email_receiver,
			"page_url" => $page_url,
			"custom_page_script" => $custom_page_script,
		];

		$this->db->update('campaign_templates', $data, array(	
			'campaign_template_id' => $campaign_template_id,
		));
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
	}

	public function get_details_by_campaign_id($campaign_id) {
		$this->db->select('IFNULL(c.campaign_guid,"") AS campaign_guid', FALSE);
		$this->db->select('IFNULL(ct.campaign_template_guid,"") AS campaign_template_guid', FALSE);
		$this->db->select('IFNULL(ct.page_title,"") AS page_title', FALSE);
		$this->db->select('IFNULL(ct.page_script,"") AS page_script', FALSE);
		$this->db->select('IFNULL(m.name,"") AS media_name', FALSE);
		$this->db->select('IFNULL(ct.template_values,"") AS template_values', FALSE);
		$this->db->select('IFNULL(t.template_unique_name,"") AS template_unique_name', FALSE);
		$this->db->select('IFNULL(t.default_values,"") AS default_values', FALSE);
		$this->db->from('campaigns AS c');
		$this->db->join('campaign_templates AS ct', 'ct.campaign_template_id = c.campaign_template_id', 'LEFT');
		$this->db->join('media AS m', 'm.media_id = ct.preview_media_id', 'LEFT');
		$this->db->join('templates AS t', 't.template_id = ct.template_id', 'LEFT');
		$this->db->where('c.campaign_id', $campaign_id);
		$query = $this->db->get();
		$reuslt = $query->row_array();
		$reuslt['template_values'] = json_decode($reuslt['template_values'], true);
		$reuslt['default_values'] = json_decode($reuslt['default_values'], true);
		if ($reuslt['media_name']) {
			$reuslt['preview_url'] = site_url('uploads/' . $reuslt['media_name']);
		} else {
			$reuslt['preview_url'] = '';
		}
		return $reuslt;
	}

	public function update_url_string($campaign_template_id, $unique_string) {
		$this->db->update('campaign_templates', ['unique_string' => $unique_string], array(	
			'campaign_template_id' => $campaign_template_id,
		));
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
	}

	public function update_campaign_qr_code($campaign_id, $qr_code_name, $qr_code_url) {
		$this->db->update('campaigns', ['qr_code_name' => $qr_code_name, 'qr_code_url' => $qr_code_url], array(	
			'campaign_id' => $campaign_id,
		));
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
	}

	public function get_media($campaign_id){
		$this->db->select('IFNULL(m.name,"") AS media_name', FALSE);
		$this->db->select('IFNULL(c.qr_code_name,"") AS qr_code_name', FALSE);
		$this->db->from('campaigns AS c');
		$this->db->join('campaign_templates AS ct', 'ct.campaign_template_id = c.campaign_template_id', 'LEFT');
		$this->db->join('media AS m', 'm.media_id = ct.preview_media_id', 'LEFT');
		$this->db->where('c.campaign_id', $campaign_id);
		$query = $this->db->get();
		$reuslt = $query->row_array();
		return $reuslt;
	}

	public function get_campaign_details_by_id($campaign_id) {
		$this->db->select('IFNULL(c.campaign_id,"") AS campaign_id', FALSE);
		$this->db->select('IFNULL(c.campaign_guid,"") AS campaign_guid', FALSE);
		$this->db->select('IFNULL(c.campaign_name,"") AS campaign_name', FALSE);
		$this->db->select('IFNULL(c.campaign_goal,"") AS campaign_goal', FALSE);
		$this->db->select('IFNULL(c.status,"") AS status', FALSE);
		$this->db->select('IFNULL(c.campaign_live_date,"") AS campaign_live_date', FALSE);
		$this->db->select('IFNULL(c.is_landing_page,"") AS is_landing_page', FALSE);
		$this->db->select('IFNULL(c.is_qr_code,"") AS is_qr_code', FALSE);
		$this->db->select('IFNULL(c.is_call_tracking_number,"") AS is_call_tracking_number', FALSE);
		$this->db->select('IFNULL(c.qr_code_name,"") AS qr_code_name', FALSE);
		$this->db->select('IFNULL(c.qr_code_url,"") AS qr_code_url', FALSE);
		$this->db->select('IFNULL(c.tracking_number_json,"") AS tracking_number_json', FALSE);
		$this->db->select('IFNULL(c.tracking_data,"") AS tracking_data', FALSE);
		$this->db->select('IFNULL(c.created_at,"") AS created_at', FALSE);
		$this->db->select('IFNULL(ct.campaign_template_guid,"") AS campaign_template_guid', FALSE);
		$this->db->select('IFNULL(ct.page_title,"") AS page_title', FALSE);
		$this->db->select('IFNULL(ct.email_receiver,"") AS email_receiver', FALSE);
		$this->db->select('IFNULL(ct.page_url,"") AS page_url', FALSE);
		$this->db->select('IFNULL(ct.page_script,"") AS page_script', FALSE);
		$this->db->select('IFNULL(ct.custom_page_script,"") AS custom_page_script', FALSE);
		$this->db->select('IFNULL(ct.unique_string,"") AS unique_string', FALSE);
		$this->db->select('IFNULL(t.template_guid,"") AS template_guid', FALSE);
		$this->db->select('IFNULL(m.name,"") AS media_name', FALSE);
		$this->db->from('campaigns AS c');
		$this->db->join('campaign_templates AS ct', 'ct.campaign_template_id = c.campaign_template_id', 'LEFT');
		$this->db->join('media AS m', 'm.media_id = ct.preview_media_id', 'LEFT');
		$this->db->join('templates AS t', 't.template_id = ct.template_id', 'LEFT');
		$this->db->where('c.campaign_id', $campaign_id);
		$query = $this->db->get();
		$result = $query->row_array();
		if ($result['media_name']) {
			$result['preview_url'] = site_url('uploads/' . $result['media_name']);
		} else {
			$result['preview_url'] = '';
		}

		if ($result['qr_code_name']) {
			$result['qr_code_image_url'] = site_url('uploads/qr_codes/' . $result['qr_code_name']);
			$result['downlod_qr_code'] = site_url('site/downlod_qr_code?id='.$result['campaign_guid']);;
		} else {
			$result['qr_code_image_url'] = '';
		}
		if ($result['tracking_number_json']) {
			$tracking_number_json = json_decode($result['tracking_number_json'], true); 
			$result['tracking_number_json'] = $tracking_number_json['result'];
		} else {
			$result['tracking_number_json'] = '';
		}
		if ($result['tracking_data']) {
			$tracking_data = json_decode($result['tracking_data'], true); 
			$result['tracking_data'] = $tracking_data;
			$result['total_leads'] = $this->getTotalLeads($result['tracking_data']);
		} else {
			$result['tracking_data'] = '';
			$result['total_leads'] = 0;
		}

		$result['qr_leads'] = $this->get_campaign_leads($result['campaign_id'], TRUE);
		$result['landing_page_leads'] = $this->get_campaign_leads($result['campaign_id'], FALSE);
		return $result;
	}

	public function get_campaign_leads($campaign_id ,$is_qr_code) {
		$this->db->select('COUNT(cu.contact_id) as count', FALSE);
		$this->db->from('contact_us AS cu');
		$this->db->where('cu.campaign_id', $campaign_id);
		if ($is_qr_code) {
			$this->db->where('cu.is_qr_code', "YES");
		} else {
			$this->db->where('cu.is_qr_code', "NO");
		}
		
		$query = $this->db->get();
		$results = $query->row()->count;
		return $results;

	}

	// public function update_call_tracking_number($campaign_id, $tracking_number_json, $tracking_number_id) {
	public function update_call_tracking_number($campaign_id, $tracking_number_json, $tracking_number_id, $number_type, $area_code) {
		$data = [
			"tracking_number_json" => $tracking_number_json,
			"tracking_number_id" => $tracking_number_id,
			"number_type" => $number_type,
			"area_code" => $area_code
		];

		$this->db->update('campaigns', $data, array(
			'campaign_id' => $campaign_id,
		));
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
	}

	public function update_call_tracking_company($organization_id, $callrail_company_id) {
		$this->db->update('organizations', ["callrail_company_id" => $callrail_company_id], ["organization_id" => $organization_id]);
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
	}

	public function create_call_tracking_company($name) {
		$url = "https://api.callrail.com/v3/a/" . CALL_RAIL_ACCOUNT_ID . "/companies.json";
		$fields = array(
			'name' => $name
		);  
		                                                               
		$data_string = json_encode($fields); 
		$header = array();
		$header[] = 'Authorization: Token token='.CALL_RAIL_TOKEN;
		$header[] = 'Content-Type: application/json';
		$header[] = 'Content-Length: ' . strlen($data_string);

		$ch = curl_init();
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		// pass header variable in curl method
		curl_setopt($ch,CURLOPT_POST, count($data_string));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $data_string);
		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$server_output = curl_exec($ch);
		if($errno = curl_errno($ch)) {
			$error_message = curl_strerror($errno);
		}
		curl_close ($ch);
		$result = json_decode($server_output, true);
		if (array_key_exists('error', $result)) {
			return [
				"status" => FALSE,
				"message" => $result['error'],
				"result" => $result
			];
		} else if(array_key_exists('errors', $result)) {
			return [
				"status" => FALSE,
				"message" => $result['errors'],
				"result" => $result
			];
		} else {
			return [
				"status" => TRUE,
				"message" => "Success",
				"result" => $result
			];
		}
		return $result;
	}

	public function create_call_tracking_number($company_id, $destination_number, $number_name, $tracking_array) {
		$url = "https://api.callrail.com/v3/a/" . CALL_RAIL_ACCOUNT_ID . "/trackers.json";
		$fields = array(
			'name' => $number_name,
			'company_id' => $company_id,
			'type' => 'source',
			'sms_enabled' => false,
			'type' => 'source',
			"call_flow" => array (
				"destination_number" => $destination_number
			),
			"tracking_number" => $tracking_array,
			"source" => array (
				"type" => "offline"
			)
		);  
		                                                               
		$data_string = json_encode($fields); 
		$header = array();
		$header[] = 'Authorization: Token token='.CALL_RAIL_TOKEN;
		$header[] = 'Content-Type: application/json';
		$header[] = 'Content-Length: ' . strlen($data_string);

		$ch = curl_init();
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		// pass header variable in curl method
		curl_setopt($ch,CURLOPT_POST, count($data_string));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $data_string);
		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$server_output = curl_exec($ch);
		if($errno = curl_errno($ch)) {
			$error_message = curl_strerror($errno);
		}
		curl_close ($ch);
		$result = json_decode($server_output, true);
		if (array_key_exists('error', $result)) {
			return [
				"status" => FALSE,
				"message" => $result['error'],
				"result" => $result
			];
		} else if(array_key_exists('errors', $result)) {
			return [
				"status" => FALSE,
				"message" => $result['errors'],
				"result" => $result
			];
		} else {
			return [
				"status" => TRUE,
				"message" => "Success",
				"result" => $result
			];
		}
		return $result;
	}

	/**
	 *
	 * @param type $user_id
	 * @param type $device_type
	 */
	public function send_cantactus_email($name, $email, $phone, $contact_message, $to_email, $cc_email, $subject) {
		$this->load->helper('email');
		$subject = $subject;
		$email_data = array("name" => $name, "email" => $email, "phone" => $phone, "contact_message" => $contact_message);
		$email_template = "emailer/web_retail_contact";
		$message = $this->load->view($email_template, $email_data, TRUE);
		$this->load->library('email');
		$this->email->from(SUPPORT_EMAIL, FROM_NAME);
		$this->email->to($to_email);
		$this->email->cc($cc_email);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
	}

	public function publish_campaign(){
		$current_date = gmdate("Y-m-d");
		$this->db->select('IFNULL(c.campaign_id,"") AS campaign_id', FALSE);
		$this->db->from('campaigns AS c');
		$this->db->where('c.status', 'INACTIVE');
		$this->db->where('c.campaign_live_date <=', $current_date);
		$query = $this->db->get();
		$results = $query->result_array();
		foreach ($results as $key => $value) {
			$this->db->update('campaigns', ['status' => 'ACTIVE'], ['campaign_id' => $value['campaign_id']]);
		}
		return;
	}

	public function update_campaign_reports(){
		$this->db->select('IFNULL(c.campaign_id,"") AS campaign_id', FALSE);
		$this->db->select('IFNULL(c.campaign_guid,"") AS campaign_guid', FALSE);
		$this->db->select('IFNULL(c.tracking_number_id,"") AS tracking_number_id', FALSE);
		$this->db->select('IFNULL(c.is_landing_page,"") AS is_landing_page', FALSE);
		$this->db->select('IFNULL(c.is_qr_code,"") AS is_qr_code', FALSE);
		$this->db->select('IFNULL(c.is_call_tracking_number,"") AS is_call_tracking_number', FALSE);
		$this->db->from('campaigns AS c');
		$this->db->where('c.status', 'ACTIVE');
		$query = $this->db->get();
		$results = $query->result_array();
		foreach ($results as $key => $value) {
			$data = $this->get_campaign_report_details( $value['campaign_guid'],  $value['tracking_number_id'],  $value['is_landing_page'],  $value['is_qr_code'],  $value['is_call_tracking_number']);
			if($data['status']){
				$this->db->update('campaigns', ['tracking_data' => json_encode($data['data'])], ['campaign_id' => $value['campaign_id']]);
			}
		}
		return;
	}

	public function update_template_default_values($template_id, $default_values){
		$this->db->update('templates', ['default_values' => $default_values], ['template_id' => $template_id]);
	}

	public function disable_call_tracking_number($old_tracking_number_id) {
		$url = "https://api.callrail.com/v3/a/" . CALL_RAIL_ACCOUNT_ID . "/trackers/".$old_tracking_number_id.".json";
		$header = array();
		$header[] = 'Authorization: Token token='.CALL_RAIL_TOKEN;
		$header[] = 'Content-Type: application/json';

		$ch = curl_init();
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		// pass header variable in curl method
		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$server_output = curl_exec($ch);
		if($errno = curl_errno($ch)) {
			$error_message = curl_strerror($errno);
		}
		curl_close ($ch);
		$result = json_decode($server_output, true);
		if ($result && array_key_exists('error', $result)) {
			return [
				"status" => FALSE,
				"message" => $result['error'],
				"result" => $result
			];
		} else {
			return [
				"status" => TRUE,
				"message" => "Success",
				"result" => $result
			];
		}
		return $result;
	}

	public function get_tracker_details($tracking_number_id) {

		$url = "https://api.callrail.com/v3/a/" . CALL_RAIL_ACCOUNT_ID . "/trackers/".$tracking_number_id.".json";
		$header = array();
		$header[] = 'Authorization: Token token='.CALL_RAIL_TOKEN;
		$header[] = 'Content-Type: application/json';

		$ch = curl_init();
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		// pass header variable in curl method
		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$server_output = curl_exec($ch);
		if($errno = curl_errno($ch)) {
			$error_message = curl_strerror($errno);
		}
		curl_close ($ch);
		$result = json_decode($server_output, true);
		if ($result && array_key_exists('error', $result)) {
			return [
				"status" => FALSE,
				"message" => $result['error'],
				"result" => $result
			];
		} else {
			return [
				"status" => TRUE,
				"message" => "Success",
				"result" => $result
			];
		}
		return $result;
	}

	public function update_tracking_number($tracking_number_id, $destination_number, $number_name, $tracking_array) {
		$url = "https://api.callrail.com/v3/a/" . CALL_RAIL_ACCOUNT_ID . "/trackers/". $tracking_number_id . ".json";
		$fields = array(
			'name' => $number_name,
			"call_flow" => array (
				"destination_number" => $destination_number
			),
			"tracking_number" => $tracking_array
		);  
		                                                               
		$data_string = json_encode($fields); 
		$header = array();
		$header[] = 'Authorization: Token token='.CALL_RAIL_TOKEN;
		$header[] = 'Content-Type: application/json';
		$header[] = 'Content-Length: ' . strlen($data_string);

		$ch = curl_init();
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		// pass header variable in curl method
		curl_setopt($ch,CURLOPT_POST, count($data_string));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $data_string);
		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$server_output = curl_exec($ch);
		if($errno = curl_errno($ch)) {
			$error_message = curl_strerror($errno);
		}
		curl_close ($ch);
		$result = json_decode($server_output, true);
		if (array_key_exists('error', $result)) {
			return [
				"status" => FALSE,
				"message" => $result['error'],
				"result" => $result
			];
		} else if(array_key_exists('errors', $result)) {
			return [
				"status" => FALSE,
				"message" => $result['errors'],
				"result" => $result
			];
		} else {
			return [
				"status" => TRUE,
				"message" => "Success",
				"result" => $result
			];
		}
		return $result;
	}

	public function get_campaign_report_details($campaign_guid, $tracking_number_id, $is_landing_page, $is_qr_code, $is_call_tracking_number) {
        $request_headers = array();
        // / $request_headers[] = 'Authorization: Bearer ' . $secretKey; /
        $fields = array(
			'campaign_guid' => $campaign_guid,
			'tracking_number_id' => $tracking_number_id,
			'is_landing_page' => $is_landing_page,
			'is_qr_code' => $is_qr_code,
			'is_call_tracking_number' => $is_call_tracking_number
		);
       
        $fields_string = http_build_query($fields);
        // $url = "https://reporting.tikisites.com/api/tracking_data_managment/campaign_tracking_data";
		$url = "https://reporting.tikisites.com/webhost-api/index.php/api/tracking_data_managment/campaign_tracking_data";
		
		switch (ENVIRONMENT) {
			case 'production':
				$url = "https://reporting.tikisites.com/webhost-api/index.php/api/tracking_data_managment/campaign_tracking_data";
				break;
			case 'staging':
				$url = "https://staging.reporting.tikisites.com/webhost-api/index.php/api/tracking_data_managment/campaign_tracking_data";
				break;
			case 'development':
				$url = "https://dev.reporting.tikisites.com/webhost-api/index.php/api/tracking_data_managment/campaign_tracking_data";
				break;
			case 'local':
				$url = "http://localhost/reporting-tikisites/webhost-api/index.php/api/tracking_data_managment/campaign_tracking_data";
				break;
			default:
		}

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    
		$data = curl_exec($ch);

		
        if (curl_errno($ch))
        {
            return curl_error($ch);
        }
        else
        {
            curl_close($ch);
            return json_decode($data, true);
        }
	}
	
	public function add_cantactus($campaign_id, $crm_contact_id, $name, $email, $phone, $contact_message, $is_qr_code) {
		$this->db->insert('contact_us', [
			"contact_guid" => get_guid(),
			"campaign_id" => $campaign_id,
			"crm_contact_id" => $crm_contact_id,
			"name" => $name,
			"email" => $email,
			"phone" => $phone,
			"contact_message" => $contact_message,
			"is_qr_code" => $is_qr_code,
			"created_at" => DATETIME,
			"updated_at" => DATETIME,
		]);
		return $this->db->insert_id();
	}

	public function campaign_active_list($user_id, $keyword = '', $limit = 0, $offset = 0, $column_name, $order_by, $user_type) {
		if ($limit > 0 && $offset >= 0) {
			$this->db->limit($limit, $offset);
			$this->db->select('IFNULL(c.campaign_id,"") AS campaign_id', FALSE);
			$this->db->select('IFNULL(c.campaign_guid,"") AS campaign_guid', FALSE);
			$this->db->select('IFNULL(c.campaign_name,"") AS campaign_name', FALSE);
			$this->db->select('IFNULL(c.campaign_goal,"") AS campaign_goal', FALSE);
			$this->db->select('IFNULL(c.status,"") AS status', FALSE);
			$this->db->select('IFNULL(c.campaign_live_date,"") AS campaign_live_date', FALSE); 
			$this->db->select('IFNULL(c.is_landing_page,"") AS is_landing_page', FALSE);
			$this->db->select('IFNULL(c.is_call_tracking_number,"") AS is_call_tracking_number', FALSE);
			$this->db->select('IFNULL(c.is_qr_code,"") AS is_qr_code', FALSE);
			$this->db->select('IFNULL(c.tracking_data,"") AS tracking_data', FALSE);
			$this->db->select('IFNULL(ct.campaign_template_guid,"") AS campaign_template_guid', FALSE);
			$this->db->select('IFNULL(ct.template_values,"") AS template_values', FALSE);
			$this->db->select('IFNULL(ct.page_title,"") AS page_title', FALSE);
			$this->db->select('IFNULL(ct.email_receiver,"") AS email_receiver', FALSE);
			$this->db->select('IFNULL(ct.page_url,"") AS page_url', FALSE);
			$this->db->select('IFNULL(ct.page_script,"") AS page_script', FALSE);
			$this->db->select('IFNULL(c.created_at,"") AS created_at', FALSE);
			$this->db->select('IFNULL(c.updated_at,"") AS updated_at', FALSE);
			$this->db->select('IFNULL(m.name,"") AS media_name', FALSE);
		} else {
			$this->db->select('COUNT(c.campaign_id) as count', FALSE);
		}
		$this->db->from('campaigns AS c');
		$this->db->join('campaign_templates AS ct', 'ct.campaign_template_id = c.campaign_template_id', 'LEFT');
		$this->db->join('media AS m', 'm.media_id = ct.preview_media_id', 'LEFT');
		$this->db->order_by('c.created_at', 'desc');

		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('c.campaign_name', $keyword, 'both');
			$this->db->group_end();
		}

		if (($column_name !== '') && ($order_by !== '')) {
			$this->db->order_by('c.' . $column_name, $order_by);
		}
		if ($user_type != 'ADMIN') {
			$this->db->where('c.added_by', $user_id);
		}
		$this->db->where('c.status', 'ACTIVE');
		$query = $this->db->get();
		$results = $query->result_array();
		if (($limit > 0) && ($offset >= 0)) {
			if ($query->num_rows() > 0) {
				$list = [];
				foreach ($results as $key => $value) {
					$list[$key]['campaign_guid'] = $value['campaign_guid'];
					$list[$key]['campaign_name'] = $value['campaign_name'];
					$list[$key]['campaign_goal'] = $value['campaign_goal'];
					$list[$key]['status'] = $value['status'];
					$list[$key]['campaign_live_date'] = $value['campaign_live_date'];
					$list[$key]['is_landing_page'] = $value['is_landing_page'];
					$list[$key]['is_call_tracking_number'] = $value['is_call_tracking_number'];
					$list[$key]['is_qr_code'] = $value['is_qr_code'];
					$list[$key]['campaign_template_guid'] = $value['campaign_template_guid'];
					$list[$key]['template_values'] = $value['template_values'];
					$list[$key]['page_title'] = $value['page_title'];
					$list[$key]['email_receiver'] = $value['email_receiver'];
					$list[$key]['page_url'] = $value['page_url'];
					$list[$key]['page_script'] = $value['page_script'];
					if ($value['media_name']) {
						$list[$key]['preview_url'] = site_url('uploads/' . $value['media_name']);
					} else {
						$list[$key]['preview_url'] = '';
					}
					if ($value['tracking_data']) {
						$tracking_data = json_decode($value['tracking_data'], true); 
						$list[$key]['tracking_data'] = $tracking_data;
						$list[$key]['total_leads'] = $this->getTotalLeads($list[$key]['tracking_data']);
					} else {
						$list[$key]['tracking_data'] = '';
						$list[$key]['total_leads'] = 0;
					}
					$list[$key]['qr_leads'] = $this->get_campaign_leads($value['campaign_id'], TRUE);
					$list[$key]['landing_page_leads'] = $this->get_campaign_leads($value['campaign_id'], FALSE);
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

	public function get_dashboard($added_by = NULL){
		$this->db->select('IFNULL(c.campaign_id,"") AS campaign_id', FALSE);
		$this->db->select('IFNULL(c.campaign_guid,"") AS campaign_guid', FALSE);
		$this->db->select('IFNULL(c.tracking_number_id,"") AS tracking_number_id', FALSE);
		$this->db->select('IFNULL(c.is_landing_page,"") AS is_landing_page', FALSE);
		$this->db->select('IFNULL(c.is_qr_code,"") AS is_qr_code', FALSE);
		$this->db->select('IFNULL(c.is_call_tracking_number,"") AS is_call_tracking_number', FALSE);
		$this->db->from('campaigns AS c');
		$this->db->where('c.status', 'ACTIVE');
		if ($added_by) {
			$this->db->where('c.added_by', $added_by);
		}
		$query = $this->db->get();
		$result = [];
		$result['campaign_count'] = $query->num_rows();
		$result['qr_leads_count'] = $this->get_counts('QR', $added_by);
		$result['landing_page_count'] = $this->get_counts('LANDING', $added_by);
		$result['call_tracking_number_count'] = $this->get_counts('CALL', $added_by);
		return $result;
	}

	public function get_counts($type, $added_by = NULL) {

		$this->db->select('IFNULL(c.campaign_id,"") AS campaign_id', FALSE);
		$this->db->from('campaigns AS c');
		$this->db->where('c.status', 'ACTIVE');
		if($type == 'QR') {
			$this->db->where('c.is_qr_code', 'YES');
		}
		if($type == 'LANDING') {
			$this->db->where('c.is_landing_page', 'YES');
		}
		if($type == 'CALL') {
			$this->db->where('c.is_call_tracking_number', 'YES');
		}
		if ($added_by) {
			$this->db->where('c.added_by', $added_by);
		}
		$query = $this->db->get();
		$count = $query->num_rows();
		return $count;
	}

	public function get_landing_page_ranking_list($user_id, $keyword, $limit, $offset, $column_name, $order_by, $user_type) {
        $fields = array(
			'user_id' => $user_id,
			'user_type' => $user_type
		);
       
        $fields_string = http_build_query($fields);
		$url = "https://reporting.tikisites.com/webhost-api/index.php/api/tracking_data_managment/campaign_landing_page_ranking";
		
		switch (ENVIRONMENT) {
			case 'production':
			$url = "https://reporting.tikisites.com/webhost-api/index.php/api/tracking_data_managment/campaign_landing_page_ranking";
				break;
			case 'staging':
				$url = "https://staging.reporting.tikisites.com/webhost-api/index.php/api/tracking_data_managment/campaign_landing_page_ranking";
				break;
			case 'development':
				$url = "https://dev.reporting.tikisites.com/webhost-api/index.php/api/tracking_data_managment/campaign_landing_page_ranking";
				break;
			case 'local':
				$url = "http://localhost/reporting-tikisites/webhost-api/index.php/api/tracking_data_managment/campaign_landing_page_ranking";
				break;
			default:
		}
		
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    
		$data = curl_exec($ch);

		$result = json_decode($data, true);
        if (curl_errno($ch))
        {
            return curl_error($ch);
        }
        else
        {
			curl_close($ch);
			$list = [];
			if(!empty($result['data'])) {
				foreach ($result['data'] as $key => $value) {
					$list[$key]['campaign_reference_guid'] = $value['campaign_reference_guid'];
					$list[$key]['page_title'] = $this->get_page_title($value['campaign_reference_guid']);
					$list[$key]['views'] = $value['views'];
					$list[$key]['business_name'] = get_detail_by_id($user_id, 'user', 'business_name');;
					$list[$key]['rank'] = $value['rank'];
				}
			}
			return $list;
        }
	}

	public function get_page_title($campaign_reference_guid) {
		$this->db->select('IFNULL(ct.page_title,"") AS page_title', FALSE);
		$this->db->from('campaigns AS c');
		$this->db->join('campaign_templates AS ct', 'ct.campaign_template_id = c.campaign_template_id', 'LEFT');
		$this->db->where('c.campaign_guid', $campaign_reference_guid);
		$query = $this->db->get();
		$reuslt = $query->row_array();
		$page_title = $reuslt['page_title'];
		return $page_title;
	}

	
	public function update_user_campaigns($user_id, $pricing_plan_id) {
		$this->db->select('IFNULL(c.campaign_id,"") AS campaign_id', FALSE);
		$this->db->from('campaigns AS c');
		$this->db->where('c.added_by', $user_id);
		$this->db->order_by('c.created_at', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();

		if ($query->num_rows() > 0) {
			$planName = $this->getPlanName($user_id);
			$campaign_limit = $this->app->getPlanCampaignLimit($user_id);
			$limit = 'NO';
			// if ($planName === 'ESSENTIAL') {
			// 	$limit = 1;
			// } else if ($planName === 'PRO') {
			// 	$limit = 5;
			// } else {
			// 	$limit = 'NO';
			// }
			if ($campaign_limit == 'NO' || $campaign_limit == NULL ) {
				$limit = 'NO';
			} else {
				$limit = $campaign_limit;
			} 
			foreach ($results as $key => $value) {
				$this->db->update('campaigns', ['status' => 'BLOCKED', 'updated_at' => DATETIME], ['campaign_id' => $value['campaign_id']]);
			}
			$this->campaign_inactive($user_id, $limit);
		}
	}

	public function campaign_inactive($user_id, $limit) {
		if ($limit != 'NO') {
			$this->db->limit($limit);	
		}
		$this->db->select('IFNULL(c.campaign_id,"") AS campaign_id', FALSE);
		$this->db->from('campaigns AS c');
		$this->db->where('c.added_by', $user_id);
		$this->db->order_by('c.created_at', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();

		if ($query->num_rows() > 0) {
			foreach ($results as $key => $value) {
				$this->db->update('campaigns', ['status' => 'INACTIVE', 'updated_at' => DATETIME], ['campaign_id' => $value['campaign_id']]);
			}
		}
	}

	public function getPlanName($added_by) {
		$this->db->select('IFNULL(pp.name, "") AS plan_name', FALSE);
		$this->db->from('users AS u');
		$this->db->join('organization_members AS om', 'om.user_id = u.user_id', 'LEFT');
		$this->db->join('pricing_plans AS pp', 'om.pricing_plan_id = pp.pricing_plan_id', 'LEFT');
		$this->db->where('u.user_id', $added_by);
		$query = $this->db->get();
		$user = $query->row_array();
		$user['plan_name'] = strtoupper($user['plan_name']);
		return $user['plan_name'];
	}

	public function update_campaign_status($campaign_id, $status) {
		$data = [
			"status" => $status
		];
		$this->db->update('campaigns', $data, array(	
			'campaign_id' => $campaign_id,
		));
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
	}
}
