<?php

class Check_model extends CI_Model {
	
	private $oracle = NULL;
	
	public function __construct(){
		parent::__construct();
		$this->oracle = $this->load->database('oracle', true);
	}

	public function get_check_details($params){
		$sql = "SELECT  to_char(aca.check_date,'MM/DD/YYYY') check_date,
						aca.check_number,
						aca.doc_sequence_value,
						aca.status_lookup_code,
						aca.vendor_name ,
						-- hp.party_name vendor_name,
						-- aca.payment_method_code,
						aca.amount,
						--aca.bank_account_name,
						cbb.bank_name,
						cba.bank_account_name,
						aca.currency_code,
						aca.vendor_site_code,
						aca.bank_account_num,
						aca.party_site_id,
						aca.party_id,
						aca.treasury_pay_date,
						cpb.payment_document_name,
						aca.attribute2 or_number,
						aca.attribute3 or_date,
						aca.attribute4 voucher_text
				FROM ap_checks_all aca
					INNER JOIN ce_bank_acct_uses_all banks
						ON banks.bank_acct_use_id = aca.ce_bank_acct_use_id
					INNER JOIN ce_bank_accounts cba
						ON cba.bank_account_id = banks.bank_account_id
					INNER JOIN ce_bank_branches_v cbb
						ON cbb.branch_party_id = cba.bank_branch_id
					INNER JOIN CE_PAYMENT_DOCUMENTS CPB
						ON cpb.payment_document_id = aca.payment_document_id
				WHERE 1 = 1
					AND aca.doc_sequence_value = ?
					AND aca.bank_account_num = ?";
		$query = $this->oracle->query($sql,$params);
		$data = $query->result();
		return $data;
	}

	

}