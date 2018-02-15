<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$user_details = $this->get_user_details();
		if($user_details->user_type == "Administrator"){
            redirect(base_url()."ppr_requests/all_ppr_requests");
        }
        else if($user_details->user_type == "Regular"){
            redirect(base_url()."ppr_requests/all_ppr_requests");

        }
        else if($user_details->user_type == "TreasuryPayer"){
            redirect(base_url()."ppr_requests/all_ppr_requests");
        }
	/*	$data['title'] = 'Dashboard';
		$data['content'] = 'dashboard_view';
		$this->load->view('include/template',$data);*/
	}
}
