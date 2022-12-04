<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Imp_notice_model extends CI_Model
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


    /** create_notice
     * @param type $subject, 
     * @param type $notice, 
     * @param type $color
     * @param type $type
     * @param type $exp_date
     * @param type $status     
     * @return type
     */
    public function create_imp_notice($subject, $notice, $color, $type, $exp_date, $status)
    {
        $this->db->insert('imp_notices', [
            "notice_guid" => get_guid(),
            "subject" => $subject,
            "notice" => $notice,
            "color" => $color,
            "type" => $type,
            "status" => $status,
            "exp_date" => $exp_date,
            "created_at" => DATETIME,
            "updated_at" => DATETIME,
        ]);
        $notice_id = $this->db->insert_id();
        return $notice_id;
    }

    /** edit_imp_notice
     * @param type $notice_id, 
     * @param type $subject, 
     * @param type $notice, 
     * @param type $color
     * @param type $type
     * @param type $status
     * @param type $exp_date
     * @return type
     */
    public function edit_imp_notice($notice_id, $subject, $notice, $color, $type, $exp_date, $status)
    {
        $data = [
            "subject" => $subject,
            "notice" => $notice,
            "color" => $color,
            "type" => $type,
            "exp_date" => $exp_date,
            "status" => $status,
            "updated_at" => DATETIME,
        ];

        $this->db->update('imp_notices', $data, array(
            'notice_id' => $notice_id,
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
            $this->db->select('IFNULL(imp.notice_guid,"") AS notice_guid', FALSE);
            $this->db->select('IFNULL(imp.subject,"") AS subject', FALSE);
            $this->db->select('IFNULL(imp.notice,"") AS notice', FALSE);
            $this->db->select('IFNULL(imp.color,"") AS color', FALSE);
            $this->db->select('IFNULL(imp.exp_date,"") AS exp_date', FALSE);
            $this->db->select('IFNULL(imp.status,"") AS status', FALSE);
            $this->db->select('IFNULL(imp.created_at,"") AS created_at', FALSE);
            $this->db->select('IFNULL(imp.updated_at,"") AS updated_at', FALSE);
        } else {
            $this->db->select('COUNT(imp.notice_id) as count', FALSE);
        }
        $this->db->from('imp_notices AS imp');
        if ($user_type != "ADMIN") {
            $this->db->where('imp.type', $user_type);
            $this->db->or_where('imp.type', "BOTH");
        }
        $this->db->order_by('imp.created_at', 'desc');

        // if (!empty($filterBy)) {
        // 	$this->db->like('u.status', $filterBy);
        // }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('imp.subject', $keyword, 'both');
            $this->db->group_end();
        }

        if (($column_name !== '') && ($order_by !== '')) {
            $this->db->order_by('imp.' . $column_name, $order_by);
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
                    $list[$key]['notice_guid'] = $value['notice_guid'];
                    $list[$key]['subject'] = $value['subject'];
                    $list[$key]['notice'] = $value['notice'];
                    $list[$key]['color'] = $value['color'];
                    $list[$key]['end_date'] = $value['exp_date'];
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
GET NOTICE DETAIL
***
    */
    public function get_imp_notice_by_id($notice_id)
    {
        $this->db->select('IFNULL(imp.notice_guid,"") AS notice_guid', FALSE);
        $this->db->select('IFNULL(imp.subject,"") AS subject', FALSE);
        $this->db->select('IFNULL(imp.notice,"") AS notice', FALSE);
        $this->db->select('IFNULL(imp.type,"") AS type', FALSE);
        $this->db->select('IFNULL(imp.color,"") AS color', FALSE);
        $this->db->select('IFNULL(imp.exp_date,"") AS exp_date', FALSE);
        $this->db->select('IFNULL(imp.status,"") AS status', FALSE);
        $this->db->select('IFNULL(imp.created_at,"") AS created_at', FALSE);
        $this->db->select('IFNULL(imp.updated_at,"") AS updated_at', FALSE);
        $this->db->from('imp_notices AS imp');
        $this->db->where('imp.notice_id', $notice_id);
        $query = $this->db->get();
        $reuslt = $query->row_array();
        // $reuslt['string'] = unique_random_string('campaign_templates', 'unique_string', [], 'alnum', 12);
        return $reuslt;
    }
}
