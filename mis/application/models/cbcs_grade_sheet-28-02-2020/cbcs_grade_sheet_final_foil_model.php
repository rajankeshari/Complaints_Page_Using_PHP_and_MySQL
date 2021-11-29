<?php

class Cbcs_grade_sheet_final_foil_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_student_details($syear,$sess,$admn_no)
    {
        $sql="SELECT a.admn_no,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name)AS stu_name,
c.name AS dname,d.name AS cname,e.name AS bname,b.photopath,a.semester,a.session_year,a.`session`
,concat(upper(a.course_id),' ( ',e.name,' )')AS discipline,f.section
FROM reg_regular_form a
INNER JOIN user_details b ON b.id=a.admn_no
INNER JOIN cbcs_departments c ON c.id=b.dept_id
INNER JOIN cbcs_courses d ON d.id=a.course_id
INNER JOIN cbcs_branches e ON e.id=a.branch_id
LEFT JOIN stu_section_data f ON f.admn_no=a.admn_no AND f.session_year=a.session_year
WHERE a.session_year=? AND a.`session`=?
AND a.hod_status='1' AND a.acad_status='1' AND a.admn_no=?";
        $query = $this->db->query($sql,array($syear,$sess,$admn_no));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }

    }

function get_exam_held($syear,$sess)
    {
        $sql="SELECT a.* from exam_held_time a WHERE a.syear=? AND a.`session`=? ";
        $query = $this->db->query($sql,array($syear,$sess));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }

    }

function get_final_foil_freeze_details($syear,$sess,$admn_no,$course){
 if($course=='minor'){
	 $sql="SELECT a.* FROM final_semwise_marks_foil_freezed a WHERE a.session_yr=? AND a.`session`=? AND a.admn_no=? AND a.course='minor'";
 }else{
		$sql="SELECT a.* FROM final_semwise_marks_foil_freezed a WHERE a.session_yr=? AND a.`session`=? AND a.admn_no=? AND a.course<>'minor' and a.published_on=(select max(a.published_on) from final_semwise_marks_foil_freezed a
WHERE a.admn_no='".$admn_no."' and a.session_yr='".$syear."' AND a.`session`='".$sess."' AND a.`type`='R')";
 }


        $query = $this->db->query($sql,array($syear,$sess,$admn_no));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }


}

function get_final_foil_freeze_desc_details($syear,$sess,$admn_no,$foil_id,$course){
if($course=='minor'){
	$str="WHERE t.course='minor' ORDER BY t.course,t.lecture DESC,t.tutorial DESC,t.practical DESC";
}else{
	$str="WHERE t.course<>'minor' ORDER BY t.course,t.lecture DESC,t.tutorial DESC,t.practical DESC";
}

$sql="SELECT t.* from
(SELECT t1.*,t2.cr_hr,t2.theory,t2.grade,t2.cr_pts,t2.sub_code
FROM (
SELECT p.*
FROM (
SELECT a.sub_offered_id,a.subject_code,
(case when a.course='honour' then concat(a.subject_name,'( Honour)')else a.subject_name end)as subject_name, 
CONCAT('c',a.sub_offered_id) AS rstatus,b.sub_type,b.lecture,b.tutorial,b.practical,a.course
FROM cbcs_stu_course a
INNER JOIN cbcs_subject_offered b ON b.id=a.sub_offered_id
WHERE a.session_year=? AND a.`session`=? AND a.admn_no=? UNION
SELECT a.sub_offered_id,a.subject_code,
(case when a.course='honour' then concat(a.subject_name,'( Honour)')else a.subject_name end)as subject_name,
CONCAT('o',a.sub_offered_id) AS rstatus,b.sub_type,b.lecture,b.tutorial,b.practical,a.course
FROM old_stu_course a
INNER JOIN old_subject_offered b ON b.id=a.sub_offered_id
WHERE a.session_year=? AND a.`session`=? AND a.admn_no=?) p)t1
LEFT JOIN (

SELECT  a.* FROM final_semwise_marks_foil_desc_freezed a,
(
SELECT s.foil_id,s.admn_no,s.sub_code , max(s.cr_pts) as mks from final_semwise_marks_foil_desc_freezed s WHERE s.foil_id=? 
group by s.sub_code)  s2
WHERE a.admn_no=s2.admn_no AND a.sub_code=s2.sub_code AND a.cr_pts=s2.mks  and  a.foil_id=s2.foil_id
group by a.sub_code
order by a.sub_code)t2 ON (t2.sub_code=t1.subject_code)
)t $str";
        $query = $this->db->query($sql,array($syear,$sess,$admn_no,$syear,$sess,$admn_no,$foil_id));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
 

}


function get_final_foil_details($syear,$sess,$admn_no){

$sql="SELECT a.* FROM final_semwise_marks_foil a WHERE a.session_yr=? AND a.`session`=? AND a.admn_no=? ";
        $query = $this->db->query($sql,array($syear,$sess,$admn_no));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }


}

function get_final_foil_desc_details($foil_id){

$sql="select a.*,b.name FROM final_semwise_marks_foil_desc a
INNER JOIN subjects b ON b.id=a.mis_sub_id WHERE a.foil_id=? ";
        $query = $this->db->query($sql,array($foil_id));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
}

function get_alternate_course($admn_no,$sub_code){

//$sql="SELECT CONCAT(a.alternate_subject_code,' In Place of ',a.old_subject_code)AS old_subject FROM alternate_course a WHERE a.admn_no=? AND a.alternate_subject_code=? ";

$sql="SELECT a.old_subject_code AS old_subject FROM alternate_course a WHERE a.admn_no=? AND a.alternate_subject_code=? ";
        $query = $this->db->query($sql,array($admn_no,$sub_code));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
}

function get_alternate_course_all($sub_code){
$sub_code = "'" . implode("','", explode(',', $sub_code)) . "'";

 $sql="SELECT group_concat(a.old_subject_code SEPARATOR ',') as old_subject_code FROM alternate_course_all a WHERE a.alternate_subject_code in (".$sub_code.") GROUP BY a.alternate_subject_code";
        $query = $this->db->query($sql);
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
}

function check_pass_fail($admn_no,$sub_code){
	$sub_code = "'" . implode("','", explode(',', $sub_code)) . "'";
	 $sql="		SELECT a.* FROM final_semwise_marks_foil_desc_freezed a 
  INNER JOIN final_semwise_marks_foil_freezed b ON b.id=a.foil_id   WHERE  b.admn_no=? AND a.sub_code in (".$sub_code.") AND (a.grade='F' or a.grade='D')   ORDER BY a.foil_id DESC LIMIT 1 ";
        $query = $this->db->query($sql,array($admn_no));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
	
}

function get_student_semester($admn_no){
	
	 $sql="SELECT a.* FROM reg_regular_form a WHERE a.admn_no=? ORDER BY a.semester DESC LIMIT 1 "; 
        $query = $this->db->query($sql,array($admn_no));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
	
	
}

function get_main_menu_details($admn_no){
	$sql="SELECT a.session_year FROM reg_regular_form a WHERE a.hod_status='1' AND a.acad_status='1' and a.admn_no=? AND SUBSTRING_INDEX(a.session_year, '-', 1)>=2019 GROUP BY a.session_year; "; 
        $query = $this->db->query($sql,array($admn_no));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
	
}

function get_sub_menu_details($session_year,$admn_no){
	$sql="SELECT a.session FROM reg_regular_form a WHERE a.hod_status='1' AND a.acad_status='1'  AND a.session_year=? and a.admn_no=? "; 
        $query = $this->db->query($sql,array($session_year,$admn_no));
        // echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
	
	
}

function get_stu_minor_status($session_year,$admn_no){
	$sql="SELECT a.* FROM final_semwise_marks_foil_freezed a WHERE a.session_yr=?  AND a.course='minor' AND a.admn_no=? "; 
        $query = $this->db->query($sql,array($session_year,$admn_no));
        // echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
	
	
}
function update_cgpa($syear,$sess,$admn_no,$id,$course){
	
	
 if($course=='minor'){
	 $sql="";
 }else{
		$sql="
SELECT SUM(z.cr_pts) AS ctotcrpts, SUM(z.cr_hr) AS ctotcrhr, FORMAT((SUM(z.cr_pts)/ SUM(z.cr_hr)),5) AS cgpa, 
SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL)), z.cr_pts, 0)) AS core_ctotcrpts, 
SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL)), z.cr_hr, 0)) AS core_ctotcrhr,
 FORMAT((SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL)), z.cr_pts, 0))/ SUM(IF (((z.course_id<> 'honour' OR z.course_id IS NULL)), z.cr_hr, 0))),5) AS core_cgpa
FROM 
(
SELECT z1.* from
(
SELECT v.*
FROM (
SELECT y.session_yr,y.session,y.dept,y.course,y.branch,y.semester,fd.sub_code, fd.grade, fd.cr_pts, fd.cr_hr,  fd.mis_sub_id,
y.admn_no, IF(ac.alternate_subject_code IS NOT NULL,ac.old_subject_code, 
IF(acl.alternate_subject_code IS NOT NULL,acl.old_subject_code,fd.sub_code)) AS newsub,

if(o.course_id IS NULL ,if(cs.id IS NOT NULL, 'honour',NULL ),o.course_id) AS course_id,
 cs.id

 
 FROM ( SELECT x.* FROM ( SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id,a.`status`,
	a.ctotcrpts,a.ctotcrhr, a.core_ctotcrpts,a.core_ctotcrhr, a.tot_cr_hr,a.tot_cr_pts, a.core_tot_cr_hr,a.core_tot_cr_pts 
	FROM final_semwise_marks_foil_freezed AS a WHERE a.admn_no='".$admn_no."' AND UPPER(a.course)<>'MINOR' AND
	 (a.semester!= '0' AND a.semester!='-1') and a.course<>'jrf' ORDER BY a.semester,a.admn_no,a.actual_published_on DESC
		 LIMIT 10000)x GROUP BY x.semester) y JOIN final_semwise_marks_foil_desc_freezed fd ON fd.foil_id=y.id AND 
			
			fd.admn_no=y.admn_no LEFT JOIN alternate_course ac ON ac.session_year=y.session_yr AND ac.`session`=y.session AND
			 ac.admn_no=y.admn_no AND ac.alternate_subject_code=fd.sub_code 
				-- AND fd.sub_code='F'
				 LEFT JOIN alternate_course_all acl ON acl.session_year=y.session_yr AND acl.`session`=y.session AND
					 acl.alternate_subject_code=fd.sub_code left join old_subject_offered o on o.sub_code=fd.sub_code and 
						o.session_year=y.session_yr and o.`session`=y.session and o.dept_id=y.dept and (case when o.course_id='honour' 
						then 'honour' else y.course end)=o.course_id and o.branch_id=y.branch 
						
						LEFT JOIN  subjects s ON s.id = fd.mis_sub_id AND y.session_yr<'2019-2020'
						LEFT JOIN  course_structure cs ON cs.id= fd.mis_sub_id AND cs.aggr_id LIKE '%honour%' AND  cs.semester=y.semester
						 AND y.session_yr<'2019-2020'
						
						ORDER BY newsub,y.session_yr DESC )v
						 GROUP BY v.newsub  ORDER BY v.session_yr,v.dept,v.course,v.branch,v.semester,v.newsub ) z1
						  GROUP BY z1.sub_code)z
							
							 GROUP BY 				
							z.admn_no
						 
		;






";
 }


        $query = $this->db->query($sql);
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            $tt=$query->row();
			$sql=" UPDATE final_semwise_marks_foil_freezed
	set 
	
	ctotcrhr=".$tt->ctotcrhr.",
	ctotcrpts =".$tt->ctotcrpts.",
	core_ctotcrhr=".$tt->core_ctotcrhr.",
	core_ctotcrpts =".$tt->core_ctotcrpts.",	  
    cgpa=".$tt->cgpa.",
    core_cgpa=".$tt->core_cgpa."
    
	WHERE session_yr='".$syear."' AND SESSION='".$sess."' AND admn_no='".$admn_no."' AND id=".$id." ";
	 //echo $sql; die();
	
	 $query = $this->db->query($sql);
	 if ($this->db->affected_rows() > 0) {
            return TRUE;		 			
        } else {
            return FALSE;
        }
			 
	 } else 
            return FALSE;
        


}




}



?>
