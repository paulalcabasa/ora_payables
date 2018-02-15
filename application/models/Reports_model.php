<?php

class Reports_model extends CI_Model {
	
	private $oracle = NULL;
	
	public function __construct(){
		parent::__construct();
		$this->oracle = $this->load->database('oracle', true);
	}

	public function get_ppr_requests_by_user($params){
		$sql = "SELECT ppr_header_id
				FROM IPC.IPC_PPR_HEADERS ph
				WHERE ph.ppr_header_id BETWEEN ? AND ?
					  AND ph.created_by = ?
				ORDER BY ppr_header_id";
		$query = $this->oracle->query($sql,$params);
		return $query->result();
	}

	

}