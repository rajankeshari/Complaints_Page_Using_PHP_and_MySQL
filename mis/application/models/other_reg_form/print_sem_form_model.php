<?php

class Print_sem_form_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    
    
    function get_personal_details($syear, $sess, $admn_no,$sem)
    {
       
        
        $sql="select a.form_id,a.admn_no,concat_ws(' ',b.first_name,b.middle_name,b.last_name) stu_name,b.dept_id,a.course_id,a.branch_id,
a.session_year,a.`session`,a.semester,a.section,
c.name as cname,d.name as bname,a.timestamp
from reg_regular_form a 
inner join user_details b on a.admn_no=b.id
inner join cs_courses c on c.id=a.course_id
inner join cs_branches d on d.id=a.branch_id
where  a.session_year=? and a.`session`=?
and a.admn_no=? and a.semester=?
/*and a.hod_status='1' and a.acad_status='1'*/";

            $query = $this->db->query($sql, array($syear, $sess, $admn_no,$sem));

           //echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->row();
            } else {
                return false;
            }
    }
    function get_core_details_regular($admn_no,$sem,$syear, $sess){
        $sql="select a.subject_id,a.name,'Core' as 'paper_type' from subjects a
inner join course_structure b on a.id=b.id
where b.aggr_id=(select a.course_aggr_id from reg_regular_form a  where a.admn_no=? and 
a.semester=? and a.session_year=? and a.`session`=? and a.hod_status='0' and a.acad_status='0' limit 1) 
and b.semester=? and b.sequence not like '%.%'";

            $query = $this->db->query($sql, array($admn_no,$sem,$syear, $sess,$sem));

           
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
    }
    function get_elective_details_regular($admn_no,$sem,$syear, $sess){
        $sql="select d.subject_id,d.name,
CASE (c.aggr_id) WHEN c.aggr_id not like '%honour%' THEN 'Honour' 
when c.aggr_id  like '%honour%' THEN 'Elective' END AS paper_type
from reg_regular_elective_opted a
inner join reg_regular_form b on a.form_id=b.form_id
inner join course_structure c on c.id=a.sub_id
inner join subjects d on d.id=a.sub_id
where b.admn_no=? and  b.semester=?
and b.session_year=? and b.`session`=?
/*and b.hod_status='1' and b.acad_status='1'*/";

            $query = $this->db->query($sql, array($admn_no,$sem,$syear, $sess));

           // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
    }
    function get_honour_details_regular($admn_no,$sem){
        
        $sql="select a.subject_id,a.name,'Honours' as 'paper_type' from subjects a
inner join course_structure b on a.id=b.id
where b.aggr_id=(select a.honours_agg_id from hm_form a where a.admn_no=? and a.honours='1' and a.honour_hod_status='Y'
 limit 1)
and b.semester=? and b.sequence not like '%.%'";

            $query = $this->db->query($sql, array($admn_no,$sem));

           // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
    }
    function get_minor_details_regular($admn_no,$sem){
        
        $sql="select a.subject_id,a.name,'Minor' as 'paper_type' from subjects a
inner join course_structure b on a.id=b.id
where b.aggr_id=(
select m.minor_agg_id from hm_minor_details m where 
m.form_id=(select a.form_id from hm_form a where a.admn_no=? and a.minor='1' and a.minor_hod_status='Y')
and m.offered='1')
and b.semester=?";

            $query = $this->db->query($sql, array($admn_no,$sem));

            //echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
    }
    function get_fee_regular($syear, $sess, $admn_no,$sem){
        $sql="select b.* from reg_regular_form a 
inner join reg_regular_fee b on a.form_id=b.form_id
where a.session_year=? and a.`session`=? and a.admn_no=? and a.semester=?";

            $query = $this->db->query($sql, array($syear, $sess, $admn_no,$sem));

           // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
    }
    function get_passfail($admn_no){
         $sql="SELECT z.*
FROM((
SELECT B.*
FROM (
SELECT
LEFT(a.status,1) AS passfail, a.exam_type, NULL AS sem_code, a.semester AS sem,a.gpa
FROM final_semwise_marks_foil a
WHERE a.admn_no=? and a.course<>'MINOR'
GROUP BY a.session_yr,a.semester,a.exam_type
ORDER BY a.session_yr,a.semester DESC, a.tot_cr_pts DESC, a.exam_type DESC)B
GROUP BY B.sem) UNION (
SELECT A.*
FROM (
SELECT
LEFT(a.passfail,1), a.examtype AS exam_type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,a.gpa
FROM tabulation1 a
WHERE a.adm_no=? AND a.sem_code NOT LIKE 'PREP%'
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY a.ysession DESC,sem DESC, a.wsms DESC,a.totcrpts DESC,a.examtype DESC)A
GROUP BY A.sem_code))z
GROUP BY z.sem";

            $query = $this->db->query($sql, array($admn_no,$admn_no));

           // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
    }
    
//==========================For Other==========================================================================
   
   function get_subject_details_other($syear, $sess,$admn_no,$sem){
        
       /* $sql="select p.*,c.subject_id,c.name from (
select a.* from reg_other_subject a
where a.form_id=(select b.form_id from reg_other_form b
where b.session_year=? and b.`session`=?
and b.admn_no=? and b.semester=? limit 1))p
inner join subjects c on c.id=p.sub_id";*/
        
        $sql="SELECT p.*,c.subject_id,c.name
FROM (
SELECT a.*
FROM reg_other_subject a
WHERE a.form_id=(
SELECT b.form_id
FROM reg_other_form b
WHERE b.session_year='".$syear."' AND b.`session`='".$sess."' AND b.admn_no='".$admn_no."' AND b.semester like '%".$sem."%' and hod_status<>'2' and acad_status<>'2'
LIMIT 1))p
INNER JOIN subjects c ON c.id=p.sub_id";

            $query = $this->db->query($sql);

            //echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
        
    }
   
   
   
   /* function get_subject_details_other($syear, $sess,$admn_no,$sem){
        
        $sql="select p.*,c.subject_id,c.name from (
select a.* from reg_other_subject a
where a.form_id=(select b.form_id from reg_other_form b
where b.session_year=? and b.`session`=?
and b.admn_no=? and b.semester=? limit 1))p
inner join subjects c on c.id=p.sub_id";

            $query = $this->db->query($sql, array($syear, $sess,$admn_no,$sem));

           // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
        
    } */
    
    //=====================Idle
    function get_fee_idle($syear, $sess,$admn_no,$sem){
        
        $sql="
select a.* from reg_idle_fee a
where a.form_id=(select b.form_id from reg_idle_form b
where b.session_year=? and b.`session`=?
and b.admn_no=? and b.semester=? limit 1)";

            $query = $this->db->query($sql, array($syear, $sess,$admn_no,$sem));

           // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
        
    }
    
        
    
    function get_fee_other($syear, $sess,$admn_no,$sem){
        
        $sql="
select a.* from reg_other_fee a
where a.form_id=(select b.form_id from reg_other_form b
where b.session_year=? and b.`session`=?
and b.admn_no=? and b.semester=? limit 1)";

            $query = $this->db->query($sql, array($syear, $sess,$admn_no,$sem));

           // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
        
    }
    function get_personal_details_other($syear, $sess, $admn_no,$sem){
        
        $sql="SELECT a.form_id,a.admn_no, 
CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) stu_name,b.dept_id,a.course_id,a.branch_id, 
a.session_year,a.`session`,a.semester, c.name AS cname,d.name AS bname,a.timestamp,a.reason
FROM reg_other_form a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN cs_courses c ON c.id=a.course_id
INNER JOIN cs_branches d ON d.id=a.branch_id
WHERE a.session_year=? AND a.`session`=? AND a.admn_no=? AND a.semester=?
limit 1";

            $query = $this->db->query($sql, array($syear, $sess,$admn_no,$sem));

           // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->row();
            } else {
                return false;
            }
    }
    
    function get_personal_details_idle($syear, $sess, $admn_no,$sem){
        
        $sql="SELECT a.form_id,a.admn_no, 
CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) stu_name,b.dept_id,a.course_id,a.branch_id, 
a.session_year,a.`session`,a.semester, c.name AS cname,d.name AS bname,a.timestamp
FROM reg_idle_form a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN cs_courses c ON c.id=a.course_id
INNER JOIN cs_branches d ON d.id=a.branch_id
WHERE a.session_year=? AND a.`session`=? AND a.admn_no=? AND a.semester=?
limit 1";

            $query = $this->db->query($sql, array($syear, $sess,$admn_no,$sem));

           // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->row();
            } else {
                return false;
            }
    }

}

?>