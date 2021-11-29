<?php

class Semester_date_open_close_model extends CI_Model
{
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
        
 //==================================================================================================================
        function get_course()
	{
		
		$query = $this->db->query("SELECT * from cs_courses where id<>'comm'");
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}
	function get_branch()
	{
		//$query = $this->db->query("SELECT * from cs_branches"); //It contains multiple id and name for one subject
            $query = $this->db->query("SELECT * from branches");
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}
        
        
        
        

//==================================================================================================================
	
	
	function get_course_bydept_cs($dept_id)
	{
		
		$query = $this->db->query("SELECT DISTINCT course_branch.course_id,id,name,duration FROM 
		cs_courses INNER JOIN course_branch ON course_branch.course_id = cs_courses.id INNER JOIN dept_course ON 
		dept_course.course_branch_id = course_branch.course_branch_id WHERE dept_course.dept_id = '$dept_id' and course_branch.course_id<>'comm'");
		 //   echo  $this->db->last_query();	die();
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}
        
        function get_course_semester($dept_id,$cid)
	{
		
		$query = $this->db->query("SELECT DISTINCT duration FROM 
		cs_courses INNER JOIN course_branch ON course_branch.course_id = cs_courses.id INNER JOIN dept_course ON 
		dept_course.course_branch_id = course_branch.course_branch_id WHERE dept_course.dept_id = '$dept_id' and course_branch.course_id<>'comm' and course_branch.course_id='".$cid."'");
		 //   echo  $this->db->last_query();	die();
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}
        
        function insert($data)
	{
		if($this->db->insert('sem_date_open_close_tbl',$data))
			return $this->db->insert_id();
		else
			return FALSE;
	}
        //All Courses
        function get_all_course_list(){
            
            $sql = "select * from sem_date_open_close_tbl where open_for='all' order by UNIX_TIMESTAMP(normal_start_date) desc";

        $query = $this->db->query($sql);

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        }
        //Specific
        function get_all_specific_list(){
            
            $sql = "select * from sem_date_open_close_tbl where open_for='specific' order by UNIX_TIMESTAMP(normal_start_date) desc";

        $query = $this->db->query($sql);

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        }
        //Individual
        function get_all_individual_list(){
            
            $sql = "select * from sem_date_open_close_tbl where open_for='indi_stu' order by UNIX_TIMESTAMP(normal_start_date) desc";

        $query = $this->db->query($sql);

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        }
        
         function insert_batch($data)
	{
		if($this->db->insert_batch('sem_date_open_close_late_tbl',$data))
			return TRUE;
		else
			return FALSE;
	}
        function get_late_fine_detaisl($id){
            $sql = " select * from sem_date_open_close_late_tbl where master_id=?";

        $query = $this->db->query($sql,array($id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        }
        function get_detais_btID($id){
            $sql = "  select * from sem_date_open_close_tbl where  id=?";

        $query = $this->db->query($sql,array($id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        }
        
        function check_already($data1){
         $q= $this->db->get_where('sem_date_open_close_tbl',$data1 );

       //echo $this->db->last_query(); die();
        if ($q->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
        }
        
        
        function check_under_date_span($session_yr,$session,$exam_type,$open_for){
            $sql="select x.id from sem_date_open_close_tbl x 
                  inner join sem_date_open_close_late_tbl y on x.id=y.master_id
                  where x.session_year =? and x.`session`=? and x.exam_type=? and x.open_for=? and 
                  (curdate() between x.normal_start_date and x.normal_close_date or   curdate() between y.late_start_date and y.late_close_date)";
            $query = $this->db->query($sql,array($session_yr,$session,$exam_type,$open_for));
            return ($query->num_rows()>0?true:false) ;        
        }
        
       

}
