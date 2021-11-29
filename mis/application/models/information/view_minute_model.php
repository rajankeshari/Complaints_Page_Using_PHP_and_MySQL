<?php

class View_minute_model extends CI_Model
{

	var $table = 'info_minute_details';

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
			FROM info_minute_details
			INNER JOIN user_details 
			 ON info_minute_details.issued_by=user_details.id
			INNER JOIN auth_types 
			 ON info_minute_details.auth_id=auth_types.id
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
			FROM info_minute_details 
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
			ORDER BY info_minute_details.posted_on desc");

		$query1=$this->db->query($query);

		return $query->result();
	}
	
	//return a row for a particular minute number
	function get_minute_row($minute_id)
	{
		$this->db->where('minutes_id',$minute_id);
		$query = $this->db->get($this->table);
		
		return $query->row();
	}
	
	function get_prev_versions($minute_id)
	{
		$table = 'info_minute_modification_details';
		$this->db->where('minutes_id',$minute_id);
		$this->db->order_by('posted_on','desc');
		$query = $this->db->get($table);
		
		return $query->result();
	}
	
	function get_minute_row2($minute_id,$modv)
	{
		$table = 'info_minute_modification_details';
		$this->db->where('minutes_id',$minute_id);
		$this->db->where('modification_value',$modv);
		$query = $this->db->get($table);
		
		return $query->row();
	}
	
	function get_minutes($date='')
	{
		
		if($date == '')	$date = date('Y-m-d');
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
	

			$query=("SELECT info_minute_details.*, user_details.*, auth_types.type as auth_name,
			 departments.name as department, designations.name as designation
			FROM info_minute_details
			INNER JOIN user_details 
			 ON info_minute_details.issued_by=user_details.id
			INNER JOIN auth_types 
			 ON info_minute_details.auth_id=auth_types.id
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
			FROM info_minute_details 
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
			ORDER BY info_minute_details.posted_on desc");

		$query1=$this->db->query($query);

		

		
		return $query1->result();
	}
	function get_new_minute_count()
	{

		$last_login_date = $this->db->query("SELECT `time` FROM `user_login_attempts` where `id` = '".$this->session->userdata('id')."' order by `time` desc limit 1,1");
		if($last_login_date->num_rows!=0)
			$last_login_date=$last_login_date->row()->time;
		else
			$last_login_date = false;

		$date = date('Y-m-d');
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
	

			$query=("SELECT * FROM info_minute_details
			INNER JOIN user_details 
			 ON info_minute_details.issued_by=user_details.id
			INNER JOIN auth_types 
			 ON info_minute_details.auth_id=auth_types.id
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
			FROM info_minute_details 
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
			ORDER BY info_minute_details.posted_on desc");

		$query1=$this->db->query($query);

		if($last_login_date)
			return $query1->num_rows();
		else 	return 0;

	}
	function get_individual($minutes_id)
	{
		$table = 'minutes_individuals';
		$this->db->where('minutes_id',$minutes_id);
		$query = $this->db->get($table);

		return $query;
	}
	function get_group_name($minutes_id)
	{
		$table = 'minutes_group_id';
		$this->db->where('minutes_id',$minutes_id);
		$query = $this->db->get($table);
		//print_r($query->row());
		if($query->row()!=NULL)
			return $query->row()->group_id;
	}
	function get_group_global_name($minutes_id)
	{
		$table = 'minutes_group_global_id';
		$this->db->where('minutes_id',$minutes_id);
		$query = $this->db->get($table);
		//print_r($query->row());
		if($query->row()!=NULL)
			return $query->row()->group_id;
	}
	function get_general_data($minutes_id)
	{
		$query="SELECT meeting_cat,dept_id from minutes_general where minutes_id='$minutes_id' UNION
				SELECT meeting_cat,dept_id  from minutes_gen_emp where minutes_id='$minutes_id'";
		$res=$this->db->query($query);
		//var_dump($res->result());
		return $res->result();
	}
	
	function get_general_stu_data($minutes_id)
	{
		$query="SELECT course_id,semester from minutes_general where minutes_id='$minutes_id'";
		$res=$this->db->query($query);
		return $res->result();
	}
	function get_general_emp_data($minutes_id)
	{
		$query="SELECT emp_auth_id
			 from minutes_gen_emp 
			 where minutes_id='$minutes_id'";
		$res=$this->db->query($query);
		return $res->result();
	}
}

/* End of file view_minute_model.php */
/* Location: mis/application/models/view_minute_model.php */