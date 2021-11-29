<?php

class Faculty_sm_status_model extends CI_Model
{
	
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

        function get_current_syear_sess(){
            $sql = "select a.session_year,a.`session` from subject_mapping a order by a.map_id desc limit 1";
            $query = $this->db->query($sql);
            if ($this->db->affected_rows() > 0) {
                return $query->row();
            } else {
            return false;
            }
        }
        
        //========Departments
        function get_departments(){
            $sql = "select a.* from departments a where a.`type`='academic';";
            $query = $this->db->query($sql);
            if ($this->db->affected_rows() > 0) {
                return $query->result();
            } else {
            return false;
            }
        }
        
        //Department Faculty
        
         function get_dept_faculty($id){
            $sql = "select a.id,concat_ws(' ',a.first_name,a.middle_name,a.last_name)as faculty from user_details a 
inner join users b on b.id=a.id
inner join emp_basic_details c on c.emp_no=a.id
where a.dept_id=? and length(a.id)<5 and b.`status`='A' and c.auth_id='ft'
order by a.first_name";
            $query = $this->db->query($sql,array($id));
            if ($this->db->affected_rows() > 0) {
                return $query->result();
            } else {
            return false;
            }
        }
     //EmP dept
        function get_emp_dept($id){
            $sql = "select  dept_id from user_details where id=?";
            $query = $this->db->query($sql,array($id));
            if ($this->db->affected_rows() > 0) {
                return $query->row()->dept_id;
            } else {
            return false;
            }
        }
	
        
        function get_subject_mapping_status($syear,$sess,$id,$did){
            /*$sql = "select c.subject_id,concat_ws( ' ',g.first_name,g.middle_name,g.last_name)as faculty,
                h.mobile_no,c.name,e.name as cname,f.name as bname,a.semester,a.aggr_id,a.section,a.map_id,b.sub_id,
                CASE  WHEN b.coordinator ='1' THEN 'Yes'   ELSE 'No'  END as mright from subject_mapping a 
inner join subject_mapping_des b on a.map_id=b.map_id
inner join subjects c on c.id=b.sub_id
inner join course_structure d on d.id=c.id
inner join cs_courses e on e.id=a.course_id
inner join cs_branches f on f.id=a.branch_id
inner join user_details g on g.id=b.emp_no
inner join user_other_details h on h.id=b.emp_no
where a.session_year=? and a.`session`=?
and b.emp_no=? and g.dept_id=?  group by c.id,a.dept_id,a.course_id,a.branch_id,a.aggr_id,a.semester,a.section";
            $query = $this->db->query($sql,array($syear,$sess,$id,$did));*/
			
			
			$sql="(SELECT a.sub_code, CONCAT_WS(' ',g.first_name,g.middle_name,g.last_name) AS faculty,
 h.mobile_no,a.sub_name,e.name AS cname,f.name AS bname,a.semester,NULL AS aggr_id,b.section,a.id,b.sub_id, 
	CASE WHEN b.coordinator ='1' THEN 'Yes' ELSE 'No' END AS mright,'old'AS rstatus
FROM old_subject_offered a
INNER JOIN old_subject_offered_desc b ON a.id=b.sub_offered_id
INNER JOIN cbcs_courses e ON e.id=a.course_id
INNER JOIN cbcs_branches f ON f.id=a.branch_id
INNER JOIN user_details g ON g.id=b.emp_no
INNER JOIN user_other_details h ON h.id=b.emp_no
WHERE a.session_year=? AND a.`session`=? AND b.emp_no=? AND g.dept_id=?
GROUP BY a.sub_code,a.dept_id,a.course_id,a.branch_id,a.semester,b.section)
UNION
(SELECT a.sub_code, CONCAT_WS(' ',g.first_name,g.middle_name,g.last_name) AS faculty,
 h.mobile_no,a.sub_name,e.name AS cname,f.name AS bname,a.semester,NULL AS aggr_id,b.section,a.id,b.sub_id, 
	CASE WHEN b.coordinator ='1' THEN 'Yes' ELSE 'No' END AS mright,'cbcs'AS rstatus
FROM cbcs_subject_offered a
INNER JOIN cbcs_subject_offered_desc b ON a.id=b.sub_offered_id
INNER JOIN cbcs_courses e ON e.id=a.course_id
INNER JOIN cbcs_branches f ON f.id=a.branch_id
INNER JOIN user_details g ON g.id=b.emp_no
INNER JOIN user_other_details h ON h.id=b.emp_no
WHERE a.session_year=? AND a.`session`=? AND b.emp_no=?AND g.dept_id=?
GROUP BY a.sub_code,a.dept_id,a.course_id,a.branch_id,a.semester,b.section)";
			$query = $this->db->query($sql,array($syear,$sess,$id,$did,$syear,$sess,$id,$did));
			
            if ($this->db->affected_rows() > 0) {
                return $query->result();
            } else {
            return false;
            }
        }
        
        function get_subject_code($syear,$sess,$did){
            
            
           /* $sql = "select b.sub_id,b.emp_no,concat_ws( ' ',g.first_name,g.middle_name,g.last_name)as faculty,h.mobile_no,c.subject_id,c.name,e.name as cname,f.name as bname,a.semester,a.aggr_id,CASE  WHEN b.coordinator ='1' THEN 'Yes'   ELSE 'No' END as mright from subject_mapping a 
inner join subject_mapping_des b on a.map_id=b.map_id
inner join subjects c on c.id=b.sub_id
inner join course_structure d on d.id=c.id
inner join cs_courses e on e.id=a.course_id
inner join cs_branches f on f.id=a.branch_id
inner join user_details g on g.id=b.emp_no
inner join user_other_details h on h.id=b.emp_no
where a.session_year=? and a.`session`=? and g.dept_id=? ";
            $query = $this->db->query($sql,array($syear,$sess,$did));*/
			
			$sql="(SELECT b.sub_id,b.emp_no, CONCAT_WS(' ',g.first_name,g.middle_name,g.last_name) AS faculty,h.mobile_no, a.sub_code,
a.sub_name ,e.name AS cname,f.name AS bname,a.semester, null as aggr_id, CASE WHEN b.coordinator ='1' THEN 'Yes' ELSE 'No' END AS mright
FROM old_subject_offered a
INNER JOIN old_subject_offered_desc b ON a.id=b.sub_offered_id
INNER JOIN cbcs_courses e ON e.id=a.course_id
INNER JOIN cbcs_branches f ON f.id=a.branch_id
INNER JOIN user_details g ON g.id=b.emp_no
INNER JOIN user_other_details h ON h.id=b.emp_no
WHERE a.session_year=? AND a.`session`=? AND g.dept_id=?
)
UNION
(SELECT b.sub_id,b.emp_no, CONCAT_WS(' ',g.first_name,g.middle_name,g.last_name) AS faculty,h.mobile_no, a.sub_code,
a.sub_name ,e.name AS cname,f.name AS bname,a.semester, null as aggr_id, CASE WHEN b.coordinator ='1' THEN 'Yes' ELSE 'No' END AS mright
FROM cbcs_subject_offered a
INNER JOIN cbcs_subject_offered_desc b ON a.id=b.sub_offered_id
INNER JOIN cbcs_courses e ON e.id=a.course_id
INNER JOIN cbcs_branches f ON f.id=a.branch_id
INNER JOIN user_details g ON g.id=b.emp_no
INNER JOIN user_other_details h ON h.id=b.emp_no
WHERE a.session_year=? AND a.`session`=? AND g.dept_id=?
)";

$query = $this->db->query($sql,array($syear,$sess,$did,$syear,$sess,$did));
            if ($this->db->affected_rows() > 0) {
                return $query->result();
            } else {
            return false;
            }
        }
        
//        function get_subject_code_faculty($syear,$sess,$did,$scode){
//            
//            
//            $sql = "select b.sub_id,b.emp_no,concat_ws( ' ',g.first_name,g.middle_name,g.last_name)as faculty,h.mobile_no,c.subject_id,c.name,e.name as cname,f.name as bname,a.semester,a.aggr_id,CASE  WHEN b.coordinator ='1' THEN 'Yes'   ELSE 'No' END as mright from subject_mapping a 
//inner join subject_mapping_des b on a.map_id=b.map_id
//inner join subjects c on c.id=b.sub_id
//inner join course_structure d on d.id=c.id
//inner join cs_courses e on e.id=a.course_id
//inner join cs_branches f on f.id=a.branch_id
//inner join user_details g on g.id=b.emp_no
//inner join user_other_details h on h.id=b.emp_no
//where a.session_year=? and a.`session`=? and g.dept_id=? and c.subject_id=?";
//            $query = $this->db->query($sql,array($syear,$sess,$did,$scode));
//            if ($this->db->affected_rows() > 0) {
//                return $query->result();
//            } else {
//            return false;
//            }
//        }
        
        //------------------------------------------
        
        function get_faculty_name($rstatus,$sub_offered_id,$sub_id){
			
			if($rstatus=='old'){$tbl=' old_stu_course_desc ';}
			if($rstatus=='cbcs'){ $tbl=' cbcs_stu_course_desc ';}
            
            $sql = "select a.emp_no,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS faculty,b.dept_id from ".$tbl." a 
inner join user_details b on b.id=a.emp_no where a.sub_offered_id=? and a.sub_id=? and a.coordinator='1'";
            $query = $this->db->query($sql,array($mid,$sid));
            if ($this->db->affected_rows() > 0) {
                return $query->result();
            } else {
            return false;
            }
            
        }
        
} 
?>