<?php

class Ex_daily_rated_emp_details_model extends CI_Model
{
	var $table = 'ex_daily_rated_emp_details';
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function insert($data)
	{
		$this->db->insert($this->table,$data);
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
}
