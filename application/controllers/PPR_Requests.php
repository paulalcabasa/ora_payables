<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PPR_Requests extends MY_Controller {

	private $user_type;
	private $system_id;
	public function __construct(){
		parent::__construct();
		$this->load->model('supplier_model');
		$this->load->model('ap_invoices_model');
		$this->load->model('ppr_requests_model');
		$this->load->model('bank_model');
		$this->load->model('person_model');
		$this->load->helper('encryption');
		//$this->load->helper('string');
		$this->load->helper('date_formatter');
		$this->system_id = 1;
		$user_access = $this->session->userdata('fnbi_system_access');
	
		foreach($user_access as $row){
			if($row->SYSTEM_ID == $this->system_id){
				$this->user_type = $row->USER_TYPE_NAME;
			}
		}
	}

	public function index(){
		$user_access = $this->session->userdata('fnbi_system_access')[0];
		if($user_access->SYSTEM_ID == 1 && $user_access->USER_TYPE_NAME == "Administrator"){
			redirect('ppr_requests/all_ppr_requests');
		}
		else if($user_access->SYSTEM_ID == 1 && $user_access->USER_TYPE_NAME == "TreasuryPayer"){
			redirect('ppr_requests/all_ppr_requests');
		}
	}

	public function create_request(){
		$data['title'] = 'Payment Process Request';
		$data['content'] = 'ppr_requests/create_request_view';	
		$this->load->view('include/template',$data);
	}

	public function ajax_search_supplier(){
		$return_arr = array();
		$data =  $this->supplier_model->get_supplier_by_name($this->input->get('q',TRUE));
		foreach($data as $s){
			$row_array = array(
							'id'=>$s->SEGMENT1,
							 'text' => $s->VENDOR_NAME
						);
			array_push($return_arr,$row_array);
		}
		echo json_encode($return_arr);
	}

	public function ajax_generate_invoices(){
		$supplier_id = $this->input->post('supplier_id') == "" ? NULL : $this->input->post('supplier_id');
		$due_date = $this->input->post('due_date') == "" ? NULL : $this->input->post('due_date');
		$supplier_site_id = $this->input->post('supplier_site_id') == "" ? NULL : $this->input->post('supplier_site_id');
		$data['invoices'] = $this->ap_invoices_model->get_unpaid_invoices($supplier_id,$supplier_site_id,$due_date);
		echo $this->load->view('ppr_requests/ap_invoices_table_view',$data,TRUE);
	}

	public function ajax_save_request(){
		// error flag
		$is_error = false;
		// output message for the process of insertion
		$message = "";
		// fetch supplier id from select2
		$supplier_id = $this->input->post('supplier_id');
		$supplier_name = $this->input->post('supplier_name');
		$supplier_site_id = $this->input->post('supplier_site_id');

		// fetch due date 
		$due_date = $this->input->post('due_date');
		$planned_pay_date = $this->input->post('planned_pay_date');

		// fetch selected invoices in JSON format
		$selected_invoices = json_decode($this->input->post('selected_invoices'));	
		// get current user who is logged in
		$user_id = $this->session->userdata('fnbi_user_id');
		// process insertion of payment process request id
		$ppr_header_id = 0;
		$ppr_header_params = array(
								1, // NEW status
							 	$user_id,
							 	$due_date,
							 	$planned_pay_date,
							 	$supplier_id,
							 	$supplier_name,
							 	$supplier_site_id
							 );
		$insert_ppr_header = $this->ppr_requests_model->insert_ppr_headers($ppr_header_params);
		// if insertion in ppr headers is success 
		if($insert_ppr_header != false){
			// get current value of the sequence for ppr_header_id (Sequence name : IPC.PPR_HEADERS_SEQ) 
			$ppr_header_id = $this->ppr_requests_model->get_current_ppr_header_id();
			// perform loop in selected invoices
			foreach($selected_invoices as $inv){
				$params = array(
					$ppr_header_id,
					$inv->invoice_id,
					$inv->invoice_num,
					$inv->doc_sequence_value,
					$inv->org_id,
					5,// selected status
					$user_id
				);
				// fetch result of inesrtion in lines
				$insert_ppr_lines = $this->ppr_requests_model->insert_ppr_lines($params);
				// if there were errors in the insertion, log an error in the error logs
				if($insert_ppr_lines == false){
					$error_params = array(
						'PPR_Requests', // controller
						'ajax_save_request', // method
						'Error in inserting data in PPR_REQUEST_LINES. Invoice_id : ' . $inv->invoice_id
					);
					$this->ppr_requests_model->insert_error_logs($error_params);
					// store error into messages
					$message .= 'Error in inserting data in PPR_REQUEST_LINES. Invoice_id : ' . $inv->invoice_id;
				} // if($insert_ppr_lines == false){
			} // foreach($selected_invoices as $inv){
		} // if($insert_ppr_header != false){
		else {
			$error_params = array(
								'PPR_Requests', // controller
								'ajax_save_request', // method
								'Error in inserting data in PPR_REQUEST_HEADERS'
							);
			$this->ppr_requests_model->insert_error_logs($error_params);
			// store error into messages
			$message .= 'Error in inserting data in PPR_REQUEST_HEADERS';
		}
		$message .= "Your payment process request was sucessfully created! <br/> 
					Payment Process Request ID is : <strong><u>" . sprintf('%05d',$ppr_header_id) . "</u></strong><br/>
					<a href='ppr_request_details/".encode_string($ppr_header_id)."'>Click here to review your request</a>";
		$this->session->set_flashdata('ppr_request_message',$message);
		//redirect('ppr_requests/create_request');
	}

	public function ppr_request_details(){
		$this->load->model('check_model');
		$ppr_header_id = decode_string($this->uri->segment(3));
		$ppr_header_details = $this->ppr_requests_model->get_ppr_header_details($ppr_header_id);
		$user_details = $this->get_user_details();
		// check for more approvers
		// selected invoices params , status_id = 5
		$selected_params = array($ppr_header_id,5);
		$selected_invoices = $this->ppr_requests_model->get_ppr_line_details($selected_params);
		// removed invoices params , status_id = 6
		$removed_params = array($ppr_header_id,6);
		$removed_invoices = $this->ppr_requests_model->get_ppr_line_details($removed_params);
	/*	$check_details = $this->check_model->get_check_details(array($ppr_header_details->AP_CHECK_VOUCHER_NO,$ppr_header_details->BANK_ACCOUNT_NUM));*/
		$data['title'] = 'Payment Process Request';
		$data['ppr_header_details'] = $ppr_header_details;
		$data['selected_invoices'] = $selected_invoices;
	//	$data['check_details'] = $check_details;
		$data['removed_invoices'] = $removed_invoices;
		$data['user_details'] = $this->get_user_details();
		$data['user_type'] = $this->user_type;
		$data['user_details'] = $user_details;
		$data['content'] = 'ppr_requests/ppr_request_details_view';	
		$this->load->view('include/template',$data);
	}

	public function ajax_update_request(){
		// output message for the process of insertion
		$message = "";
		// fetch supplier id from select2
		$status_id = $this->input->post('status_id');
		// fetch due date 
		$ppr_header_id = $this->input->post('ppr_header_id');
		// fetch selected invoices in JSON format
		$ppr_lines = json_decode($this->input->post('ppr_lines'));	
		// get current user who is logged in
		$user_id = $this->session->userdata('fnbi_user_id');
		
		foreach($ppr_lines as $line){
			$params = array(
				$status_id,
				$user_id,
				$line->ppr_line_id,
				$line->invoice_id
			);
			
			// fetch result of inesrtion in lines
			$this->ppr_requests_model->update_ppr_line_status($params);
		} // foreach($selected_invoices as $inv){
		
		// set message display for updating
		$message = "Your payment process request was succesfully updated";
		$this->session->set_flashdata('ppr_request_message',$message);
		// return the encrypted payment process request id for page reload
		echo encode_string($ppr_header_id);
	}

	public function ajax_submit_request(){
		// get current user who is logged in
		$user_id = $this->session->userdata('fnbi_user_id');
		$status_id = $this->input->post('status_id');
		$ppr_header_id = $this->input->post('ppr_header_id');
		$ppr_lines = json_decode($this->input->post('ppr_lines'));	
		$ppr_header_details = $this->ppr_requests_model->get_ppr_header_details($ppr_header_id);
		// step 1. Update request status to "For Approval"
		$update_status_params = array(
			$status_id, // for approval status id
			$user_id,
			$ppr_header_id
		);
		$this->ppr_requests_model->update_ppr_header_status($update_status_params);

		/* Update payment details */
		
		foreach($ppr_lines as $line){
			$params = array(
				$line->payment_amount,
				$user_id,
				$line->ppr_line_id
			);
			// fetch result of inesrtion in lines
			$this->ppr_requests_model->update_payment_amount($params);
		} // foreach($selected_invoices as $inv){
		

		// step 2. Insert approvers to PPR_APROVERS table
		// load model for approver
		
		/* Disabled by paul
		   No approval workflow is applicable
		$this->load->model('approver_model');
		$this->load->model('notification_model');
		

		$default_approvers = $this->approver_model->get_default_approvers();
		// loop on each approver
		foreach($default_approvers as $approver){
			// params for approver
			$approver_params = array(
				$approver->APPROVAL_SEQUENCE_NO,
				$ppr_header_id,
				$approver->APPROVER_ID,
				$status_id
			);
			// insert into approvers table
			$this->approver_model->insert_ppr_approval($approver_params);

			if($approver->APPROVAL_SEQUENCE_NO == 1){ // only send email notif to the first approver
				// params for mail notification
				$mail_subject = "Payment Process Request - For approval";
				$mail_type = "mail_for_approver";
				$message_sent_flag = 'N';
				$mail_template_dir = "mail_templates/ppr_request_approval_view";
				$email_notif_params = array(
					'notification1@isuzuphil.com',
					$approver->EMAIL_ADDRESS,
					$mail_subject,
					$mail_type,
					$message_sent_flag,
					$mail_template_dir,
					$ppr_header_id,
					'Payment Process Request No . ' . $ppr_header_id . ' has been submitted for your approval', // message 1
					null, // message 2
					null, // message 3
					null, // message 4
					null, // message 5
					$ppr_header_details->REQUESTOR_EMAIL, // cc email
					'paul-alcabasa@isuzuphil.com' // bcc email

				);
				$this->notification_model->insert_email_notif($email_notif_params);
			}
		}	
		*/
		// set message display notification
		$message = "Your payment process request has been submitted.";
		$this->session->set_flashdata('ppr_request_message',$message);
		echo encode_string($ppr_header_id);
	}

	public function all_ppr_requests(){
		$bank_accounts = $this->bank_model->get_bank_accounts();
		$user_details = $this->get_user_details();
		$fsd_signatories = $this->person_model->get_fsd_signatories();
		$data['user_details'] = $user_details;
		
	
		
		$current_person_details = $this->person_model->get_person_details($user_details->user_id);
		$data['user_type'] = $this->user_type;
		$data['bank_accounts'] = $bank_accounts;	
		$data['fsd_signatories'] = $fsd_signatories;	
		$data['current_person_details'] = $current_person_details;	
		$data['title'] = 'Payment Process Request';
		$data['content'] = 'ppr_requests/all_ppr_requests_view';	
		$this->load->view('include/template',$data);
	}

	public function dt_all_ppr_requests(){
    	$start_date = $this->input->post('start_date');
    	$end_date = $this->input->post('end_date');
    	$status_id = $this->input->post('status_id');
    	$user_details = $this->get_user_details();
    	$user_access = $this->session->userdata('fnbi_system_access')[0];
    	$user_type = $this->input->post('user_type');
		//if($user_access->SYSTEM_ID == 1 && $user_access->USER_TYPE_NAME == "Administrator"){
			
    	$params = array(
    		$start_date,
    		$end_date
    	);
    	
    /*	var_dump($user_details);
    	var_dump($user_access);
    	die();*/
   		$ppr_list = $this->ppr_requests_model->get_ppr_requests(
   						$params,
   						$status_id,
   						$user_type, //$user_access->USER_TYPE_NAME,
   						$user_details->user_id
   					);
   		$data['ppr_list'] = $ppr_list;
   		$data['user_details'] = $user_details;
    	echo $this->load->view('ppr_requests/ppr_request_list_view',$data,TRUE); 
    }

    public function ajax_approval_request(){
    	// load models needed for this method
    	$this->load->model('approver_model');
		$this->load->model('notification_model');	
		$msg = "";
    	// get current user who is logged in
		$user_id = $this->session->userdata('fnbi_user_id');
		// approval status id of the user
		$status_id = $this->input->post('approval_status_id');
		if($status_id == 23){
			$msg = "approved";
		}
		else if($status_id == 24){
			$msg = "disapproved";
		}
		// ppr header id
		$ppr_header_id = $this->input->post('ppr_header_id');
		// ppr request details

		$ppr_request_details = $this->ppr_requests_model->get_ppr_header_details($ppr_header_id);

		// update approval status for certain approver
		$approval_params = array(
			$status_id,
			$user_id,
			$ppr_header_id,
			$user_id
		);

		$this->approver_model->update_approval_state($approval_params);

		$current_approver_details = $this->approver_model->get_user_details($user_id);
	
		// sent notification email to requestor and cc the approver
		$mail_subject = "Payment Process Request Approval";
		$mail_type = "mail_to_requestor";
		$message_sent_flag = 'N';
		$mail_template_dir = "mail_templates/ppr_request_approval_view";
		$email_notif_params = array(
			'notification1@isuzuphil.com',
			$ppr_request_details->REQUESTOR_EMAIL,
			$mail_subject,
			$mail_type,
			$message_sent_flag,
			$mail_template_dir,
			$ppr_header_id,
			'Your payment process request has been '.$msg.' by ' . $current_approver_details->FULL_NAME . ".",
			null,
			null,
			null,
			null,
			$current_approver_details->EMAIL_ADDRESS, // cc email
			'paul-alcabasa@isuzuphil.com'// bcc email
		);

		$this->notification_model->insert_email_notif($email_notif_params);
    	// check for more approvers
    	$next_approver = $this->approver_model->get_pending_approvers($ppr_header_id);
    	if(empty($next_approver)){ // set request as approved since there are no next approvers
			$update_status_params = array(
				$status_id, // for approval status id
				$user_id,
				$ppr_header_id
			);
			$this->ppr_requests_model->update_ppr_header_status($update_status_params);
			// send to treasury payer
			$mail_subject = "Payment Process Request - For Payment";
			$mail_type = "mail_for_treasury_payer";
			$message_sent_flag = 'N';
			$mail_template_dir = "mail_templates/ppr_request_approval_view";
			$cc_email = $ppr_request_details->REQUESTOR_EMAIL . ";" . $current_approver_details->EMAIL_ADDRESS;
			$email_notif_params = array(
				'notification1@isuzuphil.com',
				'zandra-dela-pena@isuzuphil.com',// to email
				$mail_subject,
				$mail_type,
				$message_sent_flag,
				$mail_template_dir,
				$ppr_header_id,
				'Payment Process Request No . ' . $ppr_header_id . ' has been submitted for payment.', // message 1
				null, // message 2
				null, // message 3
				null, // message 4
				null, // message 5
				$cc_email, // cc email
				'paul-alcabasa@isuzuphil.com'  // bcc email
			);
			$this->notification_model->insert_email_notif($email_notif_params);
    	}
    	else { // if there are more approvers, send email to next approver
    		// params for mail notification
			$mail_subject = "Payment Process Request - For approval";
			$mail_type = "mail_for_approver";
			$message_sent_flag = 'N';
			$mail_template_dir = "mail_templates/ppr_request_approval_view";
			$email_notif_params = array(
				'notification1@isuzuphil.com',
				$next_approver[0]->EMAIL_ADDRESS,
				$mail_subject,
				$mail_type,
				$message_sent_flag,
				$mail_template_dir,
				$ppr_header_id,
				'Payment Process Request No . ' . $ppr_header_id . ' has been submitted for your approval', // message 1
				null, // message 2
				null, // message 3
				null, // message 4
				null, // message 5
				null, // cc email
				'paul-alcabasa@isuzuphil.com'  // bcc email
			);
			$this->notification_model->insert_email_notif($email_notif_params);
    	}

    	// set message display for updating
		$message = "Payment process request has been succesfully " . $msg;
		$this->session->set_flashdata('ppr_request_message',$message);
		// return the encrypted payment process request id for page reload
		echo encode_string($ppr_header_id);
    }

    public function ajax_cancel_request(){
		// get current user who is logged in
		$user_id = $this->session->userdata('fnbi_user_id');
		$status_id = $this->input->post('status_id');
		$ppr_header_id = $this->input->post('ppr_header_id');
		// step 1. Update request status to "For Approval"
		$update_status_params = array(
			$status_id, // for approval status id
			$user_id,
			$ppr_header_id
		);
		$this->ppr_requests_model->update_ppr_header_status($update_status_params);
			
		// set message display notification
		$message = "Your payment process request has been cancelled.";
		$this->session->set_flashdata('ppr_request_message',$message);
		echo encode_string($ppr_header_id);
	}

	public function ajax_search_check(){
		$this->load->model('check_model');
		$doc_sequence_value = $this->input->post('check_voucher_no');
		$bank_account_num = $this->input->post('bank_account_num');
		$check_params = array($doc_sequence_value,$bank_account_num);
		$check_details = $this->check_model->get_check_details($check_params);
		if(!empty($check_details)){
			echo json_encode($check_details);
		}
		else {
			echo "invalid";
		}	
	}

	public function ajax_save_payment_details(){
		$this->load->model('notification_model');
		$user_details = $this->get_user_details();
		$ppr_header_id = $this->input->post('ppr_header_id');
		$ppr_header_details = $this->ppr_requests_model->get_ppr_header_details($ppr_header_id);
		$params = array(
			$this->input->post('check_voucher_no'),
			$this->input->post('bank_account_num'),
			$this->input->post('bank_account_name'),
			$this->input->post('bank_name'),
			$user_details->user_id,
			$this->input->post('ppr_header_id')
		);
		$this->ppr_requests_model->update_ppr_payment_details($params);

		$update_status_params = array(
			41, // PAID status id
			$user_details->user_id,
			$this->input->post('ppr_header_id')
		);
		
		$this->ppr_requests_model->update_ppr_header_status($update_status_params);

		$to_email = $ppr_header_details->REQUESTOR_EMAIL;
		// save email notif
		$mail_subject = "Payment Process Request - Payment";
		$mail_type = "mail_to_all";
		$message_sent_flag = 'N';
		$mail_template_dir = "mail_templates/ppr_request_payment_view";
		$email_notif_params = array(
			'notification1@isuzuphil.com',
			$to_email,
			$mail_subject,
			$mail_type,
			$message_sent_flag,
			$mail_template_dir,
			$ppr_header_id,
			'Payment Process Request No . ' . $ppr_header_id . ' has been updated.', // message 1
			null, // message 2
			null, // message 3
			null, // message 4
			null, // message 5
			'zandra-dela-pena@isuzuphil.com', // cc email treasury payer
			'paul-alcabasa@isuzuphil.com'  // bcc email
		);
		$this->notification_model->insert_email_notif($email_notif_params);

		echo "success";
	}

	public function ajax_get_payment_details(){
		$this->load->model('check_model');
		$ppr_header_id = $this->input->post('ppr_header_id');
		$ppr_header_details = $this->ppr_requests_model->get_ppr_header_details($ppr_header_id);
		$check_params = array($ppr_header_details->AP_CHECK_VOUCHER_NO,$ppr_header_details->BANK_ACCOUNT_NUM);
		$check_details = $this->check_model->get_check_details($check_params);
		echo json_encode(
				array(
					'ppr_header_details' => ($ppr_header_details),
					'check_details' => (empty($check_details) ?  "" : $check_details[0])
				)
			 );
	}


	public function ajax_add_to_request(){
		// error flag
		$is_error = false;
		// output message for the process of insertion
		$message = "";
		// fetch supplier id from select2
		$ppr_header_id = $this->input->post('ppr_header_id');
		// fetch selected invoices in JSON format
		$selected_invoices = json_decode($this->input->post('selected_invoices'));	
		// get current user who is logged in
		$user_id = $this->session->userdata('fnbi_user_id');
		

		// perform loop in selected invoices
		foreach($selected_invoices as $inv){
			$params = array(
				$ppr_header_id,
				$inv->invoice_id,
				$inv->invoice_num,
				$inv->doc_sequence_value,
				$inv->org_id,
				5,// selected status
				$user_id
			);
			// fetch result of inesrtion in lines
			$insert_ppr_lines = $this->ppr_requests_model->insert_ppr_lines($params);
			// if there were errors in the insertion, log an error in the error logs
			if($insert_ppr_lines == false){
				$error_params = array(
					'PPR_Requests', // controller
					'ajax_save_request', // method
					'Error in inserting data in PPR_REQUEST_LINES. Invoice_id : ' . $inv->invoice_id
				);
				$this->ppr_requests_model->insert_error_logs($error_params);
				// store error into messages
				$message .= 'Error in inserting data in PPR_REQUEST_LINES. Invoice_id : ' . $inv->invoice_id;
			} // if($insert_ppr_lines == false){
		} // foreach($selected_invoices as $inv){
		
		$message .= "Your payment process request was sucessfully updated!";
		$this->session->set_flashdata('ppr_request_message',$message);
		echo encode_string($ppr_header_id);
	}

	public function ajax_get_supplier_sites(){
		$supplier_id = $this->input->post('supplier_id');
		$supplier_sites = $this->supplier_model->get_supplier_sites($supplier_id);
		foreach($supplier_sites as $site){
			echo '<option value="'.$site->VENDOR_SITE_ID.'">' . $site->VENDOR_SITE_CODE . '</option>';
		}
	}




}
