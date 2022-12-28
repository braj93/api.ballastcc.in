<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Lesson_model extends CI_Model
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
    public function create_lesson($lesson_name, $summary, $chapter_id, $user_id, $status)
    {
        $this->db->insert('lessons', [
            "lesson_guid" => get_guid(),
            "lesson_name" => $lesson_name,
            "lesson_summary" => $summary,
            "chapter_id" => $chapter_id,
            "status" => $status,
            "created_at" => DATETIME,
            "updated_at" => DATETIME,
        ]);
        $lesson_id = $this->db->insert_id();
        return $lesson_id;
    }

    /** edit_lesson
     * @param type $chapter_id, 
     * @param type $lesson_name, 
     * @param type $summary, 
     * @param type $status
     * @return type
     */
    public function edit_lesson($lesson_id, $chapter_id, $lesson_name, $summary, $user_id, $status)
    {
        $data = [
            "chapter_id" => $chapter_id,
            "lesson_name" => $lesson_name,
            "lesson_summary" => $summary,
            "status" => $status,
            "updated_at" => DATETIME,
        ];

        $this->db->update('lessons', $data, array(
            'lesson_id' => $lesson_id,
        ));
        $affected_rows_count = $this->db->affected_rows();
        return $affected_rows_count;
    }

    /*
***  
GET SUBJECTS list
***
    */

    public function list($user_id, $column_name, $order_by, $user_type, $keyword = '', $limit = 0, $offset = 0)
    {
        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);


            $this->db->select('IFNULL(l.lesson_guid,"") AS lesson_guid', FALSE);
            $this->db->select('IFNULL(l.lesson_name,"") AS lesson_name', FALSE);
            $this->db->select('IFNULL(l.lesson_summary,"") AS lesson_summary', FALSE);
            $this->db->select('IFNULL(c.chapter_name,"") AS chapter_name', FALSE);
            $this->db->select('IFNULL(l.status,"") AS status', FALSE);
            $this->db->select('IFNULL(l.created_at,"") AS created_at', FALSE);
            $this->db->select('IFNULL(l.updated_at,"") AS updated_at', FALSE);
        } else {
            $this->db->select('COUNT(l.lesson_id) as count', FALSE);
        }
        $this->db->from('lessons AS l');
        $this->db->join('chapters AS c', 'c.chapter_id = l.chapter_id', 'LEFT');
        $this->db->order_by('l.created_at', 'desc');

        // if (!empty($filterBy)) {
        // 	$this->db->like('u.status', $filterBy);
        // }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('l.lesson_name', $keyword, 'both');
            $this->db->group_end();
        }

        if (($column_name !== '') && ($order_by !== '')) {
            $this->db->order_by('l.' . $column_name, $order_by);
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
                    $list[$key]['lesson_guid'] = $value['lesson_guid'];
                    $list[$key]['lesson_name'] = $value['lesson_name'];
                    $list[$key]['lesson_summary'] = $value['lesson_summary'];
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

    public function list_by_chapter_id($user_id, $column_name, $order_by, $user_type, $chapter_id, $keyword = '', $limit = 0, $offset = 0)
    {
        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);


            $this->db->select('IFNULL(l.lesson_guid,"") AS lesson_guid', FALSE);
            $this->db->select('IFNULL(l.lesson_name,"") AS lesson_name', FALSE);
            $this->db->select('IFNULL(l.lesson_summary,"") AS lesson_summary', FALSE);
            $this->db->select('IFNULL(c.chapter_name,"") AS chapter_name', FALSE);
            $this->db->select('IFNULL(l.status,"") AS status', FALSE);
            $this->db->select('IFNULL(l.created_at,"") AS created_at', FALSE);
            $this->db->select('IFNULL(l.updated_at,"") AS updated_at', FALSE);
        } else {
            $this->db->select('COUNT(l.lesson_id) as count', FALSE);
        }
        $this->db->from('lessons AS l');
        $this->db->join('chapters AS c', 'c.chapter_id = l.chapter_id', 'LEFT');
        $this->db->where('l.chapter_id', $chapter_id);
        $this->db->order_by('l.created_at', 'desc');

        // if (!empty($filterBy)) {
        // 	$this->db->like('u.status', $filterBy);
        // }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('l.lesson_name', $keyword, 'both');
            $this->db->group_end();
        }

        if (($column_name !== '') && ($order_by !== '')) {
            $this->db->order_by('l.' . $column_name, $order_by);
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
                    $list[$key]['lesson_guid'] = $value['lesson_guid'];
                    $list[$key]['lesson_name'] = $value['lesson_name'];
                    $list[$key]['lesson_summary'] = $value['lesson_summary'];
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



    public function get_details_by_id($lesson_id)
    {
        $this->db->select('IFNULL(l.lesson_guid,"") AS lesson_guid', FALSE);
        $this->db->select('IFNULL(l.lesson_name,"") AS lesson_name', FALSE);
        $this->db->select('IFNULL(l.lesson_summary,"") AS lesson_summary', FALSE);
        $this->db->select('IFNULL(c.chapter_name,"") AS chapter_name', FALSE);
        $this->db->select('IFNULL(l.status,"") AS status', FALSE);
        $this->db->select('IFNULL(l.created_at,"") AS created_at', FALSE);
        $this->db->select('IFNULL(l.updated_at,"") AS updated_at', FALSE);

        $this->db->from('lessons AS l');
        $this->db->join('chapters AS c', 'c.chapter_id = l.chapter_id', 'LEFT');
        $this->db->where('l.lesson_id', $lesson_id);
        $this->db->order_by('l.created_at', 'desc');
        $query = $this->db->get();
        $reuslt = $query->row_array();
        // $reuslt['string'] = unique_random_string('campaign_templates', 'unique_string', [], 'alnum', 12);
        return $reuslt;
    }




    // ============================================= question answer model start =========================================================

    /** create_lesson
     * @param type $question, 
     * @param type $answer, 
     * @param type $lesson_id, 
     * @param type $status
     * @return type
     */
    public function create_question_answer($lesson_id, $question, $answer, $user_id, $status)
    {
        $this->db->insert('questions_answers', [
            "qa_guid" => get_guid(),
            "question" => $question,
            "answer" => $answer,
            "lesson_id" => $lesson_id,
            "status" => $status,
            "created_at" => DATETIME,
            "updated_at" => DATETIME,
        ]);
        $qa_id = $this->db->insert_id();
        return $qa_id;
    }



    /** edit_question_answer
     * @param type $qa_id, 
     * @param type $lesson_id, 
     * @param type $title, 
     * @param type $summary, 
     * @param type $answer, 
     * @param type $status
     * @return type
     */
    public function edit_question_answer($qa_id, $lesson_id, $question, $answer, $user_id, $status)
    {
        $data = [
            "lesson_id" => $lesson_id,
            "question" => $question,
            "answer" => $answer,
            "status" => $status,
            "updated_at" => DATETIME,
        ];

        $this->db->update('questions_answers', $data, array(
            'qa_id' => $qa_id,
            'lesson_id'=>$lesson_id
        ));
        $affected_rows_count = $this->db->affected_rows();
        return $affected_rows_count;
    }

    /*
***  
GET SUBJECTS list
***
    */

    public function qa_list($user_id, $column_name, $order_by, $user_type, $keyword = '', $limit = 0, $offset = 0)
    {
        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);


            $this->db->select('IFNULL(qa.qa_guid,"") AS qa_guid', FALSE);
            $this->db->select('IFNULL(qa.question,"") AS question', FALSE);
            $this->db->select('IFNULL(qa.answer,"") AS answer', FALSE);
            $this->db->select('IFNULL(l.lesson_name,"") AS lesson_name', FALSE);
            $this->db->select('IFNULL(qa.status,"") AS status', FALSE);
            $this->db->select('IFNULL(qa.created_at,"") AS created_at', FALSE);
            $this->db->select('IFNULL(qa.updated_at,"") AS updated_at', FALSE);
        } else {
            $this->db->select('COUNT(qa.qa_id) as count', FALSE);
        }
        $this->db->from('questions_answers AS qa');
        $this->db->join('lessons AS l', 'l.lesson_id = qa.lesson_id', 'LEFT');
        $this->db->order_by('l.created_at', 'desc');

        // if (!empty($filterBy)) {
        // 	$this->db->like('u.status', $filterBy);
        // }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('qa.question_title', $keyword, 'both');
            $this->db->group_end();
        }

        if (($column_name !== '') && ($order_by !== '')) {
            $this->db->order_by('qa.' . $column_name, $order_by);
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
                    $list[$key]['qa_guid'] = $value['qa_guid'];
                    $list[$key]['question'] = $value['question'];
                    $list[$key]['answer'] = $value['answer'];
                    $list[$key]['lesson_name'] = $value['lesson_name'];
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
GET QUESTION LIST BY LESSION ID
***
    */

    public function list_by_lesson_id($user_id, $column_name, $order_by, $user_type, $lesson_id, $keyword = '', $limit = 0, $offset = 0)
    {
        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);


            $this->db->select('IFNULL(qa.qa_guid,"") AS qa_guid', FALSE);
            $this->db->select('IFNULL(qa.question,"") AS question', FALSE);
            $this->db->select('IFNULL(qa.answer,"") AS answer', FALSE);
            $this->db->select('IFNULL(l.lesson_name,"") AS lesson_name', FALSE);
            $this->db->select('IFNULL(qa.status,"") AS status', FALSE);
            $this->db->select('IFNULL(qa.created_at,"") AS created_at', FALSE);
            $this->db->select('IFNULL(qa.updated_at,"") AS updated_at', FALSE);
        } else {
            $this->db->select('COUNT(qa.qa_id) as count', FALSE);
        }
        $this->db->from('questions_answers AS qa');
        $this->db->join('lessons AS l', 'l.lesson_id = qa.lesson_id', 'LEFT');
        $this->db->where('qa.lesson_id', $lesson_id);
        $this->db->order_by('qa.created_at', 'desc');


        // if (!empty($filterBy)) {
        // 	$this->db->like('u.status', $filterBy);
        // }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('qa.question_title', $keyword, 'both');
            $this->db->group_end();
        }

        if (($column_name !== '') && ($order_by !== '')) {
            $this->db->order_by('qa.' . $column_name, $order_by);
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
                    $list[$key]['qa_guid'] = $value['qa_guid'];
                    $list[$key]['question'] = $value['question'];
                    $list[$key]['answer'] = $value['answer'];
                    $list[$key]['lesson_name'] = $value['lesson_name'];
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

    public function get_qa_details_by_id($qa_id)
    {
        $this->db->select('IFNULL(qa.qa_guid,"") AS qa_guid', FALSE);
        $this->db->select('IFNULL(qa.question,"") AS question', FALSE);
        $this->db->select('IFNULL(qa.answer,"") AS answer', FALSE);
        $this->db->select('IFNULL(l.lesson_name,"") AS lesson_name', FALSE);
        $this->db->select('IFNULL(qa.status,"") AS status', FALSE);
        $this->db->select('IFNULL(qa.created_at,"") AS created_at', FALSE);
        $this->db->select('IFNULL(qa.updated_at,"") AS updated_at', FALSE);

        $this->db->from('questions_answers AS qa');
        $this->db->join('lessons AS l', 'l.lesson_id = qa.lesson_id', 'LEFT');
        $this->db->where('qa.qa_id', $qa_id);
        $this->db->order_by('qa.created_at', 'desc');        
        $query = $this->db->get();
        $reuslt = $query->row_array();
        // $reuslt['string'] = unique_random_string('campaign_templates', 'unique_string', [], 'alnum', 12);
        return $reuslt;
    }
}
