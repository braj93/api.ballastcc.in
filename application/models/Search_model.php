<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This is an example of a few basic search interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Search_model extends CI_Model {

	public function __construct() {
		// Call the CI_Model constructor
		parent::__construct();
	}

	/**
	 * @param [string] $type
	 * @param [string] $keyword
	 * @param [string] $entity_type
	 * @param [int] $entity_id
	 * @return type
	 */
	public function autosuggest($use_id, $type, $keyword, $entity_type = "mandate", $entity_id = 0) {

		$data = array();
		
		if ($type == "master_tag") {

			$this->db->select('tm.tag_guid as guid, tm.name, tm.created_at, tm.updated_at');
			$this->db->from('tag_master' . " as tm");
			$this->db->where('tm.status', 'ACTIVE');
			$this->db->where('tm.added_by', $use_id);
			$this->db->where("tm.name LIKE '%" . $keyword . "%'", NULL, FALSE);
			$query = $this->db->get();
			if ($query->num_rows()) {
				$data = $query->result_array();
			}
		}

		if ($type == "composition_tag") {

			$this->db->select('ct.composition_tag_guid as guid, ct.name, ct.created_at, ct.updated_at');
			$this->db->from('composition_tags' . " as ct");
			$this->db->where('ct.status', 'ACTIVE');
			$this->db->where("ct.name LIKE '%" . $keyword . "%'", NULL, FALSE);
			$query = $this->db->get();
			if ($query->num_rows()) {
				$data = $query->result_array();
			}
		}
		if ($type == "skill") {

			$this->db->select('s.skill_guid as guid, s.name, s.created_at, s.updated_at');
			$this->db->from('skills' . " as s");
			$this->db->where('s.status', 'ACTIVE');
			$this->db->where("s.name LIKE '%" . $keyword . "%'", NULL, FALSE);
			if ($entity_id && $entity_type == "mandate") {
				$this->db->where("s.skill_id NOT IN (SELECT skill_id FROM mandate_skills WHERE mandate_id='" . $entity_id . "' AND status='ACTIVE')", NULL, FALSE);
			}
			$query = $this->db->get();
			// echo $this->db->last_query();die;
			if ($query->num_rows()) {
				$data = $query->result_array();
			}
		}

		if ($type == "education") {

			$this->db->select('e.education_guid as guid, e.name, e.created_at, e.updated_at');
			$this->db->from('educations' . " as e");
			$this->db->where('e.status', 'ACTIVE');
			$this->db->where("e.name LIKE '%" . $keyword . "%'", NULL, FALSE);
			if ($entity_id && $entity_type == "mandate") {
				$this->db->where("e.education_id NOT IN (SELECT education_id FROM mandate_educations WHERE mandate_id='" . $entity_id . "' AND status='ACTIVE')", NULL, FALSE);
			}
			$query = $this->db->get();
			// echo $this->db->last_query();die;

			if ($query->num_rows()) {
				$data = $query->result_array();
			}
		}

		if ($type == "company") {
			$this->db->select('c.company_guid as guid, c.company_id, c.name,  c.created_at, c.updated_at');

			$this->db->select('IFNULL(u.name,"") as created_by', FALSE);
			$this->db->select('IFNULL(uu.name,"") as updated_by', FALSE);
			$this->db->select('IFNULL(uu.email,"") as updated_by_email', FALSE);
			$this->db->select('IFNULL(c.description,"") as description', FALSE);
			$this->db->select('IFNULL(c.industry,"") as industry', FALSE);
			$this->db->select('IFNULL(c.website,"") as website', FALSE);
			// $this->db->select('IFNULL(c.industry_icon,"") as industry_icon', FALSE);
			// $this->db->select('IFNULL(c.company_media_id,"") as company_media_id', FALSE);
			// $this->db->select('IFNULL(m.name,"") as company_media_name', FALSE);

			$this->db->from('companies' . " as c");
			$this->db->join('users AS u', 'u.user_id = c.created_by', 'left');
			$this->db->join('users AS uu', 'uu.user_id = c.updated_by', 'left');
			//
			// $this->db->join('media AS m', 'm.media_id = c.company_media_id', 'left');
			$this->db->where('c.status', 'ACTIVE');
			$this->db->where("c.name LIKE '%" . $keyword . "%'", NULL, FALSE);
			if ($entity_id && $entity_type == "user") {
				$this->db->where("c.company_id IN (SELECT company_id FROM user_companies WHERE user_id='" . $entity_id . "' AND status='ACTIVE')", NULL, FALSE);
			}
			// $query = $this->db->get();
			// if ($query->num_rows()) {
			// 	$data = $query->result_array();
			// }
			//
			$query = $this->db->get();
			if ($query->num_rows()) {
				$data = $query->result_array();
				foreach ($data as $key => $value) {
					$data[$key]['industry_icon'] = url_title($value['industry'], "dash", TRUE);
				}
			}
		}
		return $data;
	}

}