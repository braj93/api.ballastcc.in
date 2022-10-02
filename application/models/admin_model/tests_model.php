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


    /** create_user
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

    /** create_user
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

    






}