<?php

class Person_model extends CI_Model {
	
	private $oracle = NULL;
	
	public function __construct(){
		parent::__construct();
		$this->oracle = $this->load->database('oracle', true);
	}

	public function get_fsd_signatories(){
		$sql = "SELECT ppf.person_id,
				         ppf.first_name || ' ' ||
				         CASE WHEN ppf.middle_names IS NOT NULL
				                   THEN substr(ppf.middle_names,1,1) || '. '
				                   ELSE ' '
				         END ||
				         ppf.last_name person_name,
				         ppf.effective_end_date,
				         fu.end_date
				FROM per_people_f ppf LEFT JOIN fnd_user fu
				            ON PPF.PERSON_ID = fu.employee_id
				WHERE ppf.attribute3 = 'FINANCE AND ACCOUNTING'
				         and ppf.effective_end_date = '31-DEC-4712'
				         and fu.end_date IS NULL
				ORDER BY ppf.last_name,
				              ppf.first_name";
		$query = $this->oracle->query($sql);
		$data = $query->result();
		return $data;
	}

	public function get_person_details($user_id){
		$sql = "SELECT ppf.person_id,
				         ppf.first_name || ' ' ||
				         CASE WHEN ppf.middle_names IS NOT NULL
				                   THEN substr(ppf.middle_names,1,1) || '. '
				                   ELSE ' '
				         END ||
				         ppf.last_name person_name,
				         ppf.effective_end_date,
				         fu.end_date
				FROM per_people_f ppf LEFT JOIN fnd_user fu
				            ON PPF.PERSON_ID = fu.employee_id
				WHERE 1 = 1
				         and ppf.effective_end_date = '31-DEC-4712'
				         and fu.end_date IS NULL
				         and fu.user_id = ?
				ORDER BY ppf.last_name,
				              ppf.first_name";
		$query = $this->oracle->query($sql,$user_id);
		$data = $query->result();
		return $data[0];
	}


}