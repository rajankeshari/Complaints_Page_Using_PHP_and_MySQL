<?php

class Ex_emp_other_details_model extends CI_Model
{

	var $table = 'ex_emp_other_details';
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function insert($data)
	{
		$this->db->insert($this->table,$data);
	}

	function updateByEmpNo($data,$emp_no)
	{
		$this->db->update($this->table,$data,array('emp_no'=>$emp_no));
	}

	function getEmpByEmpNo($emp_no = '')
	{
		if($emp_no == '')
			return FALSE;
		else
		{
			$query=$this->db->where('emp_no',$emp_no)->get($this->table);
			if($query->num_rows() ==1 )	return $query->row();
			return FALSE;
		}
	}
	
	function delete_record($where_array)
	{
		$this->db->delete($this->table,$where_array);
	}
}