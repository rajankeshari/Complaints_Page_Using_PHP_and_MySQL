<?php

class R_and_d_add_project_details_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function add_new_project($data){
		//print_r($data);
		
		$ism_project_no  =  $data['ism_project_no'];                    
		$org_project_no  =  $data['org_project_no'];

		for($i = 0; $i < strlen($ism_project_no);$i++){                  //CHECKING FOR ANY SPACE IN ISM PROJECT NUMBER           
			if($ism_project_no[$i] == ' ')
				return 3;
		}

		for($i = 0; $i < strlen($ism_project_no);$i++){                 //CHECKING FOR ANY SPACE IN ORGANISATION PROJECT NUMBER
			if($ism_project_no[$i] == ' ')
				return 4;
		}
																		//CHECKING FOR EXISTENCE OF PROJECT WITH THE SAME ISM PROJECT NUMBER
		$query=$this->db->query("SELECT * 
								FROM r_and_d_project_details AS A
								WHERE ism_project_no = '$ism_project_no'
								");
		$rows = $query->num_rows();
		if($rows > 0){
			return 0;
		}
																		//CHECKING FOR EXISTENCE OF PROJECT WITH THE SAME ORG PROJECT NUMBER
		$query=$this->db->query("SELECT * 
								FROM r_and_d_project_details AS A
								WHERE org_project_no = '$org_project_no'
								");
		$rows = $query->num_rows();
		if($rows > 0){
			return 5;
		}
		

		$project_title  =   $data['project_title'];
		$pi_dept 		=  $data['dept_id'];

		if($pi_dept =='---Select---')					//checking for pi details been selected or not//
			return 8;
														// extracting PI's Name and PI's Employee ID //
		$name 			=   $data['pi_name'];

		$pi_name 		= 	"";
		$i=0;
		while($name[$i]!='(')
			$pi_name = $pi_name.$name[$i++];
		
		while($name[$i]!=':')
			$i++;

		$i 				=	$i+2;

		$emp_no 		= 	"";
		while($name[$i]!=' ')
			$emp_no 	= 	$emp_no.$name[$i++];


		$project_value  =  $data['project_value'];
																	/*	//CHECKING FOR EXISTENCE OF EMP_ID IN DATABASE FOR PI
		$query 			= 	$this->db->query("SELECT * FROM `users` where `id` = '$emp_no' ");
		$rows 			= 	$query->num_rows();
		if($rows != 1){
			return 8;
		}*/
		
		for($i = 0; $i < strlen($emp_no);$i++){                 //CHECKING FOR ANY SPACE IN PI emp_no
			if($emp_no[$i] == ' ')
				return 6;
		}

		for($i = 0; $i <strlen($project_value) ; $i++){                            // TYPE CHECKING FOR PROJECT VALUE
			if($project_value[$i]<'0' || $project_value[$i] > '9')
				return 2;
		}
		if($data['sponsoring_agency']== 'Other')
			$sponsoring_agency = $data['other_sponsoring_agency'];
		else
			$sponsoring_agency = $data['sponsoring_agency'];
		
	 	$date_of_commencement  =  $data['date_of_commencement'];
		$date_of_completion    =  $data['date_of_completion'];
		$objective 			   =  $data['objective'];
		$status 			   =  $data['status'];
		$findings 			   =  $data['findings'];
		$approve_status 	   =  0;

		// ****************CHECKING FOR CO PI ISM ENTIRES FOR SOME POSSIBLE ERRORS***********************
		$number_co_pi_ism = $data['number_co_pi_ism'];
		for($i = 0 ; $i < $number_co_pi_ism ; $i++){

			$co_pi_dept = $data['dept_id_'.$i];

			if($co_pi_dept == '---Select---')
				return 7;
			
			/*$query 			= 	$this->db->query("SELECT * FROM `users` where `id` = '$co_pi_emp_no' ");
			$rows 			= 	$query->num_rows();
			if($rows != 1){
				return 9;
			}*/

		}
		///////////////// CHECKING FOR TWO EMPLOYEES WITH SAME EMPLOYEE ID ///////////////////
		for($i = 0 ; $i < $number_co_pi_ism ; $i++){
			for($j = $i+1; $j < $number_co_pi_ism ; $j++){              
				if( $data['co_pi_name'.$i] == $data['co_pi_name'.$j] )
					return 10;
			}
		}

		// ****************CHECKING FOR CO PI OTHER ENTIRES FOR SOME POSSIBLE ERRORS***********************		
		$number_co_pi_other = $data['number_co_pi_other'];
		/*for($i = 0 ; $i < $number_co_pi_ism ; $i++){ 

			//$designation = $data['designation_'.$i];
			//$department = $data['department_'.$i];
			//$organisation = $data['org_'.$i];
			//$email = $data['email_'.$i];
			$contact = $data['contact_'.$i];
		}*/


		$query = $this->db->query(" INSERT INTO `r_and_d_project_details`(`ism_project_no`, `org_project_no`, `project_title`, `pi_name`, `pi_dept`
												,`emp_no`, `project_value`, `sponsoring_agency`, `date_of_commencement`,
									 			`date_of_completion`, `objective`, `status`, 
												`findings`, `approve_status`) 
									VALUES ('$ism_project_no',
											'$org_project_no' ,
											'$project_title' ,
											'$pi_name' ,
											'$pi_dept' ,
											'$emp_no' ,
											'$project_value' ,
											'$sponsoring_agency' ,
											'$date_of_commencement',
											'$date_of_completion' ,
											'$objective' ,
											'$status' ,
											'$findings' ,
											'$approve_status' )
								");

		$number_co_pi_ism = $data['number_co_pi_ism'];
		for($i = 0 ; $i < $number_co_pi_ism ; $i++){

			$name 			=   $data['co_pi_name'.$i];

			$co_pi_name 		= 	"";
			$k=0;
			while($name[$k]!='(')
				$co_pi_name = $co_pi_name.$name[$k++];
			
			while($name[$k]!=':')
				$k++;

			$k 				=	$k+2;

			$co_pi_emp_no 		= 	"";
			while($name[$k]!=' ')
				$co_pi_emp_no 	= 	$co_pi_emp_no.$name[$k++];
			
			$co_pi_dept 		=   $data['dept_id_'.$i];

			$query = $this->db->query(" INSERT INTO `r_and_d_co_pi_ism_details`(`ism_project_no`, `co_pi_name`, `co_pi_dept`, `emp_no` ) 
								VALUES ('$ism_project_no' ,
										'$co_pi_name' ,
										'$co_pi_dept' ,
										'$co_pi_emp_no' )
								");
		}

		
		for($i = 0 ; $i < $number_co_pi_other ; $i++){ 

			$co_pi_name 	=  $data['co_pi_other_'.$i];
			$designation 	=  $data['designation_'.$i];
			$department 	=  $data['department_'.$i];
			$organisation 	=  $data['org_'.$i];
			$email 			=  $data['email_'.$i];
			$contact 		=  $data['contact_'.$i];

			$this->db->query(" INSERT INTO `r_and_d_co_pi_other_details`(`ism_project_no`, `org_project_no`, `co_pi_name`, `designation`,
											`department`, `organisation`, `email`, `contact_no`) 
								VALUES ('$ism_project_no' ,
										'$org_project_no' ,
										'$co_pi_name' ,
										'$designation' ,
										'$department' ,
										'$organisation' ,
										'$email' ,
										'$contact'	)
								");
		}

		return 1;
	}

	//****************************************************  Edit Details  ************************************************//

	public function edit_project_details($data){

		//print_r($data);
		$ism_project_no  =  $data['ism_project_no'];     

		$org_project_no  =  $data['org_project_no'];

		for($i = 0; $i < strlen($ism_project_no);$i++){                  //CHECKING FOR ANY SPACE IN ISM PROJECT NUMBER           
			if($ism_project_no[$i] == ' ')
				return 3;
		}

		for($i = 0; $i < strlen($ism_project_no);$i++){                 //CHECKING FOR ANY SPACE IN ORGANISATION PROJECT NUMBER
			if($ism_project_no[$i] == ' ')
				return 4;
		}
																		//CHECKING FOR EXISTENCE OF PROJECT WITH THE SAME ISM PROJECT NUMBER
		/*$query=$this->db->query("SELECT * 
								FROM r_and_d_project_details AS A
								WHERE ism_project_no = '$ism_project_no'
								");
		$rows = $query->num_rows();
		if($rows > 0){
			return 0;
		}
																		//CHECKING FOR EXISTENCE OF PROJECT WITH THE SAME ORG PROJECT NUMBER
		$query=$this->db->query("SELECT * 
								FROM r_and_d_project_details AS A
								WHERE org_project_no = '$org_project_no'
								");
		$rows = $query->num_rows();
		if($rows > 0){
			return 5;
		}*/
		

		$project_title  =  $data['project_title'];

		$pi_dept 		=  $data['dept_id'];

		if($pi_dept =='---Select---')					//checking for pi details been selected or not//
			return 8;
														// extracting PI's Name and PI's Employee ID //
		$name 			=   $data['pi_name'];
		$pi_name 		= 	'';
		$i=0;
		while($name[$i]!='(')
			$pi_name = $pi_name.$name[$i++];
		
		while($name[$i]!=':')
			$i++;
		$i 				=	$i+2;

		$emp_no = '';
		while($name[$i]!=' ')
			$emp_no 	= 	$emp_no.$name[$i++]; 

		$project_value  =  $data['project_value'];
																	/*	//CHECKING FOR EXISTENCE OF EMP_ID IN DATABASE FOR PI
		$query 			= 	$this->db->query("SELECT * FROM `users` where `id` = '$emp_no' ");
		$rows 			= 	$query->num_rows();
		if($rows != 1){
			return 8;
		}*/
		
		for($i = 0; $i < strlen($emp_no);$i++){                 //CHECKING FOR ANY SPACE IN PI emp_no
			if($emp_no[$i] == ' ')
				return 6;
		}

		for($i = 0; $i <strlen($project_value) ; $i++){                            // TYPE CHECKING FOR PROJECT VALUE
			if($project_value[$i]<'0' || $project_value[$i] > '9')
				return 2;
		}
		if($data['sponsoring_agency']== 'Other')
			$sponsoring_agency = $data['other_sponsoring_agency'];
		else
			$sponsoring_agency = $data['sponsoring_agency'];
		
	 	$date_of_commencement  =  $data['date_of_commencement'];
		$date_of_completion    =  $data['date_of_completion'];
		$objective 			   =  $data['objective'];
		$status 			   =  $data['status'];
		$findings 			   =  $data['findings'];
		$approve_status 	   =  3;

		// ****************CHECKING FOR CO PI ISM ENTIRES FOR SOME POSSIBLE ERRORS***********************
		$number_co_pi_ism = $data['number_co_pi_ism'];
		for($i = 0 ; $i < $number_co_pi_ism ; $i++){

			$co_pi_dept = $data['dept_id_'.$i];

			if($co_pi_dept == '---Select---')
				return 7;
			
			/*$query 			= 	$this->db->query("SELECT * FROM `users` where `id` = '$co_pi_emp_no' ");
			$rows 			= 	$query->num_rows();
			if($rows != 1){
				return 9;
			}*/

		}
		///////////////// CHECKING FOR TWO EMPLOYEES WITH SAME EMPLOYEE ID ///////////////////
		for($i = 0 ; $i < $number_co_pi_ism ; $i++){
			for($j = $i+1; $j < $number_co_pi_ism ; $j++){              
				if( $data['co_pi_name'.$i] == $data['co_pi_name'.$j] )
					return 10;
			}
		}
		// ****************CHECKING FOR CO PI OTHER ENTIRES FOR SOME POSSIBLE ERRORS***********************		
		$number_co_pi_other = $data['number_co_pi_other'];
		/*for($i = 0 ; $i < $number_co_pi_ism ; $i++){ 

			//$designation = $data['designation_'.$i];
			//$department = $data['department_'.$i];
			//$organisation = $data['org_'.$i];
			//$email = $data['email_'.$i];
			$contact = $data['contact_'.$i];
		}*/
		
//DELETING THE RECORD OF PROJECT FIRST
		$query = $this->db->query(" DELETE FROM `mis_new`.`r_and_d_project_details` WHERE `r_and_d_project_details`.`ism_project_no` = '$ism_project_no'");

		$query = $this->db->query(" INSERT INTO `r_and_d_project_details`(`ism_project_no`, `org_project_no`, `project_title`, `pi_name`, `pi_dept`
												,`emp_no`, `project_value`, `sponsoring_agency`, `date_of_commencement`,
									 			`date_of_completion`, `objective`, `status`, 
												`findings`, `approve_status`) 
									VALUES ('$ism_project_no',
											'$org_project_no' ,
											'$project_title' ,
											'$pi_name' ,
											'$pi_dept' ,
											'$emp_no' ,
											'$project_value' ,
											'$sponsoring_agency' ,
											'$date_of_commencement',
											'$date_of_completion' ,
											'$objective' ,
											'$status' ,
											'$findings' ,
											'$approve_status' )
								");

		$number_co_pi_ism = $data['number_co_pi_ism'];
		for($i = 0 ; $i < $number_co_pi_ism ; $i++){

			$name 			=   $data['co_pi_name'.$i];

			$co_pi_name 		= 	"";
			$k=0;
			while($name[$k]!='(')
				$co_pi_name = $co_pi_name.$name[$k++];
			
			while($name[$k]!=':')
				$k++;

			$k 				=	$k+2;

			$co_pi_emp_no 		= 	"";
			while($name[$k]!=' ')
				$co_pi_emp_no 	= 	$co_pi_emp_no.$name[$k++];
			
			$co_pi_dept 		=   $data['dept_id_'.$i];

			$this->db->query(" INSERT INTO `r_and_d_co_pi_ism_details`(`ism_project_no`, `co_pi_name`, `co_pi_dept`, `emp_no` ) 
								VALUES ('$ism_project_no' ,
										'$co_pi_name' ,
										'$co_pi_dept' ,
										'$co_pi_emp_no' )
								");
		}

		$number_co_pi_other = $data['number_co_pi_other'];
		for($i = 0 ; $i < $number_co_pi_other ; $i++){ 

			$co_pi_name 	=  $data['co_pi_other_'.$i];
			$designation 	=  $data['designation_'.$i];
			$department 	=  $data['department_'.$i];
			$organisation 	=  $data['org_'.$i];
			$email 			=  $data['email_'.$i];
			$contact 		=  $data['contact_'.$i];

			$this->db->query(" INSERT INTO `r_and_d_co_pi_other_details`(`ism_project_no`, `org_project_no`, `co_pi_name`, `designation`,
											`department`, `organisation`, `email`, `contact_no`) 
								VALUES ('$ism_project_no' ,
										'$org_project_no' ,
										'$co_pi_name' ,
										'$designation' ,
										'$department' ,
										'$organisation' ,
										'$email' ,
										'$contact'	)
								");
		}

		return 1;

	}

	public function delete_project_details($ism_project_no){
		//DELETING THE RECORD OF PROJECT FIRST
		$query = $this->db->query(" DELETE FROM `mis_new`.`r_and_d_project_details` WHERE `r_and_d_project_details`.`ism_project_no` = '$ism_project_no'");

	}
	
}


?>