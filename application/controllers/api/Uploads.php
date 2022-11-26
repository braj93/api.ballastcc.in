<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a image/file upload methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Uploads extends REST_Controller {

	var $_data = array();
	var $upload_path = "./uploads";
	function __construct() {
		// Construct the parent class
		parent::__construct();
		$this->upload_path = "./uploads";
		$this->_data = $this->post();
		if (empty($this->_data)) {
			$this->_data = $_FILES;
		}

		$this->_data['key'] = "value";
		$this->_response = [
			"status" => TRUE,
			"message" => "Success",
			"errors" => (object) [],
			"data" => (object) [],
		];
		$this->load->library('form_validation');
		$this->form_validation->set_data($this->_data);
	}

	public function index_post() {
		$this->_response["service_name"] = "uploads";
		$session_key = $this->rest->key;
		$user_id = $this->rest->user_id;

		$type = safe_array_key($this->_data, "type", "");
		// print_r($this->_data);
		// die();
		$this->form_validation->set_rules('type', 'type', 'trim|required|in_list[excel,pdf,image,file,audio,audio_mp3,audio_wav,other_audio_mp3,other_audio_wav]');
		//$this->form_validation->set_rules('upfile', 'select file ', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$this->load->model("users_model");
			$this->load->model("uploads_model");
			$type = safe_array_key($this->_data, "type", "");
			$is_profile_picture = safe_array_key($this->_data, 'is_profile_picture', 0);

			if ($type == "image") {
				$upload_data = $this->uploads_model->upload_image($this->_data, $user_id);
			} elseif ($type == "file") {
				$upload_data = $this->uploads_model->upload_file($this->_data, $user_id, $type);
			} elseif ($type == "excel") {
				$upload_data = $this->uploads_model->upload_file($this->_data, $user_id, $type);
			}

			if ($upload_data['upload_status']) {
				if ($type == "excel") {
					$user_role = $this->users_model->get_user_role($user_id);
					$added_by_id = safe_array_key($user_role, "added_by", "");
					$user_role = safe_array_key($user_role, "role", "");
					if ($added_by_id && $user_role == 'TEAM') {
						$user_id = $added_by_id;
					}
					$state = $this->import_excel_post($upload_data, $user_id);
					if (!$state) {
						$this->_response["status"] = TRUE;
						$this->_response["message"] = "Please import correct file.";
						$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
					}
					$this->uploads_model->remove_file_media($upload_data['media_id'], $upload_data['name'], 'uploads/files/');
				}
				if ($is_profile_picture == 1) {
					$this->db->update('users', [
						'media_id' => $upload_data['media_id'],
					], [
						'user_id' => $user_id,
					]);
				}

				unset($upload_data['upload_status']);
				unset($upload_data['user_id']);
				unset($upload_data['media_id']);
				$this->_response["data"] = $upload_data;

				$this->set_response($this->_response);
			} else {
				$this->_response["status"] = TRUE;
				$this->_response["message"] = "Invalid file format or uploaded file exceeds size limit.";
				$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
			}
		}
	}

	public function bulk_post() {
		$this->_response["service_name"] = "uploads";
		$this->form_validation->set_rules('type', 'type', 'trim|required|in_list[excel,pdf,image,file,audio,audio_mp3,audio_wav,other_audio_mp3,other_audio_wav]');
		$this->form_validation->set_rules('user_id', 'user id', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$this->load->model("users_model");
			$this->load->model("uploads_model");
			$type = safe_array_key($this->_data, "type", "");
			$user_guid = safe_array_key($this->_data, 'user_id', "");
			$user_data = $this->app->get_row('users', 'user_id', ['user_guid' => $user_guid]);
			$user_id = safe_array_key($user_data, 'user_id', $this->rest->user_id);
			$upload_data = $this->uploads_model->upload_file($this->_data, $user_id, $type);

			$user_role = $this->users_model->get_user_role($user_id);
			$added_by_id = safe_array_key($user_role, "added_by", "");
			$user_role = safe_array_key($user_role, "role", "");
			if ($added_by_id && $user_role == 'TEAM') {
				$user_id = $added_by_id;
			}
			if ($upload_data['upload_status']) {
				if ($type == "excel") {
					$state = $this->import_excel_post($upload_data, $user_id);
					if (!$state) {
						$this->_response["status"] = TRUE;
						$this->_response["message"] = "Please import correct file.";
						$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
					}
					$this->uploads_model->remove_file_media($upload_data['media_id'], $upload_data['name'], 'uploads/files/');
				}
				$this->_response["message"] = "Bulk Upload Done";
				$this->set_response($this->_response);
			} else {
				$this->_response["status"] = TRUE;
				$this->_response["message"] = "Invalid file format or uploaded file exceeds size limit.";
				$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
			}
		}
	}

	public function remove_profile_pic_post() {
		$this->_response["service_name"] = "uploads/remove_profile_pic";
		$user_id = $this->rest->user_id;
		$session_key = $this->rest->key;

		$this->form_validation->set_rules('media_id', 'media id', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$media_id = safe_array_key($this->_data, 'media_id', 0);
			$where = [
				"media_id" => $media_id,
				"user_id" => $user_id,
			];
			$row = $this->app->get_row('media', '', $where);
			if ($row > 0) {
				$this->load->model("uploads_model");
				$media_id = $row['media_id'];
				$user_id = $row['user_id'];
				$name = $row['name'];
				$this->db->update('users', [
					'media_id' => NULL,
				], [
					'user_id' => $user_id,
				]);
				$this->uploads_model->remove_image($media_id, $user_id, $name);
				$this->set_response($this->_response);
			}
		}
	}

	public function remove_media_post() {
		$this->_response["service_name"] = "uploads/remove_media";
		$user_id = $this->rest->user_id;
		$session_key = $this->rest->key;

		$this->form_validation->set_rules('media_guid', 'media id', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$media_guid = safe_array_key($this->_data, 'media_guid', 0);
			$where = [
				"media_guid" => $media_guid,
			];
			$row = $this->app->get_row('media', '', $where);
			if ($row > 0) {
				$this->load->model("uploads_model");
				$media_id = $row['media_id'];
				$name = $row['name'];
				$this->uploads_model->remove_media($media_id, $name);
				$this->_response["message"] = "File removed successfully";
				$this->set_response($this->_response);
			}
		}
	}

	public function upload_base64_post(){
		$this->_response["service_name"] = "uploads/upload_base64";
		$user_id = $this->rest->user_id;
		$session_key = $this->rest->key;
		$this->form_validation->set_rules('type', 'type', 'trim|required|in_list[image,file,audio]');
		$this->form_validation->set_rules('image_crop', 'image crop', 'required');
		$this->form_validation->set_rules('image_type', 'images type', 'required');
		$this->form_validation->set_rules('original_name', 'original name', 'required');
		$this->form_validation->set_rules('cover_type', 'cover type', 'required');
		$this->form_validation->set_rules('cover_type_id', 'cover type id', 'required');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$image_crop = safe_array_key($this->_data, 'image_crop', '');
			$type = safe_array_key($this->_data, 'type', '');
			$file_size = safe_array_key($this->_data, 'file_size', '');
			$image_type = safe_array_key($this->_data, 'image_type', '');
			$original_name = safe_array_key($this->_data, 'original_name', '');
			$cover_type = safe_array_key($this->_data, 'cover_type', '');
			$cover_type_id = safe_array_key($this->_data, 'cover_type_id', '');
			
			$file_name_ext = explode('.', $original_name);
			$ext = strtolower(end($file_name_ext));
			$name = get_guid().'.'.$ext;

			$image = base64_decode($image_crop);
			file_put_contents($this->upload_path."/".$name, $image);
			
			$this->load->model("uploads_model");
			$upload_data = $this->uploads_model->upload_base64($user_id, $original_name, $name, $ext, $file_size);
			// if($cover_type == 'profile_cover'){
			// 	$user = $this->app->get_row('users', 'user_id, cover_media_id', ['user_guid' => $cover_type_id]);
			// 	$this->db->update('users', [
			// 		'cover_media_id' => $upload_data['media_id'],
			// 	], [
			// 		'user_id' => $user['user_id'],
			// 	]);
			// 	if($user['cover_media_id']){
			// 		$this->uploads_model->update_media_status_pending($user['cover_media_id'], "PENDING");
			// 	}
			// } else if($cover_type == 'profile_image'){
			// 	$user = $this->app->get_row('users', 'user_id, media_id', ['user_guid' => $cover_type_id]);
			// 	$this->db->update('users', [
			// 		'media_id' => $upload_data['media_id'],
			// 	], [
			// 		'user_id' => $user['user_id'],
			// 	]);
			// 	if($user['media_id']){
			// 		$this->uploads_model->update_media_status_pending($user['media_id'], "PENDING");
			// 	}
			// }

			$this->set_response($this->_response);
			unset($upload_data['upload_status']);
			unset($upload_data['user_id']);
			unset($upload_data['media_id']);
			$this->_response["data"] = $upload_data;
			$this->_response["message"] = "Image uploaded successfully";
			$this->set_response($this->_response);
		}
	}

	public function upload_base64_image_post(){
		$this->_response["service_name"] = "uploads/upload_base64_image";
		$user_id = $this->rest->user_id;
		$session_key = $this->rest->key;
		$this->form_validation->set_rules('type', 'type', 'trim|required|in_list[image,file,audio]');
		$this->form_validation->set_rules('image_crop', 'image crop', 'required');
		$this->form_validation->set_rules('image_type', 'images type', 'required');
		$this->form_validation->set_rules('original_name', 'original name', 'required');
		$this->form_validation->set_rules('type_id', 'Type id', 'required');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$image_crop = safe_array_key($this->_data, 'image_crop', '');
			$type = safe_array_key($this->_data, 'type', '');
			$file_size = safe_array_key($this->_data, 'file_size', '');
			$image_type = safe_array_key($this->_data, 'image_type', '');
			$type_id = safe_array_key($this->_data, 'type_id', '');
			$original_name = safe_array_key($this->_data, 'original_name', '');
			
			$file_name_ext = explode('.', $original_name);
			$ext = strtolower(end($file_name_ext));
			$name = get_guid().'.'.$ext;
			
			$image = base64_decode($image_crop);
			file_put_contents($this->upload_path."/".$name, $image);
			
			$this->load->model("uploads_model");
			$upload_data = $this->uploads_model->upload_base64($user_id, $original_name, $name, $ext, $file_size);
			if($image_type == 'preview_tempate'){
				$campaign = $this->app->get_row('campaigns', 'campaign_template_id', ['campaign_guid' => $type_id]);
				$campaign_template = $this->app->get_row('campaign_templates', 'preview_media_id', ['campaign_template_id' => $campaign['campaign_template_id']]);
				$this->db->update('campaign_templates', [
					'preview_media_id' => $upload_data['media_id'],
				], [
					'campaign_template_id' => $campaign['campaign_template_id'],
				]);
				if($campaign_template['preview_media_id']){
					$this->uploads_model->update_media_status_pending($campaign_template['preview_media_id'], "PENDING");
				}
			}

			$this->set_response($this->_response);
			unset($upload_data['upload_status']);
			unset($upload_data['user_id']);
			unset($upload_data['media_id']);
			$this->_response["data"] = $upload_data;
			$this->_response["message"] = "Image uploaded successfully";
			$this->set_response($this->_response);
		}
	}

	public function import_excel_post($upload_data, $user_id) {
		$this->load->library('excel');
		$this->load->model("crm_model");
		$source = $this->app->get_row('source_master', 'source_id', ['name' => 'bulk']);
		$source_id = safe_array_key($source, "source_id", 2);
		$inputFileName = $this->upload_path . "/files/" . $upload_data['name'];

		try {
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} catch (Exception $e) {
			return false;
		}
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		// Name	Email	Phone	Street	City	State	Zipcode	Source

		$arrayCount = count($allDataInSheet);
		$flag = 0;
		$createArray = [
			'Name',
			'Email',
			'Phone',
			'Street',
			'City',
			'State',
			'Zipcode',
		];

		$makeArray = [
			'Name' => 'Name',
			'Email' => 'Email',
			'Phone' => 'Phone',
			'Street' => 'Street',
			'City' => 'City',
			'State' => 'State',
			'Zipcode' => 'Zipcode',
		];
		$SheetDataKey = array();
		foreach ($allDataInSheet as $dataInSheet) {
			foreach ($dataInSheet as $key => $value) {
				if (in_array(trim($value), $createArray)) {
					$value = preg_replace('/\s+/', '', $value);
					$SheetDataKey[trim($value)] = $key;
				} else {

				}
			}
		}
		$data = array_diff_key($makeArray, $SheetDataKey);
		if ($arrayCount > 1 && empty($data)) {
			$flag = 1;
		}
		if ($flag == 1) {
			$fetchData = [];
			for ($i = 2; $i <= $arrayCount; $i++) {
				$crm_contact_guid = get_guid();
				$added_by = $user_id;
				$crm_contact_name = $SheetDataKey['Name'];
				$crm_contact_email = $SheetDataKey['Email'];
				$crm_contact_phone = $SheetDataKey['Phone'];
				$crm_contact_street = $SheetDataKey['Street'];
				$crm_contact_city = $SheetDataKey['City'];
				$crm_contact_state = $SheetDataKey['State'];
				$crm_contact_zipcode = $SheetDataKey['Zipcode'];
				$crm_contact_name = filter_var(trim($allDataInSheet[$i][$crm_contact_name]), FILTER_SANITIZE_STRING);
				$crm_contact_email = filter_var(trim($allDataInSheet[$i][$crm_contact_email]), FILTER_SANITIZE_EMAIL);
				$crm_contact_phone = filter_var(trim($allDataInSheet[$i][$crm_contact_phone]), FILTER_SANITIZE_STRING);
				$crm_contact_street = filter_var(trim($allDataInSheet[$i][$crm_contact_street]), FILTER_SANITIZE_STRING);
				$crm_contact_city = filter_var(trim($allDataInSheet[$i][$crm_contact_city]), FILTER_SANITIZE_STRING);
				$crm_contact_state = filter_var(trim($allDataInSheet[$i][$crm_contact_state]), FILTER_SANITIZE_STRING);
				$crm_contact_zipcode = filter_var(trim($allDataInSheet[$i][$crm_contact_zipcode]), FILTER_SANITIZE_STRING);
				$source_type = $source_id;
				$state_id = $this->crm_model->getStateId(strtolower($crm_contact_state));
				$created_at = DATETIME;
				$updated_at = DATETIME;
				if ($source_type && $crm_contact_name && $crm_contact_email) {
					$crm_contact_data = $this->app->get_row('crm_contact', 'crm_contact_id', ['crm_contact_email' => $crm_contact_email]);
					if (empty($crm_contact_data)) {
						$fetchData[] = array('crm_contact_guid' => $crm_contact_guid, 'added_by' => $added_by, 'source_type' => $source_type, 'crm_contact_name' => $crm_contact_name, 'crm_contact_email' => $crm_contact_email, 'crm_contact_phone' => $crm_contact_phone, 'crm_contact_street' => $crm_contact_street, 'crm_contact_city' => $crm_contact_city, 'crm_contact_state' => $state_id, 'crm_contact_zipcode' => $crm_contact_zipcode, 'created_at' => $created_at, 'updated_at' => $updated_at);
					}
				}
			}
			if (!empty($fetchData)) {
				$this->crm_model->setBatchImport($fetchData);
				$this->crm_model->importData();
			}
			return true;

		} else {
			return false;
		}
	}
}