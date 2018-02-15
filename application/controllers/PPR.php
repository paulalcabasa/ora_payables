<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
	Payment Process Request Controller
*/
class PPR extends MY_Controller { 

	public function __construct(){
		parent::__construct();
		$this->load->model('system_model');
	}

	public function index(){
		$systems_list = $this->system_model->get_all_systems();
	
		$data['title'] = 'Dashboard';
		$data['content'] = 'dashboard_view';
		$data['user_systems_access'] = $this->session->userdata('fnbi_systems_access_list');
		$data['systems_list'] = $systems_list;		
		$this->load->view('include/template',$data);
	}
}
