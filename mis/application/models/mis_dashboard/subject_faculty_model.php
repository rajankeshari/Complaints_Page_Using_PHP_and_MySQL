<?php

class Subject_faculty_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_subject_faculty_codewise($selsyear,$selsess,$sub_code)
    {
          
        $sql = "(SELECT a.dept_id,a.course_id,a.branch_id,a.semester,b.section,a.sub_name,a.sub_code,a.sub_type,b.emp_no,
concat_ws(' ',c.first_name,c.middle_name,c.last_name)as faculty,d.name AS dept,a.session_year,a.`session`,a.dept_id AS dept_id1,a.course_id AS course_id1,a.branch_id AS branch_id1,a.semester AS semester1
 FROM cbcs_subject_offered a 
INNER JOIN cbcs_subject_offered_desc b ON b.sub_offered_id=a.id
INNER JOIN user_details c ON c.id=b.emp_no
INNER JOIN cbcs_departments  d on d.id=c.dept_id
WHERE a.session_year=? AND a.`session`=? AND a.sub_code=?)
UNION
(SELECT a.dept_id,a.course_id,a.branch_id,a.semester,b.section,a.sub_name,a.sub_code,a.sub_type,b.emp_no,
concat_ws(' ',c.first_name,c.middle_name,c.last_name)as faculty,d.name AS dept,a.session_year,a.`session`,a.dept_id AS dept_id1,a.course_id AS course_id1,a.branch_id AS branch_id1,a.semester AS semester1
 FROM old_subject_offered a 
INNER JOIN old_subject_offered_desc b ON b.sub_offered_id=a.id
INNER JOIN user_details c ON c.id=b.emp_no
INNER JOIN cbcs_departments d on d.id=c.dept_id
WHERE a.session_year=? AND a.`session`=? AND a.sub_code=?); ";

        //echo $sql;die();
        $query = $this->db->query($sql,array($selsyear,$selsess,$sub_code,$selsyear,$selsess,$sub_code));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_subject_faculty_namewise($selsyear,$selsess,$course_name)
    {
          
        $sql = "(SELECT a.dept_id,a.course_id,a.branch_id,a.semester,b.section,a.sub_name,a.sub_code,a.sub_type,b.emp_no,
concat_ws(' ',c.first_name,c.middle_name,c.last_name)as faculty,d.name AS dept,a.session_year,a.`session`,a.dept_id AS dept_id1,a.course_id AS course_id1,a.branch_id AS branch_id1,a.semester AS semester1
 FROM cbcs_subject_offered a 
INNER JOIN cbcs_subject_offered_desc b ON b.sub_offered_id=a.id
INNER JOIN user_details c ON c.id=b.emp_no
INNER JOIN cbcs_departments  d on d.id=c.dept_id
WHERE a.session_year=? AND a.`session`=? AND a.sub_name LIKE '".$course_name."%')
UNION
(SELECT a.dept_id,a.course_id,a.branch_id,a.semester,b.section,a.sub_name,a.sub_code,a.sub_type,b.emp_no,
concat_ws(' ',c.first_name,c.middle_name,c.last_name)as faculty,d.name AS dept,a.session_year,a.`session`,a.dept_id AS dept_id1,a.course_id AS course_id1,a.branch_id AS branch_id1,a.semester AS semester1
 FROM old_subject_offered a 
INNER JOIN old_subject_offered_desc b ON b.sub_offered_id=a.id
INNER JOIN user_details c ON c.id=b.emp_no
INNER JOIN cbcs_departments d on d.id=c.dept_id
WHERE a.session_year=? AND a.`session`=? AND a.sub_name LIKE '".$course_name."%'); ";

        //echo $sql;die();
        $query = $this->db->query($sql,array($selsyear,$selsess,$selsyear,$selsess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	function get_subject_faculty_empnowise($selsyear,$selsess,$emp_no)
    {
          
        $sql = "(SELECT a.dept_id,a.course_id,a.branch_id,a.semester,b.section,a.sub_name,a.sub_code,a.sub_type,b.emp_no,
concat_ws(' ',c.first_name,c.middle_name,c.last_name)as faculty,d.name AS dept,a.session_year,a.`session`,a.dept_id AS dept_id1,a.course_id AS course_id1,a.branch_id AS branch_id1,a.semester AS semester1
 FROM cbcs_subject_offered a 
INNER JOIN cbcs_subject_offered_desc b ON b.sub_offered_id=a.id
INNER JOIN user_details c ON c.id=b.emp_no
INNER JOIN cbcs_departments  d on d.id=c.dept_id
WHERE a.session_year=? AND a.`session`=? AND b.emp_no=?)
UNION
(SELECT a.dept_id,a.course_id,a.branch_id,a.semester,b.section,a.sub_name,a.sub_code,a.sub_type,b.emp_no,
concat_ws(' ',c.first_name,c.middle_name,c.last_name)as faculty,d.name AS dept,a.session_year,a.`session`,a.dept_id AS dept_id1,a.course_id AS course_id1,a.branch_id AS branch_id1,a.semester AS semester1
 FROM old_subject_offered a 
INNER JOIN old_subject_offered_desc b ON b.sub_offered_id=a.id
INNER JOIN user_details c ON c.id=b.emp_no
INNER JOIN cbcs_departments d on d.id=c.dept_id
WHERE a.session_year=? AND a.`session`=? AND b.emp_no=?); ";

        //echo $sql;die();
        $query = $this->db->query($sql,array($selsyear,$selsess,$emp_no,$selsyear,$selsess,$emp_no));

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

function get_subject_faculty_ft_namewise($selsyear,$selsess,$fname)
    {
          
        $sql = "SELECT t.*
 FROM(
(
SELECT a.dept_id,a.course_id,a.branch_id,a.semester,b.section,a.sub_name,a.sub_code,a.sub_type,b.emp_no, CONCAT_WS(' ',c.first_name,c.middle_name,c.last_name) AS faculty,d.name AS dept,a.session_year,a.`session`,a.dept_id AS dept_id1,a.course_id AS course_id1,a.branch_id AS branch_id1,a.semester AS semester1
FROM cbcs_subject_offered a
INNER JOIN cbcs_subject_offered_desc b ON b.sub_offered_id=a.id
INNER JOIN user_details c ON c.id=b.emp_no
INNER JOIN cbcs_departments d ON d.id=c.dept_id
WHERE a.session_year=? AND a.`session`=? ) UNION (
SELECT a.dept_id,a.course_id,a.branch_id,a.semester,b.section,a.sub_name,a.sub_code,a.sub_type,b.emp_no, 
CONCAT_WS(' ',c.first_name,c.middle_name,c.last_name) AS faculty,d.name AS dept,a.session_year,a.`session`,
a.dept_id,a.course_id,a.branch_id,a.semester
FROM old_subject_offered a
INNER JOIN old_subject_offered_desc b ON b.sub_offered_id=a.id
INNER JOIN user_details c ON c.id=b.emp_no
INNER JOIN cbcs_departments d ON d.id=c.dept_id
WHERE a.session_year=? AND a.`session`=? )
)t WHERE t.faculty LIKE '%".$fname."%' ";

        //echo $sql;die();
        $query = $this->db->query($sql,array($selsyear,$selsess,$selsyear,$selsess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    

 
    

}

?>