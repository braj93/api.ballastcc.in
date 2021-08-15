<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Census extends CI_Controller {

	public function index(){
		$this->db->where('imported_at', gmdate("Y-m-d 00:00:00")); 
		$query = $this->db->get('censuses');
		$data['census'] = $query->row_array();
		$this->load->view('census', $data);
	}

	public function cron(){
		$this->db->where('imported_at', gmdate("Y-m-d 00:00:00")); 
		$query = $this->db->get('censuses');
		$row = $query->row_array();
		if(!isset($row['id'])){
			$for_place = file_get_contents('https://api.census.gov/data/2020/dec/responserate?get=RESP_DATE,CRRALL&for=place:08220&in=state:04&key=4f68f3ab93412eff8fba41aefb40b10c0b57ea73');
			$for_county = file_get_contents('https://api.census.gov/data/2020/dec/responserate?get=RESP_DATE,CRRALL&for=county:015&in=state:04&key=4f68f3ab93412eff8fba41aefb40b10c0b57ea73');
			$this->db->insert('censuses', [
				'for_place' => $for_place,
				'for_county' => $for_county,
				'imported_at' => gmdate("Y-m-d 00:00:00"),
			]);
			echo "new data inserted for ".gmdate("Y-m-d 00:00:00");
		}else{
			echo "data already exists for ".gmdate("Y-m-d 00:00:00");
		}
	}
}
