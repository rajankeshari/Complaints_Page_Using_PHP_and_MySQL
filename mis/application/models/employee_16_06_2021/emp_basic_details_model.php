<?php

class Emp_basic_details_model extends CI_Model
{
	var $table = 'emp_basic_details';
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
			$query = $this->db->where('emp_no = "'.$emp_no.'"','',FALSE)->get($this->table);
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
		$query = $this->db->query("SELECT emp_no FROM emp_basic_details AS E INNER JOIN users AS U ON E.emp_no=U.id WHERE U.auth_id = 'emp' or U.auth_id = 'daily_emp' ORDER BY E.emp_no"); // Added for including Temporary MIS users
		//$query = $this->db->query("SELECT emp_no FROM emp_basic_details AS E INNER JOIN users AS U ON E.emp_no=U.id WHERE U.auth_id = 'emp' ORDER BY E.emp_no"); //Original
		if($query->num_rows() > 0)
			return $query->result();
	}
	
	function delete_record($where_array)
	{
		$this->db->delete($this->table,$where_array);
	}

	function getPendingDetailsById($emp_no = '')
	{
		if($emp_no == '')
			return FALSE;
		else
		{
			$query=$this->db->where('emp_no',$emp_no)->get('pending_'.$this->table);
			if($query->num_rows() ==1 )	return $query->row();
			return FALSE;
		}
	}

	function insertPendingDetails($data)
	{
		$this->db->insert('pending_'.$this->table,$data);
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

/* End of file emp_basic_details_model.php */
/* Location: mis/application/models/emp_basic_details_model.php */