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
class Quizs extends REST_Controller
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
        $this->load->model("admin_model/quiz_model");
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
    public function add_quiz_post()
    {
        $this->_response["service_name"] = "admin/add_quiz";
        $user_id = $this->rest->user_id;

        $this->form_validation->set_rules('quiz_name', 'Quiz Name', 'trim|required|callback__check_unique_quiz');
        $this->form_validation->set_rules('quiz_summary', 'Quiz summary', 'trim');
        $this->form_validation->set_rules('quiz_time', 'Quiz Time', 'trim');
        $this->form_validation->set_rules('chapter_id', 'Chapter Id', 'trim|required|callback__check_chapter_exist');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $name = safe_array_key($this->_data, "quiz_name", "");
            $summary = safe_array_key($this->_data, "quiz_summary", "");
            $quiz_time = safe_array_key($this->_data, "quiz_time", "");

            $chapter_guid = safe_array_key($this->_data, "chapter_id", "");
            $chapter = $this->app->get_row('chapters', 'chapter_id', ['chapter_guid' => $chapter_guid]);
            $quiz_name = strtolower($name);
            $chapter_id = safe_array_key($chapter, "chapter_id", "");

            $status = safe_array_key($this->_data, "status", "");
            $quiz_id = $this->quiz_model->create_quiz($quiz_name, $summary, $quiz_time, $chapter_id, $user_id, $status);
            $this->_response["message"] = 'Quiz created successfully';
            $this->set_response($this->_response);
        }
    }
    /**
     * EDIT QUIZ
     */
    public function edit_quiz_post()
    {
        $this->_response["service_name"] = "admin/edit_quiz";
        $user_id = $this->rest->user_id;
        $this->form_validation->set_rules('quiz_id', 'Quiz Id', 'trim|required|callback__check_quiz_exist');
        $this->form_validation->set_rules('chapter_id', 'Chapter Id', 'trim|required|callback__check_chapter_exist');
        $this->form_validation->set_rules('quiz_name', 'Quiz Name', 'trim|required|callback__check_unique_quiz');
        $this->form_validation->set_rules('quiz_summary', 'Quiz summary', 'trim');
        $this->form_validation->set_rules('quiz_time', 'Quiz time', 'trim');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $quiz_guid = safe_array_key($this->_data, "quiz_id", "");
            $quiz = $this->app->get_row('quizs', 'quiz_id', ['quiz_guid' => $quiz_guid]);
            $quiz_id = safe_array_key($quiz, "quiz_id", "");

            $name = safe_array_key($this->_data, "quiz_name", "");
            $summary = safe_array_key($this->_data, "quiz_summary", "");
            $quiz_time = safe_array_key($this->_data, "quiz_time", "");

            $chapter_guid = safe_array_key($this->_data, "chapter_id", "");
            $chapter = $this->app->get_row('chapters', 'chapter_id', ['chapter_guid' => $chapter_guid]);
            $quiz_name = strtolower($name);
            $chapter_id = safe_array_key($chapter, "chapter_id", "");

            $status = safe_array_key($this->_data, "status", "");

            $this->quiz_model->edit_quiz($quiz_id, $chapter_id, $quiz_name, $summary, $quiz_time, $user_id, $status);
            $this->_response['message'] = "Quiz Updated successfully.";
            $this->set_response($this->_response);
        }
    }
    /**
     * LIST QUIZ
     */
    public function quiz_list_post()
    {
        $this->_response["service_name"] = "admin/quiz_list";
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
            $column_name = safe_array_key($sort_by, "column_name", 'quiz_name');
            $order_by = safe_array_key($sort_by, "order_by", 'acs');
            $this->_response["data"] = $this->quiz_model->list($user_id, $column_name, $order_by, $user_type,$keyword, $limit, $offset);
            $this->_response["counts"] = $this->quiz_model->list($user_id,$column_name, $order_by, $user_type,$keyword, 0, 0);
            $this->set_response($this->_response);
        }
    }

    /**
     * LIST OF LESSONS IN CHAPTER
     */
    public function quiz_list_by_chapter_id_post()
    {
        $this->_response["service_name"] = "admin/quiz_list_by_chapter_id";
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
            $column_name = safe_array_key($sort_by, "column_name", 'quiz_name');
            $order_by = safe_array_key($sort_by, "order_by", 'acs');

            $chapter_guid = safe_array_key($this->_data, "chapter_id", "");
            $chapter = $this->app->get_row('chapters', 'chapter_id', ['chapter_guid' => $chapter_guid]);
            $chapter_id = safe_array_key($chapter, "chapter_id", "");
            $this->_response["data"] = $this->quiz_model->list_by_chapter_id($user_id, $column_name, $order_by, $user_type, $chapter_id,$keyword, $limit, $offset, );
            $this->_response["counts"] = $this->quiz_model->list_by_chapter_id($user_id,$column_name, $order_by, $user_type, $chapter_id,$keyword, 0, 0);
            $this->set_response($this->_response);
        }
    }

    /**
     * GET Course DETAILS BY ID API
     */
    public function get_details_by_id_post()
    {
        $this->_response["service_name"] = "lessons/get_details_by_id";
        $this->form_validation->set_rules('quiz_id', 'Quiz Id', 'trim|required|callback__check_quiz_exist');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $quiz_guid = safe_array_key($this->_data, "quiz_id", "");
            $quiz = $this->app->get_row('quizs', 'quiz_id', ['quiz_guid' => $quiz_guid]);
            $quiz_id = safe_array_key($quiz, "quiz_id", "");

            $data = $this->quiz_model->get_details_by_id($quiz_id);
            $this->_response["data"] = $data;
            $this->_response["message"] = "quiz details";
            $this->set_response($this->_response);
        }
    }


    // ================================== question answer controller start====================================================


    /**
     * CREATE QUIZ QUESTION 
     */
    public function add_quiz_question_post()
    {
        $this->_response["service_name"] = "admin/quizs/add_quiz_question";
        $user_id = $this->rest->user_id;

        $this->form_validation->set_rules('question_type', 'Lesson Name', 'trim|required');
        $this->form_validation->set_rules('question_title', 'Lesson Name', 'trim|required');
        $this->form_validation->set_rules('question_summary', 'Question summary', 'trim');
        $this->form_validation->set_rules('marks', 'Marks', 'trim|required');
        $this->form_validation->set_rules('quiz_id', 'Quiz Id', 'trim|required|callback__check_quiz_exist');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $type = safe_array_key($this->_data, "question_type", "");
            $title = safe_array_key($this->_data, "question_title", "");
            $summary = safe_array_key($this->_data, "question_summary", "");
            $marks = safe_array_key($this->_data, "marks", "");

            $quiz_guid = safe_array_key($this->_data, "quiz_id", "");
            $quiz = $this->app->get_row('quizs', 'quiz_id', ['quiz_guid' => $quiz_guid]);
            $quiz_id = safe_array_key($quiz, "quiz_id", "");
            $status = safe_array_key($this->_data, "status", "");
            $question_id = $this->quiz_model->create_quiz_question($quiz_id, $type, $title, $summary, $marks, $user_id, $status);
            $this->_response["message"] = 'quiz question added successfully';
            $this->set_response($this->_response);
        }
    }


    /**
     * EDIT QUIZ QUESTION
     */
    public function edit_quiz_question_post()
    {
        $this->_response["service_name"] = "admin/quizs/edit_quiz_question";
        $user_id = $this->rest->user_id;
        $this->form_validation->set_rules('qq_id', 'Quiz Question Id', 'trim|required|callback__check_quiz_question_exist');

        $this->form_validation->set_rules('question_type', 'Lesson Name', 'trim|required');
        $this->form_validation->set_rules('question_title', 'Lesson Name', 'trim|required');
        $this->form_validation->set_rules('question_summary', 'Question summary', 'trim');
        $this->form_validation->set_rules('marks', 'Marks', 'trim|required');
        $this->form_validation->set_rules('quiz_id', 'Quiz Id', 'trim|required|callback__check_quiz_exist');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $qq_guid = safe_array_key($this->_data, "qq_id", "");
            $qquestion = $this->app->get_row('quiz_questions', 'qq_id', ['qq_guid' => $qq_guid]);
            $qq_id = safe_array_key($qquestion, "qq_id", "");

            $type = safe_array_key($this->_data, "question_type", "");
            $title = safe_array_key($this->_data, "question_title", "");
            $summary = safe_array_key($this->_data, "question_summary", "");
            $marks = safe_array_key($this->_data, "marks", "");

            $quiz_guid = safe_array_key($this->_data, "quiz_id", "");
            $quiz = $this->app->get_row('quizs', 'quiz_id', ['quiz_guid' => $quiz_guid]);
            $quiz_id = safe_array_key($quiz, "quiz_id", "");
            $status = safe_array_key($this->_data, "status", "");

            $this->quiz_model->edit_quiz_question($qq_id, $type, $title, $summary, $marks, $quiz_id, $user_id, $status);
            $this->_response['message'] = "Quiz Question Updated successfully.";
            $this->set_response($this->_response);
        }
    }

    /**
     * QUIZ QUESTION  LIST
     */
    public function quiz_question_list_post()
    {
        $this->_response["service_name"] = "admin/quizs/quiz_question_list";
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
            $this->_response["data"] = $this->quiz_model->qq_list($user_id, $column_name, $order_by, $user_type,$keyword, $limit, $offset);
            $this->_response["counts"] = $this->quiz_model->qq_list($user_id,$column_name, $order_by, $user_type, $keyword, 0, 0);
            $this->set_response($this->_response);
        }
    }

    /**
     * LIST OF QUIZ QUESTION  IN  QUIZ 
     */
    public function quiz_question_list_by_quiz_id_post()
    {
        $this->_response["service_name"] = "admin/quizs/quiz_question_list_by_quiz_id";
        $this->form_validation->set_rules('keyword', 'keyword', 'trim');
        $this->form_validation->set_rules('quiz_id', 'Quiz Id', 'trim|required|callback__check_quiz_exist');
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

            $quiz_guid = safe_array_key($this->_data, "quiz_id", "");
            $quiz = $this->app->get_row('quizs', 'quiz_id', ['quiz_guid' => $quiz_guid]);
            $quiz_id = safe_array_key($quiz, "quiz_id", "");

            $this->_response["data"] = $this->quiz_model->qq_list($user_id, $column_name, $order_by, $user_type, $quiz_id,$keyword, $limit, $offset);
            $this->_response["counts"] = $this->quiz_model->qq_list($user_id, $column_name, $order_by, $user_type, $quiz_id,$keyword, 0, 0,);
            $this->set_response($this->_response);
        }
    }

    /**
     * GET QUIZ QUESTION  DETAILS BY ID API
     */
    public function get_qq_details_by_id_post()
    {
        $this->_response["service_name"] = "lessons/quizs/get_qq_details_by_id";
        $this->form_validation->set_rules('qq_id', 'Quiz Question Id', 'trim|required|callback__check_quiz_question_exist');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $qq_guid = safe_array_key($this->_data, "qq_id", "");
            $qquestion = $this->app->get_row('quiz_questions', 'qq_id', ['qq_guid' => $qq_guid]);
            $qq_id = safe_array_key($qquestion, "qq_id", "");
            $data = $this->quiz_model->get_qquestion_details_by_id($qq_id);
            $this->_response["data"] = $data;
            $this->_response["message"] = "quiz question  details";
            $this->set_response($this->_response);
        }
    }


    // ======================================================================= quiz options controller started ======================================================
    /**
     * CREATE QUIZ QUESTION 
     */
    public function add_quiz_question_option_post()
    {
        $this->_response["service_name"] = "admin/quizs/add_quiz_question";
        $user_id = $this->rest->user_id;
        $this->form_validation->set_rules('qq_id', 'Quiz Question Id', 'trim|required|callback__check_quiz_question_exist');
        $this->form_validation->set_rules('option_title', 'Lesson Name', 'trim|required');
        $this->form_validation->set_rules('option_summary', 'Question summary', 'trim');

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $title = safe_array_key($this->_data, "option_title", "");
            $summary = safe_array_key($this->_data, "option_summary", "");
            $qq_guid = safe_array_key($this->_data, "qq_id", "");
            $qquestion = $this->app->get_row('quiz_questions', 'qq_id', ['qq_guid' => $qq_guid]);
            $qq_id = safe_array_key($qquestion, "qq_id", "");

            $option_id = $this->quiz_model->create_quiz_question_option($qq_id, $title, $summary, $user_id);
            $this->_response["message"] = 'quiz question option added successfully';
            $this->set_response($this->_response);
        }
    }

    /**
     * EDIT QUIZ QUESTION OPTIONS
     */
    public function edit_quiz_question_option_post()
    {
        $this->_response["service_name"] = "admin/quizs/edit_quiz_question_option";
        $user_id = $this->rest->user_id;
        $this->form_validation->set_rules('option_id', 'Quiz Question option Id', 'trim|required|callback__check_quiz_question_option_exist');

        $this->form_validation->set_rules('qq_id', 'Quiz Question Id', 'trim|required|callback__check_quiz_question_exist');
        $this->form_validation->set_rules('option_title', 'Option Title', 'trim|required');
        $this->form_validation->set_rules('option_summary', 'Option summary', 'trim');

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {

            $option_guid = safe_array_key($this->_data, "option_id", "");
            $q_option = $this->app->get_row('quiz_options', 'option_id', ['option_guid' => $option_guid]);
            $option_id = safe_array_key($q_option, "option_id", "");
            $title = safe_array_key($this->_data, "option_title", "");
            $summary = safe_array_key($this->_data, "option_summary", "");

            $qq_guid = safe_array_key($this->_data, "qq_id", "");
            $qquestion = $this->app->get_row('quiz_questions', 'qq_id', ['qq_guid' => $qq_guid]);
            $qq_id = safe_array_key($qquestion, "qq_id", "");

            $this->quiz_model->edit_quiz_question_option($option_id, $title, $summary, $qq_id, $user_id);
            $this->_response['message'] = "Quiz Question option Updated successfully";
            $this->set_response($this->_response);
        }
    }

    /**
     * LIST OF QUIZ QUESTION  IN  QUIZ 
     */
    public function quiz_question_options_list_post()
    {
        $this->_response["service_name"] = "admin/quizs/quiz_question_options_list";
        $this->form_validation->set_rules('keyword', 'keyword', 'trim');
        $this->form_validation->set_rules('qq_id', 'Quiz Question Id', 'trim|callback__check_quiz_question_exist');
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

            $qq_guid = safe_array_key($this->_data, "qq_id", "");
            $qquestion = $this->app->get_row('quiz_questions', 'qq_id', ['qq_guid' => $qq_guid]);
            $qq_id = safe_array_key($qquestion, "qq_id", "");

            $this->_response["data"] = $this->quiz_model->options_list($user_id, $keyword, $limit, $offset, $column_name, $order_by, $user_type, $qq_id);
            $this->_response["counts"] = $this->quiz_model->options_list($user_id, $keyword, 0, 0, $column_name, $order_by, $user_type, $qq_id);
            $this->set_response($this->_response);
        }
    }



    /**
     * GET QUIZ QUESTION  DETAILS BY ID API
     */
    public function get_option_details_by_id_post()
    {
        $this->_response["service_name"] = "lessons/quizs/get_option_details_by_id";
        $this->form_validation->set_rules('option_id', 'Quiz Question option Id', 'trim|required|callback__check_quiz_question_option_exist');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $option_guid = safe_array_key($this->_data, "option_id", "");
            $q_option = $this->app->get_row('quiz_options', 'option_id', ['option_guid' => $option_guid]);
            $option_id = safe_array_key($q_option, "option_id", "");

            $data = $this->quiz_model->get_options_details_by_id($option_id);
            $this->_response["data"] = $data;
            $this->_response["message"] = "quiz question  details";
            $this->set_response($this->_response);
        }
    }

    // ======================================================================= quiz options controller ended ======================================================
    // ======================================================================= quiz question answer controller started ======================================================
    /**
     * CREATE QUIZ QUESTION 
     */
    public function add_quiz_question_solution_post()
    {
        $this->_response["service_name"] = "admin/quizs/add_quiz_question_answer";
        $user_id = $this->rest->user_id;
        $this->form_validation->set_rules('qq_id', 'Quiz Question Id', 'trim|required|callback__check_quiz_question_exist|callback__check_quiz_question_solution_not_exist');

        if (empty(safe_array_key($this->_data, "option_id", ""))) {
            $this->form_validation->set_rules('solution_text', 'Solution Text', 'trim|required');
        } else {
            $this->form_validation->set_rules('option_id', 'Quiz Question option Id', 'trim|required|callback__check_quiz_question_option_exist|callback__check_quiz_question_option_exist_in_question');
        }
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {
            $solution_text = safe_array_key($this->_data, "solution_text", "");
            $qq_guid = safe_array_key($this->_data, "qq_id", "");
            $qquestion = $this->app->get_row('quiz_questions', 'qq_id', ['qq_guid' => $qq_guid]);
            $qq_id = safe_array_key($qquestion, "qq_id", "");


            $option_guid = safe_array_key($this->_data, "option_id", "");
            $q_option = $this->app->get_row('quiz_options', 'option_id', ['option_guid' => $option_guid]);
            $option_id = safe_array_key($q_option, "option_id", "");

            $qans_id = $this->quiz_model->create_quiz_question_solution($qq_id, $solution_text, $option_id, $user_id);
            $this->_response["message"] = 'quiz question solution added successfully';
            $this->set_response($this->_response);
        }
    }

        /**
     * EDIT QUIZ QUESTION OPTIONS
     */
    public function edit_quiz_question_answer_post()
    {
        $this->_response["service_name"] = "admin/quizs/edit_quiz_question_answer";
        $user_id = $this->rest->user_id;

        $this->form_validation->set_rules('qans_id', 'Question Answer Id', 'trim|required|callback__check_quiz_question_answer_exist');

        $this->form_validation->set_rules('qq_id', 'Quiz Question Id', 'trim|required|callback__check_quiz_question_exist|callback__check_quiz_question_solution_not_exist');
        if (empty(safe_array_key($this->_data, "option_id", ""))) {
            $this->form_validation->set_rules('solution_text', 'Solution Text', 'trim|required');
        } else {
            $this->form_validation->set_rules('option_id', 'Quiz Question option Id', 'trim|required|callback__check_quiz_question_option_exist|callback__check_quiz_question_option_exist_in_question');
        }

        

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->_response["message"] = current($errors);
            $this->_response["errors"] = $errors;
            $this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
        } else {

            $qans_guid = safe_array_key($this->_data, "qans_id", "");
            $q_option = $this->app->get_row('quiz_answers', 'qans_id', ['qans_guid' => $qans_guid]);
            $qans_id = safe_array_key($q_option, "qans_id", "");

            $solution_text = safe_array_key($this->_data, "solution_text", "");
            $qq_guid = safe_array_key($this->_data, "qq_id", "");
            $qquestion = $this->app->get_row('quiz_questions', 'qq_id', ['qq_guid' => $qq_guid]);
            $qq_id = safe_array_key($qquestion, "qq_id", "");


            $option_guid = safe_array_key($this->_data, "option_id", "");
            $q_option = $this->app->get_row('quiz_options', 'option_id', ['option_guid' => $option_guid]);
            $option_id = safe_array_key($q_option, "option_id", "");

            $this->quiz_model->edit_quiz_question_solution($qans_id, $solution_text, $qq_id, $option_id, $user_id);
            $this->_response['message'] = "Quiz Question solution Updated successfully";
            $this->set_response($this->_response);
        }
    }


    // ======================================================================= quiz question answer controller ended ======================================================



















    public function _check_unique_quiz($str)
    {
        $chapter_guid = safe_array_key($this->_data, "chapter_id", "");
        $chapter = $this->app->get_row('chapters', 'chapter_id', ['chapter_guid' => $chapter_guid]);
        $chapter_id = safe_array_key($chapter, "chapter_id", "");

        $quiz_guid = safe_array_key($this->_data, "quiz_id", "");

        if (!empty($quiz_guid)) {
            $rows = $this->app->get_rows('quizs', 'quiz_guid', [
                'quiz_name' => strtolower($str),
                "chapter_id " => $chapter_id,
                "quiz_guid !=" => $quiz_guid
            ]);
        } else if (!empty($chapter_guid)) {
            $rows = $this->app->get_rows('quizs', 'quiz_guid', [
                'quiz_name' => strtolower($str),
                "chapter_id " => $chapter_id,
            ]);
        } else {
            $rows = $this->app->get_rows('quizs', 'quiz_guid', [
                'quiz_name' => strtolower($str),
                "chapter_id " => $chapter_id,
            ]);
        }
        if (count($rows) > 0) {
            $this->form_validation->set_message('_check_unique_quiz', 'Quiz Name already in use.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function _check_quiz_question_option_exist($option_guid)
    {
        if (!empty($option_guid)) {
            $q_option = $this->app->get_row('quiz_options', 'option_id', ['option_guid' => $option_guid]);

            if (empty($q_option)) {
                $this->form_validation->set_message('_check_quiz_question_option_exist', 'Not Valid Quiz Question Option ID.');
                return FALSE;
            }
        }
        return TRUE;
    }
    public function _check_quiz_question_exist($qq_guid)
    {
        if (!empty($qq_guid)) {
            $qquestion = $this->app->get_row('quiz_questions', 'qq_id', ['qq_guid' => $qq_guid]);

            if (empty($qquestion)) {
                $this->form_validation->set_message('_check_quiz_question_exist', 'Not Valid Quiz Question ID.');
                return FALSE;
            }
        }
        return TRUE;
    }
    public function _check_quiz_question_answer_exist($qans_guid)
    {
        if (!empty($qans_guid)) {
            $qanswer = $this->app->get_row('quiz_answers', 'qans_id', ['qans_guid' => $qans_guid]);

            if (empty($qanswer)) {
                $this->form_validation->set_message('_check_quiz_question_answer_exist', 'Not Valid Quiz Question Solution ID.');
                return FALSE;
            }
        }
        return TRUE;
    }
    public function _check_quiz_question_solution_not_exist($question_id)
    {
        $qans_guid = safe_array_key($this->_data, "qans_id", "");

        $qquestion = $this->app->get_row('quiz_questions', 'qq_id', ['qq_guid' => $question_id]);
        $qq_id = safe_array_key($qquestion, "qq_id", "");
        $rows = $this->app->get_rows('quiz_answers', 'qans_guid', [

            "qq_id " => $qq_id,
        ]);
        if (count($rows) > 0 && empty($qans_guid)) {
            $this->form_validation->set_message('_check_quiz_question_solution_not_exist', 'Question solution already exist.');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    public function _check_quiz_question_option_exist_in_question($option_id)
    {
        $qq_guid = safe_array_key($this->_data, "qq_id", "");
        $qquestion = $this->app->get_row('quiz_questions', 'qq_id', ['qq_guid' => $qq_guid]);
        $qq_id = safe_array_key($qquestion, "qq_id", "");

        $q_option = $this->app->get_row('quiz_options', 'option_id', ['option_guid' => $option_id]);
        $option_id = safe_array_key($q_option, "option_id", "");

        if (!empty($option_id)) {
            $qoption = $this->app->get_row('quiz_options', 'option_guid', ["question_id " => $qq_id, "option_id " => $option_id]);

            if (empty($qoption)) {
                $this->form_validation->set_message('_check_quiz_question_option_exist_in_question', 'Not Valid Option Id for this Question.');
                return FALSE;
            }
        }
        return TRUE;
    }



    public function _check_quiz_exist($quiz_guid)
    {
        if (!empty($quiz_guid)) {
            $quiz = $this->app->get_row('quizs', 'quiz_id', ['quiz_guid' => $quiz_guid]);

            if (empty($quiz)) {
                $this->form_validation->set_message('_check_quiz_exist', 'Not valid quiz ID.');
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
