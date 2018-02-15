<?php

class Supplier_model extends CI_Model {
	
	private $oracle = NULL;
	
	public function __construct(){
		parent::__construct();
		$this->oracle = $this->load->database('oracle', true);
	}

	public function get_supplier_by_name($supplier_name){
		$this->oracle->select('SEGMENT1, VENDOR_NAME',FALSE);
		$this->oracle->from('APPS.AP_SUPPLIERS',FALSE);
		$this->oracle->where('END_DATE_ACTIVE IS NULL');
		$this->oracle->where('ROWNUM <= 10',NULL,FALSE);
		$this->oracle->like('lower(VENDOR_NAME)', strtolower($supplier_name));
		return $this->oracle->get()->result();
	}

	public function get_supplier_sites($supplier_id){
		$sql = "SELECT vendor_site_id,
					   vendor_site_code
				FROM ap_suppliers aps 
				     INNER JOIN ap_supplier_sites_all apsa
						ON aps.vendor_id = apsa.vendor_id
				WHERE aps.segment1 = ?
					AND aps.end_date_active IS NULL
					AND apsa.inactive_date IS NULL
					AND apsa.org_id = 82";
		$query = $this->oracle->query($sql,$supplier_id);
		return $query->result();
	}

}