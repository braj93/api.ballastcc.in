<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Feed extends CI_Controller {

	function __construct() {
		// Construct the parent class
		parent::__construct();
		$this->load->helper('xml');
        $this->load->helper('text');
	}

	function index(){
        $data['feed_name'] = site_url();
        $data['encoding'] = 'utf-8';
        $data['feed_url'] = site_url('feed');
        $data['page_language'] = 'en-en';

		$this->load->model("users_model");
		$data['users'] = $this->users_model->get_recent_users_list();    
        header("Content-Type: application/rss+xml");
        $this->load->view('feed/rss', $data);
    }
}