<?php

class Tnp_basic_model extends CI_Model
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
	var $table_course = 'cs_courses';
	var $table_jnf_course = 'jnf_courses';
	var $table_course_branch = 'course_branch';	//Kuldeep 
	var $table_reg_regular_form = 'reg_regular_form';	//Kuldeep 
	var $table_placement_result = 'tnp_placement_result'; //10 may
//----------------------------Dinesh-----------------------------
	var $table_inf_company_details='inf_company_details';
	var $table_tnp_calender_inf='tnp_calender_inf';
	var $table_inf_eligible_branches='inf_eligible_branches';
	var $table_inf_course = 'inf_courses';
	var $table_inf_salary='inf_salary';
    var $table_inf_selectioncutoff='inf_selectioncutoff';
    var $table_inf_selectionprocess='inf_selectionprocess';
	var $table_inf_logistic='inf_logistics';
	var $table_internship_result = 'tnp_internship_result'; //10 may
//-----------------------------------dinesh----------------------------
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	function get_company_basic_details($company_id = '')
	{
		if($company_id != '')
			$this->db->where("jnf_users.company_id",$company_id);
			
		$this->db->distinct();
		$this->db->join($this->table_jnf_user_details, "jnf_user_details.company_id = jnf_users.company_id");
		$query = $this->db->get($this->table_jnf_users);
		return $query->result();	
	}
	
	function get_company_basic_details_jnf($company_id = '')
	{
		if($company_id != '')
			$this->db->where("jnf_users.company_id",$company_id);
			
		$this->db->distinct();
		$this->db->join($this->table_jnf_user_details, "jnf_user_details.company_id = jnf_users.company_id");
		$this->db->where('jnf_user_details.filled_jnf', 1);
		$query = $this->db->get($this->table_jnf_users);
		return $query->result();	
	}

	function get_company_basic_details_inf($company_id = '')
	{
		if($company_id != '')
			$this->db->where("jnf_users.company_id",$company_id);
			
		$this->db->distinct();
		$this->db->join($this->table_jnf_user_details, "jnf_user_details.company_id = jnf_users.company_id");
		$this->db->where('jnf_user_details.filled_inf', 1);
		$query = $this->db->get($this->table_jnf_users);
		return $query->result();	
	}
	
	function get_company_basic_details_not_given_dates($company_id)
	{
		if($company_id != '')
			$this->db->where("jnf_users.company_id",$company_id);
		
		// $this->db->where("jnf_users.company_id NOT IN (SELECT company_id FROM tnp_calender)");
		// $this->db->distinct();
		// $this->db->join($this->table_jnf_user_details,"jnf_user_details.company_id = jnf_users.company_id");
		
		// $query = $this->db->get($this->table_jnf_users);
		$query = $this->db->get($this->table_jnf_user_details);
		return $query->result();	
	}
	
	function get_alloted_company_basic_details($company_id)
	{
		if($company_id != '')
      $this->db->where("jnf_users.company_id",$company_id);
			
		$this->db->where("jnf_users.company_id IN (SELECT company_id FROM tnp_calender)");
		//dont show companies for which status is rejected.
		//$this->db->where("tnp_calender.status  != 'Rejected'");
		$this->db->join($this->table_jnf_user_details,"jnf_user_details.company_id = jnf_users.company_id");
		$this->db->join($this->table_tnp_calender,"tnp_calender.company_id = jnf_users.company_id");
		
		$query = $this->db->from($this->table_jnf_users);
		$query = $this->db->get();
		
		return $query->result();	
	}

	function get_alloted_company_basic_details_inf($company_id)
	{
		if($company_id != '')
      $this->db->where("jnf_users.company_id",$company_id);
			
		$this->db->where("jnf_users.company_id IN (SELECT company_id FROM tnp_calender_inf)");
		//dont show companies for which status is rejected.
		//$this->db->where("tnp_calender.status  != 'Rejected'");
		$this->db->join($this->table_jnf_user_details,"jnf_user_details.company_id = jnf_users.company_id");
		$this->db->join($this->table_tnp_calender_inf,"tnp_calender_inf.company_id = jnf_users.company_id");
		
		$query = $this->db->from($this->table_jnf_users);
		$query = $this->db->get();
		
		return $query->result();	
	}
	
	function get_visited_companies_details($for="", $distinct="") // Details of Placement companies or Internship Companies //19
	{
		$this->db->where("tnp_calender.status","Visited");
		if($for!="")		
			$this->db->where("jnf_user_details.$for","1");
		$this->db->where("jnf_users.company_id IN (SELECT company_id FROM tnp_calender)");
		//dont show companies for which status is rejected.
		//$this->db->where("tnp_calender.status  != 'Rejected'");
		$this->db->join($this->table_jnf_user_details,"jnf_user_details.company_id = jnf_users.company_id");
		$this->db->join($this->table_tnp_calender,"tnp_calender.company_id = jnf_users.company_id");
		
		$query = $this->db->from($this->table_jnf_users);

		if ($distinct != "") {
			$this->db->select("jnf_user_details.company_id, jnf_user_details.company_name, session");
			$this->db->distinct();
		}

		$query = $this->db->get();
		
		return $query->result();	
	}
	
	/* //19
	function get_visited_companies_details()
	{
		$this->db->where("tnp_calender.status","Visited");
			
		$this->db->where("jnf_users.company_id IN (SELECT company_id FROM tnp_calender)");
		//dont show companies for which status is rejected.
		//$this->db->where("tnp_calender.status  != 'Rejected'");
		$this->db->join($this->table_jnf_user_details,"jnf_user_details.company_id = jnf_users.company_id");
		$this->db->join($this->table_tnp_calender,"tnp_calender.company_id = jnf_users.company_id");
		
		$query = $this->db->from($this->table_jnf_users);
		$query = $this->db->get();
		
		return $query->result();	
	}*/
	

	function get_visited_companies_inf_details()
	{
		$this->db->where("tnp_calender_inf.status","Visited");
			
		$this->db->where("jnf_users.company_id IN (SELECT company_id FROM tnp_calender_inf)");
		//dont show companies for which status is rejected.
		//$this->db->where("tnp_calender.status  != 'Rejected'");
		$this->db->join($this->table_jnf_user_details,"jnf_user_details.company_id = jnf_users.company_id");
		$this->db->join($this->table_tnp_calender_inf,"tnp_calender_inf.company_id = jnf_users.company_id");
		
		$query = $this->db->from($this->table_jnf_users);
		$query = $this->db->get();
		
		return $query->result();	
	}

	function get_company_list()
	{		
		$query = $this->db->query("SELECT user_id,jnf_users.company_id,jnf_user_details.company_name,jnf_user_details.website,jnf_user_details.session,		jnf_salary.ctc,jnf_salary.gross,jnf_salary.take_home,jnf_company_details.category,jnf_company_details.industry,jnf_company_details.job_designation,
		jnf_company_details.job_description,jnf_company_details.job_posting,tnp_calender.date_from,tnp_calender.date_to,tnp_calender.status,
		tnp_calender.ppt,tnp_calender.test,tnp_calender.interview FROM jnf_users INNER JOIN jnf_user_details ON jnf_user_details.company_id = jnf_users.company_id INNER JOIN jnf_salary ON jnf_salary.company_id = jnf_users.company_id INNER JOIN jnf_company_details ON jnf_company_details.company_id = jnf_users.company_id LEFT JOIN tnp_calender ON tnp_calender.company_id = jnf_users.company_id");
		return $query->result();	
	}

	//------------------------------------------------------dinesh-------------------------------------------------------
	function get_company_inf_list()
	{		
		$query = $this->db->query("SELECT user_id,jnf_users.company_id,jnf_user_details.company_name,jnf_user_details.website,jnf_user_details.session,		inf_salary.stipend ,inf_company_details.category,inf_company_details.industry,inf_company_details.job_designation,inf_company_details.job_description,inf_company_details.job_posting,tnp_calender_inf.date_from,tnp_calender_inf.date_to,tnp_calender_inf.status, tnp_calender_inf.ppt,tnp_calender_inf.test,tnp_calender_inf.interview FROM jnf_users INNER JOIN jnf_user_details ON jnf_user_details.company_id = jnf_users.company_id INNER JOIN inf_salary ON inf_salary.company_id = jnf_users.company_id INNER JOIN inf_company_details ON inf_company_details.company_id = jnf_users.company_id LEFT JOIN tnp_calender_inf ON tnp_calender_inf.company_id = jnf_users.company_id");
		return $query->result();	
	}
	//----------------------------------------------------------------------------------------------------
	
	function get_company_list_visible_to_student()
	{		
		$query = $this->db->query("SELECT distinct user_id,jnf_users.company_id,jnf_user_details.company_name,jnf_user_details.website,jnf_user_details.session,		jnf_salary.ctc,jnf_salary.gross,jnf_salary.take_home,jnf_company_details.category,jnf_company_details.industry,jnf_company_details.job_designation,
		jnf_company_details.job_description,jnf_company_details.job_posting,tnp_calender.date_from,tnp_calender.date_to,tnp_calender.status,
		tnp_calender.ppt,tnp_calender.test,tnp_calender.interview
		 FROM jnf_users INNER JOIN jnf_user_details ON jnf_user_details.company_id = jnf_users.company_id INNER JOIN jnf_salary ON jnf_salary.company_id = jnf_users.company_id INNER JOIN jnf_company_details ON jnf_company_details.company_id = jnf_users.company_id LEFT JOIN tnp_calender ON tnp_calender.company_id = jnf_users.company_id 
            INNER JOIN inf_selectioncutoff ON inf_selectioncutoff.company_id = tnp_calender.company_id
             INNER JOIN jnf_selectioncutoff ON jnf_selectioncutoff.company_id = inf_selectioncutoff.company_id
			WHERE tnp_calender.stu_visibility = 1");//Kuldeep 18.10.17 added-->tnp_calender.ppt,tnp_calender.test,tnp_calender.interview
		return $query->result();		
	}

	//---------------------------------------------st-----------------------------------------------------------
	function get_company_inf_list_visible_to_student()
	{		
		$query = $this->db->query("SELECT distinct user_id,jnf_users.company_id,jnf_user_details.company_name,jnf_user_details.website,jnf_user_details.session,inf_salary.stipend,inf_company_details.category,inf_company_details.industry,inf_company_details.job_designation,inf_company_details.job_description,inf_company_details.job_posting,tnp_calender_inf.date_from,tnp_calender_inf.date_to,tnp_calender_inf.status FROM jnf_users INNER JOIN jnf_user_details ON jnf_user_details.company_id = jnf_users.company_id INNER JOIN inf_salary ON inf_salary.company_id = jnf_users.company_id INNER JOIN inf_company_details ON inf_company_details.company_id = jnf_users.company_id LEFT JOIN tnp_calender_inf ON tnp_calender_inf.company_id = jnf_users.company_id LEFT JOIN inf_selectioncutoff ON inf_selectioncutoff.company_id = tnp_calender_inf.company_id WHERE tnp_calender_inf.stu_visibility = 1");
		return $query->result();		
	}
	//-----------------------------------------------end----------------------------------------------------------
	
	function get_company_details($company_id)
	{
		$query = $this->db->get_where($this->table_jnf_company_details,array("company_id"=>$company_id));
		return $query->result();	
	}

	function get_company_inf_details($company_id)
	{
		$query = $this->db->get_where($this->table_inf_company_details,array("company_id"=>$company_id));
		return $query->result();	
	}
	
	function get_company_eligible_branches($company_id)
	{
		$query = $this->db->query("SELECT DISTINCT branches.id as b_id,branches.name as b_name,courses.id as c_id,courses.name as c_name,courses.duration,departments.id as dept_id,departments.name as dept_name , count(tpr.stu_id) as stu_strength, SUM(if(tpr.blocked = 1,1,0)) as placed
									FROM jnf_eligible_branches
									INNER JOIN course_branch ON course_branch.course_branch_id = jnf_eligible_branches.course_branch_id
									INNER JOIN courses ON courses.id = course_branch.course_id
									INNER JOIN (SELECT DISTINCT * from dept_course where true group by dept_course.course_branch_id) as udc  ON udc.course_branch_id = course_branch.course_branch_id
									INNER JOIN departments ON udc.dept_id = departments.id
									INNER JOIN branches ON branches.id = course_branch.branch_id
									LEFT JOIN stu_academic AS sa ON sa.course_id = course_branch.course_id
									AND sa.branch_id = course_branch.branch_id
									AND sa.semester > ( ( 2 * courses.duration ) -2 ) 
									LEFT JOIN tnp_placement_registration as tpr ON sa.admn_no = tpr.stu_id
									WHERE jnf_eligible_branches.company_id =  '".$company_id."'
									GROUP BY branches.id, courses.id");
		return $query->result();	
	}
	
	//----------------------------------------dinesh------------------------------------------------------------
	function get_company_inf_eligible_branches($company_id)
	{
		$query = $this->db->query("SELECT DISTINCT branches.id as b_id,branches.name as b_name,courses.id as c_id,courses.name as c_name,courses.duration,departments.id as dept_id,departments.name as dept_name , count(tpr.stu_id) as stu_strength, SUM(if(tpr.blocked = 1,1,0)) as placed
									FROM inf_eligible_branches
									INNER JOIN course_branch ON course_branch.course_branch_id = inf_eligible_branches.course_branch_id
									INNER JOIN courses ON courses.id = course_branch.course_id
									INNER JOIN (SELECT DISTINCT * from dept_course where true group by dept_course.course_branch_id) as udc  ON udc.course_branch_id = course_branch.course_branch_id
									INNER JOIN departments ON udc.dept_id = departments.id
									INNER JOIN branches ON branches.id = course_branch.branch_id
									LEFT JOIN stu_academic AS sa ON sa.course_id = course_branch.course_id
									AND sa.branch_id = course_branch.branch_id
									AND sa.semester > ( ( 2 * courses.duration ) -4 ) 
									AND sa.semester <= ( ( 2 * courses.duration ) -2) 
									LEFT JOIN tnp_placement_registration as tpr ON sa.admn_no = tpr.stu_id
									WHERE inf_eligible_branches.company_id =  '".$company_id."'
									
									GROUP BY branches.id, courses.id");
		return $query->result();	
	}


	//-------------------------------------------end--------------------------------------------------------------
	function get_company_logistics($company_id)
	{
		$query = $this->db->get_where($this->table_jnf_logistic,array("company_id"=>$company_id));
		return $query->result();	
	}

	//--------------------------------Dinesh--------------------------------------------------------------
	function get_company_inf_logistics($company_id)
	{
		$query = $this->db->get_where($this->table_inf_logistic,array("company_id"=>$company_id));
		return $query->result();	
	}
	//------------------------------------Dinesh--------------------------------------------------------------------
	
	


	function get_company_salary($company_id)
	{
		$query = $this->db->get_where($this->table_jnf_salary,array("company_id"=>$company_id));
		return $query->result();	
	}



	//--------------------------------Dinesh--------------------------------------------------------------
	function get_company_inf_salary($company_id)
	{
		$query = $this->db->get_where($this->table_inf_salary,array("company_id"=>$company_id));
		return $query->result();	
	}
	//------------------------------------Dinesh--------------------------------------------------------------------
	

	function get_company_selectioncutoff($company_id) //dinesh
	{
		
		$this->db->select("10marks as marks_10,12marks as marks_12,UG,PG,courses.name");
		$this->db->join("courses","courses.id = jnf_selectioncutoff.course_id");
		$query = $this->db->get_where($this->table_jnf_selectioncutoff,array("company_id"=>$company_id));
		return $query->result();	
	}

//--------------------------------Dinesh--------------------------------------------------------------
	function get_company_inf_selectioncutoff($company_id)   //dinesh
	{
		
		$this->db->select("10marks as marks_10,12marks as marks_12,UG,PG,courses.name");
		$this->db->join("courses","courses.id = inf_selectioncutoff.course_id");
		$query = $this->db->get_where($this->table_inf_selectioncutoff,array("company_id"=>$company_id));
		return $query->result();	
	}
	//------------------------------------Dinesh--------------------------------------------------------------------
	

	

	
	function get_company_selectionprocess($company_id)
	{
		$query = $this->db->get_where($this->table_jnf_selectionprocess,array("company_id"=>$company_id));
		return $query->result();	
	}


	//--------------------------------Dinesh--------------------------------------------------------------
    function get_company_inf_selectionprocess($company_id)
	{
		$query = $this->db->get_where($this->table_inf_selectionprocess,array("company_id"=>$company_id));
		return $query->result();	
	}
	//------------------------------------Dinesh--------------------------------------------------------------------
	



	function get_company_in_date_range($from,$to)
	{
		$this->db->where("date_from >= '$from' AND date_from <= '$to'");
		$this->db->or_where("date_to >= '$from' AND date_to <= '$to'");
		$this->db->or_where("date_from <= '$from' AND date_to >= '$to'");
		$this->db->join("jnf_user_details","jnf_user_details.company_id = tnp_calender.company_id");
		$query = $this->db->get($this->table_tnp_calender);
		return $query->result();	
	}

	//--------------------------------------st-----------------------------------------
	function get_company_inf_in_date_range($from,$to)
	{
		$this->db->where("date_from >= '$from' AND date_from <= '$to'");
		$this->db->or_where("date_to >= '$from' AND date_to <= '$to'");
		$this->db->or_where("date_from <= '$from' AND date_to >= '$to'");
		$this->db->join("jnf_user_details","jnf_user_details.company_id = tnp_calender_inf.company_id");
		$this->db->join("inf_selectioncutoff","inf_selectioncutoff.company_id=tnp_calender_inf.company_id");
		$query = $this->db->get($this->table_tnp_calender_inf);
		return $query->result();	
	}
	
	//--------------------------------------------end---------------------------------------

	function get_tnp_calender_for_ppt($company_id,$ppt){	//Kuldeep
		$query = $this->db->get_where($this->table_tnp_calender,array("company_id"=>$company_id,"ppt"=>$ppt));
		if($query->num_rows() > 0)	return $query->result();
		return false;	
	}
	function get_tnp_calender_for_test($company_id,$test){//Kuldeep
		$query = $this->db->get_where($this->table_tnp_calender,array("company_id"=>$company_id,"test"=>$test));
		if($query->num_rows() > 0)	return $query->result();
		return false;	
	}
	function get_tnp_calender_for_interview($company_id,$interview){//Kuldeep
		$query = $this->db->get_where($this->table_tnp_calender,array("company_id"=>$company_id,"interview"=>$interview));
		if($query->num_rows() > 0)	return $query->result();
		return false;	
	}
	
	function get_tnp_calender($company_id)	//this was replaced and above 3 func are used instead of this
	{
		$query = $this->db->get_where($this->table_tnp_calender,array("company_id"=>$company_id));
		if($query->num_rows() > 0)
			return $query->result();
		return false;	
	}
//dinesh
	function get_tnp_calender_inf($company_id)
	{
		$query = $this->db->get_where($this->table_tnp_calender_inf,array("company_id"=>$company_id));
		if($query->num_rows() > 0)
			return $query->result();
		return false;	
	}


	function insert_tnp_calender($tnp_calender)
	{
		$query = $this->db->insert_string($this->table_tnp_calender, $tnp_calender);
		$query = str_replace('INSERT INTO','INSERT IGNORE INTO',$query);
		$this->db->query($query);
		return $this->db->affected_rows();	
	}
	function insert_tnp_calender_inf($tnp_calender)
	{
		$query = $this->db->insert_string($this->table_tnp_calender_inf, $tnp_calender);
		$query = str_replace('INSERT INTO','INSERT IGNORE INTO',$query);
		$this->db->query($query);
		return $this->db->affected_rows();	
	}
	
	
	function update_tnp_calender_for_ppt($tnp_calender,$company_id,$ppt){
		$this->db->where("company_id",$company_id);
		$this->db->where("ppt",$ppt);
		$query = $this->db->update($this->table_tnp_calender,$tnp_calender);
		return $this->db->affected_rows();
	}
	function update_tnp_calender_for_test($tnp_calender,$company_id,$test){
		$this->db->where("company_id",$company_id);
		$this->db->where("test",$test);
		$query = $this->db->update($this->table_tnp_calender,$tnp_calender);
		return $this->db->affected_rows();
	}
	function update_tnp_calender_for_interview($tnp_calender,$company_id,$interview){
		$this->db->where("company_id",$company_id);
		$this->db->where("interview",$interview);
		$query = $this->db->update($this->table_tnp_calender,$tnp_calender);
		return $this->db->affected_rows();	
	}
	
	
	function update_tnp_calender($tnp_calender,$company_id)	//this was replaced and above 3 func are used instead of this
	{
		$this->db->where("company_id",$company_id);
		$query = $this->db->update($this->table_tnp_calender,$tnp_calender);
		return $this->db->affected_rows();
	}
	function update_tnp_calender_inf($tnp_calender,$company_id)
	{
		$this->db->where("company_id",$company_id);
		$query = $this->db->update($this->table_tnp_calender_inf,$tnp_calender);
		return $this->db->affected_rows();
	}
	
	function delete_tnp_calender($company_id)
	{
		$query = $this->db->delete($this->table_tnp_calender,array('company_id'=>$company_id));
		return $query;
	}
	function delete_tnp_calender_inf($company_id)
	{
		$query = $this->db->delete($this->table_tnp_calender_inf,array('company_id'=>$company_id));
		return $query;
	}
	function update_visiting_status($company_id,$status)
	{
		$this->db->where("company_id",$company_id);
		$query = $this->db->update($this->table_tnp_calender,array("status"=>$status));
		return $query;	
	}

	function update_visiting_status_inf($company_id,$status)
	{
		$this->db->where("company_id",$company_id);
		$query = $this->db->update($this->table_tnp_calender_inf,array("status"=>$status));
		return $query;	
	}
	
	function insert_placement_portal($placement_portal_details)
	{
		$query = $this->db->insert($this->table_placement_portal,$placement_portal_details);
		if($this->db->affected_rows() > 0)
			return true;
		return false;
	}
	
	function select_placement_portal($session)
	{
		if($session != '')
		{
			//$query = $this->db->get_where($this->table_placement_portal,array("session"=>$session));
			$query = $this->db->query('SELECT * FROM `tnp_placement_portal` WHERE session='.'"'.$session.'" '.'ORDER BY date_to DESC');
			if($query->num_rows() > 0)
				return $query->result();
			return false;
		}
		return false;
		
	}
	
	function update_placement_portal($session,$placement_portal_details)
	{
		$this->db->where("session",$session);	
		$query = $this->db->update($this->table_placement_portal,$placement_portal_details);
		if($this->db->affected_rows() > 0)
			return true;
		return false;
	}
	
	function delete_placement_portal($session)
	{
		$query = $this->db->delete($this->table_placement_portal,array("session"=>$session));
		if($this->db->affected_rows() > 0)
			return true;
		return false;
	}
	
	//queries for Student info about the placement registration
	function insert_stu_for_placement_registration($data)
	{
		$query = $this->db->insert($this->table_placement_registration,$data);
		if($this->db->affected_rows() > 0)
			return true;
		return false;	
	}


   //------------------------------------st---------------------------------------------------
	function insert_stu_for_internship_registration($data)
	{
		$query = $this->db->insert($this->table_placement_registration,$data);
		if($this->db->affected_rows() > 0)
			return true;
		return false;	
	}
	//-------------------------------en-------------------------------------------------------




	function select_stu_for_placement_registration($stu_id)
	{
	    $this->db->where("registered_for",2);
		//$this->db->where("stu_id = '$stu_id'");
		$query = $this->db->get_where($this->table_placement_registration,array("stu_id"=>$stu_id));
		if($query->num_rows() > 0)
			return $query->result();
			
		return false;	
	}

	//--------------------------------------st------------------------------------------------

	function select_stu_for_internship_registration($stu_id)
	{
		$this->db->where("registered_for = 1");
		//$this->db->where("blocked = 0");
		//$this->db->where("stu_id = '$stu_id'");
		$query = $this->db->get_where($this->table_placement_registration,array("stu_id"=>$stu_id)); 
		if($query->num_rows() > 0)
			return $query->result();
			
		return false;	
	}
	//--------------------------------------------end--------------------------------------------





	function select_stu_for_current_placement_registration($stu_id,$curr_session)
	{
		$query = $this->db->get_where($this->table_placement_registration,array("stu_id"=>$stu_id,"session"=>$curr_session));
		if($query->num_rows() > 0)
			return $query->result();
			
		return false;
	}
	//-----------------------------------------------------------st------------------------------------------------

	function select_stu_for_current_internship_registration($stu_id,$curr_session)
	{
		$query = $this->db->get_where($this->table_placement_registration,array("stu_id"=>$stu_id,"session"=>$curr_session));
		if($query->num_rows() > 0)
			return $query->result();
			
		return false;
	}
	//---------------------------------------------------------------end----------------------------------------




	function delete_stu_for_placement_registration($stu_id)
	{
		$this->db->where("stu_id",$stu_id);
		$query = $this->db->delete($this->table_placement_registration);
		if($this->db->affected_rows() > 0)
			return true;
		return false;	
	}
   //---------------------------------------------st-----------------------------------------------------------------------

	function delete_stu_for_internship_registration($stu_id)
	{
		$this->db->where("stu_id",$stu_id);
		$query = $this->db->delete($this->table_placement_registration);
		if($this->db->affected_rows() > 0)
			return true;
		return false;	
	}
	//------------------------------------------------------end--------------------------------------------------




	// function update_stu_for_placement_registration($stu_id,$data)
	// {
	// 	$this->db->where("stu_id",$stu_id);
	// 	$query = $this->db->update($this->table_placement_registration,$data);
	// 	if($this->db->affected_rows() > 0)
	// 		return true;
	// 	return false;	
	// }
	
	function update_stu_for_placement_registration($stu_id,$data)
	{

		$query = $this->db->query("SELECT * FROM `tnp_placement_registration` WHERE stu_id='".$stu_id."'AND session='".$data['session']."'");
		if($query->num_rows() > 0)
		{//--
			if($data['added_on']==NULL){
				$query = $this->db->query("UPDATE `tnp_placement_registration` SET `blocked` = ".$data['blocked'].", `blocked_on`='".$data['blocked_on']."', `remarks_block` ='".$data['remarks_block']."', `block_date_till` ='".$data['block_date_till']."', `block_date_from` ='".$data['block_date_from']."' WHERE stu_id= '".$stu_id."' AND session='".$data['session']."'");
			}
			else if($data['blocked_on']==NULL){
				$query = $this->db->query("UPDATE `tnp_placement_registration` SET `blocked` = ".$data['blocked'].",  `added_on`='".$data['added_on']."' , `remarks_add` ='".$data['remarks_add']."' WHERE stu_id= '".$stu_id."' AND session='".$data['session']."'");
			}

			// if($this->db->affected_rows() > 0)
			// 	return true;
			if($this->db->_error_message() )
				return false;
			else
				return true;
		}
		else
		{
			$query = $this->db->query("INSERT INTO `tnp_placement_registration`(`session`, `stu_id`,`registered_for`, `registered_on`, `blocked`, `added_on`,`blocked_on`,`remarks_add`,`remarks_block`,`block_date_from`,`block_date_till`) VALUES ('".$data['session']."','".$data['stu_id']."','".$data['registered_for']."', '".$data['registered_on']."','".$data['blocked']."','".$data['added_on']."','".$data['blocked_on']."','".$data['remarks_add']."','".$data['remarks_block']."','".$data['block_date_from']."','".$data['block_date_till']."') ");
			if($this->db->affected_rows() > 0)
				return true;
			else
				return false;
		}
		return false;	
	} 


//---------------------------------------------------st------------------------------------------------------------------

	function update_stu_for_internship_registration($stu_id,$data)
	{

		$query = $this->db->query("SELECT * FROM `tnp_placement_registration` WHERE stu_id='".$stu_id."'AND session='".$data['session']."'");
		if($query->num_rows() > 0)
		{
			if($data['added_on']==NULL){
				$query = $this->db->query("UPDATE `tnp_placement_registration` SET `blocked` = ".$data['blocked'].", `blocked_on`='".$data['blocked_on']."', `remarks_block` ='".$data['remarks_block']."' WHERE stu_id= '".$stu_id."' AND session='".$data['session']."'");
			}
			else if($data['blocked_on']==NULL){
				$query = $this->db->query("UPDATE `tnp_placement_registration` SET `blocked` = ".$data['blocked'].",  `added_on`='".$data['added_on']."' , `remarks_add` ='".$data['remarks_add']."' WHERE stu_id= '".$stu_id."' AND session='".$data['session']."'");
			}

			// if($this->db->affected_rows() > 0)
			// 	return true;
			if($this->db->_error_message() )
				return false;
			else
				return true;
		}
		else
		{
			$query = $this->db->query("INSERT INTO `tnp_placement_registration`(`session`, `stu_id`, `registered_for`,`registered_on`, `blocked`, `added_on`,`blocked_on`,`remarks_add`,`remarks_block`) VALUES ('".$data['session']."','".$data['stu_id']."', '".$data['registered_for']."', '".$data['registered_on']."','".$data['blocked']."','".$data['added_on']."','".$data['blocked_on']."','".$data['remarks_add']."','".$data['remarks_block']."') ");
			if($this->db->affected_rows() > 0)
				return true;
			else
				return false;
		}
		return false;	
	} 
//----------------------------------------------------------end-----------------------------------------------------------

function get_block_stu_detail($stu_id="")
	{
		if(!$stu_id){
			$query = $this->db->query("SELECT * FROM `tnp_placement_registration` INNER JOIN stu_academic ON stu_academic.admn_no = tnp_placement_registration.stu_id INNER JOIN user_details ON stu_academic.admn_no = user_details.id INNER JOIN user_other_details ON stu_academic.admn_no = user_other_details.id LEFT JOIN tnp_placement_result ON tnp_placement_result.stu_id = user_details.id LEFT JOIN jnf_user_details ON tnp_placement_result.company_id = jnf_user_details.company_id  WHERE tnp_placement_registration.blocked=1 ");}
		else{
			$query = $this->db->query("SELECT * FROM `tnp_placement_registration` INNER JOIN stu_academic ON stu_academic.admn_no = tnp_placement_registration.stu_id INNER JOIN user_details ON stu_academic.admn_no = user_details.id INNER JOIN user_other_details ON stu_academic.admn_no = user_other_details.id LEFT JOIN tnp_placement_result ON tnp_placement_result.stu_id = user_details.id LEFT JOIN jnf_user_details ON tnp_placement_result.company_id = jnf_user_details.company_id  WHERE tnp_placement_registration.blocked=1 AND tnp_placement_registration.stu_id= '".$stu_id."' ");
		}	
		//if($query->num_rows() > 0)
			return $query->result();
	
		//return false;
		
	}

//---------------


	function get_all_registered_student_for_placement()
	{
		$query = $this->db->query('SELECT DISTINCT * FROM `tnp_placement_registration` INNER JOIN stu_academic ON stu_academic.admn_no = tnp_placement_registration.stu_id INNER JOIN user_details ON stu_academic.admn_no = user_details.id INNER JOIN user_other_details ON stu_academic.admn_no = user_other_details.id LEFT JOIN tnp_placement_result ON tnp_placement_result.stu_id = user_details.id LEFT JOIN jnf_user_details ON tnp_placement_result.company_id = jnf_user_details.company_id  WHERE tnp_placement_registration.registered_for=2');
		//if($query->num_rows() > 0)
			return $query->result();
	
		//return false;
		
	}
	//----------------------------------------------------st-----------------------------------------------------------

	function get_all_registered_student_for_internship()
	{
		$query = $this->db->query('SELECT DISTINCT * FROM `tnp_placement_registration` INNER JOIN stu_academic ON stu_academic.admn_no = tnp_placement_registration.stu_id INNER JOIN user_details ON stu_academic.admn_no = user_details.id INNER JOIN user_other_details ON stu_academic.admn_no = user_other_details.id LEFT JOIN tnp_placement_result ON tnp_placement_result.stu_id = user_details.id LEFT JOIN jnf_user_details ON tnp_placement_result.company_id = jnf_user_details.company_id  WHERE tnp_placement_registration.registered_for=1');
		//if($query->num_rows() > 0)
			return $query->result();
	
		//return false;
		
	}
	//-------------------------------------------end---------------------------------------------------------



	//These functions are used in JNF filling
	function insert_company_details($company_details){
		$this->db->query("INSERT INTO jnf_users(company_id, user_id, enabled, step, date_created) VALUES ('".$company_details['company_id']."','".$company_details['cr_username']."',1, 1,'".date("Y-m-d H:i:sa")."')");
		
		if($this->db->affected_rows() != 0) {
			$this->db->query("INSERT INTO `jnf_user_details`(`company_id`, `name`, `designation`, `company_name`, `website`, `session`, `mobile`, `board`, `fax`, `postal_address`) VALUES ('".$company_details['company_id']."', '".$company_details['name']."','".$company_details['designation']."', '".$company_details['company_name']."', '".$company_details['website']."', '".$company_details['session']."', '".$company_details['mobile']."', '".$company_details['board']."', '".$company_details['fax']."', '".$company_details['postal_address']."')");

			$this->db->query("INSERT INTO `jnf_company_details`(`company_id`) VALUES ('".$company_details['company_id']."') ");

			$this->db->query("INSERT INTO `jnf_logistics`(`company_id`) VALUES ('".$company_details['company_id']."') ");
			$this->db->query("INSERT INTO `jnf_salary`(`company_id`) VALUES ('".$company_details['company_id']."') ");
			$this->db->query("INSERT INTO `jnf_selectioncutoff`(`company_id`) VALUES ('".$company_details['company_id']."') ");
			$this->db->query("INSERT INTO `jnf_selectionprocess`(`company_id`) VALUES ('".$company_details['company_id']."') ");

           $this->db->query("INSERT INTO `inf_company_details`(`company_id`) VALUES ('".$company_details['company_id']."') ");

			$this->db->query("INSERT INTO `inf_logistics`(`company_id`) VALUES ('".$company_details['company_id']."') ");
			$this->db->query("INSERT INTO `inf_salary`(`company_id`) VALUES ('".$company_details['company_id']."') ");			
			$this->db->query("INSERT INTO `inf_selectioncutoff`(`company_id`) VALUES ('".$company_details['company_id']."') ");
			$this->db->query("INSERT INTO `inf_selectionprocess`(`company_id`) VALUES ('".$company_details['company_id']."') ");

			if($this->db->affected_rows() != 0)
				return true;
			else
				return false;

		}
		else
			return false;
	} 

	/************************ Deprecated Function *******************************/

	// function insert_company_inf_details($company_details){
	// 	$this->db->query("INSERT INTO jnf_users(company_id, user_id, enabled, step, date_created) VALUES ('".$company_details['company_id']."','".$company_details['cr_username']."',1,1,'".date("Y-m-d H:i:sa")."')");

	// 	if($this->db->affected_rows() != 0){
	// 		$this->db->query("INSERT INTO `jnf_user_details`(`company_id`, `name`, `designation`, `company_name`, `website`, `session`, `mobile`, `board`, `fax`, `postal_address`) VALUES ('".$company_details['company_id']."', '".$company_details['name']."','".$company_details['designation']."', '".$company_details['company_name']."', '".$company_details['website']."', '".$company_details['session']."', '".$company_details['mobile']."', '".$company_details['board']."', '".$company_details['fax']."', '".$company_details['postal_address']."')");
	// 		$this->db->query("INSERT INTO `jnf_company_details`(`company_id`) VALUES ('".$company_details['company_id']."') ");
	// 		//$this->db->query("INSERT INTO `jnf_eligible_branches`(`company_id`) VALUES ('".$company_details['company_id']."') ");
	// 		$this->db->query("INSERT INTO `inf_logistics`(`company_id`) VALUES ('".$company_details['company_id']."') ");
	// 		$this->db->query("INSERT INTO `inf_salary`(`company_id`) VALUES ('".$company_details['company_id']."') ");
			
	// 		$this->db->query("INSERT INTO `inf_selectioncutoff`(`company_id`) VALUES ('".$company_details['company_id']."') ");
	// 		$this->db->query("INSERT INTO `inf_selectionprocess`(`company_id`) VALUES ('".$company_details['company_id']."') ");
	// 		if($this->db->affected_rows() != 0){
	// 			return true;
	// 		}
	// 		else{
	// 			return false;
	// 		}
	// 	}
	// 	else{
	// 		return false;
	// 	}
	// } 

	//-----------------------------------------------------------------------------------------------------------
	/*function insert_company_details($company_details){
		$this->db->query("INSERT INTO jnf_users(company_id, user_id, password , enabled, step, date_created) VALUES ('".$company_details['company_id']."','".$company_details['cr_username']."','".$company_details['cr_password']."',1,1,".time().")");
		if($this->db->affected_rows() != 0){
			$this->db->query("INSERT INTO `jnf_user_details`(`company_id`, `name`, `designation`, `company_name`, `website`, `session`, `mobile`, `board`, `fax`, `postal_address`) VALUES ('".$company_details['company_id']."', '".$company_details['name']."','".$company_details['designation']."', '".$company_details['company_name']."', '".$company_details['website']."', '".$company_details['session']."', '".$company_details['mobile']."', '".$company_details['board']."', '".$company_details['fax']."', '".$company_details['postal_address']."')");
			if($this->db->affected_rows() != 0){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	} */
	
	function insert_job_details($jnf_details){
		$query = "INSERT INTO `jnf_company_details`(`company_id`, `category`, `industry`, `job_designation`, `job_description`, `job_posting`) VALUES (?,?,?,?,?,?);";
		$this->db->query($query,array($jnf_details['cmp_id'],$jnf_details['cmp_category'],$jnf_details['cmp_sector'],$jnf_details['cmp_job_designation'],$jnf_details['cmp_job_description'],$jnf_details['cmp_job_location']));
		if($this->db->affected_rows() != 0){
			return true;
		}
		return false;
	}
	function insert_job_details_inf($inf_details){
		$query = "INSERT INTO `inf_company_details`(`company_id`, `category`, `industry`, `job_designation`, `job_description`, `job_posting`) VALUES (?,?,?,?,?,?);";
		$this->db->query($query,array($inf_details['cmp_id'],$inf_details['cmp_category'],$inf_details['cmp_sector'],$inf_details['cmp_job_designation'],$inf_details['cmp_job_description'],$inf_details['cmp_job_location']));
		if($this->db->affected_rows() != 0){
			return true;
		}
		return false;
	}




	/*function insert_eligible_branches($jnf_details){
		$error_occured = false;
		$query = "INSERT INTO `jnf_eligible_branches`(`company_id`, `session`, `course_branch_id`) VALUES (?,?,?);";
		foreach ($jnf_details['eligible_cb'] as $key => $value) {
			$cb = explode('_', $value);
			$this->db->query($query,array($jnf_details['cmp_id'],$jnf_details['cmp_session'],$cb[0]));
			if($this->db->affected_rows() == 0){
				$error_occured = true;
				break;
			}
		}
		if($error_occured){
			return false;
		}
		return true;
	}*/
	function insert_eligible_branches($jnf_details){
		$error_occured = false;
	  $this->db->query("delete from `jnf_eligible_branches` where `company_id`='".$jnf_details['cmp_id']."' ");
		$query = "INSERT INTO `jnf_eligible_branches`(`company_id`, `session`, `course_branch_id`) VALUES (?,?,?);";
		foreach ($jnf_details['eligible_cb'] as $key => $value) {
			$cb = explode('_', $value);
			$qq=$this->db->query("SELECT * FROM `jnf_eligible_branches` where `company_id`='".$jnf_details['cmp_id']."' and
			 `session` = '".$jnf_details['cmp_session']."' and `course_branch_id`='".$cb[0]."' ");
			if($qq->num_rows>0)
			continue;
			$this->db->query($query,array($jnf_details['cmp_id'],$jnf_details['cmp_session'],$cb[0]));
			if($this->db->affected_rows() == 0){
				$error_occured = true;
				break;
			}
		}
		if($error_occured){
			return false;
		}
		return true;
	}

	//-----------------------------------st-----------------------------------------------------
	function insert_inf_eligible_branches($jnf_details){
		$error_occured = false;
	  $this->db->query("delete from `inf_eligible_branches` where `company_id`='".$jnf_details['cmp_id']."' ");
		$query = "INSERT INTO `inf_eligible_branches`(`company_id`, `session`, `course_branch_id`) VALUES (?,?,?);";
		foreach ($jnf_details['eligible_cb'] as $key => $value) {
			$cb = explode('_', $value);
			$qq=$this->db->query("SELECT * FROM `inf_eligible_branches` where `company_id`='".$jnf_details['cmp_id']."' and
			 `session` = '".$jnf_details['cmp_session']."' and `course_branch_id`='".$cb[0]."' ");
			if($qq->num_rows>0)
			continue;
			$this->db->query($query,array($jnf_details['cmp_id'],$jnf_details['cmp_session'],$cb[0]));
			if($this->db->affected_rows() == 0){
				$error_occured = true;
				break;
			}
		}
		if($error_occured){
			return false;
		}
		return true;
	}

//----------------------------------------------end--------------------------------------------

	function insert_logistics_details($jnf_details){
		$query = 'INSERT INTO `jnf_logistics`(`company_id`, `session`, `ppt_room`, `laptop`, `projector`, `printer`, `interview_room`, `any_other`) VALUES (?,?,?,?,?,?,?,?);';
		$this->db->query($query,array($jnf_details['cmp_id'],$jnf_details['cmp_session'],$jnf_details['cmp_ppt_room'],$jnf_details['cmp_laptop'],$jnf_details['cmp_projector'],$jnf_details['cmp_printer'],$jnf_details['cmp_interview_room'],$jnf_details['cmp_other_requirement']));
		if($this->db->affected_rows() != 0){
			return true;
		}
		return false;
	}

	//-------------------------------------------------Dinesh----------------------------------------------
		function insert_inf_logistics_details($jnf_details){
		$query = 'INSERT INTO `inf_logistics`(`company_id`, `session`, `ppt_room`, `laptop`, `projector`, `printer`, `interview_room`, `any_other`) VALUES (?,?,?,?,?,?,?,?);';
		$this->db->query($query,array($jnf_details['cmp_id'],$jnf_details['cmp_session'],$jnf_details['cmp_ppt_room'],$jnf_details['cmp_laptop'],$jnf_details['cmp_projector'],$jnf_details['cmp_printer'],$jnf_details['cmp_interview_room'],$jnf_details['cmp_other_requirement']));
		if($this->db->affected_rows() != 0){
			return true;
		}
		return false;
	}
	//----------------------------------------------Dinesh---------------------------------------------





	function insert_salary_details($jnf_details){
		$query = 'INSERT INTO `jnf_salary`(`company_id`, `session`, `ctc`, `gross`, `take_home`) VALUES(?,?,?,?,?)';
		$this->db->query($query,array($jnf_details['cmp_id'],$jnf_details['cmp_session'],$jnf_details['cmp_ctc'],$jnf_details['cmp_gross_salary'],$jnf_details['cmp_take_home_salary']));
		if($this->db->affected_rows() != 0){
			return true;
		}
		return false;
	}

//----------------------------------------------Dinesh---------------------------------------------

	function insert_inf_salary_details($jnf_details){
		$query = 'INSERT INTO `inf_salary`(`company_id`, `session`, `stipend`) VALUES(?,?,?)';
		$this->db->query($query,array($jnf_details['cmp_id'],$jnf_details['cmp_session'],$jnf_details['cmp_stipend']));
		if($this->db->affected_rows() != 0){
			return true;
		}
		return false;
	}

	//--------------------------------------------------------------------------------------------------


	//Authored by Dinesh
    function insert_selection_cutoff($jnf_details){ //dinesh
		$error_occured = false;
		$this->db->query("delete from `jnf_selectioncutoff` where `company_id`='".$jnf_details['cmp_id']."' ");

		$query1 = 'INSERT INTO `jnf_selectioncutoff`(`company_id`, `course_id`, `session`, `10marks`, `12marks`, `UG`, `PG`) VALUES (?,?,?,?,?,?,?);';
		
	foreach ($jnf_details['cmp_jnf_eligible_criteria'] as $key => $value) {
				$ten_marks = $value["10"];
				$twelve_marks = $value["12"];
				$ug = $value["ug_gpa"];
				$pg = $value["pg_gpa"];
				
				
				$this->db->query($query1,array($jnf_details['cmp_id'],$key,$jnf_details['cmp_session'],$ten_marks,$twelve_marks,$ug,$pg));	

			     if($this->db->affected_rows() == 0) {
					$error_occured= true;
					break;
				}			
		}		

		if($error_occured)
			return false;

		return true;
	}
	
	//--------------------------------dinesh-----------------------------------------------------------
	function insert_inf_selection_cutoff($inf_details){ //dinesh
		$error_occured = false;
		
		$this->db->query("delete from `inf_selectioncutoff` where `company_id`='".$inf_details['cmp_id']."' ");
       
		$query2 = 'INSERT INTO `inf_selectioncutoff`(`company_id`, `course_id`, `session`, `10marks`, `12marks`, `UG`, `PG`) VALUES (?,?,?,?,?,?,?);';

		foreach ($inf_details['cmp_inf_eligible_criteria'] as $key => $value) {
				$ten_marks = $value["10"];
				$twelve_marks = $value["12"];
				$ug = $value["ug_gpa"];
				$pg = $value["pg_gpa"];

				$this->db->query($query2,array($inf_details['cmp_id'],$key,$inf_details['cmp_session'],$ten_marks,$twelve_marks,$ug,$pg));

			     if($this->db->affected_rows() == 0){
					$error_occured= true;
					break;
				}
			
		}

		if($error_occured){
			return false;
		}
		return true;
	}

	function insert_selection_process($jnf_details){
		$query = 'INSERT INTO `jnf_selectionprocess`(`company_id`, `session`, `shortlist_resume`, `written_tech`, `written_ntech`, `gd`, `tech_interview`, `hr_interview`, `year_gap`, `mode_interview`, `mode_written`, `total_round`, `number_of_offer`, `bond`, `bond_details`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);';
		$this->db->query($query,array($jnf_details['cmp_id'],$jnf_details['cmp_session'],$jnf_details['cmp_shortlist_resume'],$jnf_details['cmp_written_technical'],$jnf_details['cmp_non_written_technical'],$jnf_details['cmp_group_discussion'],$jnf_details['cmp_technical_interview'],$jnf_details['cmp_hr_interview'],$jnf_details['cmp_year_gap'],$jnf_details['cmp_interview_mode'],$jnf_details['cmp_written_mode'],$jnf_details['cmp_total_rounds'],$jnf_details['cmp_total_offers'],$jnf_details['cmp_bond'],$jnf_details['cmp_bond_details']));
		if($this->db->affected_rows() != 0){
			return true;
		}
		return false;
	}

//--------------------------------------Dinesh--------------------------------------------------------
	function insert_inf_selection_process($jnf_details){
		$query = 'INSERT INTO `inf_selectionprocess`(`company_id`, `session`, `shortlist_resume`, `written_tech`, `written_ntech`, `gd`, `tech_interview`, `hr_interview`, `year_gap`, `mode_interview`, `mode_written`, `total_round`, `number_of_offer`, `bond`, `bond_details`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);';
		$this->db->query($query,array($jnf_details['cmp_id'],$jnf_details['cmp_session'],$jnf_details['cmp_shortlist_resume'],$jnf_details['cmp_written_technical'],$jnf_details['cmp_non_written_technical'],$jnf_details['cmp_group_discussion'],$jnf_details['cmp_technical_interview'],$jnf_details['cmp_hr_interview'],$jnf_details['cmp_year_gap'],$jnf_details['cmp_interview_mode'],$jnf_details['cmp_written_mode'],$jnf_details['cmp_total_rounds'],$jnf_details['cmp_total_offers'],$jnf_details['cmp_bond'],$jnf_details['cmp_bond_details']));
		if($this->db->affected_rows() != 0){
			return true;
		}
		return false;
	}
//-----------------------------------------end------------------------------------------------------------


	 
	 function update_job_details($jnf_details){
	//Kuldeep 7.11.17 imploded sector and category
	  $this->db->query("UPDATE `jnf_company_details` SET `category`='".implode("\n",$jnf_details['cmp_category'])."',                               
	  `industry`='".implode("\n",$jnf_details['cmp_sector'])."',`job_designation`='".$jnf_details['cmp_job_designation']."', 
			`job_description`='".$jnf_details['cmp_job_description']."',`job_posting`='".$jnf_details['cmp_job_location']."'
			where `company_id`='".$jnf_details['cmp_id']."' ");
		if($this->db->affected_rows() > 0){
			return true;
		}
		return true;
	}
	
	//Kuldeep
	function update_inf_job_details($inf_details){	//Kuldeep 7.11.17 imploded sector and category
      $this->db->query("UPDATE `inf_company_details` SET `category`='".implode("\n",$inf_details['cmp_category'])."',                               
	  `industry`='".implode("\n",$inf_details['cmp_sector'])."',`job_designation`='".$inf_details['cmp_job_designation']."', 
			`job_description`='".$inf_details['cmp_job_description']."',`job_posting`='".$inf_details['cmp_job_location']."'
			where `company_id`='".$inf_details['cmp_id']."' ");
		if($this->db->affected_rows() > 0){
			return true;
		}
		return true;
	}


	function update_logistics_details($jnf_details){
		$this->db->query("UPDATE`jnf_logistics` SET `session`='".$jnf_details['cmp_session']."',
		 `ppt_room`= '".$jnf_details['cmp_ppt_room']."',
		 `laptop`='".$jnf_details['cmp_laptop']."', `projector`='".$jnf_details['cmp_projector']."', 
		 `printer`='".$jnf_details['cmp_printer']."',
		  `interview_room`='".$jnf_details['cmp_interview_room']."',`any_other`= '".$jnf_details['cmp_other_requirement']."'
		WHERE `company_id`='".$jnf_details['cmp_id']."' ");
		if($this->db->affected_rows() > 0){
			return true;
		}
		return false;
	}

	//----------------------------------Dinesh------------------------------------------------------------
    function update_inf_logistics_details($jnf_details){
		$this->db->query("UPDATE`inf_logistics` SET `session`='".$jnf_details['cmp_session']."',
		 `ppt_room`= '".$jnf_details['cmp_ppt_room']."',
		 `laptop`='".$jnf_details['cmp_laptop']."', `projector`='".$jnf_details['cmp_projector']."', 
		 `printer`='".$jnf_details['cmp_printer']."',
		  `interview_room`='".$jnf_details['cmp_interview_room']."',`any_other`= '".$jnf_details['cmp_other_requirement']."'
		WHERE `company_id`='".$jnf_details['cmp_id']."' ");
		if($this->db->affected_rows() > 0){
			return true;
		}
		return false;
	}
	



	function update_salary_details($jnf_details){
		$this->db->query(" UPDATE `jnf_salary` SET `session`='".$jnf_details['cmp_session']."', `ctc`='".$jnf_details['cmp_ctc']."', `gross`='".$jnf_details['cmp_gross_salary']."', `take_home` ='".$jnf_details['cmp_take_home_salary']."' WHERE
		`company_id`='".$jnf_details['cmp_id']."' ");
		if($this->db->affected_rows() != 0){
			return true;
			
		}

		return false;
	}


//19.8.17

	function update_inf_salary_details($jnf_details){
		$this->db->query(" UPDATE `inf_salary` SET `session`='".$jnf_details['cmp_session']."', `stipend`='".$jnf_details['cmp_stipend']."', 
		`relocation`='".$jnf_details['cmp_reloc']."' WHERE `company_id`='".$jnf_details['cmp_id']."' ");	//Kuldeep 19.8.17
		if($this->db->affected_rows() != 0){
			return true;
			
		}

		return false;
	}



	function update_selection_process($jnf_details){
		$this->db->query(" UPDATE `jnf_selectionprocess` SET `session`='".$jnf_details['cmp_session']."', 
		`shortlist_resume`='".$jnf_details['cmp_shortlist_resume']."',`written_tech`='".$jnf_details['cmp_written_technical']."', `written_ntech`='".$jnf_details['cmp_non_written_technical']."', `gd`='".$jnf_details['cmp_group_discussion']."', `tech_interview`='".$jnf_details['cmp_technical_interview']."', `hr_interview`='".$jnf_details['cmp_hr_interview']."', `year_gap`='".$jnf_details['cmp_year_gap']."', `mode_interview`='".$jnf_details['cmp_interview_mode']."',
		 `mode_written`='".$jnf_details['cmp_written_mode']."', `total_round`='".$jnf_details['cmp_total_rounds']."', `number_of_offer`='".$jnf_details['cmp_total_offers']."', `bond`='".$jnf_details['cmp_bond']."', 
		 `bond_details`='".$jnf_details['cmp_bond_details']."', 
		 `back_log`='".$jnf_details['back_log']."' WHERE
		`company_id` = '".$jnf_details['cmp_id']."' ");
		if($this->db->affected_rows() != 0){
			return true;
		}
		return false;
	}


	
	function update_inf_selection_process($jnf_details){
		$this->db->query(" UPDATE `inf_selectionprocess` SET `session`='".$jnf_details['cmp_session']."', 
		`shortlist_resume`='".$jnf_details['cmp_shortlist_resume']."',`written_tech`='".$jnf_details['cmp_written_technical']."', `written_ntech`='".$jnf_details['cmp_non_written_technical']."', `gd`='".$jnf_details['cmp_group_discussion']."', `tech_interview`='".$jnf_details['cmp_technical_interview']."', `hr_interview`='".$jnf_details['cmp_hr_interview']."', `year_gap`='".$jnf_details['cmp_year_gap']."', `mode_interview`='".$jnf_details['cmp_interview_mode']."',
		 `mode_written`='".$jnf_details['cmp_written_mode']."', `total_round`='".$jnf_details['cmp_total_rounds']."', `number_of_offer`='".$jnf_details['cmp_total_offers']."', `bond`='".$jnf_details['cmp_bond_details']."', 
		 `bond_details`='".$jnf_details['cmp_bond_details']."',`duration`='".$jnf_details['cmp_duration']."',`back_log`='".$jnf_details['back_log']."' WHERE
		`company_id` = '".$jnf_details['cmp_id']."' ");
		if($this->db->affected_rows() != 0){
			return true;
		}
		return false;
	}
	
	function update_poc($jnf_details){
		$this->db->query(" UPDATE `jnf_users` SET `user_id`='".$jnf_details['cr_email']."' WHERE
		`company_id`='".$jnf_details['cmp_id']."' ");
		$this->db->query(" UPDATE `jnf_user_details` SET `name`='".$jnf_details['cr_name']."' ,`designation`='".$jnf_details['cr_desig']."',`company_name`='".$jnf_details['cmp_name']."',`website`='".$jnf_details['cmp_website']."',`session`='".$jnf_details['cmp_session']."',`mobile`='".$jnf_details['cr_num']."',`board`='".$jnf_details['cmp_board']."',
			`fax` ='".$jnf_details['cmp_fax']."',`postal_address`='".$jnf_details['cmp_address']."' WHERE
		`company_id`='".$jnf_details['cmp_id']."' ");
		if($this->db->affected_rows() != 0){
			return true;	
		}
		return false;
	}

     function insert_jnf_details($jnf_details){
		//return true;
		//$error_occured = false;
		//$this->db->trans_start();
		$this->update_poc($jnf_details);
		$this->update_job_details($jnf_details);
		$this->insert_eligible_branches($jnf_details);
        $this->update_logistics_details($jnf_details);
		$this->update_salary_details($jnf_details);
		$this->insert_selection_cutoff($jnf_details);
		$this->update_selection_process($jnf_details);

		// Set JNF is filled in 'jnf_user_details' table
		$this->db->set('filled_jnf', 1);
		$this->db->where('company_id', $jnf_details['cmp_id']);
		$this->db->update('jnf_user_details');

		return true;
	}
	
	function allow_jnf_edit($cmp_id){
		// Set JNF is filled in 'jnf_user_details' table
		$this->db->set('edit_jnf', 1);
		$this->db->where('company_id', $cmp_id);
		$this->db->update('tnp_company_jnf_inf');

		return true;
	}

	
	function insert_inf_details($inf_details){
		//return true;
		//$error_occured = false;
		//$this->db->trans_start();
		$this->update_poc($inf_details);
		$this->update_inf_job_details($inf_details);//Kuldeep 18.10.17
		$this->insert_inf_eligible_branches($inf_details);
        $this->update_inf_logistics_details($inf_details);
		$this->update_inf_salary_details($inf_details);
		$this->insert_inf_selection_cutoff($inf_details);
		$this->update_inf_selection_process($inf_details);

		// Set INF is filled in 'jnf_user_details' table
		$this->db->set('filled_inf', 1);
		$this->db->where('company_id', $inf_details['cmp_id']);
		$this->db->update('jnf_user_details');

		return true;
	}
	
	function allow_inf_edit($cmp_id){
		// Set JNF is filled in 'jnf_user_details' table
		$this->db->set('edit_inf', 1);
		$this->db->where('company_id', $cmp_id);
		$this->db->update('tnp_company_jnf_inf');

		return true;
	}
	
	//-----Kuldeep
	/*function insert_jnf_details($jnf_details){

		$error_occured = false;
		$this->db->trans_start();

		if($this->insert_job_details($jnf_details)){

			if($this->insert_eligible_branches($jnf_details)){

				if($this->insert_logistics_details($jnf_details)){

					if($this->insert_salary_details($jnf_details)){

						if($this->insert_selection_cutoff($jnf_details)){

							if($this->insert_selection_process($jnf_details)){
								//dont have to do anything here
							}
							else{
								$error_occured = true;
							}
						}
						else{
							$error_occured = true;
						}
					}
					else{
						$error_occured = true;
					}
				}
				else{
					$error_occured = true;
				}
			}
			else{
				$error_occured = true;
			}

		}
		else{
			$error_occured = true;
		}
		$this->db->trans_complete();
		if($error_occured){
			return false;
		}
		return true;
	}*/
	//these functions get the details of student for which the company is allowed to..
	function get_course_branch_details($company_id)
	{
		$this->db->where("jnf_eligible_branches.company_id",$company_id);
		$this->db->join("jnf_eligible_branches","jnf_eligible_branches.course_branch_id = course_branch.course_branch_id");
		$this->db->join("courses","courses.id= course_branch.course_id");
		$this->db->join("branches","branches.id= course_branch.branch_id");
		$query = $this->db->get("course_branch");
		
		if($query->num_rows() > 0)
			return $query->result();
		return false;
	}
//------------------------------------------st-------------------------------------------------------------------
	function get_inf_course_branch_details($company_id)
	{
		$this->db->where("inf_eligible_branches.company_id",$company_id);
		$this->db->join("inf_eligible_branches","inf_eligible_branches.course_branch_id = course_branch.course_branch_id");
		$this->db->join("courses","courses.id= course_branch.course_id");
		$this->db->join("branches","branches.id= course_branch.branch_id");
		$query = $this->db->get("course_branch");
		
		if($query->num_rows() > 0)
			return $query->result();
		return false;
	}
	//-----------------------------------------------end----------------------------------------


	
	function get_eligible_students_for_placement($course_id,$branch_id,$duration)
	{
		$this->db->where("stu_academic.course_id = '$course_id' AND stu_academic.branch_id = '$branch_id'");
		$this->db->where("stu_academic.semester >= ((2*$duration)-2)");
		$this->db->where("tnp_placement_registration.blocked != 1");
		$this->db->join("stu_academic","stu_academic.id = tnp_placement_registration.stu_id");
		//add a where clause which will check marks and ogpa criteria..
		$query = $this->db->get($this->table_placement_registration);
		if($query->num_rows() > 0)
			return $query->result();
		
		return false;	
	}

	//----------------------------------------------------st---------------------------------------------------

	function get_eligible_students_for_intership($course_id,$branch_id,$duration)
	{
		$this->db->where("stu_academic.course_id = '$course_id' AND stu_academic.branch_id = '$branch_id'");
		$this->db->where("stu_academic.semester > ((2*$duration)-4)  AND stu_academic.semester <= ((2*$duration)-2)");
		$this->db->where("tnp_placement_registration.blocked != 1");
		$this->db->join("stu_academic","stu_academic.id = tnp_placement_registration.stu_id");
		//add a where clause which will check marks and ogpa criteria..
		$query = $this->db->get($this->table_placement_registration);
		if($query->num_rows() > 0)
			return $query->result();
		
		return false;	
	}
	//----------------------------------------------end---------------------------------------------





	
	function get_eligible_students_for_company($company_id,$course_id,$branch_id,$duration)
	{
		$this->db->where("stu_academic.course_id = '".$course_id."'");
		$this->db->where("stu_academic.branch_id = '".$branch_id."'"); 
		$this->db->where("stu_academic.semester >= ((2*".$duration.")-2)");
		$this->db->where("tnp_placement_registration.blocked != 1");
		$this->db->where("company_id = '".$company_id."'");
		
		$this->db->join("stu_academic","stu_academic.admn_no = tnp_company_registration.stu_id");
		$this->db->join("tnp_placement_registration","tnp_placement_registration.stu_id =  tnp_company_registration.stu_id");
		//add a where clause which will check marks and ogpa criteria..
		$query = $this->db->get($this->table_company_registration);
		return $query->result();
	}

//-----------------------------------------------------------st----------------------------------------

	function get_eligible_students_for_company_inf($company_id,$course_id,$branch_id,$duration)
	{
		$this->db->where("stu_academic.course_id = '".$course_id."'");
		$this->db->where("stu_academic.branch_id = '".$branch_id."'"); 
		$this->db->where("stu_academic.semester > ((2*".$duration.")-4) AND stu_academic.semester <= ((2*".$duration.")-2) ");
		$this->db->where("tnp_placement_registration.blocked != 1");
		$this->db->where("company_id = '".$company_id."'");
		
		$this->db->join("stu_academic","stu_academic.admn_no = tnp_company_registration.stu_id");
		$this->db->join("tnp_placement_registration","tnp_placement_registration.stu_id =  tnp_company_registration.stu_id");
		//add a where clause which will check marks and ogpa criteria..
		$query = $this->db->get($this->table_company_registration);
		return $query->result();
	}

	//----------------------------------------end------------------------------------------------------




	function get_registered_stu_by_company_id_course_branch($company_id,$course_id,$branch_id){ //remarks_block was added -10 may
		$query = $this->db->query("SELECT distinct(sa.admn_no) as admn_no, ud.first_name as first_name, ud.last_name as last_name, ud.sex as sex, ud.category as cat,ud.email as email,ud.dob as dob, uod.mobile_no as mobile_no, sa.iit_jee_rank as jee, sa.iit_jee_cat_rank as jee_cat, sa.cat_score as cat_s, sa.gate_score as gate_s,sa.other_rank as other_rank, tpr.blocked as blocked, tpr.remarks_block as remarks, tpres.company_id as company, uod.father_name as father 
							FROM `jnf_eligible_branches` as jeb 
							INNER JOIN `course_branch` as cb ON jeb.course_branch_id = cb.course_branch_id 
							INNER JOIN `tnp_company_registration` as tcr ON tcr.company_id = jeb.company_id
							INNER JOIN `stu_academic` as sa ON sa.admn_no = tcr.stu_id AND sa.course_id = cb.course_id AND sa.branch_id = cb.branch_id
							INNER JOIN `user_details` as ud ON ud.id = sa.admn_no
							INNER JOIN `user_other_details` as uod ON uod.id = sa.admn_no
							LEFT JOIN `tnp_placement_registration` as tpr ON tpr.stu_id = sa.admn_no
							LEFT JOIN `tnp_placement_result` as tpres ON tpres.stu_id = sa.admn_no
							WHERE (tpr.blocked = 0 OR tpr.blocked IS NULL) AND jeb.`company_id` = '".$company_id."' AND sa.course_id='".$course_id."'  AND tpr.registered_for=2  AND sa.branch_id='".$branch_id."';");
		return $query->result();
	}

	//-------------------------------------------------st----------------------------------------------------------------

	function get_inf_registered_stu_by_company_id_course_branch($company_id,$course_id,$branch_id){
		$query = $this->db->query("SELECT distinct(sa.admn_no) as admn_no, ud.first_name as first_name, ud.last_name as last_name, ud.sex as sex, ud.category as cat,ud.email as email,ud.dob as dob, uod.mobile_no as mobile_no, sa.iit_jee_rank as jee, sa.iit_jee_cat_rank as jee_cat, sa.cat_score as cat_s, sa.gate_score as gate_s,sa.other_rank as other_rank, tpr.blocked as blocked, tpr.remarks as remarks, tpres.company_id as company, uod.father_name as father 
							FROM `inf_eligible_branches` as ieb 
							INNER JOIN `course_branch` as cb ON ieb.course_branch_id = cb.course_branch_id 
							INNER JOIN `tnp_company_registration` as tcr ON tcr.company_id = ieb.company_id
							INNER JOIN `stu_academic` as sa ON sa.admn_no = tcr.stu_id AND sa.course_id = cb.course_id AND sa.branch_id = cb.branch_id
							INNER JOIN `user_details` as ud ON ud.id = sa.admn_no
							INNER JOIN `user_other_details` as uod ON uod.id = sa.admn_no
							LEFT JOIN `tnp_placement_registration` as tpr ON tpr.stu_id = sa.admn_no
							LEFT JOIN `tnp_placement_result` as tpres ON tpres.stu_id = sa.admn_no
							WHERE (tpr.blocked = 0 OR tpr.blocked IS NULL) AND ieb.`company_id` = '".$company_id."' AND  tpr.registered_for=2 AND sa.course_id='".$course_id."' AND sa.branch_id='".$branch_id."';");
		return $query->result();
	}
	//-----------------------------------------------------end--------------------------------------------------



	function get_course()
	{
		$query = $this->db->get($this->table_course);
		return $query->result();
	}



	function get_allowed_course_in_jnf(){
		$query = $this->db->query("SELECT * FROM jnf_courses as jc 
			INNER JOIN cs_courses as cc ON cc.id = jc.course_id WHERE 1;");
		return $query->result();
	}
	//-----------------------------------------dinesh---------------------------------------------------------
	function get_allowed_course_in_inf(){
		$query = $this->db->query("SELECT * FROM inf_courses as ic 
			INNER JOIN cs_courses as cc ON cc.id = ic.course_id WHERE 1;");
		return $query->result();
	}
	//-------------------------------------------------end-----------------------------------------------------




	function get_allowed_sectors(){
		$query = $this->db->query("SELECT * FROM jnf_allowed_company_sectors WHERE 1;");
		return $query->result();
	}


	function get_allowed_categories(){
		$query = $this->db->query("SELECT * FROM jnf_company_category WHERE 1;");
		return $query->result();
	}

	function get_all_semester_gpa($admn_no)
	{
		$query = $this->db->query("SELECT semester,gpa,cgpa FROM final_semwise_marks_foil WHERE admn_no ='".$admn_no."' ORDER BY semester" );
		return $query->result();
	}

	function get_prev_edu_details($admn_no)
	{
		$query = $this->db->query("SELECT * FROM stu_prev_education WHERE admn_no ='".$admn_no."' ORDER by year");
		return $query->result();
	}

	function get_stu_list_by_course_branch_year($course,$branch,$y,$catego='',$comp_id = '',$registered_for='')
	{
		$y = (int)$y;
		$a = 2*$y;
		$b = $a - 1;

		if ($comp_id != '') {
			//$where = "`admn_no` IN (SELECT `stu_id` FROM `tnp_company_registration` WHERE `company_id` = '".$comp_id."' )";
			//$this->db->where($where, NULL, false);
			$this->db->select('admn_no');
			$this->db->from('stu_academic');
			$this->db->join("tnp_company_registration","tnp_company_registration.stu_id =  stu_academic.admn_no");
			$this->db->where('tnp_company_registration.company_id', $comp_id);
			$this->db->where('tnp_company_registration.registered_for', $registered_for);
			$query = $this->db->get();	
			return $query->result();
		}

		$this->db->distinct();
		$this->db->select('admn_no');
		$this->db->where('course_id', $course);
		$this->db->where_in('semester', array($a, $b));

		if ($branch != '0')
			$this->db->where('branch_id', $branch);
		$this->db->from('stu_academic');	
		if ($catego == 1 or $comp_id!=NULL)
			$this->db->join("tnp_placement_registration","tnp_placement_registration.stu_id =  stu_academic.admn_no");
		else if ($catego == 3){
			//$this->db->join("tnp_placement_registration","tnp_placement_registration.stu_id =  stu_academic.admn_no");
			$this->db->join("tnp_placement_result","tnp_placement_result.stu_id =  stu_academic.admn_no",'left');
			$this->db->where('tnp_placement_result.stu_id', NULL); //to exclude the common matching
			}
		$query = $this->db->get();
		
		return $query->result();

		// if($branch == '0')
		// 	$query = $this->db->query("SELECT DISTINCT admn_no FROM stu_academic WHERE course_id ='".$course."' AND semester IN (".$a.",".$b.")");
		// else
		// 	$query = $this->db->query("SELECT DISTINCT admn_no FROM stu_academic WHERE course_id ='".$course."' AND semester IN (".$a.','.$b.") AND branch_id ='".$branch."'" );
		// return $query->result();
	}



	function get_skype_id_shoe_size($admn_no)
	{
		$query = $this->db->query("SELECT skype_id,shoe_size FROM tnp_cv_achievements WHERE user_id='".$admn_no."'");
		return $query->result();
	}

	function get_numberof_subject_backlogs($admn_no)
	{
		$query = $this->db->query("SELECT (SELECT COUNT( DISTINCT subje_code) FROM tabulation1 WHERE subje_pf='F' AND adm_no='".$admn_no."') + (SELECT COUNT(DISTINCT sub_code)  FROM final_semwise_marks_foil_desc WHERE grade='F' AND admn_no='".$admn_no."') as sum");
		return $query->result();
	}



	function get_jnf_selection_cutoff($cmp_id,$course_id)
	{
		$query = $this->db->query("SELECT COUNT(*) as C FROM jnf_selectioncutoff WHERE company_id='". $cmp_id."' AND course_id='".$course_id."'");
		if($query->result()[0]->C == 0)
			return false;
		$query = $this->db->query("SELECT * FROM jnf_selectioncutoff WHERE company_id='". $cmp_id."'");
		return $query->result();
	}
//---------------------------------------------------------------------------------------------------------
	function get_inf_selection_cutoff($cmp_id,$course_id)
	{
		$query = $this->db->query("SELECT COUNT(*) as C FROM inf_selectioncutoff WHERE company_id='". $cmp_id."' AND course_id='".$course_id."'");
		if($query->result()[0]->C == 0)
			return false;
		$query = $this->db->query("SELECT * FROM inf_selectioncutoff WHERE company_id='". $cmp_id."'");
		return $query->result();
	}
//--------------------------------------------------------------------------------------------


	// function get_gpa($stu_id)
	// {
	// 	$query = $this->db->query("SELECT core_cgpa,semester FROM final_semwise_marks_foil WHERE admn_no='".$stu_id."' ORDER BY semester DESC LIMIT 1");
	// 	return $query->result();
	// }

	function get_coursename($course_id)
	{
		$query = $this->db->query("SELECT * FROM cs_courses WHERE id='".$course_id."'");
		return $query->result();
	}

	function get_branchname($branch_id)
	{
		$query = $this->db->query("SELECT * FROM branches WHERE id='".$branch_id."'");
		return $query->result();
	}


	
	function get_registered_stu_by_course_branch_year($course_id,$branch_id,$year,$regf){ //$regf added 2Feb18
		if($course_id == '0' ){
		$q="SELECT distinct(sa.admn_no) as admn_no, ud.first_name as first_name, ud.last_name as last_name, ud.sex as sex, ud.category as cat,ud.email as email,ud.dob as dob, uod.mobile_no as mobile_no, sa.iit_jee_rank as jee, sa.iit_jee_cat_rank as jee_cat, sa.cat_score as cat_s, sa.gate_score as gate_s,sa.other_rank as other_rank, tpr.blocked as blocked, tpr.remarks_block as remarks, tpres.company_id as company, uod.father_name as father, csc.duration as duration, sa.semester as semester
							FROM `stu_academic` as sa 
							INNER JOIN `user_details` as ud ON ud.id = sa.admn_no
							INNER JOIN `user_other_details` as uod ON uod.id = sa.admn_no
							JOIN `tnp_placement_registration` as tpr ON tpr.stu_id = sa.admn_no
							LEFT JOIN `tnp_placement_result` as tpres ON tpres.stu_id = sa.admn_no
							JOIN cs_courses as csc ON csc.id = sa.course_id
							WHERE tpr.registered_for=2 OR tpr.registered_for=1 ";
		$query = $this->db->query($q);
		return $query->result();
		}
		//echo $regf;die();
		$year = (int)$year;
		$a = 2*$year;
		$b = $a - 1;		//in query below tpr.remarks was changed to tpr.remarks_block -- Kuldeep
		$q="SELECT distinct(sa.admn_no) as admn_no, ud.first_name as first_name, ud.last_name as last_name, ud.sex as sex, ud.category as cat,ud.email as email,ud.dob as dob, uod.mobile_no as mobile_no, sa.iit_jee_rank as jee, sa.iit_jee_cat_rank as jee_cat, sa.cat_score as cat_s, sa.gate_score as gate_s,sa.other_rank as other_rank, tpr.blocked as blocked, tpr.remarks_block as remarks, tpres.company_id as company, uod.father_name as father, csc.duration as duration, sa.semester as semester
							FROM `stu_academic` as sa 
							INNER JOIN `user_details` as ud ON ud.id = sa.admn_no
							INNER JOIN `user_other_details` as uod ON uod.id = sa.admn_no
							LEFT JOIN `tnp_placement_registration` as tpr ON tpr.stu_id = sa.admn_no
							LEFT JOIN `tnp_placement_result` as tpres ON tpres.stu_id = sa.admn_no
							JOIN cs_courses as csc ON csc.id = sa.course_id
							WHERE sa.course_id='".$course_id."' AND sa.branch_id='".$branch_id."' AND tpr.registered_for=? AND sa.semester 
							IN (".$a.','.$b.")";
		$query = $this->db->query($q,array($regf));//array($admn_no,$admn_no)
		return $query->result();
	}
// 7 feb new
function get_complete_stu_by_course_branch_year($course_id,$branch_id,$year){
		if($course_id == '0' ){
		$q="SELECT distinct(sa.admn_no) as admn_no, ud.first_name as first_name, ud.last_name as last_name, ud.sex as sex, ud.category as cat,ud.email as email,ud.dob as dob, uod.mobile_no as mobile_no, sa.iit_jee_rank as jee, sa.iit_jee_cat_rank as jee_cat, sa.cat_score as cat_s, sa.gate_score as gate_s,sa.other_rank as other_rank, tpr.blocked as blocked, tpr.remarks_block as remarks, tpres.company_id as company, uod.father_name as father, csc.duration as duration, sa.semester as semester
							FROM `stu_academic` as sa 
							INNER JOIN `user_details` as ud ON ud.id = sa.admn_no
							INNER JOIN `user_other_details` as uod ON uod.id = sa.admn_no
							JOIN `tnp_placement_registration` as tpr ON tpr.stu_id = sa.admn_no
							LEFT JOIN `tnp_placement_result` as tpres ON tpres.stu_id = sa.admn_no
							JOIN cs_courses as csc ON csc.id = sa.course_id";
		$query = $this->db->query($q);
		return $query->result();
		}
		$year = (int)$year;
		$a = 2*$year;
		$b = $a - 1;		//in query below tpr.remarks was changed to tpr.remarks_block -- Kuldeep
		$q="SELECT distinct(sa.admn_no) as admn_no, ud.first_name as first_name, ud.last_name as last_name, ud.sex as sex, ud.category as cat,ud.email as email,ud.dob as dob, uod.mobile_no as mobile_no, sa.iit_jee_rank as jee, sa.iit_jee_cat_rank as jee_cat, sa.cat_score as cat_s, sa.gate_score as gate_s,sa.other_rank as other_rank, tpr.blocked as blocked, tpr.remarks_block as remarks, tpres.company_id as company, uod.father_name as father, csc.duration as duration, sa.semester as semester  
							FROM `stu_academic` as sa 
							INNER JOIN `user_details` as ud ON ud.id = sa.admn_no
							INNER JOIN `user_other_details` as uod ON uod.id = sa.admn_no
							LEFT JOIN `tnp_placement_registration` as tpr ON tpr.stu_id = sa.admn_no
							LEFT JOIN `tnp_placement_result` as tpres ON tpres.stu_id = sa.admn_no
							JOIN cs_courses as csc ON csc.id = sa.course_id
							WHERE sa.course_id='".$course_id."' AND sa.branch_id='".$branch_id."' AND sa.semester 
							IN (".$a.','.$b.")";
		$query = $this->db->query($q);
		return $query->result();
	}	
	
function get_unplaced_stu_by_course_branch_year($course_id,$branch_id,$year){
		if($course_id == '0' ){
		$q="SELECT distinct(sa.admn_no) as admn_no, ud.first_name as first_name, ud.last_name as last_name, ud.sex as sex, ud.category as cat,ud.email as email,ud.dob as dob, uod.mobile_no as mobile_no, sa.iit_jee_rank as jee, sa.iit_jee_cat_rank as jee_cat, sa.cat_score as cat_s, sa.gate_score as gate_s,sa.other_rank as other_rank, tpr.blocked as blocked, tpr.remarks_block as remarks, tpres.company_id as company, uod.father_name as father, csc.duration as duration, sa.semester as semester
							FROM `stu_academic` as sa 
							INNER JOIN `user_details` as ud ON ud.id = sa.admn_no
							INNER JOIN `user_other_details` as uod ON uod.id = sa.admn_no
							JOIN `tnp_placement_registration` as tpr ON tpr.stu_id = sa.admn_no
							LEFT JOIN `tnp_placement_result` as tpres ON tpres.stu_id != sa.admn_no
							JOIN cs_courses as csc ON csc.id = sa.course_id";
		$query = $this->db->query($q);
		return $query->result();
		}
		$year = (int)$year;
		$a = 2*$year;
		$b = $a - 1;		//in query below tpr.remarks was changed to tpr.remarks_block -- Kuldeep
		$q="SELECT distinct(sa.admn_no) as admn_no, ud.first_name as first_name, ud.last_name as last_name, ud.sex as sex, ud.category as cat,ud.email as email,ud.dob as dob, uod.mobile_no as mobile_no, sa.iit_jee_rank as jee, sa.iit_jee_cat_rank as jee_cat, sa.cat_score as cat_s, sa.gate_score as gate_s,sa.other_rank as other_rank, tpr.blocked as blocked, tpr.remarks_block as remarks, tpres.company_id as company, uod.father_name as father, csc.duration as duration, sa.semester as semester  
							FROM `stu_academic` as sa 
							INNER JOIN `user_details` as ud ON ud.id = sa.admn_no
							INNER JOIN `user_other_details` as uod ON uod.id = sa.admn_no
							LEFT JOIN `tnp_placement_registration` as tpr ON tpr.stu_id = sa.admn_no
							LEFT JOIN `tnp_placement_result` as tpres ON tpres.stu_id != sa.admn_no
							JOIN cs_courses as csc ON csc.id = sa.course_id
							WHERE sa.course_id='".$course_id."' AND sa.branch_id='".$branch_id."' AND sa.semester 
							IN (".$a.','.$b.")";
		$query = $this->db->query($q);
		return $query->result();
	}	
	
// 1 Feb 2018 //NEW

function get_only_gpa($admn_no,$startSem,$endsem,$session_year,$dept,$course){
	/*$query = $this->db->query("
			SELECT fa.semester as sem, fa.core_gpa as gpa, fa.core_cgpa as ogpa, fa.hstatus as honour
			FROM `final_semwise_marks_foil` as fa
			WHERE fa.admn_no='".$admn_no."';");
	*/
	/*$query = $this->db->query("
			SELECT ff.semester as sem, ff.gpa as hgpa, ff.core_gpa as gpa, ff.cgpa as hogpa, ff.core_cgpa as ogpa, ff.hstatus as honour
			FROM final_semwise_marks_foil_freezed ff
			INNER JOIN
				(SELECT gpa, core_gpa, cgpa, core_cgpa ,MAX(actual_published_on) AS apo
				FROM final_semwise_marks_foil_freezed
				GROUP BY course, semester, admn_no) groupedtt 
			ON admn_no='".$admn_no."'
			AND ff.actual_published_on = groupedtt.apo
			ORDER BY ff.semester ");	*/	
	$query = $this->db->query("SELECT a.admn_no,a.semester as sem, a.gpa as hgpa, a.core_gpa as gpa, a.cgpa as hogpa, a.core_cgpa as ogpa, a.hstatus as honour, MAX(`actual_published_on`) AS max_subkey 
	FROM final_semwise_marks_foil_freezed a
          WHERE admn_no='".$admn_no."' AND course='".$course."' 
          GROUP BY semester
    ORDER BY semester ");	
	return $query->result();
}	
	
//-------------------------------------------st------------------------------------------------------------
function get_inf_registered_stu_by_course_branch_year($course_id,$branch_id,$year){
		if($branch_id == '0' )
			return false;
		$year = (int)$year;
		$a = 2*$year;
		$b = $a - 1;
		$query = $this->db->query("SELECT distinct(sa.admn_no) as admn_no, ud.first_name as first_name, ud.last_name as last_name, ud.sex as sex, ud.category as cat,ud.email as email,ud.dob as dob, uod.mobile_no as mobile_no, sa.iit_jee_rank as jee, sa.iit_jee_cat_rank as jee_cat, sa.cat_score as cat_s, sa.gate_score as gate_s,sa.other_rank as other_rank, tpr.blocked as blocked, tpr.remarks as remarks, tpres.company_id as company, uod.father_name as father  
							FROM `stu_academic` as sa 
							INNER JOIN `user_details` as ud ON ud.id = sa.admn_no
							INNER JOIN `user_other_details` as uod ON uod.id = sa.admn_no
							LEFT JOIN `tnp_placement_registration` as tpr ON tpr.stu_id = sa.admn_no
							LEFT JOIN `tnp_placement_result` as tpres ON tpres.stu_id = sa.admn_no
							WHERE sa.course_id='".$course_id."' AND sa.branch_id='".$branch_id."' AND tpr.registered_for=1  AND sa.semester IN (".$a.','.$b.");");
		return $query->result();
	}

//----------------------------------------------------------end-------------------------------------------------


	function get_stu_basic_info($admn_no)
	{
		$query = $this->db->query("SELECT ud.first_name as first_name, ud.last_name as last_name,sa.course_id as course_id, sa.branch_id as branch_id 
							FROM `stu_academic` as sa
							INNER JOIN `user_details` as ud ON ud.id = sa.admn_no
							WHERE sa.admn_no = '".$admn_no."';");
		return $query->result();
	}

	function check_alumni($admn_no)
	{
		//number of semesters in old table
		$a = $this->db->query("SELECT COUNT(DISTINCT sem_code) as m
								FROM tabulation1 
								WHERE passfail = 'P' AND adm_no = '".$admn_no."';");
		$a = $a->result();
		//number of semesters in new table
		$b = $this->db->query("SELECT max(semester) as m
								FROM final_semwise_marks_foil
								WHERE status = 'PASS' AND core_status = 'PASS' AND admn_no='".$admn_no."';");
		$b = $b->result();
		//highest semester passed by the student.
		return max($a[0]->m,$b[0]->m);
		
	}

	function get_mba_specialization($admn_no)
	{
		$query = $this->db->query("SELECT DISTINCT tca.user_id,tca.specialization as s FROM tnp_cv_achievements as tca where user_id='".$admn_no."';");
		return $query->result();
	}

	function get_blood_group($admn_no)
	{
		$query = $this->db->query("SELECT blood_group FROM tnp_cv_achievements WHERE user_id='".$admn_no."'");
		return $query->result();
	}

	function get_address($admn_no)
	{
		$query = $this->db->query("SELECT * FROM user_address WHERE id='".$admn_no."' AND type='permanent'");
		return $query->result();
	}

	function get_bank_details($admn_no)
	{
		$query = $this->db->query("SELECT bank_name,account_no FROM stu_other_details WHERE admn_no='".$admn_no."'");
		return $query->result();
	}

	function get_stu_eligible_for_placement()
	{
		$query = $this->db->query("SELECT admn_no FROM stu_academic sa INNER JOIN cs_courses  cc ON sa.course_id = cc.id where sa.semester  > 2*(cc.duration-2) ");
		return $query->result();
	}
	//----------------------------------------------------------dinesh------------------------------------------
	function get_stu_eligible_for_internship()
	{
		$query = $this->db->query("SELECT admn_no FROM stu_academic sa INNER JOIN cs_courses  cc ON sa.course_id = cc.id where sa.semester  > 2*(cc.duration-4) AND sa.semester <= 2*(cc.duration-2) ");
		return $query->result();
	}
	//-----------------------------------------------------------end--------------------------------------------

	function allow_edit_cv($data)
	{
		$query = $this->db->query("INSERT INTO `allow_edit_cv_desc`(`id`, `opt`, `timestamp`) VALUES ('".$data['id']."','".$data['opt']."', '".$data['timestamp']."') ");

		    if(date("m") >= 7)
			$curr_session = date("Y")."-".(date("Y")+1);
			else
			$curr_session = (date("Y")-1)."-".(date("Y"));

		if($data['opt']=="1")
		{
             $this->db->query("UPDATE `tnp_cv_achievements` SET `submit_status`=0 where user_id in (select stu_id from tnp_placement_registration where session like '".$curr_session."')");
		}
		else if($data['opt']=="2")
		{
			$this->db->query("UPDATE `tnp_cv_achievements` SET `submit_status`=0 where `user_id` in (select user_details.id
            from `user_details` inner join `stu_academic` on user_details.id = stu_academic.admn_no where  
            user_details.dept_id like '".$data['dept']."' and stu_academic.course_id like '".$data['course']."' and 
            user_details.id and user_details.id in (select stu_id from tnp_placement_registration where 
            session like '".$curr_session."'))");
			
			$query = $this->db->query("INSERT INTO `allow_edit_cv_dept_course`(`id`, `dept_id`, `course_id`) VALUES ('".$data['id']."','".$data['dept']."', '".$data['course']."') ");
             
		}
		else
		{
           $this->db->query("UPDATE `tnp_cv_achievements` SET `submit_status`=0 where `user_id` like '".$data['adm_no']."'");

           $query = $this->db->query("INSERT INTO `allow_edit_cv_adm_no`(`id`, `adm_no`, `remarks`) VALUES ('".$data['id']."','".$data['adm_no']."', '".$data['remk']."') ");
		}
	}

	function get_branches_by_course($course){	//Kuldeep
		$query = $this->db->query("SELECT DISTINCT id,name FROM cs_branches 
			INNER JOIN course_branch ON course_branch.branch_id = cs_branches.id 
			WHERE course_branch.course_id = '".$course."'"
		);
		return $query->result();
	}

	function check_valid_branch($course_id,$branch_id,$curr_session)	//Kuldeep //for checking whether any stu is registered for that course and branch in that session
	{
    	$query = $this->db->get_where($this->table_reg_regular_form, 
    		array('course_id'=>$course_id,'branch_id'=>$branch_id,'session_year'=>$curr_session));
		if($query->num_rows() >= 1)
			return true;//$query->result();
		return false;
	}

	function check_valid_course($course_id,$curr_session)	//Kuldeep //for checking whether any stu is registered for that course in that session
	{
    	$query = $this->db->get_where($this->table_reg_regular_form, 
    		array('course_id'=>$course_id,'session_year'=>$curr_session));
		if($query->num_rows() >= 1)
			return true;//$query->result();
		return false;
	}

	function select_course_branch($course_id,$branch_id)	//Kuldeep
	{
    	$query = $this->db->get_where($this->table_course_branch, array('course_id'=>$course_id,'branch_id'=>$branch_id));
		if($query->num_rows() >= 1)
			return $query->result();
		return false;
	}
	
	function student_already_got_placed($stu_id)	//Kuldeep
	{
    	$this->db->where("stu_id",$stu_id);
		//$this->db->where("stu_id = '$stu_id'");
		$query = $this->db->get_where($this->table_placement_result,array("stu_id"=>$stu_id));
		if($query->num_rows() > 0)
			return $query->result();
			
		return false;
	}
	
	function student_already_got_internship($stu_id)	//Kuldeep
	{
    	$this->db->where("stu_id",$stu_id);
		//$this->db->where("stu_id = '$stu_id'");
		$query = $this->db->get_where($this->table_internship_result,array("stu_id"=>$stu_id));
		if($query->num_rows() > 0)
			return $query->result();
			
		return false;
	}
}

/* End of file emp_current_entry_model.php */
/* Location: Codeigniter/application/models/employee/emp_current_entry_model.php */
	
