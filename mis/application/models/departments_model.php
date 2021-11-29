<?php

class Departments_model extends CI_Model
{

	var $table = 'departments';

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function get_departments($type = '')
	{
		if($type !== '')
		{
			$this->db->select('id, name')
					 ->where('type="'.$type.'"','',FALSE)
					 ->order_by('name');
			$query = $this->db->get($this->table);
			return $query->result();
		}
		else
		{
			$query = $this->db->get($this->table);
			return $query->result();
		}
	}

	function getDepartmentById($id = '')
	{
		//echo $id; exit;
		$query = $this->db->get_where($this->table,array('id'=>$id));
		if($query->num_rows() === 1)
			return $query->row();
		else
			return false;
	}

	public function getCourseById($course)
	{
		$query = $this->db->select('name')
						  ->from('cbcs_courses')
						  ->where('id',$course)
						  ->get();

	   return $query->result();

	}

	public function getBranchById($branch)
	{
		$query = $this->db->select('name')
						  ->from('cbcs_branches')
						  ->where('id',$branch)
						  ->get();

	   return $query->result();

	}
}

/* End of file departments_model.php */
/* Location: mis/application/models/departments_model.php */