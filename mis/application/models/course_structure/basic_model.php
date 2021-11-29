<?php

class Basic_model extends CI_Model
{
	var $table_userdetails = 'user_details';
	var $table_dept_course = 'dept_course';
	var $table_course = 'cs_courses';
	var $table_branch = 'cs_branches';
	var $table_subject = 'subjects';
	var $table_course_structure = 'course_structure';
	var $table_elective_group = 'elective_group';
	var $table_course_branch = 'course_branch';
	var $table_optional_offered = 'optional_offered';
	var $table_honour_minor_offered = 'honour_minor_offered';
	var $table_minor_offered = 'minor_offered';
  	var $table_depts = 'departments';

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
 	function get_depts()
	{
		$query = $this->db->get_where($this->table_depts, array('type'=>'academic'));
		return $query->result();
	}
	
	function Select_Department_By_User_ID($userid)
	{
    	$query = $this->db->get_where($this->table_userdetails,array('id'=>$userid));
		return $query->result();
	}
	
	function count_dept_course_by_aggr_id($aggr_id)
	{
		$query = $this->db->get_where($this->table_dept_course,array('aggr_id'=>$aggr_id));
		return $query->num_rows();	
	}
	
	function get_aggr_id_by_course_branch($course,$branch){
		$combi  = $course."_".$branch;
		$qu = $this->db->query("select a.aggr_id from ".$this->table_course_structure." a where a.aggr_id like '".$combi."%' group by a.aggr_id");
		//echo $this->db->last_query();
		if($qu->num_rows() > 0){
			return $qu->result();
		}
		return false;
	}
	
	function select_dept_course()
	{
		$query = $this->db->get($this->table_dept_course);
		if($query->num_rows() > 0)
			return $query->result();	
	}
	
	function select_dept_by_dept_id($dept_id)
	{ 
	   $query = $this->db->get_where($this->table_depts,array('id'=>$dept_id));
		if($query->num_rows() > 0)
			return $query->result();
	}
	
	function select_id_by_dept_name($dept)
	{
	   $query=$this->db->get_where($this->table_depts,array('name'=>$dept));
	   $row=$query->row_array();
	   return $row['id'];
	}
	
	function select_dept_course_by_dept_id($dept_id)
	{
		$query = $this->db->get_where($this->table_dept_course,array('dept_id'=>$dept_id));
		if($query->num_rows() > 0)
			return $query->result();	
	}
	
	
	
	function insert_dept_course($dept_course)
	{
		$query = $this->db->insert($this->table_dept_course,$dept_course);
		return true;
	}
	
	function get_course()
	{
		$query = $this->db->get($this->table_course);
		return $query->result();
	}
	
	function delete_course($course)
	{
		return $this->db->delete($this->table_course,array("id"=>$course));
	}
	
	function get_course_details_by_id($id)
	{
		$query = $this->db->get_where($this->table_course,array('id'=>$id));
		//if($query->num_rows() > 0) 
			return $query->result();
	}
	
	function get_course_offered_by_dept($dept_id)
	{
		$query = $this->db->query("SELECT DISTINCT course_branch.course_id,id,name,duration FROM 
		cs_courses INNER JOIN course_branch ON course_branch.course_id = cs_courses.id INNER JOIN dept_course ON 
		dept_course.course_branch_id = course_branch.course_branch_id WHERE dept_course.dept_id = '$dept_id'");
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}

	function get_course_offered_by_dept_for_student_reg($dept_id)
	{
		$query = $this->db->query("SELECT DISTINCT course_branch.course_id,id,name,duration FROM 
		courses INNER JOIN course_branch ON course_branch.course_id = courses.id INNER JOIN dept_course ON 
		dept_course.course_branch_id = course_branch.course_branch_id WHERE dept_course.dept_id = '$dept_id' AND courses.id != 'honour' AND courses.id != 'minor' AND courses.id != 'comm'");
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}

	function get_branches_by_course_and_dept_for_student_reg($course,$dept){
		$query = $this->db->query("SELECT DISTINCT id,name,dept_course.course_branch_id FROM branches INNER JOIN course_branch ON course_branch.branch_id = branches.id INNER JOIN dept_course ON dept_course.course_branch_id = course_branch.course_branch_id WHERE course_branch.course_id = '".$course."' AND dept_course.dept_id = '".$dept."' AND branches.id != 'comm' AND branches.id != 'honour' AND branches.id != 'minor'");
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}
	
	
	function insert_course($course_details)
	{
    	$this->db->insert($this->table_course, $course_details);
     	return true; 
	}
	
	function get_branches()
	{
		$query = $this->db->get($this->table_branch);
		return $query->result();
	}
	
	function delete_branch($branch)
	{
		return $this->db->delete($this->table_branch,array("id"=>$branch));
	}

	
	function get_branches_by_course($course){
		$query = $this->db->query("SELECT DISTINCT id,name FROM cs_branches INNER JOIN course_branch ON course_branch.branch_id = cs_branches.id WHERE course_branch.course_id = '$course'"
		);
		return $query->result();
	}
	
	
	function get_branches_by_course_and_dept($course,$dept){
		$query = $this->db->query("SELECT DISTINCT id,name,dept_course.course_branch_id FROM cs_branches INNER JOIN course_branch ON course_branch.branch_id = cs_branches.id INNER JOIN dept_course ON dept_course.course_branch_id = course_branch.course_branch_id WHERE course_branch.course_id = '".$course."' AND dept_course.dept_id = '".$dept."'");
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}

	
	function get_branch_details_by_id($id)
	{
		$query = $this->db->get_where($this->table_branch,array('id'=>$id));
		if($query->num_rows()>0)
			return $query->result();
	}
	
	function get_branch_offered_by_dept($dept_id)
	{
		$query = $this->db->query("SELECT DISTINCT id,name FROM cs_branches INNER JOIN course_branch ON course_branch.branch_id = cs_branches.id 
		INNER JOIN dept_course ON dept_course.course_branch_id = course_branch.course_branch_id WHERE dept_course.dept_id = '$dept_id'");
		return $query->result();
	}
	
	function insert_branch($branch_details)
	{
    	$this->db->insert($this->table_branch, $branch_details);
      	return true;
	}
	
	function get_session_by_course_and_branch($course,$branch){
		$this->db->select('year');
		$query = $this->db->get_where($this->table_course_branch,array("course_id"=>$course,"branch_id"=>$branch));
		return $query->result();
	}

	function select_course_branch($course_id,$branch_id)
	{
    	$query = $this->db->get_where($this->table_course_branch, array('course_id'=>$course_id,'branch_id'=>$branch_id));
		if($query->num_rows() >= 1)
			return $query->result();
		return false;
	}
	
	function insert_course_branch($course_branch_mapping)
	{
    	$this->db->insert($this->table_course_branch, $course_branch_mapping);
		return true;
	}

	function get_subject_details($id)
  	{
    	 $query = $this->db->get_where($this->table_subject,array('id'=>$id));
    	 return $query->row();
  	}
	
	function get_subject_details_by_group_id($elective)
  	{
    	 $query = $this->db->get_where($this->table_subject,array('elective'=>$elective));
    	 return $query->row();
  	}
	
	
	function get_subjects_by_sem($sem,$aggr_id)
	{
		$query = $this->db->query("SELECT * FROM course_structure WHERE semester = '$sem' AND aggr_id = '$aggr_id' ORDER BY 
		cast(SUBSTRING_INDEX(`sequence`, '.', 1) as decimal) asc, 
		cast(SUBSTRING_INDEX(`sequence`, '.', -1) as decimal) asc");
		//$this->db->order_by("sequence","ASC");
		//$query = $this->db->get_where($this->table_course_structure,array('semester'=>$sem, 'aggr_id'=>$aggr_id));
		return $query->result();
	}
	
	function get_subjects_by_sem_and_dept($sem,$aggr_id,$dept_id)
	{      
            // added  for  summeer case  in case  multiple sem
             if (substr_count($sem, ',') > 0) {
                 $sem_replace=" semester in( $sem )";
             }
             else{
                 $sem_replace=" semester = '$sem' ";
              }
            // end
		$qs= explode("_",$aggr_id);
		$rr= ' ';
		if($qs[0] =='comm')
			$dept_id='comm';
			$rr=" AND course_structure.aggr_id = '$aggr_id' ";
		
		$query = $this->db->query("SELECT * FROM course_structure 
		INNER JOIN dept_course ON dept_course.aggr_id = course_structure.aggr_id 
		WHERE $sem_replace ".$rr."		
		AND dept_course.dept_id = '$dept_id'
		ORDER BY 
		cast(SUBSTRING_INDEX(`sequence`, '.', 1) as decimal) asc, 
		cast(SUBSTRING_INDEX(`sequence`, '.', -1) as decimal) asc");
		//$this->db->order_by("sequence","ASC");
		//$query = $this->db->get_where($this->table_course_structure,array('semester'=>$sem, 'aggr_id'=>$aggr_id));
                
		return $query->result();
	}
	
	function check_if_aggr_id_exist_in_CS($aggr_id)
	{
		$query = $this->db->get_where($this->table_dept_course,array("aggr_id"=>$aggr_id));
		if($query->num_rows() > 0)
			return $query->num_rows();	
		
		return false;
	}
	function select_all_elective_subject_by_aggr_id_and_semester($aggr_id,$semester,$dept)
	{
		
		$query = $this->db->query("SELECT			
		subjects.id,subject_id,name,lecture,tutorial,practical,credit_hours,contact_hours,elective,type,
		course_structure.aggr_id,course_structure.sequence 
		FROM 
		subjects 
		INNER JOIN course_structure ON course_structure.id = subjects.id 
		LEFT JOIN dept_course ON dept_course.aggr_id = course_structure.aggr_id
		WHERE course_structure.aggr_id = '$aggr_id'
		AND dept_course.dept_id = '$dept'
		AND course_structure.semester = '$semester' 
		AND elective != '0' 
		ORDER BY cast(SUBSTRING_INDEX(`sequence`, '.', 1) as decimal) 
		asc, cast(SUBSTRING_INDEX(`sequence`, '.', -1) as decimal) asc");
		return $query->result();
	}
	
	function select_all_honour_or_minor_subject_by_aggr_id_and_semester($aggr_id,$semester,$course_id,$branch_id,$dept_id)
	{
		$query = $this->db->query("SELECT			
		subjects.id,subject_id,subjects.name,lecture,tutorial,practical,credit_hours,contact_hours,elective,type,
		course_structure.aggr_id,course_structure.sequence  
		FROM  
		subjects 
		INNER JOIN course_structure ON course_structure.id = subjects.id 
		INNER JOIN dept_course ON dept_course.aggr_id = course_structure.aggr_id 
		INNER JOIN course_branch ON course_branch.course_branch_id = dept_course.course_branch_id 
		WHERE course_structure.aggr_id = '$aggr_id' 
		AND dept_course.dept_id = '$dept_id'
		AND course_structure.semester = '$semester' 
		AND course_branch.course_id = '$course_id' 
		AND course_branch.branch_id = '$branch_id' 
		ORDER BY cast(SUBSTRING_INDEX(`sequence`,'.', 1) as decimal) asc, cast(SUBSTRING_INDEX(`sequence`, '.', -1) as decimal) asc");
		return $query->result();
	}
	function select_all_subject_by_aggr_id_and_semester($aggr_id,$semester)
	{
		$query = $this->db->query("SELECT * FROM subjects INNER JOIN course_structure ON course_structure.id = subjects.id WHERE course_structure.aggr_id = '$aggr_id' AND 
		course_structure.semester = '$semester'");
		return $query->result();
	}	
	
	function get_course_structure_by_id($id)
	{
		$query = $this->db->get_where($this->table_course_structure,array('id'=>$id));
		return $query->row();
	}
	
	function select_elective_group_by_group_id($group_id)
	{
		$query = $this->db->query("SELECT * FROM  `elective_group` WHERE `group_id` = '".$group_id."' ");

		//$query = $this->db->get_where($this->table_elective_group,array('group_id'=>$group_id));
		return $query->result();
	}
	function get_elective_count($group_id)
	{
		$query =  $this->db->get_where($this->table_subject,array('elective'=>$group_id));	
		return $query->num_rows();
	}
	
	function update($data, $where)
	{
		$this->db->update($this->table,$data,$where);
	}

	function delete_course_structure($semester,$aggr_id)
	{
		$query = $this->db->delete($this->table_course_structure,array('semester'=>$semester,'aggr_id'=>$aggr_id));
		if($this->db->affected_rows() > 0)
			return true;
		return false;
	}
	
	
	function get_latest_aggr_id($course,$branch,$expected_aggr_id)
	{
		$query = $this->db->query("SELECT aggr_id FROM dept_course INNER JOIN course_branch ON course_branch.course_branch_id = dept_course.course_branch_id WHERE course_branch.course_id = '$course' AND course_branch.branch_id = '$branch' AND aggr_id <= '$expected_aggr_id'  ORDER BY aggr_id DESC");
		//print_r($query->result());
                return $query->result();
	}
	
	
	function select_honour_or_minor_offered($aggr_id,$id)
	{
    	$query = $this->db->get_where($this->table_honour_minor_offered,array('aggr_id'=>$aggr_id,'id'=>$id));
    	if($query->num_rows() > 0)
			return true;	
	}
	
	function select_honour_or_minor_offered_by_aggr_id($aggr_id,$semester)
	{
		$query = $this->db->query("SELECT * FROM honour_minor_offered INNER JOIN course_structure ON course_structure.id = honour_minor_offered.id WHERE honour_minor_offered.aggr_id = '$aggr_id' AND course_structure.semester = '$semester'");
			return $query->result();	
	}	
	
	function delete_honour_or_minor_offered($aggr_id,$semester)
	{
		$query = $this->db->query("DELETE honour_off FROM honour_minor_offered honour_off INNER JOIN course_structure ON course_structure.id = 
		honour_off.id WHERE honour_off.aggr_id = '$aggr_id' AND course_structure.semester = '$semester'");
	    
		if($this->db->affected_rows() >=0 || !$this->db->_error_message())
			return true;

	} 
	
	function insert_honour_or_minor_offered($data)
	{
    	$this->db->insert($this->table_honour_minor_offered,$data);
    	return $this->db->_error_message(); 
	}
	
	// Added from this line by Prateek for CS
	  function get_valid_range($course = '', $branch = '') {
        //var_dump($course." ".$branch);
        
        $sql = "select distinct '$course' as id_of_course,'$branch' as id_of_branch,SUBSTRING(aggr_id,Length(aggr_id)-8,4) as start_yr,SUBSTRING(aggr_id,Length(aggr_id)-3,4) as end_yr from course_structure where SUBSTRING(aggr_id,1," . strlen($course) . ")='" . $course . "' and SUBSTRING(aggr_id," . (strlen($course) + 2) . "," . (strlen($branch)) . ")='" . $branch . "'";
        return $this->db->query($sql)->result_array();
    }

    function get_all_from_dept_id($dept_id = '') {
        if($dept_id === 'comm'){
            
            $sql = "SELECT DISTINCT 'cse' as id,departments.name as dept_name,duration,course_branch.course_id,course_branch.branch_id,cs_branches.name as branch_name,SUBSTRING(course_structure.aggr_id,Length(course_structure.aggr_id)-8,4) as start,SUBSTRING(course_structure.aggr_id,Length(course_structure.aggr_id)-3,4) as end,cs_courses.name as course_name FROM
                course_structure,departments,course_branch,cs_branches,cs_courses,dept_course where 	course_structure.aggr_id=dept_course.aggr_id and
										departments.id IN ('" . $dept_id . "','cse') and
										cs_courses.id=course_branch.course_id and
										dept_course.course_branch_id=course_branch.course_branch_id and
										dept_course.dept_id IN ('" . $dept_id . "','cse') and
										cs_branches.id=course_branch.branch_id  and course_branch.course_id='comm' GROUP BY course_branch.course_id,course_branch.branch_id,start DESC";
        }else{
            $sql = "SELECT DISTINCT departments.id,departments.name as dept_name,duration,course_branch.course_id,course_branch.branch_id,cs_branches.name as branch_name,SUBSTRING(course_structure.aggr_id,Length(course_structure.aggr_id)-8,4) as start,SUBSTRING(course_structure.aggr_id,Length(course_structure.aggr_id)-3,4) as end,cs_courses.name as course_name FROM
                course_structure,departments,course_branch,cs_branches,cs_courses,dept_course where 	course_structure.aggr_id=dept_course.aggr_id and
										departments.id='" . $dept_id . "' and
										cs_courses.id=course_branch.course_id and
										dept_course.course_branch_id=course_branch.course_branch_id and
										dept_course.dept_id='" . $dept_id . "' and course_id NOT IN ('comm')and
										cs_branches.id=course_branch.branch_id GROUP BY course_branch.course_id,course_branch.branch_id,start DESC";
        }
        
        return $this->db->query($sql)->result_array();
    }
    
    
    function get_all_course_branch_pairs($dept_id = ''){
        $sql = "SELECT DISTINCT departments.id,departments.name as dept_name,duration,course_branch.course_id,course_branch.branch_id,cs_branches.name as branch_name,cs_courses.name as course_name FROM
                course_structure,departments,course_branch,cs_branches,cs_courses,dept_course where 	course_structure.aggr_id=dept_course.aggr_id and
										departments.id='" . $dept_id . "' and
										cs_courses.id=course_branch.course_id and
										dept_course.course_branch_id=course_branch.course_branch_id and
										dept_course.dept_id='" . $dept_id . "' and
										cs_branches.id=course_branch.branch_id  GROUP BY course_branch.course_id,course_branch.branch_id DESC
										
										union
select distinct dept_course.dept_id as id,departments.name as dept_name,cs_courses.duration,course_branch.course_id,course_branch.branch_id,cs_branches.name as branch_name,cs_courses.name as course_name from departments,course_branch,course_structure,dept_course,cs_courses,cs_branches where course_branch.course_branch_id NOT IN (select course_structure.id from course_structure) and cs_courses.id = course_branch.course_id and cs_branches.id = course_branch.branch_id and dept_course.course_branch_id = course_branch.course_branch_id and dept_course.dept_id=departments.id and dept_id = '".$dept_id."'";
        return $this->db->query($sql)->result_array();
    }

    function select_all_elective_subject_by_aggr_id_and_semester_new($aggr_id,$semester,$dept,$session_year,$session,$batch)
	{
		
		$sql="SELECT	subjects.id,subject_id,name,lecture,tutorial,practical,credit_hours,contact_hours,elective,type, course_structure.aggr_id,course_structure.sequence,
		if((select count(*) from summer_offered where id=subjects.id and aggr_id=course_structure.aggr_id and session_year='$session_year' and session='$session' and batch='$batch'),'y','n') as status  
		FROM subjects 
		INNER JOIN course_structure ON course_structure.id = subjects.id 
		LEFT JOIN dept_course ON dept_course.aggr_id = course_structure.aggr_id 
		left join summer_offered so on so.id=subjects.id 
		WHERE course_structure.aggr_id = '$aggr_id' AND dept_course.dept_id = '$dept' AND course_structure.semester = '$semester' /*AND elective != '0'*/ 
		ORDER BY cast(SUBSTRING_INDEX(`sequence`, '.', 1) as decimal) asc, cast(SUBSTRING_INDEX(`sequence`, '.', -1) as decimal) asc";
		$query = $this->db->query($sql);
		return $query->result();
	}
	
}

/* End of file emp_current_entry_model.php */
/* Location: Codeigniter/application/models/employee/emp_current_entry_model.php */