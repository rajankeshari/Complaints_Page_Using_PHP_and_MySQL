<?php

class Report_model extends CI_Model
{
	var $table_userdetails = 'user_details';
	var $table_dept_course = 'dept_course';
	var $table_course = 'courses';
	var $table_branch = 'branches';
	var $table_subject = 'subjects';
	var $table_course_structure = 'course_structure';
	var $table_elective_group = 'elective_group';
	var $table_course_branch = 'course_branch';
	var $table_elective_offered = 'elective_offered';
  	var $table_depts = 'departments';
	

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	function get_admn_no()
	{
		$query = $this->db->query("SELECT admn_no FROM `stu_details`");
			foreach($query->result_array() as $row){
				//$new_row['label']=htmlentities(stripslashes($row['name_in_hindi']." - ".$row['admn_no']));
				$new_row['value']=htmlentities(stripslashes($row['admn_no']));
				$row_set[] = $new_row; //build an array
			}
			echo json_encode($row_set); //format the array into json data
	}
 	
	function get_depts()
	{
		$query = $this->db->get_where($this->table_depts, array('type'=>'academic'));
		return $query->result();
	}
	function get_course()
	{
		
		$query = $this->db->query("SELECT * from courses");
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}
	function get_branch()
	{
		$query = $this->db->query("SELECT * from branches");
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}
	
	function get_course_dept($id)
	{
		
		$query = $this->db->query("SELECT * from courses");
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}
	
	function get_course_bydept($dept_id)
	{
		
		$query = $this->db->query("SELECT DISTINCT course_branch.course_id,id,name,duration FROM 
		courses INNER JOIN course_branch ON course_branch.course_id = courses.id INNER JOIN dept_course ON 
		dept_course.course_branch_id = course_branch.course_branch_id WHERE dept_course.dept_id ='".$dept_id."'");
		
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}
	//Added by anuj for getting cs_course ie ,hons,minor
	   function get_course_bydept_cs($dept_id)
	{
		
		$query = $this->db->query("SELECT DISTINCT course_branch.course_id,id,name,duration FROM 
		cs_courses INNER JOIN course_branch ON course_branch.course_id = cs_courses.id INNER JOIN dept_course ON 
		dept_course.course_branch_id = course_branch.course_branch_id WHERE dept_course.dept_id = '$dept_id'");
		 //   echo  $this->db->last_query();	die();
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}
	// end
	/*function get_branch_bycourse($course,$dept)
	{
		//$query = $this->db->query("SELECT DISTINCT id,name,dept_course.course_branch_id FROM branches INNER JOIN course_branch ON course_branch.branch_id = branches.id INNER JOIN dept_course ON dept_course.course_branch_id = course_branch.course_branch_id WHERE course_branch.course_id = '".$course."' AND dept_course.dept_id = '".$dept."'");
		$query = $this->db->query("SELECT DISTINCT id,name,dept_course.course_branch_id FROM cs_branches INNER JOIN course_branch ON course_branch.branch_id = cs_branches.id INNER JOIN dept_course ON dept_course.course_branch_id = course_branch.course_branch_id WHERE course_branch.course_id = '".$course."' AND dept_course.dept_id = '".$dept."'");
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}*/
	
	
	function get_branch_bycourse($course,$dept)
	{
		// @ desc: as per cbcs norms  jrf  will be also  stored in regular form @ dated:20-4-20
		if($course <> 'jrf'){
			 $course =" course_branch.course_id = '".$course."' AND ";
			 $grp=" ";
			}
			  else 
			  	{
			    		$course='';
			    		$grp=" GROUP BY name ";
			    }		
        // end 

		//$query = $this->db->query("SELECT DISTINCT id,name,dept_course.course_branch_id FROM branches INNER JOIN course_branch ON course_branch.branch_id = branches.id INNER JOIN dept_course ON dept_course.course_branch_id = course_branch.course_branch_id WHERE course_branch.course_id = '".$course."' AND dept_course.dept_id = '".$dept."'");
		
		$query = $this->db->query("SELECT DISTINCT id,name,dept_course.course_branch_id FROM cs_branches INNER JOIN course_branch ON course_branch.branch_id = cs_branches.id INNER JOIN dept_course ON dept_course.course_branch_id = course_branch.course_branch_id WHERE  $course dept_course.dept_id = '".$dept."' and cs_branches.id!='ap'   $grp " );
		if($query->num_rows() > 0)//{ echo $this->db->last_query(); die();}
		  return $query->result();
		else
		 return false;
	}
	
	function get_auth($admn_no)
	{
		$this->load->database();
		$this->db->select('auth_id');
		$this->db->from('users');
		$this->db->where('id',$admn_no);
		$query=$this->db->get();
		$auth=$query->result();
		return $auth[0]->auth_id=='stu';
	}
}
