<?php

class Course_code_faculty_mapping_model extends CI_Model {
 
    function __construct() {
        parent::__construct();
    }

        
    
	function get_details($subcode){
		
		$sql="SELECT t.* FROM 
((SELECT a.session_year,a.`session`,
a.dept_id,a.course_id,a.branch_id,a.semester,a.sub_code,a.sub_name,
CONCAT_WS(' ',a.lecture,a.tutorial,a.practical)AS ltp,a.sub_type,
b.emp_no,
CONCAT_ws(' ',c.first_name,c.middle_name,c.last_name)AS faculty,c.dept_id AS faculty_deptid
 FROM cbcs_subject_offered a 
    INNER JOIN cbcs_subject_offered_desc b ON b.sub_offered_id=a.id
    INNER JOIN user_details c ON  c.id=b.emp_no
    WHERE a.sub_code=?)
    union
    (SELECT a.session_year,a.`session`,
a.dept_id,a.course_id,a.branch_id,a.semester,a.sub_code,a.sub_name,
CONCAT_WS(' ',a.lecture,a.tutorial,a.practical)AS ltp,a.sub_type,
b.emp_no,CONCAT_ws(' ',c.first_name,c.middle_name,c.last_name)AS faculty,c.dept_id AS faculty_deptid
 FROM old_subject_offered a 
    INNER JOIN old_subject_offered_desc b ON b.sub_offered_id=a.id
    INNER JOIN user_details c ON  c.id=b.emp_no
    WHERE a.sub_code=?))t
    ORDER BY t.session_year,t.session,t.dept_id,t.course_id,t.branch_id,t.semester";
        $query = $this->db->query($sql,array($subcode,$subcode));
        if ($this->db->affected_rows() > 0) {
             return $query->result();
        } else {
             return false;
        }
		
	}
	

    
    

 
    

}

?>