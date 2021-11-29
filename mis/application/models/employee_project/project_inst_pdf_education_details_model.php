<?php

class Project_inst_pdf_education_details_model extends CI_Model
{
	var $table = 'project_inst_pdf_education_details';

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

	function getEmpEduById($emp_no = '',$sno=-1)
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

	function getPendingDetailsById($emp_no = '',$sno=-1)
	{
		if($sno == -1) {
			$query = $this->db->where('emp_no',$emp_no)->get('pending_'.$this->table);
			return $query->result();
		}else {
			$query = $this->db->where('emp_no',$emp_no)->where('sno',$sno)->get('pending_'.$this->table);
			return $query->row();
		}
	}

	function copyDetailsToPendingById($emp_no='')
	{
		$query = $this->db->where('emp_no',$emp_no)->get($this->table);
		foreach ($query->result() as $row) {
			$this->db->insert('pending_'.$this->table,$row);
		}
	}

	function MoveDetailsFromPendingById($emp_no='')
	{
		//copy details to real table from pending table
		$query = $this->db->where('emp_no',$emp_no)->get('pending_'.$this->table);
		foreach ($query->result() as $row) {
			$this->db->insert($this->table,$row);
		}

		//delete pending details from pending table
		$this->db->delete('pending_'.$this->table,array('emp_no' => $emp_no));
	}

	function insertPendingDetails($data)
	{
		$this->db->insert_batch('pending_'.$this->table,$data);
	}

	function updatePendingDetailsWhere($data,$where_array)
	{
		$this->db->update('pending_'.$this->table,$data,$where_array);
	}

	function deletePendingDetailsWhere($where_array)
	{
		$this->db->delete('pending_'.$this->table,$where_array);
	}
}

/* End of file emp_education_details_model.php */
/* Location: mis/application/models/emp_education_details_model.php */