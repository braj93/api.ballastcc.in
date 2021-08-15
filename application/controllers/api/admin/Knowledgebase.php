<?php

defined('BASEPATH') OR exit('No direct script access allowed');

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
class Knowledgebase extends REST_Controller {

	var $_data = array();

	function __construct() {
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
		$this->load->model("admin_model/knowledgebase_model", "knowledgebase_model");
		$this->load->library('MY_Form_validation');
	}

	public function index_get() {
		$this->set_response($this->_response);
	}

	public function _check_pricing_plan($pricing_plans) {
		$pricing_plans = safe_array_key($this->_data, "pricing_pslans", []);
		foreach ($pricing_plans as $v) {
			$this->db->select("*");
			$this->db->where("pricing_plan_id", $v["id"]);
			if ($this->db->get('pricing_plans')->num_rows() == 0) {
				$this->form_validation->set_message('_check_pricing_plan', $v["id"] . ' Pricing plan has invalid entry');
				return FALSE;
			}
		}
		return TRUE;
	}

	public function _check_category($categories) {
		$categories = safe_array_key($this->_data, "categoriess", []);
		foreach ($categories as $v) {
			$this->db->select("*");
			$this->db->where("category_guid", $v["id"]);
			if ($this->db->get('category_master')->num_rows() == 0) {
				$this->form_validation->set_message('_check_category', $v["id"] . ' category has invalid entry');
				return FALSE;
			}
		}
		return TRUE;
	}
	/**
	 * CREATE KNOWLEDGEBASE API
	 */
	public function create_knowledgebase_post() {
		$this->_response["service_name"] = "admin/knowledgebase/create_knowledgebase";

		$this->form_validation->set_rules('name', 'Title', 'trim|required|min_length[3]');

		$categories = safe_array_key($this->_data, "categories", "");
		if (empty($categories)) {
			$this->form_validation->set_rules('categories', 'category', 'trim|required');
		} else {
			$this->form_validation->set_rules('categories', 'category', 'trim|callback__check_category');
		}

		$pricing_plans = safe_array_key($this->_data, "pricing_plans", "");
		if (empty($pricing_plans)) {
			$this->form_validation->set_rules('pricing_plans', 'category', 'trim|required');
		} else {
			$this->form_validation->set_rules('pricing_plans', 'category', 'trim|callback__check_pricing_plan');
		}
		// $this->form_validation->set_rules('status', 'status', 'trim|required|in_list[ACTIVE,INACTIVE]');
		// $this->form_validation->set_rules('date', 'date', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$name = safe_array_key($this->_data, "name", "");
			$categories = safe_array_key($this->_data, "categories", array());
			$pricing_plans = safe_array_key($this->_data, "pricing_plans", array());
			$status = safe_array_key($this->_data, "status", "ACTIVE");
			$video_embed_url = safe_array_key($this->_data, "video_embed_url", "");
			$knowledgebase_media_id = safe_array_key($this->_data, "knowledgebase_media_id", "");
			$date = safe_array_key($this->_data, "date", "");
			$description = safe_array_key($this->_data, "description", "");
			if ($knowledgebase_media_id) {
				$knowledgebase_media_guid = safe_array_key($this->_data, "knowledgebase_media_id", "");
				$media_data = $this->app->get_row('media', 'media_id, name', ['media_guid' => $knowledgebase_media_guid]);
				$knowledgebase_media_id = safe_array_key($media_data, "media_id", "");
			}
			$knowledgebase_id = $this->knowledgebase_model->create_knowledgebase($name, $status, $video_embed_url, $knowledgebase_media_id, $date, $description);
			$this->knowledgebase_model->save_category($categories, $knowledgebase_id);
			$this->knowledgebase_model->save_pricing_plans($pricing_plans, $knowledgebase_id);
			$this->_response["message"] = "knowledgebase added successfully";
			$this->set_response($this->_response);
		}
	}

	/**
	 * UPDATE KNOWLEDGEBASE API
	 */
	public function update_knowledgebase_post() {
		$this->_response["service_name"] = "admin/knowledgebase/update_knowledgebase";

		$this->form_validation->set_rules('name', 'Title', 'trim|required|min_length[3]');
		$categories = safe_array_key($this->_data, "categories", "");
		if (empty($categories)) {
			$this->form_validation->set_rules('categories', 'category', 'trim|required');
		} else {
			$this->form_validation->set_rules('categories', 'category', 'trim|callback__check_category');
		}

		$pricing_plans = safe_array_key($this->_data, "pricing_plans", "");
		if (empty($pricing_plans)) {
			$this->form_validation->set_rules('pricing_plans', 'Pricing Plan', 'trim|required');
		} else {
			$this->form_validation->set_rules('pricing_plans', 'Pricing Plan', 'trim|callback__check_pricing_plan');
		}
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$knowledgebase_guid = safe_array_key($this->_data, "knowledgebase_id", "");
			$knowledgebase_data = $this->app->get_row('knowledgebase', 'knowledgebase_id', ['knowledgebase_guid' => $knowledgebase_guid]);
			$knowledgebase_id = safe_array_key($knowledgebase_data, 'knowledgebase_id', "");
			$name = safe_array_key($this->_data, "name", "");
			$categories = safe_array_key($this->_data, "categories", array());
			$pricing_plans = safe_array_key($this->_data, "pricing_plans", array());
			// print_r($categories);
			// print_r($pricing_plans);
			// die();
			$status = safe_array_key($this->_data, "status", "ACTIVE");
			$video_embed_url = safe_array_key($this->_data, "video_embed_url", "");
			$knowledgebase_media_id = safe_array_key($this->_data, "knowledgebase_media_id", "");
			$date = safe_array_key($this->_data, "date", "");
			$description = safe_array_key($this->_data, "description", "");
			if ($knowledgebase_media_id) {
				$knowledgebase_media_guid = safe_array_key($this->_data, "knowledgebase_media_id", "");
				$media_data = $this->app->get_row('media', 'media_id, name', ['media_guid' => $knowledgebase_media_guid]);
				$knowledgebase_media_id = safe_array_key($media_data, "media_id", "");
			}
			$affected_rows_count = $this->knowledgebase_model->update_knowledgebase($knowledgebase_id, $name, $status, $video_embed_url, $knowledgebase_media_id, $date, $description);
			$this->knowledgebase_model->save_category($categories, $knowledgebase_id);
			$this->knowledgebase_model->save_pricing_plans($pricing_plans, $knowledgebase_id);
			if ($affected_rows_count > 0) {
				$this->knowledgebase_model->update_media_staus($knowledgebase_media_id);
			}
			$this->_response["message"] = "knowledgebase updated successfully";
			$this->set_response($this->_response);
		}
	}

	/**
	 * DELETE KNOWLEDGEBASE API
	 */
	public function knowledgebase_delete_post() {
		$this->_response["service_name"] = "admin/songs_manage/knowledgebase_delete";
		$session_key = $this->rest->key;
		$this->form_validation->set_rules('knowledgebase_id', 'knowledgebase id', 'trim|required');
		$this->form_validation->set_rules('delete_type', 'Delete type', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			$knowledgebase_guid = safe_array_key($this->_data, "knowledgebase_id", "");
			$knowledgebase_data = $this->app->get_row('knowledgebase', 'knowledgebase_id', ['knowledgebase_guid' => $knowledgebase_guid]);
			$knowledgebase_id = safe_array_key($knowledgebase_data, 'knowledgebase_id', "");
			$delete_type = safe_array_key($this->_data, "delete_type", "STATUS_DELETED");
			$this->knowledgebase_model->knowledgebase_delete($knowledgebase_id, $delete_type);
			$this->_response["message"] = 'Deleted successfully.';
			$this->set_response($this->_response);
		}
	}

	/**
	 * GET KNOWLEDGEBASE DETAILS BY ID API
	 */
	public function get_knowledgebase_details_by_id_post() {
		$this->_response["service_name"] = "admin/knowledgebase/get_knowledgebase_details_by_id";
		$this->form_validation->set_rules('knowledgebase_id', 'knowledgebase id', 'trim|required|min_length[3]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$knowledgebase_guid = safe_array_key($this->_data, "knowledgebase_id", "");
			$category_data = $this->app->get_row('knowledgebase', 'knowledgebase_id', ['knowledgebase_guid' => $knowledgebase_guid]);
			$knowledgebase_id = safe_array_key($category_data, 'knowledgebase_id', "");
			$data = $this->knowledgebase_model->get_knowledgebase_details_by_id($knowledgebase_id);
			$this->_response["data"] = $data;
			$this->_response["message"] = "knowledgebase updated successfully";
			$this->set_response($this->_response);
		}
	}

	/**
	 * GET KNOWLEDGEBASE LIST API
	 */
	public function get_knowledgebase_list_post() {
		$this->_response["service_name"] = "admin/knowledgebase/get_knowledgebase_list";
		// $limit = 25;
		// $offset = 0;
		// $keyword = '';
		// $filterBy = '';
		// $sortField = '';
		// $sortOrder = '';
		// if ($this->post('limit')) {
		// 	$limit = $this->post('limit');
		// }
		// if ($this->post('offset')) {
		// 	$offset = $this->post('offset');
		// }
		// if ($this->post('keyword')) {
		// 	$keyword = $this->post('keyword');
		// }
		// if ($this->post('filterBy')) {
		// 	$filterBy = $this->post('filterBy');
		// }

		// $sort = safe_array_key($this->_data, "sort", []);
		// if (isset($this->_data['sort']) && !empty($this->_data['sort']) && isset($this->_data['sort']) && !empty($this->_data['sort'])) {
		// 	$sortField = safe_array_key($sort, 'sortBy', 'name');
		// 	$sortOrder = safe_array_key($sort, 'sortOrder', 'ASC');
		// }

		$keyword = safe_array_key($this->_data, "keyword", "");
		$pagination = safe_array_key($this->_data, "pagination", []);
		$limit = safe_array_key($pagination, "limit", 10);
		$offset = safe_array_key($pagination, "offset", 0);
		$sort_by = safe_array_key($this->_data, "sort_by", []);
		$sort_field = safe_array_key($sort_by, "sort_field", '');
		$sort_order = safe_array_key($sort_by, "sort_order", '');

		$list = $this->knowledgebase_model->get_knowledgebase_list($limit, $offset, $keyword, $sort_field, $sort_order);
		$count = $this->knowledgebase_model->get_knowledgebase_list(0, 0, $keyword, $sort_field, $sort_order);
		$this->_response["data"] = $list;
		$this->_response["counts"] = $count;
		$this->set_response($this->_response);
	}

	/**
	 * CALLBACKS
	 */

	public function categories_get() {
		$this->_response["service_name"] = "admin/knowledgebase/categories";

		$session_key = $this->rest->key;
		$user = $this->app->user_data($session_key);
		$user_id = $this->rest->user_id;
		if($user['user_type'] === 'USER' && ($user['user_role'] == 'USER_INDIVIDUAL_TEAM' || $user['user_role'] == 'USER_INDIVIDUAL_OWNER')) {
			if ($user['user_role'] == 'USER_INDIVIDUAL_TEAM') {
				$user_plan = $this->app->getPlanDetailsById($user['added_by']);
				$pricing_plan_id = safe_array_key($user_plan, "pricing_plan_id", "");
				$this->_response["data"] = $this->knowledgebase_model->get_categories_by_id($pricing_plan_id);
				$this->set_response($this->_response);
			} else {
				$user_plan = $this->app->getPlanDetailsById($user_id);
				$pricing_plan_id = safe_array_key($user_plan, "pricing_plan_id", "");
				$this->_response["data"] = $this->knowledgebase_model->get_categories_by_id($pricing_plan_id);
				$this->set_response($this->_response);
			}
		} else {
			$this->_response["data"] = $this->app->get_rows_with_order('category_master', 'name, category_id AS id, category_guid', [], 'name', 'ACS');
			$this->set_response($this->_response);
		}

		// if ($user['plan_name'] === 'ESSENTIAL') {
		// 	$category = $this->app->get_row('category_master', 'category_id', ['name' => 'How-to']);
		// 	$category_id = safe_array_key($category, "category_id", "");
		// }

		// $this->_response["data"] = $this->app->get_rows_with_order('category_master', 'name, category_id AS id, category_guid', [], 'name', 'ACS');
		// $this->set_response($this->_response);
	}

	public function user_knowledgebase_list_post() {
		$this->_response["service_name"] = "admin/knowledgebase/user_knowledgebase_list";
		$this->form_validation->set_rules('keyword', 'keyword', 'trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$session_key = $this->rest->key;
			$keyword = safe_array_key($this->_data, "keyword", "");
			$pagination = safe_array_key($this->_data, "pagination", []);
			$limit = safe_array_key($pagination, "limit", 10);
			$offset = safe_array_key($pagination, "offset", 0);
			$sort_by = safe_array_key($this->_data, "sort_by", []);
			$column_name = safe_array_key($sort_by, "column_name", 'first_name');
			$order_by = safe_array_key($sort_by, "order_by", 'acs');
			$filters = safe_array_key($this->_data, "filters", []);
			$category_guid = safe_array_key($filters, "category", '');
			$category = $this->app->get_row('category_master', 'category_id', ['category_guid' => $category_guid]);
			$category_id = safe_array_key($category, "category_id", "");
			$user = $this->app->user_data($session_key);
			// if ($user['plan_name'] === 'ESSENTIAL') {
			// 	$category = $this->app->get_row('category_master', 'category_id', ['name' => 'How-to']);
			// 	$category_id = safe_array_key($category, "category_id", "");
			// }
			$categories = [];
			$pricing_plan_id = '';
			if($user['user_type'] === 'USER' && ($user['user_role'] == 'USER_INDIVIDUAL_TEAM' || $user['user_role'] == 'USER_INDIVIDUAL_OWNER')) {
				if ($user['user_role'] == 'USER_INDIVIDUAL_TEAM') {
					$user_plan = $this->app->getPlanDetailsById($user['added_by']);
					$pricing_plan_id = safe_array_key($user_plan, "pricing_plan_id", "");
					$category_array = $this->knowledgebase_model->get_categories_by_id($pricing_plan_id);
					foreach ($category_array as $key => $value) {
						$categories[] = $value['id'];
					}
				} else {
					$user_plan = $this->app->getPlanDetailsById($user_id);
					$pricing_plan_id = safe_array_key($user_plan, "pricing_plan_id", "");
					$this->knowledgebase_model->get_categories_by_id($pricing_plan_id);
					$category_array = $this->knowledgebase_model->get_categories_by_id($pricing_plan_id);
					foreach ($category_array as $key => $value) {
						$categories[] = $value['id'];
					}
				}
			} else {
				$category_array = $this->app->get_rows_with_order('category_master', 'name, category_id AS id, category_guid', [], 'name', 'ACS');
				foreach ($category_array as $key => $value) {
					$categories[] = $value['id'];
				}
			}

			$this->_response["data"] = $this->knowledgebase_model->user_knowledgebase_list($user_id, $keyword, $limit, $offset, $column_name, $order_by, $categories, $category_id, $pricing_plan_id);
			$this->_response["counts"] = $this->knowledgebase_model->user_knowledgebase_list($user_id, $keyword, 0, 0, $column_name, $order_by, $categories, $category_id, $pricing_plan_id);
			$this->set_response($this->_response);
		}
	}

	public function user_knowledgebase_detail_post() {
		$this->_response["service_name"] = "admin/knowledgebase/user_knowledgebase_detail";
		$this->form_validation->set_rules('knowledgebase_id', 'knowledgebase id', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$knowledgebase_guid = safe_array_key($this->_data, "knowledgebase_id", "");
			// $knowledgebase_id = get_detail_by_guid($knowledgebase_guid, 'knowledgebase');
			$this->_response["data"] = $this->knowledgebase_model->get_user_knowledgebase_detail($knowledgebase_guid);
			$this->set_response($this->_response);
		}
	}

	public function user_knowledgebase_related_stuff_list_post() {
		$this->_response["service_name"] = "admin/knowledgebase/user_knowledgebase_related_stuff_list";
		$this->form_validation->set_rules('knowledgebase_id', 'knowledgebase id', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$user_id = $this->rest->user_id;
			$knowledgebase_guid = safe_array_key($this->_data, "knowledgebase_id", "");
			$knowledgebase_id = get_detail_by_guid($knowledgebase_guid, 'knowledgebase');
			// $category_id = get_detail_by_guid($knowledgebase_guid, 'knowledgebase', 'category_id');
			$pagination = safe_array_key($this->_data, "pagination", []);
			$limit = safe_array_key($pagination, "limit", 4);
			$offset = safe_array_key($pagination, "offset", 0);
			$sort_by = safe_array_key($this->_data, "sort_by", []);
			$column_name = safe_array_key($sort_by, "column_name", '');
			$order_by = safe_array_key($sort_by, "order_by", '');

			$categories = $this->app->get_rows('knowledgebase_category', 'category_id', ['knowledgebase_id' => $knowledgebase_id]);
			$category_ids = !empty($categories) ? array_pluck($categories, 'category_id') : [0];

			$this->_response["data"] = $this->knowledgebase_model->related_stuff_list($user_id, $knowledgebase_guid, $category_ids, $limit, $offset, $column_name, $order_by);
			$this->_response["counts"] = $this->knowledgebase_model->related_stuff_list($user_id, $knowledgebase_guid, $category_ids, 0, 0, $column_name, $order_by);
			$this->set_response($this->_response);
		}
	}

	public function change_status_post() {
		$this->_response["service_name"] = "admin/knowledgebase/change_status";
		$this->form_validation->set_rules('knowledgebase_id', 'Knowledgebase Id', 'trim|required');
		$this->form_validation->set_rules("status", "Status", "trim|required|in_list[ACTIVE,INACTIVE]");
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->_response["message"] = current($errors);
			$this->_response["errors"] = $errors;
			$this->set_response($this->_response, REST_Controller::HTTP_FORBIDDEN);
		} else {
			$knowledgebase_guid = safe_array_key($this->_data, "knowledgebase_id", "");
			$status = safe_array_key($this->_data, "status", "");
			$this->knowledgebase_model->change_status($knowledgebase_guid, $status);
			$this->_response['message'] = "Updated successfully";
			$this->set_response($this->_response);
		}
	}
}