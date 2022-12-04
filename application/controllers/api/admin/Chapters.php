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
class Chapters extends REST_Controller
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
        $this->load->model("admin_model/chapter_model");
        $this->load->model("admin_model/master_model");
        $this->load->library('MY_Form_validation');
    }

    public function index_get()
    {
        $this->set_response($this->_response);
    }

    public function test_get()
    {
        echo 'Get Rest Controller of Subject';
    }

    /**
     * CREATE COURSE
     */
    public function add_chapter_post()
    {
        $this->_response["service_name"] = "admin/add_Subject";
        $user_id = $this->rest->user_id;

        $this->form_validation->set_rules('chapter_name', 'Chapter Name', 'trim|required|callback__check_unique_chapter');
        $this->form_validation->set_rules('chapter_summary', 'Chapter summary', 'trim');
        $this->form_validation->set_rules('course_id', 'Course Id', 'trim|required|callback__check_course_exist');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $name = safe_array_key($this->_data, "chapter_name", "");
            $summary = safe_array_key($this->_data, "chapter_summary", "");
            $course_guid = safe_array_key($this->_data, "course_id", "");
            $course = $this->app->get_row('courses', 'course_id', ['course_guid' => $course_guid]);
            $chapter_name = strtolower($name);
            $course_id = safe_array_key($course, "course_id", "");
            $status = safe_array_key($this->_data, "status", "");
            $chapter_id = $this->chapter_model->create_chapter($chapter_name,$summary, $course_id, $user_id, $status);
            $this->_response["message"] = 'chapter created successfully';
            $this->set_response($this->_response);
        }
    }
    /**
     * EDIT COURSE
     */
    public function edit_chapter_post()
    {
        $this->_response["service_name"] = "admin/edit_subject";
        $user_id = $this->rest->user_id;
        $this->form_validation->set_rules('chapter_id', 'Chapter Id', 'trim|required|callback__check_chapter_exist');
        $this->form_validation->set_rules('course_id', 'Course Id', 'trim|required|callback__check_course_exist');
        $this->form_validation->set_rules('chapter_name', 'Chapter Name', 'trim|required|callback__check_unique_chapter');
        $this->form_validation->set_rules('chapter_summary', 'Chapter summary', 'trim');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $chapter_guid = safe_array_key($this->_data, "chapter_id", "");
            $chapter = $this->app->get_row('chapters', 'chapter_id', ['chapter_guid' => $chapter_guid]);
            $chapter_id = safe_array_key($chapter, "chapter_id", "");

            $name = safe_array_key($this->_data, "chapter_name", "");
            $summary = safe_array_key($this->_data, "chapter_summary", "");
            $course_guid = safe_array_key($this->_data, "course_id", "");
            $course = $this->app->get_row('courses', 'course_id', ['course_guid' => $course_guid]);
            $chapter_name = strtolower($name);
            $course_id = safe_array_key($course, "course_id", "");
            $status = safe_array_key($this->_data, "status", "");
            $this->chapter_model->edit_chapter($chapter_id, $course_id, $chapter_name,$summary, $user_id, $status);
            $this->_response['message'] = "Chapter Updated successfully.";
            $this->set_response($this->_response);
        }
    }
    /**
     * LIST COURSE
     */
    public function chapter_list_post()
    {
        $this->_response["service_name"] = "admin/subject_list";
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
            $column_name = safe_array_key($sort_by, "column_name", 'chapter_name');
            $order_by = safe_array_key($sort_by, "order_by", 'acs');
            $this->_response["data"] = $this->chapter_model->list($user_id, $column_name, $order_by, $user_type, $keyword, $limit, $offset);
            $this->_response["counts"] = $this->chapter_model->list($user_id, $column_name, $order_by, $user_type, $keyword, 0, 0);
            $this->set_response($this->_response);
        }
    }

     /**
     * LIST OF SUBJECTS IN COURSE
     */
    public function chapter_list_by_course_id_post()
    {
        $this->_response["service_name"] = "admin/chapter_list_by_course_id";
        $this->form_validation->set_rules('keyword', 'keyword', 'trim');
        $this->form_validation->set_rules('course_id', 'Course Id', 'trim|required|callback__check_course_exist');
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
            $column_name = safe_array_key($sort_by, "column_name", 'chapter_name');
            $order_by = safe_array_key($sort_by, "order_by", 'acs');

            $course_guid = safe_array_key($this->_data, "course_id", "");
            $course = $this->app->get_row('courses', 'course_id', ['course_guid' => $course_guid]);
            $course_id = safe_array_key($course, "course_id", "");

            // $subject_guid = safe_array_key($this->_data, "subject_id", "");
            // $subject = $this->app->get_row('subjects', 'subject_id', ['subject_guid' => $subject_guid]);
            // $subject_id = safe_array_key($subject, "subject_id", "");

            $this->_response["data"] = $this->chapter_model->list_by_course_id($user_id, $column_name, $order_by, $user_type,$course_id, $keyword, $limit, $offset);
            $this->_response["counts"] = $this->chapter_model->list_by_course_id($user_id, $column_name, $order_by, $user_type,$course_id, $keyword, 0, 0);
            $this->set_response($this->_response);
        }
    }

    /**
     * GET Course DETAILS BY ID API
     */
    public function get_details_by_id_post()
    {
        $this->_response["service_name"] = "subject/get_details_by_id";
        $this->form_validation->set_rules('chapter_id', 'Chapter Id', 'trim|required|callback__check_chapter_exist');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $chapter_guid = safe_array_key($this->_data, "chapter_id", "");
            $chapter = $this->app->get_row('chapters', 'chapter_id', ['chapter_guid' => $chapter_guid]);
            $chapter_id = safe_array_key($chapter, "chapter_id", "");
            $data = $this->chapter_model->get_details_by_id($chapter_id);
            $this->_response["data"] = $data;
            $this->_response["message"] = "chapter details";
            $this->set_response($this->_response);
        }
    }



    public function _check_unique_chapter($str)
    {
        $course_guid = safe_array_key($this->_data, "course_id", "");
        $course = $this->app->get_row('courses', 'course_id', ['course_guid' => $course_guid]);
        $course_id = safe_array_key($course, "course_id", "");

        // $subject_guid = safe_array_key($this->_data, "subject_id", "");
        // $subject = $this->app->get_row('subjects', 'subject_id', ['subject_guid' => $subject_guid]);
        // $subject_id = safe_array_key($subject, "subject_id", "");

        $chapter_guid = safe_array_key($this->_data, "chapter_id", "");

        if (!empty($chapter_guid)) {
            $rows = $this->app->get_rows('chapters', 'chapter_guid', [
                'chapter_name' => strtolower($str),
                "course_id " => $course_id,
                "chapter_guid !=" => $chapter_guid
            ]);
        } else if (!empty($course_guid)) {
            $rows = $this->app->get_rows('chapters', 'chapter_guid', [
                'chapter_name' => strtolower($str),
                "course_id " => $course_id,
            ]);
        } else {
            $rows = $this->app->get_rows('chapters', 'chapter_guid', [
                'chapter_name' => strtolower($str),
                "course_id " => $course_id,
            ]);
        }
        if (count($rows) > 0) {
            $this->form_validation->set_message('_check_unique_chapter', 'chapter Name already in use.');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    public function _check_course_exist($course_guid)
    {
        if (!empty($course_guid)) {
            $course = $this->app->get_row('courses', 'course_id', ['course_guid' => $course_guid]);

            if (empty($course)) {
                $this->form_validation->set_message('_check_course_exist', 'Not valid Course ID.');
                return FALSE;
            }
        }
        return TRUE;
    }
    public function _check_chapter_exist($chapter_guid)
    {
        if (!empty($chapter_guid)) {
            $chapter = $this->app->get_row('chapters', 'chapter_id', ['chapter_guid' => $chapter_guid]);

            if (empty($chapter)) {
                $this->form_validation->set_message('_check_chapter_exist', 'Not valid Chapter ID.');
                return FALSE;
            }
        }
        return TRUE;
    }
}
