<?php

class Tnp_result_model extends CI_Model
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
		var $table_tnp_internship_result = "tnp_internship_result"; //19
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function get_placement_result_by_stuid_company_id($stu_id,$company_id) //19
	{
		$this->db->where("stu_id",$stu_id);
		$this->db->where("company_id",$company_id);
		$query = $this->db->get($this->table_tnp_placement_result);
		return $query->result();
	}
	
	function get_internship_result_by_stuid_company_id($stu_id,$company_id) //19
	{
		$this->db->where("stu_id",$stu_id);
		$this->db->where("company_id",$company_id);
		$query = $this->db->get($this->table_tnp_internship_result);
		return $query->result();
	}
	
	function insert_placement_result($result)//19
	{
		$this->db->insert($this->table_tnp_placement_result,$result);
		if($this->db->affected_rows() > 0)
			return true;
		
		return false;	
	}
	
	function insert_internship_result($result) //19
	{
		$this->db->insert($this->table_tnp_internship_result,$result);
		if ($this->db->affected_rows() > 0)
			return true;

		return false;
	}
	
	function get_placement_result_by_company_id($company_id) //19
	{
		$this->db->where("tnp_placement_result.company_id",$company_id);
		
		$this->db->join("stu_academic","stu_academic.admn_no = tnp_placement_result.stu_id");
		$this->db->join("courses","courses.id = stu_academic.course_id");
		$this->db->join("branches","branches.id = stu_academic.branch_id");
		$this->db->join("user_details","user_details.id = stu_academic.admn_no");
		$this->db->join("jnf_user_details","jnf_user_details.company_id = tnp_placement_result.company_id");
		$this->db->join("user_other_details", "user_other_details.id = user_details.id");
		$query = $this->db->get($this->table_tnp_placement_result);
		return $query->result();	
	}
	
	function get_internship_result_by_company_id($company_id) //19
	{
		$this->db->where("tnp_internship_result.company_id",$company_id);
		
		$this->db->join("stu_academic","stu_academic.admn_no = tnp_internship_result.stu_id");
		$this->db->join("courses","courses.id = stu_academic.course_id");
		$this->db->join("branches","branches.id = stu_academic.branch_id");
		$this->db->join("user_details","user_details.id = stu_academic.admn_no");
		$this->db->join("jnf_user_details","jnf_user_details.company_id = tnp_internship_result.company_id");		
		$this->db->join("user_other_details", "user_other_details.id = user_details.id");
		$query = $this->db->get($this->table_tnp_internship_result);
		return $query->result();
	}
	
	function get_placement_result_by_course_branch_id($course_id,$branch_id) //19
	{
		
		$this->db->join("stu_academic","stu_academic.admn_no = tnp_placement_result.stu_id");
		$this->db->join("courses","courses.id = stu_academic.course_id");
		$this->db->join("branches","branches.id = stu_academic.branch_id");
		$this->db->join("user_details","user_details.id = stu_academic.admn_no");
		$this->db->join("user_other_details","user_other_details.id= stu_academic.admn_no");//dinesh
		$this->db->join("jnf_user_details","jnf_user_details.company_id = tnp_placement_result.company_id");
		$this->db->where("courses.id",$course_id);
		$this->db->where("branches.id",$branch_id);
		$query = $this->db->get($this->table_tnp_placement_result);
		return $query->result();
	}
	
	function get_internship_result_by_course_branch_id($course_id,$branch_id) //19
	{

		$this->db->join("stu_academic","stu_academic.admn_no = tnp_internship_result.stu_id");
		$this->db->join("courses","courses.id = stu_academic.course_id");
		$this->db->join("branches","branches.id = stu_academic.branch_id");
		$this->db->join("user_details","user_details.id = stu_academic.admn_no");
		$this->db->join("user_other_details","user_other_details.id= stu_academic.admn_no");//dinesh
		$this->db->join("jnf_user_details","jnf_user_details.company_id = tnp_internship_result.company_id");
		$this->db->where("courses.id",$course_id);
		$this->db->where("branches.id",$branch_id);
		$query = $this->db->get($this->table_tnp_internship_result);
		return $query->result();
	}
	
	function get_complete_placement_result() 
	{
		$this->db->join("stu_academic","stu_academic.admn_no = tnp_placement_result.stu_id");
		//$this->db->join("courses","courses.id = stu_academic.course_id");
		//$this->db->join("branches","branches.id = stu_academic.branch_id");
		$this->db->join("user_details","user_details.id = stu_academic.admn_no");
		$this->db->join("user_other_details","user_other_details.id= stu_academic.admn_no");//dinesh
		$this->db->join("jnf_user_details","jnf_user_details.company_id = tnp_placement_result.company_id");
		$this->db->join("jnf_salary","jnf_salary.company_id = tnp_placement_result.company_id");
		//$this->db->where("courses.id",$course_id);
		//$this->db->where("branches.id",$branch_id);
			//$query = $this->db->get($this->table_tnp_placement_result);
			//return $query->result();
		$query= $this->db->get($this->table_tnp_placement_result)->result();
		//$query = $this->db->query("SELECT * FROM `tnp_placement_result`");//session='".$data['session']."'");
		return $query;	
	}
	
	function get_complete_internship_result() 
	{
		$this->db->join("stu_academic","stu_academic.admn_no = tnp_internship_result.stu_id");
		//$this->db->join("courses","courses.id = stu_academic.course_id");
		//$this->db->join("branches","branches.id = stu_academic.branch_id");
		$this->db->join("user_details","user_details.id = stu_academic.admn_no");
		$this->db->join("user_other_details","user_other_details.id= stu_academic.admn_no");//dinesh
		$this->db->join("jnf_user_details","jnf_user_details.company_id = tnp_internship_result.company_id");
		$this->db->join("inf_salary","inf_salary.company_id = tnp_internship_result.company_id");
		//$this->db->where("courses.id",$course_id);
		//$this->db->where("branches.id",$branch_id);
		$query = $this->db->get($this->table_tnp_internship_result);
		return $query->result();
		//$query = $this->db->query("SELECT * FROM `tnp_placement_result`");//session='".$data['session']."'");
		//return $query->result();	
	}
}

/* End of file emp_current_entry_model.php */
/* Location: Codeigniter/application/models/employee/emp_current_entry_model.php */
