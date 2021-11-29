<?php

class Ex_emp_other_card_details_model extends CI_Model
{

	var $table = 'ex_emp_other_card_details';
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function insert($data)
	{
		$this->db->insert($this->table,$data);
	}
	
	function insert_batch($data)
	{
		$this->db->insert_batch($this->table,$data);
	}

	function getCardByEmpNo($emp_no)
	{
		if($emp_no != '')
		{
			$query = $this->db->where('emp_no',$emp_no)->get($this->table);
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
