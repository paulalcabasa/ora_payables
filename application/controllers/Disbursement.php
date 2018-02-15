<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Disbursement extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('date_formatter');
        $this->load->model('disbursement_model');
	}


	public function index(){
		
	/*	$data['title'] = 'Dashboard';
		$data['content'] = 'dashboard_view';
		$this->load->view('include/template',$data);*/
	}

	public function disbursement_list(){
		// initialize
     	$current_date = date('Y-m-d');
        $current_month_range = rangeWeek($current_date);
        $start_date = $this->input->post('start_date') == "" ? $current_month_range['start'] : $this->input->post('start_date');
        $end_date = $this->input->post('end_date') == "" ? $current_month_range['end'] : $this->input->post('end_date');
        $release_status = $this->input->post('release_status');
        $check_number = $this->input->post('check_number');
        $params = array(
        	format_oracle_date($start_date),
        	format_oracle_date($end_date)
        );
        $disbursement_list = $this->disbursement_model->get_disbursement_list($params,$release_status,$check_number);
		$data = array(
			"title" 	=> "Payments",
			'start_date' => $start_date,
			'end_date' => $end_date,
			'release_status' => $release_status,
			'check_number' => $check_number,
			"content" => "disbursement/disbursement_list_view",
			'disbursement_list' => $disbursement_list
		);
		$this->load->view('include/template',$data);
	}

	public function ajax_save_or_details(){
		$official_receipt_id = $this->input->post('official_receipt_id');
		$operation = $this->input->post('operation');
		$or_number = $this->input->post('or_number');
		$check_id = $this->input->post('check_id');
		$or_date = $this->input->post('or_date');
		$remarks = $this->input->post('remarks');
		$check_number = $this->input->post('check_number');
		$check_voucher_no = $this->input->post('check_voucher_no');
		$user_id = $this->session->userdata('fnbi_user_id');
		if($operation == "insert"){
			$params = array(
				$or_number,
				$or_date,
				$check_id,
				$check_voucher_no,
				$check_number,
				$remarks,
				$user_id
			);
			
			$official_receipt_id = $this->disbursement_model->insert_or_details($params);

		}
		else if($operation == "update"){
			$params = array(
				$or_number,
				$or_date,
				$remarks,
				$user_id,
				$official_receipt_id
			);
			$this->disbursement_model->update_or_details($params);
		}
		
		echo json_encode(
				array(
					"message" => "Official receipt successfully updated.",
					"entry_date" => date('m/d/Y'),
					"official_receipt_id" => $official_receipt_id
				)
			);
		
	}

	public function vat_list(){

		// initialize
     	$current_date = date('Y-m-d');
        $current_week_range = rangeWeek($current_date);
        $start_date = $this->input->post('start_date') == "" ? $current_week_range['start'] : $this->input->post('start_date');
        $end_date = $this->input->post('end_date') == "" ? $current_week_range['end'] : $this->input->post('end_date');
       	$invoice_num = $this->input->post('invoice_num');
       	$voucher_no = $this->input->post('voucher_no');
       	$transaction_types = $this->disbursement_model->get_transaction_types();
       	$transaction_type = $this->input->post('sel_transaction_type');
        $params = array(
        	format_oracle_date($start_date),
        	format_oracle_date($end_date)
        );

        $vat_list = array();

        if($start_date != "" && $end_date != ""){
        	$vat_list = $this->disbursement_model->get_vat_list($params,$invoice_num,$voucher_no,$transaction_type);
        }

		$data = array(
			"title" => "VAT Monitoring",
			"content" => "disbursement/vat_list_view",
			"vat_list" => $vat_list,
			"start_date" => $start_date,
			"end_date" => $end_date,
			"voucher_no" => $voucher_no,
			"invoice_num" => $invoice_num,
			"transaction_types" => $transaction_types
		);

		$this->load->view('include/template',$data);


	}

	public function ajax_save_vat_details(){
		$vat_detail_id = $this->input->post('vat_detail_id');
		$application_period = $this->input->post('application_period');
		$ap_line_number = $this->input->post('ap_line_number');
		$ap_dist_line_number = $this->input->post('ap_dist_line_number');
		$ap_voucher_no = $this->input->post('ap_voucher_no');
		$user_id = $this->session->userdata('fnbi_user_id');

		
		// perform insert
		$params = array(
			$ap_voucher_no,
			$ap_line_number,
			$ap_dist_line_number,
			$application_period,
			$user_id
		);
		$this->disbursement_model->insert_vat_details($params);
		
		// if this is update, deactive previous vat details
		if($vat_detail_id != ""){
			$params = array(
				$user_id,
				$vat_detail_id
			);
			$this->disbursement_model->deactivate_vat_detail($params);
		}


		/*echo json_encode(
				array(
					'vat_detail_id' => $vat_detail_id			
				)

			 );
*/
		
	}



}
