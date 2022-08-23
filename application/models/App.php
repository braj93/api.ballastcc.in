<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
APP Model
 */

class App extends CI_Model {

	var $device_types = array();
	var $user_types = array();
	var $user_profile_types = array();
	var $disallowed_email_domains = array();
	var $allowed_image_types = 'gif|jpg|png|JPG|GIF|PNG|jpeg|JPEG|bmp|BMP';
	var $allowed_image_max_size = '4096'; //KB
	var $allowed_image_max_width = '1024';
	var $allowed_image_max_height = '768';
	var $target_date = NULL;

	public function __construct() {
		// Call the CI_Model constructor
		parent::__construct();

		$this->device_types = [
			"1" => "web_browser",
			"2" => "ios",
			"3" => "android",
		];

		$this->user_types = [
			"1" => "admin",
			"2" => "user",
		];

		$this->user_profile_types = [
			"1" => "employer",
			"2" => "collaborator",
			"3" => "candidate",
		];
		
		$this->disallowed_email_domains = array();
		if (ENVIRONMENT == 'production') {
			// $this->disallowed_email_domains = array("mailinator.com");
		}

		$current = new DateTime(DATETIME, new DateTimeZone("UTC"));
		$current->sub(new DateInterval('PT' . ANALYTICS_INTERVAL . 'S'));
		$this->target_date = $current->format('Y-m-d H:i:s');
	}

	/**
	 *
	 * @param type $table
	 * @param type $fields
	 * @param type $where
	 * @return type
	 */
	public function get_rows($table, $fields = "*", $where = array()) {
		$this->db->select($fields);
		$this->db->from($table);
		$this->db->where($where);
		$query = $this->db->get();
		$records = $query->result_array();
		return $records;
	}

	/**
	 *
	 * @param type $table
	 * @param type $fields
	 * @param type $where
	 * @return type
	 */
	public function get_rows_with_order($table, $fields = "*", $where = array(), $sort_field = '', $sort_order = 'ACS') {
		$this->db->select($fields);
		$this->db->from($table);
		$this->db->where($where);
		$this->db->order_by($sort_field, $sort_order);
		$query = $this->db->get();
		$records = $query->result_array();
		return $records;
	}

	/**
	 *
	 * @param type $table
	 * @param type $fields
	 * @param type $where
	 * @return type
	 */
	public function get_rows_or_where($table, $fields = "*", $where = array()) {
		$this->db->select($fields);
		$this->db->from($table);
		// var_dump($where);die();
		for ($i = 0; $i < count($where); $i++) {
			$this->db->or_where('status', $where[$i]);
		}
		$query = $this->db->get();
		$records = $query->result_array();
		// print_r($records);die();
		return $records;
	}

	/**
	 *
	 * @param type $table
	 * @param type $fields
	 * @param type $where
	 * @return type
	 */
	public function get_row($table, $fields = "*", $where = array()) {
		$this->db->select($fields);
		$this->db->from($table);
		$this->db->where($where);
		$query = $this->db->get();
		$record = $query->row_array();
		return $record;
	}

	/**
	 *
	 * @param type $session_key
	 * @return type
	 */
	public function user_data($session_key) {
		$this->db->select('uls.session_key');
		$this->db->select('u.user_id, u.user_guid, u.email,u.mobile, u.first_name, u.last_name');
		$this->db->select('CONCAT(u.first_name, " ", IFNULL (u.last_name, "")) AS name');
		$this->db->select('IFNULL(u.last_login_at,"") AS last_login_at', FALSE);
		$this->db->from('user_login_sessions AS uls');
		$this->db->join('users AS u', 'uls.user_id = u.user_id');
		$this->db->where('uls.session_key', $session_key);
		$query = $this->db->get();
		$user = $query->row_array();
		unset($user['user_id']);
		return $user;
	}

	public function canAddCampaign($added_by, $user_role, $plan_name, $campaign_limit) {
		$count = 0;
		$this->db->select('COUNT(c.campaign_id) as count', FALSE);
		$this->db->from('campaigns AS c');
		$this->db->where('c.added_by', $added_by);
		$query = $this->db->get();
		$count = $query->row()->count;
		// if(($user_role === 'USER_INDIVIDUAL_TEAM' || $user_role === 'USER_INDIVIDUAL_OWNER') && $plan_name === 'ESSENTIAL' && $count >= 1) {
		// 	return "NO";
		//   } else if (($user_role === 'USER_INDIVIDUAL_TEAM' || $user_role === 'USER_INDIVIDUAL_OWNER') && $plan_name === 'PRO' && $count >= 5) {
		// 	return "NO";
		//   } else {
		// 	return "YES";
		// }

		if(($user_role === 'USER_INDIVIDUAL_TEAM' || $user_role === 'USER_INDIVIDUAL_OWNER')){
			if($campaign_limit > $count) {
				return "YES";
			} else {
				return "NO";
			}
		  } else {
			return "YES";
		}

		// if ($campaign_limit == 'NO') {
		// 	return "YES";
		// } else {
		// 	if(($user_role === 'USER_INDIVIDUAL_TEAM' || $user_role === 'USER_INDIVIDUAL_OWNER') && $campaign_limit > $count) {
		// 		return "YES";
		// 	} else {
		// 		return "NO";
		// 	}
		// }
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
	
	public function getPlanDetailsById($added_by) {
		$this->db->select('IFNULL(pp.name, "") AS plan_name', FALSE);
		$this->db->select('IFNULL(pp.pricing_plan_guid, "") AS pricing_plan_guid', FALSE);
		$this->db->select('IFNULL(pp.pricing_plan_id, "") AS pricing_plan_id', FALSE);
		$this->db->from('users AS u');
		$this->db->join('organization_members AS om', 'om.user_id = u.user_id', 'LEFT');
		$this->db->join('pricing_plans AS pp', 'om.pricing_plan_id = pp.pricing_plan_id', 'LEFT');
		$this->db->where('u.user_id', $added_by);
		$query = $this->db->get();
		$user = $query->row_array();
		$user['plan_name'] = strtoupper($user['plan_name']);
		return $user;
	}

	public function getPlanCampaignLimit($added_by) {
		$this->db->select('IFNULL(pp.name, "") AS plan_name', FALSE);
		$this->db->select('IFNULL(pp.campaign_limit, "") AS campaign_limit', FALSE);
		$this->db->from('users AS u');
		$this->db->join('organization_members AS om', 'om.user_id = u.user_id', 'LEFT');
		$this->db->join('pricing_plans AS pp', 'om.pricing_plan_id = pp.pricing_plan_id', 'LEFT');
		$this->db->where('u.user_id', $added_by);
		$query = $this->db->get();
		$user = $query->row_array();
		if ($user['campaign_limit'] === NULL || $user['campaign_limit'] === 'NO') {
			$user['campaign_limit'] = $user['campaign_limit'];
		} else {
			$user['campaign_limit'] = (int)$user['campaign_limit'];
		}

		return $user['campaign_limit'];
	}

	public function user_detail_by_id($user_id) {
		$this->db->select('u.user_type, u.user_sub_type');
		$this->db->select('CONCAT(u.user_type, "_", IFNULL (o.organization_status, ""), "_" , IFNULL(om.role, "")) AS user_role', FALSE);
		$this->db->select('IFNULL(u.user_guid,"") AS user_guid', FALSE);
		$this->db->select('IFNULL(u.first_name,"") AS name', FALSE);
		$this->db->select('IFNULL(u.business_name,"") AS business_name', FALSE);
		$this->db->select('IFNULL(u.email,"") AS email', FALSE);
		$this->db->select('IFNULL(u.customer_stripe_id,"") AS customer_stripe_id', FALSE);
		$this->db->select('IFNULL(u.last_login_at,"") AS last_login_at', FALSE);
		$this->db->select('IFNULL(u.login_at,"") AS login_at', FALSE);
		$this->db->select('IFNULL(u.created_at,"") AS created_at', FALSE);
		$this->db->select('IFNULL(u.status,"") AS status', FALSE);
		$this->db->select('IFNULL(om.pricing_plan_id,"") AS pricing_plan_id', FALSE);
		$this->db->from('users AS u');
		$this->db->join('organization_members AS om', 'om.user_id = u.user_id', 'LEFT');
		$this->db->join('organizations AS o', 'o.organization_id = om.organization_id', 'LEFT');
		$this->db->join('user_plans AS up', 'up.user_id = u.user_id', 'LEFT');
		$this->db->where('u.user_id', $user_id);
		$query = $this->db->get();
		$result = $query->row_array();
		$result['user_id'] = $result['user_guid'];
		$result['user_type'] = $result['user_type'];
		$result['user_sub_type'] = $result['user_sub_type'];
		$result['user_role'] = $result['user_role'];
		$result['name'] = $result['name'];
		$result['business_name'] = $result['business_name'];
		$result['email'] = $result['email'];
		$result['customer_stripe_id'] = $result['customer_stripe_id'];
		$result['last_login_at'] = $result['last_login_at'];
		$result['login_at'] = $result['login_at'];
		$result['created_at'] = $result['created_at'];
		$result['status'] = $result['status'];
		$result['pricing_plan_guid'] = get_detail_by_id($result['pricing_plan_id'], 'plan', 'pricing_plan_guid');
		$result['package'] = $result['pricing_plan_id'] != 0 ? get_detail_by_id($result['pricing_plan_id'], 'plan', 'name') : "--";
		unset($result['pricing_plan_id']);
		return $result;
	}

	public function thousandsCurrencyFormat($num) {
		if ($num > 1000) {
			$x = round($num);
			$x_number_format = number_format($x);
			$x_array = explode(',', $x_number_format);
			$x_parts = array('K', 'M', 'B', 'T');
			$x_count_parts = count($x_array) - 1;
			$x_display = $x;
			$x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
			$x_display .= $x_parts[$x_count_parts - 1];
			return $x_display;
		}
		return $num;
	}

	/**
	 *
	 * @param type $session_key
	 * @return type
	 */
	public function admin_data($session_key) {
		$this->db->select('uls.session_key');
		$this->db->select('u.user_id, u.user_guid, u.email, u.first_name, u.last_name, u.user_type');
		$this->db->select('IFNULL(u.last_login_at,"") AS last_login_at', FALSE);
		$this->db->from('user_login_sessions AS uls');
		$this->db->join('admin_users AS u', 'uls.user_id = u.user_id');
		$this->db->where('uls.session_key', $session_key);
		$query = $this->db->get();
		$user = $query->row_array();
		unset($user['user_id']);
		return $user;
	}

	public function get_user_id_by_session($session_key) {
		$session_user_data = $this->get_row('user_login_sessions', 'user_id', ['session_key' => $session_key]);
		$user_id = safe_array_key($session_user_data, "user_id", 0);
		return $user_id;
	}

	public function is_job_expired($expiry_date) {
		if ($expiry_date >= gmdate("Y-m-d")) {
			return 'NO';
		} else {
			return 'YES';
		}
	}

	public function check_user_active_or_not($user_id) {
		$row = $this->get_row('users', 'email', [
			'user_id' => $user_id,
			'status' => 'ACTIVE',
		]);
		if (empty($row)) {
			return "Your user id is inactive.";
		} else {
			return TRUE;
		}
	}

	public function random_color_part() {
		return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
	}

	public function random_color() {
		return $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
	}

	public function is_hot_job($deadtime) {
		$hot_job_date = Date(DATE_FORMAT, strtotime('+3 days'));
		if ($deadtime == $hot_job_date) {
			return 'YES';
		} else {
			return 'NO';
		}
	}


	public function create_stripe_customer_account($email, $secret_key, $stripeToken){
		require APPPATH .'libraries/stripe/init.php'; // load stripe
		try{
			\Stripe\Stripe::setApiKey($secret_key);

			   $customer = \Stripe\Customer::create(array(
				 'email' => $email,
				 'source'  => $stripeToken
			   ));
			   return ['status' => "success", 'customer' => $customer, 'error' => null];
			   
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
	   
	   return ['status' => "failed", 'error' => $err['message'], 'customer' => null];
	}

	public function stripe_charge_customers($secret_key, $stripe_customer_id, $stripeToken, $amount){
		  
		try{

		   \Stripe\Stripe::setApiKey($secret_key);

			  $stripe_response = \Stripe\Charge::create([
			  "customer" => $stripe_customer_id,
			  "amount" => $amount,
			  "currency" => "usd",
			  "description" => "Customer charge for plan",
			 
			  
			  ]);
			  return ['status' => "success", 'charge' =>  $stripe_response, 'error' => null];
			 

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
		return ['status'=>'failed', 'error' => $err, 'charge' => null];
	}

	public function create_subscription($secret_key, $stripe_customer_id, $plan_id){
		  
		try{

		   \Stripe\Stripe::setApiKey($secret_key);

			  $stripe_response = \Stripe\Subscription::create([
				'customer' => $stripe_customer_id,
				'items' => [['plan' => $plan_id]],
			  ]);

			  return ['status' => "success", 'subscription' =>  $stripe_response, 'error' => null];
			 

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
		return ['status'=>'failed', 'error' => $err, 'charge' => null];
	}

	public function check_pricing_plan_status($secret_key, $pricing_plan_id){
		  
		try{
		   \Stripe\Stripe::setApiKey($secret_key);

			$pricing_plan_response = \Stripe\Plan::retrieve($pricing_plan_id);

			return ['status' => "success", 'pricing_plan_response' =>  $pricing_plan_response, 'error' => null];
		}catch(\Stripe\Error\Card $e){
			$body = $e->getJsonBody();
			$err  = $body['error'];	
		 }catch (\Stripe\Error\RateLimit $e) {
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
		return ['status'=>'failed', 'error' => $err, 'charge' => null];
	}

	public function create_qr_code($qr_code_url) {
		require_once APPPATH . 'libraries/phpqrcode/qrlib.php';
		$qr_code_url = trim($qr_code_url);
		$file_path = UPLOADPATH . '/qr_codes/';
		$folder = $file_path;
		$name = get_guid().'.png';
		$file_name = $folder.$name;
		$status = QRcode::png($qr_code_url, $file_name, "L", 8, 8);
		return $name;
	}

	public function create_stripe_pricing_plan($amount, $interval, $metadata = []){
		  
		try{
			\Stripe\Stripe::setApiKey(STRIPE_SKEY);

			  $stripe_response = \Stripe\Plan::create([
				'amount' => $amount,
				'currency' => 'usd',
				'interval' => $interval,
				'metadata' => $metadata,
				'product' => STRIPE_PRODUCT_ID,
			  ]);
			  return ['status' => "success", 'subscription' =>  $stripe_response, 'error' => null];
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
		return ['status'=>'failed', 'error' => $err, 'charge' => null];
	}

	public function migrate_all_subscribers($old_stripe_pricing_plan_id, $stripe_pricing_plan_id){
		try{
			\Stripe\Stripe::setApiKey(STRIPE_SKEY);

			$plan = \Stripe\Plan::retrieve($stripe_pricing_plan_id, []);
			$result = $plan->delete();
			return ['status' => "success", 'result' =>  $result, 'error' => null];
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
		return ['status'=>'failed', 'error' => $err, 'charge' => null];
	}

	public function delete_stripe_pricing_plan($stripe_pricing_plan_id){
		try{
			\Stripe\Stripe::setApiKey(STRIPE_SKEY);

			$plan = \Stripe\Plan::retrieve($stripe_pricing_plan_id, []);
			$result = $plan->delete();
			return ['status' => "success", 'result' =>  $result, 'error' => null];
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
		return ['status'=>'failed', 'error' => $err, 'charge' => null];
	}

	public function update_stripe_pricing_plan_metadata($stripe_pricing_plan_id, $metadata){
		  
		try{
			\Stripe\Stripe::setApiKey(STRIPE_SKEY);

			$result = \Stripe\Plan::update($stripe_pricing_plan_id,['metadata' => $metadata]);
			return ['status' => "success", 'result' =>  $result, 'error' => null];
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
		return ['status'=>'failed', 'error' => $err, 'charge' => null];
	}
}
