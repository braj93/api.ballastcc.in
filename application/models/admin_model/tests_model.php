<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class tests_model extends CI_Model
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


    /** create_test
     * @param type $subject_name, 
     * @param type $user_id, 
     * @param type $status
     * @return type
     */
    public function create_test($test_name, $chapter_id, $user_id, $status)
    {
        $this->db->insert('tests', [
            "test_guid" => get_guid(),
            "test_name" => $test_name,
            "chapter_id" => $chapter_id,
            "added_by" => $user_id,
            "status" => $status,
            "created_at" => DATETIME,
            "updated_at" => DATETIME,
        ]);
        $test_id = $this->db->insert_id();
        return $test_id;
    }

    /** edit_test
     * @param type $test_id, 
     * @param type $test_name, 
     * @param type $user_id, 
     * @param type $status
     * @return type
     */
    public function edit_test($test_id, $chapter_id, $test_name, $user_id, $status)
    {
        $data = [
            "chapter_id" => $chapter_id,
            "test_name" => $test_name,
            "added_by" => $user_id,
            "status" => $status,
            "updated_at" => DATETIME,
        ];

        $this->db->update('tests', $data, array(
            'test_id' => $test_id,
        ));
        $affected_rows_count = $this->db->affected_rows();
        return $affected_rows_count;
    }

    /*
***  
GET SUBJECTS list
***
    */

    public function Testlist($user_id, $keyword = '', $limit = 0, $offset = 0, $column_name, $order_by, $user_type)
    {
        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);
            
           
            $this->db->select('IFNULL(t.test_guid,"") AS test_guid', FALSE);
            $this->db->select('IFNULL(t.test_name,"") AS test_name', FALSE);
            $this->db->select('IFNULL(c.chapter_name,"") AS chapter_name', FALSE);
            $this->db->select('CONCAT(u.first_name, " ", IFNULL (u.last_name, "")) AS added_by', FALSE);
            $this->db->select('IFNULL(t.status,"") AS status', FALSE);
            $this->db->select('IFNULL(t.created_at,"") AS created_at', FALSE);
            $this->db->select('IFNULL(t.updated_at,"") AS updated_at', FALSE);
        } else {
            $this->db->select('COUNT(t.test_id) as count', FALSE);
        }
        $this->db->from('tests AS t');        
        $this->db->join('chapters AS c', 'c.chapter_id = t.chapter_id', 'LEFT');
        $this->db->join('users AS u', 'u.user_id = t.added_by', 'LEFT');
        $this->db->order_by('t.created_at', 'desc');

        // if (!empty($filterBy)) {
        // 	$this->db->like('u.status', $filterBy);
        // }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('t.test_name', $keyword, 'both');
            $this->db->group_end();
        }

        if (($column_name !== '') && ($order_by !== '')) {
            $this->db->order_by('t.' . $column_name, $order_by);
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
                    $list[$key]['test_guid'] = $value['test_guid'];
                    $list[$key]['test_name'] = $value['test_name'];
                    $list[$key]['chapter_name'] = $value['chapter_name'];
                    $list[$key]['added_by'] = $value['added_by'];
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
GET TEST list BY CHAPTER ID
***
    */

    public function list_by_chapter_id($user_id, $keyword = '', $limit = 0, $offset = 0, $column_name, $order_by, $user_type, $chapter_id)
    {
        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);
            
           
            $this->db->select('IFNULL(t.test_guid,"") AS test_guid', FALSE);
            $this->db->select('IFNULL(t.test_name,"") AS test_name', FALSE);
            $this->db->select('IFNULL(c.chapter_name,"") AS chapter_name', FALSE);
            $this->db->select('CONCAT(u.first_name, " ", IFNULL (u.last_name, "")) AS added_by', FALSE);
            $this->db->select('IFNULL(t.status,"") AS status', FALSE);
            $this->db->select('IFNULL(t.created_at,"") AS created_at', FALSE);
            $this->db->select('IFNULL(t.updated_at,"") AS updated_at', FALSE);
        } else {
            $this->db->select('COUNT(t.test_id) as count', FALSE);
        }
        $this->db->from('tests AS t');        
        $this->db->join('chapters AS c', 'c.chapter_id = t.chapter_id', 'LEFT');
        $this->db->join('users AS u', 'u.user_id = t.added_by', 'LEFT');
        $this->db->where('t.chapter_id', $chapter_id);
        $this->db->order_by('t.created_at', 'desc');

        // if (!empty($filterBy)) {
        // 	$this->db->like('u.status', $filterBy);
        // }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('t.test_name', $keyword, 'both');
            $this->db->group_end();
        }

        if (($column_name !== '') && ($order_by !== '')) {
            $this->db->order_by('t.' . $column_name, $order_by);
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
                    $list[$key]['test_guid'] = $value['test_guid'];
                    $list[$key]['test_name'] = $value['test_name'];
                    $list[$key]['chapter_name'] = $value['chapter_name'];
                    $list[$key]['added_by'] = $value['added_by'];
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

    

    public function get_test_details_by_id($test_id)
    {
        $this->db->select('IFNULL(t.test_guid,"") AS test_guid', FALSE);
        $this->db->select('IFNULL(t.test_name,"") AS test_name', FALSE);
        $this->db->select('IFNULL(c.chapter_name,"") AS chapter_name', FALSE);
        $this->db->select('IFNULL(c.chapter_guid,"") AS chapter_guid', FALSE);
        $this->db->select('CONCAT(u.first_name, " ", IFNULL (u.last_name, "")) AS added_by', FALSE);
        $this->db->select('IFNULL(t.status,"") AS status', FALSE);
        $this->db->select('IFNULL(t.created_at,"") AS created_at', FALSE);
        $this->db->select('IFNULL(t.updated_at,"") AS updated_at', FALSE); 
        
        $this->db->from('tests AS t');
        $this->db->join('chapters AS c', 'c.chapter_id = t.chapter_id', 'LEFT');
        $this->db->join('users AS u', 'u.user_id = t.added_by', 'LEFT');
        
        $this->db->where('t.test_id', $test_id);

        $query = $this->db->get();
        $reuslt = $query->row_array();
        // $reuslt['string'] = unique_random_string('campaign_templates', 'unique_string', [], 'alnum', 12);
        return $reuslt;
    }
// ==============================test model end ======================================================================
/*
****
***
*
*/
// =========================================== question model start ============================================

    /** create_question
     * @param type $test_id, 
     * @param type $question_type, 
     * @param type $grading_type
     * @param type $question_marks, 
     * @param type $question
     * @param type $user_id, 
     * @param type $status
     * @return type
     */
    public function create_question($test_id,$question_type,$grading_type,$question_marks,$question,$user_id,$status)
    {
        $this->db->insert('questions', [
            "question_guid" => get_guid(),
            "test_id" => $test_id,
            "question_type" => $question_type,
            "grading_type" => $grading_type,
            "marks" => $question_marks,
            "question" => $question,
            "added_by" => $user_id,
            "status" => $status,
            "created_at" => DATETIME,
            "updated_at" => DATETIME,
        ]);
        $question_id = $this->db->insert_id();
        return $test_id;
    }

    /** edit_question
     * @param type $test_id, 
     * @param type $test_name, 
     * @param type $user_id, 
     * @param type $status
     * @return type
     */
    public function edit_question($question_id, $question_type, $grading_type, $question_marks, $question, $user_id, $status)
    {
        $data = [
            "question_type" => $question_type,
            "grading_type" => $grading_type,
            "marks" => $question_marks,
            "question" => $question,
            "added_by" => $user_id,
            "status" => $status,
            "updated_at" => DATETIME,
        ];

        $this->db->update('questions', $data, array(
            'question_id' => $question_id,
        ));
        $affected_rows_count = $this->db->affected_rows();
        return $affected_rows_count;
    }

    /*
***  
GET SUBJECTS list
***
    */

    public function Questionlist($user_id, $keyword = '', $limit = 0, $offset = 0, $column_name, $order_by, $user_type)
    {
        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);
            
           
            $this->db->select('IFNULL(q.question_guid,"") AS question_guid', FALSE);
            $this->db->select('IFNULL(q.question,"") AS question', FALSE);
            $this->db->select('IFNULL(q.question_type,"") AS question_type', FALSE);
            $this->db->select('IFNULL(q.grading_type,"") AS grading_type', FALSE);
            $this->db->select('IFNULL(q.marks,"") AS marks', FALSE);
            $this->db->select('IFNULL(t.test_name,"") AS test_name', FALSE);
            $this->db->select('CONCAT(u.first_name, " ", IFNULL (u.last_name, "")) AS added_by', FALSE);
            $this->db->select('IFNULL(q.status,"") AS status', FALSE);
            $this->db->select('IFNULL(q.created_at,"") AS created_at', FALSE);
            $this->db->select('IFNULL(q.updated_at,"") AS updated_at', FALSE);
        } else {
            $this->db->select('COUNT(t.test_id) as count', FALSE);
        }
        $this->db->from('questions AS q');        
        $this->db->join('tests AS t', 't.test_id = q.test_id', 'LEFT');
        $this->db->join('users AS u', 'u.user_id = q.added_by', 'LEFT');
        $this->db->order_by('q.created_at', 'desc');

        // if (!empty($filterBy)) {
        // 	$this->db->like('u.status', $filterBy);
        // }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('q.question', $keyword, 'both');
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
                    $list[$key]['question_guid'] = $value['question_guid'];
                    $list[$key]['question'] = $value['question'];                    
                    $list[$key]['question_type'] = $value['question_type'];
                    $list[$key]['grading_type'] = $value['grading_type'];
                    $list[$key]['marks'] = $value['marks'];
                    $list[$key]['test_name'] = $value['test_name'];
                    $list[$key]['added_by'] = $value['added_by'];
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
GET TEST list BY CHAPTER ID
***
    */

    public function Question_list_by_test_id($user_id, $keyword = '', $limit = 0, $offset = 0, $column_name, $order_by, $user_type, $test_id)
    {
        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);
            
           
            $this->db->select('IFNULL(q.question_guid,"") AS question_guid', FALSE);
            $this->db->select('IFNULL(q.question,"") AS question', FALSE);
            $this->db->select('IFNULL(q.question_type,"") AS question_type', FALSE);
            $this->db->select('IFNULL(q.grading_type,"") AS grading_type', FALSE);
            $this->db->select('IFNULL(q.marks,"") AS marks', FALSE);
            $this->db->select('IFNULL(t.test_name,"") AS test_name', FALSE);
            $this->db->select('CONCAT(u.first_name, " ", IFNULL (u.last_name, "")) AS added_by', FALSE);
            $this->db->select('IFNULL(q.status,"") AS status', FALSE);
            $this->db->select('IFNULL(q.created_at,"") AS created_at', FALSE);
            $this->db->select('IFNULL(q.updated_at,"") AS updated_at', FALSE);
        } else {
            $this->db->select('COUNT(t.test_id) as count', FALSE);
        }
        
        $this->db->from('questions AS q');        
        $this->db->join('tests AS t', 't.test_id = q.test_id', 'LEFT');
        $this->db->join('users AS u', 'u.user_id = t.added_by', 'LEFT');
        $this->db->where('q.test_id', $test_id);
        $this->db->order_by('t.created_at', 'desc');

        // if (!empty($filterBy)) {
        // 	$this->db->like('u.status', $filterBy);
        // }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('q.question', $keyword, 'both');
            $this->db->group_end();
        }

        if (($column_name !== '') && ($order_by !== '')) {
            $this->db->order_by('t.' . $column_name, $order_by);
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
                    // $list[$key]['test_guid'] = $value['test_guid'];
                    // $list[$key]['test_name'] = $value['test_name'];
                    // $list[$key]['chapter_name'] = $value['chapter_name'];
                    // $list[$key]['added_by'] = $value['added_by'];
                    // $list[$key]['status'] = $value['status'];
                    // $list[$key]['created_at'] = $value['created_at'];
                    // $list[$key]['updated_at'] = $value['updated_at'];
                    $list[$key]['question_guid'] = $value['question_guid'];
                    $list[$key]['question'] = $value['question'];                    
                    $list[$key]['question_type'] = $value['question_type'];
                    $list[$key]['grading_type'] = $value['grading_type'];
                    $list[$key]['marks'] = $value['marks'];
                    $list[$key]['test_name'] = $value['test_name'];
                    $list[$key]['added_by'] = $value['added_by'];
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

    

    public function get_question_details_by_id($question_id)
    {
        $this->db->select('IFNULL(q.question_guid,"") AS question_guid', FALSE);
        $this->db->select('IFNULL(q.question,"") AS question', FALSE);
        $this->db->select('IFNULL(q.question_type,"") AS question_type', FALSE);
        $this->db->select('IFNULL(q.grading_type,"") AS grading_type', FALSE);
        $this->db->select('IFNULL(q.marks,"") AS marks', FALSE);
        $this->db->select('IFNULL(t.test_name,"") AS test_name', FALSE);
        $this->db->select('IFNULL(t.test_guid,"") AS test_guid', FALSE);
        $this->db->select('CONCAT(u.first_name, " ", IFNULL (u.last_name, "")) AS added_by', FALSE);
        $this->db->select('IFNULL(q.status,"") AS status', FALSE);
        $this->db->select('IFNULL(q.created_at,"") AS created_at', FALSE);
        $this->db->select('IFNULL(q.updated_at,"") AS updated_at', FALSE);
        
        $this->db->from('questions AS q');        
        $this->db->join('tests AS t', 't.test_id = q.test_id', 'LEFT');
        $this->db->join('users AS u', 'u.user_id = t.added_by', 'LEFT');
        $this->db->where('q.question_id', $question_id);
        $query = $this->db->get();
        $reuslt = $query->row_array();
        // $reuslt['string'] = unique_random_string('campaign_templates', 'unique_string', [], 'alnum', 12);
        return $reuslt;
    }
// ==============================question model end ======================================================================
/*
****
***
*
*/
// =========================================== answers model start ============================================

    /** create_answer
     * @param type $test_id, 
     * @param type $question_type, 
     * @param type $grading_type
     * @param type $question_marks, 
     * @param type $question
     * @param type $user_id, 
     * @param type $status
     * @return type
     */
    public function create_answer($question_id, $answer, $user_id)
    {
        $this->db->insert('answers', [
            "answer_guid" => get_guid(),
            "question_id" => $question_id,            
            "title" => $answer,
            // "added_by" => $user_id,
            "created_at" => DATETIME,
            "updated_at" => DATETIME,
        ]);
        $answer_id = $this->db->insert_id();
        return $answer_id;
    }

    /** edit_answer
     * @param type $answer_id, 
     * @param type $answer,
     */
    public function edit_answer($answer_id, $answer)
    {
        $data = [
            "title" => $answer,
            "updated_at" => DATETIME,
        ];

        $this->db->update('answers', $data, array(
            'answer_id' => $answer_id,
        ));
        $affected_rows_count = $this->db->affected_rows();
        return $affected_rows_count;
    }
    /*
***  
GET ANSWER LIST BY QUESTION ID
***
    */

    public function Answers_list_by_question_id($user_id, $keyword = '', $limit = 0, $offset = 0, $column_name, $order_by, $user_type, $question_id)
    {
        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);
            
           
            $this->db->select('IFNULL(a.answer_guid,"") AS answer_guid', FALSE);
            $this->db->select('IFNULL(a.title,"") AS answer', FALSE);            
            $this->db->select('IFNULL(q.question,"") AS question', FALSE);
            $this->db->select('IFNULL(q.question_guid,"") AS question_guid', FALSE);
            $this->db->select('CONCAT(u.first_name, " ", IFNULL (u.last_name, "")) AS added_by', FALSE);            
            $this->db->select('IFNULL(a.created_at,"") AS created_at', FALSE);
            $this->db->select('IFNULL(a.updated_at,"") AS updated_at', FALSE);
        } else {
            $this->db->select('COUNT(a.answer_id) as count', FALSE);
        }
        
        $this->db->from('answers AS a');        
        $this->db->join('questions AS q', 'q.question_id = a.question_id', 'LEFT');
        $this->db->join('users AS u', 'u.user_id = q.added_by', 'LEFT');
        $this->db->where('a.question_id', $question_id);
        $this->db->order_by('a.created_at', 'desc');

        // if (!empty($filterBy)) {
        // 	$this->db->like('u.status', $filterBy);
        // }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('a.answer', $keyword, 'both');
            $this->db->group_end();
        }

        if (($column_name !== '') && ($order_by !== '')) {
            $this->db->order_by('a.' . $column_name, $order_by);
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
                    $list[$key]['question_guid'] = $value['question_guid'];
                    $list[$key]['answer_guid'] = $value['answer_guid'];
                    $list[$key]['answer'] = $value['answer'];                    
                    $list[$key]['added_by'] = $value['added_by'];
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

    

    public function get_answer_details_by_id($answer_id)
    {
        $this->db->select('IFNULL(a.answer_guid,"") AS answer_guid', FALSE);
        $this->db->select('IFNULL(a.title,"") AS answer', FALSE);       
        $this->db->select('IFNULL(q.question,"") AS question', FALSE);
        $this->db->select('IFNULL(q.question_guid,"") AS question_guid', FALSE);
        $this->db->select('CONCAT(u.first_name, " ", IFNULL (u.last_name, "")) AS added_by', FALSE);
        $this->db->select('IFNULL(q.status,"") AS status', FALSE);
        $this->db->select('IFNULL(q.created_at,"") AS created_at', FALSE);
        $this->db->select('IFNULL(q.updated_at,"") AS updated_at', FALSE);        
        $this->db->from('answers AS a');        
        $this->db->join('questions AS q', 'q.question_id = a.question_id', 'LEFT');
        $this->db->join('users AS u', 'u.user_id = q.added_by', 'LEFT');
        $this->db->where('a.answer_id', $answer_id);
        $query = $this->db->get();
        $reuslt = $query->row_array();
        // $reuslt['string'] = unique_random_string('campaign_templates', 'unique_string', [], 'alnum', 12);
        return $reuslt;
    }








}
