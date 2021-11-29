<?php

class Stu_registration_history_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_details($admn_no)
    {
       
        $sql = "SELECT t.*, CASE WHEN t.session='Monsoon' THEN '1' WHEN t.session='Winter' THEN '2' WHEN t.session='Summer' THEN '3' END AS exam_seq
FROM (
SELECT x.*
FROM((
SELECT 'na' AS form_id,a.adm_no AS admn_no,b.course_id,b.branch_id,
RIGHT(a.sem_code,1) AS semester,'na' AS section, a.ysession AS session_year,a.wsms AS `session`,'na' AS hod_status,'na' AS acad_status,'na' AS TIMESTAMP,'Old' AS exam_type,'tabulation1' AS tbl_name
FROM tabulation1 a
INNER JOIN stu_academic b ON b.admn_no=a.adm_no
WHERE a.adm_no=?
GROUP BY a.sem_code) UNION (
SELECT a.form_id,a.admn_no,a.course_id,a.branch_id,a.semester,b.section, a.session_year,a.`session`,a.hod_status,a.acad_status,a.timestamp,'Regular' AS exam_type,'reg_regular_form' AS tbl_name
FROM reg_regular_form a
LEFT JOIN stu_section_data b ON b.admn_no=a.admn_no AND a.session_year=b.session_year
WHERE a.admn_no=? AND a.hod_status='1' AND a.acad_status='1') UNION (
SELECT a.form_id,a.admn_no,a.course_id,a.branch_id,a.semester,'na' AS section, a.session_year,a.`session`,a.hod_status,a.acad_status,a.timestamp,'Carry' AS exam_type,'reg_other_form' AS tbl_name
FROM reg_other_form a
WHERE a.admn_no=? AND a.hod_status='1' AND a.acad_status='1') UNION (
SELECT a.form_id,a.admn_no,a.course_id,a.branch_id,a.semester,'na' AS section, a.session_year,a.`session`,a.hod_status,a.acad_status,a.timestamp,'Exam' AS exam_type,'reg_exam_rc_form' AS tbl_name
FROM reg_exam_rc_form a
WHERE a.admn_no=? AND a.hod_status='1' AND a.acad_status='1') UNION (
SELECT a.form_id,a.admn_no,a.course_id,a.branch_id,substring(d.semester,1,1)AS semester,'na' AS section, a.session_year,a.`session`,a.hod_status,a.acad_status,a.timestamp,'Summer' AS exam_type,'reg_summer_form' AS tbl_name
FROM reg_summer_form a
INNER JOIN reg_summer_subject b ON b.form_id=a.form_id
INNER JOIN subjects c ON c.id=b.sub_id
INNER JOIN course_structure d ON d.id=c.id
WHERE a.admn_no=? AND a.hod_status='1' AND a.acad_status='1'
GROUP BY d.semester))x
ORDER BY x.session_year,x.session)t
ORDER BY t.session_year,exam_seq,t.session,t.semester



";
        $query = $this->db->query($sql,array($admn_no,$admn_no,$admn_no,$admn_no,$admn_no));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function stu_personal_details($id){
      /*  $sql="select a.id,
concat_ws(' ',a.first_name,a.middle_name,a.last_name)as sname,
c.name as dname,
d.name as cname,
e.name as bname,
a.photopath,b.course_id,d.duration*2 AS tot_sem
from user_details a 
inner join stu_academic b on b.admn_no=a.id
inner join departments c on c.id=a.dept_id
left join cbcs_courses d on d.id=b.course_id
left join cbcs_branches e on e.id=b.branch_id
inner join users f on f.id=a.id and  (f.`status`='A'||f.`status`='P')
where a.id=?";*/

$sql="SELECT a.id, CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name) AS sname, c.name AS dname, d.name AS 
cname, e.name AS bname, a.photopath,b.course_id,d.duration*2 AS tot_sem,a.email AS per_email,g.domain_name AS ins_email,
h.mobile_no,i.parent_mobile_no
FROM user_details a
INNER JOIN stu_academic b ON b.admn_no=a.id
INNER JOIN departments c ON c.id=a.dept_id
LEFT JOIN cbcs_courses d ON d.id=b.course_id
LEFT JOIN cbcs_branches e ON e.id=b.branch_id
LEFT JOIN emaildata g ON g.admission_no=a.id
inner join user_other_details h ON h.id=a.id
inner join stu_details i ON i.admn_no=a.id
INNER JOIN users f ON f.id=a.id AND (f.`status`='A'||f.`status`='P')
WHERE a.id=?";
        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }


    }
    function check_physical_verification($admn_no,$session_year,$session){
        
        $sy=explode('-', $session_year);
        if($sy[0]>='2019'){
             $sql="select a.* from reg_regular_form a
where a.admn_no=? and a.session_year=? and a.`session`=? 
and a.hod_status='1' and a.acad_status='1' and a.`status` ='1' and a.re_id like '%verified%'";
        $query = $this->db->query($sql,array($admn_no,$session_year,$session));

       //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return 'Verified';
        } else {
             return 'Not Verified';
        }

        }
        else{
            return 'NA';
        }
       


    }
    function check_fee_details($admn_no,$session_year,$session,$tbl){
		
	
		
	
		if($tbl=='reg_regular_form'){
			//$t1='reg_regular_form';
			$t2='reg_regular_fee';
		}
		if($tbl=='reg_summer_form'){
			///$t1='reg_summer_form';
			$t2='reg_summer_fee';
		}
		if($tbl=='reg_other_form'){
			//$t1='reg_other_form';
			$t2='reg_other_fee';
		}
		if($tbl=='reg_exam_rc_form'){
			//$t1='reg_exam_rc_form';
			$t2='reg_exam_rc_fee';
		}
	
	
        $sql="select b.* from ".$tbl." a
inner join ".$t2." b on b.form_id=a.form_id and b.admn_no=a.admn_no
where  a.admn_no=? and a.session_year=? and a.`session`=? 
and a.hod_status='1' and a.acad_status='1' ";

        $query = $this->db->query($sql,array($admn_no,$session_year,$session));

       //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
             return $query->row();
        } else {
             return false;
        }
    }
    function get_registration_details($formid,$tbl){
    $sql="select a.* from ".$tbl." a where a.form_id=?";
    $query = $this->db->query($sql,array($formid));

       //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
             return $query->row();
        } else {
             return false;
        }

    }
    function get_old_courses($aggr_id,$sem,$sec){
    if($sem=='1' || $sem=='2'){
        $s=$sem.'_'.$sec;
    $sql="select b.subject_id,b.name,b.lecture,b.tutorial,b.practical,b.`type` from course_structure a 
inner join subjects b on b.id=a.id
where a.aggr_id='".$aggr_id."' and a.semester='".$s."'
order by b.name";
    $query = $this->db->query($sql);
    }   
    else{
    $sql="select b.subject_id,b.name,b.lecture,b.tutorial,b.practical,b.`type` from course_structure a 
inner join subjects b on b.id=a.id
where a.aggr_id=? and a.semester=?
order by b.name";
    $query = $this->db->query($sql,array($aggr_id,$sem));
    }
    

       //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
             return $query->result();
        } else {
             return false;
        }

    }
	function get_old_courses_other($tbl,$form_id)
	{
		if($tbl=='reg_summer_form'){
			$t1='reg_summer_subject';
			
		}
		if($tbl=='reg_other_form'){
			$t1='reg_other_subject';
		}
		if($tbl=='reg_exam_rc_form'){
			$t1='reg_exam_rc_subject';
			
		}
		
		$sql="SELECT b.subject_id,b.name,b.lecture,b.tutorial,b.practical,b.`type` FROM ".$t1." a 
INNER JOIN subjects b ON b.id=a.sub_id
WHERE a.form_id=?
order by b.name";
    $query = $this->db->query($sql,array($form_id));
	if ($this->db->affected_rows() > 0) {
             return $query->result();
        } else {
             return false;
        }
		
	}

    function get_new_courses($formid,$adm_no){
        $sql="
(select a.subject_code as subject_id,a.subject_name as name,b.lecture,b.tutorial,b.practical,b.sub_type as type from old_stu_course a 
inner join old_subject_offered b on b.id=a.sub_offered_id
where a.form_id=? and a.admn_no=?)
union
(select a.subject_code as subject_id,a.subject_name as name,b.lecture,b.tutorial,b.practical,b.sub_type as type from cbcs_stu_course a 
inner join cbcs_subject_offered b on b.id=a.sub_offered_id
where a.form_id=? and a.admn_no=?)";
        $query = $this->db->query($sql,array($formid,$adm_no,$formid,$adm_no));
        if ($this->db->affected_rows() > 0) {
             return $query->result();
        } else {
             return false;
        }

    }
	function check_result($admn_no,$session_year,$session,$semester){
		$sql="SELECT a.status FROM final_semwise_marks_foil_freezed a
WHERE  a.admn_no=? and a.session_yr=? AND a.`session`=? and a.semester=?";
        $query = $this->db->query($sql,array($admn_no,$session_year,$session,$semester));
        if ($this->db->affected_rows() > 0) {
             return $query->row();
        } else {
             return false;
        }
	}
	function stu_current_registration($admn_no){
		$sql="SELECT a.* FROM reg_regular_form a WHERE a.admn_no=?  AND a.hod_status='1'
AND a.acad_status='1' ORDER BY a.semester DESC LIMIT 1";
        $query = $this->db->query($sql,array($admn_no));
        if ($this->db->affected_rows() > 0) {
             return $query->row();
        } else {
             return false;
        }
		
	}
	
	function check_registration($admn_no,$syear,$sess,$subcode){
		
		$sql="SELECT t.* FROM
(
SELECT a.session_year,a.`session`,a.subject_code FROM old_stu_course a WHERE a.admn_no=?
UNION
SELECT a.session_year,a.`session`,a.subject_code FROM cbcs_stu_course a WHERE a.admn_no=?
)t
WHERE t.session_year=? AND t.session=? AND t.subject_code=?";
        $query = $this->db->query($sql,array($admn_no,$admn_no,$syear,$sess,$subcode));
        if ($this->db->affected_rows() > 0) {
             return true;
        } else {
             return false;
        }
		
	} 


     function get_course_structure($sy,$sess,$semester,$course_id,$branch_id){
        $sql="

(SELECT a.sub_code,a.sub_name,a.lecture,a.tutorial,a.practical,a.sub_type FROM cbcs_subject_offered a WHERE a.session_year=? AND a.`session`=? AND a.semester=? AND a.course_id=?  AND a.branch_id=?)
UNION
(SELECT a.sub_code,a.sub_name,a.lecture,a.tutorial,a.practical,a.sub_type FROM old_subject_offered a WHERE a.session_year=? AND a.`session`=? AND a.semester=? AND a.course_id=? AND a.branch_id=?)";
        $query = $this->db->query($sql,array($sy,$sess,$semester,$course_id,$branch_id,$sy,$sess,$semester,$course_id,$branch_id));
        if ($this->db->affected_rows() > 0) {
             return $query->result();
        } else {
             return false;
        }

    }

    function stu_registration($sy,$sess,$sem,$admn_no){
        $sql="SELECT a.* FROM reg_regular_form a WHERE a.session_year=? AND a.`session`=?
AND a.semester=? AND a.admn_no=? AND a.hod_status='1' AND a.acad_status='1';";
        $query = $this->db->query($sql,array($sy,$sess,$sem,$admn_no));
        if ($this->db->affected_rows() > 0) {
             return $query->row();
        } else {
             return false;
        }
        
    }

    function get_grade($session_year,$session,$semester,$course_id,$branch_id,$admn_no,$sub_code){

        $sql="SELECT b.* FROM final_semwise_marks_foil_freezed a
INNER JOIN final_semwise_marks_foil_desc_freezed b ON b.foil_id=a.id
WHERE a.session_yr=? AND a.`session`=? AND a.semester=? AND a.course=? AND a.branch=?
AND b.admn_no=? AND b.sub_code=? ";
        $query = $this->db->query($sql,array($session_year,$session,$semester,$course_id,$branch_id,$admn_no,$sub_code));
        if ($this->db->affected_rows() > 0) {
             return $query->row();
        } else {
             return false;
        }


    }
	

    
    

 
    

}

?>