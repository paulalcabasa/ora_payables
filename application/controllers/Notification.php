<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('notification_model');
		$this->load->model('ppr_requests_model');
		$this->load->helper('string_helper');
	}

	public function index(){
	
	}

	public function send_pending_emails(){
		$this->load->library('PHPMailerLib');
		$list_of_email = $this->notification_model->get_pending_emails();

		foreach($list_of_email as $email){

			$ppr_header_details = $this->ppr_requests_model->get_ppr_header_details($email->PPR_HEADER_ID);

			$to_email = explode(";",$email->TO_EMAIL);
			$cc_email = explode(";",$email->CC_EMAIL);
			$bcc_email = explode(";",$email->BCC_EMAIL);
		
			//PHPMailer Object
			$email_data = array(
				'msg_header' => $email->MESSAGE1,
				'ppr_no' => $email->PPR_HEADER_ID,
				'ppr_header_details' => $ppr_header_details
			);
			$mail = new PHPMailerLib;	
			foreach($to_email as $to){
				$mail->addAddress($to);
			}
			foreach($cc_email as $cc){
				$mail->addCC($cc);
			}
			foreach($bcc_email as $bcc){
				$mail->addBCC($bcc);
			}
		
			$mail->Subject = $email->SUBJECT;
			$mail->Body = $this->load->view($email->MAIL_TEMPLATE_FILENAME,$email_data,TRUE);
		
			if(!$mail->send()) {
				$error_params = array(
					'Notification', // controller
					'send_pending_emails', // method
					"Mailer Error: " . $mail->ErrorInfo
				);
				$this->ppr_requests_model->insert_error_logs($error_params);
			} 
			else {
				$mail_params = array(
					'Y',
					$email->NOTIF_ID
				);
			  //  $this->notification_model->update_email_status($mail_params);
			}
			
		}
	}

}
