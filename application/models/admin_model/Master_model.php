<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 */
class Master_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    /** create_batch
     * @param type $name
     * @param type $start_date
     * @param type $end_date
     * @param type $start
     * @param type $end
     * @return type
     */
    public function create_batch($name, $medium, $start_date, $end_date,  $start, $end, $status)
    {
        $this->db->insert('batches', [
            "batch_guid" => get_guid(),
            "name" => $name,
            "medium" => $medium,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "start" => $start,
            "end" => $end,
            "status" => $status,
            "created_at" => DATETIME,
            "updated_at" => DATETIME,
        ]);
        $user_id = $this->db->insert_id();
        return $user_id;
    }

    //  UPDATE BATCH
    /**
     *
     * @param type $batch_id
     * @param type $name
     * @param type $medium
     * @param type $start_date
     * @param type $end_date
     * @param type $status
     * @return boolean
     */
    public function update_batch($batch_id, $name, $medium, $start_date, $end_date,  $start, $end, $status)
    {
        $batch = [
            "name" => $name,
            "medium" => $medium,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "start" => $start,
            "end" => $end,
            "status" => $status,
            "updated_at" => DATETIME,
        ];

        $this->db->update('batches', $batch, array(
            'batch_id' => $batch_id,
        ));
        return TRUE;
    }

    //  DELETE BATCH
    /**
     *
     * @param type $batch_id
     * 
     */
    public function delete_batch($batch_id)
    {
        $this->db->update('batches', [
            'status' => 'DELETED'
        ], [
            'batch_id' => $batch_id,
        ]);
        // return TRUE;
    }


    /** CREATE CLASS
     * @param type $name
     */
    public function create_class($name, $status)
    {
        $this->db->insert('classes', [
            "class_guid" => get_guid(),
            "name" => $name,
            "status" => $status,
            "created_at" => DATETIME,
            "updated_at" => DATETIME,
        ]);
        $class_id = $this->db->insert_id();
        return $class_id;
    }

    //  UPDATE CLASS
    /**
     *
     * @param type $class_id
     * @param type $name
     * @param type $status
     * @return boolean
     */
    public function update_class($class_id, $name, $status)
    {
        $batch = [
            "name" => $name,
            "status" => $status,
            "updated_at" => DATETIME,
        ];

        $this->db->update('classes', $batch, array(
            'class_id' => $class_id,
        ));
        return TRUE;
    }

    //  DELETE CLASS
    /**
     *
     * @param type $batch_id
     * 
     */
    public function delete_class($class_id)
    {
        $this->db->update('classes', [
            'status' => 'DELETED'
        ], [
            'class_id' => $class_id,
        ]);
        // return TRUE;
    }
}
