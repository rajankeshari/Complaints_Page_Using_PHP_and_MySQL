<?php

class Viewprevminute_model extends CI_Model
{

	var $table = 'info_minute_modification_details';

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	//return a list of minute number for a particular employee
	function get_minute_ids()
	{
		
		$id_user=$this->session->userdata('id');
		$auth_id = $this->db->select('auth_id')->where('id',$this->session->userdata('id'))->get('users');
		$meeting_cat = $auth_id->row()->auth_id;
		$notice_type='';

		$dept_id = $this->db->select('dept_id')->where('id',$this->session->userdata('id'))->get('user_details');
		$notice_dept=$dept_id->row()->dept_id;
		$emp_type='';
		$course_type='';
		$sem_type='';

		if($meeting_cat=='stu')
		{
			
			$course_id=$this->db->select('course_id')->where('admn_no',$this->session->userdata('id'))->get('stu_academic');
		
			$course_type=$course_id->row()->course_id;
			$course_id=$this->db->select('semester')->where('admn_no',$this->session->userdata('id'))->get('stu_academic');
			$sem_type=$course_id->row()->semester;
		}
		else if($meeting_cat=='emp')
		{
			$auth_id = $this->db->select('auth_id')->where('emp_no',$this->session->userdata('id'))->get('emp_basic_details');
			$notice_type = $auth_id->row()->auth_id;
			$emp_id = $this->db->select('auth_id')->where('emp_no',$this->session->userdata('id'))->get('emp_basic_details');
			$emp_type=$emp_id->row()->auth_id;
		}
	

			$query=("SELECT minutes_id
			FROM info_minute_modification_details
			INNER JOIN user_details 
			 ON info_minute_modification_details.issued_by=user_details.id
			INNER JOIN auth_types 
			 ON info_minute_modification_details.auth_id=auth_types.id
			INNER JOIN emp_basic_details
			 ON emp_basic_details.emp_no=user_details.id
			INNER JOIN departments
			 ON user_details.dept_id=departments.id
			INNER JOIN designations
			 ON designations.id = emp_basic_details.designation
			WHERE minutes_id in
			(SELECT  minutes_group_id.minutes_id
			FROM notice_group 
			INNER JOIN minutes_group_id 
			ON notice_group.group_id=minutes_group_id.group_id 
			where notice_group.user_id='$id_user' 
			UNION 
			SELECT  minutes_group_global_id.minutes_id
			FROM info_group_global
			INNER JOIN minutes_group_global_id 
			ON info_group_global.group_id=minutes_group_global_id.group_id 
			where info_group_global.user_id='$id_user' 
			UNION 
			SELECT minutes_id 
			FROM minutes_individuals
			where minutes_individuals.user_id='$id_user'
			UNION
			SELECT minutes_id 
			FROM info_minute_modification_details 
			WHERE meeting_cat='all' OR meeting_cat='$meeting_cat'
			 OR meeting_cat='$notice_type'
			UNION 
			SELECT minutes_id 
			FROM minutes_gen_emp
			WHERE  (meeting_cat='all'  OR meeting_cat='$meeting_cat')
			  AND (dept_id='$notice_dept' OR dept_id='all')
			  AND (emp_auth_id='$emp_type' OR emp_auth_id='all')
			UNION
			SELECT minutes_id FROM minutes_general
			WHERE meeting_cat='$meeting_cat' 
			 AND (dept_id='$notice_dept' OR dept_id='all' )
			 AND (course_id='$course_type' OR course_id='all')
			 AND (Semester='$sem_type' OR Semester='all'))
			ORDER BY info_minute_modification_details.posted_on desc");

		$query1=$this->db->query($query);

		return $query->result();
	}
	
	function get_minutes($minute_id)
	{
		//$auth_id = $this->db->select('auth_id')->where('id',$this->session->userdata('id'))->get('users');
		//$minute_cat = $auth_id->row()->auth_id;

		//$where = "minute_id = '".$minute_id."'";
		//$this->db->where($where);
		$this->db->where('minutes_id',$minute_id);
		$query = $this->db->select("info_minute_modification_details.*, user_details.*, auth_types.type as auth_name, departments.name as department, designations.name as designation")
						  ->from($this->table)
						  ->join("user_details", $this->table.".issued_by = user_details.id")
						  ->join("auth_types", $this->table.".auth_id = auth_types.id")
						  ->join("emp_basic_details", "emp_basic_details.emp_no = user_details.id")
						  ->join("departments", "user_details.dept_id = departments.id")
						  ->join("designations", "designations.id = emp_basic_details.designation")
						  ->order_by("info_minute_modification_details.posted_on", "desc")
						  ->get();

		return $query->result();
	}
	
	//return a row for a particular minute id
	function get_minute_row($minute_id)
	{
		$this->db->where('minute_id',$minute_id);
		$query = $this->db->get($this->table);
		
		return $query->row();
	}
	
	function get_prev_versions($minute_id)
	{
		$table = 'info_minute_modification_details';
		$this->db->where('minute_id',$minute_id);
		$this->db->order_by('posted_on','desc');
		$query = $this->db->get($table);
		
		return $query->result();
	}
	
	function get_minute_row2($minute_id,$modv)
	{
		$table = 'info_minute_modification_details';
		$this->db->where('minute_id',$minute_id);
		$this->db->where('modification_value',$modv);
		$query = $this->db->get($table);
		
		return $query->row();
	}
	
}

/* End of file view_minute_model.php */
/* Location: mis/application/models/view_minute_model.php */