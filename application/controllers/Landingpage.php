<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Landingpage extends CI_Controller {

	function __construct() {
		// Construct the parent class
		parent::__construct();
	}

	public function index() {
		// $this->load->view('dist/index.html');
		// echo "DONE";
	}

	public function cantactus_submit(){
		$this->load->model("campaign_model");
		$this->form_validation->set_rules('campaign_guid', 'Campaign Id', 'trim|required|callback__check_campaign_exist');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			// $this->load->view('landingpages/retail.php');
		} else {
			$this->load->model("users_model");
			$this->load->model("crm_model");
			$is_qr_code = $this->input->post('is_qr_code');
			$name = $this->input->post('name');
			$email = $this->input->post('email');
			$phone = $this->input->post('phone');
			$message = $this->input->post('message');
			$is_sent_notification = $this->input->post('is_sent_notification');
			// $subject = $this->input->post('subject');

			$campaign_guid = $this->input->post('campaign_guid');
			$campaign = $this->app->get_row('campaigns', 'campaign_id, added_by, campaign_name, campaign_template_id', ['campaign_guid' => $campaign_guid]);
			$campaign_id = safe_array_key($campaign, "campaign_id", "");
			$campaign_template_id = safe_array_key($campaign, "campaign_template_id", "");
			$campaign_name = safe_array_key($campaign, "campaign_name", NULL);
			$added_by = safe_array_key($campaign, "added_by", "");
			$campaign_template = $this->app->get_row('campaign_templates', 'email_receiver', ['campaign_template_id' => $campaign_template_id]);
			$campaign_template_id = safe_array_key($campaign_template, "campaign_template_id", "");
			$email_receiver = safe_array_key($campaign_template, "email_receiver", "");
			$sources = $this->app->get_row('source_master', 'source_id', ['name' => 'campaign']);
			$source_id = safe_array_key($sources, "source_id", "");
			$to_email = $email_receiver;
			$cc_email = '';
			$crm_contact = $this->app->get_row('crm_contact', 'crm_contact_id, added_by', ['crm_contact_email' => trim($email)]);
			$crm_contact_old_id = safe_array_key($crm_contact, "crm_contact_id", "");
			if (!$crm_contact_old_id) {
				$crm_contact_old_id = $this->crm_model->add_crm($added_by, $source_id, $name, $email, $phone, NULL, NULL, NULL, NULL, $message);
			}
			$crm_contact_detail = $this->app->get_row('crm_contact', 'added_by', ['crm_contact_id' => $crm_contact_old_id]);
			$added_by = safe_array_key($crm_contact_detail, "added_by", "");
			$user = $this->app->get_row('users', 'business_name', ['user_id' => $added_by]);
			$business_name = safe_array_key($user, 'business_name', '');
			$subject = 'New Contact Submission for ' . $business_name;
			$this->campaign_model->add_cantactus($campaign_id, $crm_contact_old_id, $name, $email, $phone, $message, $is_qr_code);
			// $this->crm_model->add_crm($added_by, $source_id, $name, $email, $phone, NULL, NULL, NULL, NULL, $message);
			$this->crm_model->add_logs($added_by, $crm_contact_old_id, 'CAMPAIGN', $campaign_name, $message);
			$this->crm_model->add_notes($added_by, $crm_contact_old_id, $message);
			
			// $cc_email = CC_EMAIL;
			$this->campaign_model->send_cantactus_email($name, $email, $phone, $message, $to_email, $cc_email, $subject);

			// SEND PUSH NOTIFICATION
			// $title = 'Password Reset';
			// $body = 'Your password has been successfully reset.';
			// push_notification($user_id, $title, $body);

			$title = 'Marketing Tiki ';
			$body = "$name has submitted a query.";
			$extra_data = ['type' => 'post'];
			// $result = push_notification($added_by, $title, $body, $extra_data);
			if($is_sent_notification === 'true') {
				$result = push_notification($added_by, $title, $body, $extra_data);
			}
			// echo $added_by;
			// print_r($result);
		}
	}

	public function _check_campaign_exist($campaign_guid) {
		if (!empty($campaign_guid)) {
			$organization_member = $this->app->get_row('campaigns', 'campaign_id', ['campaign_guid' => $campaign_guid]);
			
			if (empty($organization_member)) {
				$this->form_validation->set_message('_check_campaign_exist', 'Not valid ID.');
				return FALSE;
			}
		}
		return TRUE;
	}

	public function page_view($bussines_name, $unique_string) {
		if($bussines_name === 'rio-aggregate' && $unique_string === 'xs0QNilgVjO6123') {
			$this->load->view('landingpages/retail.php');
		} else {
			$this->load->model("campaign_model");
			$preview = $this->input->get('preview');
			$is_qr_code = $this->input->get('qr');
			if ($is_qr_code) {
				$is_qr_code = 'YES';
			} else {
				$is_qr_code = 'NO';
			}
	
			if($preview && $preview == true){
				$campaign_template = $this->app->get_row('campaign_templates', 'campaign_template_id', ['unique_string' => $unique_string]);
				$campaign_template_id = safe_array_key($campaign_template, "campaign_template_id", "");
				$campaign = $this->app->get_row('campaigns', 'campaign_id, is_landing_page, is_qr_code', ['campaign_template_id' => $campaign_template_id]);
				$is_landing_page = safe_array_key($campaign, "is_landing_page", "NO");
				$is_qr_code = safe_array_key($campaign, "is_qr_code", "NO");
			}else{
				$campaign_template = $this->app->get_row('campaign_templates', 'campaign_template_id, page_url', ['unique_string' => $unique_string]);
				$campaign_template_id = safe_array_key($campaign_template, "campaign_template_id", "");
				$page_url = safe_array_key($campaign_template, "page_url", "");
				$campaign = $this->app->get_row('campaigns', 'campaign_id, is_landing_page, is_qr_code', ['campaign_template_id' => $campaign_template_id, 'status' => 'ACTIVE']);
				$is_landing_page = safe_array_key($campaign, "is_landing_page", "NO");
				$is_qr_code = safe_array_key($campaign, "is_qr_code", "NO");
			}
			if($campaign){
				if ($is_landing_page == 'NO' && $is_qr_code == 'YES') {
					header("Location: " . $page_url);
				}
				
				$campaign_id = safe_array_key($campaign, "campaign_id", "");
				$data['campaign'] = $this->campaign_model->get_details_by_campaign_id($campaign_id);
				$data['campaign']['preview'] = $preview;
				$data['campaign']['is_qr_code'] = $is_qr_code;
				$data['campaign_subject'] = 'Subject';
				$number = $data['campaign']['template_values']['header']['number']['value'];
				$data['campaign']['template_values']['header']['number']['value'] = preg_replace("/^(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $number);

				if ($data['campaign']['template_unique_name'] == 'template_real_estate') {
					$data['campaign']['campaign_subject'] = 'Real Estate';
					$this->load->view('landingpages/headers/template_real_estate_header.php', $data);
					$this->load->view('landingpages/template_real_estate.php', $data);
					$this->load->view('landingpages/footer/footer.php', $data);
				}else if ($data['campaign']['template_unique_name'] == 'template_fitness') {
					$data['campaign']['campaign_subject'] = 'Fitness';
					$this->load->view('landingpages/headers/template_fitness_header.php', $data);
					$this->load->view('landingpages/template_fitness.php', $data);
					$this->load->view('landingpages/footer/footer.php', $data);
				}else if ($data['campaign']['template_unique_name'] == 'template_retail') {
					$data['campaign']['campaign_subject'] = 'Retail';
					$this->load->view('landingpages/headers/template_retail_header.php', $data);
					$this->load->view('landingpages/template_retail.php', $data);
					$this->load->view('landingpages/footer/footer.php', $data);
				}else if ($data['campaign']['template_unique_name'] == 'template_automotive') {
					$data['campaign']['campaign_subject'] = 'Automotive';
					$this->load->view('landingpages/headers/template_automotive_header.php', $data);
					$this->load->view('landingpages/template_automotive.php', $data);
					$this->load->view('landingpages/footer/footer.php', $data);
				}else if ($data['campaign']['template_unique_name'] == 'template_event') {
					$data['campaign']['campaign_subject'] = 'Events';
					$this->load->view('landingpages/headers/template_event_header.php', $data);
					$this->load->view('landingpages/template_event.php', $data);
					$this->load->view('landingpages/footer/footer.php', $data);
				}else if ($data['campaign']['template_unique_name'] == 'template_restaurant') {
					$data['campaign']['campaign_subject'] = 'Restaurant';
					$this->load->view('landingpages/headers/template_restaurant_header.php', $data);
					$this->load->view('landingpages/template_restaurant.php', $data);
					$this->load->view('landingpages/footer/footer.php', $data);
				}else if ($data['campaign']['template_unique_name'] == 'template_media') {
					$data['campaign']['campaign_subject'] = 'Media';
					$this->load->view('landingpages/headers/template_media_header.php', $data);
					$this->load->view('landingpages/template_media.php', $data);
					$this->load->view('landingpages/footer/footer.php', $data);
				}else if($data['campaign']['template_unique_name'] == 'template_simple') {
					$data['campaign']['campaign_subject'] = 'Simple Landing Page';
					$this->load->view('landingpages/headers/template_simple_header.php', $data);
					$this->load->view('landingpages/template_simple.php', $data);
					$this->load->view('landingpages/footer/footer.php', $data);
				}else if($data['campaign']['template_unique_name'] == 'template_detailed') {
					$data['campaign']['campaign_subject'] = 'Detailed Landing Page';
					$this->load->view('landingpages/headers/template_detailed_header.php', $data);
					$this->load->view('landingpages/template_detailed.php', $data);
					$this->load->view('landingpages/footer/footer.php', $data);
				}else{
					$this->load->view('landingpages/template_not-found.php');
				}
			}else{
				$this->load->view('landingpages/template_not-found.php');
			}
		}
		
	}
}