<?php

class Project_emp_details_model extends CI_Model
{
	var $table = 'project_emp_details';

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function insert($data)
	{

		$this->db->insert($this->table,$data);
		//echo $this->db->last_query(); die();

	}

	function updateById($data,$emp_no)
	{
		$this->db->update($this->table,$data,array('emp_no'=>$emp_no));
	}

	function getEmployeeByID($emp_no = '')
	{
		if($emp_no != '')
		{
			$query = $this->db->where('emp_no="'.$emp_no.'"','',FALSE)->get($this->table);
			if($query->num_rows() === 1)
				return $query->row();
			else
				return FALSE;
		}
		else
			return FALSE;
	}

	function getAllEmployeesId()
	{
		$query = $this->db->select('emp_no')->order_by('emp_no')->get($this->table);
		if($query->num_rows() > 0)
			return $query->result();
		return false;
	}

	function getAllAndPendingEmployeesId()
	{
		//$query = "select * from (SELECT emp_no FROM ".$this->table." UNION SELECT emp_no FROM pending_".$this->table.")x order by x.emp_no ";
		$query = "select * from (SELECT emp_no FROM pending_".$this->table.")x order by x.emp_no ";
		$query = $this->db->query($query);
		if($query->num_rows() > 0)
			return $query->result();
		else
		return false;
	}

	function getAllAndVerifiedEmployeesId(){
		$query = "select * from (SELECT emp_no FROM ".$this->table.")x order by x.emp_no ";
		$query = $this->db->query($query);
		if($query->num_rows() > 0)
			return $query->result();
		else
		return false;
	}

	function delete_record($where_array)
	{
		$this->db->delete($this->table,$where_array);
	}

	function getDetailsWhere($condition)
	{
		if($condition == '')
			return FALSE;
		else
		{
			$query = "SELECT * FROM ".$this->table." WHERE ".$condition."";
			$query=$this->db->query($query);
			if($query->num_rows() >=1 )	return $query->result();
//			else if($query->num_rows() == 1 )	return $query->row();
			return FALSE;
		}
	}

	function getAllPendingDetails()
	{
		$query = $this->db->order_by('emp_no')->get('pending_'.$this->table);
		if($query->num_rows() > 0)
			return $query->result();
		else
			return FALSE;
	}

	function getAllPendingDetailsToApprove()
	{
		$query = "SELECT a.emp_no, a.salutation, a.first_name, a.middle_name, a.last_name, b.status, b.reason FROM pending_project_emp_details AS a JOIN project_emp_validation_details AS b ON a.emp_no = b.emp_no";
		//echo $query ; die();
		$query = $this->db->query($query);
		//echo $this->db->last_query(); die();

		if($query->num_rows() > 0)
			return $query->result();
		else
			return FALSE;
	}

	// These two functions 	getTempUserById and getPendingDetailsById is used to check while creating new project employees

	 function getTempUserById($id = '')
    {


		 $query=$this->db->query("SELECT * FROM users where id = '".$id."'");
		//echo $query1 ; die();
        if ($query->num_rows() > 0)
		{

			return true;
		}

        else
		{

			return false;
		}

    }

	function getPendingDetailsById($emp_id = '')
	{

        $query = $this->db->query("select * from pending_project_emp_details where emp_no = '$emp_id'");

        if ($query->num_rows() >0)
		{
			//echo "data available " ; die();
			return $query->row();
			//return true;
		}

        else
		{

			return false;
		}

    }
	function getPendingDetailsdataById($emp_id = '')
	{

        $query = $this->db->query("select * from pending_project_emp_details where emp_no = '$emp_id'");

        if ($query->num_rows() >0)
		{
			//echo "data available " ; die();
			return $query->row();
			//return true;
		}

        else
		{

			return false;
		}

    }

	function getPendingDetailsRejectedById($emp_no = '')
	{
		if($emp_no == '')
			return FALSE;
		else
		{
			//$query=$this->db->where('emp_no',$emp_no)->get('pending_'.$this->table);
			//$query= "select * from pending_project_emp_details"
		//	$query = "SELECT a.emp_no, a.nature_of_hiring,a.salutation, a.first_name, a.middle_name, a.last_name,a.sex b.status, b.reason FROM pending_project_emp_details AS a JOIN project_emp_validation_details AS b ON a.emp_no = b.emp_no";
		$query= "select a.*, b.status, b.reason from pending_project_emp_details AS a JOIN project_emp_validation_details AS b ON a.emp_no = b.emp_no where a.emp_no='$emp_no'";
		//$query = "SELECT a.emp_no, b.status, b.reason FROM pending_project_emp_details AS a JOIN project_emp_validation_details AS b ON a.emp_no = b.emp_no where a.emp_no =$emp_no  and b.status=`"rejected"` ";
		$query = $this->db->query($query);
			//echo $query->num_rows() ;
		//	echo "here";die();
			if($query->num_rows() ==1 )	return $query->row();
			return FALSE;
		}
	}

	function getPendingDetailsWhere($condition)
	{
		if($condition == '')
			return FALSE;
		else
		{
			$query = "SELECT * FROM pending_".$this->table." WHERE ".$condition."";
			//echo $query ; die();
			$query=$this->db->query($query);
			if($query->num_rows() >=1 )	return $query->result();
//			else if($query->num_rows() ==1 )	return $query->row();
			return FALSE;
		}
	}

	function getPendingDetailsByIdToApprove($emp_no = '')
	{
		if($emp_no == '')
			return FALSE;
		else
		{
			$query="SELECT a.emp_no, a.salutation, a.first_name, a.middle_name, a.last_name, a.sex, a.dob, a.joining_date, a.nature_of_hiring, d.name AS designation, a.project_no, a.photopath, a.id_proof, a.other_relevent_info, b.name as dept_name, a.designation AS desi FROM pending_".$this->table." AS a JOIN departments AS b ON a.dept_id = b.id
			JOIN designations d ON d.id=a.designation
			where a.emp_no = '".$emp_no."'";
			// echo $query ; die();
			$query = $this->db->query($query);
			//echo $this->db->last_query();die();
			if($query->num_rows() ==1 )	return $query->row();
			return FALSE;
		}
	}

	function insertPendingDetails($data)
	{
		//echo "inserting now" ; die();
		$this->db->insert('pending_'.$this->table,$data);
		//echo $this->db->last_query(); die();
	}

	function updatePendingDetailsById($data,$emp_no)
	{
		$this->db->update('pending_'.$this->table,$data,array('emp_no'=>$emp_no));
	}

	function deletePendingDetailsWhere($data)
	{
		$this->db->delete('pending_'.$this->table,$data);
	}

	function getAllProjectsNo()
		{

			$query1 = $this->db->query("

						SELECT DISTINCT(project_no)
						from accounts_project_details

						ORDER BY project_no DESC;
				");

			//$details = $query1->result_array();
			//$query = $this->db->get($this->table);
			return $query1->result();

			/*
			if(sizeof($details) == 0)
			{
				return false;
			}
			else
			{
				$data['details'] = $details;
				return $data;
			}
			*/
		}
		function insert_emaildata($data){   // storing employee email data to permanent table emaildata_emp

			if($this->db->insert('emaildata_emp',$data)){
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		//add by govind sahu for email creation start end
		function insert_email_form_emp($data){ // storing employee basic email data to temporary table email_form_emp

			if($this->db->insert('email_form_emp',$data))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

	    function get_dept_name($id)
		{
			$stmt="select name from departments where id='$id'";
			$result=$this->db->query($stmt);
			if($result->num_rows() >0)
			{
			return $result->result();
			}
			else
			{
			return false;
			}
		}
    //add by govind sahu for email creation start end

		function check_email_data($emp_no)
		{
			$stmt="select Active from emaildata_emp where emp_id='$emp_no'";
			$result=$this->db->query($stmt);
			if($result->num_rows() >0)
			{
			return $result->result();
			}
			else
			{
			return false;
			}
		}
		function update_email_data($data,$emp_no)
		{
			$this->db->update('emaildata_emp',$data,array('emp_id'=>$emp_no));
		}

}
