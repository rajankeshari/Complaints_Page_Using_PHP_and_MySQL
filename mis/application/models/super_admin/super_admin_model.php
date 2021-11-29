<?php 
class Super_admin_model extends CI_Model{


	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
// this function return all the authorization of employee having in deny auth by employee id preview
	function getUsersByEmpId($emp_id = 'all')
	 {
	 	
		$data=$this->db->query("SELECT auth_id,type from user_auth_types as U inner join auth_types as A on U.auth_id=A.id where U.id = $emp_id");
		return $data->result_array() ;
						
	 }
     // this function return the name and department of the employee to show in deny auth by employeeid view 
	 function getUsersDetailByEmpId($emp_id = 'all')
	  {
	  		$data = $this->db->query("SELECT salutation, first_name, middle_name, last_name, name as department from user_details as U inner join departments as D on U.dept_id= D.id where U.id= $emp_id");
	  		return $data->result_array();
	  }
 }
	  ?>
