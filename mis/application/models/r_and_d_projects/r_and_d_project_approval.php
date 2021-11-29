<?php

class R_and_d_project_approval extends CI_Model{

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function project_approved($ism_project_no){
		$query = $this->db->query("UPDATE `r_and_d_project_details` 
								   SET `approve_status`= '1' 
								   WHERE `ism_project_no` = '$ism_project_no'");

		return 1;
	}

	public function project_disapproved($ism_project_no){
		$query = $this->db->query("UPDATE `r_and_d_project_details` 
								   SET `approve_status`= '2' 
								   WHERE `ism_project_no` = '$ism_project_no'");

		return 1;
	}

}