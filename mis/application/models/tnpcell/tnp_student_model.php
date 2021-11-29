<?php

class Tnp_student_model extends CI_Model
{
	var $table_jnf_users = 'jnf_users';
  	var $table_jnf_user_details='jnf_user_details';
	var $table_jnf_company_details='jnf_company_details';
	var $table_jnf_eligible_branches='jnf_eligible_branches';
	var $table_jnf_logistic='jnf_logistics';
	var $table_jnf_salary='jnf_salary';
	var $table_jnf_selectioncutoff='jnf_selectioncutoff';
	var $table_jnf_selectionprocess='jnf_selectionprocess';
	var $table_tnp_calender = 'tnp_calender';
	var $table_placement_portal = 'tnp_placement_portal';
	var $table_placement_registration= 'tnp_placement_registration';
	var $table_company_registration= 'tnp_company_registration';
	var $table_stu_academic = "stu_academic";
	var $table_tnp_placement_result = "tnp_placement_result";
	var $table_CD = "cs_courses";		// Authored by MS
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	// Authored by MS
	function get_student_by_course_branch_for_placement($course,$branch)
	{
		$course_duration = $this->db->get_where($this->table_CD,array('id'=>$course))->result();
		$min_sem = 2*($course_duration[0]->duration)-2;		// only final year students will be shown for placements

		$this->db->where("course_id",$course);
		$this->db->where("branch_id",$branch);
		$this->db->where("stu_academic.semester > $min_sem");
		$this->db->join("user_details","user_details.id = stu_academic.admn_no");
		$this->db->order_by("UPPER(first_name),UPPER(last_name)","asc");
		$query = $this->db->get($this->table_stu_academic);
		return $query->result();
	}
	
	// Authored by MS
	function get_student_by_course_branch_for_internship($course,$branch)
	{
		// 5th year students of Dual Degree, Integrated etc courses not shown for internships - Chiranjeev sir A2A
		$course_duration = $this->db->get_where($this->table_CD,array('id'=>$course))->result();
		$min_sem = 2*($course_duration[0]->duration)-4;		// only pre-final year students will be shown for Internships
		$max_sem = 2*($course_duration[0]->duration)-1;

		$this->db->where("course_id",$course);
		$this->db->where("branch_id",$branch);
		$this->db->where("stu_academic.semester > $min_sem");
		$this->db->where("stu_academic.semester < $max_sem");
		$this->db->join("user_details","user_details.id = stu_academic.admn_no");
		$this->db->order_by("UPPER(first_name),UPPER(last_name)","asc");
		$query = $this->db->get($this->table_stu_academic);
		return $query->result();
	}
	
	function get_result_by_stuid_company_id($stu_id,$company_id)
	{
		$this->db->where("stu_id",$stu_id);
		$this->db->where("company_id",$company_id);
		$query = $this->db->get($this->table_tnp_placement_result);
		return $query->result();
	}
	
	function get_students_who_registerd_for_placement($session)
	{
		$this->db->where("session",$session);
		$query = $this->db->get($this->table_placement_registration);	
		return $query->result();
	}
	
	
	
}

/* End of file emp_current_entry_model.php */
/* Location: Codeigniter/application/models/employee/emp_current_entry_model.php */