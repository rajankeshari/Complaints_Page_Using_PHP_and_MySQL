<?php

class Project_emp_validation_details_model extends CI_Model
{
	var $table = 'project_emp_validation_details';

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

	function updateById($data, $emp_no = '')
	{
		$this->db->update($this->table,$data,array('emp_no'=> $emp_no));
	}

	function getValidationDetailsById($emp_no ='')
	{
		$query = $this->db->where('emp_no',$emp_no)->get($this->table);
		if($query->num_rows() == 1)
			return $query->row();
		else
			return FALSE;
	}

	function getValidationDetails()
	{
		$query = $this->db->get($this->table);
		if($query->num_rows() == 0)
			return FALSE;
		else
			return $query->result();
	}

	function deleteValidationDetailsWhere($data)
	{
		$this->db->delete($this->table,$data);
	}
}