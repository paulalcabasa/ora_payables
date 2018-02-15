<?php

class Disbursement_model extends CI_Model {
	
	private $oracle = NULL;
	
	public function __construct(){
		parent::__construct();
		$this->oracle = $this->load->database('oracle', true);
	}

	public function get_disbursement_list($params,$release_status,$check_number){

		$release_status_cond = "";
		if($release_status != ""){
			if($release_status == "unreleased"){
				$release_status_cond = "AND aca.treasury_pay_date IS NULL";
			}
			else if($release_status == "released"){
				$release_status_cond = "AND aca.treasury_pay_date IS NOT NULL";
			}
		}
		$check_number_cond = "";
		if($check_number != ""){
			$check_number_cond = "AND aca.check_number = '". $check_number."'";
		}
		$sql = "SELECT aps.segment1 supplier_id,
				            aca.vendor_name supplier_name,
				            cpb.payment_document_name,
				            aca.check_number,
				            aca.doc_sequence_value check_voucher_no,
				            aca.amount check_amount,
				            TO_CHAR(aca.check_date,'MM/DD/YYYY') check_date,
				            aca.status_lookup_code status,
				            cbb.bank_name,
				            TO_CHAR(aca.treasury_pay_date,'MM/DD/YYYY') release_date,
				            ipc_or.or_number or_no,
				            TO_CHAR(ipc_or.or_date,'MM/DD/YYYY') or_date,
				            TO_CHAR(ipc_or.date_created,'MM/DD/YYYY') entry_date,
				         	aca.check_id,
				            ipc_or.remarks remarks,
				            ipc_or.official_receipt_id
				FROM ap_checks_all aca 
				       LEFT JOIN ap_suppliers aps
				            ON aps.vendor_id = aca.vendor_id
				       LEFT JOIN ipc.ipc_ppr_or_details ipc_or
				            ON ipc_or.ap_check_voucher_no = aca.doc_sequence_value
				            AND ipc_or.ap_check_number = aca.check_number
				       INNER JOIN ce_payment_documents cpb
				            ON cpb.payment_document_id = aca.payment_document_id
				       INNER JOIN ce_bank_acct_uses_all cbaua
				            ON aca.ce_bank_acct_use_id = cbaua.bank_acct_use_id
				       INNER JOIN ce_bank_accounts cba
				            ON cba.bank_account_id = cbaua.bank_account_id
				       INNER JOIN ce_bank_branches_v cbb
				            ON cbb.bank_party_id = cba.bank_id
				       INNER JOIN fnd_user fu
				            ON fu.user_id = aca.created_by
				WHERE TO_DATE(aca.check_date) BETWEEN ? AND ?
					  $release_status_cond
					  $check_number_cond
				ORDER BY aca.check_number ASC";
	
		$query = $this->oracle->query($sql,$params);
		$data = $query->result();
		return $data;
	}

	public function insert_or_details($params){
		$sql = "INSERT INTO IPC.IPC_PPR_OR_DETAILS (
					official_receipt_id,
					or_number,
					or_date,
					ap_check_id,
					ap_check_voucher_no,
					ap_check_number,
					remarks,
					created_by,
					date_created
				)
				VALUES(
					IPC.PPR_OR_DETAILS_SEQ.NEXTVAL,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					SYSDATE
				)";
		$query = $this->oracle->query($sql,$params);
		return $this->get_current_or_id();
	}

	public function update_or_details($params){
		$sql = "UPDATE IPC.IPC_PPR_OR_DETAILS 
				SET or_number = ?,
					or_date = ?,
					remarks = ?,
					updated_by = ?,
					date_updated = SYSDATE
				WHERE official_receipt_id = ?";
		$query = $this->oracle->query($sql,$params);
	}

	public function get_vat_list($params,$invoice_num,$voucher_no,$transaction_type){
		ini_set('max_execution_time', 36000); //300 seconds = 5 minutes
			
		ini_set('memory_limit', '-1'); //300 seconds = 5 minutes
		$voucher_no_cond = "";
		if($voucher_no != ""){
			$voucher_no_cond = "AND aia.doc_sequence_value = '" . $voucher_no . "'";
		}

		$invoice_num_cond = "";
		if($invoice_num != ""){
			$invoice_num_cond = "AND aia.invoice_num = '". $invoice_num."'";
		}

		$transaction_type_cond = "";
		if($transaction_type != ""){
			$transaction_type_cond = "AND aia.attribute1 = '" . $transaction_type . "'";
		}

		$sql = "SELECT   aia.doc_sequence_value,
			             aia.gl_date,
			             aila.line_number,
			             gcc.segment6 account,
			             aida.amount vat_amount,
			             aps.segment1 supplier_id,
			             aps.vendor_name supplier_name,
			             aida.description,
			             aila.tax_classification_code tax_code,
			             aia.invoice_num,
			             aila.attribute1 third_party_supplier_id,
			             CASE 
			             	WHEN aila.attribute2 IS NOT NULL THEN aila.attribute2
			             	ELSE aps_third_party.vendor_name
			             END third_party_supplier,
			             cbb.bank_name,
			             aca.check_date,
			             aca.check_number,
			             aipa.amount payment_amount,
			             TO_CHAR(aca.treasury_pay_date,'MM/DD/YYYY') release_date,
			             ipc_or.or_number or_no,
			             TO_CHAR(ipc_or.or_date,'MM/DD/YYYY') or_date,
			             TO_CHAR(ipc_or.date_created,'MM/DD/YYYY') entry_date,
			             vatm.application_period,
			             aia.attribute1 transaction_type,
			             aida.DISTRIBUTION_LINE_NUMBER,
			             vatm.VAT_DETAIL_ID
				FROM ap_invoice_lines_all aila
			           INNER JOIN ap_invoice_distributions_all aida 
			               ON aida.invoice_id = aila.invoice_id
			               AND aida.invoice_line_number = aila.line_number
			           INNER JOIN ap_invoices_all aia
			               ON aia.invoice_id = aila.invoice_id
			           INNER JOIN gl_code_combinations gcc
			               ON gcc.code_combination_id = aida.dist_code_combination_id
			           LEFT JOIN ap_suppliers aps_third_party
			               ON aps_third_party.segment1 = aila.attribute1
			           LEFT JOIN ap_suppliers aps
			                ON aps.vendor_id = aia.vendor_id
			           LEFT JOIN ap_invoice_payments_all aipa
			                ON aipa.invoice_id = aia.invoice_id
			           LEFT JOIN (SELECT * FROM ap_checks_all WHERE status_lookup_code <> 'VOIDED') aca
			                ON aca.check_id = aipa.check_id
			           LEFT JOIN ce_payment_documents cpb
			                ON cpb.payment_document_id = aca.payment_document_id
			           LEFT JOIN ce_bank_acct_uses_all cbaua
			                ON aca.ce_bank_acct_use_id = cbaua.bank_acct_use_id
			           LEFT JOIN ce_bank_accounts cba
			                ON cba.bank_account_id = cbaua.bank_account_id
			           LEFT JOIN ce_bank_branches_v cbb
			                ON cbb.bank_party_id = cba.bank_id
			           LEFT JOIN ipc.ipc_ppr_or_details ipc_or
			                ON ipc_or.ap_check_id = aca.check_id
			           LEFT JOIN  IPC.IPC_VATM_VAT_DETAILS vatm
			           		ON vatm.AP_VOUCHER_NUM = aia.doc_sequence_value
			           		AND vatm.AP_INVOICE_LINE_NUM = aila.line_number
			           		AND vatm.AP_DISTRIBUTION_LINE_NUM = aida.DISTRIBUTION_LINE_NUMBER
			           		AND vatm.status = 'ACTIVE'
				WHERE  1 = 1
			            AND gcc.segment6 = '67000'
			            AND aida.recovery_rate_name = 'V2(S) R'
			         --   AND aila.tax_classification_code = 'V2(S)'
			         --   AND aila.attribute1 IS NOT NULL
			            AND aia.cancelled_date IS NULL
		                AND TRUNC(aia.gl_date) BETWEEN ? AND ?
			            AND aila.org_id = 82
			            $voucher_no_cond
			            $invoice_num_cond
			            $transaction_type_cond
			   	ORDER BY 
						aia.doc_sequence_value,
						aila.line_number";
		$query = $this->oracle->query($sql,$params);
		$data = $query->result();
		return $data;
	}

	public function get_transaction_types(){
		$sql = "SELECT  ffvs.flex_value_set_id ,
			            ffvs.flex_value_set_name ,
			            ffv.flex_value,
			            ffvt.description value_description,
			            ffv.enabled_flag
				FROM fnd_flex_value_sets ffvs,
		             fnd_flex_values ffv,
		             fnd_flex_values_tl ffvt
				WHERE 1 = 1
		            AND ffvs.flex_value_set_id = ffv.flex_value_set_id
		            AND ffv.flex_value_id = ffvt.flex_value_id
		            AND ffv.enabled_flag = 'Y'
		            AND ffvt.language = USERENV('LANG')
		            AND flex_value_set_name = 'TRANSACTION TYPE'
		            ORDER BY flex_value ASC";
		$query = $this->oracle->query($sql);
		$data = $query->result();
		return $data;
	}
	
	public function insert_vat_details($params){
		$sql = "INSERT INTO IPC.IPC_VATM_VAT_DETAILS (
					VAT_DETAIL_ID,
					AP_VOUCHER_NUM,
					AP_INVOICE_LINE_NUM,
					AP_DISTRIBUTION_LINE_NUM,
					APPLICATION_PERIOD,
					CREATED_BY,
					DATE_CREATED,
					STATUS
				)
				VALUES(
					IPC.VATM_VAT_DETAILS_SEQ.NEXTVAL,
					?,
					?,
					?,
					?,
					?,
					SYSDATE,
					'ACTIVE'
				)";
		$this->oracle->query($sql,$params);
		//return $this->get_current_vat_detail_id();
	}

	public function deactivate_vat_detail($params){
		$sql = "UPDATE IPC.IPC_VATM_VAT_DETAILS
				SET status = 'INACTIVE',
					updated_by = ?,
					date_updated = SYSDATE
				WHERE vat_detail_id = ?";
		$this->oracle->query($sql,$params);
	}

	public function get_current_vat_detail_id(){
		$sql = "SELECT IPC.VATM_VAT_DETAILS_SEQ.CURRVAL FROM DUAL";
		$query = $this->oracle->query($sql);
		$data = $query->result();
		return $data[0]->CURRVAL;
	}

	public function get_current_or_id(){
		$sql = "SELECT IPC.PPR_OR_DETAILS_SEQ.CURRVAL FROM DUAL";
		$query = $this->oracle->query($sql);
		$data = $query->result();
		return $data[0]->CURRVAL;
	}
}