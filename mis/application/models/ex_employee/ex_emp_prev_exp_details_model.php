<?php

class Ex_emp_prev_exp_details_model extends CI_Model
{
	var $table = 'ex_emp_prev_exp_details';

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

	function getEmpPrevExpById($emp_no = '',$sno=-1)
	{
		if($sno == -1) {
			$query = $this->db->where('emp_no',$emp_no)->get($this->table);
			return $query->result();
		}else {
			$query = $this->db->where('emp_no',$emp_no)->where('sno',$sno)->get($this->table);
			return $query->row();
		}
	}

	function delete_record($where_array)
	{
		$this->db->delete($this->table,$where_array);
	}

	function update_record($data,$where_array)
	{
		$this->db->update($this->table,$data,$where_array);
	}
}