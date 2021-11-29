<?php

class Cbcs_single_grade_sheet_final_foil_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_student_details($syear,$sess,$admn_no)
    {
        /*$sql="SELECT a.admn_no,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name)AS stu_name,
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
*/

$sql="select  d.*,f.name as dname,c.name AS cname,e.name AS bname from 
(SELECT a.id AS admn_no,CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name)AS stu_name,
(case when x.admn_no is not null then x.new_course_id else  b.course_id  end ) as course_id, 
(case when x.admn_no is not null then x.new_branch_id else  b.branch_id  end ) as branch_id,
a.dept_id,a.photopath,c.semester,c.session_year,c.`session`,c.hod_status,c.acad_status
 FROM user_details a
INNER JOIN stu_academic b ON b.admn_no=a.id
inner join reg_regular_form c on c.admn_no=a.id
left join cbcs_transcript x  on  x.old_course_id=b.course_id and  x.admn_no=a.id
)d
INNER JOIN cbcs_courses c ON c.id=d.course_id
INNER JOIN cbcs_branches e ON e.id=d.branch_id
inner join cbcs_departments f on f.id=d.dept_id
WHERE d.admn_no=? and d.session_year=? and d.session=?
AND d.hod_status='1' AND d.acad_status='1'";
        $query = $this->db->query($sql,array($admn_no,$syear,$sess));
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
		$sql="SELECT a.* FROM final_semwise_marks_foil_freezed a WHERE a.session_yr=? AND a.`session`=? AND a.admn_no=? AND a.course<>'minor' and a.actual_published_on=(select max(a.actual_published_on) from final_semwise_marks_foil_freezed a
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
(SELECT t1.*,t2.cr_hr,t2.theory,t2.grade,CASE WHEN t2.grade='F' THEN 0 ELSE t2.cr_pts END AS cr_pts,t2.sub_code
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

function get_sub_name_alternate($sub_code){
	$sql="SELECT a.old_subject_name FROM alternate_course a WHERE a.old_subject_code=? GROUP BY a.old_subject_code
UNION
SELECT a.old_subject_name FROM alternate_course_all a WHERE a.old_subject_code=? GROUP BY a.old_subject_code";
        $query = $this->db->query($sql,array($sub_code,$sub_code));
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

//=============================================================Sujit Code===========================================
function get_basic_detail($admn_no){
    // $sql = "SELECT CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS sname,cc.name AS cname,u.id AS admn_no,
    // cb.name AS bname FROM users u INNER JOIN user_details ud ON u.id=ud.id INNER JOIN stu_academic sa ON u.id=sa.admn_no
    // LEFT JOIN cbcs_courses cc ON cc.id=sa.course_id LEFT JOIN cbcs_branches cb ON cb.id=sa.branch_id WHERE u.id='$admn_no'";
    

    $sql = "SELECT k.*,CONCAT(a.current_year,'/',a.form_id) AS cert_no FROM(SELECT CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS sname,cc.name AS cname
    ,u.id AS admn_no,if(cc.id='dualdegree',SUBSTRING_INDEX(cb.name, '+', 1),cb.name) AS bname,cc.id AS bid,cb.id AS cid FROM users u INNER JOIN user_details ud ON 
    u.id=ud.id INNER JOIN stu_academic sa ON u.id=sa.admn_no LEFT JOIN cbcs_courses cc ON cc.id=sa.course_id LEFT JOIN cbcs_branches cb ON cb.id=sa.branch_id
    WHERE u.id='$admn_no')k LEFT JOIN (SELECT a.* FROM (SELECT * from stu_gradesheet_number sn WHERE sn.admn_no='$admn_no' ORDER BY sn.created_date 
    DESC LIMIT 10000)a LIMIT 1)a ON k.admn_no=a.admn_no";

    $query = $this->db->query($sql);

    // echo $this->db->last_query();

    if ($this->db->affected_rows() > 0) {
        return $query->result();		 			
    }
    else {
        return FALSE;
    }

}

/*======================= 21-05-2021 ========================*/
function get_final_data($admn_no){    
    $str='';

    if (in_array("stu", $this->session->userdata('auth'))) {
        $str = 'AND f.published_on IS NOT NULL';
        // $str = 'AND a.published_on IS NOT NULL';
    }
    $sql = "SELECT f.admn_no,if(f.ctotcrpts IS NULL ,'NA',f.ctotcrpts) AS ctotcrpts,if(f.ctotcrhr IS NULL ,'NA',f.ctotcrhr) AS ctotcrhr,if((f.cgpa<5 || f.cgpa IS NULL),'INC',ROUND(CAST(FORMAT(f.cgpa,5) AS DECIMAL(10,2)),2)) AS ogpa,k.* FROM (SELECT * FROM reg_regular_form rrg WHERE rrg.admn_no='$admn_no' AND rrg.acad_status='1' AND rrg.hod_status='1' ORDER BY rrg.timestamp DESC LIMIT 1000)k INNER JOIN cbcs_courses cc ON cc.id=k.course_id AND cc.duration*2=k.semester LEFT JOIN final_semwise_marks_foil_freezed f ON f.admn_no=k.admn_no AND f.session_yr=k.session_year AND f.`session`=k.session AND f.semester=k.semester $str ORDER BY f.published_on DESC LIMIT 10000";

    $query = $this->db->query($sql);
    // echo $this->db->last_query();

     if ($this->db->affected_rows() > 0) {
        return $query->result();                    
    }
    else {
        return FALSE;
    }
}
/*======================= 21-05-2021 ========================= */

/*======================= 14-06-2021 ========================= */
function get_backlog_detail($admn_no,$session_year,$session){
    // echo $admn_no;
    // echo $session_year;
     //echo $session;
    

    $sem_str = ($session==='Monsoon' ? " and a.session_yr<='$session_year' and !(a.session_yr='$session_year' and (a.session='Winter' or a.session='Summer'))" : ($session=='Winter' ? " and a.session_yr<='$session_year' and !( a.session_yr='$session_year' and a.session='Summer')" : " and a.session_yr<='$session_year'"));
  //echo $sem_str; die();

    $sql = "SELECT * FROM(SELECT v.* FROM(SELECT y.session_yr, y.session, y.dept, y.course, y.branch, y.semester, fd.mis_sub_id, fd.sub_code, fd.grade, y.admn_no,IF(ac.alternate_subject_code IS NOT NULL,ac.old_subject_code, IF(acl.alternate_subject_code IS NOT NULL, acl.old_subject_code, fd.sub_code)) AS newsub,IF(ac.alternate_subject_code IS NOT NULL,ac.alternate_subject_code,IF(acl.alternate_subject_code IS NOT NULL, acl.alternate_subject_code, fd.sub_code)) AS alternate_sub_code FROM (SELECT x.* FROM(SELECT a.hstatus, a.session_yr, a.session, a.admn_no, a.dept, a.course, a.branch, a.semester, a.id, a.status,a.ctotcrpts, a.ctotcrhr, a.core_ctotcrpts, a.core_ctotcrhr, a.tot_cr_hr, a.tot_cr_pts, a.core_tot_cr_hr, a.core_tot_cr_pts,a.published_on, a.actual_published_on FROM final_semwise_marks_foil_freezed AS a WHERE a.admn_no = '$admn_no' AND a.actual_published_on IS NOT NULL AND UPPER(a.course) <> 'MINOR' AND (a.semester != '0' AND a.semester != '-1') AND a.course <> 'jrf' $sem_str ORDER BY a.admn_no,a.semester, a.actual_published_on DESC LIMIT 100000000)x GROUP BY x.admn_no, x.semester, IF(x.session_yr >= '2019-2020', x.session_yr, NULL), IF(x.session_yr >= '2019-2020', x.session, NULL) ORDER BY x.admn_no,x.semester, x.actual_published_on DESC LIMIT 100000000) y JOIN final_semwise_marks_foil_desc_freezed fd ON fd.foil_id = y.id AND fd.admn_no = y.admn_no LEFT JOIN alternate_course ac ON ac.admn_no = y.admn_no AND ac.old_subject_code = fd.sub_code LEFT JOIN alternate_course_all acl ON acl.old_subject_code = fd.sub_code ORDER BY y.admn_no,newsub, fd.cr_pts DESC, y.session_yr DESC LIMIT 10000000)v GROUP BY v.newsub HAVING v.grade in ('I', 'F') ORDER BY v.admn_no, v.session_yr, v.dept, v.course, v.branch, v.semester,v.newsub LIMIT 10000000)v WHERE v.alternate_sub_code NOT IN (SELECT fd.sub_code FROM (SELECT * FROM final_semwise_marks_foil_freezed a WHERE a.admn_no ='$admn_no' AND a.actual_published_on IS NOT NULL AND UPPER(a.course) <> 'MINOR' AND (a.semester != '0' AND a.semester != '-1') AND a.course <> 'jrf' AND a.session_yr<='$session_year' ORDER BY a.admn_no, a.semester, a.actual_published_on DESC LIMIT 100000000)k JOIN final_semwise_marks_foil_desc_freezed fd ON fd.foil_id = k.id AND fd.admn_no = k.admn_no AND fd.grade NOT IN ('F','I') GROUP BY fd.sub_code)";

    $query = $this->db->query($sql);
    // echo $this->db->last_query();

    if ($this->db->affected_rows() > 0) {
        return $query->result();                    
    }
    else {
        return FALSE;
    }

}

function get_drop_detail($admn_no,$cid,$bid,$session_year,$session){
    // echo $admn_no;
    // echo $cid;
    // echo $bid;
    // echo $session_year;
    // echo $session;
    // exit;


    $session_str =  ($session==='Monsoon' ? "and  session_year<='$session_year' and !(session_year='$session_year' and  (session='Monsoon' or session='Winter'  or session='Summer') )" : ( $session=='Winter' ?  " and  session_year<='$session_year' and !( session_year='$sessionYear' and ( session='Winter' or session='Summer') )" :($session=='Summer' ?  " and  session_year<='$session_year'   and !( session_year='$session_year' and ( session='Summer') )" : " and session_year<='$session_year' " )));

    $sql = "SELECT k.* FROM(SELECT 'not_offered' as check_offered,x.sub_code as prev_sub_code,x.dept_id,x.course_id,x.branch_id,x.admn_no,x.semester,x.session_year
    ,x.session,x.id AS sub_offered_id,x.sub_category,x.lecture,x.practical,x.tutorial ,x.credit_hours, x.contact_hours,x.sub_type ,IF(ac.alternate_subject_code
    IS NOT NULL, ac.alternate_subject_code,IF(acl.alternate_subject_code IS NOT NULL, acl.alternate_subject_code, x.sub_code)) AS  sub_code,
    IF(ac.alternate_subject_code IS NOT NULL, ac.alternate_subject_name,IF(acl.alternate_subject_code IS NOT NULL, acl.alternate_subject_name, x.sub_name)) AS
    sub_name FROM((SELECT x.dept_id,x.course_id,x.branch_id,x.admn_no,x.semester,  x.session_year,x.session, x.sub_code , x.foil_sub_code,  CONCAT('o',x.id) AS id
    ,csc.sub_offered_id,x.sub_category,x.sub_name,x.lecture ,x.practical,x.tutorial ,x.credit_hours, x.contact_hours,x.sub_type from ( select y.id, y.session_year,
    y.session,y.dept_id,y.course_id,y.branch_id, y.semester, rg.admn_no,y.sub_code, y.sub_category,h.sub_code as foil_sub_code,y.sub_name,y.lecture,y.practical,
    y.tutorial, y.credit_hours, y.contact_hours,y.sub_type, rg.course_aggr_id,rg.form_id  from (select  * from old_subject_offered  where sub_category not like 
    'DE%' and sub_category is not null and  sub_category<>'' and sub_code not in ('CSC19801','MSR16152','GLE26140') and course_id='$cid' and branch_id='$bid' 
    $session_str)y join reg_regular_form rg ON y.course_id= rg.course_id AND y.branch_id= rg.branch_id AND 
    rg.semester=y.semester AND rg.session_year=y.session_year AND rg.session=y.session AND rg.hod_status='1' AND rg.acad_status='1' and rg.admn_no='$admn_no'
    left  join final_semwise_marks_foil_desc  h on  h.admn_no=rg.admn_no  and h.sub_code= y.sub_code)x left join old_stu_course csc on csc.sub_offered_id=x.id 
    and csc.admn_no=x.admn_no HAVING   x.foil_sub_code is null  AND  csc.sub_offered_id is null) union (select x.dept_id,x.course_id,x.branch_id,x.admn_no,
    x.semester,x.session_year,x.session, x.sub_code ,x.foil_sub_code,CONCAT('c',x.id) AS id,csc.sub_offered_id,x.sub_category,x.sub_name,x.lecture ,x.practical,
    x.tutorial ,x.credit_hours, x.contact_hours,x.sub_type from ( select y.id, y.session_year,y.session,y.dept_id,y.course_id,y.branch_id, y.semester, rg.admn_no,
    y.sub_code, y.sub_category,h.sub_code as foil_sub_code, y.sub_name, y.lecture ,y.practical,y.tutorial, y.credit_hours, y.contact_hours,y.sub_type,
    rg.course_aggr_id,rg.form_id  from (select  * from cbcs_subject_offered  where (sub_category   like 'IC%' or sub_category   like 'DC%') and sub_category
    is not null and  sub_category<>'' and sub_code not in ('CSC19801','MSR16152','GLE26140') $session_str)y join
    reg_regular_form rg on  y.course_id= (case when (rg.section is not null and rg.section<>'0' and rg.section<>'') then 'comm' else rg.course_id end ) and 
    y.branch_id=(case when (rg.section is not null and rg.section<>'0' and rg.section<>'') then 'comm' else rg.branch_id end) and rg.semester=y.semester and 
    rg.session_year=y.session_year and rg.session=y.session AND (case when (rg.section is not null and rg.section<>'0' and rg.section<>'') then rg.section=
    y.sub_group ELSE 1=1 END) and rg.hod_status='1' AND rg.acad_status='1' and rg.admn_no='$admn_no' left  join final_semwise_marks_foil_desc h on  
    h.admn_no=rg.admn_no  and h.sub_code= y.sub_code)x left join cbcs_stu_course csc on csc.sub_offered_id=x.id and csc.admn_no=x.admn_no HAVING 
    x.foil_sub_code is null  and csc.sub_offered_id is null))x LEFT JOIN alternate_course ac ON ac.admn_no = x.admn_no AND ac.old_subject_code = x.sub_code
    LEFT JOIN alternate_course_all acl ON acl.old_subject_code = x.sub_code order BY  x.session_year,x.session,x.dept_id,x.course_id,x.branch_id,x.semester,
    x.admn_no,x.sub_code limit 1000000)k WHERE k.sub_code NOT IN (SELECT fd.sub_code FROM (SELECT * FROM final_semwise_marks_foil_freezed a Where a.admn_no =
    '$admn_no' AND a.actual_published_on IS NOT NULL AND UPPER(a.course) <> 'MINOR' AND (a.semester != '0' AND a.semester != '-1') AND a.course <> 'jrf' AND 
    a.session_yr<='$session_year' ORDER BY a.admn_no, a.semester, a.actual_published_on DESC LIMIT 100000000)k JOIN final_semwise_marks_foil_desc_freezed fd ON 
    fd.foil_id = k.id AND fd.admn_no = k.admn_no AND fd.grade  NOT IN ('F','I') GROUP BY fd.sub_code)";

    $query = $this->db->query($sql);
    // echo $this->db->last_query();

    if ($this->db->affected_rows() > 0) {
        return $query->result();                    
    }
    else {
        return FALSE;
    }

}
/*======================= 14-06-2021 ========================= */

function get_grade_detail($admn_no){

    $str='';

    if (in_array("stu", $this->session->userdata('auth'))) {
        $str = 'AND a.published_on IS NOT NULL';
    }

    $cadmn_no = substr($admn_no,0,2); 

    if($cadmn_no=='14'){
        $token = "SELECT s.session_year AS session_yr,s.session,GROUP_CONCAT(s.sub_code ORDER BY s.sessions,s.sub_order SEPARATOR '@') AS sub_code,
        ucase(GROUP_CONCAT(s.sub_name ORDER BY s.sessions,s.sub_order SEPARATOR '@')) AS sub_name,
        GROUP_CONCAT(s.cr_hr ORDER BY s.sessions,s.sub_order SEPARATOR '@') AS credit,
        GROUP_CONCAT(s.grade ORDER BY s.sessions,s.sub_order SEPARATOR '@') AS grade,s.gpa,s.cgpa,s.tot_cr_hr FROM (
        SELECT if(t.ysession='1415','2014-2015',NULL) AS session_year,ucase(case t.wsms when 'MS' then 'Monsoon' when 'WS' then 'Winter' ELSE 'Summer' END)
        AS session,(case when t.wsms='MS' then 1 when t.wsms='WS' then 2 ELSE 3 END) AS sessions,t.subje_code AS sub_code,t.subje_order AS sub_order,
        t.subje_name AS sub_name,t.crdhrs AS cr_hr,t.grade,ROUND(CAST(FORMAT(t.gpa,5) AS DECIMAL(10,2)),2) AS gpa,ROUND(CAST(FORMAT(t.ogpa,5) AS DECIMAL(10,2)),2) AS cgpa,t.totcrhr AS tot_cr_hr
        FROM tabulation1 t WHERE if(t.wsms='ZS',t.adm_no='$admn_no' AND t.subje_pf='SP',t.adm_no='$admn_no')
        ORDER BY t.wsms,t.subje_order,sessions,t.srn DESC LIMIT 100000)s
        GROUP BY s.sessions,s.session_year ORDER BY s.sessions,s.session_year";
        $tquery = $this->db->query($token);
        // echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            $data['first'] = $tquery->result();
        }
    }    

    // $sql = "SELECT s.session_yr,ucase(s.session) AS session,GROUP_CONCAT(s.sub_code ORDER BY s.types,s.sub_name SEPARATOR '@') AS sub_code,
    // ucase(GROUP_CONCAT(s.sub_name ORDER BY s.types,s.sub_name SEPARATOR '@')) AS sub_name,
    // GROUP_CONCAT(s.cr_hr ORDER BY s.types,s.sub_name SEPARATOR '@') AS credit,
    // GROUP_CONCAT(s.grade ORDER BY s.types,s.sub_name SEPARATOR '@') AS grade,s.gpa,s.cgpa,s.published_on,s.exam_type FROM (SELECT m.session_yr,m.session,m.sessions,m.sub_type,(CASE m.sub_type WHEN 'Theory' THEN '1' WHEN 'Modular' THEN '2' WHEN 'Sessional' THEN '3' WHEN 'Audit' THEN '4' WHEN 
    // 'Practical' THEN '5' WHEN 'Non-Contact' THEN '6' END) AS types,m.sub_code,m.sub_name,m.cr_hr,m.grade,m.gpa,m.cgpa,m.published_on,m.course,m.exam_type
    // FROM (SELECT x.session_yr,x.session,x.semester,(CASE x.session WHEN 'Monsoon' THEN '1' WHEN 'Winter' THEN '2' WHEN 'Summer' THEN '3' END) AS sessions,
    // x.sub_code,(case when sb.`type` IS NOT NULL then sb.`type` when cs.sub_type IS NOT NULL then cs.sub_type  else os.sub_type END) AS sub_type,
    // (CASE WHEN sb.name IS NOT NULL THEN sb.name WHEN os.sub_name IS NOT NULL THEN os.sub_name ELSE cs.sub_name END) AS sub_name,x.cr_hr,
    // x.grade,ROUND(CAST(FORMAT(x.gpa,5) AS DECIMAL(10,2)),2) AS gpa,ROUND(CAST(FORMAT(x.cgpa,5) AS DECIMAL(10,2)),2) AS cgpa,x.published_on,x.course,x.exam_type FROM
    // (SELECT x.* FROM (SELECT x.*,ff.foil_id,ff.sub_code,ff.mis_sub_id,
    // ff.cr_hr,ff.total,ff.grade,ff.cr_pts,ff.current_exam FROM (SELECT k.* FROM (SELECT a.* FROM final_semwise_marks_foil_freezed AS a WHERE a.admn_no='$admn_no' 
    // $str  AND (if(a.course='JRF',a.semester>=0,(a.semester<>0 AND a.semester<>-1))) AND a.exam_type='R'
    // ORDER BY a.session_yr,a.semester,a.exam_type,a.actual_published_on DESC LIMIT 100000000)k GROUP BY k.exam_type,k.session_yr,k.session,k.semester)x
    // INNER JOIN final_semwise_marks_foil_desc_freezed ff ON if(x.session='Summer',ff.foil_id=x.id AND ff.current_exam='Y',ff.foil_id=x.id)
    // ORDER BY x.session_yr,x.session,ff.sub_code,ff.grade  LIMIT 100000)x GROUP BY x.session_yr,x.session,x.sub_code
    // UNION ALL 
    // SELECT x.* FROM (SELECT x.*,ff.foil_id,ff.sub_code,ff.mis_sub_id,
    // ff.cr_hr,ff.total,ff.grade,ff.cr_pts,ff.current_exam FROM (
    // SELECT k.* FROM (SELECT a.* FROM final_semwise_marks_foil_freezed AS a WHERE a.admn_no='$admn_no' $str 
    // AND (if(a.course='JRF',a.semester>=0,(a.semester<>0 AND a.semester<>-1))) AND a.exam_type<>'R'
    // ORDER BY a.session_yr,a.semester,a.actual_published_on DESC LIMIT 100000000)k GROUP BY k.session_yr,k.session,k.semester)x
    // INNER JOIN final_semwise_marks_foil_desc_freezed ff ON ff.foil_id=x.id AND ff.current_exam='Y'ORDER BY x.session_yr,x.session,
    // ff.sub_code,ff.grade  LIMIT 100000)x GROUP BY x.session_yr,x.session,x.sub_code)x
    // LEFT JOIN subjects sb ON sb.id=x.mis_sub_id
    // LEFT JOIN cbcs_subject_offered cs ON 
    // if(x.course='jrf',cs.sub_code=x.sub_code AND cs.session_year=x.session_yr AND (cs.`session`=x.session OR cs.`session`= CONCAT(x.session,'1')) AND 
    // cs.course_id=x.course , cs.sub_code=x.sub_code AND cs.session_year=x.session_yr AND (cs.`session`=x.session OR cs.`session`= CONCAT(x.session,'1')))
    // LEFT JOIN old_subject_offered os ON if(x.course='jrf',os.sub_code=x.sub_code AND os.session_year=x.session_yr AND os.`session`=x.session AND os.course_id=x.course,
    // os.sub_code=x.sub_code AND os.session_year=x.session_yr AND os.`session`=x.session) GROUP BY x.session_yr,x.session,x.semester,x.exam_type,x.sub_code ORDER BY 
    // x.session_yr,sessions,x.exam_type)m ORDER BY m.session_yr,m.sessions,m.session,m.semester,types
    // )s GROUP BY s.session_yr,s.session ORDER BY s.session_yr,sessions,types";

    $sql = "SELECT s.session_yr,ucase(s.session) AS session,GROUP_CONCAT(s.sub_code ORDER BY s.types,s.sub_name SEPARATOR '@') AS sub_code,
    ucase(GROUP_CONCAT(s.sub_name ORDER BY s.types,s.sub_name SEPARATOR '@')) AS sub_name,
    GROUP_CONCAT(s.cr_hr ORDER BY s.types,s.sub_name SEPARATOR '@') AS credit,
    GROUP_CONCAT(s.grade ORDER BY s.types,s.sub_name SEPARATOR '@') AS grade,s.gpa,s.cgpa,s.published_on,s.exam_type FROM
    (SELECT m.session_yr,m.session,m.sessions,m.sub_type,(CASE m.sub_type WHEN 'Theory' THEN '1' WHEN 'Modular' THEN '2' WHEN 'Sessional' THEN '3' WHEN 'Audit' THEN '4' WHEN
    'Practical' THEN '5' WHEN 'Non-Contact' THEN '6' END) AS types,m.sub_code,m.sub_name,m.cr_hr,m.grade,m.gpa,m.cgpa,m.published_on,m.course,m.exam_type
    FROM (SELECT x.session_yr,x.session,x.semester,(CASE x.session WHEN 'Monsoon' THEN '1' WHEN 'Winter' THEN '2' WHEN 'Summer' THEN '3' END) AS sessions,
    x.sub_code,(case when sb.`type` IS NOT NULL then sb.`type` when cs.sub_type IS NOT NULL then cs.sub_type  else os.sub_type END) AS sub_type,
    (CASE WHEN sb.name IS NOT NULL THEN sb.name WHEN os.sub_name IS NOT NULL THEN os.sub_name ELSE cs.sub_name END) AS sub_name,x.cr_hr,
    x.grade,ROUND(CAST(FORMAT(x.gpa,5) AS DECIMAL(10,2)),2) AS gpa,ROUND(CAST(FORMAT(x.cgpa,5) AS DECIMAL(10,2)),2) AS cgpa,x.published_on,x.course,x.exam_type FROM
    (SELECT x.* FROM (SELECT x.*,ff.foil_id,ff.sub_code,ff.mis_sub_id,
    ff.cr_hr,ff.total,ff.grade,ff.cr_pts,ff.current_exam FROM (SELECT k.* FROM (SELECT a.* FROM final_semwise_marks_foil_freezed AS a WHERE a.admn_no='$admn_no' $str
     AND (if(a.course='JRF',a.semester>=0,(a.semester<>0 AND a.semester<>-1))) AND a.exam_type='R'
    ORDER BY a.session_yr,a.semester,a.exam_type,a.actual_published_on DESC LIMIT 100000000)k GROUP BY k.exam_type,k.session_yr,k.session,k.semester)x
    INNER JOIN final_semwise_marks_foil_desc_freezed ff ON if(x.session='Summer',ff.foil_id=x.id AND ff.current_exam='Y',ff.foil_id=x.id)
    ORDER BY x.session_yr,/*x.session*/Case WHEN x.session = 'Summer' THEN x.semester END DESC, Case WHEN 1=1 THEN x.semester END ,x.actual_published_on DESC,ff.sub_code,ff.grade  LIMIT 100000)x GROUP BY x.session_yr,x.session,x.sub_code
    UNION ALL
    SELECT x.* FROM (SELECT x.*,ff.foil_id,ff.sub_code,ff.mis_sub_id,
    ff.cr_hr,ff.total,ff.grade,ff.cr_pts,ff.current_exam FROM (
    SELECT k.* FROM (SELECT a.* FROM final_semwise_marks_foil_freezed AS a WHERE a.admn_no='$admn_no' $str 
    AND (if(a.course='JRF',a.semester>=0,(a.semester<>0 AND a.semester<>-1))) AND a.exam_type<>'R'
    ORDER BY a.session_yr,/*a.semester*/Case WHEN a.session = 'Summer' THEN a.semester END DESC, Case WHEN 1=1 THEN a.semester END ,a.actual_published_on DESC LIMIT 100000000)k GROUP BY k.session_yr,k.session,k.semester)x
    INNER JOIN final_semwise_marks_foil_desc_freezed ff ON ff.foil_id=x.id AND ff.current_exam='Y'ORDER BY x.session_yr,/*x.session*/Case WHEN x.session = 'Summer' THEN x.semester END DESC, Case WHEN 1=1 THEN x.semester END ,x.actual_published_on DESC,
    ff.sub_code,ff.grade  LIMIT 100000)x GROUP BY x.session_yr,x.session,x.sub_code)x
    LEFT JOIN subjects sb ON sb.id=x.mis_sub_id
    LEFT JOIN cbcs_subject_offered cs ON
    if(x.course='jrf',cs.sub_code=x.sub_code AND cs.session_year=x.session_yr AND (cs.`session`=x.session OR cs.`session`= CONCAT(x.session,'1')) AND cs.course_id=x.course , cs.sub_code=x.sub_code AND
    cs.session_year=x.session_yr AND (cs.`session`=x.session OR cs.`session`= CONCAT(x.session,'1')))
    LEFT JOIN old_subject_offered os ON if(x.course='jrf',os.sub_code=x.sub_code AND os.session_year=x.session_yr AND os.`session`=x.session AND os.course_id=x.course,
    os.sub_code=x.sub_code AND os.session_year=x.session_yr AND os.`session`=x.session) GROUP BY x.session_yr,x.session,x.semester,x.exam_type,x.sub_code ORDER BY
    x.session_yr,sessions,Case WHEN x.session = 'Summer' THEN x.semester END DESC, Case WHEN 1=1 THEN x.semester END ,x.exam_type)m ORDER BY m.session_yr,m.sessions,m.session,m.semester,types
    )s GROUP BY s.session_yr,s.session ORDER BY s.session_yr,sessions,types";

    // echo $this->db->last_query();
    $query = $this->db->query($sql);
    if ($this->db->affected_rows() > 0) {
        if($cadmn_no=='14'){
            $data['second']= $query->result();
            return array_merge($data['first'],$data['second']);
        }
        else{
            return $query->result(); 
        }
    }
    else {
        return FALSE;
    }
}

function getsubject($subcode){
    $sql = "SELECT c.subject_name FROM cbcs_stu_course c WHERE c.subject_code='$subcode' GROUP BY c.subject_code
    UNION SELECT c.subject_name FROM old_stu_course c WHERE c.subject_code='$subcode' GROUP BY c.subject_code";

    $query = $this->db->query($sql);
    // echo $this->db->last_query();
    if ($this->db->affected_rows() > 0) {
        return $query->row()->subject_name;		
    } 
    else {
        return FALSE;
    }


}

function check_alternate($admn_no,$subject_code){
    $sql = "SELECT * FROM alternate_course ac WHERE ac.admn_no='$admn_no' AND ac.alternate_subject_code='$subject_code'";
    $query = $this->db->query($sql);
    // echo $this->db->last_query();
    if ($this->db->affected_rows() > 0) {
        $str1 = trim($query->row()->old_subject_name);
        $str2 = trim($query->row()->alternate_subject_name);
        if($str1!=$str2){
            return true;
        }
        else{
            return false;
        }
    } 
    else {
        return false;
    }
}


//add by govind sahu
public function sessiondata()
{

 $datas=array();
 $stmtd="select id,name from cbcs_departments where type='academic' and status='1'";
 $excdep=$this->db->query($stmtd);
 $valuesd=$excdep->result_array();
 $datas['department']=$valuesd;

 $stmty="select session_year from mis_session_year";
 $excutey=$this->db->query($stmty);
 //if($excutey->num_rows() > 0)
 $valuesy=$excutey->result_array();
 $datas['session_year']=$valuesy;
 // $r=count($valuesy);
 // $q=$valuesy[$r-1]['session_year'];
 // $syear=($q)+1;
 // $i=($syear)+1;
 // $nextyear[0]['session_year']=$syear."-".$i; 
 // $datas['session_year']=array_merge($datas['session_year'],$nextyear);
 // echo "<pre>";
 // print_r($datas['session_year']);
 // exit;

 //
 // $stmtactive="select session_year from mis_session_year where active='1'";
 // $exeactive=$this->db->query($stmtactive);
 // $activevalues=$exeactive->result_array();
 // $p=$activevalues[0]['session_year'];
 // $syear=($p)+1;
 // $i=($syear)+1;
 // $yearnext[0]['session_year']=$syear."-".$i;
 // $datas['session_year']=array_merge($datas['session_year'],$yearnext);

 $stmts="select * from mis_session";
 $excutes=$this->db->query($stmts); 
 $values=$excutes->result_array();
 $datas['session']=$values;
 
 
 return $datas;

}  

public function get_course_bydept_cs($dept_id)
{

  $query = $this->db->query("SELECT DISTINCT course_branch.course_id,id,name,duration FROM 
  courses INNER JOIN course_branch ON course_branch.course_id = courses.id INNER JOIN dept_course ON 
  dept_course.course_branch_id = course_branch.course_branch_id WHERE dept_course.dept_id ='".$dept_id."'");

 if($query->num_rows() > 0)
  return $query->result();
 else
  return false;

}

public function get_branch_by_course($course,$dept){
   
$query = $this->db->query("SELECT DISTINCT id,name,dept_course.course_branch_id FROM cs_branches INNER JOIN course_branch ON course_branch.branch_id = cs_branches.id INNER JOIN dept_course ON dept_course.course_branch_id = course_branch.course_branch_id WHERE course_branch.course_id = '".$course."' AND dept_course.dept_id = '".$dept."'");
  if($query->num_rows() > 0)
    return $query->result();
  else
    return false;
}

public function get_admission_no($data)
{  
   
    $stmt="SELECT rrf.admn_no,rrf.form_id,dp.name,cd.duration,branch_id,rrf.session_year,rrf.session ,cd.name AS crs_name, cb.name AS br_name
    FROM reg_regular_form AS rrf
    INNER JOIN cbcs_courses AS cd ON rrf.course_id=cd.id
    INNER JOIN user_details AS ud ON ud.id=rrf.admn_no
    INNER JOIN users AS urs ON urs.id=rrf.admn_no
    INNER JOIN departments AS dp ON dp.id=ud.dept_id
    INNER JOIN cbcs_branches cb ON cb.id=rrf.branch_id
    where rrf.session ='".$data['session_']."' AND rrf.session_year='".$data['year_']."' AND rrf.hod_status='1' AND rrf.acad_status ='1' AND dp.id='".$data['dept_id']."' AND cd.id='".$data['course_id']."' AND cb.id='".$data['branch_id']."' AND rrf.semester='".$data['semester']."' AND urs.status='A' order by rrf.admn_no asc";
    $query = $this->db->query($stmt);
    // echo $this->db->last_query();
    // exit;
   if($query->num_rows() > 0)
     return $query->result();
    else
    return false;
}
public function get_admission_no2($data)
{  
    $s="2020-2021";
    $m="2021-2022";
    $stmt="SELECT rrf.admn_no,fsm.cgpa,rrf.form_id,dp.name,cd.duration,branch_id,rrf.session_year,rrf.session ,cd.name AS crs_name, cb.name AS br_name
    FROM reg_regular_form AS rrf
    INNER JOIN cbcs_courses AS cd ON rrf.course_id=cd.id
    INNER JOIN user_details AS ud ON ud.id=rrf.admn_no
    INNER JOIN users AS urs ON urs.id=rrf.admn_no
    INNER JOIN departments AS dp ON dp.id=ud.dept_id
    INNER JOIN cbcs_branches cb ON cb.id=rrf.branch_id
    INNER JOIN final_semwise_marks_foil_freezed as fsm on fsm.admn_no=rrf.admn_no and fsm.session_yr=rrf.session_year and fsm.`session`=rrf.`session`
    where rrf.session ='".$data['session_']."' AND (fsm.cgpa>5 ) AND rrf.session_year='".$data['year_']."' AND rrf.hod_status='1' AND rrf.acad_status ='1' AND dp.id='".$data['dept_id']."' AND cd.id='".$data['course_id']."' AND cb.id='".$data['branch_id']."' AND rrf.semester='".$data['semester']."' AND urs.status='A' AND rrf.admn_no NOT IN ((SELECT m.admn_no FROM (SELECT g.* FROM reg_regular_form g WHERE g.session_year='".$m."' AND g.session='Monsoon' UNION ALL SELECT g.* FROM reg_regular_form g WHERE g.session_year='".$s."' AND g.`session`='Summer') m)) and rrf.admn_no='".$data['adm_no']."' ";
    $query = $this->db->query($stmt);
    // echo $this->db->last_query();
    // exit;
   if($query->num_rows() > 0)
     return true;
    else
    return false;
}
public function check_random_unique()
{

}
public function insert_stu_admno_id($data)
{
    $query=$this->db->insert('stu_gradesheet_number',$data); 
    if($query)
    {
      return true;
    }
    else
    {
      return false;
    }
}
public function get_stu_admno_id($y,$s,$adm_no,$d,$c,$b,$sm)
{
    $stmt="select t.id,t.admn_no,t.form_id,t.current_year from stu_gradesheet_number t where t.session_year='$y' and t.session='$s' and t.admn_no='$adm_no' and t.dept_id='$d' and t.course_id='$c'  and t.branch_id='$b' and t.semester='$sm'";
    $query=$this->db->query($stmt);
    
    if($query)
    { 
      return $query->result();
    }
    else
    {
      return false;
    }
}
// public function get_course_branch_id($course,$brn) 
// {
//     $stmt="select t.id from stu_course_branch_id t where t.course_id='".$course."' and t.branch_id='".$brn."' ";
//     $query=$this->db->query($stmt);
//     if($query->num_rows()>0)
//     { 
//       return $query->result();
//     }
//     else
//     {
//       return false;
//     }
// }

public function check_random_exits($val,$cuurent_y)
{
    $stmt="select t.id from stu_gradesheet_number t where t.random_no='".$val."' and t.current_year='".$cuurent_y."'";
    $query=$this->db->query($stmt);
    if($query->num_rows()>0)
    { 
      return true;
    }
    else
    {
      return false;
    }
}
//add by govind sahu
//======================================================================================================================




}



?>
