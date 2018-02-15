<?php

class Notification_model extends CI_Model {
	
	private $oracle = NULL;
	
	public function __construct(){
		parent::__construct();
		$this->oracle = $this->load->database('oracle', true);
	}

	public function insert_email_notif($params){
		$sql = "INSERT INTO IPC.IPC_PPR_EMAIL_NOTIF (
					notif_id,
					from_email,
					to_email,
					subject,
					mail_type,
					date_created,
					is_message_sent,
					mail_template_filename,
					ppr_header_id,
					message1,
					message2,
					message3,
					message4,
					message5,
					cc_email,
					bcc_email
				)
				VALUES (
					IPC.PPR_NOTIFS_SEQ.NEXTVAL,
					?, -- from email
					?, -- to email
					?, -- subject
					?, -- mail type
					SYSDATE,
					?, -- is message sent
					?, -- mail template filename
					?, -- ppr header id
					?, -- msg1
					?, -- msg2
					?, -- msg3
					?, --msg4
					?, -- msg5
					?, -- cc email
					? -- bcc email
				)";	
		$this->oracle->query($sql,$params);	
	}

	public function get_pending_emails(){
		$sql = "SELECT ipn.notif_id,
				             ipn.from_email,
				             ipn.to_email,
				             ipn.subject,
				             ipn.mail_type,
				             ipn.mail_template_filename,
				             ipn.ppr_header_id,
				             ipn.message1,
				             ipn.message2,
				             ipn.message3,
				             ipn.message4,
				             ipn.message5,
				             ipn.cc_email,
				             ipn.bcc_email
				FROM ipc.ipc_ppr_email_notif ipn
				WHERE 1 = 1
              		  AND ipn.is_message_sent = 'N'
              		  AND ipn.notif_id = 34";
        $query = $this->oracle->query($sql);
        return $query->result();
	}

	public function update_email_status($params){
		$sql = "UPDATE ipc.ipc_ppr_email_notif
				SET is_message_sent = ?,
				    date_sent = SYSDATE
				WHERE notif_id = ?";
		$this->oracle->query($sql,$params);	
	}

}