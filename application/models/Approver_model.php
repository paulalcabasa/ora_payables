<?php

class Approver_model extends CI_Model {
	
	private $oracle = NULL;
	
	public function __construct(){
		parent::__construct();
		$this->oracle = $this->load->database('oracle', true);
	}

	public function insert_ppr_approval($params){
		/*$sql = "INSERT INTO IPC.IPC_PPR_APPROVAL (
				    approval_id,
				    approval_sequence_no,
				    ppr_header_id,
				    approver_id,
				    status_id,
				    date_created
				)
				SELECT  IPC.PPR_APPROVAL_SEQ.NEXTVAL,
						pda.approval_sequence_no,
						?,
						pda.approver_id,
						?,
						SYSDATE
				FROM IPC.IPC_PPR_DEFAULT_APPROVER pda";
		*/
		$sql = "INSERT INTO IPC.IPC_PPR_APPROVAL (
				    approval_id,
				    approval_sequence_no,
				    ppr_header_id,
				    approver_id,
				    status_id,
				    date_created
				)
				VALUES (
					IPC.PPR_APPROVAL_SEQ.NEXTVAL,
					?,
					?,
					?,
					?,
					SYSDATE
				)";
		$this->oracle->query($sql,$params);
	}

	public function get_default_approvers(){
		$sql = "SELECT pda.default_approver_id,
                       pda.approver_id,
                       pda.approval_sequence_no,
                       fu.email_address
                FROM IPC.IPC_PPR_DEFAULT_APPROVER pda LEFT JOIN fnd_user fu
                       ON fu.user_id = pda.approver_id";
		$query = $this->oracle->query($sql);
		return $query->result();
	}

	public function update_approval_state($params){
		$sql = "UPDATE IPC.IPC_PPR_APPROVAL
				SET status_id = ?,
					approved_by = ?,
					date_approved = SYSDATE
				WHERE ppr_header_id = ?
					  AND approver_id = ?";
		$this->oracle->query($sql,$params);

	}

	public function get_user_details($user_id){
		$sql = "SELECT fu.email_address,
	             ppf.full_name
	                FROM fnd_user fu INNER JOIN per_all_people_f ppf
	                    ON fu.employee_id = ppf.person_id
	                WHERE fu.user_id = ?";
		$query = $this->oracle->query($sql,$user_id);
		$data = $query->result();
		return $data[0];
	}

	public function get_pending_approvers($ppr_header_id){
		$sql = "SELECT *
				FROM (SELECT ppa.approval_id,
				           ppa.approval_sequence_no,
				           ppa.approver_id,
				           ppa.status_id,
				           ppa.ppr_header_id,
				           fu.email_address
				FROM IPC.IPC_PPR_APPROVAL ppa LEFT JOIN fnd_user fu
                       ON fu.user_id = ppa.approver_id
				WHERE ppr_header_id = ?
				             AND status_id = 21
				ORDER BY ppa.approval_sequence_no DESC)
				WHERE rownum = 1";
		$query = $this->oracle->query($sql,$ppr_header_id);
		$data = $query->result();
		return $data;
	}


}