<?php

class Emp_pay_details_model extends CI_Model
{
	var $table = 'emp_pay_details';
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

	function getEmpPayDetailsbyId($emp_no = '')
	{
		$query = $this->db->select('emp_pay_details.pay_code, pay_band, pay_band_description, grade_pay, basic_pay')
							->from($this->table)
							->join('pay_scales','emp_pay_details.pay_code = pay_scales.pay_code')
							->where('emp_no',$emp_no)
							->get();
		if($query->num_rows() == 1)
			return $query->row();
		else
			return FALSE;
	}
	
	function delete_record($where_array)
	{
		$this->db->delete($this->table,$where_array);
	}

	function getPendingDetailsById($emp_no = '')
	{
		$query = $this->db->select('pending_emp_pay_details.pay_code, pay_band, pay_band_description, grade_pay, basic_pay')
							->from('pending_'.$this->table)
							->join('pay_scales','pending_emp_pay_details.pay_code = pay_scales.pay_code')
							->where('emp_no',$emp_no)
							->get();
		if($query->num_rows() == 1)
			return $query->row();
		else
			return FALSE;
	}

	function insertPendingDetails($data)
	{
		$this->db->insert('pending_'.$this->table,$data);
	}

	function updatePendingDetailsById($data,$emp_no)
	{
		$this->db->update('pending_'.$this->table,$data,array('emp_no'=>$emp_no));
	}

	function deletePendingDetailsWhere($data)
	{
		$this->db->delete('pending_'.$this->table,$data);
	}
}

/* End of file emp_pay_details_model.php */
/* Location: mis/application/models/emp_pay_details_model.php */