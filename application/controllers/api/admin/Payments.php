<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Payments extends REST_Controller {

	var $_data = array();
	function __construct() {
		// Construct the parent class
		parent::__construct();

		$this->_data = $this->post();
		$this->_data['key'] = "value";
		$this->_response = [
			"status" => TRUE,
			"message" => "Success",
			"errors" => (object) [],
			"data" => (object) [],
		];
		$this->load->library('form_validation');
		$this->form_validation->set_data($this->_data);
		$this->load->model("admin_model/payments_model");
		$this->load->library('MY_Form_validation');
	}

	public function index_get() {
		$this->set_response($this->_response);
	}

	public function test_get() {
		echo 'Get Rest Controller';
	}

        /**
     * PAY FEE
     */
    public function pay_fee_post()
    {
        $this->_response["service_name"] = "admin/pay_fee";
        $session_key = $this->rest->key;
        $this->form_validation->set_rules('student_id', 'Student id', 'trim|callback__check_student_id_exist');
        $this->form_validation->set_rules('amount', 'Batch medium', 'trim|required');
        $this->form_validation->set_rules('type', 'Payment Type', 'trim|required');
        $this->form_validation->set_rules('pay_date', 'Start Date', 'trim|required');
        // $this->form_validation->set_rules('end_date', 'End Date', 'trim|required');
        // $this->form_validation->set_rules('start', 'Start Time', 'trim|required');
        // $this->form_validation->set_rules('end', 'End Time', 'trim|required');
        // $this->form_validation->set_rules('status', 'Status', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $student_guid = safe_array_key($this->_data, "student_id", "");
		    $student_id = get_detail_by_guid($student_guid, 'student');
            $amount = safe_array_key($this->_data, "amount", "");
            $student_data = $this->app->get_row('students', 'total_fee, remain_fee', ['student_id' => $student_id]);
            if($amount > $student_data['remain_fee'] ){
                $this->_response["status"] = false;
                $this->_response["message"] = 'You try to pay more then student fee remaining amount.';
                $this->set_response($this->_response);
            }else{
            $remain_fee = $student_data['remain_fee'] - $amount;
            $type = safe_array_key($this->_data, "type", "");
            $pay_date = safe_array_key($this->_data, "pay_date", "");
            $this->payments_model->update_remain_fee($student_id, $remain_fee);
            $user_id = $this->payments_model->pay_fees($student_id, $amount, $type, $pay_date);
            $this->_response["message"] = 'You have pay fee for student successfully';
            $this->set_response($this->_response);
            }

        }
    }

    	/**
 * FEE LIST
 */
public function get_fee_list_post() {
	$this->_response["service_name"] = "admin/get_fee_list";
	$keyword = safe_array_key($this->_data, "keyword", "");
	$pagination = safe_array_key($this->_data, "pagination", []);
	$limit = safe_array_key($pagination, "limit", 10);
	$offset = safe_array_key($pagination, "offset", 0);
	$sort_by = safe_array_key($this->_data, "sort_by", []);
	$column_name = safe_array_key($sort_by, "column_name", 'first_name');
	$order_by = safe_array_key($sort_by, "order_by", 'acs');
	$filters = safe_array_key($this->_data, "filters", []);
	$status = safe_array_key($filters, "status", '');
	// $this->load->model("users_model");
	$this->_response["data"] = $this->payments_model->get_fee_list( $limit, $offset, $column_name, $order_by, $status);
	$this->_response["counts"] = $this->payments_model->get_fee_list( 0, 0, $column_name, $order_by, $status);
	$this->_response["message"] = "Fee Report List";
	$this->set_response($this->_response);
}

    public function _check_student_id_exist($str) {
		if ($str != "") {
			$student_data = $this->app->get_rows('students', 'student_id', ['student_guid' => $str]);
			if (empty($student_data)) {
				$this->form_validation->set_message('_check_student_id_exist', 'Please provide correct Student id');
				return FALSE;
			}
		}
		return TRUE;
	}




}