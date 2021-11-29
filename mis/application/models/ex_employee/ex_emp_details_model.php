<?php

class Ex_emp_details_model extends CI_Model
{

	var $table = 'ex_emp_details';
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

	function getEmpByEmpNo($emp_no = '')
	{
		if($emp_no == '')
			return FALSE;
		else
		{
			$query=$this->db->where('emp_no',$emp_no)->get($this->table);
			if($query->num_rows() ==1 )	return $query->row();
			return FALSE;
		}
	}
	
	function getAllEmployeesId()
	{
		/*$query = $this->db->select('emp_no')->order_by('emp_no')->get($this->table);
		if($query->num_rows() > 0)
			return $query->result();*/
			$query = $this->db->get('ex_emp_details');
    $array = array();

    foreach($query->result() as $row)
    {
        array_push($array,$row->emp_no); // add each user id to the array
    }

    return $array;

    
	}
	
	function delete_record($where_array)
	{
		$this->db->delete($this->table,$where_array);
	}
}
