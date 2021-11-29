<?php

class Ex_emp_address_model extends CI_Model
{

	var $table = 'ex_emp_address';
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

	function updatePresentAddrByEmpNo($data,$emp_no)
	{
		$this->db->update($this->table,$data,array('emp_no'=>$emp_no,'type'=>'present'));
	}

	function updatePermanentAddrByEmpNo($data,$emp_no)
	{
		$this->db->update($this->table,$data,array('emp_no'=>$emp_no,'type'=>'permanent'));
	}

	function updateCorrespondenceAddrByEmpNo($data,$emp_no)
	{
		$this->db->update($this->table,$data,array('emp_no'=>$emp_no,'type'=>'correspondence'));
	}

	function deleteCorrespondenceAddrByEmpNo($emp_no)
	{
		$this->db->delete($this->table,array('emp_no'=>$emp_no,'type'=>'correspondence'));
	}

	function getAddrByEmpNo($emp_no = '',$type = '')
	{
		if($emp_no == '')
			return FALSE;
		else
		{
			$this->db->where('emp_no',$emp_no);
			if($type != '')	$this->db->where('type',$type);
			$query=$this->db->get($this->table);
			if($query->num_rows() == 1)
				return $query->row();
			else
				return $query->result();
		}
	}
	
	function delete_record($where_array)
	{
		$this->db->delete($this->table,$where_array);
	}
}