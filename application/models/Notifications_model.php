<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This is an example of a few basic Notification methods
 *
 * @package         CodeIgniter
 */
class Notifications_model extends CI_Model {

	public function __construct() {
		// Call the CI_Model constructor
		parent::__construct();
	}

	/**
	 * [save Used to add notifications]
	 * @param  [int] $notification_type_id [notification type id]
	 * @param  [int] $sender_user_id    [sender user id]
	 * @param  [array] $receivers         [receiver user id array]
	 * @param  [int] $refrence_id       [It may be user id, organisation id and it is depend on notification type]
	 * @param  [array] $parameters      [details parameter which is used to convert notification template in readable form]
	 * @return [type]                    [description]
	 */
	public function save($notification_type_id, $sender_user_id, $receivers, $refrence_id, $parameters = array()) {
		foreach ($receivers as $receiver_user_id) {
			if ($sender_user_id != $receiver_user_id) {
				$notification = array(
					'notification_guid' => get_guid(),
					'notification_type_id' => $notification_type_id,
					'sender_user_id' => $sender_user_id,
					'receiver_user_id' => $receiver_user_id,
					'refrence_id' => $refrence_id,
					'params' => '',
					'created_at' => DATETIME,
					'updated_at' => DATETIME,
				);

				$this->db->insert('notifications', $notification);
				$notification_id = $this->db->insert_id();

				$n = 1;
				$i = 0;
				if ($parameters) {
					$notification_params = array();
					foreach ($parameters as $value) {
						$notification_param_value = json_encode($value);
						$notification_params[$i++] = array(
							'notification_id' => $notification_id,
							'notification_param_name' => $n,
							'notification_param_value' => $notification_param_value,
						);
						$n++;
						$i++;
					}
					if ($notification_params) {
						$this->db->insert_batch('notification_params', $notification_params);
					}
				}

				$this->db->select('notification_type_key');
				$this->db->from('notification_types');
				$this->db->where('notification_type_id', $notification_type_id);
				$res = $this->db->get()->row_array();
				$notification_type_key = '';
				if (!empty($res)) {
					$notification_type_key = $res['notification_type_key'];
				}

				// $receiver_user_guid = get_detail_by_id($receiver_user_id, 'user', 'user_guid');
				// notify_node_server('notify_notification_count', array('user_guid' => $receiver_user_guid, 'notification_count' => 1, 'notification_type_key' => $notification_type_key, 'notification_type_id' => $notification_type_id));
			}
		}
	}

	/**
	 * [delete Used to delete notifications]
	 * @param  [string]  $notification_id [notification id]
	 */
	public function delete($notification_guid, $user_id) {
		$this->db->where('notification_guid', $notification_guid);
		$this->db->where('receiver_user_id', $user_id);
		$this->db->set('status', 'DELETED');
		$this->db->update('notifications');
	}

	/**
	 * [list uses to get user notifications list]
	 * @param  [int]     $user_id
	 * @param  [int]        $page_no
	 * @param  [int]        $page_size
	 * @return [array]      [notifications]
	 */
	public function list($user_id, $page_no = 1, $page_size = 10, $count_flag = FALSE) {
		// $this->db->select('n.*');
		$this->db->select('IFNULL(n.notification_guid,"") AS notification_guid', FALSE);
		$this->db->select('IFNULL(n.notification_id,"") AS notification_id', FALSE);
		$this->db->select('IFNULL(n.notification_type_id,"") AS notification_type_id', FALSE);
		$this->db->select('n.status');
		$this->db->select('IFNULL(n.created_at,"") AS created_at', FALSE);
		$this->db->select('IFNULL(u.user_guid,"") AS sender_user_guid', FALSE);
		$this->db->select('nt.notification_type_key, nt.notification_template', FALSE);
		$this->db->from('notifications n');
		$this->db->join('notification_types nt', 'nt.notification_type_id=n.notification_type_id', 'left');
		$this->db->join('users u', 'u.user_id=n.sender_user_id', 'left');

		if (!$count_flag) {
			$offset = ($page_no - 1) * $page_size;
			$this->db->limit($page_size, $offset);
		}
		$this->db->where('n.receiver_user_id', $user_id);
		$this->db->where_not_in('n.status', array('DELETED'));
		$this->db->order_by("n.created_at", "DESC");
		$query = $this->db->get();
		if ($count_flag) {
			return $query->num_rows();
		} else {
			$results = $query->result_array();
			$notifications = array();
			foreach ($results as $notification) {
				$params = $this->get_params($notification['notification_id']);
				$notification_type_id = $notification['notification_type_id'];
				$notification['logo'] = site_url('webhost/assets/image/dummy-logo.png');
				if ($params) {
					foreach ($params as $param) {
						$key = 'p' . $param['notification_param_name'];
						$param_details = json_decode($param['notification_param_value'], true);

						$param_type = $param_details['type'];
						$param_refrence_id = $param_details['refrence_id'];
						$select_field = "*";
						if ($param_type == 'user') {
							$select_field = 'concat(first_name," ",last_name) as name, user_guid as guid';
						}

						if ($param_type == 'organisation') {
							$select_field = 'name, organisation_guid as guid';
						}

						if ($param_type == 'job') {
							$select_field = 'title as name, job_guid as guid';
						}

						if ($param_type == 'date') {
							// $param_refrence_id = date("M d, Y", strtotime($param_refrence_id));
							$entity_details = array('name' => $param_refrence_id, 'guid' => '');
						} else {
							$entity_details = get_detail_by_id($param_refrence_id, $param_type, $select_field, 2);
						}

						if ($param_type == 'member_type') {
							$entity_details = ['name' => $param_refrence_id, 'guid' => ''];
						}

						if ($param_type == 'icon') {
							$entity_details = ['name' => $param_refrence_id, 'guid' => ''];
						}

						if ($param_type == 'user_applied_job') {
							$applied_job_guid = get_detail_by_id($param_refrence_id, 'user_applied_job', 'applied_job_guid');
							$entity_details = ['name' => '', 'guid' => $applied_job_guid];
						}

						// ADDED LOGO
						if ($param_type == 'organisation') {
							$organisation_logo_id = get_guid_detail($entity_details['guid'], 'organisation', 'logo_id');
							$organisation_type_id = get_guid_detail($entity_details['guid'], 'organisation', 'organisation_type_id');
							$media_name = get_detail_by_id($organisation_logo_id, 'media', 'name');

							if (!empty($media_name)) {
								// $media_name = site_url('/webhost/uploads/' . $media_name);
								$media_name = s3_url($media_name);
							} else {
								if ($organisation_type_id == 1) {
									$media_name = site_url('webhost/assets/image/dummy_company.png');
								} elseif ($organisation_type_id == 2) {
									$media_name = site_url('webhost/assets/image/dummy_university.png');
								}
							}
						}

						if ($param_type == 'job') {
							$organisation_id = get_guid_detail($entity_details['guid'], 'job', 'organisation_id');

							$organisation_logo_id = get_detail_by_id($organisation_id, 'organisation', 'logo_id');

							$organisation_type_id = get_detail_by_id($organisation_id, 'organisation', 'organisation_type_id');
							$media_name = get_detail_by_id($organisation_logo_id, 'media', 'name');

							if (!empty($media_name)) {
								// $media_name = site_url('/webhost/uploads/' . $media_name);
								$media_name = s3_url($media_name);

							} else {
								if ($organisation_type_id == 1) {
									$media_name = site_url('webhost/assets/image/dummy_company.png');
								} elseif ($organisation_type_id == 2) {
									$media_name = site_url('webhost/assets/image/dummy_university.png');
								}
							}
						}

						if ($param_type == 'user_applied_job') {
							$job_id = get_guid_detail($entity_details['guid'], 'user_applied_job', 'job_id');

							$organisation_id = get_detail_by_id($job_id, 'job', 'organisation_id');

							$organisation_logo_id = get_detail_by_id($organisation_id, 'organisation', 'logo_id');

							$organisation_type_id = get_detail_by_id($organisation_id, 'organisation', 'organisation_type_id');
							$media_name = get_detail_by_id($organisation_logo_id, 'media', 'name');

							if (!empty($media_name)) {
								// $media_name = site_url('/webhost/uploads/' . $media_name);
								$media_name = s3_url($media_name);
							} else {
								if ($organisation_type_id == 1) {
									$media_name = site_url('webhost/assets/image/dummy_company.png');
								} elseif ($organisation_type_id == 2) {
									$media_name = site_url('webhost/assets/image/dummy_university.png');
								}
							}
						}

						if (!empty($media_name)) {
							$notification['logo'] = $media_name;
						} else {
							$notification['logo'] = site_url('webhost/assets/image/dummy-logo.png.png');
						}
						$notification['param_details'][] = [
							'id' => $key,
							'name' => $entity_details['name'],
							'guid' => $entity_details['guid'],
							'param_type' => $param_type,
						];
						// if (isset($notification[$key]) && !empty($notification[$key])) {
						// 	if (!in_array($entity_details, $notification[$key])) {
						// 		$notification[$key][] = $entity_details;
						// 	}
						// } else {
						// 	$notification[$key][] = $entity_details;
						// }

						// if ($param_type == 'user' && $param_refrence_id == $user_id) {
						// 	if (in_array($notification_type_id, array(5, 6, 22, 23))) {
						// 		$notification['notification_template'] = str_replace("#p1#", 'your', $notification['notification_template']);
						// 	}
						// }
					}
				}
				unset($notification['notification_id']);
				unset($notification['notification_type_id']);
				unset($notification['sender_user_guid']);
				$notifications[] = $notification;

			}

			return $notifications;
		}
	}

	public function get_params($notification_id) {
		$params = array();
		$this->db->select('notification_param_name,notification_param_value');
		$this->db->from('notification_params');
		$this->db->where('notification_id', $notification_id);
		$notification_params = $this->db->get();
		if ($notification_params->num_rows()) {
			$params = $notification_params->result_array();
		}
		return $params;
	}

	/**
	 * [mark_as_read used to update notification status as READ]
	 * @param  [int] $notification_id [notification id]
	 * @param  [int] $user_id         [user id]
	 */
	function mark_as_read($notification_id, $user_id) {
		$this->db->set('status', 'READ');
		$this->db->where('notification_id', $notification_id);
		$this->db->where('receiver_user_id', $user_id);
		$this->db->update('notifications');
	}

	/**
	 * [mark_as_seen used to update notification status as SEEN]
	 * @param  [int] $user_id         [user id]
	 */
	public function mark_as_seen($user_id) {
		$this->db->where('receiver_user_id', $user_id);
		$this->db->where('status', 'UNSEEN');
		$this->db->update('notifications', array('status' => 'SEEN'));
	}

	public function unread_count($user_id) {
		$this->db->where('receiver_user_id', $user_id);
		$this->db->where_in('status', array('UNSEEN'));
		$this->db->from('notifications');
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function update_notifications_setting($user_id, $send_push_notifications, $send_email_notifications) {
		$this->db->update('users', [
			'send_push_notifications' => $send_push_notifications,
			'send_email_notifications' => $send_email_notifications,
		], ['user_id' => $user_id]);
	}

}
