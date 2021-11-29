<?php

class Emp_validation_details_model extends CI_Model
{
	var $table = 'emp_validation_details';

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function insert($data)
	{
		$this->db->insert($this->table,$data);
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

	function insertRejectReason($data)
	{
		$this->db->insert('emp_reject_reason',$data);
	}

	function getRejectReasonWhere($data)
	{
		$query = $this->db->get_where('emp_reject_reason',$data);
		if($query->num_rows() == 1)
			return $query->row();
		else
			return FALSE;
	}

	function updateRejectReason($data,$where_array)
	{
		$this->db->update('emp_reject_reason',$data,$where_array);
	}

	function deleteRejectReasonWhere($data)
	{
		$this->db->delete('emp_reject_reason',$data);
	}
}
/* End of file emp_validation_details_model.php */
/* Location: mis/application/models/emp_validation_details_model.php */