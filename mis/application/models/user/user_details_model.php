<?php

class User_details_model extends CI_Model
{

	var $table = 'user_details';
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function insert($data)
	{
		$this->db->insert($this->table,$data);
		//echo $this->db->last_query(); die();  
		//print_r($data); echo 'user_details' ; die();
	}

	function updateById($data,$id)
	{
		$this->db->update($this->table,$data,array('id'=>$id));
	}

	function getUserById($id = '')
	{
		if($id == '')
			return FALSE;
		else
		{
			$query=$this->db->where('id',$id)->get($this->table);
			if($query->num_rows() ==1 )	return $query->row();
			return FALSE;
		}
	}

	function getEmpNamesByDept($dept = '')
	{
		if($dept == '')
			return FALSE;
		else
		{
			$query=$this->db->select('users.id, salutation, first_name, middle_name, last_name, dept_id')
								->from('user_details')
								->join('users','users.id = user_details.id')
								->where('dept_id',$dept)
								->where('auth_id','emp')
								->order_by('first_name','ASC')
								->get();
			return $query->result();
		}
	}
		function getEmpNamesByDeptFT($dept = '')
	{
		if($dept == '')
			return FALSE;
		else
		{
			/*$query=$this->db->select('user_details.id, user_details.salutation, user_details.first_name, user_details.middle_name, user_details.last_name, user_details.dept_id')
								->from('user_details')
								->join('emp_basic_details','emp_basic_details.emp_no = user_details.id')
								->join('users','users.id = user_details.id')
								->where('user_details.dept_id',$dept)
								->where('emp_basic_details.auth_id','ft')
								->where('users.status','A')
								->order_by('user_details.first_name','ASC')
								->get();
			return $query->result();*/
			$basic_query = "select user_details.id, user_details.salutation, user_details.first_name, user_details.middle_name, 
user_details.last_name, user_details.dept_id 
								FROM user_details
								JOIN emp_basic_details on emp_basic_details.emp_no = user_details.id
								JOIN users ON users.id=user_details.id
								WHERE user_details.dept_id='".$dept."' AND users.`status`='A'
								AND (emp_basic_details.auth_id='ft'||emp_basic_details.designation='spo')
								order by user_details.first_name asc";


					return $this->db->query($basic_query)->result();
		}
	}

	
	
	function getStuNameByDeptAndCourse($dept = '',$course)
	{
		if($dept == '')
			return FALSE;
		else
		{
			$array=array('course_id' => $course,'dept_id'=>$dept );
			$query=$this->db->select('user_details.id, salutation, first_name, middle_name, last_name, dept_id')
								->from('user_details')
								->join('stu_academic','stu_academic.admn_no = user_details.id')
								->where($array)
								->get();
			return $query->result();
		}
	}
	
	function delete_record($where_array)
	{
		$this->db->delete($this->table,$where_array);
	}

	function getPendingDetailsById($id = '')
	{
		if($id == '')
			return FALSE;
		else
		{
			$query=$this->db->where('id',$id)->get('pending_'.$this->table);
			if($query->num_rows() ==1 )	return $query->row();
			return FALSE;
		}
	}

	function insertPendingDetails($data)
	{
		$this->db->insert('pending_'.$this->table,$data);
	}

	function updatePendingDetailsById($data,$id)
	{
		$this->db->update('pending_'.$this->table,$data,array('id'=>$id));
	}

	function deletePendingDetailsWhere($data)
	{
		$this->db->delete('pending_'.$this->table,$data);
	}
}


/* End of file user_details_model.php */
/* Location: mis/application/models/user_details_model.php */