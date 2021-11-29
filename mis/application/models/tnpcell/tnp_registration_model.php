<?php

class Tnp_registration_model extends CI_Model
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
	var $table_tnp_registration = 'tnp_reg_portal';
	var $tnp_company_registration = 'tnp_company_registration';
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	
	
	function get_companies_with_registration_open()
	{
		$this->db->where("tnp_registration.date_from >= ".date("Y-m-d")." AND tnp_registration.date_to <= ".date("Y-m-d")."");
		$query = $this->db->get($this->table_tnp_registration);	
		
		return $query->result();
	}
	
	//this function will return the company info of those companies which have status != Visited.
	function get_companies_with_registration_available()
	{
		//also add tnp_calender. AND tnp_calender.date_from >= ".date("yyyy-mm-dd")."
		$query = $this->db->query("SELECT tnp_reg_portal.date_from as portal_date_from,tnp_reg_portal.date_to as portal_date_to,tnp_calender.company_id,tnp_calender.date_from,tnp_calender.date_to,tnp_calender.status,jnf_user_details.company_name, COUNT(DISTINCT tcr.stu_id) as stu_count 

			FROM tnp_calender 
			LEFT JOIN tnp_reg_portal ON tnp_reg_portal.company_id = tnp_calender.company_id 
			INNER JOIN jnf_user_details ON jnf_user_details.company_id = tnp_calender.company_id 
			LEFT JOIN tnp_company_registration as tcr ON tcr.company_id = tnp_calender.company_id 
			LEFT JOIN tnp_placement_registration as tpr ON tpr.stu_id = tcr.stu_id 
			WHERE tnp_calender.status != 'Visited' AND tnp_calender.stu_visibility = 1  AND (tpr.blocked = 0 OR tpr.blocked IS NULL) GROUP BY tnp_calender.company_id;");
		return $query->result();
	}

	function get_registered_students_details_companywise($company_id,$registered_for) {
		// sending only admission numbers thus far
		//$this->db->select('stu_id');
		//$this->db->where('company_id', $company_id);

		//return $this->db->get('tnp_company_registration')->result();
		$query = $this->db->query("SELECT tnp_reg_portal.date_from as portal_date_from,tnp_reg_portal.date_to as portal_date_to,tnp_calender.company_id,tnp_calender.date_from,tnp_calender.date_to,tnp_calender.status,jnf_user_details.company_name, COUNT(DISTINCT tcr.stu_id) as stu_count 

			FROM tnp_calender 
			LEFT JOIN tnp_reg_portal ON tnp_reg_portal.company_id = tnp_calender.company_id 
			INNER JOIN jnf_user_details ON jnf_user_details.company_id = tnp_calender.company_id 
			LEFT JOIN tnp_company_registration as tcr ON tcr.company_id = tnp_calender.company_id 
			LEFT JOIN tnp_placement_registration as tpr ON tpr.stu_id = tcr.stu_id 
			WHERE tnp_calender.status != 'Visited' AND tnp_calender.stu_visibility = 1  AND (tpr.blocked = 0 OR tpr.blocked IS NULL) AND tnp_calender.company_id = '".$company_id."' AND tcr.registered_for = '".$registered_for."'
			GROUP BY tnp_calender.company_id;");
		return $query->result();
	}

//dinesh
	function get_registered_student_count_comapanywise_for_internship($company_id){
            $query=$this->db->query("SELECT  COUNT(tcr.stu_id) FROM tnp_company_registration as tcr WHERE tcr.company_id='".$company_id."' AND tcr.registered_for =1 GROUP BY tcr.company_id ;");

		return $query->result();
		}
function get_registered_student_count_comapanywise_for_placement($company_id){
            $query=$this->db->query("SELECT  COUNT(tcr.stu_id) FROM tnp_company_registration as tcr WHERE tcr.company_id='".$company_id."'  AND tcr.registered_for =2 GROUP BY tcr.company_id ;");

		return $query->result();
		}
function get_companies_with_registration_available_for_placement()
	{
		//also add tnp_calender. AND tnp_calender.date_from >= ".date("yyyy-mm-dd")."
		$query = $this->db->query("SELECT tnp_reg_portal.date_from as portal_date_from,tnp_reg_portal.date_to as portal_date_to,tnp_calender.company_id,tnp_calender.date_from,tnp_calender.date_to,tnp_calender.status,jnf_user_details.company_name,COUNT(tcr.stu_id) as stu_count 

			FROM tnp_calender 
			LEFT JOIN tnp_reg_portal ON tnp_reg_portal.company_id = tnp_calender.company_id 
			INNER JOIN jnf_user_details ON jnf_user_details.company_id = tnp_calender.company_id 
			LEFT JOIN tnp_company_registration as tcr ON tcr.company_id = tnp_calender.company_id 
			LEFT JOIN tnp_placement_registration as tpr ON tpr.stu_id = tcr.stu_id 
			WHERE tnp_calender.status != 'Visited' AND tnp_calender.stu_visibility = 1 AND tcr.registered_for=2 AND (tpr.blocked = 0 OR tpr.blocked IS NULL) GROUP BY tnp_calender.company_id;");
		return $query->result();
	}
	
	function get_companies_with_reg_portal_open()
	{
		//add this later in where clause AND tnp_calender.date_from >= ".date("yyyy-mm-dd")."
		$query = $this->db->query("SELECT tnp_reg_portal.date_from as portal_date_from,tnp_reg_portal.date_to as portal_date_to,tnp_calender.company_id,tnp_calender.date_from,tnp_calender.date_to,tnp_calender.status,jnf_user_details.company_name FROM tnp_calender INNER JOIN tnp_reg_portal ON tnp_reg_portal.company_id = tnp_calender.company_id INNER JOIN jnf_user_details ON jnf_user_details.company_id = tnp_calender.company_id WHERE tnp_calender.status != 'Visited' AND tnp_calender.stu_visibility = 1 ");
		return $query->result();
	}
	
	function get_companies_with_reg_portal_open_with_eligibility_criteria($stu_id,$course_branch_id)
	{
		//OGPA case needs to be handeled..
		//$gpa = $this->db->query("SELECT cgpa,semester FROM final_semwise_marks_foil WHERE admn_no='".$stu_id."' ORDER BY semester desc");
		$query = $this->db->query("SELECT  distinct tnp_reg_portal.date_from as portal_date_from,tnp_reg_portal.date_to as portal_date_to
		,tnp_calender.company_id,tnp_calender.status,
		jnf_user_details.company_name 
		FROM tnp_calender 
		INNER JOIN tnp_reg_portal ON tnp_reg_portal.company_id = tnp_calender.company_id 
		INNER JOIN jnf_user_details ON jnf_user_details.company_id = tnp_calender.company_id 
		INNER JOIN jnf_eligible_branches ON jnf_eligible_branches.company_id = tnp_calender.company_id
		INNER JOIN jnf_selectioncutoff ON jnf_selectioncutoff.company_id=jnf_eligible_branches.company_id
		WHERE tnp_calender.status != 'Visited' AND tnp_calender.stu_visibility = 1
		AND tnp_reg_portal.date_from <= now() AND tnp_reg_portal.date_to >= now()
		AND jnf_eligible_branches.course_branch_id = '".$course_branch_id."'");//Kuldeep removed-->tnp_calender.date_from,tnp_calender.date_to, 18.10.17

		return $query->result();
	}
	//Kuldeep ->below function
	function get_companies_with_reg_portal_open_with_eligibility_criteria_2($stu_id,$course_branch_id)
	{
		//OGPA case needs to be handeled..
		//$gpa = $this->db->query("SELECT cgpa,semester FROM final_semwise_marks_foil WHERE admn_no='".$stu_id."' ORDER BY semester desc");
		$query = $this->db->query("SELECT  distinct tnp_reg_portal.date_from as portal_date_from,tnp_reg_portal.date_to as portal_date_to
		,tnp_calender.company_id,tnp_calender.status,tnp_company_registration.registered_for,
		jnf_user_details.company_name 
		FROM tnp_calender 
		INNER JOIN tnp_company_registration ON tnp_company_registration.company_id = tnp_calender.company_id 
		INNER JOIN tnp_reg_portal ON tnp_reg_portal.company_id = tnp_calender.company_id 
		INNER JOIN jnf_user_details ON jnf_user_details.company_id = tnp_calender.company_id 
		INNER JOIN jnf_eligible_branches ON jnf_eligible_branches.company_id = tnp_calender.company_id
		INNER JOIN jnf_selectioncutoff ON jnf_selectioncutoff.company_id=jnf_eligible_branches.company_id
		WHERE tnp_calender.status != 'Visited' AND tnp_calender.stu_visibility = 1
		AND tnp_reg_portal.date_from <= now() AND tnp_reg_portal.date_to >= now()
		AND jnf_eligible_branches.course_branch_id = '".$course_branch_id."'");//Kuldeep removed-->tnp_calender.date_from,tnp_calender.date_to, 18.10.17

		return $query->result();
	}
	
	function get_company_with_portal_date($company_id)
	{
		$query = $this->db->get_where($this->table_tnp_registration,array("company_id"=>$company_id));
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;	
	}
	
	function insert_portal_date($company_id,$date_from,$date_to)
	{
		$data = array("company_id"=>$company_id,"date_from"=>$date_from,"date_to"=>$date_to);
		$query = $this->db->insert($this->table_tnp_registration,$data);	
		
		if($this->db->affected_rows() > 0)
			return true;
		return false;
	}
	
	function update_portal_date($company_id,$date_from,$date_to)
	{
		$data = array("date_from"=>$date_from,"date_to"=>$date_to);
		$this->db->where("company_id",$company_id);
		$query = $this->db->update($this->table_tnp_registration,$data);	
		
		if($this->db->affected_rows() > 0)
			return true;
		return false;
	}
	
	//these functions insert update delete and select student registration info for companies.
	function insert_stu_for_company_registration($data)
	{
		$this->db->insert($this->tnp_company_registration,$data);
		if($this->db->affected_rows() > 0)
			return true;
		return false;
	}
	
	function update_stu_for_company_registration($data)
	{
		$this->db->where("stu_id",$data['stu_id']);
		$this->db->where("company_id",$data['company_id']);
		$this->db->update($this->tnp_company_registration,$data);
		if($this->db->affected_rows() > 0)
			return true;
		return false;
	}
	
	function select_stu_for_company_registration($data)
	{
		// $this->db->where("stu_id",$data['stu_id']);
		// $this->db->where("company_id",$data['company_id']);
		// $query = $this->db->get($this->tnp_company_registration);
		$query = $this->db->query("SELECT * FROM tnp_company_registration 
									WHERE stu_id='".$data['stu_id']."' AND
									company_id='".$data['company_id']."'");
		if($query->num_rows() > 0)
			return $query->result();
		return false;
	}
	
	function select_all_companies_registered_by_stu($stu_id)
	{
		$this->db->where("stu_id",$stu_id);
		$query = $this->db->get($this->tnp_company_registration);
		if($query->num_rows() > 0)
			return $query->result();
		return false;
	}
	
	function delete_stu_for_company_registration($data)
	{
		$this->db->where("stu_id",$data['stu_id']);
		$this->db->where("company_id",$data['company_id']);
		$this->db->delete($this->tnp_company_registration);
		if($this->db->affected_rows() > 0)
			return true;
		return false;
	}
	
}

?>
