<?php

class R_and_d_project_get_info extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function get_hod($dept_id){
		$query 				= 	$this->db->query("SELECT `id` FROM `departments` WHERE `name` = '$dept_id'");
		$result 			= 	$query->result();
		//print_r($result);
		$arr['dept_id']		= 	$result[0]->id;	
		$dept_id 			= 	$result[0]->id;
		$query 				= 	$this->db->query(" SELECT * from user_details,user_auth_types 
									WHERE user_details.id = user_auth_types.id   
									AND user_auth_types.auth_id = 'hod' 
									AND user_details.dept_id = '$dept_id'
								");
		$result 			= 	$query->result();
		//print_r($result);
		$arr['emp_id'] 		= 	$result[0]->id;
		return $arr;
	}

	public function get_pi($ism_project_no){
		$query 				= 	$this->db->query("SELECT `emp_no` FROM `r_and_d_project_details` WHERE `ism_project_no` = '$ism_project_no' ");
		$result 			= 	$query->result();
		return $result[0]->emp_no;
	}

	public function get_co_pi_ism($ism_project_no){
		$query 				= 	$this->db->query("SELECT `emp_no` FROM `r_and_d_co_pi_ism_details` WHERE `ism_project_no` = '$ism_project_no' ");
		$result				= 	$query->result();
		//print_r($result);
		return $result;
	}

	public function get_co_pi_other($ism_project_no){
		$query 				= 	$this->db->query("SELECT `emp_no` FROM `r_and_d_co_pi_other_details` WHERE `ism_project_no` = '$ism_project_no' ");
		$result				= 	$query->result();
		//print_r($result);
		return $result;
	}

	public function get_approve_status($ism_project_no){
		//print_r($ism_project_no);
		$query 				=   $this->db->query("SELECT `approve_status` FROM `r_and_d_project_details` WHERE `ism_project_no` = '$ism_project_no' ");
		$result 			=	$query->result();

		return $result[0]->approve_status;
	}

	public function get_faculties_of_department($dept){
		//print_r($dept);
		$query 				= 	$this->db->query("SELECT `id` FROM `departments` WHERE `name` = '$dept'");
		$result 			= 	$query->result();
		//print_r($result);
		$dept_id			= 	$result[0]->id;	
		$query 				= 	$this->db->query("SELECT * FROM user_details , emp_basic_details
												  WHERE user_details.dept_id = '$dept_id' 
												  AND   emp_basic_details.emp_no = user_details.id
												  AND   emp_basic_details.auth_id = 'ft'
												  ");
		$result 			=	$query->result();
		$faculties 			=	array();
		$i=0;
		foreach($result as $faculty){
			$name = $faculty->salutation.' '.$faculty->first_name.' '.$faculty->middle_name.' '.$faculty->last_name ;
			$emp_no = $faculty->id;
			$f  =  array('name'=>$name,'emp_no'=>$emp_no);
			array_push($faculties, $f);
			$i++;
		}
		//print_r($i);
		//$arr = ['faculties'=>$faculties,'number_of_faculties'=>$i];
		//print_r($faculties);
		return $faculties;
	}

}

?>