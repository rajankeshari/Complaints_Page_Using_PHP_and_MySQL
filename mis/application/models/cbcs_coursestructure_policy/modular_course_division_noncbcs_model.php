<?php

class Modular_course_division_noncbcs_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_modular_subject($syear, $sess,$dept_id)
    {
          

        
        $sql="SELECT t.* from
(
(SELECT 'o' AS rstatus,a.id,a.session_year,a.`session`,a.dept_id,a.course_id,a.branch_id,a.semester,a.sub_name,a.sub_code,a.sub_type
,b.name AS cname,c.name AS bname
FROM old_subject_offered a
INNER JOIN cbcs_courses b ON b.id=a.course_id
INNER JOIN cbcs_branches c ON c.id=a.branch_id
WHERE a.session_year=? AND a.`session`=? AND a.dept_id=? 
AND a.sub_type='Modular' AND a.course_id!='comm')

union
(SELECT 'c' AS rstatus,a.id,a.session_year,a.`session`,a.dept_id,a.course_id,a.branch_id,a.semester,a.sub_name,a.sub_code,a.sub_type
,b.name AS cname,c.name AS bname
FROM cbcs_subject_offered a
INNER JOIN cbcs_courses b ON b.id=a.course_id
INNER JOIN cbcs_branches c ON c.id=a.branch_id
WHERE a.session_year=? AND a.`session`=? AND a.dept_id=?
AND a.sub_type='Modular' AND a.course_id!='comm')
)t 
ORDER BY t.dept_id,t.course_id,t.branch_id,t.semester,t.sub_name";

        
        $query = $this->db->query($sql,array($syear, $sess,$dept_id,$syear, $sess,$dept_id));

       
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	function check_into_modular_main($data)
	{
		//echo '<pre>';print_r($data);echo '</pre>';
		
		$sql="SELECT * from cbcs_modular_paper_main
WHERE session_year=? AND SESSION=? AND course_id=? AND branch_id=? AND exam_type=? AND group_section=?
AND `GROUP`=? AND section=? AND sub_code=?";
        
		//echo $sql;
        $query = $this->db->query($sql,array($data['session_year'],$data['session'],$data['course_id'],$data['branch_id'],$data['exam_type'],$data['group_section'],$data['group'],$data['section'],$data['sub_code']));
       
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
		
	}
	function insert_into_modular_main($data)
	{
		if($this->db->insert('cbcs_modular_paper_main',$data))
			return $this->db->insert_id();
		else
			return FALSE;
		
	}
	
	function get_student_old($id){
		
		   $sql="SELECT a.* FROM old_stu_course a WHERE a.sub_offered_id=?";
        
        $query = $this->db->query($sql,array($id));
       
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
		
	}
	function get_student_cbcs($id){
		
		   $sql="SELECT a.* FROM cbcs_stu_course a WHERE a.sub_offered_id=?";
        
        $query = $this->db->query($sql,array($id));
       
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
		
	}
	
	function insert_records($data)
    {
        if($this->db->insert_batch('cbcs_modular_paper_details',$data))
            return TRUE;
        else
            return FALSE;
    }
	function get_modular_main_list( $course,$syear, $sess){
		
		 $course = "'" . implode("','", explode(',', $course)) . "'";
		
		$sql="SELECT * from cbcs_modular_paper_main WHERE course_id in(". $course . ") and session_year=? AND SESSION=?";

        
        $query = $this->db->query($sql,array($syear, $sess));

       
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
		
		
	}
	
	function get_course_list($syear, $sess,$dept_id){
		$sql="SELECT group_concat(t.course_id) AS course_id from(SELECT a.course_id from old_subject_offered a  
WHERE a.session_year=? AND a.`session`=? AND a.dept_id=?
AND a.sub_type='Modular' 
UNION
SELECT a.course_id FROM cbcs_subject_offered a  
WHERE a.session_year=? AND a.`session`=? AND a.dept_id=?
AND a.sub_type='Modular')t";

        
        $query = $this->db->query($sql,array($syear, $sess,$dept_id,$syear, $sess,$dept_id));

       
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
	}
	
	function get_subject_name( $id){
		$sql="SELECT sub_name FROM cbcs_subject_offered WHERE sub_code=? GROUP BY  sub_code
union
SELECT sub_name FROM old_subject_offered WHERE sub_code=? GROUP BY  sub_code";
        $query = $this->db->query($sql,array($id,$id));
        if ($this->db->affected_rows() >= 0) {
            return $query->row()->sub_name;
        } else {
            return false;
        }
	}
	
	
	
    

}

?>
