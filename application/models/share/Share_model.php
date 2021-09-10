<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Share_model extends CI_Model {
	public function __construct() {
		// Call the CI_Model constructor
		parent::__construct();
	}
	

	public function get_campaign_list_for_reporting_tikisites() {
		$this->db->select('IFNULL(c.campaign_id,"") AS campaign_id', FALSE);
		$this->db->select('IFNULL(c.campaign_guid,"") AS campaign_guid', FALSE);
		$this->db->select('IFNULL(c.campaign_name,"") AS campaign_name', FALSE);
		$this->db->select('IFNULL(c.added_by,"") AS added_by', FALSE);
		$this->db->select('IFNULL(c.status,"") AS status', FALSE);
		$this->db->select('IFNULL(c.campaign_live_date,"") AS campaign_live_date', FALSE);
		$this->db->select('IFNULL(c.created_at,"") AS created_at', FALSE);
		
		$this->db->select('IFNULL(c.is_qr_code,"") AS is_qr_code', FALSE);
		$this->db->select('IFNULL(c.is_call_tracking_number,"") AS is_call_tracking_number', FALSE);
		$this->db->select('IFNULL(c.qr_code_name,"") AS qr_code_name', FALSE);
		$this->db->select('IFNULL(c.qr_code_url,"") AS qr_code_url', FALSE);
		$this->db->select('IFNULL(c.tracking_number_json,"") AS tracking_number_json', FALSE);
		$this->db->select('IFNULL(c.tracking_number_id,"") AS tracking_number_id', FALSE);
		$this->db->select('IFNULL(c.number_type ,"") AS number_type', FALSE);		
		$this->db->select('IFNULL(c.tracking_data ,"") AS tracking_data', FALSE);
		$this->db->select('IFNULL(c.area_code ,"") AS area_code ', FALSE);

		$this->db->from('campaigns AS c');		
		$this->db->order_by('c.created_at', 'desc');
		
		$query = $this->db->get();
		$results = $query->result_array();
		if ($query->num_rows() > 0) {
			$list = [];
			foreach ($results as $key => $value) {
				$list[$key]['campaign_id'] = $value['campaign_id'];
				$list[$key]['campaign_guid'] = $value['campaign_guid'];
				$list[$key]['campaign_name'] = $value['campaign_name'];
				$list[$key]['added_by'] = $value['added_by'];
				$list[$key]['status'] = $value['status'];
				$list[$key]['campaign_live_date'] = $value['campaign_live_date'];
				$list[$key]['created_at'] = $value['created_at'];
				$list[$key]['is_qr_code'] = $value['is_qr_code'];
				$list[$key]['is_call_tracking_number'] = $value['is_call_tracking_number'];
				$list[$key]['qr_code_name'] = $value['qr_code_name'];
				$list[$key]['qr_code_url'] = $value['qr_code_url'];
				$list[$key]['tracking_number_json'] = $value['tracking_number_json'];
				$list[$key]['tracking_number_id'] = $value['tracking_number_id'];
				$list[$key]['number_type'] = $value['number_type'];
				$list[$key]['tracking_data'] = $value['tracking_data'];
				$list[$key]['area_code'] = $value['area_code'];
			}
			return $list;
		} else {
			return [];
		}
	}


	public function get_users_list_for_reporting_tikisites() {
		$this->db->select('IFNULL(u.user_id,"") AS tikisites_reference_id', FALSE);
		$this->db->select('IFNULL(u.user_guid,"") AS tikisites_reference_guid', FALSE);
		$this->db->select('IFNULL(u.user_type,"") AS user_type', FALSE);
		$this->db->select('IFNULL(u.user_sub_type,"") AS user_sub_type', FALSE);
		$this->db->select('IFNULL(u.first_name,"") AS first_name', FALSE);
		$this->db->select('IFNULL(u.business_name,"") AS business_name', FALSE);
		$this->db->select('IFNULL(u.email,"") AS email', FALSE);
		$this->db->select('IFNULL(u.status,"") AS status', FALSE);
		
		$this->db->where('u.status', 'ACTIVE');
		$this->db->where('u.user_type', 'USER');
		
		$this->db->group_start();
		$this->db->where('u.user_sub_type', 'USER');
		$this->db->or_where('u.user_sub_type', 'AGENCY_USER');
   		$this->db->group_end();

		$this->db->from('users AS u');
		$query = $this->db->get();
		$str = $this->db->last_query();

		$results = $query->result_array();
		if ($query->num_rows() > 0) {
			return $results;
		} else {
			return [];
		}
	}

	public function get_organization_list_for_reporting_tikisites() {

		$this->db->select('IFNULL(u.user_id,"") AS tikisites_reference_user_id', FALSE);
		$this->db->select('IFNULL(u.user_guid,"") AS tikisites_reference_user_guid', FALSE);
		$this->db->select('IFNULL(u.user_type,"") AS user_type', FALSE);
		$this->db->select('IFNULL(u.user_sub_type,"") AS user_sub_type', FALSE);
		$this->db->select('IFNULL(u.first_name,"") AS first_name', FALSE);
		$this->db->select('IFNULL(u.business_name,"") AS business_name', FALSE);
		$this->db->select('IFNULL(u.email,"") AS email', FALSE);
		$this->db->select('IFNULL(u.status,"") AS status', FALSE);
		$this->db->select('IFNULL(om.organization_id,"") AS tikisites_organization_reference_id', FALSE);		
		$this->db->where('u.status', 'ACTIVE');
		$this->db->where('u.user_type', 'USER');
		
		$this->db->group_start();
		$this->db->where('u.user_sub_type', 'USER');
		$this->db->or_where('u.user_sub_type', 'AGENCY_USER');
   		$this->db->group_end();

		$this->db->from('users AS u');
		$this->db->join('organization_members AS om', 'om.user_id = u.user_id');
		
		$query = $this->db->get();
		//echo $str = $this->db->last_query();

		$results = $query->result_array();
		if ($query->num_rows() > 0) {
			return $results;
		} else {
			return [];
		}
	}



	// public function update_campaign_script($campaign_id, $page_script) {
	// 	$data = [
	// 		"page_script" => $page_script,
	// 	];

	// 	$this->db->update('campaigns', $data, array(
	// 		'campaign_id' => $campaign_id,
	// 	));
	// 	$affected_rows_count = $this->db->affected_rows();
	// 	return $affected_rows_count;
	// }

	public function update_campaign_script($campaign_template_id, $page_script) {
		$data = [
			"page_script" => $page_script
		];

		$this->db->update('campaign_templates', $data, array(	
			'campaign_template_id' => $campaign_template_id,
		));
		$affected_rows_count = $this->db->affected_rows();
		return $affected_rows_count;
	}

	public function get_pricing_plan_list_for_marketingtiki($type) {
		$this->db->select('IFNULL(pp.pricing_plan_guid,"") AS pricing_plan_id', FALSE);
		$this->db->select('IFNULL(pp.name,"") AS name', FALSE);
		$this->db->select('IFNULL(pp.type,"") AS type', FALSE);
		$this->db->select('IFNULL(pp.note,"") AS note', FALSE);
		$this->db->select('IFNULL(pp.created_at,"") AS created_at', FALSE);

		$this->db->from('pricing_plans AS pp');	
		$this->db->where('pp.type', $type);	
		$this->db->where('pp.status', 'ACTIVE');	
		$this->db->order_by('pp.base_price', 'desc');
		
		$query = $this->db->get();
		$results = $query->result_array();
		if ($query->num_rows() > 0) {
			$list = [];
			foreach ($results as $key => $value) {
				$list[$key]['pricing_plan_id'] = $value['pricing_plan_id'];
				$list[$key]['name'] = $value['name'];
				$list[$key]['type'] = $value['type'];
				$list[$key]['note'] = $value['note'];
				$list[$key]['created_at'] = $value['created_at'];
			}
			return $list;
		} else {
			return [];
		}
	}
}
