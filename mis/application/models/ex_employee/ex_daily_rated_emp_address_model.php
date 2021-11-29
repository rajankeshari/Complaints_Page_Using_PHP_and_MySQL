<?php

class ex_daily_rated_emp_address_model extends CI_Model
{

	var $table = 'ex_daily_rated_emp_address';
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

	function updatePresentAddrById($data,$emp_no)
	{
		$this->db->update($this->table,$data,array('emp_no'=>$emp_no,'type'=>'present'));
	}

	function updatePermanentAddrById($data,$emp_no)
	{
		$this->db->update($this->table,$data,array('emp_no'=>$emp_no,'type'=>'permanent'));
	}
	
	function delete_record($where_array)
	{
		$this->db->delete($this->table,$where_array);
	}

	function getAddrById($id = '',$type = '')
	{
		if($id == '')
			return FALSE;
		else
		{
			$this->db->where('emp_no',$id);
			if($type != '')	$this->db->where('type',$type);
			$query=$this->db->get($this->table);
			if($query->num_rows() == 1)
				return $query->row();
			else
				return $query->result();
		}
	}
}