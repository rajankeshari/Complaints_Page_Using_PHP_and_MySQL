<?php

class Daily_rated_emp_address_model extends CI_Model
{

	var $table = 'daily_rated_emp_address';
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

	function getPendingDetailsById($emp_no = '',$type = '')
	{
		if($emp_no == '')
			return FALSE;
		else
		{
			$this->db->where('emp_no',$emp_no);
			if($type != '')	$this->db->where('type',$type);
			$query=$this->db->get('pending_'.$this->table);
			if($query->num_rows() == 1)
				return $query->row();
			else
				return $query->result();
		}
	}

	function insertPendingDetails($data)
	{
		$this->db->insert_batch('pending_'.$this->table,$data);
	}

	function updatePendingPermanentDetailsById($data,$id)
	{
		$this->db->update('pending_'.$this->table,$data,array('emp_no'=>$id,'type'=>'permanent'));
	}

	function updatePendingPresentDetailsById($data,$emp_no)
	{
		$this->db->update('pending_'.$this->table,$data,array('emp_no'=>$emp_no,'type'=>'present'));
	}

	function deletePendingDetailsWhere($data)
	{
		$this->db->delete('pending_'.$this->table,$data);
	}
}