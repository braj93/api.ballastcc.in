<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This is an example of a few basic image/file upload methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Uploads_model extends CI_Model {

	var $image_server = "local";
	var $upload_path = "uploads/";
	public function __construct() {
		// Call the CI_Model constructor
		parent::__construct();
		$this->image_server = "local";
		$this->upload_path = "uploads/";
		
	}

	public function upload_base64($user_id, $original_name, $name, $ext, $file_size) {
		$media = array(
			'media_guid' => get_guid(),
			'user_id' => $user_id,
			'original_name' => $original_name,
			'name' => $name,
			'size' => $file_size,
			'extension' => $ext,
			'status' => 'ACTIVE',
			'created_at' => DATETIME,
			'updated_at' => DATETIME,
		);
		$this->db->insert('media', $media);
		$media_id = $this->db->insert_id();
		$media['upload_status'] = TRUE;
		$media['media_id'] = $media_id;
		$media['full_path'] = site_url('uploads/' . $media['name']);
		return $media;
	}

	public function upload_file($data, $user_id, $type) {
		// $this->check_directory_exist($this->upload_path . "/files/");
		$config['upload_path'] = $this->upload_path . "/files/";
		$config['max_size'] = 5000000;
		$config['encrypt_name'] = TRUE;
		$config['allowed_types'] = 'pdf|PDF|doc|DOC|docx|DOCX|rtf|RTF|txt|TXT|ppt|PPT|xls|XLS|xlsx|XLSX|pptx|PPTX';
		$this->load->library('upload', $config);

		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('qqfile')) {
			$return['upload_status'] = FALSE;
			$errors = $this->upload->error_msg;
			// print_r($errors);die();
			if (!empty($errors)) {
				$return['message'] = $errors['0']; // first message
			} else {
				$return['message'] = "Unable to fetch error code."; // first message
			}
			return $return;
			//Shows all error messages as a string
		} else {
			$upload_data = $this->upload->data();
			$file_name_ext = explode('.', $upload_data['file_name']);
			$ext = strtolower(end($file_name_ext));
			if (strtolower($this->image_server) == 'remote') {
				// code to upload file at remove server
			}
			$media_guid = get_guid();
			$media = array(
				'media_guid' => $media_guid,
				'user_id' => $user_id,
				'original_name' => $upload_data['orig_name'],
				'name' => $upload_data['file_name'],
				'size' => $upload_data['file_size'],
				'extension' => $ext,
				'status' => 'PENDING',
				'created_at' => DATETIME,
				'updated_at' => DATETIME,
			);
			$this->db->insert('media', $media);
			$media_id = $this->db->insert_id();
			$media['upload_status'] = TRUE;
			$media['media_id'] = $media_id;
			return $media;
		}
	}

	public function upload_image($data, $user_id) {
		$this->check_directory_exist($this->upload_path. "images/");
		$config['upload_path'] = $this->upload_path . "images/";
		$config['allowed_types'] = 'gif|jpg|png|JPG|GIF|PNG|jpeg|JPEG|mp3|wav';
		$config['max_size'] = 10024;
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload');	
		$this->upload->initialize($config);		
		if (!$this->upload->do_upload('qqfile')) {
			$return['upload_status'] = FALSE;
			$errors = $this->upload->error_msg;
			print_r($errors);die();
			if (!empty($errors)) {
				$return['message'] = $errors['0']; // first message
			} else {
				$return['message'] = "Unable to fetch error code."; // first message
			}
			return $return;
			//Shows all error messages as a string
		} else {
			$upload_data = $this->upload->data();
			// print_r($upload_data);die();
			$file_name_ext = explode('.', $upload_data['file_name']);
			$ext = strtolower(end($file_name_ext));

			if (strtolower($this->image_server) == 'remote') {

				// code to upload file at remove server
			}

			$media = array(
				'media_guid' => get_guid(),
				'user_id' => $user_id,
				'original_name' => $upload_data['orig_name'],
				'name' => $upload_data['file_name'],
				'size' => $upload_data['file_size'],
				'extension' => $ext,
				'status' => 'PENDING',
				'created_at' => DATETIME,
				'updated_at' => DATETIME,
			);

			$this->db->insert('media', $media);
			$media_id = $this->db->insert_id();
			$media['upload_status'] = TRUE;
			$media['media_id'] = $media_id;
			$media['media_url'] = $upload_data['file_name'] ? site_url('/uploads/images/' . $upload_data['file_name']) : "";
			return $media;
		}
	}

	public function upload_document($data, $user_id) {

		//$this->check_directory_exist($this->upload_path);
		$config['upload_path'] = $this->upload_path . "/";
		//$config['allowed_types']    = 'pdf|PDF|doc|DOC|docx|DOCX|rtf|RTF|txt|TXT|ppt|PPT|xls|XLS|xlsx|XLSX|pptx|PPTX';
		$config['max_size'] = 4096;
		$config['encrypt_name'] = TRUE;

		$attachment_for = safe_array_key($data, "attachment_for", 0);
		switch ($attachment_for) {
		case 1: //Mandate
			$config['allowed_types'] = 'pdf|PDF|doc|DOC|docx|DOCX|xls|XLS|xlsx|XLSX|ppt|PPT|pptx|PPTX';
			break;
		case 2: //Resume
			$config['allowed_types'] = 'pdf|PDF|doc|DOC|docx|DOCX';
			break;
		case 3: //Offer
			$config['allowed_types'] = 'pdf|PDF|doc|DOC|docx|DOCX|xls|XLS|xlsx|XLSX';
			break;
		default:
			$config['allowed_types'] = 'pdf|PDF|doc|DOC|docx|DOCX';
			break;
		}

		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('upfile')) {
			$return['upload_status'] = FALSE;
			$errors = $this->upload->error_msg;
			if (!empty($errors)) {
				$return['message'] = $errors['0']; // first message
			} else {
				$return['message'] = "Unable to fetch error code."; // first message
			}
			return $return;
			//Shows all error messages as a string
		} else {
			$upload_data = $this->upload->data();

			$file_name_ext = explode('.', $upload_data['file_name']);
			$ext = strtolower(end($file_name_ext));

			if (strtolower($this->image_server) == 'remote') {

				// code to upload file at remove server
			}

			$media = array(
				'media_guid' => get_guid(),
				'user_id' => $user_id,
				'original_name' => $upload_data['orig_name'],
				'name' => $upload_data['file_name'],
				'size' => $upload_data['file_size'],
				'extension' => $ext,
				'status' => 'PENDING',
				'created_at' => DATETIME,
				'updated_at' => DATETIME,
			);

			$this->db->insert('media', $media);
			$media_id = $this->db->insert_id();
			$media['upload_status'] = TRUE;
			$media['full_path'] = site_url('uploads/' . $media['name']);
			$media['$media_id'] = $media_id;
			return $media;
		}
	}

	public function check_directory_exist($dir_name) {
		$d = $_SERVER['DOCUMENT_ROOT'].'/'. $dir_name ;
		// print_r($d);
		// die();
		if (!is_dir($d)) {
			mkdir($d, 0777);
		}
	}

	public function remove_image($media_id, $user_id, $name) {
		$this->db->where('media_id', $media_id);
		$this->db->where('user_id', $user_id);
		$this->db->delete('media');
		unlink('uploads/' . $name);
	}

	public function remove_media($media_id, $name) {
		$this->db->where('media_id', $media_id);
		$this->db->delete('media');
		unlink('uploads/' . $name);
	}

	public function remove_file_media($media_id, $name, $path) {
		$this->db->where('media_id', $media_id);
		$this->db->delete('media');
		unlink($path . $name);
	}

	public function update_media_status($media_id, $status) {
		$data = array(
			'status' => $status,
		);
		$this->db->where('media_id', $media_id);
		$this->db->update('media', $data);
	}

	public function delete_pending_images() {
		$created_at_obj = new DateTime(DATETIME, new DateTimeZone("UTC"));
		$created_at_obj->sub(new DateInterval('PT' . THIRTY_DAYS . 'S'));
		$created_at = $created_at_obj->format("Y-m-d H:i:s");
		$rows = $this->app->get_rows('media', '*', ['created_at <=' => $created_at, 'status' => 'PENDING']);
		foreach ($rows as $key => $row) {
			$this->remove_media($row['media_id'], $row['name']);
		}
	}

	public function update_media_status_pending($media_id, $status) {
		$data = array(
			'status' => $status
		);
		$this->db->where('media_id', $media_id);
		$this->db->update('media', $data);
	}

}
