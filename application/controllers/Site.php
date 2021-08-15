<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends CI_Controller {

	function __construct() {
		// Construct the parent class
		parent::__construct();
		$this->load->library('form_validation');
      	$this->load->library('session');
	}

	public function index() {
		echo "DONE";
	}

	public function retail(){
		$this->load->view('landingpages/retail.php');
	}

	public function send_notification_for_contact_lead($user_id, $song_id, $template_id, $action_type) {
			$this->load->model("notifications_model");
			$parameters = array();
			$parameters[0]['refrence_id'] = $song_id;
			$parameters[0]['type'] = 'song';
			$parameters[1]['refrence_id'] = $user_id;
			$parameters[1]['type'] = 'user';
			$this->notifications_model->save($template_id, $user_id, array(1), $song_id, $parameters);
	}

	public function test_pushnotification() {
		// $this->send_notification_for_checked_songs($user_id, $song_id, 25, $action_type);
		echo 'YES';
		$title = 'Marketing Tiki ';
		$body = "John has submited a query.";
		$extra_data = ['type' => 'post'];
		push_notification(2, $title, $body, $extra_data);
		// push_notification($added_by, $title, $body, $extra_data);

		// cUV5q5FTwXQ:APA91bGfwvyFcP9BfJtUqtIySmURv8VVb9Qmbf0AOv6zR6lzc9VhnmeQvgws13b56INkQvD14CX3yDoPxPTBpTPkaTvZWCeXMhEZhDkUGrH8HuZXIGL4Zlg-emrMYDYch7Pj0Fn7bKpF

		$fcmUrl = 'https://fcm.googleapis.com/fcm/send';
 		$token = $this->input->get('token');

    	$notification = [
            'title' =>'title',
            'body' => 'body of message.',
            'icon' =>'myIcon', 
            'sound' => 'mySound'
		];
		
        $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=' . FCM_SERVER_KEY,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);


        echo $result;
		// echo 'YES';
		// die;
		// $json_data = [
		// 	'message' => 'This is dummy message',
		// 	'message_form' => 'From User',
		// 	'message_to' => 'To User',
		// 	'message' => 'Test',
		// ];
		// $data = json_encode($json_data);
		// //FCM API end-point
		// $url = 'https://fcm.googleapis.com/fcm/send';
		// //api_key in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
		// $server_key = FCM_SERVER_KEY;
		// //header with content_type api key
		// $headers = array(
		// 	'Content-Type:application/json',
		// 	'Authorization:key='.$server_key
		// );
		// //CURL request to route notification to FCM connection server (provided by Google)
		// $ch = curl_init();
		// curl_setopt($ch, CURLOPT_URL, $url);
		// curl_setopt($ch, CURLOPT_POST, true);
		// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		// $result = curl_exec($ch);
		// if ($result === FALSE) {
		// 	die('Oops! FCM Send Error: ' . curl_error($ch));
		// }
		// curl_close($ch);
	}

	public function retail_submit(){
		$this->load->model("users_model");
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$this->load->view('landingpages/retail.php');
		} else {
			$this->load->model("users_model");
			$name = $this->input->post('name');
			$email = $this->input->post('email');
			$phone = $this->input->post('phone');
			$message = $this->input->post('message');
			$to_email = 'office@rioaggregate.com';
			$cc_email = 'ryan@bciinteractive.com';
			$this->users_model->send_retail_contact_email($name, $email, $phone, $message, $to_email, $cc_email);
			redirect('site/retail');
		}
	}

	public function upgrade_or_downgrade_plan() {
		// print_r('fdsfdsfs');
		// die;

		require APPPATH .'libraries/stripe/init.php'; // load stripe

		try{

			\Stripe\Stripe::setApiKey(STRIPE_SKEY);
			// $subscription_update = \Stripe\Plan::update('plan_I3t6ZCZScelzKb',['metadata' => ['order_id' => '', 'note' => 'Dummy note 23']]);
			// print_r($subscription_update);
			// die();
			$subscriptions = \Stripe\Subscription::all(array('limit' => 1, 'plan' => 'plan_I4J4Ys7eMyCClb', 'status' => 'all', 'include[]' => 'total_count'));
			echo $subscriptions->total_count;
			die();			
			$plan = \Stripe\Plan::retrieve('plan_I4amGtxnpDDH8C', []);
			print_r($plan);
			die();
			$subscriptions = \Stripe\Subscription::all(['price'=> 'plan_I4IZaU6zlGYt8W', 'limit' => 100]);
			print_r($subscriptions);
			die();

			$plans = \Stripe\Plan::all(['product'=> 'prod_I2T0PUIwVRuHto', 'limit' => 100]);
			// print_r($plans);
			// die();
			foreach($plans->data as $key => $value) {
				$plan = \Stripe\Plan::retrieve($value['id'], []);
				$result = $plan->delete();
			}
			die();
			$subscription = \Stripe\Subscription::retrieve('sub_I4GTaJtf1heNcO');
			print_r($subscription);
			// $plan = \Stripe\Plan::retrieve('plan_I4EfeQe7he3gtT');
			// $plan = \Stripe\Plan::retrieve('plan_I2P41RPwg7bBNW', []);
			// $result = $plan->delete();
			// print_r($plan);
			// print_r('$result');
			// print_r($result);
			// echo 'YES';
			die();
			//   return ['status' => "success", 'subscription' =>  $stripe_response, 'error' => null];

			// $stripe = new \Stripe\StripeClient(
			// 	'sk_test_yzdkYc6t9GzoDMEfTeNBcDuG00rVcpv242'
			//   );
			//   $stripe->plans->create([
			// 	'amount' => 9900,
			// 	'currency' => 'usd',
			// 	'interval' => 'month',
			// 	'product' => 'prod_GlIO0CeaHt7uPx',
			//   ]);
			  $subscription_update = \Stripe\Plan::create([
				'amount' => 0,
				'currency' => 'usd',
				'interval' => 'month',
				'product' => STRIPE_PRODUCT_ID,
			  ]);

			// $subscription = \Stripe\Subscription::retrieve('sub_I4GTaJtf1heNcO');

			// $subscription_update = \Stripe\Plan::retrieve('plan_GlIPbdrHRtWs6d');
			// $subscription_update = \Stripe\Plan::all(['limit' => 10]);
			// $subscription = \Stripe\Subscription::retrieve('sub_Hyb842FQxShe4q');

			// $subscription_update = \Stripe\Subscription::update('sub_Hyb842FQxShe4q', [
			// 	'cancel_at_period_end' => true,
			// 	'proration_behavior' => 'create_prorations',
			// 		'items' => [
			// 			[
			// 			'id' => $subscription->items->data[0]->id,
			// 			'price' => 'plan_GlIQTDB8y0bFFp',
			// 			],
			// 		],
			// ]);

			print_r($subscription_update);
			die();

			// $stripe = new \Stripe\StripeClient(
			// 	'sk_test_yzdkYc6t9GzoDMEfTeNBcDuG00rVcpv242'
			//   );
			// $stripe->plans->all(['limit' => 3]);
			  

			// $header = array();
			// $header[] =  'Authorization: Bearer ' . STRIPE_SKEY;
			// $url = "https://api.stripe.com/v1/invoices/upcoming/lines?customer=cus_Ho6XKT9EXZkiYH&limit=5";
			// $ch = curl_init();
			// curl_setopt($ch, CURLOPT_URL, $url);
			// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			// curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			// $data = curl_exec($ch);
			// $result = json_decode($data, true);

			// print_r($result);
			print_r('******************************************');
			print_r($subscription_update);
			// $subscriptions = \Stripe\Subscription::all(array('limit' => 1, 'plan' => 'plan-name-here', 'status' => 'trialing|active|past_due|unpaid|all', 'include[]' => 'total_count'));
			// echo $subscriptions->total_count;
			
			// return ['status' => "success", 'customer' => $customer, 'error' => null];
				
		}
		catch(\Stripe\Error\Card $e){
				$body = $e->getJsonBody();
					$err  = $body['error'];
						
		}

		catch (\Stripe\Error\RateLimit $e) {
			// Too many requests made to the API too quickly
				$body = $e->getJsonBody();
				$err  = $body['error'];
				
		} catch (\Stripe\Error\InvalidRequest $e) {
			// Invalid parameters were supplied to Stripe's API
				$body = $e->getJsonBody();
				$err  = $body['error'];
			
		} catch (\Stripe\Error\Authentication $e) {
			// Authentication with Stripe's API failed
			// (maybe you changed API keys recently)
				$body = $e->getJsonBody();
				$err  = $body['error'];
				
		} catch (\Stripe\Error\ApiConnection $e) {
			// Network communication with Stripe failed
				$body = $e->getJsonBody();
				$err  = $body['error'];
			

		} catch (\Stripe\Error\Base $e) {
			// Display a very generic error to the user, and maybe send
			// yourself an email
				$body = $e->getJsonBody();
				$err  = $body['error'];
			
		} catch (Exception $e) {
			// Something else happened, completely unrelated to Stripe
				$body = $e->getJsonBody();
					$err  = $body['error'];
					
		}
		print_r($err['message']);
		
		// return ['status' => "failed", 'error' => $err['message'], 'customer' => null];	
	}

	public function createp() {
		// echo 'dsfdsf';
		// die();
		// include "phpqrcode/qrlib.php";
		require_once APPPATH . 'libraries/phpqrcode/qrlib.php';
		// QRcode::png("My First QR Code");
		// $image = QRcode::png("http://www.sitepoint.com", "test.png", "L", 4, 4);
		// echo $image;

		$path = 'http://localhost/tikisites/uploads/qr_codes/';
		$qrtext = 'My text';	
		if(isset($qrtext))
		{
			// $SERVERFILEPATH = $_SERVER['DOCUMENT_ROOT'].'/qrcode-generation-in-codeigniter/images/';
			$SERVERFILEPATH = UPLOADPATH . '/qr_codes/';
			$text = $qrtext;
			$folder = $SERVERFILEPATH;
			$file_name1 = "-Qrcode" . rand(2,200) . ".png";
			$file_name = $folder.$file_name1;
			// QRcode::png($text,$file_name);	
			// QRcode::png("http://www.sitepoint.com", "test.png", "L", 4, 4);	
			QRcode::png("http://localhost/tikisites/test/AwHkNKr9QBat", $file_name, "L", 8, 8);	
			echo"<center><img src=".$path.$file_name1."></center";
		}
		else
		{
		 echo 'No Text Entered';
		}
	}

	public function downlod_qr_code() {
		$this->load->model("campaign_model");
		$type = strtolower($this->input->get('type'));
		$campaign_guid = $this->input->get('id');
		$campaign = $this->app->get_row('campaigns', 'campaign_id', ['campaign_guid' => $campaign_guid]);
		$campaign_id = safe_array_key($campaign, "campaign_id", "");
		$campaign = $this->campaign_model->get_media($campaign_id);
		$qr_code_name = safe_array_key($campaign, 'qr_code_name', '');
		if($type == 'pdf'){
			require_once APPPATH . 'libraries/mypdf.php';
			$image = UPLOADPATH . '/qr_codes/'.$qr_code_name;
			$exp=explode('.',$qr_code_name);
			$new_name = 'QR_code.pdf';
			$pdf = new Mypdf();
			$pdf->AddPage();
			$pdf->centreImage($image);
			$pdf->Output('D', $new_name);
		}else{
			$this->load->helper('download');
			$data = file_get_contents(UPLOADPATH . '/qr_codes/'.$qr_code_name);
			$new_name = 'QR_code.' . $type;
			// $new_name = 'QR_code.png';
			force_download($new_name, $data);
		}
	}

	public function export_billing() {
		$user_guid = $this->input->get('id');
		$path_name = UPLOADPATH . '/files/';
		// create file name
		$fileName = 'BillingReport.xlsx';  
		// load excel library
		$this->load->library('excel');
		$user = $this->app->get_row('users', 'user_id', ['user_guid' => $user_guid]);
		$user_id = $user['user_id'];
		$this->load->model("admin_model/users_manage_model", "users_manage_model");
		$agency = $this->users_manage_model->get_agency_billing_export_contacts($user_id);
		$result = [];
		$result[] = [
			'name' => $agency['name'],
			'business_name' => '--',
			'plan' => $agency['package'],
			'created_at' => $agency['created_at'],
			'billing_days' => '--',
			'amount' => '--'
	    ];
		foreach ($agency['agency_users'] as $agency_user) {
			$result[] = [
				'name' => '--',
				'business_name' => $agency_user['business_name'],
				'plan' => $agency_user['package'],
				'created_at' => $agency_user['created_at'],
				'billing_days' => $agency_user['billing_days'],
				'amount' => '$' . $agency_user['plan_amount']
			];
		}
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Agency Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Business Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Plan');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Signup Date');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Days In This Billing Cycle');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Amount To Be Billed');   
		// set Row
		$rowCount = 2;
		foreach ($result as $res) {
			$current_date_time = $res['created_at'];
			$current = new DateTime($current_date_time, new DateTimeZone("UTC"));
			$current = $current->format('M d, Y');
			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $res['name']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $res['business_name']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $res['plan']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $current);
			$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $res['billing_days']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $res['amount']);
			$rowCount++;
		}
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save($path_name.$fileName);   
		$this->load->helper('download');
	    $data = file_get_contents($path_name.$fileName);
	    force_download(strtolower($fileName), $data);
		unlink($path_name.$fileName);
	}
	
	public function export_user_contact_file() {
		$user_guid = $this->input->get('id');
		$path_name = UPLOADPATH . '/files/';
		// create file name
		$fileName = 'crm_contact.xlsx';  
		// load excel library
		$this->load->library('excel');
		$user = $this->app->get_row('users', 'user_id', ['user_guid' => $user_guid]);
		$user_id = $user['user_id'];
		$this->load->model("crm_model");
		$this->load->model("users_model");
		$user_role = $this->users_model->get_user_role($user_id);
		$added_by_id = safe_array_key($user_role, "added_by", "");
		$user_role = safe_array_key($user_role, "role", "");
		if ($added_by_id && $user_role == 'TEAM') {
			$user_id = $added_by_id;
		}
		$crm_contacts = $this->crm_model->crm_user_contacts($user_id);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		// set Header
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Email');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Phone');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Street');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'City');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'State');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Zipcode');
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Source');
		$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Date Added');    
		// set Row
		$rowCount = 2;
		foreach ($crm_contacts as $crm_contact) {
			$current_date_time = $crm_contact['created_at'];
			$current = new DateTime($current_date_time, new DateTimeZone("UTC"));
			$current = $current->format('M d, Y');
			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $crm_contact['crm_contact_name']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $crm_contact['crm_contact_email']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $crm_contact['crm_contact_phone']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $crm_contact['crm_contact_street']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $crm_contact['crm_contact_city']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $crm_contact['state']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $crm_contact['crm_contact_zipcode']);
			$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $crm_contact['source']);
			$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $current);
			$rowCount++;
		}
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save($path_name.$fileName);   
		$this->load->helper('download');
	    $data = file_get_contents($path_name.$fileName);
	    force_download(strtolower($fileName), $data);
		unlink($path_name.$fileName);
	}

	public function export_all_contact_file() {
		$path_name = UPLOADPATH . '/files/';
		// create file name
		$fileName = 'crm_contact.xlsx';  
		// load excel library
		$this->load->library('excel');
		$this->load->model("crm_model");
		$crm_contacts = $this->crm_model->crm_all_contacts();
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		// set Header
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Email');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Phone');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Street');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'City');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'State');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Zipcode');
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Source');
		$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Date Added');    
		// set Row
		$rowCount = 2;
		foreach ($crm_contacts as $crm_contact) {
			$current_date_time = $crm_contact['created_at'];
			$current = new DateTime($current_date_time, new DateTimeZone("UTC"));
			$current = $current->format('M d, Y');
			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $crm_contact['crm_contact_name']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $crm_contact['crm_contact_email']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $crm_contact['crm_contact_phone']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $crm_contact['crm_contact_street']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $crm_contact['crm_contact_city']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $crm_contact['state']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $crm_contact['crm_contact_zipcode']);
			$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $crm_contact['source']);
			$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $current);
			$rowCount++;
		}
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save($path_name.$fileName);   
		$this->load->helper('download');
	    $data = file_get_contents($path_name.$fileName);
	    force_download(strtolower($fileName), $data);
		unlink($path_name.$fileName);
	}

	public function testpost(){
		$url = "https://api.callrail.com/v3/a.json";
		$fields_string = '';
		$fields = array(
			'grant_type' => MUSIMAP_CLIENT_GRANT_TYPE,
			'client_id' => MUSIMAP_CLIENT_ID,
			'client_secret' => MUSIMAP_CLIENT_SECRET,
		);

		//url-ify the data for the POST
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');

		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		// pass header variable in curl method
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		if($errno = curl_errno($ch)) {
			$error_message = curl_strerror($errno);
			echo "cURL error ({$errno}):\n {$error_message}";
		}
		curl_close ($ch);
		$result = json_decode($server_output, true);
		if (!array_key_exists('token', $result)) {
			// return $result['error'];
			return NULL;
		}
		return $result['token'];

	}

	public function testget(){
		$utoken = 'Token token="57b29b5700be014e6a0975fb539a085f';
		// $url = "https://api.callrail.com/v3/a.json?Authorization=' . $utoken";
		$url = "https://api.callrail.com/v3/a/" . CALL_RAIL_ACCOUNT_ID . "/trackers.json";

		$header = array();
		$header[] = 'Authorization: Token token="57b29b5700be014e6a0975fb539a085f';

		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, [
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url
		]);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);

		print_r('$resp');
		print_r($resp);
		die();
	}
}