<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('push_notification')) {
    function push_notification($user_id, $title = '', $body = '', $extra_data = [], $sound = 'default', $badge = '1', $color = '#203E78')
    {
        // print_r($user_id);die;
        $ci = &get_instance();
        $rows = $ci->app->get_rows('user_login_sessions', 'user_login_session_id, device_token, user_id', [
            'user_id' => $user_id,
            'device_token!=' => null,
        ]);
        // print_r($rows);die;
        if (!empty($rows)) {
            $device_tokens = [];
            foreach ($rows as $key => $value) {
                if (!in_array($value['device_token'], $device_tokens)) {
                    $device_tokens[] = $value['device_token'];
                }
            }

            $registration_chunk_ids = [];
            if (count($device_tokens) > 25) {
                $registration_chunk_ids = array_chunk($device_tokens, 25, true);
            } else {
                $registration_chunk_ids[] = $device_tokens;
            }

            foreach ($registration_chunk_ids as $key => $value) {
                $registrationIDs = [];
                $registrationIDs = $value;

                $fcmMsg = [
                    'title' => $title,
                    'body' => $body,
                    'sound' => $sound,
                    'badge' => $badge,
                    'color' => $color,
                ];

                $fcmFields = [
                    // 'to' => $singleID,
                    'registration_ids' => $registrationIDs,
                    'priority' => 'high',
                    'notification' => $fcmMsg,
                    'data' => $extra_data,
                ];

                $headers = [
                    'Authorization: key=' . FCM_SERVER_KEY,
                    'Content-Type: application/json',
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
                $result = curl_exec($ch);
                curl_close($ch);
                // echo $result . "\n\n";
                return $result;
            }
        } else {
            return json_encode('This user have no device token please add device token');
        }
    }
}

/**
 *
 * @param   format
 * @return  Current UTC Date
 */
if (!function_exists('current_date')) {

    function current_date($format, $time_diff = 0, $plus = 0, $time = 0)
    {
        $CI = &get_instance();
        $CI->load->helper('date');
        $now = now();
        if ($time) {
            $now = $time;
        }
        if ($time_diff) {
            if ($plus) {
                $now = $now + (24 * 60 * 60 * $time_diff);
            } else {
                $now = $now - (24 * 60 * 60 * $time_diff);
            }
        }
        return mdate($format, $now);
    }

}

/**
 * Create GUID
 * @return string
 */
if (!function_exists('get_guid')) {

    function get_guid()
    {
        if (function_exists('com_create_guid')) {
            return strtolower(com_create_guid());
        } else {
            mt_srand((double) microtime() * 10000); //optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45); // "-"
            $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);
            return strtolower($uuid);
        }
    }

}

if (!function_exists('unique_random_string')) {

    function unique_random_string($table, $unique_colomn, $extra_where = [], $type = 'alnum', $len = 8)
    {
        $ci = &get_instance();
        while (1) {
            $random_string = random_string($type, $len);
            $ci->db->from($table);
            $ci->db->where($unique_colomn, $random_string);
            if (!empty($extra_where)) {
                $ci->db->where($extra_where);
            }
            if ($ci->db->count_all_results() == 0) {
                break;
            }
        }
        return $random_string;
    }

}

/**
 *  safe_array_key
 * @return string
 */
if (!function_exists('safe_array_key')) {

    function safe_array_key($array, $key, $default = "")
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }

}

/**
 *  s3_url
 * @return url
 */
if (!function_exists('s3_url')) {

    function s3_url($file_name)
    {
        // if (IMAGE_SERVER == 'REMOTE') {
        //     return S3_URL . $file_name; //S3_SETTING
        // } else {
        return site_url('/uploads/' . $file_name);
        // }
    }

}

// function push_notification_iphone($device_token = '', $message = '', $badge = '1', $extra = array()) {
//     if (SEND_PUSH) {
//         try {
//             if (defined('ENVIRONMENT')) {
//                 switch (ENVIRONMENT) {
//                 case 'production':
//                     $ctx = stream_context_create();
//                     stream_context_set_option($ctx, 'ssl', 'passphrase', '123456');
//                     stream_context_set_option($ctx, "ssl", "local_cert", 'ck.pem');

//                     $fp = NULL;
//                     $errno = NULL;
//                     $errstr = NULL;
//                     $fp = stream_socket_client("tls://gateway.push.apple.com:2195", $errno, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
//                     break;
//                 default:
//                     $ctx = stream_context_create();
//                     stream_context_set_option($ctx, 'ssl', 'passphrase', '123456');
//                     stream_context_set_option($ctx, "ssl", "local_cert", 'dev-ck.pem');

//                     $fp = NULL;
//                     $errno = NULL;
//                     $errstr = NULL;
//                     $fp = stream_socket_client("tls://gateway.sandbox.push.apple.com:2195", $errno, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
//                 }
//             }
//             if ($fp === FALSE) {
//                 exit($errstr . "-asdf");
//             }
//             $content = array("aps" => array("alert" => $message, "badge" => 1, "sound" => 'default', "code" => 200, "extra" => $extra));
//             $data = json_encode($content);
//             $msg = chr(0) . pack("n", 32) . @pack("H*", $device_token) . @pack("n", strlen($data)) . $data;
//             fwrite($fp, $msg);
//             fflush($fp);
//             fclose($fp);
//         } catch (Exception $e) {
//             log_message('error', $e->getMessage());
//         }
//     }
// }

// function push_notification_android($device_token = '', $message = '', $badge = '1', $extra = array()) {
//     if (SEND_PUSH) {
//         try {

//             $badge = (int) $badge;
//             $apiKey = "AIzaSyCdjuz7D4L-wto2Y_6OvyIQR1u7IcxPSFQ";
//             // Set POST variables
//             $url = 'https://android.googleapis.com/gcm/send';

// //                $notification_type  = 'push';
//             //                $id                 = "721260039790";

//             $fields = array(
//                 'registration_ids' => array($device_token),
//                 'data' => array(
//                     "message" => rawurldecode($message),
//                     "badge" => $badge,
//                     "extra" => $extra,
//                 ),
//             );

//             $headers = array(
//                 'Authorization: key=' . $apiKey,
//                 'Content-Type: application/json',
//             );
//             // Open connection
//             $ch = curl_init();
//             // Set the url, number of POST vars, POST data
//             curl_setopt($ch, CURLOPT_URL, $url);
//             curl_setopt($ch, CURLOPT_POST, true);
//             curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // allow https verification if true
//             curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // check common name and verify with host name
//             curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
//             // Execute post
//             $result = curl_exec($ch);
//             // Close connection
//             curl_close($ch);
//             return $result;
//         } catch (Exception $e) {
//             log_message('error', $e->getMessage());
//         }
//     }
// }

/**
 * [insert_location check and insert location]
 * @param  [array] $location [Location data]
 * @return [int]           [location id]
 */
if (!function_exists('insert_location')) {

    //check and insert location
    function insert_location($data)
    {
        $data['City'] = safe_array_key($data, "City", "");
        $data['State'] = safe_array_key($data, "State", "");
        $data['Country'] = safe_array_key($data, "Country", "");
        $data['CountryCode'] = safe_array_key($data, "CountryCode", "");
        $data['StateCode'] = safe_array_key($data, "StateCode", "");
        $data['unique_id'] = get_guid();

        $CI = &get_instance();

        /* $CI->db->select('l.location_id');
        $CI->db->from('locations l');
        $CI->db->where('l.unique_id',$data['unique_id']);
        $CI->db->limit('1');
        $query = $CI->db->get();
         */
        $location = array();
        $location_id = "";
        if (!empty($location)) {
            $location_id = $location['location_id'];
        } else {
            $d = update_location($data);
            unset($data['City']);
            unset($data['Country']);
            unset($data['State']);
            unset($data['StateCode']);
            unset($data['CountryCode']);

            $data['city_id'] = $d['city_id'];
            $data['state_id'] = $d['state_id'];
            $data['country_id'] = $d['country_id'];
            $data['location_guid'] = get_guid();

            $CI->db->insert('locations', $data);
            $location['location_id'] = $CI->db->insert_id();
        }
        return $location;
    }

}

/**
 * [update_location description]
 * @param  [array] $location [Location data]
 * @return [array]           [array of country, state and city id]
 */
if (!function_exists('update_location')) {

    function update_location($location)
    {
        $CI = &get_instance();
        $city = safe_array_key($location, "City", "");
        $state = safe_array_key($location, "State", "");
        $country = safe_array_key($location, "Country", "");
        $country_code = safe_array_key($location, "CountryCode", "");
        $short_code = safe_array_key($location, "StateCode", "");
        $country_id = null;
        $state_id = null;
        $city_id = null;
        $city = trim($city);
        $state = trim($state);
        $country = trim($country);
        $country_code = trim($country_code);
        $short_code = trim($short_code);
        if ($country == "-") {
            $country = "";
        }
        if ($state == "-") {
            $state = "";
        }
        if ($city == "-") {
            $city = "";
        }

        if (!empty($country) || !empty($country_code)) {
            $CI->db->select('c.country_id');
            if (!empty($country_code)) {
                $CI->db->where('LOWER(country_code)', strtolower($country_code), null, false);
                $country_code = strtoupper($country_code);
            }
            if (!empty($country)) {
                $CI->db->where('LOWER(name)', strtolower($country), null, false);
                $country = ucfirst(strtolower($country));
            }
            $CI->db->limit('1');
            $query = $CI->db->get('countries c');
            //country master handling starts here
            if ($query->num_rows() > 0) {
                $country_data = $query->row_array();
                $country_id = $country_data['country_id'];
            } else {
                $CI->db->insert('countries', array('country_code' => $country_code, 'name' => $country));
                $country_id = $CI->db->insert_id();
            }
        }

        if ($country_id > 0 && !empty($state)) {
            //states master handling start here
            $CI->db->select('state_id');
            $CI->db->where('LOWER(name)', strtolower($state), null, false);
            $CI->db->where('country_id', $country_id);
            $CI->db->limit('1');
            $query = $CI->db->get('states');
            if ($query->num_rows() > 0) {
                $state_data = $query->row_array();
                $state_id = $state_data['state_id'];
            } else {
                $state = ucfirst(strtolower($state));
                $insert_state = array('name' => $state, 'country_id' => $country_id);
                if (!empty($short_code)) {
                    $insert_state['short_code'] = strtoupper($short_code);
                }
                $CI->db->insert('states', $insert_state);
                $state_id = $CI->db->insert_id();
            }
        }
        //city master handling start here
        if ($state_id > 0 && !empty($city)) {
            $CI->db->select('city_id');
            $CI->db->where('LOWER(Name)', strtolower($city), null, false);
            $CI->db->where('state_id', $state_id);
            $CI->db->limit('1');
            $query = $CI->db->get('cities');
            if ($query->num_rows() > 0) {
                $city_data = $query->row_array();
                $city_id = $city_data['city_id'];
            } else {
                $city = ucfirst(strtolower($city));
                $CI->db->insert('cities', array('name' => $city, 'state_id' => $state_id));
                $city_id = $CI->db->insert_id();
            }
        }
        return array('city_id' => $city_id, 'country_id' => $country_id, 'state_id' => $state_id);
    }

}

/**
 * [get_location_by_id Get location data by location_id]
 * @param  [int] $location_id [Location id]
 * @return [array]           [array of location data]
 */
if (!function_exists('get_location_by_id')) {

    function get_location_by_id($location_id)
    {
        if (!empty($location_id)) {
            $CI = &get_instance();
            $CI->db->select('l.location_guid, l.unique_id, l.formatted_address');

            $CI->db->select('IFNULL(l.latitude,"") as latitude', false);
            $CI->db->select('IFNULL(l.longitude,"") as longitude', false);
            $CI->db->select('IFNULL(l.postal_code,"") as postal_code', false);
            $CI->db->select('IFNULL(l.street_number,"") as street_number', false);

            $CI->db->select('IFNULL(s.name,"") as State', false);
            $CI->db->select('IFNULL(ct.name,"") as City', false);
            $CI->db->select('IFNULL(c.name,"") as Country', false);
            $CI->db->select('IFNULL(c.country_code,"") as CountryCode', false);

            $CI->db->from('locations l');
            $CI->db->join('cities ct', 'ct.city_id = l.city_id', 'left');
            $CI->db->join('countries c', 'c.country_id = l.country_id', 'left');
            $CI->db->join('states s', 's.state_id = l.state_id', 'left');

            $CI->db->where(array('location_id' => $location_id));
            $CI->db->limit('1');
            $query = $CI->db->get();
            $location = $query->row_array();
            return $location;
        } else {
            return array();
        }
    }

}

if (!function_exists('get_detail_by_guid')) {

    function get_detail_by_guid($entity_guid, $entity_type, $select_field = "", $response_type = 1)
    {
        if (!empty($entity_guid) && !empty($entity_type)) {
            $CI = &get_instance();
            $select_fields = ($select_field) ? $select_field : "*";
            $table_name = "";
            switch ($entity_type) {
                case 'batch':
                    $table_name = 'batches';
                    $select_fields = ($select_field) ? $select_field : "batch_id";
                    $condition = array("batch_guid" => $entity_guid);
                    break;

                case 'pricing_plan':
                    $table_name = 'pricing_plans';
                    $select_fields = ($select_field) ? $select_field : "pricing_plan_id";
                    $condition = array("pricing_plan_guid" => $entity_guid);
                    break;
                case 'knowledgebase':
                    $table_name = 'knowledgebase';
                    $select_fields = ($select_field) ? $select_field : "knowledgebase_id";
                    $condition = array("knowledgebase_guid" => $entity_guid);
                    break;
                case 'user':
                    $table_name = 'users';
                    $select_fields = ($select_field) ? $select_field : "user_id";
                    $condition = array("user_guid" => $entity_guid);
                    break;
                case 'master':
                    $table_name = 'masters';
                    $select_fields = ($select_field) ? $select_field : "master_id";
                    $condition = array("master_guid" => $entity_guid);
                    break;
                case 'plan':
                    $table_name = 'pricing_plans';
                    $select_fields = ($select_field) ? $select_field : "pricing_plan_id";
                    $condition = array("pricing_plan_guid" => $entity_guid);
                    break;
                case 'organisation':
                    $table_name = 'organisations';
                    $select_fields = ($select_field) ? $select_field : "organisation_id";
                    $condition = array("organisation_guid" => $entity_guid);
                    break;
                case 'media':
                    $table_name = 'media';
                    $select_fields = ($select_field) ? $select_field : "media_id";
                    $condition = array("media_guid" => $entity_guid);
                    break;
                case 'organisation_member':
                    $table_name = 'organisation_members';
                    $select_fields = ($select_field) ? $select_field : "organisation_member_id";
                    $condition = array("organisation_member_guid" => $entity_guid);
                    break;
                case 'job_spot':
                    $table_name = 'job_spot_types';
                    $select_fields = ($select_field) ? $select_field : "job_spot_id";
                    $condition = array("job_spot_guid" => $entity_guid);
                    break;
                case 'order':
                    $table_name = 'orders';
                    $select_fields = ($select_field) ? $select_field : "order_id";
                    $condition = array("order_guid" => $entity_guid);
                    break;
                case 'program':
                    $table_name = 'organisation_programs';
                    $select_fields = ($select_field) ? $select_field : "program_id";
                    $condition = array("program_guid" => $entity_guid);
                    break;
                case 'contact':
                    $table_name = 'crm_contact';
                    $select_fields = ($select_field) ? $select_field : "crm_contact_id";
                    $condition = array("crm_contact_guid" => $entity_guid);
                    break;
                case 'notification':
                    $table_name = 'notifications';
                    $select_fields = ($select_field) ? $select_field : "notification_id";
                    $condition = array("notification_guid" => $entity_guid);
                    break;
                case 'category_master':
                    $table_name = 'category_master';
                    $select_fields = ($select_field) ? $select_field : "category_id";
                    $condition = array("category_guid" => $entity_guid);
                    break;
                default:
                    break;
            }

            $result = array();
            if ($table_name) {
                $CI->db->select($select_fields, false);
                $CI->db->from($table_name);
                $CI->db->where($condition);
                $CI->db->limit('1');
                $query = $CI->db->get();
                if ($query->num_rows() > 0) {
                    $result = $query->row_array();
                }
            }

            switch ($response_type) {
                case '2':
                    return $result;
                    break;
                default:
                    return isset($result[$select_fields]) ? $result[$select_fields] : 0;
                    break;
            }
        }
        return 0;
    }

}

if (!function_exists('get_detail_by_id')) {

    function get_detail_by_id($entity_id, $entity_type, $select_field = "", $response_type = 1)
    {
        if (!empty($entity_id) && !empty($entity_type)) {
            $CI = &get_instance();
            $select_fields = ($select_field) ? $select_field : "*";
            $table_name = "";
            switch ($entity_type) {
                case 'user':
                    $table_name = 'users';
                    $select_fields = ($select_field) ? $select_field : "user_id";
                    $condition = array("user_id" => $entity_id);
                    break;
                case 'master':
                    $table_name = 'masters';
                    $select_fields = ($select_field) ? $select_field : "master_id";
                    $condition = array("master_id" => $entity_id);
                    break;
                case 'order':
                    $table_name = 'orders';
                    $select_fields = ($select_field) ? $select_field : "order_id";
                    $condition = array("order_id" => $entity_id);
                    break;
                case 'media':
                    $table_name = 'media';
                    $select_fields = ($select_field) ? $select_field : "media_id";
                    $condition = array("media_id" => $entity_id);
                    break;
                case 'plan':
                    $table_name = 'pricing_plans';
                    $select_fields = ($select_field) ? $select_field : "pricing_plan_guid";
                    $condition = array("pricing_plan_id" => $entity_id);
                    break;
                    break;
                case 'knowledgebase':
                    $table_name = 'knowledgebase';
                    $select_fields = ($select_field) ? $select_field : "knowledgebase_id";
                    $condition = array("knowledgebase_id" => $entity_id);
                    break;
                case 'organisation_type':
                    $table_name = 'organisation_types_master';
                    $select_fields = ($select_field) ? $select_field : "organisation_type_id";
                    $condition = array("organisation_type_id" => $entity_id);
                    break;
                case 'job_spot':
                    $table_name = 'job_spot_types';
                    $select_fields = ($select_field) ? $select_field : "job_spot_id";
                    $condition = array("job_spot_id" => $entity_id);
                    break;
                case 'user_applied_job':
                    $table_name = 'user_applied_jobs';
                    $select_fields = ($select_field) ? $select_field : "applied_job_id";
                    $condition = array("applied_job_id" => $entity_id);
                    break;
                case 'notification_type':
                    $table_name = 'notification_types';
                    $select_fields = ($select_field) ? $select_field : "notification_type_id";
                    $condition = array("notification_type_id" => $entity_id);
                    break;
                case 'program':
                    $table_name = 'organisation_programs';
                    $select_fields = ($select_field) ? $select_field : "program_id";
                    $condition = array("program_id" => $entity_id);
                    break;
                case 'event':
                    $table_name = 'organisation_events';
                    $select_fields = ($select_field) ? $select_field : "event_id";
                    $condition = array("event_id" => $entity_id);
                    break;
                default:
                    break;
            }

            $result = array();
            if ($table_name) {
                $CI->db->select($select_fields, false);
                $CI->db->from($table_name);
                $CI->db->where($condition);
                $CI->db->limit('1');
                $query = $CI->db->get();
                if ($query->num_rows() > 0) {
                    $result = $query->row_array();
                }
            }

            switch ($response_type) {
                case '2':
                    return $result;
                    break;
                default:
                    return isset($result[$select_fields]) ? $result[$select_fields] : 0;
                    break;
            }
        }
        return 0;
    }

}

if (!function_exists('validate_date')) {

    function validate_date($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

}

if (!function_exists('time_ago')) {
    function time_ago($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) {
            $string = array_slice($string, 0, 1);
        }

        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}

if (!function_exists('populate_diff_string')) {

    function populate_diff_string($interval)
    {
        $result = "";
        if ($interval->y) {
            $result .= $interval->format("%yY ");
        }
        if ($interval->m) {
            $result .= $interval->format("%mM ");
        }
        if ($interval->d) {
            $result .= $interval->format("%dD ");
        }
        if ($interval->h) {
            $result .= $interval->format("%hH ");
        }
        if ($interval->i) {
            $result .= $interval->format("%iM ");
        }
        if ($interval->s && trim($result) == "") {
            $result .= $interval->format("%sS ");
        }
        return trim($result);
    }

}

if (!function_exists('notify_node_server')) {

    function notify_node_server($method, $data)
    {
        $CI = &get_instance();
        $CI->load->library('Node');
        $node = new node(array("route" => $method, "postData" => $data));
    }
}

if (!function_exists('send_email')) {

    function send_email($to_email, $subject, $email_data)
    {
        $CI = &get_instance();
        $CI->load->helper('email');
        $email_template = $email_data['email_template'];
        $message = $CI->load->view($email_template, $email_data, true);

        $CI->load->library('email');
        $email = new CI_Email();
        print_r($email);
        $email->from(SUPPORT_EMAIL, FROM_NAME);
        $email->to($to_email);
        $email->subject($subject);
        $email->message($message);
        $email->send();

    }
}

/**
 * [convert_date_to_time_zone used to convert date from one time zone to another time zone]
 * @param  [Date]   $time           [Date, which being converted]
 * @param  [string] $from_time_zone [From time zone]
 * @param  [string] $to_time_zone   [To time zone]
 * @param  [string] $format         [Reuired date time format]
 * @return [Date]                   [Converted Date]
 */
if (!function_exists('convert_date_to_time_zone')) {
    function convert_date_to_time_zone($time, $from_time_zone, $to_time_zone, $format = 'Y-m-d H:i:s')
    {
        // create timeZone object , with from_time_zone
        $from = new DateTimeZone($from_time_zone);
        // create timeZone object , with to_time_zone
        $to = new DateTimeZone($to_time_zone);
        // read given time into ,from_time_zone
        $orignal_time = new DateTime($time, $from);
        //print_r($orignal_time);
        // fromte input date to ISO 8601 date (added in PHP 5). the create new date time object
        $to_time = new DateTime($orignal_time->format("c"));

        // set target time zone to $toTme ojbect.
        $to_time->setTimezone($to);
        //print_r($to_time);
        // return reuslt.
        return $to_time->format($format);
    }
}

/**
 * Array Pluck
 * @return object
 */
if (!function_exists('array_pluck')) {
    function array_pluck($array, $key)
    {
        return array_map(function ($v) use ($key) {
            return is_object($v) ? $v->$key : $v[$key];
        }, $array);
    }
}

// if (!function_exists('push_notification')) {
//     function push_notification($user_id, $title = '', $body = '', $extra_data = [], $sound = 'default', $badge = '1', $color = '#203E78') {
//         // print_r($user_id);die;
//         $ci = &get_instance();
//         $rows = $ci->app->get_rows('user_login_sessions', 'user_login_session_id, device_token, user_id', [
//             'user_id' => $user_id,
//             'device_token!=' => NULL,
//         ]);
//         // print_r($rows);die;
//         if (!empty($rows)) {
//             $device_tokens = [];
//             foreach ($rows as $key => $value) {
//                 if (!in_array($value['device_token'], $device_tokens)) {
//                     $device_tokens[] = $value['device_token'];
//                 }
//             }

//             $registration_chunk_ids = [];
//             if (count($device_tokens) > 25) {
//                 $registration_chunk_ids = array_chunk($device_tokens, 25, true);
//             } else {
//                 $registration_chunk_ids[] = $device_tokens;
//             }

//             foreach ($registration_chunk_ids as $key => $value) {
//                 $registrationIDs = [];
//                 $registrationIDs = $value;

//                 $fcmMsg = [
//                     'title' => $title,
//                     'body' => $body,
//                     'sound' => $sound,
//                     'badge' => $badge,
//                     'color' => $color,
//                 ];

//                 $fcmFields = [
//                     // 'to' => $singleID,
//                     'registration_ids' => $registrationIDs,
//                     'priority' => 'high',
//                     'notification' => $fcmMsg,
//                     'data' => $extra_data,
//                 ];

//                 $headers = [
//                     'Authorization: key=' . FCM_SERVER_KEY,
//                     'Content-Type: application/json',
//                 ];

//                 $ch = curl_init();
//                 curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
//                 curl_setopt($ch, CURLOPT_POST, true);
//                 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//                 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
//                 $result = curl_exec($ch);
//                 curl_close($ch);
//                 // echo $result . "\n\n";
//                 return $result;
//             }
//         } else {
//             return json_encode('This user have no device token please add device token');
//         }
//     }
// }

if (!function_exists('compareByTimeStamp')) {
    function compareByTimeStamp($time1, $time2)
    {
        if (strtotime($time1) < strtotime($time2)) {
            return 1;
        } else if (strtotime($time1) > strtotime($time2)) {
            return -1;
        } else {
            return 0;
        }
    }
}

if (!function_exists('get_last_days')) {
    function get_last_days($days, $format = 'd/m')
    {
        $m = date("m");
        $de = date("d");
        $y = date("Y");
        $dateArray = array();
        for ($i = 0; $i <= $days - 1; $i++) {
            // $dateArray[] = '"' . date($format, mktime(0, 0, 0, $m, ($de - $i), $y)) . '"';
            $dateArray[] = date($format, mktime(0, 0, 0, $m, ($de - $i), $y));
        }
        return array_reverse($dateArray);
    }
}
