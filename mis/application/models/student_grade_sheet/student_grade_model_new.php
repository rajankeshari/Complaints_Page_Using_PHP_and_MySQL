<?php

class Student_grade_model_new extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    
    
    function get_course_duration_sem($adm_no)
    {
        $sql="select a.course_id,b.duration,(b.duration*2)as tsem,a.auth_id  from stu_academic a
inner join cs_courses b on a.course_id=b.id and a.admn_no='".$adm_no."'
group by a.course_id";
        $query = $this->db->query($sql);
        // echo $this->db->last_query();    
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
        
    }
    
    
    function stu_details_regular_status($admn_no,$sem,$sy,$sess,$et='') 
    {
        
        if($et=='O')
        {
            $ret="other";
            $et='R';
        }
        else
        {
            $ret="special";
            
        }
       
       // $sql = " select * from reg_regular_form where admn_no='".$admn_no."' and semester=".$sem." and hod_status='1' and acad_status='1'";
      /*$sql = "select * from reg_exam_rc_form where admn_no='".$admn_no."' and semester like '%".$sem."%' and hod_status='1' and acad_status='1' and session_year='".$sy."' and `session`='".$sess."' and type='".$et."'";   //and reg_type='".$et."'"*/
       
        $sql="SELECT *
FROM reg_other_form
WHERE admn_no=? AND semester LIKE '%".$sem."%' AND hod_status='1' 
AND acad_status='1' AND session_year=? AND `session`=? AND TYPE=?
union
SELECT *
FROM reg_exam_rc_form
WHERE admn_no=? AND semester LIKE '%".$sem."%' AND hod_status='1' 
AND acad_status='1' AND session_year=? AND `session`=? AND TYPE=?";
        
        $query = $this->db->query($sql,array($admn_no,$sy,$sess,$et,$admn_no,$sy,$sess,$et));
        //echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            return $ret;
        } else {
            $p=$this->stu_details_other_status($admn_no,$sem,$sy,$sess,$et='');
            return $p;
        }
    }
    function stu_details_other_status($admn_no,$sem,$sy,$sess,$et='') 
    {
      //  $sql = " select * from reg_exam_rc_form where admn_no='".$admn_no."' and semester=".$sem." and hod_status='1' and acad_status='1'";
     $sql = "select * from reg_regular_form where admn_no='".$admn_no."' and semester like '%".$sem."%' and hod_status='1' and acad_status='1' and session_year='".$sy."' and `session`='".$sess."'";  
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
          return 'regular';
        } else {
            //return FALSE;
           // return 'previous';
            $p=$this->stu_details_status_summer($admn_no,$sem,$sy,$sess,$et='');
            return $p;
        }
    }
    //--------------summer-------------
    function stu_details_status_summer($admn_no,$sem,$sy,$sess,$et='') 
    {
      //  $sql = " select * from reg_exam_rc_form where admn_no='".$admn_no."' and semester=".$sem." and hod_status='1' and acad_status='1'";
     $sql = "SELECT b.*
FROM reg_summer_form b
inner join reg_summer_subject c on c.form_id=b.form_id
inner join course_structure d on c.sub_id=d.id
WHERE b.admn_no = '".$admn_no."' AND d.semester LIKE '%".$sem."%' AND b.hod_status = '1' AND b.acad_status = '1'
and b.session_year='".$sy."' and b.`session`='".$sess."' group by b.admn_no";  
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
          return 'summer';
        } else {
            //return FALSE;
            return 'previous';
        }
    }
    
    
    //--------------------------
    
    
    
    function check_marks_availability($admn_no,$sem,$et,$sy,$sess)
    {
      if($et=='J' || $et=='JS')
      {
          $sql="select a.*,b.*,c.semester from marks_subject_description a
inner join marks_master b on a.marks_master_id=b.id
inner join subject_mapping c on c.map_id=b.sub_map_id
where a.admn_no='".$admn_no."' and b.`type`='".$et."' and b.session_year='".$sy."' and b.`session`='".$sess."'";
      }else{
        $sql="select a.*,b.*,c.semester from marks_subject_description a
inner join marks_master b on a.marks_master_id=b.id
inner join subject_mapping c on c.map_id=b.sub_map_id
where a.admn_no='".$admn_no."' and c.semester=".$sem."
and b.`type`='".$et."' and b.session_year='".$sy."' and b.`session`='".$sess."'";
      }
        $query = $this->db->query($sql);
     // echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
           return TRUE;
        } else {
            return FALSE;
        }
    }
    
    function check_jrf($admn_no)
    {
        $sql = "select * from stu_academic where admn_no=? and auth_id='jrf'";
        $query = $this->db->query($sql,array($admn_no));
       
        if ($this->db->affected_rows() > 0) {
           return TRUE;
        } else {
            return FALSE;
        }
    }
    function check_jrf_registration($admn_no)
    {
        $sql = "select * from reg_exam_rc_form where course_id='jrf' and admn_no='".$admn_no."' and hod_status='1' and acad_status='1'";
        $query = $this->db->query($sql);
       
        if ($this->db->affected_rows() > 0) {
           return TRUE;
        } else {
            return FALSE;
        }
    }
    function fetch_jrf_sy_session($admn_no)
    {
      //  $sql = "select * from reg_exam_rc_form where course_id='jrf' and admn_no='".$admn_no."' and hod_status='1' and acad_status='1'";
       $sql="SELECT b.session_year, b.`session`,b.`type`,b.admn_no,
CASE (b.`type`) WHEN 'R' THEN 'Regular'  ELSE 'Special' END AS last_et
,concat('JRF','_',b.`session`,'_',b.session_year,'_',CASE (b.`type`) WHEN 'R' THEN 'Regular'  ELSE 'Special' END) as tab
FROM reg_exam_rc_form b
WHERE b.admn_no = ? AND b.hod_status = '1' AND b.acad_status = '1'


";
        $query = $this->db->query($sql,array($admn_no));
       
      if ($query->num_rows() > 0) {
          return $query->result();
        } else {
            return FALSE;
        }
    }
    
        function stu_details_others_jrf($adm_no,$sy,$sess) {
        $sql = "select a.*,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as stu_name,b.dept_id,b.photopath,
d.name as dept_nm from reg_exam_rc_form a
inner join user_details b on a.admn_no=b.id 
inner join stu_academic c on c.admn_no=b.id
inner join departments d on d.id=b.dept_id
where a.admn_no='".$adm_no."' and session_year='".$sy."' and session='".$sess."'
and a.hod_status='1' and a.acad_status='1'";


        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
        }
        
        function grade_sheet_details_jrf($admn_no,$sy,$sess) {
    
        $sql = "select B.*,e.dept_id,e.aggr_id,e.course_id,e.branch_id,e.semester,e.`group` from 
(select A.*,c.name,c.subject_id as sid,c.credit_hours,c.`type` from 
(
select a.total,a.grade,a.stu_status,a.sub_type,b.subject_id,b.`session`,b.session_year,b.sub_map_id from marks_subject_description as a
inner join marks_master as b on a.marks_master_id=b.id  where a.admn_no='".$admn_no."' and b.`status`='Y' and b.`type`='J') A
inner join subjects as c on A.subject_id=c.id 
) B
inner join subject_mapping as e on B.sub_map_id = e.map_id 
where B.session_year='".$sy."' and B.`session`='".$sess."'
group by B.sid order by e.semester
";



        $query = $this->db->query($sql);
	
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function grade_sheet_details_jrf_other($admn_no,$sy,$sess) {
    
        $sql = "select B.*,e.dept_id,e.aggr_id,e.course_id,e.branch_id,e.semester,e.`group` from 
(select A.*,c.name,c.subject_id as sid,c.credit_hours,c.`type` from 
(
select a.total,a.grade,a.stu_status,a.sub_type,b.subject_id,b.`session`,b.session_year,b.sub_map_id from marks_subject_description as a
inner join marks_master as b on a.marks_master_id=b.id  where a.admn_no='".$admn_no."' and b.`status`='Y' and b.`type`='JS') A
inner join subjects as c on A.subject_id=c.id 
) B
inner join subject_mapping as e on B.sub_map_id = e.map_id 
where B.session_year='".$sy."' and B.`session`='".$sess."'
group by B.sid order by e.semester
";



        $query = $this->db->query($sql);
	
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
   function get_previous_sub_marks($admn_no,$sem,$etype,$sy )
   {
      /* $sql = " select * from tabulation1 as a where a.adm_no='".$admn_no."' and right(a.sem_code,1)='".$sem."' and a.examtype=((select max(a.examtype) as m from tabulation1 as a
 where a.adm_no='".$admn_no."' and right(a.sem_code,1)='".$sem."'  ))";*/
       
       //$sql="select * from tabulation1 as a where a.adm_no=? and right(a.sem_code,1)=? and examtype=?";
	   $sql="select * from tabulation1 as a where a.adm_no=? and right(a.sem_code,1)=? and examtype=? and ysession=?
and a.wsms=(select a.wsms from tabulation1 a 
where a.adm_no=? and right(a.sem_code,1)=? and examtype=?
order by a.wsms desc limit 1) group by a.subje_order";



        $query = $this->db->query($sql,array($admn_no,$sem,$etype,$sy,$admn_no,$sem,$etype));
	//echo $this->db->last_query();die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
   }
    
   function get_current_semester($admn_no)
   {
       $sql = "select semester from stu_academic where admn_no='".$admn_no."' ";



        $query = $this->db->query($sql);
	
        if ($this->db->affected_rows() >= 0) {
            return $query->row()->semester;
        } else {
            return false;
        }
   }
    
    
    //--------------------------------Logic for special exam----------------
   
   function stu_details_regular_status_spl($admn_no,$sem) 
    {
        
       // $sql = " select * from reg_regular_form where admn_no='".$admn_no."' and semester=".$sem." and hod_status='1' and acad_status='1'";
      $sql = "select * from reg_regular_form where admn_no='".$admn_no."' and semester like '%".$sem."%' and hod_status='1' and acad_status='1'";  
        
        $query = $this->db->query($sql);
      
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function stu_details_other_status_spl($admn_no,$sem) 
    {
      //  $sql = " select * from reg_exam_rc_form where admn_no='".$admn_no."' and semester=".$sem." and hod_status='1' and acad_status='1'";
     $sql = "select * from reg_exam_rc_form where admn_no='".$admn_no."' and semester like '%".$sem."%' and hod_status='1' and acad_status='1'";  
        $query = $this->db->query($sql);
       // echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
   
   
   
   //-------------------------------------------Ends-------------------------------
    

  
      function stu_details_old($adm_no,$sem) {
        $sql = " select ssd.section as st_section ,a.*,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as stu_name,b.dept_id,b.photopath,
c.course_id,c.branch_id,d.name as dept_nm,e.name as course_nm,f.name as branch_nm from reg_regular_form a
inner join user_details b on a.admn_no=b.id
inner join stu_academic c on c.admn_no=b.id
inner join departments d on d.id=b.dept_id
inner join cs_courses e on e.id=c.course_id
inner join cs_branches f on f.id=c.branch_id
left join stu_section_data ssd on ssd.admn_no = a.admn_no and ssd.session_year= a.`session_year`
where a.admn_no=? 
and a.hod_status='1' and a.acad_status='1'";
     
        //and a.semester=".$sem."
//order by a.form_id desc limit 1
//
        
        $query = $this->db->query($sql,array($adm_no));
		
		// echo $this->db->last_query(); die();
		
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
          function stu_details_old_others($adm_no) {
        $sql = " select ssd.section as st_section ,a.*,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as stu_name,b.dept_id,b.photopath,
c.course_id,c.branch_id,d.name as dept_nm,e.name as course_nm,f.name as branch_nm from reg_exam_rc_form a
inner join user_details b on a.admn_no=b.id
inner join stu_academic c on c.admn_no=b.id
inner join departments d on d.id=b.dept_id
inner join cs_courses e on e.id=c.course_id
inner join cs_branches f on f.id=c.branch_id
left join stu_section_data ssd on ssd.admn_no = a.admn_no and ssd.session_year= a.`session_year`
where a.admn_no=? 
and a.hod_status='1' and a.acad_status='1'";
     
        //and a.semester=".$sem."
//order by a.form_id desc limit 1
//
        
        $query = $this->db->query($sql,array($adm_no));
		
		// echo $this->db->last_query(); die();
		
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    function stu_details_left($adm_no) {
        $sql = " select concat_ws(' ',a.first_name,a.middle_name,a.last_name) as stu_name,a.id as admn_no,a.dept_id,a.photopath,b.course_id,
b.branch_id,c.name as course_nm,d.name as branch_nm
from user_details a 
inner join stu_academic b on a.id= b.admn_no
inner join cs_courses c on c.id=b.course_id
inner join cs_branches d on d.id=b.branch_id
where a.id=?
";
     
        
        $query = $this->db->query($sql,array($adm_no));
		
		// echo $this->db->last_query(); die();
		
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
     function get_gpa_others_previous($admn_no,$cid)
    {
      $myquery = "select * from final_semwise_marks_foil where admn_no=? AND course=? order by id desc limit 1 ";
      
        $query = $this->db->query($myquery,array($admn_no,$cid));
       //echo $this->db->last_query(); die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    function check_prepratory_yesno($admn_no,$sy,$sess)
    {
      $myquery = "select a.* from reg_regular_form a
where a.admn_no=? and a.session_year=? and a.`session`=? and a.course_aggr_id like '%prep%'
and a.hod_status='1' and a.acad_status='1'";
      
        $query = $this->db->query($myquery,array($admn_no,$sy,$sess));
       //echo $this->db->last_query(); die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    function get_gpa_cgpa_examwise($admn_no,$sem,$syear,$sess,$et)
    {
      $myquery = "select z.* from(

(
SELECT B.*
FROM (
SELECT a.session_yr,a.session,
LEFT(a.status,1) AS passfail,LEFT(a.core_status,1) AS core_passfail,  a.exam_type, NULL AS sem_code, a.semester AS sem, a.core_tot_cr_hr,a.core_tot_cr_pts,a.core_ctotcrhr,a.core_ctotcrpts,
/*a.core_gpa,a.core_cgpa,a.gpa AS hgpa,a.cgpa AS hcgpa*/
format(a.core_cgpa,5) as core_cgpa,  format(a.cgpa,5) AS hcgpa, format(a.core_gpa,5) as core_gpa,  format(a.gpa,5) AS hgpa 
FROM final_semwise_marks_foil a
WHERE a.admn_no=?  and  a.course<>'MINOR'
GROUP BY a.session_yr,a.session,a.semester,a.exam_type
/*ORDER BY a.session_yr,a.semester DESC, a.tot_cr_pts DESC, a.exam_type DESC)B*/
ORDER BY a.session_yr desc  ,  a.semester DESC,   a.tot_cr_pts desc)B
) 
UNION (
SELECT A.*
FROM (
SELECT a.ysession as session_yr,a.wsms as session,'N/A' AS passfail,
LEFT(a.passfail,1) AS core_passfail, a.examtype AS exam_type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem, 
/*a.totcrhr,a.totcrpts,a.gpa as core_gpa,a.ctotcrhr,a.ctotcrpts,a.ogpa as core_cgpa ,'N/A' AS hgpa,'N/A' AS hcgpa*/
a.totcrhr as core_tot_cr_hr,a.totcrpts as  core_tot_cr_pts, a.ctotcrhr as core_ctotcrhr,a.ctotcrpts as core_ctotcrpts ,a.ogpa AS core_cgpa,'N/A' AS hcgpa,a.gpa AS core_gpa,'N/A' AS hgpa
FROM tabulation1 a
WHERE a.adm_no=? and a.sem_code not like 'PREP%'
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY a.ysession desc,sem DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A
)
)z 
where z.sem=?
and z.session_yr=? and z.session=? and z.exam_type=?";
      
        $query = $this->db->query($myquery,array($admn_no,$admn_no,$sem,$syear,$sess,$et));
       //echo $this->db->last_query(); die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    function get_all_gpa_cgpa($admn_no)
    {
   /*   $myquery = "select z.* from(

(
SELECT B.*
FROM (
SELECT LEFT(a.status,1) AS passfail, a.exam_type, NULL AS sem_code, a.semester AS sem,
a.core_tot_cr_hr,a.core_tot_cr_pts,a.core_gpa,a.core_ctotcrhr,a.core_ctotcrpts,a.core_cgpa,a.gpa as hgpa,a.cgpa as hcgpa
FROM final_semwise_marks_foil a
WHERE a.admn_no=? and a.course<>'MINOR'
GROUP BY a.session_yr,a.semester,a.exam_type
ORDER BY a.session_yr,a.semester DESC, a.tot_cr_pts DESC, a.exam_type DESC)B
GROUP BY B.sem) 
UNION (
SELECT A.*
FROM (
SELECT LEFT(a.passfail,1), a.examtype AS exam_type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,
a.totcrhr,a.totcrpts,a.gpa,a.ctotcrhr,a.ctotcrpts,a.ogpa,'N/A' as hgpa,'N/A' as hcgpa
FROM tabulation1 a
WHERE a.adm_no=? and a.sem_code not like 'PREP%'
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY a.ysession desc,sem DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A
GROUP BY A.sem_code)
)z  group by z.sem";*/
       /* $myquery ="select z.* from(

(
SELECT B.*
FROM (
SELECT 
LEFT(a.status,1) AS passfail, a.exam_type, NULL AS sem_code, a.semester AS sem, a.core_tot_cr_hr,a.core_tot_cr_pts,a.core_gpa,a.core_ctotcrhr,a.core_ctotcrpts,a.core_cgpa,a.gpa AS hgpa,a.cgpa AS hcgpa
FROM final_semwise_marks_foil a
WHERE a.admn_no=?  and  (a.course<>'MINOR' && a.course<>'PREP' ) 
GROUP BY a.session_yr,a.session,a.semester,a.exam_type
ORDER BY a.session_yr desc  ,  a.semester DESC,   a.tot_cr_pts desc)B
GROUP BY B.sem) 
UNION (
SELECT A.*
FROM (
SELECT
LEFT(a.passfail,1), a.examtype AS exam_type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem, a.totcrhr,a.totcrpts,a.gpa,a.ctotcrhr,a.ctotcrpts,a.ogpa,'N/A' AS hgpa,'N/A' AS hcgpa
FROM tabulation1 a
WHERE a.adm_no=? and a.sem_code not like 'PREP%'
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY a.ysession desc,sem DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A
GROUP BY A.sem_code)
)z group by z.sem ";*/
      $myquery="SELECT z.*
FROM(

(
SELECT B.*
FROM (
SELECT  a.actual_published_on, a.session_yr,a.session,a.type,
LEFT(a.status,1) AS passfail,LEFT(a.core_status,1) AS core_passfail, a.exam_type, NULL AS sem_code, a.semester AS sem, a.core_tot_cr_hr,a.core_tot_cr_pts,a.core_ctotcrhr,a.core_ctotcrpts,
/*a.core_gpa,a.core_cgpa,a.gpa AS hgpa,a.cgpa AS hcgpa*/
format(a.core_cgpa,5) as core_cgpa,  format(a.cgpa,5) AS hcgpa, format(a.core_gpa,5) as core_gpa,  format(a.gpa,5) AS hgpa
FROM final_semwise_marks_foil_freezed a
WHERE a.admn_no=? AND (a.course<>'MINOR' && a.course<>'PREP')
GROUP BY a.session_yr,a.session,a.semester,a.type, a.actual_published_on /*ORDER BY a.session_yr,a.semester DESC, a.tot_cr_pts DESC, a.exam_type DESC)B*/
ORDER BY a.session_yr DESC, a.semester DESC,a.actual_published_on desc, a.tot_cr_pts DESC)B
GROUP BY B.sem) 

union

(
SELECT B.*
FROM (
SELECT null as  actual_published_on, a.session_yr,a.session,a.type,
LEFT(a.status,1) AS passfail,LEFT(a.core_status,1) AS core_passfail, a.exam_type, NULL AS sem_code, a.semester AS sem, a.core_tot_cr_hr,a.core_tot_cr_pts,a.core_ctotcrhr,a.core_ctotcrpts,
/*a.core_gpa,a.core_cgpa,a.gpa AS hgpa,a.cgpa AS hcgpa*/
format(a.core_cgpa,5) as core_cgpa,  format(a.cgpa,5) AS hcgpa, format(a.core_gpa,5) as core_gpa,  format(a.gpa,5) AS hgpa
FROM final_semwise_marks_foil a
WHERE a.admn_no=? AND (a.course<>'MINOR' && a.course<>'PREP')
GROUP BY a.session_yr,a.session,a.semester,a.type /*ORDER BY a.session_yr,a.semester DESC, a.tot_cr_pts DESC, a.exam_type DESC)B*/
ORDER BY a.session_yr DESC, a.semester DESC, a.tot_cr_pts DESC)B
GROUP BY B.sem) 


UNION (
SELECT A.*
FROM (
SELECT null as  actual_published_on, a.ysession as session_yr,a.wsms  as session ,a.examtype as type,
LEFT(a.passfail,1) AS core_passfail,'N/A' AS passfail, a.examtype AS exam_type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,
/* a.totcrhr,a.totcrpts,a.gpa as core_gpa ,a.ctotcrhr,a.ctotcrpts,a.ogpa as core_cgpa,'N/A' AS hgpa,'N/A' AS hcgpa*/
a.totcrhr as core_tot_cr_hr,a.totcrpts as  core_tot_cr_pts, a.ctotcrhr as core_ctotcrhr,a.ctotcrpts as core_ctotcrpts ,a.ogpa AS core_cgpa,'N/A' AS hcgpa,a.gpa AS core_gpa,'N/A' AS hgpa
FROM tabulation1 a
WHERE a.adm_no=? AND a.sem_code NOT LIKE 'PREP%'
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY a.ysession DESC,sem DESC, a.wsms DESC,a.totcrpts DESC,a.examtype DESC)A
GROUP BY A.sem_code))z
GROUP BY z.sem


";
        $query = $this->db->query($myquery,array($admn_no,$admn_no,$admn_no));
      
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    function check_honours_yes_no($admn_no,$sem)
    {
      $myquery = "select * from final_semwise_marks_foil a where a.admn_no=? and a.semester=? and a.course<>'MINOR'";
      
        $query = $this->db->query($myquery,array($admn_no,$sem));
       //echo $this->db->last_query(); die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
	
	function get_hm_status($admn_no)
    {
      $myquery = "select * from hm_form where admn_no=? group by admn_no";
      
        $query = $this->db->query($myquery,array($admn_no));
       //echo $this->db->last_query(); die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
  
  function stu_dept_course($admn_no)
    {
      $myquery = "select a.dept_id,b.course_id from user_details a
                  inner join stu_academic b on a.id=b.admn_no
                  where a.id=?";
      
        $query = $this->db->query($myquery,array($admn_no));
       //echo $this->db->last_query(); die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
  
}

?>