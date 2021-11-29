<?php

class Remove_emp_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function get_retirement_exceeded_emp_details()
	{
		$date=date('Y-m-d');	
		$query="Select a.emp_no,a.designation,a.retirement_date,t.salutation,t.first_name,t.middle_name,t.last_name,t.dept_name
		FROM
		( SELECT b.id,b.salutation,b.first_name,b.middle_name,b.last_name,c.name as dept_name FROM user_details as b JOIN departments AS c ON b.dept_id = c.id)t
		JOIN emp_basic_details AS a
		ON a.emp_no=t.id AND a.retirement_date<='".$date."' ORDER BY a.retirement_date DESC";
		
		$query = $this->db->query($query);
		if( $query->num_rows() > 0 )
			return $query->result();
		else return false;
	}
	
	function get_permanent_EmployeesId()
	{
		$query="SELECT emp_no FROM emp_basic_details WHERE employment_nature='permanent' ORDER BY emp_no";
		$query = $this->db->query($query);
		if($query->num_rows() > 0)
			return $query->result();
		else return false;
	}
	
	function get_temporary_EmployeesId()
	{
		$query="SELECT emp_no FROM emp_basic_details WHERE employment_nature='temporary' OR employment_nature='contract' ORDER BY emp_no";
		$query = $this->db->query($query);
		
		if($query->num_rows() > 0)
			return $query->result();
		else return false;
	}
	
	function get_contract_EmployeesId()
	{
		$query="SELECT emp_no FROM emp_basic_details WHERE employment_nature='contract' ORDER BY emp_no";
		$query = $this->db->query($query);
		
		if($query->num_rows() > 0)
			return $query->result();
		else return false;
	}
	
	function get_emp_work_details_by_id($emp_no)
	{
		$query="SELECT a.emp_no,a.designation,t.salutation,t.first_name,t.middle_name,t.last_name,t.dept_name
		FROM
		(SELECT b.id,b.salutation,b.first_name,b.middle_name,b.last_name,c.name AS dept_name FROM user_details AS b JOIN departments AS c ON b.dept_id=c.id)t
		JOIN emp_basic_details AS a ON a.emp_no=t.id WHERE emp_no = '".$emp_no."'";
		
		$query = $this->db->query($query);
		if($query->num_rows() == 1)
			return $query->row();
		else return false;
	}
	
	function get_daily_emp_work_details_by_id($emp_no)
	{
		$query="SELECT a.emp_no,t.salutation,t.first_name,t.middle_name,t.last_name,t.dept_name
		FROM
		(SELECT b.id,b.salutation,b.first_name,b.middle_name,b.last_name,c.name AS dept_name FROM user_details AS b JOIN departments AS c ON b.dept_id=c.id)t
		JOIN daily_rated_emp_details AS a ON a.emp_no=t.id WHERE emp_no = '".$emp_no."'";
		
		$query = $this->db->query($query);
		if($query->num_rows() == 1)
			return $query->row();
		else return false;
	}
	
}
?>