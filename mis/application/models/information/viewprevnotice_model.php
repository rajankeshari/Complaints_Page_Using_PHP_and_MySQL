<?php

class Viewprevnotice_model extends CI_Model
{

	var $table = 'info_notice_modification_details';

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	//return a list of minute number for a particular employee
	function get_notice_ids()
	{
		
		$$id_user=$this->session->userdata('id');
		$auth_id = $this->db->select('auth_id')->where('id',$this->session->userdata('id'))->get('users');
		$notice_cat = $auth_id->row()->auth_id;
		$notice_type='';

		$dept_id = $this->db->select('dept_id')->where('id',$this->session->userdata('id'))->get('user_details');
		$notice_dept=$dept_id->row()->dept_id;
		$emp_type='';
		$course_type='';
		$sem_type='';

		if($notice_cat=='stu')
		{
			
			$course_id=$this->db->select('course_id')->where('admn_no',$this->session->userdata('id'))->get('stu_academic');
		
			$course_type=$course_id->row()->course_id;
			$course_id=$this->db->select('semester')->where('admn_no',$this->session->userdata('id'))->get('stu_academic');
			$sem_type=$course_id->row()->semester;
		}
		else if($notice_cat=='emp')
		{
			$auth_id = $this->db->select('auth_id')->where('emp_no',$this->session->userdata('id'))->get('emp_basic_details');
			$notice_type = $auth_id->row()->auth_id;
			$emp_id = $this->db->select('auth_id')->where('emp_no',$this->session->userdata('id'))->get('emp_basic_details');
			$emp_type=$emp_id->row()->auth_id;
		}
	

			$query=("SELECT notice_id
			FROM info_notice_modification_details
			WHERE notice_id in
			(SELECT  notice_group_id.notice_id
			FROM notice_group 
			INNER JOIN notice_group_id 
			ON notice_group.group_id=notice_group_id.group_id 
			where notice_group.user_id='$id_user' 
			UNION 
			SELECT  notice_group_global_id.notice_id
			FROM info_group_global 
			INNER JOIN notice_group_global_id 
			ON info_group_global.group_id=notice_group_global_id.group_id 
			where info_group_global.user_id='$id_user' 
			UNION 
			SELECT notice_id 
			FROM notice_individuals
			where notice_individuals.user_id='$id_user'
			UNION
			SELECT notice_id 
			FROM iinfo_notice_modification_details 
			WHERE notice_cat='all' OR notice_cat='$notice_cat'
			 OR notice_cat='$notice_type'
			UNION 
			SELECT notice_id 
			FROM notice_gen_emp
			WHERE  (notice_cat='all'  OR notice_cat='$notice_cat')
			  AND (dept_id='$notice_dept' OR dept_id='all')
			  AND (emp_auth_id='$emp_type' OR emp_auth_id='all')
			UNION
			SELECT notice_id FROM notice_general
			WHERE notice_cat='$notice_cat' 
			 AND (dept_id='$notice_dept' OR dept_id='all' )
			 AND (course_id='$course_type' OR course_id='all')
			 AND (Semester='$sem_type' OR Semester='all'))
			ORDER BY info_notice_modification_details.posted_on desc");

		$query1=$this->db->query($query);

		return $query1->result();
	}
	
	function get_notices($notice_id)
	{
		//$auth_id = $this->db->select('auth_id')->where('id',$this->session->userdata('id'))->get('users');
		//$notice_cat = $auth_id->row()->auth_id;

		//$where = "notice_id = '".$notice_id."'";
		//$this->db->where($where);
		$this->db->where('notice_id',$notice_id);
		$query = $this->db->select("info_notice_modification_details.*, user_details.*, auth_types.type as auth_name, departments.name as department, designations.name as designation")
						  ->from($this->table)
						  ->join("user_details", $this->table.".issued_by = user_details.id")
						  ->join("auth_types", $this->table.".auth_id = auth_types.id")
						  ->join("emp_basic_details", "emp_basic_details.emp_no = user_details.id")
						  ->join("departments", "user_details.dept_id = departments.id")
						  ->join("designations", "designations.id = emp_basic_details.designation")
						  ->order_by("info_notice_modification_details.posted_on", "desc")
						  ->get();

		return $query->result();
	}
	
	//return a row for a particular notice id
	function get_notice_row($notice_id)
	{
		$this->db->where('notice_id',$notice_id);
		$query = $this->db->get($this->table);
		
		return $query->row();
	}
	
	function get_prev_versions($notice_id)
	{
		$table = 'info_notice_modification_details';
		$this->db->where('notice_id',$notice_id);
		$this->db->order_by('posted_on','desc');
		$query = $this->db->get($table);
		
		return $query->result();
	}
	
	function get_notice_row2($notice_id,$modv)
	{
		$table = 'info_notice_modification_details';
		$this->db->where('notice_id',$notice_id);
		$this->db->where('modification_value',$modv);
		$query = $this->db->get($table);
		
		return $query->row();
	}
	
}

/* End of file view_notice_model.php */
/* Location: mis/application/models/view_notice_model.php */