<?php

class Bank_model extends CI_Model {
	
	private $oracle = NULL;
	
	public function __construct(){
		parent::__construct();
		$this->oracle = $this->load->database('oracle', true);
	}

	public function get_bank_accounts(){
		$sql = "SELECT cba.bank_account_name,
						cba.bank_account_num,
						cba.currency_code,
						cbb.bank_branch_name,
						cbb.bank_name,
						cba.bank_account_id
				FROM ce_bank_accounts cba
				         INNER JOIN ce_bank_branches_v cbb
				            ON cbb.branch_party_id = cba.bank_branch_id
				WHERE 1 = 1";
		$query = $this->oracle->query($sql);
		$data = $query->result();
		return $data;
	}

	

}