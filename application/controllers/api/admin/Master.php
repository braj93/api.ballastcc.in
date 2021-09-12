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
class Master extends REST_Controller
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
        $this->load->model("admin_model/master_model");
        $this->load->library('MY_Form_validation');
    }

    public function index_get()
    {
        $this->set_response($this->_response);
    }

    public function test_get()
    {
        echo 'Get Rest Controller';
    }

    /**
     * BATCH REGISTERATION
     */
    public function add_batch_post()
    {
        $this->_response["service_name"] = "admin/addbatch";
        $session_key = $this->rest->key;
        $this->form_validation->set_rules('name', 'Batch Name', 'trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('medium', 'Batch medium', 'trim|required');
        $this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
        $this->form_validation->set_rules('end_date', 'End Date', 'trim|required');
        $this->form_validation->set_rules('start', 'Start Time', 'trim|required');
        $this->form_validation->set_rules('end', 'End Time', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $name = safe_array_key($this->_data, "name", "");
            $medium = safe_array_key($this->_data, "medium", "");
            $start_date = safe_array_key($this->_data, "start_date", "");
            $end_date = safe_array_key($this->_data, "end_date", "");
            $start = safe_array_key($this->_data, "start", "");
            $end = safe_array_key($this->_data, "end", "");
            $status = safe_array_key($this->_data, "status", "");
            $user_id = $this->master_model->create_batch($name, $medium, $start_date, $end_date,  $start, $end, $status);
            $this->_response["message"] = 'You have created new batch successfully';
            $this->set_response($this->_response);
        }
    }
    public function get_batches_get()
    {
        $this->_response["service_name"] = "admin/get_batches";
        $batches_data = $this->app->get_rows('batches', 'batch_id,batch_guid,name,start_date,end_date,start,end,medium,status', []);
        if (empty($batches_data)) {
            $batches_data = [];
        }
        $this->_response["data"] = $batches_data;
        $this->set_response($this->_response);
    }

    public function edit_batch_post()
    {
        $this->_response["service_name"] = "students/edit_batches";
        $this->form_validation->set_rules('batch_id', 'batch id', 'trim|required');
        $this->form_validation->set_rules('name', 'Batch Name', 'trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('medium', 'Batch medium', 'trim|required');
        $this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
        $this->form_validation->set_rules('end_date', 'End Date', 'trim|required');
        $this->form_validation->set_rules('start', 'Start Time', 'trim|required');
        $this->form_validation->set_rules('end', 'End Time', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $batch_guid = safe_array_key($this->_data, "batch_id", "");
            $name = safe_array_key($this->_data, "name", "");
            $medium = safe_array_key($this->_data, "medium", "");
            $start_date = safe_array_key($this->_data, "start_date", "");
            $end_date = safe_array_key($this->_data, "end_date", "");
            $start = safe_array_key($this->_data, "start", "");
            $end = safe_array_key($this->_data, "end", "");
            $status = safe_array_key($this->_data, "status", "");
            $batch_id = get_detail_by_guid($batch_guid, 'batch');
            $this->_response["data"] = $this->master_model->update_batch($batch_id, $name, $medium, $start_date, $end_date,  $start, $end, $status);
            $this->_response["message"] = "Success.";
            $this->set_response($this->_response);
        }
    }

    public function delete_batch_post()
    {
        $this->_response["service_name"] = "admin/delete_batches";
        $this->form_validation->set_rules('batch_id', 'batch id', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $batch_guid = safe_array_key($this->_data, "batch_id", "");
            $batch_id = get_detail_by_guid($batch_guid, 'batch');
            $this->_response["data"] = $this->master_model->delete_batch($batch_id);
            $this->_response["message"] = "batch deleted Successfully.";
            $this->set_response($this->_response);
        }
    }

        /**
     * CLASS REGISTERATION
     */
    public function add_class_post()
    {
        $this->_response["service_name"] = "admin/addclass";
        $session_key = $this->rest->key;
        $this->form_validation->set_rules('name', 'Class Name', 'trim|required|min_length[2]|max_length[50]');
        // $this->form_validation->set_rules('medium', 'Batch medium', 'trim|required');
        // $this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
        // $this->form_validation->set_rules('end_date', 'End Date', 'trim|required');
        // $this->form_validation->set_rules('start', 'Start Time', 'trim|required');
        // $this->form_validation->set_rules('end', 'End Time', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $name = safe_array_key($this->_data, "name", "");
            // $medium = safe_array_key($this->_data, "medium", "");
            // $start_date = safe_array_key($this->_data, "start_date", "");
            // $end_date = safe_array_key($this->_data, "end_date", "");
            // $start = safe_array_key($this->_data, "start", "");
            // $end = safe_array_key($this->_data, "end", "");
            $status = safe_array_key($this->_data, "status", "");
            $user_id = $this->master_model->create_class($name, $status);
            $this->_response["message"] = 'You have created new class successfully';
            $this->set_response($this->_response);
        }
    }
    public function get_classes_get()
    {
        $this->_response["service_name"] = "admin/get_classes";
        $classes_data = $this->app->get_rows('classes', 'class_id,class_guid,name,status', []);
        if (empty($classes_data)) {
            $classes_data = [];
        }
        $this->_response["data"] = $classes_data;
        $this->set_response($this->_response);
    }

    public function edit_class_post()
    {
        $this->_response["service_name"] = "admin/edit_class";
        $this->form_validation->set_rules('class_id', 'class id', 'trim|required');
        $this->form_validation->set_rules('name', 'Batch Name', 'trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $class_guid = safe_array_key($this->_data, "class_id", "");
            $name = safe_array_key($this->_data, "name", "");
            $status = safe_array_key($this->_data, "status", "");

            $class_id = get_detail_by_guid($class_guid, 'class');
            $this->_response["data"] = $this->master_model->update_class($class_id, $name, $status);
            $this->_response["message"] = "Success.";
            $this->set_response($this->_response);
        }
    }

    public function delete_class_post()
    {
        $this->_response["service_name"] = "admin/delete_class";
        $this->form_validation->set_rules('class_id', 'class id', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $class_guid = safe_array_key($this->_data, "class_id", "");
            $class_id = get_detail_by_guid($class_guid, 'class');
            $this->_response["data"] = $this->master_model->delete_class($class_id);
            $this->_response["message"] = "class deleted Successfully.";
            $this->set_response($this->_response);
        }
    }
}
