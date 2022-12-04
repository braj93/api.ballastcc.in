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
class Lessons extends REST_Controller
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
        $this->load->model("admin_model/lesson_model");
        $this->load->model("admin_model/master_model");
        $this->load->library('MY_Form_validation');
    }

    public function index_get()
    {
        $this->set_response($this->_response);
    }

    public function test_get()
    {
        echo 'Get Rest Controller of Lesson';
    }

    /**
     * CREATE COURSE
     */
    public function add_lesson_post()
    {
        $this->_response["service_name"] = "admin/add_lesson";
        $user_id = $this->rest->user_id;

        $this->form_validation->set_rules('lesson_name', 'Lesson Name', 'trim|required|callback__check_unique_lesson');
        $this->form_validation->set_rules('lesson_summary', 'Lesson summary', 'trim');
        $this->form_validation->set_rules('chapter_id', 'Chapter Id', 'trim|required|callback__check_chapter_exist');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $name = safe_array_key($this->_data, "lesson_name", "");
            $summary = safe_array_key($this->_data, "lesson_summary", "");

            $chapter_guid = safe_array_key($this->_data, "chapter_id", "");
            $chapter = $this->app->get_row('chapters', 'chapter_id', ['chapter_guid' => $chapter_guid]);
            $lesson_name = strtolower($name);
            $chapter_id = safe_array_key($chapter, "chapter_id", "");

            $status = safe_array_key($this->_data, "status", "");
            $lesson_id = $this->lesson_model->create_lesson($lesson_name, $summary, $chapter_id, $user_id, $status);
            $this->_response["message"] = 'Lesson created successfully';
            $this->set_response($this->_response);
        }
    }
    /**
     * EDIT COURSE
     */
    public function edit_lesson_post()
    {
        $this->_response["service_name"] = "admin/edit_lesson";
        $user_id = $this->rest->user_id;
        $this->form_validation->set_rules('lesson_id', 'Lesson Id', 'trim|required|callback__check_lesson_exist');
        $this->form_validation->set_rules('chapter_id', 'Chapter Id', 'trim|required|callback__check_chapter_exist');
        $this->form_validation->set_rules('lesson_name', 'Lesson Name', 'trim|required|callback__check_unique_lesson');
        $this->form_validation->set_rules('lesson_summary', 'Lesson summary', 'trim');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $lesson_guid = safe_array_key($this->_data, "lesson_id", "");
            $lesson = $this->app->get_row('lessons', 'lesson_id', ['lesson_guid' => $lesson_guid]);
            $lesson_id = safe_array_key($lesson, "lesson_id", "");
            $name = safe_array_key($this->_data, "lesson_name", "");
            $summary = safe_array_key($this->_data, "lesson_summary", "");

            $chapter_guid = safe_array_key($this->_data, "chapter_id", "");
            $chapter = $this->app->get_row('chapters', 'chapter_id', ['chapter_guid' => $chapter_guid]);
            $lesson_name = strtolower($name);
            $chapter_id = safe_array_key($chapter, "chapter_id", "");

            $status = safe_array_key($this->_data, "status", "");

            $this->lesson_model->edit_lesson($lesson_id, $chapter_id, $lesson_name, $summary, $user_id, $status);
            $this->_response['message'] = "Lesson Updated successfully.";
            $this->set_response($this->_response);
        }
    }
    /**
     * LIST COURSE
     */
    public function lesson_list_post()
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
            $column_name = safe_array_key($sort_by, "column_name", 'lesson_name');
            $order_by = safe_array_key($sort_by, "order_by", 'acs');
            $this->_response["data"] = $this->lesson_model->list($user_id, $column_name, $order_by, $user_type, $keyword, $limit, $offset);
            $this->_response["counts"] = $this->lesson_model->list($user_id, $column_name, $order_by, $user_type, $keyword, 0, 0);
            $this->set_response($this->_response);
        }
    }

    /**
     * LIST OF LESSONS IN CHAPTER
     */
    public function lesson_list_by_chapter_id_post()
    {
        $this->_response["service_name"] = "admin/chapter_list_by_course_id";
        $this->form_validation->set_rules('keyword', 'keyword', 'trim');
        $this->form_validation->set_rules('chapter_id', 'Chapter Id', 'trim|required|callback__check_chapter_exist');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $user_id = $this->rest->user_id;
            $user = $this->app->get_row('users', 'user_type', ['user_id' => $user_id]);
            $user_type = safe_array_key($user, "user_type", "");
            $keyword = safe_array_key($this->_data, "keyword", "");
            $pagination = safe_array_key($this->_data, "pagination", []);
            $limit = safe_array_key($pagination, "limit", 10);
            $offset = safe_array_key($pagination, "offset", 0);
            $sort_by = safe_array_key($this->_data, "sort_by", []);
            $column_name = safe_array_key($sort_by, "column_name", 'lesson_name');
            $order_by = safe_array_key($sort_by, "order_by", 'acs');

            $chapter_guid = safe_array_key($this->_data, "chapter_id", "");
            $chapter = $this->app->get_row('chapters', 'chapter_id', ['chapter_guid' => $chapter_guid]);
            $chapter_id = safe_array_key($chapter, "chapter_id", "");
            $this->_response["data"] = $this->lesson_model->list_by_chapter_id($user_id, $column_name, $order_by, $user_type, $chapter_id, $keyword, $limit, $offset);
            $this->_response["counts"] = $this->lesson_model->list_by_chapter_id($user_id, $column_name, $order_by, $user_type, $chapter_id, $keyword, 0, 0);
            $this->set_response($this->_response);
        }
    }

    /**
     * GET Course DETAILS BY ID API
     */
    public function get_details_by_id_post()
    {
        $this->_response["service_name"] = "lessons/get_details_by_id";
        $this->form_validation->set_rules('lesson_id', 'Lesson Id', 'trim|required|callback__check_lesson_exist');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $lesson_guid = safe_array_key($this->_data, "lesson_id", "");
            $lesson = $this->app->get_row('lessons', 'lesson_id', ['lesson_guid' => $lesson_guid]);
            $lesson_id = safe_array_key($lesson, "lesson_id", "");
            $data = $this->lesson_model->get_details_by_id($lesson_id);
            $this->_response["data"] = $data;
            $this->_response["message"] = "lesson details";
            $this->set_response($this->_response);
        }
    }


    // ================================== question answer controller start====================================================


    /**
     * CREATE QUESTION ANSWER
     */
    public function add_question_answer_post()
    {
        $this->_response["service_name"] = "admin/lessons/add_question_answer";
        $user_id = $this->rest->user_id;

        $this->form_validation->set_rules('question_title', 'Lesson Name', 'trim|required');
        $this->form_validation->set_rules('question_summary', 'Question summary', 'trim');
        $this->form_validation->set_rules('answer', 'Answer', 'trim|required');
        $this->form_validation->set_rules('lesson_id', 'Chapter Id', 'trim|required|callback__check_lesson_exist');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $title = safe_array_key($this->_data, "question_title", "");
            $summary = safe_array_key($this->_data, "question_summary", "");
            $answer = safe_array_key($this->_data, "answer", "");

            $lesson_guid = safe_array_key($this->_data, "lesson_id", "");
            $lesson = $this->app->get_row('lessons', 'lesson_id', ['lesson_guid' => $lesson_guid]);
            $lesson_id = safe_array_key($lesson, "lesson_id", "");

            $status = safe_array_key($this->_data, "status", "");
            $lesson_id = $this->lesson_model->create_question_answer($lesson_id, $title, $summary, $answer, $user_id, $status);
            $this->_response["message"] = 'question answer added successfully';
            $this->set_response($this->_response);
        }
    }


    /**
     * EDIT QUESTION ANSWER
     */
    public function edit_question_answer_post()
    {
        $this->_response["service_name"] = "admin/lessons/edit_question_answer";
        $user_id = $this->rest->user_id;
        $this->form_validation->set_rules('qa_id', 'Lesson Id', 'trim|required|callback__check_question_answer_exist');
        $this->form_validation->set_rules('lesson_id', 'Lesson Id', 'trim|required|callback__check_lesson_exist');
        $this->form_validation->set_rules('question_title', 'Lesson Name', 'trim|required');
        $this->form_validation->set_rules('question_summary', 'Question summary', 'trim');
        $this->form_validation->set_rules('answer', 'Answer', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $qa_guid = safe_array_key($this->_data, "qa_id", "");
            $qa = $this->app->get_row('questions_answers', 'qa_id', ['qa_guid' => $qa_guid]);
            $qa_id = safe_array_key($qa, "qa_id", "");

            $title = safe_array_key($this->_data, "question_title", "");
            $summary = safe_array_key($this->_data, "question_summary", "");
            $answer = safe_array_key($this->_data, "answer", "");

            $lesson_guid = safe_array_key($this->_data, "lesson_id", "");
            $lesson = $this->app->get_row('lessons', 'lesson_id', ['lesson_guid' => $lesson_guid]);
            $lesson_id = safe_array_key($lesson, "lesson_id", "");

            $status = safe_array_key($this->_data, "status", "");

            $this->lesson_model->edit_question_answer($qa_id, $lesson_id, $title, $summary, $answer, $user_id, $status);
            $this->_response['message'] = "Question answer Updated successfully.";
            $this->set_response($this->_response);
        }
    }

    /**
     * QUESTION ANSWER LIST
     */
    public function question_answer_list_post()
    {
        $this->_response["service_name"] = "admin/lessons/question_answer_list_post";
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
            $column_name = safe_array_key($sort_by, "column_name", 'created_at');
            $order_by = safe_array_key($sort_by, "order_by", 'acs');
            $this->_response["data"] = $this->lesson_model->qa_list($user_id, $column_name, $order_by, $user_type, $keyword, $limit, $offset);
            $this->_response["counts"] = $this->lesson_model->qa_list($user_id, $column_name, $order_by, $user_type, $keyword, 0, 0);
            $this->set_response($this->_response);
        }
    }

    /**
     * LIST OF QUESTION ANSWER IN LESSON 
     */
    public function qa_list_by_lesson_id_post()
    {
        $this->_response["service_name"] = "admin/lessons/qa_list_by_lesson_id";
        $this->form_validation->set_rules('keyword', 'keyword', 'trim');
        $this->form_validation->set_rules('lesson_id', 'Lesson Id', 'trim|required|callback__check_lesson_exist');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $user_id = $this->rest->user_id;
            $user = $this->app->get_row('users', 'user_type', ['user_id' => $user_id]);
            $user_type = safe_array_key($user, "user_type", "");
            $keyword = safe_array_key($this->_data, "keyword", "");
            $pagination = safe_array_key($this->_data, "pagination", []);
            $limit = safe_array_key($pagination, "limit", 10);
            $offset = safe_array_key($pagination, "offset", 0);
            $sort_by = safe_array_key($this->_data, "sort_by", []);
            $column_name = safe_array_key($sort_by, "column_name", 'created_at');
            $order_by = safe_array_key($sort_by, "order_by", 'acs');

            $lesson_guid = safe_array_key($this->_data, "lesson_id", "");
            $lesson = $this->app->get_row('lessons', 'lesson_id', ['lesson_guid' => $lesson_guid]);
            $lesson_id = safe_array_key($lesson, "lesson_id", "");

            $this->_response["data"] = $this->lesson_model->list_by_lesson_id($user_id, $column_name, $order_by, $user_type, $lesson_id, $keyword, $limit, $offset);
            $this->_response["counts"] = $this->lesson_model->list_by_lesson_id($user_id, $column_name, $order_by, $user_type, $lesson_id, $keyword, 0, 0);
            $this->set_response($this->_response);
        }
    }

       /**
     * GET QUESTION ANSWER DETAILS BY ID API
     */
    public function get_qa_details_by_id_post()
    {
        $this->_response["service_name"] = "lessons/lessons/get_qa_details_by_id";
        $this->form_validation->set_rules('qa_id', 'Question answer Id', 'trim|required|callback__check_question_answer_exist');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $qa_guid = safe_array_key($this->_data, "qa_id", "");
            $qa = $this->app->get_row('questions_answers', 'qa_id', ['qa_guid' => $qa_guid]);
            $qa_id = safe_array_key($qa, "qa_id", "");
            $data = $this->lesson_model->get_qa_details_by_id($qa_id);
            $this->_response["data"] = $data;
            $this->_response["message"] = "question answer details";
            $this->set_response($this->_response);
        }
    }





















    public function _check_unique_lesson($str)
    {
        $chapter_guid = safe_array_key($this->_data, "chapter_id", "");
        $chapter = $this->app->get_row('chapters', 'chapter_id', ['chapter_guid' => $chapter_guid]);
        $chapter_id = safe_array_key($chapter, "chapter_id", "");

        $lesson_guid = safe_array_key($this->_data, "lesson_id", "");

        if (!empty($lesson_guid)) {
            $rows = $this->app->get_rows('lessons', 'lesson_guid', [
                'lesson_name' => strtolower($str),
                "chapter_id " => $chapter_id,
                "lesson_guid !=" => $lesson_guid
            ]);
        } else if (!empty($course_guid)) {
            $rows = $this->app->get_rows('lessons', 'lesson_guid', [
                'lesson_name' => strtolower($str),
                "chapter_id " => $chapter_id,
            ]);
        } else {
            $rows = $this->app->get_rows('lessons', 'lesson_guid', [
                'lesson_name' => strtolower($str),
                "chapter_id " => $chapter_id,
            ]);
        }
        if (count($rows) > 0) {
            $this->form_validation->set_message('_check_unique_lesson', 'lesson Name already in use.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function _check_question_answer_exist($qa_guid)
    {
        if (!empty($qa_guid)) {
            $lesson = $this->app->get_row('questions_answers', 'qa_id', ['qa_guid' => $qa_guid]);

            if (empty($lesson)) {
                $this->form_validation->set_message('_check_question_answer_exist', 'Not valid Question Answer ID.');
                return FALSE;
            }
        }
        return TRUE;
    }
    public function _check_lesson_exist($lesson_guid)
    {
        if (!empty($lesson_guid)) {
            $lesson = $this->app->get_row('lessons', 'lesson_id', ['lesson_guid' => $lesson_guid]);

            if (empty($lesson)) {
                $this->form_validation->set_message('_check_lesson_exist', 'Not valid lesson ID.');
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
