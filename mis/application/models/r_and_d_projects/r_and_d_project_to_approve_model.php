<?php

class R_and_d_project_to_approve_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function project_to_approve($ism_project_no){
		//echo $ism_project_no;
		$data = array();

		$query = $this->db->query("SELECT * from `r_and_d_project_details` WHERE `ism_project_no`='$ism_project_no'");
		$result = $query->result();
		//print_r($result);

		$data['ism_project_no']        =  $result[0]->ism_project_no ;
		$data['org_project_no']        =  $result[0]->org_project_no;
		$data['project_title']         =  $result[0]->project_title;
		$data['sponsoring_agency']     =  $result[0]->sponsoring_agency;
		$data['pi_name']               =  $result[0]->pi_name;
		$data['pi_dept']               =  $result[0]->pi_dept;
		$data['emp_no']                =  $result[0]->emp_no;
		$data['project_value']         =  $result[0]->project_value;
		$data['date_of_commencement']  =  $result[0]->date_of_commencement;
		$data['date_of_completion']    =  $result[0]->date_of_completion;
		$data['objective'] 			   =  $result[0]->objective;
		$data['status'] 			   =  $result[0]->status;
		$data['findings']			   =  $result[0]->findings;
		$data['approve_status']		   =  $result[0]->approve_status;
		//print_r($data);

		$query = $this->db->query("SELECT * from `r_and_d_co_pi_ism_details` WHERE `ism_project_no`='$ism_project_no'");
		$result = $query->result();
		//print_r($result);
		$co__pi_ism = array();
		$i = 0;
		foreach($result as $co_pi_ism){

			$co__pi_ism['co_pi_ism_'.$i] 	  	=  $co_pi_ism->co_pi_name;
			$co__pi_ism['dept_id_'.$i]  	    =  $co_pi_ism->co_pi_dept;
			$co__pi_ism['emp_no_'.$i]    	   	=  $co_pi_ism->emp_no;
			$i++;

		}
		$data['no_of_co_pi_ism'] 				=  $i;
		$data['co_pi_ism'] 						=  $co__pi_ism;
		//print_r($data);

		$query = $this->db->query("SELECT * from `r_and_d_co_pi_other_details` WHERE `ism_project_no`='$ism_project_no'");
		$result = $query->result();
		$co__pi_other = array();
		$i = 0;
		foreach($result as $co_pi_other){
			$co__pi_other['co_pi_other_'.$i]   =  $co_pi_other->co_pi_name;
			$co__pi_other['designation_'.$i]   =  $co_pi_other->designation;
			$co__pi_other['department_'.$i]    =  $co_pi_other->department;
			$co__pi_other['org_'.$i] 	       =  $co_pi_other->organisation;
			$co__pi_other['email_'.$i]		   =  $co_pi_other->email;
			$co__pi_other['contact_'.$i]       =  $co_pi_other->contact_no;
			$i++;
		}
		$data['no_of_co_pi_other'] 			   =  $i;
		$data['co_pi_other'] 				   =  $co__pi_other;
		//print_r($data);

		return $data;
	}

}

?>