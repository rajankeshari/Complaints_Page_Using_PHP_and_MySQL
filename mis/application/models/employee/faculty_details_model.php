<?php

class Faculty_details_model extends CI_Model
{

	var $table = 'faculty_details';
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

	function getFacultyById($emp_no = '')
	{
		if($emp_no != '')
		{
			$query = $this->db->where('emp_no="'.$emp_no.'"','',FALSE)->get($this->table);
			if($query->num_rows() === 1)
				return $query->row();
			else
				return FALSE;
		}
		else
			return FALSE;
	}
	
	function delete_record($where_array)
	{
		$this->db->delete($this->table,$where_array);
	}

	function getPendingDetailsById($emp_no = '')
	{
		if($emp_no == '')
			return FALSE;
		else
		{
			$query=$this->db->where('emp_no',$emp_no)->get('pending_'.$this->table);
			if($query->num_rows() ==1 )	return $query->row();
			return FALSE;
		}
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
	
	function get_faculty_created_email_id_by_emp($emp_no)
	{
		$myquery = "select t.domain_name from emaildata_emp t where t.emp_id='".$emp_no."';";

        $query = $this->db->query($myquery)->row();

        if ($query) 
        {
            return $query->domain_name;
        }
        else 
        {
            return false;
        }
	}
}

/* End of file faculty_details_model.php */
/* Location: mis/application/models/faculty_details_model.php */