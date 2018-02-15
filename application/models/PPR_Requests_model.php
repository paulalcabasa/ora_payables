<?php

class PPR_Requests_model extends CI_Model {
	
	private $oracle = NULL;
	
	public function __construct(){
		parent::__construct();
		$this->oracle = $this->load->database('oracle', true);
	}

	public function insert_ppr_headers($params){
	
		$sql = "INSERT INTO IPC.IPC_PPR_HEADERS (
					ppr_header_id,
					ppr_doc_sequence_value,
					status_id,
					created_by,
					date_created,
					due_date,
					planned_pay_date,
					vendor_id,
					vendor_name,
					vendor_site_id
				) 
				VALUES (
					IPC.PPR_HEADERS_SEQ.NEXTVAL,
					IPC.PPR_HEADERS_DOC_SEQ.NEXTVAL,
					?,
					?,
					SYSDATE,
					?,
					?,
					?,
					?,
					?
				)";
		return $this->oracle->query($sql,$params);
	}

	public function insert_ppr_lines($params){
		$sql = "INSERT INTO IPC.IPC_PPR_LINES(
					PPR_LINE_ID,
					PPR_HEADER_ID,
					AP_INVOICE_ID,
					AP_INVOICE_NUM,
					AP_DOCUMENT_SEQUENCE_VALUE,
					ORG_ID,
					STATUS_ID,
					CREATED_BY,
					DATE_CREATED
				)
				VALUES(
					IPC.PPR_LINES_SEQ.NEXTVAL,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					SYSDATE
				)";
		return $this->oracle->query($sql,$params);
	}

	public function get_current_ppr_header_id(){
		$sql = "SELECT IPC.PPR_HEADERS_SEQ.CURRVAL FROM DUAL";
		$query = $this->oracle->query($sql);
		$data = $query->result();
		return $data[0]->CURRVAL;
	}

	public function insert_error_logs($params){
		$sql = "INSERT INTO IPC.IPC_PPR_ERROR_LOGS (
					error_id,
					error_source_controller,
					error_source_method,
					error_description,
					date_logged
				)
				VALUES (
					IPC.PPR_ERRORS_SEQ.NEXTVAL,
					?
					?,
					?,
					SYSDATE
				)";
		$this->oracle->query($sql,$params);
	}

	public function get_current_error_log_id(){
		$sql = "SELECT IPC.PPR_ERRORS_SEQ.CURRVAL FROM DUAL";
		$query = $this->oracle->query($sql);
		$data = $query->result();
		return $data[0]->CURRVAL;
	}

	public function get_ppr_header_details($ppr_header_id){
		$sql = "SELECT ph.ppr_header_id,
				           ph.ppr_doc_sequence_value,
				           ph.status_id,
				           ph.ap_check_voucher_no,
				           TO_CHAR(ph.date_created,'MONTH DD, YYYY') date_created,
				           ps.status_name,
				           ps.description,
				           ph.bank_account_num,
				           ppf.full_name created_by_name,
				           ph.created_by,
				           fu.email_address requestor_email,
				           ph.bank_name,
				           ph.bank_account_name,
				           ph.bank_account_num,
				           ph.ap_check_voucher_no,
				           ph.vendor_name,
				           ph.vendor_id,
				           TO_CHAR(ph.planned_pay_date,'MONTH DD, YYYY') planned_pay_date,
				           ph.due_date,
				           apsa.vendor_site_code
				FROM IPC.IPC_PPR_HEADERS ph LEFT JOIN IPC.IPC_PPR_STATES ps
				            ON PH.STATUS_ID = ps.status_id
				          LEFT JOIN fnd_user fu
				            ON fu.user_id = ph.created_by
				          LEFT JOIN per_all_people_f ppf
				            ON fu.employee_id = ppf.person_id
				          LEFT JOIN ap_supplier_sites_all apsa
				          	ON apsa.vendor_site_id = ph.vendor_site_id
				WHERE 1 = 1
				           AND ph.ppr_header_id = ?";
		$query = $this->oracle->query($sql,$ppr_header_id);
		$data = $query->result();
		return $data[0];
	}

/*  (select abs(sum(amount))
				        from ap_invoice_lines_all
				        where invoice_id = pl.ap_invoice_id
				                   and line_type_lookup_code = 'AWT') wht*/
	public function get_ppr_line_details($params){
		$sql = "SELECT pl.ppr_line_id,
				       pl.ap_document_sequence_value,
				       pl.ap_invoice_id,
				       pl.ap_invoice_num,
				       aps.vendor_name,
				       TO_CHAR (aia.invoice_date, 'MM/DD/YYYY') invoice_date,
				       TO_CHAR (aia.gl_date, 'MM/DD/YYYY') gl_date,
				       aia.invoice_amount,
				       aia.invoice_amount - nvl(aia.total_tax_amount,0) net,
				       (aia.invoice_amount - nvl(aia.amount_paid,0)) balance,
				       TO_CHAR (aia.terms_date + atl.due_days, 'MM/DD/YYYY') due_date,
				       ps.status_name,
				       pl.org_id,
				       aia.total_tax_amount vat,
				       AP_INVOICES_PKG.GET_AMOUNT_WITHHELD (aia.INVOICE_ID) wht,
				       pl.proposed_payment_amount
				  FROM ipc.ipc_ppr_lines pl
				       INNER JOIN apps.ap_invoices_all aia
				          ON aia.invoice_id = pl.ap_invoice_id AND aia.org_id = pl.org_id
				       INNER JOIN apps.ap_suppliers aps
				          ON aps.vendor_id = aia.vendor_id
				       INNER JOIN ap_terms_tl apt
				          ON apt.term_id = aia.terms_id
				       INNER JOIN ap_terms_lines atl
				          ON atl.term_id = apt.term_id
				       INNER JOIN ipc.ipc_ppr_states ps
				          ON ps.status_id = pl.status_id
				 WHERE 1 = 1 
				              AND pl.ppr_header_id = ? 
				              AND pl.status_id = ?
				 ORDER BY pl.ap_invoice_num";
		$query = $this->oracle->query($sql,$params);
		return $query->result();
	}

	public function update_ppr_line_status($params){
		$sql = "UPDATE IPC.IPC_PPR_LINES
				SET status_id = ?,
				 	updated_by = ?,
				 	date_updated = SYSDATE
				 WHERE ppr_line_id = ?
				 	   AND ap_invoice_id = ?";
		$this->oracle->query($sql,$params);
	}

	public function update_ppr_header_status($params){
		$sql = "UPDATE IPC.IPC_PPR_HEADERS
				SET status_id = ?,
					updated_by = ?,
					date_updated = SYSDATE
				WHERE ppr_header_id = ?";
		$this->oracle->query($sql,$params);
	}

	public function get_ppr_requests($params,$status_id,$user_type,$user_id){
		$cond1 = "";

		if($status_id != "all"){
			$cond1 = " AND ph.status_id=" .$status_id;
		}
		$cond2 = "";
		
		if($user_type == "Regular"){
			$cond2 = "AND ph.created_by=" . $user_id;
		}
		//echo $user_type;
	
		$sql = "SELECT  ph.ppr_header_id,
						ph.ppr_doc_sequence_value,
						ph.status_id,
						ps.status_name,
						count(pl.ppr_line_id) total_invoices,
						sum(aia.invoice_amount) total_invoice_amount,
						sum(nvl(aia.invoice_amount,0) - nvl(aia.amount_paid,0)) total_balance_amount,
						ph.ap_check_voucher_no,
						ph.bank_account_num,
						ph.bank_account_name,
						ph.bank_name,
						aca.check_date,
						ph.vendor_name,
						ppf.full_name created_by,
						to_char(ph.date_created,'mm/dd/yyyy') date_created
				FROM ipc.ipc_ppr_headers ph 
		            INNER JOIN ipc.ipc_ppr_states ps
		                ON ph.status_id = ps.status_id
		            INNER JOIN apps.fnd_user usr
		                ON usr.user_id = ph.created_by
		            INNER JOIN per_all_people_f ppf
		                ON ppf.person_id = usr.employee_id
		            LEFT JOIN ipc.ipc_ppr_lines pl
		                ON pl.ppr_header_id = ph.ppr_header_id
		            LEFT JOIN apps.ap_invoices_all aia
		                ON aia.invoice_id = pl.ap_invoice_id
		            LEFT JOIN apps.ap_checks_all aca 
		            	ON aca.bank_account_num = ph.bank_account_num
		            	AND aca.doc_sequence_value = ph.ap_check_voucher_no
				WHERE 1 = 1
		            
		              AND TO_DATE(ph.date_created) BETWEEN ? AND ?
		              {$cond1}
		              {$cond2}
				GROUP BY
	                ph.ppr_header_id,
	                ph.ppr_doc_sequence_value,
	                ph.status_id,
	                ps.status_name,
	                ph.ap_check_voucher_no,
	                ppf.full_name,
	                ph.date_created,
					ph.bank_account_num,
					ph.bank_account_name,
					ph.bank_name,
					ph.vendor_name,
					aca.check_date
				ORDER BY 
					ph.ppr_header_id";
	  
	    $query = $this->oracle->query($sql,$params);
     	//echo $this->oracle->last_query();
	    return $query->result();
	    
	}

	public function update_ppr_payment_details($params){
		$sql = "UPDATE ipc.ipc_ppr_headers
				SET ap_check_voucher_no = ?,
					bank_account_num = ?,
					bank_account_name = ?,
					bank_name = ?,
					updated_by = ?,
					date_updated = SYSDATE
				WHERE ppr_header_id = ?";
		$this->oracle->query($sql,$params);
	}

	public function update_payment_amount($params){
		$sql = "UPDATE IPC.IPC_PPR_LINES
				SET proposed_payment_amount = ?,
				 	updated_by = ?,
				 	date_updated = SYSDATE
				 WHERE ppr_line_id = ?";
		$this->oracle->query($sql,$params);
	}

	public function get_ppr_number($ppr_no,$user_id){
		$this->oracle->select('PPR_HEADER_ID',FALSE);
		$this->oracle->from('IPC.IPC_PPR_HEADERS',FALSE);
		$this->oracle->where('ROWNUM <= 20',NULL,FALSE);
		$this->oracle->where('CREATED_BY',$user_id);
		$this->oracle->like('LPAD(PPR_HEADER_ID,5,0)', strtolower($ppr_no));
		$this->oracle->order_by('PPR_HEADER_ID', 'ASC');
		return $this->oracle->get()->result();
	}

	public function ppr_detailed_summary(){
		$sql = "SELECT ph.ppr_header_id payment_proposal_number,
			           ph.date_created,
			           pl.ap_invoice_num,
			           ph.planned_pay_date,
			           NVL(pl.proposed_payment_amount,aia.invoice_amount) payment_amount
				from ipc.ipc_ppr_headers ph
				            INNER JOIN ipc.ipc_ppr_lines pl
				                ON pl.ppr_header_id = ph.ppr_header_id
				                AND pl.status_id = 5
				            INNER JOIN ap_invoices_all aia
				                ON aia.invoice_id = pl.ap_invoice_id
				where 1 = 1
				            and ph.status_id IN (4) -- SUBMITTED STATE
				            and ph.created_by = 1775 -- USER_ID
				            and TO_DATE(ph.date_created) BETWEEN :P_START AND :P_END; -- DATE RANGE
				";
	}

}