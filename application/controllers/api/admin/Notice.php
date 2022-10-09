<?php

defined('BASEPATH') or exit('No direct script access allowed');

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
class Notice extends REST_Controller
{

    var $_data = array();

    function __construct()
    {
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
        $this->load->model("admin_model/Imp_notice_model");
        $this->load->model("admin_model/master_model");
        $this->load->library('MY_Form_validation');
    }

    public function index_get()
    {
        $this->set_response($this->_response);
    }

    public function test_get()
    {
        echo 'Get Rest Controller of Imp notice';
    }

    /**
     * CREATE COURSE
     */
    public function add_imp_notice_post()
    {
        $this->_response["service_name"] = "admin/notice/add_imp_notice";
        $user_id = $this->rest->user_id;
        
        $this->form_validation->set_rules('subject', 'Notice subject', 'trim|required');
        $this->form_validation->set_rules('notice', 'notice', 'trim|required');
        $this->form_validation->set_rules('color', 'Color', 'trim|required');
        $this->form_validation->set_rules('type', 'Notice type', 'trim|required');
        $this->form_validation->set_rules('exp_date', 'End date', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $subject_title = safe_array_key($this->_data, "subject", "");
            $notice = safe_array_key($this->_data, "notice", "");
            $color = safe_array_key($this->_data, "color", "");
            $type = safe_array_key($this->_data, "type", "");
            $exp_date = safe_array_key($this->_data, "exp_date", "");            
            $subject = strtolower($subject_title);
            $status = safe_array_key($this->_data, "status", "");
            $notice_id = $this->Imp_notice_model->create_imp_notice($subject, $notice, $color, $type, $exp_date, $status);
            $this->_response["message"] = 'Imp Notice created successfully';
            $this->set_response($this->_response);
        }
    }
    /**
     * EDIT COURSE
     */
    public function edit_imp_notice_post()
    {
        $this->_response["service_name"] = "admin/notice/edit_imp_notice";
        $user_id = $this->rest->user_id;

        $this->form_validation->set_rules('notice_id', 'Notice Id', 'trim|required|callback__check_imp_notice_exist');

        $this->form_validation->set_rules('subject', 'Notice subject', 'trim|required');
        $this->form_validation->set_rules('notice', 'notice', 'trim|required');
        $this->form_validation->set_rules('color', 'Color', 'trim|required');
        $this->form_validation->set_rules('type', 'Notice type', 'trim|required');
        $this->form_validation->set_rules('exp_date', 'End date', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $notice_guid = safe_array_key($this->_data, "notice_id", "");
            $notice = $this->app->get_row('imp_notices', 'notice_id', ['notice_guid' => $notice_guid]);
            $notice_id = safe_array_key($notice, "notice_id", "");
            $subject_title = safe_array_key($this->_data, "subject", "");
            $notice = safe_array_key($this->_data, "notice", "");
            $color = safe_array_key($this->_data, "color", "");
            $type = safe_array_key($this->_data, "type", "");
            $exp_date = safe_array_key($this->_data, "exp_date", "");            
            $subject = strtolower($subject_title);
            $status = safe_array_key($this->_data, "status", "");
            $this->Imp_notice_model->edit_imp_notice($notice_id, $subject, $notice, $color, $type, $exp_date, $status);
            $this->_response['message'] = "Imp Notice Updated successfully.";
            $this->set_response($this->_response);
        }
    }
    /**
     * LIST IMP NOTICE
     */
    public function imp_notice_list_post()
    {
        $this->_response["service_name"] = "admin/notice/imp_notice_list";
        $this->form_validation->set_rules('keyword', 'keyword', 'trim');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            // $this->load->model("users_model");
            $user_id = $this->rest->user_id;
            $user = $this->app->get_row('users', 'user_type', ['user_id' => $user_id]);
            $user_type = safe_array_key($user, "user_type", "");
            $keyword = safe_array_key($this->_data, "keyword", "");
            $pagination = safe_array_key($this->_data, "pagination", []);
            $limit = safe_array_key($pagination, "limit", 10);
            $offset = safe_array_key($pagination, "offset", 0);
            $sort_by = safe_array_key($this->_data, "sort_by", []);
            $column_name = safe_array_key($sort_by, "column_name", 'subject');
            $order_by = safe_array_key($sort_by, "order_by", 'acs');
            $this->_response["data"] = $this->Imp_notice_model->list($user_id, $keyword, $limit, $offset, $column_name, $order_by, $user_type);
            $this->_response["counts"] = $this->Imp_notice_model->list($user_id, $keyword, 0, 0, $column_name, $order_by, $user_type);
            $this->set_response($this->_response);
        }
    }

    /**
     * GET Course DETAILS BY ID API
     */
    public function get_imp_notice_by_id_post()
    {
        $this->_response["service_name"] = "admin/notice/get_imp_notice_by_id";
        $this->form_validation->set_rules('notice_id', 'Notice Id', 'trim|required|callback__check_imp_notice_exist');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $notice_guid = safe_array_key($this->_data, "notice_id", "");
            $notice = $this->app->get_row('imp_notices', 'notice_id', ['notice_guid' => $notice_guid]);
            $notice_id = safe_array_key($notice, "notice_id", "");
            $data = $this->Imp_notice_model->get_imp_notice_by_id($notice_id);
            $this->_response["data"] = $data;
            $this->_response["message"] = "Notice details";
            $this->set_response($this->_response);
        }
    }




    public function _check_imp_notice_exist($notice_guid)
    {
        if (!empty($notice_guid)) {
            $notice = $this->app->get_row('imp_notices', 'notice_id', ['notice_guid' => $notice_guid]);

            if (empty($notice)) {
                $this->form_validation->set_message('_check_imp_notice_exist', 'Not valid Notice ID.');
                return FALSE;
            }
        }
        return TRUE;
    }
}
