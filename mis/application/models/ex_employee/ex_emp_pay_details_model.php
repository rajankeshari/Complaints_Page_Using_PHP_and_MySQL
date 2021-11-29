<?php

class Ex_emp_pay_details_model extends CI_Model
{
	var $table = 'ex_emp_pay_details';
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function insert($data)
	{
		$this->db->insert($this->table,$data);
	}

	function updateByEmpNo($data,$emp_no)
	{
		$this->db->update($this->table,$data,array('emp_no'=>$emp_no));
	}

	function getEmpPayDetailsbyEmpNo($emp_no = '')
	{
		$query = $this->db->select('ex_emp_pay_details.pay_code, pay_band, pay_band_description, grade_pay, basic_pay')
							->from($this->table)
							->join('pay_scales','ex_emp_pay_details.pay_code = pay_scales.pay_code')
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
}