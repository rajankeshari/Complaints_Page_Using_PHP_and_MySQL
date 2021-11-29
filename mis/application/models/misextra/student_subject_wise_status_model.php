<?php

class Student_subject_wise_status_model extends CI_Model {

    function __construct() {
        parent::__construct();
    } 

        
    function get_student_details($admn_no)
    {
		$sql="SELECT a.* from  reg_regular_form a 
where a.admn_no=?  and a.hod_status='1' and a.acad_status='1'";

        
        $query = $this->db->query($sql,array($admn_no));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	function get_registration($admn_no,$sem)
	{
		$sql="select a.session_year,a.`session`,a.semester,c.subject_id,c.name from reg_regular_form a
inner join course_structure b on b.aggr_id=a.course_aggr_id and b.semester=concat(a.semester,'_',a.section)
inner join subjects c on c.id=b.id
where a.admn_no=? and a.semester=? and a.hod_status='1' and a.acad_status='1'
";

        
        $query = $this->db->query($sql,array($admn_no,$sem));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();;
        } else {
            return false;
        }
	}
	function get_registration_rest($admn_no,$sem)
	{
		$sql="(SELECT a.session_year,a.`session`,a.semester,c.subject_id,c.name from reg_regular_form a 
inner join course_structure b on b.aggr_id=a.course_aggr_id 
and b.semester=a.semester inner join subjects 
c on c.id=b.id where a.admn_no=? and a.semester=? and a.hod_status='1' and a.acad_status='1'
AND b.sequence NOT LIKE '%.%')union(
SELECT a.session_year,a.`session`,a.semester,c.subject_id,c.name from reg_regular_form a 
inner join course_structure b on b.aggr_id=a.course_aggr_id and b.semester=a.semester 
inner join subjects c on c.id=b.id 
INNER JOIN reg_regular_elective_opted d ON d.form_id=a.form_id AND d.sub_id AND c.subject_id
where a.admn_no=? and a.semester=? and a.hod_status='1' and a.acad_status='1'
)
";

        
        $query = $this->db->query($sql,array($admn_no,$sem,$admn_no,$sem));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();;
        } else {
            return false;
        }
	}
	function get_registration_cbcs($admn_no,$syear,$sess,$sem)
	{
		$sql="SELECT a.subject_code as subject_id,a.subject_name FROM cbcs_stu_course a 
INNER JOIN reg_regular_form b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
WHERE a.admn_no=?  AND b.session_year=? AND b.`session`=? AND b.semester=?
UNION
SELECT a.subject_code as subject_id,a.subject_name FROM old_stu_course a 
INNER JOIN reg_regular_form b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
WHERE a.admn_no=?  AND b.session_year=? AND b.`session`=? AND b.semester=?
";

        
        $query = $this->db->query($sql,array($admn_no,$syear,$sess,$sem,$admn_no,$syear,$sess,$sem));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();;
        } else {
            return false;
        }
	}

    
}

?>