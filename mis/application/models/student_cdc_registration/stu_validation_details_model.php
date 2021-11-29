<?php

class Stu_validation_details_model extends CI_Model
{
	var $table = 'stu_validation_details';

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function insert($data)
	{
		$this->db->insert($this->table,$data);
	}

	function updateById($data, $admn_no = '')
	{
		$this->db->update($this->table,$data,array('admn_no'=> $admn_no));
	}

	function getValidationDetailsById($admn_no ='')
	{
		$query = $this->db->where('admn_no',$admn_no)->get($this->table);
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
		$this->db->insert('stu_reject_reason',$data);
	}

	function getRejectReasonWhere($data)
	{
		$query = $this->db->get_where('stu_reject_reason',$data);
		if($query->num_rows() == 1)
			return $query->row();
		else
			return FALSE;
	}

	function updateRejectReason($data,$where_array)
	{
		$this->db->update('stu_reject_reason',$data,$where_array);
	}

	function deleteRejectReasonWhere($data)
	{
		$this->db->delete('stu_reject_reason',$data);
	}

	function getPhotoById($admn_no)
	{

	}
	function getStudentDetailsById($admn_no)
	{
		$query = $this->db->where('admn_no',$admn_no)->get('stu_details');
		if($query->num_rows() == 1)
			return $query->row();
	}
	function getPendingStudentDetailsById($admn_no)
	{
		$query = $this->db->where('admn_no',$admn_no)->get('pending_stu_details');
		if($query->num_rows() == 1)
			return $query->row();
	}

	function getStudentAcademic($admn_no)
	{
		$query = $this->db->where('admn_no',$admn_no)->get('stu_academic');
		if($query->num_rows() == 1)
			return $query->row();
	}

	function getPendingStudentAcademic($admn_no)
	{
		$query = $this->db->where('admn_no',$admn_no)->get('pending_stu_academic');
		if($query->num_rows() == 1)
			return $query->row();
	}

	function getStudentAdmnFeeById($admn_no)
	{
		$query = $this->db->where('admn_no',$admn_no)->get('stu_admn_fee');
		if($query->num_rows() == 1)
			return $query->row();
	}
}
/* End of file admn_validation_details_model.php */
/* Location: mis/application/models/admn_validation_details_model.php */