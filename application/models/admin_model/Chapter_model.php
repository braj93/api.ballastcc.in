<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Chapter_model extends CI_Model
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


    /** create_chapter
     * @param type $subject_name, 
     * @param type $user_id, 
     * @param type $status
     * @return type
     */
    public function create_chapter($chapter_name, $summary, $course_id, $user_id, $status)
    {
        $this->db->insert('chapters', [
            "chapter_guid" => get_guid(),
            "chapter_name" => $chapter_name,
            "chapter_summary" => $summary,
            "course_id" => $course_id,
            "status" => $status,
            "created_at" => DATETIME,
            "updated_at" => DATETIME,
        ]);
        $chapter_id = $this->db->insert_id();
        return $chapter_id;
    }

    /** edit_chapter
     * @param type $subject_id, 
     * @param type $subject_name, 
     * @param type $user_id, 
     * @param type $status
     * @return type
     */
    public function edit_chapter($chapter_id, $course_id, $chapter_name, $summary, $user_id, $status)
    {
        $data = [
            "course_id" => $course_id,
            "chapter_name" => $chapter_name,
            "chapter_summary" => $summary,
            "status" => $status,
            "updated_at" => DATETIME,
        ];

        $this->db->update('chapters', $data, array(
            'chapter_id' => $chapter_id,
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


            $this->db->select('IFNULL(ch.chapter_guid,"") AS chapter_guid', FALSE);
            $this->db->select('IFNULL(ch.chapter_name,"") AS chapter_name', FALSE);
            $this->db->select('IFNULL(ch.chapter_summary,"") AS chapter_summary', FALSE);
            $this->db->select('IFNULL(c.course_name,"") AS course_name', FALSE);
            $this->db->select('IFNULL(ch.status,"") AS status', FALSE);
            $this->db->select('IFNULL(ch.created_at,"") AS created_at', FALSE);
            $this->db->select('IFNULL(ch.updated_at,"") AS updated_at', FALSE);
        } else {
            $this->db->select('COUNT(ch.chapter_id) as count', FALSE);
        }
        $this->db->from('chapters AS ch');
        $this->db->join('courses AS c', 'c.course_id = ch.course_id', 'LEFT');
        $this->db->order_by('ch.created_at', 'desc');

        // if (!empty($filterBy)) {
        // 	$this->db->like('u.status', $filterBy);
        // }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('ch.chapter_name', $keyword, 'both');
            $this->db->group_end();
        }

        if (($column_name !== '') && ($order_by !== '')) {
            $this->db->order_by('ch.' . $column_name, $order_by);
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
                    $list[$key]['chapter_guid'] = $value['chapter_guid'];
                    $list[$key]['chapter_name'] = $value['chapter_name'];
                    $list[$key]['chapter_summary'] = $value['chapter_summary'];
                    $list[$key]['course_name'] = $value['course_name'];
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

    public function list_by_course_id($user_id, $column_name, $order_by, $user_type, $course_id, $keyword = '', $limit = 0, $offset = 0)
    {
        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);
            $this->db->select('IFNULL(ch.chapter_id,"") AS chapter_id', FALSE);
            $this->db->select('IFNULL(ch.chapter_guid,"") AS chapter_guid', FALSE);
            $this->db->select('IFNULL(ch.chapter_name,"") AS chapter_name', FALSE);
            $this->db->select('IFNULL(ch.chapter_summary,"") AS chapter_summary', FALSE);
            $this->db->select('IFNULL(c.course_name,"") AS course_name', FALSE);
            $this->db->select('IFNULL(ch.status,"") AS status', FALSE);
            $this->db->select('IFNULL(ch.created_at,"") AS created_at', FALSE);
            $this->db->select('IFNULL(ch.updated_at,"") AS updated_at', FALSE);
        } else {
            $this->db->select('COUNT(ch.chapter_id) as count', FALSE);
        }
        $this->db->from('chapters AS ch');
        $this->db->join('courses AS c', 'c.course_id = ch.course_id', 'LEFT');
        // $this->db->join('users AS u', 'u.user_id = ch.added_by', 'LEFT');
        $this->db->where('ch.course_id', $course_id);
        $this->db->order_by('ch.created_at', 'desc');

        // if (!empty($filterBy)) {
        // 	$this->db->like('u.status', $filterBy);
        // }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('ch.chapter_name', $keyword, 'both');
            $this->db->group_end();
        }

        if (($column_name !== '') && ($order_by !== '')) {
            $this->db->order_by('ch.' . $column_name, $order_by);
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
                    $list[$key]['chapter_guid'] = $value['chapter_guid'];
                    $list[$key]['chapter_name'] = $value['chapter_name'];
                    $list[$key]['chapter_summary'] = $value['chapter_summary'];
                    $list[$key]['course_name'] = $value['course_name'];
                    $list[$key]['status'] = $value['status'];
                    $list[$key]['lessons'] = $this->app->get_rows('lessons','lesson_guid,lesson_name,lesson_summary,status,created_at,updated_at', ['chapter_id' => $value['chapter_id']]);
                    $list[$key]['quizs'] = $this->app->get_rows('quizs','quiz_guid,quiz_name,quiz_summary,quiz_time,status,created_at,updated_at', ['chapter_id' => $value['chapter_id']]);
                    // $list[$key]['lessons'] = $this->get_lessons($value['chapter_id ']);
                    // $list[$key]['quizs'] = $this->get_quizs($value['chapter_id ']);
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



    public function get_details_by_id($chapter_id)
    {
        $this->db->select('IFNULL(ch.chapter_guid,"") AS chapter_guid', FALSE);
        $this->db->select('IFNULL(ch.chapter_name,"") AS chapter_name', FALSE);
        $this->db->select('IFNULL(ch.chapter_summary,"") AS chapter_summary', FALSE);
        $this->db->select('IFNULL(c.course_name,"") AS course_name', FALSE);
        $this->db->select('IFNULL(ch.status,"") AS status', FALSE);
        $this->db->select('IFNULL(ch.created_at,"") AS created_at', FALSE);
        $this->db->select('IFNULL(ch.updated_at,"") AS updated_at', FALSE);


        $this->db->from('chapters AS ch');
        $this->db->join('courses AS c', 'c.course_id = ch.course_id', 'LEFT');
        $this->db->where('ch.chapter_id', $chapter_id);
        $this->db->order_by('ch.created_at', 'desc');

        $query = $this->db->get();
        $reuslt = $query->row_array();
        // $reuslt['string'] = unique_random_string('campaign_templates', 'unique_string', [], 'alnum', 12);
        return $reuslt;
    }

    public function get_chapters($course_id)
    {

        $this->db->select('IFNULL(ch.chapter_guid,"") AS chapter_guid', FALSE);
        $this->db->select('IFNULL(ch.chapter_name,"") AS chapter_name', FALSE);
        $this->db->select('IFNULL(ch.chapter_summary,"") AS chapter_summary', FALSE);
        $this->db->select('IFNULL(c.course_name,"") AS course_name', FALSE);
        $this->db->select('IFNULL(ch.status,"") AS status', FALSE);
        $this->db->select('IFNULL(ch.created_at,"") AS created_at', FALSE);
        $this->db->select('IFNULL(ch.updated_at,"") AS updated_at', FALSE);
        $this->db->from('chapters AS ch');
        $this->db->join('courses AS c', 'c.course_id = ch.course_id', 'LEFT');
        $this->db->where('ch.course_id', $course_id);
        $this->db->order_by('ch.created_at', 'desc');
        $query = $this->db->get();
        // echo $this->db->last_query();die();
        $results = $query->result_array();
        return $results;
    }

    public function get_chapters_with_lessons_and_quiz_by_course_id($course_id)
    {
        $this->db->select('IFNULL(ch.chapter_id,"") AS chapter_id', FALSE);
        $this->db->select('IFNULL(ch.chapter_guid,"") AS chapter_guid', FALSE);
        $this->db->select('IFNULL(ch.chapter_name,"") AS chapter_name', FALSE);
        $this->db->select('IFNULL(ch.chapter_summary,"") AS chapter_summary', FALSE);
        $this->db->select('IFNULL(c.course_name,"") AS course_name', FALSE);
        $this->db->select('IFNULL(ch.status,"") AS status', FALSE);
        $this->db->select('IFNULL(ch.created_at,"") AS created_at', FALSE);
        $this->db->select('IFNULL(ch.updated_at,"") AS updated_at', FALSE);
        $this->db->from('chapters AS ch');
        $this->db->join('courses AS c', 'c.course_id = ch.course_id', 'LEFT');
        $this->db->where('ch.course_id', $course_id);
        $this->db->order_by('ch.created_at', 'desc');
        $query = $this->db->get();
        // echo $this->db->last_query();die();
        $results = $query->result_array();
        $list = [];
        foreach ($results as $key => $value) {
            $list[$key]['chapter_guid'] = $value['chapter_guid'];
            $list[$key]['chapter_name'] = $value['chapter_name'];
            $list[$key]['chapter_summary'] = $value['chapter_summary'];
            $list[$key]['course_name'] = $value['course_name'];
            $list[$key]['status'] = $value['status'];
            $list[$key]['lessons'] = $this->app->get_rows('lessons','lesson_guid,lesson_name,lesson_summary,status,created_at,updated_at', ['chapter_id' => $value['chapter_id']]);
            $list[$key]['quizs'] = $this->app->get_rows('quizs','quiz_guid,quiz_name,quiz_summary,quiz_time,status,created_at,updated_at', ['chapter_id' => $value['chapter_id']]);
            // $list[$key]['lessons'] = $this->get_lessons($value['chapter_id ']);
            // $list[$key]['quizs'] = $this->get_quizs($value['chapter_id ']);
            $list[$key]['created_at'] = $value['created_at'];
            $list[$key]['updated_at'] = $value['updated_at'];
        }
        return $list;
    }


}
