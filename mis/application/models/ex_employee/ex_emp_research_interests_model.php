<?php

class Ex_emp_research_interests_model extends CI_Model
{

	var $table = 'ex_emp_research_interests';
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
	
	function getFacultyById($emp_no = '')
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
	
	function delete_record($where_array)
	{
		$this->db->delete($this->table,$where_array);
	}
}
