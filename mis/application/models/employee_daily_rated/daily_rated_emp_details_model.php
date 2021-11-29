<?php

class Daily_rated_emp_details_model extends CI_Model
{
	var $table = 'daily_rated_emp_details';
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
		$query = "SELECT emp_no FROM ".$this->table." UNION SELECT emp_no FROM pending_".$this->table." ";
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
		$query = "SELECT a.emp_no, a.salutation, a.first_name, a.middle_name, a.last_name, b.status, b.reason FROM pending_daily_rated_emp_details AS a JOIN daily_rated_emp_validation_details AS b ON a.emp_no = b.emp_no";
		//echo $query ; die();
		$query = $this->db->query($query);
		//echo $this->db->last_query(); die();


		if($query->num_rows() > 0)
			return $query->result();
		else
			return FALSE;
	}

	function getPendingDetailsById($emp_no = '')
	{
		if($emp_no == '')
			return FALSE;
		else
		{
			$query=$this->db->where('emp_no',$emp_no)->get('pending_'.$this->table);
			//echo $query->num_rows() ; die();
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
			$query="SELECT a.emp_no, a.salutation, a.first_name, a.middle_name, a.last_name, a.sex, a.dob, a.joining_date, a.nature_of_hiring, a.photopath, a.id_proof, a.other_relevent_info, b.name as dept_name FROM pending_".$this->table." AS a JOIN departments AS b ON a.dept_id = b.id where a.emp_no = '".$emp_no."'";
			// echo $query ; die();
			$query = $this->db->query($query);
			if($query->num_rows() ==1 )	return $query->row();
			return FALSE;
		}
	}

	function insertPendingDetails($data)
	{
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
}
