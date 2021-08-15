<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

	function __construct() {
		// Construct the parent class
		parent::__construct();
		// $this->load->library('s3_upload');
	}

	public function index() {
		$insert_array = [
			"start_at" => DATETIME
		];
		$this->db->insert('cron_logs', $insert_array);
		$cron_log_id = $this->db->insert_id();
		$this->sent_broadcast();
		// $this->publish_campaign();
		$this->update_campaign_reports();

		// $this->job_expire_notified_reminder(10, 11);
		// $this->job_expire_notified_reminder(5, 12);
		// $this->job_expire_notified_reminder(2, 13);

		// $this->claim_organisation_reminder();

		// $this->expire_notified_all_star_membership(10, 21);
		// $this->expire_notified_all_star_membership(5, 22);
		// $this->expire_notified_all_star_membership(2, 23);

		// $this->expire_notified_organisation_vip_membership(10);
		// $this->expire_notified_organisation_vip_membership(5);
		// $this->expire_notified_organisation_vip_membership(2);

		// $this->reminder_for_sceduled_interview();
		// $this->delete_pending_media();

		// $this->load->helper('file');
		// $filename = "./uploads/cron_data.txt";
		// $data = "Cron executed at :" . DATETIME;
		// write_file($filename, $data, 'a+');
		$date = gmdate("Y-m-d H:i:s");
		$this->db->update('cron_logs', ['end_at' => $date], [
			'cron_log_id' => $cron_log_id,
		]);
		echo "DONE";
	}

	public function publish_campaign(){
		$this->load->model("campaign_model");
		$data['campaign'] = $this->campaign_model->publish_campaign();
	}

	public function update_campaign_reports(){
		$this->load->model("campaign_model");
		$data['campaign'] = $this->campaign_model->update_campaign_reports();
	}

	public function sent_broadcast(){
		$this->load->model("admin_model/broadcast_model", "broadcast_model");
		$broadcast_list = $this->app->get_rows('broadcast', '*', [
			'status' => 'UNSENT',
			'scheduled_at <=' => DATETIME,
		]);
		foreach ($broadcast_list as $key => $row) {
			$user_list = $this->broadcast_model->get_user_list($row['is_user_active'], $row['is_user_inactive'], $row['is_last_thirty_days_signed_up'], $row['is_last_login'], $row['last_login_from_date'], $row['last_login_to_date'], $row['is_agnecy'], $row['agency'], $row['is_non_agnecy'], $row['non_agency'], $row['is_agency_users'], $row['is_agency_envitee_users'], $row['is_individual_users']);
			// print_r($user_list);
			// die();
			foreach ($user_list as $k => $val) {
				$this->broadcast_model->send_message($val['user_id'], $row['broadcast_id']);
				
			}
			$this->broadcast_model->update_broadcast_status($row['broadcast_id'], 'SENT');
		}
	}

	public function job_expire_notified_reminder($days, $notifications_template_id) {
		$current_date = gmdate("Y-m-d");
		$date = new DateTime($current_date);
		$date->add(new DateInterval('P' . $days . 'D'));
		$future_date = $date->format('Y-m-d');
		$rows = $this->app->get_rows('jobs', 'job_guid, job_id, deadtime, user_id, title', [
			'deadtime' => $future_date,
			'expire_notified_' . $days => 'NO',
		]);
		// print_r($rows);die;
		foreach ($rows as $key => $value) {
			// SEND EMAIL
			$this->load->helper('email');
			$email_template = "emailer/expire_job_reminder";
			$subject = 'Tikisites - Your Job Post is about to Expire!';
			$member = "Teammate!";
			$email_data = [
				"member" => $member,
				"days" => $days,
				"job_title" => $value['title'],
			];
			$email = get_detail_by_id($value['user_id'], 'user', 'email');
			$message = $this->load->view($email_template, $email_data, TRUE);
			$this->load->library('email');
			$this->email->from(SUPPORT_EMAIL, FROM_NAME);
			$this->email->to($email);
			$this->email->subject($subject);
			$this->email->message($message);
			$this->email->send();

			// SEND NOTIFICATION
			$this->load->model("notifications_model");
			$parameters = [];
			$parameters[0]['refrence_id'] = $value['job_id'];
			$parameters[0]['type'] = 'job';
			$this->notifications_model->save($notifications_template_id, 0, [$value['user_id']], $value['job_id'], $parameters);

			// SEND PUSH NOTIFICATION
			$title = 'Tikisites';
			$body = get_detail_by_id($notifications_template_id, 'notification_type', 'notification_template');
			push_notification($value['user_id'], $title, $body);

			// UPDATE IN JOBS
			$this->load->model("jobs_model");
			$this->jobs_model->update_expire_job_reminder($value['job_guid'], $days);
		}
	}

	public function claim_organisation_reminder() {
		$rows = $this->app->get_rows('organisation_members', 'organisation_member_guid, email, organisation_id, last_reminder_sent', [
			'user_id' => NULL,
			'role' => 'SUPEROWNER',
		]);

		$past_date = NULL;
		foreach ($rows as $key => $value) {
			if ($value['last_reminder_sent'] != NULL) {
				$past_date = date('Y-m-d', strtotime("-" . REMINDER_FREQUENCY . "days"));
			}

			$organisation_type_id = get_detail_by_id($value['organisation_id'], 'organisation', 'organisation_type_id');
			$type = get_detail_by_id($organisation_type_id, 'organisation_type', 'name');
			$organisation_type = ucfirst(strtolower($type));

			if ($value['last_reminder_sent'] == NULL || date_format(date_create($value['last_reminder_sent']), 'Y-m-d') == $past_date) {
				// SEND EMAIL
				$this->load->helper('email');
				$email_template = "emailer/claim_organisation_reminder";
				$subject = 'Tikisites - Remember to Claim Your Company!';
				$member = "Teammate!";
				$email_data = [
					"member" => $member,
				];
				$email = $value['email'];
				$message = $this->load->view($email_template, $email_data, TRUE);
				$this->load->library('email');
				$this->email->from(SUPPORT_EMAIL, FROM_NAME);
				$this->email->to($email);
				$this->email->subject($subject);
				$this->email->message($message);
				$this->email->send();

				// UPDATE IN organisation_members
				$this->load->model("organisations_model");
				$this->organisations_model->update_last_reminder_sent($value['organisation_member_guid']);
			}
		}
	}

	public function expire_notified_all_star_membership($days, $notifications_template_id) {
		$current_date = gmdate("Y-m-d");
		$date = new DateTime($current_date);
		$date->add(new DateInterval('P' . $days . 'D'));
		$future_date = $date->format('Y-m-d');
		$rows = $this->app->get_rows('users', 'first_name, last_name, email, all_star_validity, user_guid, user_id', [
			'is_vip' => 'YES',
			'all_star_validity' => $future_date,
			'expire_notified_' . $days => 'NO',
		]);
		foreach ($rows as $key => $value) {
			// SEND NOTIFICATION
			$this->load->model("notifications_model");
			$parameters = [];
			$this->notifications_model->save($notifications_template_id, 0, [$value['user_id']], $value['user_id'], $parameters);

			// SEND PUSH NOTIFICATION
			$title = 'Tikisites';
			$body = get_detail_by_id($notifications_template_id, 'notification_type', 'notification_template');
			push_notification($value['user_id'], $title, $body);

			// SEND EMAIL
			$this->load->helper('email');
			$email_template = "emailer/expire_all_star_membership_reminder";
			$subject = 'Tikisites - Your All-Star Membership is about to Expire!';
			$member = "Teammate!";
			$email_data = [
				"member" => $member,
				"days" => $days,
			];
			$email = $value['email'];
			$message = $this->load->view($email_template, $email_data, TRUE);
			$this->load->library('email');
			$this->email->from(SUPPORT_EMAIL, FROM_NAME);
			$this->email->to($email);
			$this->email->subject($subject);
			$this->email->message($message);
			$this->email->send();

			// UPDATE IN users
			$this->load->model("users_model");
			$this->users_model->update_expire_all_star_membership_reminder($value['user_guid'], $days);
		}
	}

	public function expire_notified_organisation_vip_membership($days) {
		$current_date = gmdate("Y-m-d");
		$date = new DateTime($current_date);
		$date->add(new DateInterval('P' . $days . 'D'));
		$future_date = $date->format('Y-m-d');
		$rows = $this->app->get_rows('organisations', 'organisation_guid, superowner_email_id, name, organisation_type_id', [
			'is_vip' => 'YES',
			'vip_validity' => $future_date,
			'expire_notified_' . $days => 'NO',
		]);

		foreach ($rows as $key => $value) {
			$type = get_detail_by_id($value['organisation_type_id'], 'organisation_type', 'name');
			$organisation_type = ucfirst(strtolower($type));

			// SEND EMAIL
			$this->load->helper('email');
			$email_template = "emailer/expire_organisation_vip_membership_reminder";
			$subject = "Tikisites - Your $organisation_type Page is about to Expire!";
			$member = "Teammate!";
			$email_data = [
				"member" => $member,
				"days" => $days,
				"organisation_type" => $organisation_type,
				"organisation_name" => $value['name'],
			];
			$email = $value['superowner_email_id'];
			$message = $this->load->view($email_template, $email_data, TRUE);
			$this->load->library('email');
			$this->email->from(SUPPORT_EMAIL, FROM_NAME);
			$this->email->to($email);
			$this->email->subject($subject);
			$this->email->message($message);
			$this->email->send();

			// // SEND NOTIFICATION
			// $this->load->model("notifications_model");
			// $parameters = [];
			// $parameters[0]['refrence_id'] = $value['job_id'];
			// $parameters[0]['type'] = 'job';
			// $this->notifications_model->save($notifications_template_id, 0, [$value['user_id']], $value['job_id'], $parameters);

			// UPDATE IN ORGANISATIONS
			$this->load->model("organisations_model");
			$this->organisations_model->update_expire_vip_memebership_reminder($value['organisation_guid'], $days);
		}
	}

	// $date_in_gmt = "2020-11-14 20:23:39"; //(YYYY-mm-dd H:i:s)
	// echo $this->gmdate_to_mydate($date_in_gmt);
	public function gmdate_to_mydate($gmdate) {
		/* $gmdate must be in YYYY-mm-dd H:i:s format*/
		// $timezone = 'Asia/Kolkata';
		$timezone = date_default_timezone_get();
		$userTimezone = new DateTimeZone($timezone);
		$gmtTimezone = new DateTimeZone('GMT');
		$myDateTime = new DateTime($gmdate, $gmtTimezone);
		$offset = $userTimezone->getOffset($myDateTime);
		return date("M j, Y, g:i A", strtotime($gmdate) + $offset);
	}

	public function push_notifications() {
		// Load composer
		require 'vendor/autoload.php';

		// Instantiate the client with the project api_token and sender_id.
		$client = new \Fcm\FcmClient(FCM_SERVER_KEY, FCM_SENDER_ID);

		// Instantiate the push notification request object.
		$notification = new \Fcm\Push\Notification();

		// Enhance the notification object with our custom options.
		$notification
			->addRecipient($deviceId)
			->setTitle('Hello from php-fcm!')
			->setBody('Notification body')
			->addData('key', 'value');

		// Send the notification to the Firebase servers for further handling.
		$client->send($notification);
	}

	public function upload_to_s3_bucket() {
		$rows = $this->app->get_rows('media', 'name');
		foreach ($rows as $key => $value) {
			$path_name = FCPATH . 'uploads/' . $value['name'];
			if (file_exists($path_name)) {
				$file_url = $this->s3_upload->upload_file($path_name);
			}
		}
		echo 'DATA SUCCESSFULLY UPLOADED ON S3 BUCKET.';
	}

	public function delete_pending_media() {
		$rows = $this->app->get_rows('media', 'media_id, name', ['status' => 'PENDING']);
		foreach ($rows as $key => $value) {
			if (IMAGE_SERVER == 'REMOTE') {
				$this->s3_upload->delete_file($value['name']);
				$this->db->delete('media', ['media_id' => $value['media_id']]);
			} else {
				$path_name = FCPATH . 'uploads/' . $value['name'];
				if (file_exists($path_name)) {
					unlink($path_name);
				}
				$this->db->delete('media', ['media_id' => $value['media_id']]);
			}
		}
	}

	public function reminder_for_sceduled_interview() {
		$current_date = gmdate("Y-m-d");
		$query = $this->db->query("SELECT uaj.applied_job_guid, u.email FROM user_applied_jobs AS uaj LEFT JOIN users AS u ON u.user_id = uaj.user_id WHERE date(uaj.call_date_time) = '" . $current_date . "' AND uaj.reminder = 'NO'");
		$results = $query->result_array();
		foreach ($results as $key => $value) {
			// SEND EMAIL
			$this->load->helper('email');
			$email_template = "emailer/call_request_accept";
			$subject = 'Tikisites - Interview Reminder!';
			$member = "Teammate!";
			$email_data = [
				"member" => $member,
			];
			$email = safe_array_key($value, 'email', '');
			$message = $this->load->view($email_template, $email_data, TRUE);
			$this->load->library('email');
			$this->email->from(SUPPORT_EMAIL, FROM_NAME);
			$this->email->to($email);
			$this->email->subject($subject);
			$this->email->message($message);
			$this->email->send();

			$this->db->update('user_applied_jobs', ['reminder' => 'YES'], ['applied_job_guid' => $value['applied_job_guid']]);
		}
	}

	public function import_excel() {
		// print_r('import_excel');
		// die();
		$this->load->library('excel');
		
		// $this->_response["service_name"] = "uploads";
		// $session_key = $this->rest->key;
		// $user_id = $this->rest->user_id;
		 
	    // $this->form_validation->set_rules('type', 'type', 'trim|required|in_list[pdf,image,file,audio,audio_mp3,audio_wav,other_audio_mp3,other_audio_wav]');
		// //$this->form_validation->set_rules('upfile', 'select file ', 'trim|required');
		// if ($this->form_validation->run() == FALSE) {
		// 	$errors = $this->form_validation->error_array();
		// 	$this->_response["message"] = current($errors);
		// 	$this->_response["errors"] = $errors;
		// 	$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		// } else {
		// 	$this->load->model("uploads_model");
		// 	$type = safe_array_key($this->_data, "type", "");
		// 	$is_profile_picture = safe_array_key($this->_data, 'is_profile_picture', 0);
			
		// 	if ($type == "image") {
		// 		$upload_data = $this->uploads_model->upload_image($this->_data, $user_id);
		// 	}elseif ($type == "file") {
		// 		$upload_data = $this->uploads_model->upload_file($this->_data, $user_id, $type);
		// 	}

		// 	if ($upload_data['upload_status']) {

		// 	}
		// }


        
        // if ($this->input->post('importfile')) {
        //     $path = ROOT_UPLOAD_IMPORT_PATH;
 
        //     $config['upload_path'] = $path;
        //     $config['allowed_types'] = 'xlsx|xls|jpg|png';
        //     $config['remove_spaces'] = TRUE;
        //     $this->upload->initialize($config);
        //     $this->load->library('upload', $config);
        //     if (!$this->upload->do_upload('userfile')) {
        //         $error = array('error' => $this->upload->display_errors());
        //     } else {
        //         $data = array('upload_data' => $this->upload->data());
        //     }
            
        //     if (!empty($data['upload_data']['file_name'])) {
        //         $import_xls_file = $data['upload_data']['file_name'];
        //     } else {
        //         $import_xls_file = 0;
		//     }
		

			// $data = [
			// 	'source_type' => $source_type,
			// 	'crm_contact_name' => $crm_contact_name,
			// 	'crm_contact_email' => $crm_contact_email,
			// 	'crm_contact_phone' => $crm_contact_phone,
			// 	'crm_contact_street' => $crm_contact_street,
			// 	'crm_contact_city' => $crm_contact_city,
			// 	'crm_contact_state' => $crm_contact_state,
			// 	'crm_contact_zipcode' => $crm_contact_zipcode,
			// 	"updated_at" => DATETIME,
			// ];

			// $createArray = [
			// 	'source_type',
			// 	'crm_contact_name',
			// 	'crm_contact_email',
			// 	'crm_contact_phone',
			// 	'crm_contact_street',
			// 	'crm_contact_city',
			// 	'crm_contact_state',
			// 	'crm_contact_zipcode',
			// 	'created_at',
			// 	// 'updated_at',
			// ];

			// $makeArray = [
			// 	'source_type' => 'source_type',
			// 	'crm_contact_name' => 'crm_contact_name',
			// 	'crm_contact_email' => 'crm_contact_email',
			// 	'crm_contact_phone' => 'crm_contact_phone',
			// 	'crm_contact_street' => 'crm_contact_street',
			// 	'crm_contact_city' => 'crm_contact_city',
			// 	'crm_contact_state' => 'crm_contact_state',
			// 	'crm_contact_zipcode' => 'crm_contact_zipcode',
			// 	// 'created_at' => 'created_at',
			// 	// 'updated_at' => 'updated_at',
			// ];
            // $inputFileName = $this->upload_path . "/files/" . $upload_data['name'];
            // $inputFileName = $this->upload_path . "/files/contact";
            $inputFileName = "./uploads" . "/files/contact.xls";
            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                        . '": ' . $e->getMessage());
            }
            $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            
            $arrayCount = count($allDataInSheet);
            $flag = 0;
			// $createArray = array('First_Name', 'Last_Name', 'Email', 'DOB', 'Contact_NO');
			$createArray = [
				'source_type',
				'crm_contact_name',
				'crm_contact_email',
				'crm_contact_phone',
				'crm_contact_street',
				'crm_contact_city',
				'crm_contact_state',
				'crm_contact_zipcode',
				'created_at',
				// 'updated_at',
			];

			$makeArray = [
				'source_type' => 'source_type',
				'crm_contact_name' => 'crm_contact_name',
				'crm_contact_email' => 'crm_contact_email',
				'crm_contact_phone' => 'crm_contact_phone',
				'crm_contact_street' => 'crm_contact_street',
				'crm_contact_city' => 'crm_contact_city',
				'crm_contact_state' => 'crm_contact_state',
				'crm_contact_zipcode' => 'crm_contact_zipcode',
				// 'created_at' => 'created_at',
				// 'updated_at' => 'updated_at',
			];

            // $makeArray = array('First_Name' => 'First_Name', 'Last_Name' => 'Last_Name', 'Email' => 'Email', 'DOB' => 'DOB', 'Contact_NO' => 'Contact_NO');
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
			// print_r($makeArray);
			// print_r($SheetDataKey);
			// die();
            $data = array_diff_key($makeArray, $SheetDataKey);
           
            if (empty($data)) {
                $flag = 1;
            }
            if ($flag == 1) {
                for ($i = 2; $i <= $arrayCount; $i++) {
                    $source_type = $SheetDataKey['source_type'];
                    $crm_contact_name = $SheetDataKey['crm_contact_name'];
                    $crm_contact_email = $SheetDataKey['crm_contact_email'];
                    $crm_contact_phone = $SheetDataKey['crm_contact_phone'];
					$crm_contact_street = $SheetDataKey['crm_contact_street'];
					$crm_contact_city = $SheetDataKey['crm_contact_city'];
					$crm_contact_state = $SheetDataKey['crm_contact_state'];
					$crm_contact_zipcode = $SheetDataKey['crm_contact_zipcode'];
                    $source_type = filter_var(trim($allDataInSheet[$i][$source_type]), FILTER_SANITIZE_STRING);
                    $crm_contact_name = filter_var(trim($allDataInSheet[$i][$crm_contact_name]), FILTER_SANITIZE_STRING);
                    $crm_contact_email = filter_var(trim($allDataInSheet[$i][$crm_contact_email]), FILTER_SANITIZE_EMAIL);
                    $crm_contact_phone = filter_var(trim($allDataInSheet[$i][$crm_contact_phone]), FILTER_SANITIZE_STRING);
					$crm_contact_street = filter_var(trim($allDataInSheet[$i][$crm_contact_street]), FILTER_SANITIZE_STRING);
					$crm_contact_city = filter_var(trim($allDataInSheet[$i][$crm_contact_city]), FILTER_SANITIZE_STRING);
					$crm_contact_state = filter_var(trim($allDataInSheet[$i][$crm_contact_state]), FILTER_SANITIZE_STRING);
					$crm_contact_zipcode = filter_var(trim($allDataInSheet[$i][$crm_contact_zipcode]), FILTER_SANITIZE_STRING);

                    $fetchData[] = array('source_type' => $source_type, 'crm_contact_name' => $crm_contact_name, 'crm_contact_email' => $crm_contact_email, 'crm_contact_phone' => $crm_contact_phone, 'crm_contact_street' => $crm_contact_street, 'crm_contact_city' => $crm_contact_city, 'crm_contact_state' => $crm_contact_state, 'crm_contact_zipcode' => $crm_contact_zipcode);
                }              
				$data['employeeInfo'] = $fetchData;
				
				print_r($fetchData);
				die();

                $this->import->setBatchImport($fetchData);
                $this->import->importData();
            } else {
                echo "Please import correct file";
            }
        // }
        
	}

	// export xlsx|xls file
    public function test() {
        $data['page'] = 'export-excel';
        $data['title'] = 'Export Excel data | TechArise';
        $data['employeeInfo'] = $this->export->employeeList();
    // load view file for output
        $this->load->view('export/index', $data);
    }

	public function createXLS() {

		$path_name = UPLOADPATH . '/files/';
		// create file name
		$fileName = 'crm_contact.xlsx';  
		// load excel library
		$this->load->library('excel');
		$crm_contacts = $this->app->get_rows('crm_contact', '*');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		// set Header
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Email');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Phone');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Date Added');
		// $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Contact_No');       
		// set Row
		$rowCount = 2;
		foreach ($crm_contacts as $element) {
			// $element['created_at'] = Apr 21, 2020 $element['created_at']->format('M - DD - y');

			$current_date_time = $element['created_at'];
			$current = new DateTime($current_date_time, new DateTimeZone("UTC"));
			$current = $current->format('M d, Y');


			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element['crm_contact_name']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['crm_contact_email']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['crm_contact_phone']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $current);
			// $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element['contact_no']);
			$rowCount++;
		}
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save($path_name.$fileName);
		// header("Content-Type: application/vnd.ms-excel");
		// redirect(HTTP_UPLOAD_IMPORT_PATH.$fileName);    
		$this->load->helper('download');
	    $data = file_get_contents($path_name.$fileName);
	    force_download(strtolower($fileName), $data);
		unlink($path_name.$fileName);
	}
	

	

public function FunctionDemoName(){
	require_once APPPATH.'third_party/PHPExcel.php';
	$this->excel = new PHPExcel(); 


	$file_info = pathinfo($_FILES["result_file"]["name"]);
	$file_directory = "uploads/";
	$new_file_name = date("d-m-Y ") . rand(000000, 999999) .".". $file_info["extension"];

	if(move_uploaded_file($_FILES["result_file"]["tmp_name"], $file_directory . $new_file_name))
	{   
    $file_type	= PHPExcel_IOFactory::identify($file_directory . $new_file_name);
    $objReader	= PHPExcel_IOFactory::createReader($file_type);
    $objPHPExcel = $objReader->load($file_directory . $new_file_name);
    $sheet_data	= $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
    foreach($sheet_data as $data)
    {
        // $result = array(
        //         'Loat_NO' => $data['A'],
        //         'C_Name' => $this->input->post('name'),
        //         'Lab' => $data['V'],
        //         'C_Shape' => $shape,
        //         'C_Carat' => $data['C'],
        //         'C_Weight' => $data['C'],
        //         'C_Color' => $C_Color,
        //         'C_Clarity' => $C_Clarity,
        //         'C_Rap' => $data['F'],
        //         'C_Discount' => $supplierdiscount,
        //         'C_Rate' => ($data['H'])?$data['H']:'',
        //         'C_NetD' => $data['I'],
        //         'C_DefthP' => $data['S'],
        //         'C_TableP' => $data['T'],
        //         'C_Cut' => $C_Cut,
        //         'C_Polish' => $C_Polish,
        //         'C_Symmetry' => $C_Symmetry,
        //         'C_Fluorescence' => $C_Fluorescence,
        //         'Milky' => ($data['P'])?$data['P']:'',
        //         'Certi_NO' => $data['W'],
        //         'Key_Symbols' => $data['X'],
        //         'C_Length' => $C_Length,
        //         'C_Width' => $C_Width,
        //         'C_Depth' => $C_Depth,
        //         'Location' => '16',
        //         'is_delete' => '0',
		// );
		

		$makeArray = [
			'source_type' => $data['A'],
			'crm_contact_name' => $data['B'],
			'crm_contact_email' => $data['C'],
			'crm_contact_phone' => $data['D'],
			'crm_contact_street' => $data['E'],
			'crm_contact_city' => $data['F'],
			'crm_contact_state' => $data['G'],
			'crm_contact_zipcode' => $data['H'],
			// 'created_at' => 'created_at',
			// 'updated_at' => 'updated_at',
		];

        $this->Uploaddiamond_Model->postDiamond($result);
    }
}
}

}

// if ($prev_year == substr($key, 0, 4)) {
// 				// if (date('n', strtotime($key)) != "1") {
// 				// 	$f_dates[$prev_year]["1"] = 0;
// 				// } else {
// 				// 	$f_dates[$prev_year][date('n', strtotime($key))] = $value;
// 				// }
// 				// if (date('n', strtotime($key)) != "2") {
// 				// 	$f_dates[$prev_year]["2"] = 0;
// 				// } else {
// 				// 	$f_dates[$prev_year][date('n', strtotime($key))] = $value;
// 				// }
// 				// if (date('n', strtotime($key)) != "3") {
// 				// 	$f_dates[$prev_year]["3"] = 0;
// 				// } else {
// 				// 	$f_dates[$prev_year][date('n', strtotime($key))] = $value;
// 				// }
// 				// if (date('n', strtotime($key)) != "4") {
// 				// 	$f_dates[$prev_year]["4"] = 0;
// 				// } else {
// 				// 	$f_dates[$prev_year][date('n', strtotime($key))] = $value;
// 				// }
// 				// if (date('n', strtotime($key)) != "5") {
// 				// 	$f_dates[$prev_year]["5"] = 0;
// 				// } else {
// 				// 	$f_dates[$prev_year][date('n', strtotime($key))] = $value;
// 				// }
// 				// if (date('n', strtotime($key)) != "6") {
// 				// 	$f_dates[$prev_year]["6"] = 0;
// 				// } else {
// 				// 	$f_dates[$prev_year][date('n', strtotime($key))] = $value;
// 				// }
// 				// if (date('n', strtotime($key)) != "7") {
// 				// 	$f_dates[$prev_year]["7"] = 0;
// 				// } else {
// 				// 	$f_dates[$prev_year][date('n', strtotime($key))] = $value;
// 				// }
// 				// if (date('n', strtotime($key)) != "8") {
// 				// 	$f_dates[$prev_year]["8"] = 0;
// 				// } else {
// 				// 	$f_dates[$prev_year][date('n', strtotime($key))] = $value;
// 				// }
// 				// if (date('n', strtotime($key)) != "9") {
// 				// 	$f_dates[$prev_year]["9"] = 0;
// 				// } else {
// 				// 	$f_dates[$prev_year][date('n', strtotime($key))] = $value;
// 				// }
// 				// if (date('n', strtotime($key)) != "10") {
// 				// 	$f_dates[$prev_year]["10"] = 0;
// 				// } else {
// 				// 	$f_dates[$prev_year][date('n', strtotime($key))] = $value;
// 				// }
// 				// if (date('n', strtotime($key)) != "11") {
// 				// 	$f_dates[$prev_year]["11"] = 0;
// 				// } else {
// 				// 	$f_dates[$prev_year][date('n', strtotime($key))] = $value;
// 				// }
// 				// if (date('n', strtotime($key)) != "12") {
// 				// 	$f_dates[$prev_year]["12"] = 0;
// 				// } else {
// 				// 	$f_dates[$prev_year][date('n', strtotime($key))] = $value;
// 				// }
// 				$months[date('n', strtotime($key))] = $value;
// 				$f_dates[$prev_year] = $months;
// 			}

// 			if ($current_year == substr($key, 0, 4)) {
// 				$months[date('n', strtotime($key))] = $value;
// 				$f_dates[$current_year] = $months;
// 				// $f_dates[$current_year][date('n', strtotime($key))] = $value;
// 			}
// 			if ($next_year == substr($key, 0, 4)) {
// 				$f_dates[$next_year][date('n', strtotime($key))] = $value;
// 			}
