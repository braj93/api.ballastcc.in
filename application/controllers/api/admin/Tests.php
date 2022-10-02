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
class Tests extends REST_Controller
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
        $this->load->model("admin_model/tests_model");
        $this->load->library('MY_Form_validation');
    }

    public function index_get()
    {
        $this->set_response($this->_response);
    }

    public function test_get()
    {
        echo 'Get Rest Controller of tests';
    }

    /**
     * CREATE COURSE
     */
    public function add_test_post()
    {
        $this->_response["service_name"] = "admin/add_test";
        $user_id = $this->rest->user_id;

        $this->form_validation->set_rules('test_name', 'Test Name', 'trim|required');
        $this->form_validation->set_rules('chapter_id', 'Chapter Id', 'trim|callback__check_chapter_exist');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $name = safe_array_key($this->_data, "test_name", "");
            $chapter_guid = safe_array_key($this->_data, "chapter_id", "");
            $chapter = $this->app->get_row('chapters', 'chapter_id', ['chapter_guid' => $chapter_guid]);
            $test_name = strtolower($name);
            $chapter_id = safe_array_key($chapter, "chapter_id", "");
            $status = safe_array_key($this->_data, "status", "");
            $test_id = $this->tests_model->create_test($test_name, $chapter_id, $user_id, $status);
            $this->_response["message"] = 'test created successfully';
            $this->set_response($this->_response);
        }
    }
    /**
     * EDIT COURSE
     */
    public function edit_test_post()
    {
        $this->_response["service_name"] = "admin/edit_test";
        $user_id = $this->rest->user_id;
        $this->form_validation->set_rules('test_id', 'Test Id', 'trim|required|callback__check_test_exist');
        $this->form_validation->set_rules('chapter_id', 'Chapter Id', 'trim|required|callback__check_chapter_exist');
        $this->form_validation->set_rules('test_name', 'Test Name', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $test_guid = safe_array_key($this->_data, "test_id", "");
            $test = $this->app->get_row('tests', 'test_id', ['test_guid' => $test_guid]);
            $test_id = safe_array_key($test, "test_id", "");
            $name = safe_array_key($this->_data, "test_name", "");
            $chapter_guid = safe_array_key($this->_data, "chapter_id", "");
            $chapter = $this->app->get_row('chapters', 'chapter_id', ['chapter_guid' => $chapter_guid]);
            $test_name = strtolower($name);
            $chapter_id = safe_array_key($chapter, "chapter_id", "");
            $status = safe_array_key($this->_data, "status", "");
            $this->tests_model->edit_test($test_id, $chapter_id, $test_name, $user_id, $status);
            $this->_response['message'] = "test Updated successfully.";
            $this->set_response($this->_response);
        }
    }
    /**
     * LIST Tests
     */
    public function test_list_post()
    {
        $this->_response["service_name"] = "admin/test_list";
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
            $column_name = safe_array_key($sort_by, "column_name", 'test_name');
            $order_by = safe_array_key($sort_by, "order_by", 'acs');
            $this->_response["data"] = $this->tests_model->Testlist($user_id, $keyword, $limit, $offset, $column_name, $order_by, $user_type);
            $this->_response["counts"] = $this->tests_model->Testlist($user_id, $keyword, 0, 0, $column_name, $order_by, $user_type);
            $this->set_response($this->_response);
        }
    }

    /**
     * LIST OF SUBJECTS IN COURSE
     */
    public function test_list_by_chapter_id_post()
    {
        $this->_response["service_name"] = "admin/test_list_by_chapter_id";
        $this->form_validation->set_rules('keyword', 'keyword', 'trim');
        $this->form_validation->set_rules('chapter_id', 'Subject Id', 'trim|required|callback__check_chapter_exist');
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
            $column_name = safe_array_key($sort_by, "column_name", 'test_name');
            $order_by = safe_array_key($sort_by, "order_by", 'acs');

            $chapter_guid = safe_array_key($this->_data, "chapter_id", "");
            $chapter = $this->app->get_row('chapters', 'chapter_id', ['chapter_guid' => $chapter_guid]);
            $chapter_id = safe_array_key($chapter, "chapter_id", "");
            $this->_response["data"] = $this->tests_model->list_by_chapter_id($user_id, $keyword, $limit, $offset, $column_name, $order_by, $user_type, $chapter_id);
            $this->_response["counts"] = $this->tests_model->list_by_chapter_id($user_id, $keyword, 0, 0, $column_name, $order_by, $user_type, $chapter_id);
            $this->set_response($this->_response);
        }
    }

    /**
     * GET Course DETAILS BY ID API
     */
    public function get_test_details_by_id_post()
    {
        $this->_response["service_name"] = "subject/get_test_details_by_id";
        $this->form_validation->set_rules('test_id', 'Test Id', 'trim|required|callback__check_test_exist');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $test_guid = safe_array_key($this->_data, "test_id", "");
            $test = $this->app->get_row('tests', 'test_id', ['test_guid' => $test_guid]);
            $test_id = safe_array_key($test, "test_id", "");
            $data = $this->tests_model->get_test_details_by_id($test_id);
            $this->_response["data"] = $data;
            $this->_response["message"] = "test details";
            $this->set_response($this->_response);
        }
    }



    // ========================================= end test controller =============================================



    /**
     * CREATE COURSE
     */
    public function add_question_post()
    {
        $this->_response["service_name"] = "admin/add_question";
        $user_id = $this->rest->user_id;
        $this->form_validation->set_rules('test_id', 'Test Id', 'trim|required|callback__check_test_exist');
        $this->form_validation->set_rules('question_type', 'Question Type', 'trim|required');
        $this->form_validation->set_rules('grading_type', 'Grading Type', 'trim|required');
        $this->form_validation->set_rules('question_marks', 'Question Marks', 'trim|required');
        $this->form_validation->set_rules('question', 'Question', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $test_guid = safe_array_key($this->_data, "test_id", "");
            $test = $this->app->get_row('tests', 'test_id', ['test_guid' => $test_guid]);
            $test_id = safe_array_key($test, "test_id", "");
            $question_type = safe_array_key($this->_data, "question_type", "");
            $grading_type = safe_array_key($this->_data, "grading_type", "");
            $question_marks = safe_array_key($this->_data, "question_marks", "");
            $question = safe_array_key($this->_data, "question", "");
            $status = safe_array_key($this->_data, "status", "");
            $test_id = $this->tests_model->create_question($test_id, $question_type, $grading_type, $question_marks, $question, $user_id, $status);
            $this->_response["message"] = 'Question created successfully';
            $this->set_response($this->_response);
        }
    }
    /**
     * EDIT COURSE
     */
    public function edit_question_post()
    {
        $this->_response["service_name"] = "admin/edit_test";
        $user_id = $this->rest->user_id;
        $this->form_validation->set_rules('question_id', 'Question Id', 'trim|required|callback__check_question_exist');
        $this->form_validation->set_rules('question_type', 'Question Type', 'trim|required');
        $this->form_validation->set_rules('grading_type', 'Grading Type', 'trim|required');
        $this->form_validation->set_rules('question_marks', 'Question Marks', 'trim|required');
        $this->form_validation->set_rules('question', 'Question', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $question_guid = safe_array_key($this->_data, "question_id", "");
            $question = $this->app->get_row('questions', 'question_id', ['question_guid' => $question_guid]);
            $question_id = safe_array_key($question, "question_id", "");

            $question_type = safe_array_key($this->_data, "question_type", "");
            $grading_type = safe_array_key($this->_data, "grading_type", "");
            $question_marks = safe_array_key($this->_data, "question_marks", "");
            $question = safe_array_key($this->_data, "question", "");
            $status = safe_array_key($this->_data, "status", "");
            $this->tests_model->edit_question($question_id, $question_type, $grading_type, $question_marks, $question, $user_id, $status);
            $this->_response['message'] = "question Updated successfully.";
            $this->set_response($this->_response);
        }
    }
    /**
     * LIST Tests
     */
    public function question_list_post()
    {
        $this->_response["service_name"] = "admin/question_list";
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
            $column_name = safe_array_key($sort_by, "column_name", 'question');
            $order_by = safe_array_key($sort_by, "order_by", 'acs');
            $this->_response["data"] = $this->tests_model->Questionlist($user_id, $keyword, $limit, $offset, $column_name, $order_by, $user_type);
            $this->_response["counts"] = $this->tests_model->Questionlist($user_id, $keyword, 0, 0, $column_name, $order_by, $user_type);
            $this->set_response($this->_response);
        }
    }

    /**
     * LIST OF SUBJECTS IN COURSE
     */
    public function questions_list_by_test_id_post()
    {
        $this->_response["service_name"] = "admin/question_list_by_test_id";
        $this->form_validation->set_rules('keyword', 'keyword', 'trim');
        $this->form_validation->set_rules('test_id', 'Test Id', 'trim|required|callback__check_test_exist');
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
            $column_name = safe_array_key($sort_by, "column_name", 'test_name');
            $order_by = safe_array_key($sort_by, "order_by", 'acs');

            $test_guid = safe_array_key($this->_data, "test_id", "");
            $test = $this->app->get_row('tests', 'test_id', ['test_guid' => $test_guid]);
            $test_id = safe_array_key($test, "test_id", "");

            $this->_response["data"] = $this->tests_model->Question_list_by_test_id($user_id, $keyword, $limit, $offset, $column_name, $order_by, $user_type, $test_id);
            $this->_response["counts"] = $this->tests_model->Question_list_by_test_id($user_id, $keyword, 0, 0, $column_name, $order_by, $user_type, $test_id);
            $this->set_response($this->_response);
        }
    }

    /**
     * GET QUESTION DETAILS BY ID API
     */
    public function get_question_details_by_id_post()
    {
        $this->_response["service_name"] = "tests/get_question_details_by_id";
        $this->form_validation->set_rules('question_id', 'question Id', 'trim|required|callback__check_question_exist');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $question_guid = safe_array_key($this->_data, "question_id", "");
            $question = $this->app->get_row('questions', 'question_id', ['question_guid' => $question_guid]);
            $question_id = safe_array_key($question, "question_id", "");
            $data = $this->tests_model->get_question_details_by_id($question_id);
            $this->_response["data"] = $data;
            $this->_response["message"] = "question details";
            $this->set_response($this->_response);
        }
    }





    public function _check_unique_chapter($str)
    {
        $subject_guid = safe_array_key($this->_data, "subject_id", "");
        $subject = $this->app->get_row('subjects', 'subject_id', ['subject_guid' => $subject_guid]);
        $subject_id = safe_array_key($subject, "subject_id", "");

        $chapter_guid = safe_array_key($this->_data, "chapter_id", "");

        if (!empty($chapter_guid)) {
            $rows = $this->app->get_rows('chapters', 'chapter_guid', [
                'chapter_name' => strtolower($str),
                "subject_id " => $subject_id,
                "chapter_guid !=" => $chapter_guid
            ]);
        } else if (!empty($subject_guid)) {
            $rows = $this->app->get_rows('chapters', 'chapter_guid', [
                'chapter_name' => strtolower($str),
                "subject_id " => $subject_id,
            ]);
        } else {
            $rows = $this->app->get_rows('chapters', 'chapter_guid', [
                'chapter_name' => strtolower($str),
                "subject_id " => $subject_id,
            ]);
        }
        if (count($rows) > 0) {
            $this->form_validation->set_message('_check_unique_chapter', 'chapter Name already in use.');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    public function _check_subject_exist($subject_guid)
    {
        if (!empty($subject_guid)) {
            $subject = $this->app->get_row('subjects', 'subject_id', ['subject_guid' => $subject_guid]);

            if (empty($subject)) {
                $this->form_validation->set_message('_check_subject_exist', 'Not valid Subject ID.');
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
    public function _check_test_exist($test_guid)
    {
        if (!empty($test_guid)) {
            $test = $this->app->get_row('tests', 'test_id', ['test_guid' => $test_guid]);

            if (empty($test)) {
                $this->form_validation->set_message('_check_test_exist', 'Not valid Test ID.');
                return FALSE;
            }
        }
        return TRUE;
    }
    public function _check_question_exist($question_guid)
    {
        if (!empty($question_guid)) {
            $question = $this->app->get_row('questions', 'question_id', ['question_guid' => $question_guid]);

            if (empty($question)) {
                $this->form_validation->set_message('_check_question_exist', 'Not valid Question ID.');
                return FALSE;
            }
        }
        return TRUE;
    }
}
