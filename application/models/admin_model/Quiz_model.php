<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Quiz_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    /**
     *
     * @param type $session_id
     * @return type
     */


    /** create_lesson
     * @param type $lesson_name, 
     * @param type $summary, 
     * @param type $chapter_id, 
     * @param type $status
     * @return type
     */
    public function create_quiz($quiz_name, $summary, $quiz_time, $chapter_id, $user_id, $status)
    {
        $this->db->insert('quizs', [
            "quiz_guid" => get_guid(),
            "quiz_name" => $quiz_name,
            "quiz_summary" => $summary,
            "quiz_time" => $quiz_time,
            "chapter_id" => $chapter_id,
            "status" => $status,
            "created_at" => DATETIME,
            "updated_at" => DATETIME,
        ]);
        $quiz_id = $this->db->insert_id();
        return $quiz_id;
    }

    /** edit_quiz
     * @param type $chapter_id, 
     * @param type $quiz_name, 
     * @param type $summary, 
     * @param type $status
     * @return type
     */
    public function edit_quiz($quiz_id, $chapter_id, $quiz_name, $summary, $quiz_time, $user_id, $status)
    {
        $data = [
            "chapter_id" => $chapter_id,
            "quiz_name" => $quiz_name,
            "quiz_summary" => $summary,
            "quiz_time" => $quiz_time,
            "status" => $status,
            "updated_at" => DATETIME,
        ];

        $this->db->update('quizs', $data, array(
            'quiz_id' => $quiz_id,
        ));
        $affected_rows_count = $this->db->affected_rows();
        return $affected_rows_count;
    }

    /*
***  
GET QUIZS list
***
    */

    public function list($user_id, $keyword = '', $limit = 0, $offset = 0, $column_name, $order_by, $user_type)
    {
        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);


            $this->db->select('IFNULL(q.quiz_guid,"") AS quiz_guid', FALSE);
            $this->db->select('IFNULL(q.quiz_name,"") AS quiz_name', FALSE);
            $this->db->select('IFNULL(q.quiz_summary,"") AS quiz_summary', FALSE);
            $this->db->select('IFNULL(q.quiz_time,"") AS quiz_time', FALSE);
            $this->db->select('IFNULL(c.chapter_name,"") AS chapter_name', FALSE);
            $this->db->select('IFNULL(q.status,"") AS status', FALSE);
            $this->db->select('IFNULL(q.created_at,"") AS created_at', FALSE);
            $this->db->select('IFNULL(q.updated_at,"") AS updated_at', FALSE);
        } else {
            $this->db->select('COUNT(q.quiz_id) as count', FALSE);
        }
        $this->db->from('quizs AS q');
        $this->db->join('chapters AS c', 'c.chapter_id = q.chapter_id', 'LEFT');
        $this->db->order_by('q.created_at', 'asc');

        // if (!empty($filterBy)) {
        // 	$this->db->like('u.status', $filterBy);
        // }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('q.quiz_name', $keyword, 'both');
            $this->db->group_end();
        }

        if (($column_name !== '') && ($order_by !== '')) {
            $this->db->order_by('q.' . $column_name, $order_by);
        }
        // if ($user_type != 'ADMIN') {
        // 	$this->db->where('c.added_by', $user_id);
        // }

        $query = $this->db->get();
        $results = $query->result_array();
        if (($limit > 0) && ($offset >= 0)) {
            if ($query->num_rows() > 0) {
                $list = [];
                foreach ($results as $key => $value) {
                    $list[$key]['quiz_guid'] = $value['quiz_guid'];
                    $list[$key]['quiz_name'] = $value['quiz_name'];
                    $list[$key]['quiz_summary'] = $value['quiz_summary'];
                    $list[$key]['quiz_time'] = $value['quiz_time'];
                    $list[$key]['chapter_name'] = $value['chapter_name'];
                    $list[$key]['status'] = $value['status'];
                    $list[$key]['created_at'] = $value['created_at'];
                    $list[$key]['updated_at'] = $value['updated_at'];
                }
                return $list;
            } else {
                return [];
            }
        } else {
            return $query->row()->count;
        }
    }
    /*
***  
GET CHAPTER list BY SUBJECT ID
***
    */

    public function list_by_chapter_id($user_id, $keyword = '', $limit = 0, $offset = 0, $column_name, $order_by, $user_type, $chapter_id)
    {
        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);


            $this->db->select('IFNULL(q.quiz_guid,"") AS quiz_guid', FALSE);
            $this->db->select('IFNULL(q.quiz_name,"") AS quiz_name', FALSE);
            $this->db->select('IFNULL(q.quiz_summary,"") AS quiz_summary', FALSE);
            $this->db->select('IFNULL(q.quiz_time,"") AS quiz_time', FALSE);
            $this->db->select('IFNULL(c.chapter_name,"") AS chapter_name', FALSE);
            $this->db->select('IFNULL(q.status,"") AS status', FALSE);
            $this->db->select('IFNULL(q.created_at,"") AS created_at', FALSE);
            $this->db->select('IFNULL(q.updated_at,"") AS updated_at', FALSE);
        } else {
            $this->db->select('COUNT(q.quiz_id) as count', FALSE);
        }
        $this->db->from('quizs AS q');
        $this->db->join('chapters AS c', 'c.chapter_id = q.chapter_id', 'LEFT');
        $this->db->where('q.chapter_id', $chapter_id);
        $this->db->order_by('q.created_at', 'desc');

        // if (!empty($filterBy)) {
        // 	$this->db->like('u.status', $filterBy);
        // }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('q.quiz_name', $keyword, 'both');
            $this->db->group_end();
        }

        if (($column_name !== '') && ($order_by !== '')) {
            $this->db->order_by('q.' . $column_name, $order_by);
        }
        // if ($user_type != 'ADMIN') {
        // 	$this->db->where('c.added_by', $user_id);
        // }

        $query = $this->db->get();
        $results = $query->result_array();
        if (($limit > 0) && ($offset >= 0)) {
            if ($query->num_rows() > 0) {
                $list = [];
                foreach ($results as $key => $value) {
                    $list[$key]['quiz_guid'] = $value['quiz_guid'];
                    $list[$key]['quiz_name'] = $value['quiz_name'];
                    $list[$key]['quiz_summary'] = $value['quiz_summary'];
                    $list[$key]['quiz_time'] = $value['quiz_time'];
                    $list[$key]['chapter_name'] = $value['chapter_name'];
                    $list[$key]['status'] = $value['status'];
                    $list[$key]['created_at'] = $value['created_at'];
                    $list[$key]['updated_at'] = $value['updated_at'];
                }
                return $list;
            } else {
                return [];
            }
        } else {
            return $query->row()->count;
        }
    }



    public function get_details_by_id($quiz_id)
    {
        $this->db->select('IFNULL(q.quiz_guid,"") AS quiz_guid', FALSE);
        $this->db->select('IFNULL(q.quiz_name,"") AS quiz_name', FALSE);
        $this->db->select('IFNULL(q.quiz_summary,"") AS quiz_summary', FALSE);
        $this->db->select('IFNULL(q.quiz_time,"") AS quiz_time', FALSE);
        $this->db->select('IFNULL(c.chapter_name,"") AS chapter_name', FALSE);
        $this->db->select('IFNULL(q.status,"") AS status', FALSE);
        $this->db->select('IFNULL(q.created_at,"") AS created_at', FALSE);
        $this->db->select('IFNULL(q.updated_at,"") AS updated_at', FALSE);


        $this->db->from('quizs AS q');
        $this->db->join('chapters AS c', 'c.chapter_id = q.chapter_id', 'LEFT');
        $this->db->where('q.quiz_id', $quiz_id);
        $this->db->order_by('q.created_at', 'desc');

        $query = $this->db->get();
        $reuslt = $query->row_array();
        // $reuslt['string'] = unique_random_string('campaign_templates', 'unique_string', [], 'alnum', 12);
        return $reuslt;
    }


    // ============================================= question answer model start =========================================================

    /** create_lesson
     * @param type $title, 
     * @param type $summary, 
     * @param type $lesson_id, 
     * @param type $status
     * @return type
     */
    public function create_quiz_question($quiz_id, $type, $title, $summary, $marks, $user_id, $status)
    {
        $this->db->insert('quiz_questions', [
            "qq_guid" => get_guid(),
            "type" => $type,
            "title" => $title,
            "summary" => $summary,
            "marks" => $marks,
            "quiz_id" => $quiz_id,
            "status" => $status,
            "created_at" => DATETIME,
            "updated_at" => DATETIME,
        ]);
        $qq_id = $this->db->insert_id();
        return $qq_id;
    }



    /** edit_quiz_question
     * @param type $qa_id, 
     * @param type $lesson_id, 
     * @param type $title, 
     * @param type $summary, 
     * @param type $answer, 
     * @param type $status
     * @return type
     */
    public function edit_quiz_question($qq_id, $type, $title, $summary, $marks, $quiz_id, $user_id, $status)
    {
        $data = [
            "type" => $type,
            "title" => $title,
            "summary" => $summary,
            "marks" => $marks,
            "quiz_id" => $quiz_id,
            "status" => $status,
            "created_at" => DATETIME,
            "updated_at" => DATETIME,
        ];

        $this->db->update('quiz_questions', $data, array(
            'qq_id' => $qq_id,
        ));
        $affected_rows_count = $this->db->affected_rows();
        return $affected_rows_count;
    }

    public function get_options($qq_id)
    {

        $this->db->select('IFNULL(qo.option_guid,"") AS option_guid', FALSE);
        $this->db->select('IFNULL(qo.option_title,"") AS option_title', FALSE);
        $this->db->select('IFNULL(qo.option_summary,"") AS option_summary', FALSE);
        $this->db->select('IFNULL(qq.title,"") AS question_title', FALSE);
        $this->db->select('IFNULL(qq.created_at,"") AS created_at', FALSE);
        $this->db->select('IFNULL(qq.updated_at,"") AS updated_at', FALSE);
        $this->db->from('quiz_options AS qo');
        $this->db->join('quiz_questions AS qq', 'qq.qq_id = qo.question_id', 'LEFT');
        $this->db->where('qo.question_id', $qq_id);
        $this->db->order_by('qo.created_at', 'desc');

        $query = $this->db->get();
        // echo $this->db->last_query();die();
        $results = $query->result_array();
        return $results;
    }

    /*
***  
GET SUBJECTS list
***
    */

    public function qq_list($user_id, $keyword = '', $limit = 0, $offset = 0, $column_name, $order_by, $user_type, $quiz_id = "")
    {
        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);
            $this->db->select('IFNULL(qq.qq_id,"") AS qq_id', FALSE);
            $this->db->select('IFNULL(qq.qq_guid,"") AS qq_guid', FALSE);
            $this->db->select('IFNULL(qq.type,"") AS type', FALSE);
            $this->db->select('IFNULL(qq.title,"") AS title', FALSE);
            $this->db->select('IFNULL(qq.summary,"") AS summary', FALSE);
            $this->db->select('IFNULL(qq.marks,"") AS marks', FALSE);
            $this->db->select('IFNULL(q.quiz_name,"") AS quiz_name', FALSE);
            $this->db->select('IFNULL(qq.status,"") AS status', FALSE);
            $this->db->select('IFNULL(qq.created_at,"") AS created_at', FALSE);
            $this->db->select('IFNULL(qq.updated_at,"") AS updated_at', FALSE);
        } else {
            $this->db->select('COUNT(qq.qq_id) as count', FALSE);
        }
        $this->db->from('quiz_questions AS qq');
        $this->db->join('quizs AS q', 'q.quiz_id = qq.quiz_id', 'LEFT');
        if (!empty($quiz_id)) {
            $this->db->where('qq.quiz_id', $quiz_id);
        }
        $this->db->order_by('qq.created_at', 'desc');

        // if (!empty($filterBy)) {
        // 	$this->db->like('u.status', $filterBy);
        // }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('qq.title', $keyword, 'both');
            $this->db->group_end();
        }

        if (($column_name !== '') && ($order_by !== '')) {
            $this->db->order_by('qq.' . $column_name, $order_by);
        }
        // if ($user_type != 'ADMIN') {
        // 	$this->db->where('c.added_by', $user_id);
        // }

        $query = $this->db->get();
        $results = $query->result_array();
        if (($limit > 0) && ($offset >= 0)) {
            if ($query->num_rows() > 0) {
                $list = [];
                foreach ($results as $key => $value) {
                    $list[$key]['qq_guid'] = $value['qq_guid'];
                    $list[$key]['type'] = $value['type'];
                    $list[$key]['title'] = $value['title'];
                    $list[$key]['summary'] = $value['summary'];
                    $list[$key]['options'] = $this->get_options($value['qq_id']);
                    $list[$key]['marks'] = $value['marks'];
                    $list[$key]['quiz_name'] = $value['quiz_name'];
                    $list[$key]['status'] = $value['status'];
                    $list[$key]['created_at'] = $value['created_at'];
                    $list[$key]['updated_at'] = $value['updated_at'];
                }
                return $list;
            } else {
                return [];
            }
        } else {
            return $query->row()->count;
        }
    }

    public function get_qquestion_details_by_id($qq_id)
    {
        $this->db->select('IFNULL(qq.qq_guid,"") AS qq_guid', FALSE);
        $this->db->select('IFNULL(qq.type,"") AS type', FALSE);
        $this->db->select('IFNULL(qq.title,"") AS title', FALSE);
        $this->db->select('IFNULL(qq.summary,"") AS summary', FALSE);
        $this->db->select('IFNULL(qq.marks,"") AS marks', FALSE);
        $this->db->select('IFNULL(q.quiz_name,"") AS quiz_name', FALSE);
        $this->db->select('IFNULL(qq.status,"") AS status', FALSE);
        $this->db->select('IFNULL(qq.created_at,"") AS created_at', FALSE);
        $this->db->select('IFNULL(qq.updated_at,"") AS updated_at', FALSE);
        $this->db->from('quiz_questions AS qq');
        $this->db->join('quizs AS q', 'q.quiz_id = qq.quiz_id', 'LEFT');
        $this->db->where('qq.qq_id', $qq_id);
        $this->db->order_by('qq.created_at', 'desc');
        $query = $this->db->get();
        $reuslt = $query->row_array();
        // $reuslt['string'] = unique_random_string('campaign_templates', 'unique_string', [], 'alnum', 12);
        return $reuslt;
    }

    // =============================================================== quiz question option model start =============================================================
    /** create_lesson
     * @param type $title, 
     * @param type $summary, 
     * @param type $lesson_id, 
     * @param type $status
     * @return type
     */
    public function create_quiz_question_option($qq_id, $title, $summary, $user_id)
    {
        $this->db->insert('quiz_options', [
            "option_guid" => get_guid(),
            "option_title" => $title,
            "option_summary" => $summary,
            "question_id" => $qq_id,
            "created_at" => DATETIME,
            "updated_at" => DATETIME,
        ]);
        $option_id = $this->db->insert_id();
        return $option_id;
    }


    /** edit_quiz_question_option
     * @param type $qa_id, 
     * @param type $lesson_id, 
     * @param type $title, 
     * @param type $summary, 
     * @param type $answer, 
     * @param type $status
     * @return type
     */
    public function edit_quiz_question_option($option_id, $title, $summary, $qq_id, $user_id)
    {
        $data = [
            "option_title" => $title,
            "option_summary" => $summary,
            "question_id" => $qq_id,
            "updated_at" => DATETIME,
        ];

        $this->db->update('quiz_options', $data, array(
            'option_id' => $option_id,
        ));
        $affected_rows_count = $this->db->affected_rows();
        return $affected_rows_count;
    }

    /*
***  
GET SUBJECTS list
***
    */

    public function options_list($user_id, $keyword, $limit, $offset, $column_name, $order_by, $user_type, $qq_id = "")
    {
        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);
            $this->db->select('IFNULL(qo.option_guid,"") AS option_guid', FALSE);
            $this->db->select('IFNULL(qo.option_title,"") AS option_title', FALSE);
            $this->db->select('IFNULL(qo.option_summary,"") AS option_summary', FALSE);
            $this->db->select('IFNULL(qq.title,"") AS question_title', FALSE);
            $this->db->select('IFNULL(qq.created_at,"") AS created_at', FALSE);
            $this->db->select('IFNULL(qq.updated_at,"") AS updated_at', FALSE);
        } else {
            $this->db->select('COUNT(qo.option_id) as count', FALSE);
        }
        $this->db->from('quiz_options AS qo');
        $this->db->join('quiz_questions AS qq', 'qq.qq_id = qo.question_id', 'LEFT');
        if (!empty($qq_id)) {
            $this->db->where('qo.question_id', $qq_id);
        }
        $this->db->order_by('qo.created_at', 'desc');

        // if (!empty($filterBy)) {
        // 	$this->db->like('u.status', $filterBy);
        // }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('qo.option_title', $keyword, 'both');
            $this->db->group_end();
        }

        if (($column_name !== '') && ($order_by !== '')) {
            $this->db->order_by('qo.' . $column_name, $order_by);
        }
        // if ($user_type != 'ADMIN') {
        // 	$this->db->where('c.added_by', $user_id);
        // }

        $query = $this->db->get();
        $results = $query->result_array();
        if (($limit > 0) && ($offset >= 0)) {
            if ($query->num_rows() > 0) {
                $list = [];
                foreach ($results as $key => $value) {
                    $list[$key]['option_guid'] = $value['option_guid'];
                    $list[$key]['option_title'] = $value['option_title'];
                    $list[$key]['option_summary'] = $value['option_summary'];
                    $list[$key]['question_title'] = $value['question_title'];
                    $list[$key]['created_at'] = $value['created_at'];
                    $list[$key]['updated_at'] = $value['updated_at'];
                }
                return $list;
            } else {
                return [];
            }
        } else {
            return $query->row()->count;
        }
    }



    public function get_options_details_by_id($option_id)
    {
        $this->db->select('IFNULL(qo.option_guid,"") AS option_guid', FALSE);
        $this->db->select('IFNULL(qo.option_title,"") AS option_title', FALSE);
        $this->db->select('IFNULL(qo.option_summary,"") AS option_summary', FALSE);
        $this->db->select('IFNULL(qq.title,"") AS question_title', FALSE);
        $this->db->select('IFNULL(qq.created_at,"") AS created_at', FALSE);
        $this->db->select('IFNULL(qq.updated_at,"") AS updated_at', FALSE);

        $this->db->from('quiz_options AS qo');
        $this->db->join('quiz_questions AS qq', 'qq.qq_id = qo.question_id', 'LEFT');
        $this->db->where('qo.option_id', $option_id);
        $this->db->order_by('qo.created_at', 'desc');
        $query = $this->db->get();
        $reuslt = $query->row_array();
        // $reuslt['string'] = unique_random_string('campaign_templates', 'unique_string', [], 'alnum', 12);
        return $reuslt;
    }

    // =============================================================== quiz question option model end =============================================================
    // =============================================================== quiz question solution model start =============================================================

    
       /** create_lesson
     * @param type $title, 
     * @param type $summary, 
     * @param type $lesson_id, 
     * @param type $status
     * @return type
     */
    public function create_quiz_question_solution($qq_id, $solution_text, $option_id, $user_id)
    {
        $this->db->insert('quiz_answers', [
            "qans_guid" => get_guid(),
            "qq_id" => $qq_id,
            "soultion" => $solution_text,
            "quiz_option_id" => $option_id,
            "created_at" => DATETIME,
            "updated_at" => DATETIME,
        ]);
        $option_id = $this->db->insert_id();
        return $option_id;
    }

        /** edit_quiz_question_solution
     * @param type $qa_id, 
     * @param type $lesson_id, 
     * @param type $title, 
     * @param type $summary, 
     * @param type $answer, 
     * @param type $status
     * @return type
     */
    public function edit_quiz_question_solution($qans_id, $solution_text, $qq_id, $option_id, $user_id)
    {
        $data = [
            "soultion" => $solution_text,
            "qq_id" => $qq_id,
            "quiz_option_id" => $option_id,
            "updated_at" => DATETIME,
        ];

        $this->db->update('quiz_answers', $data, array(
            'qans_id' => $qans_id,
        ));
        $affected_rows_count = $this->db->affected_rows();
        return $affected_rows_count;
    }

    // =============================================================== quiz question solution model end =============================================================
}
