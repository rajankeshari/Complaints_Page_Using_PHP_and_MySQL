<?php
	class Cbcs_stu_details extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}
		
		function get_details_for_transcript($id){
               /* $sql="SELECT t.*,
@running_total:=@running_total + 1 AS exam_attempt
 from
(select a.*,
(CASE a.session
      WHEN 'Monsoon' THEN '1'
      WHEN 'Winter' THEN '2' 
						WHEN 'Summer' THEN '3'   
   END) as order_list
from final_semwise_marks_foil_freezed a where a.admn_no=? and a.course<>'MINOR' 
GROUP BY a.session_yr,order_list,a.semester
ORDER BY a.session_yr,order_list,a.semester)t
JOIN (SELECT @running_total:=0) r";*/

/*$sql="SELECT t2.*, @running_total:=@running_total + 1 AS exam_attempt FROM
(SELECT t1.* from
(SELECT t.*,(CASE t.session WHEN 'Monsoon' THEN '1' WHEN 'Winter' THEN '2' WHEN 'Summer' THEN '3' END) AS order_list from
(SELECT a.* FROM final_semwise_marks_foil_freezed a
WHERE a.admn_no=? AND a.course<>'MINOR'
ORDER BY a.semester DESC LIMIT 100000)t 
GROUP BY t.semester,t.session_yr,order_list
ORDER BY t.semester desc,t.session_yr,order_list)t1 ORDER BY t1.semester ASC LIMIT 100000)t2
JOIN (
SELECT @running_total:=0) r";*/

/*$sql="SELECT t1.*, @running_total:=@running_total + 1 AS exam_attempt
FROM (
SELECT t.*,GROUP_CONCAT(t.id)AS id1,(CASE t.session WHEN 'Monsoon' THEN '1' WHEN 'Winter' THEN '2' WHEN 'Summer' THEN '3' END) AS order_list
FROM (
SELECT a.*
FROM final_semwise_marks_foil_freezed a
WHERE a.admn_no=? AND a.course<>'MINOR'
ORDER BY a.semester DESC,a.admn_no,a.actual_published_on DESC
LIMIT 100000)t
GROUP BY t.session_yr,order_list
ORDER BY t.session_yr,order_list)t1
JOIN (
SELECT @running_total:=0) r";*/

/*
$sql="
SELECT t1.*, @running_total:=@running_total + 1 AS exam_attempt
FROM (
select t.*,  GROUP_CONCAT(t.id) AS id1,(CASE t.session WHEN 'Monsoon' THEN '1' WHEN 'Winter' THEN '2' WHEN 'Summer' THEN '3' END) AS order_list 
from ( 
SELECT t.*
FROM 
(
SELECT a.*
FROM final_semwise_marks_foil_freezed a
WHERE a.admn_no=? AND a.course<>'MINOR'
ORDER BY a.admn_no,a.semester DESC,a.actual_published_on DESC  LIMIT 1000000 )t
GROUP BY t.admn_no, t.session_yr, t.session,t.semester
ORDER BY t.admn_no,t.semester DESC,t.actual_published_on DESC  LIMIT 1000000 
)t
GROUP BY t.session_yr,order_list
ORDER BY t.session_yr,order_list  limit 1000000 )t1
JOIN (
SELECT @running_total:=0) r
";*/

/*$sql="SELECT t1.*, @running_total:=@running_total + 1 AS exam_attempt
FROM (
SELECT t.*,GROUP_CONCAT(distinct(t.id) order by t.id ) AS id1,
group_concat( distinct(t.semester) order by t.semester ) as sem_summer_list ,
GROUP_CONCAT(distinct(t.examtype)   ORDER BY t.examtype) AS examtype_summer_list, 
GROUP_CONCAT( distinct( t.sem_code)  ORDER BY t.sem_code) AS sem_code_summer_list, 
(CASE t.session WHEN 'Monsoon' THEN '1' WHEN 'Winter' THEN '2' WHEN 'Summer' THEN '3' END) AS order_list
FROM (
SELECT t.*
FROM (

SELECT a.session_yr,a.session,a.admn_no,a.id,a.semester,a.actual_published_on , null as wsms, a.type AS examtype, null as sem_code 
FROM final_semwise_marks_foil_freezed a
WHERE a.admn_no=? AND a.course<>'MINOR' and a.published_on is not null

union all
(SELECT  A.session_yr,A.session,A.admn_no,A.id,A.semester,A.actual_published_on ,A.wsms,A.examtype,A.sem_code 
FROM (
SELECT   a.ysession as  session_yr, a.wsms,  a.examtype, (case when  a.wsms='ZS' then 'Summer'  when a.wsms='MS' then 'Monsoon' when a.wsms='WS' then 'Winter' else a.wsms end)   as  session, a.adm_no as  admn_no, a.id, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS semester ,  null as actual_published_on ,a.sem_code
FROM tabulation1 a
WHERE a.adm_no=? and a.sem_code not like 'PREP%'
 GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
    ORDER BY semester, a.ysession , a.wsms ,a.examtype  limit 10000000
)A
)

ORDER BY admn_no,semester DESC, (case when actual_published_on is null then session_yr end) ,
                                (case when actual_published_on is null then wsms else actual_published_on  end)  ,
                                CASE actual_published_on WHEN null THEN wsms END ASC,
                                CASE actual_published_on WHEN not null THEN actual_published_on END desc,
                                (case when actual_published_on is null then examtype  end)




LIMIT 1000000)t
GROUP BY t.admn_no, t.session_yr, t.session,t.semester
ORDER BY t.admn_no,t.semester DESC,t.actual_published_on DESC
LIMIT 1000000)t
GROUP BY t.session_yr,order_list,(case when t.session<>'Summer' then     t.semester end)
ORDER BY t.session_yr,order_list,(case when t.session<>'Summer' then     t.semester end)

LIMIT 1000000)t1
JOIN (
SELECT @running_total:=0) r";*/

$sql="SELECT t1.*, @running_total:=@running_total + 1 AS exam_attempt
FROM (
SELECT t.*, GROUP_CONCAT(DISTINCT(t.id)
ORDER BY t.id) AS id1, GROUP_CONCAT(DISTINCT(t.semester)
ORDER BY t.semester) AS sem_summer_list, GROUP_CONCAT(DISTINCT(t.examtype)
ORDER BY t.examtype) AS examtype_summer_list, GROUP_CONCAT(DISTINCT(t.sem_code)
ORDER BY t.sem_code) AS sem_code_summer_list, (CASE t.session WHEN 'Monsoon' THEN '1' WHEN 'Winter' THEN '2' WHEN 'Summer' THEN '3' END) AS order_list
FROM (
SELECT t.*
FROM (

SELECT a.* FROM(
SELECT a.session_yr,a.session,a.admn_no,a.id,a.semester,a.actual_published_on, NULL AS wsms, a.type AS examtype, NULL AS sem_code
FROM final_semwise_marks_foil_freezed a
WHERE a.admn_no=? AND a.course<>'MINOR' AND a.published_on IS NOT NULL ORDER BY a.admn_no,a.semester DESC,a.session_yr,a.session,a.actual_published_on DESC limit 100000
) a

GROUP BY   a.admn_no,a.semester ,a.session_yr,a.session,a.examtype
UNION ALL (
SELECT A.session_yr,A.session,A.admn_no,A.id,A.semester,A.actual_published_on,A.wsms,A.examtype,A.sem_code
FROM (
SELECT a.ysession AS session_yr, a.wsms, a.examtype, (CASE WHEN a.wsms='ZS' THEN 'Summer' WHEN a.wsms='MS' THEN 'Monsoon' WHEN a.wsms='WS' THEN 'Winter' ELSE a.wsms END) AS SESSION, a.adm_no AS admn_no, a.id, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS semester, NULL AS actual_published_on,a.sem_code
FROM tabulation1 a
WHERE a.adm_no=? AND a.sem_code NOT LIKE 'PREP%'
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY semester, a.ysession, a.wsms,a.examtype
LIMIT 10000000)A)
ORDER BY admn_no,semester DESC, 
         (CASE WHEN actual_published_on IS NULL THEN session_yr END),
		   (CASE WHEN actual_published_on IS NULL THEN wsms ELSE actual_published_on END), 
   	    CASE actual_published_on WHEN NULL THEN wsms END ASC, 
          CASE actual_published_on WHEN NOT NULL THEN actual_published_on END DESC, 
	      (CASE WHEN actual_published_on IS NULL THEN examtype END)
		  
LIMIT 1000000)t
GROUP BY t.admn_no, t.session_yr, t.session,t.semester
ORDER BY t.admn_no,t.semester DESC,t.actual_published_on DESC
LIMIT 1000000)t
GROUP BY t.session_yr,order_list,(CASE WHEN t.session<>'Summer' THEN t.semester END)
ORDER BY t.session_yr,order_list,(CASE WHEN t.session<>'Summer' THEN t.semester END)
LIMIT 1000000)t1
JOIN (
SELECT @running_total:=0) r";

                
                    $query = $this->db->query($sql,array($id,$id));
		//echo $this->db->last_query(); die();	
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->result();
                    }
                    else
                    {
                        return false;
                    }
            }	

            function get_final_foil_data($admn_no,$exam_attempt){

            	/*$sql="SELECT p.* from
(SELECT t2.*, @running_total:=@running_total + 1 AS exam_attempt FROM
(SELECT t1.* from
(SELECT t.*,(CASE t.session WHEN 'Monsoon' THEN '1' WHEN 'Winter' THEN '2' WHEN 'Summer' THEN '3' END) AS order_list from
(SELECT a.* FROM final_semwise_marks_foil_freezed a
WHERE a.admn_no=? AND a.course<>'MINOR'
ORDER BY a.semester DESC LIMIT 100000)t 
GROUP BY t.semester,t.session_yr,order_list
ORDER BY t.semester desc,t.session_yr,order_list)t1 ORDER BY t1.semester ASC LIMIT 100000)t2
JOIN (
SELECT @running_total:=0) r
)p
WHERE p.exam_attempt=?";*/
/*$sql=" SELECT p.* from(SELECT t1.*, @running_total:=@running_total + 1 AS exam_attempt
FROM (
SELECT t.*,GROUP_CONCAT(t.id)AS id1,(CASE t.session WHEN 'Monsoon' THEN '1' WHEN 'Winter' THEN '2' WHEN 'Summer' THEN '3' END) AS order_list
FROM (
SELECT a.*
FROM final_semwise_marks_foil_freezed a
WHERE a.admn_no=? AND a.course<>'MINOR'
ORDER BY a.semester DESC,a.admn_no,a.actual_published_on DESC
LIMIT 100000)t
GROUP BY t.session_yr,order_list
ORDER BY t.session_yr,order_list)t1
JOIN (
SELECT @running_total:=0) r)p
WHERE p.exam_attempt=?
";*/


/*$sql=" SELECT p.* from(SELECT t1.*, @running_total:=@running_total + 1 AS exam_attempt
FROM (
select t.*,  GROUP_CONCAT(t.id) AS id1,(CASE t.session WHEN 'Monsoon' THEN '1' WHEN 'Winter' THEN '2' WHEN 'Summer' THEN '3' END) AS order_list 
from ( 
SELECT t.*
FROM 
(
SELECT a.*
FROM final_semwise_marks_foil_freezed a
WHERE a.admn_no=? AND a.course<>'MINOR'
ORDER BY a.admn_no,a.semester DESC,a.actual_published_on DESC  LIMIT 1000000 )t
GROUP BY t.admn_no, t.session_yr, t.session,t.semester
ORDER BY t.admn_no,t.semester DESC,t.actual_published_on DESC  LIMIT 1000000 
)t
GROUP BY t.session_yr,order_list
ORDER BY t.session_yr,order_list  limit 1000000 )t1
JOIN (
SELECT @running_total:=0) r)p
WHERE p.exam_attempt=? ";
*/

$sql=" SELECT p.* from(SELECT t1.*, @running_total:=@running_total + 1 AS exam_attempt
FROM (
SELECT t.*, GROUP_CONCAT(DISTINCT(t.id)
ORDER BY t.id) AS id1, GROUP_CONCAT(DISTINCT(t.semester)
ORDER BY t.semester) AS sem_summer_list, GROUP_CONCAT(DISTINCT(t.examtype)
ORDER BY t.examtype) AS examtype_summer_list, GROUP_CONCAT(DISTINCT(t.sem_code)
ORDER BY t.sem_code) AS sem_code_summer_list, (CASE t.session WHEN 'Monsoon' THEN '1' WHEN 'Winter' THEN '2' WHEN 'Summer' THEN '3' END) AS order_list
FROM (
SELECT t.*
FROM (

SELECT a.* FROM(
SELECT a.session_yr,a.session,a.admn_no,a.id,a.semester,a.actual_published_on, NULL AS wsms, a.type AS examtype, NULL AS sem_code
FROM final_semwise_marks_foil_freezed a
WHERE a.admn_no=? AND a.course<>'MINOR' AND a.published_on IS NOT NULL ORDER BY a.admn_no,a.semester DESC,a.session_yr,a.session,a.actual_published_on DESC limit 100000
) a

GROUP BY   a.admn_no,a.semester ,a.session_yr,a.session,a.examtype
UNION ALL (
SELECT A.session_yr,A.session,A.admn_no,A.id,A.semester,A.actual_published_on,A.wsms,A.examtype,A.sem_code
FROM (
SELECT a.ysession AS session_yr, a.wsms, a.examtype, (CASE WHEN a.wsms='ZS' THEN 'Summer' WHEN a.wsms='MS' THEN 'Monsoon' WHEN a.wsms='WS' THEN 'Winter' ELSE a.wsms END) AS SESSION, a.adm_no AS admn_no, a.id, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS semester, NULL AS actual_published_on,a.sem_code
FROM tabulation1 a
WHERE a.adm_no=? AND a.sem_code NOT LIKE 'PREP%'
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY semester, a.ysession, a.wsms,a.examtype
LIMIT 10000000)A)
ORDER BY admn_no,semester DESC, 
         (CASE WHEN actual_published_on IS NULL THEN session_yr END),
		   (CASE WHEN actual_published_on IS NULL THEN wsms ELSE actual_published_on END), 
   	    CASE actual_published_on WHEN NULL THEN wsms END ASC, 
          CASE actual_published_on WHEN NOT NULL THEN actual_published_on END DESC, 
	      (CASE WHEN actual_published_on IS NULL THEN examtype END)
		  
LIMIT 1000000)t
GROUP BY t.admn_no, t.session_yr, t.session,t.semester
ORDER BY t.admn_no,t.semester DESC,t.actual_published_on DESC
LIMIT 1000000)t
GROUP BY t.session_yr,order_list,(CASE WHEN t.session<>'Summer' THEN t.semester END)
ORDER BY t.session_yr,order_list,(CASE WHEN t.session<>'Summer' THEN t.semester END)
LIMIT 1000000)t1
JOIN (
SELECT @running_total:=0) r)p
WHERE p.exam_attempt=? ";

                
                    $query = $this->db->query($sql,array($admn_no,$admn_no,$exam_attempt));
			//echo $this->db->last_query();	
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }


            }
            function get_final_foil_desc_data($admn_no,$foil_id){
            	
                $sql="SELECT t.session_yr,t.session, (CASE t.type WHEN 'R' THEN 'Regular' WHEN 'O' THEN 'Carry' WHEN 'S' THEN 'Special' end) as exam_type, 
t.subname,t.cr_hr,t.cr_pts,t.grade,t.sub_code,t.sub_type,t.status,
t.tot_cr_hr,t.tot_cr_pts,t.gpa,t.cgpa,t.ctotcrpts,t.ctotcrhr
from
(
SELECT v.*, 
IFNULL(cso.id,oso.id) AS sub_offered_id1, 
IF(v.sub_code=oso.sub_code, CONCAT('o',oso.id), IF(v.sub_code=cso.sub_code, CONCAT('c',cso.id),'')) AS sub_offered_id2, 
IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_code ELSE cso.sub_code END) IS NULL, s.subject_id,
 (CASE WHEN cso.sub_code IS NULL THEN oso.sub_code ELSE cso.sub_code END)) AS subcode, 
	IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_name ELSE cso.sub_name END) IS NULL, s.name, 
	(CASE WHEN cso.sub_code IS NULL THEN oso.sub_name ELSE cso.sub_name END)) AS subname, 
	IF((CASE WHEN cso.sub_code IS NULL THEN oso.lecture ELSE cso.lecture END) IS NULL, s.lecture, (CASE WHEN cso.sub_code IS NULL THEN oso.lecture ELSE cso.lecture END)) AS lecture, IF((CASE WHEN cso.sub_code IS NULL THEN oso.practical ELSE cso.practical END) IS NULL, s.practical, (CASE WHEN cso.sub_code IS NULL THEN oso.practical ELSE cso.practical END)) AS practical, IF((CASE WHEN cso.sub_code IS NULL THEN oso.tutorial ELSE cso.tutorial END) IS NULL, s.tutorial, (CASE WHEN cso.sub_code IS NULL THEN oso.tutorial ELSE cso.tutorial END)) AS tutorial, IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_type ELSE cso.sub_type END) IS NULL, s.`type`, (CASE WHEN cso.sub_code IS NULL THEN oso.sub_type ELSE cso.sub_type END)) AS sub_type, IF((CASE WHEN cso.sub_code IS NULL THEN oso.credit_hours ELSE cso.credit_hours END) IS NULL, s.credit_hours, (CASE WHEN cso.sub_code IS NULL THEN oso.credit_hours ELSE cso.credit_hours END)) AS credit_hours, IF((CASE WHEN cso.sub_code IS NULL THEN oso.contact_hours ELSE cso.contact_hours END) IS NULL, s.contact_hours, (CASE WHEN cso.sub_code IS NULL THEN oso.contact_hours ELSE cso.contact_hours END)) AS contact_hours
FROM (
SELECT y.session_yr,y.session,y.dept,y.course,y.branch,y.semester, fd.mis_sub_id, fd.sub_code, fd.grade,y.admn_no,y.exam_type,y.type,
fd.cr_hr,fd.cr_pts,y.status,y.tot_cr_hr,y.tot_cr_pts,y.gpa,y.cgpa,y.ctotcrpts,y.ctotcrhr
FROM (
SELECT x.*
FROM (
SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id,a.`status`,a.exam_type,	a.type,
a.tot_cr_hr,a.tot_cr_pts,a.gpa,a.cgpa,a.ctotcrpts,a.ctotcrhr
FROM final_semwise_marks_foil_freezed AS a
WHERE a.admn_no=? AND a.id=?
ORDER BY a.semester,a.admn_no,a.actual_published_on DESC
LIMIT 10000)x
GROUP BY x.semester) y
JOIN final_semwise_marks_foil_desc_freezed fd ON fd.foil_id=y.id AND fd.admn_no=y.admn_no /*AND fd.grade='F'*/
GROUP BY fd.sub_code
ORDER BY y.session_yr,y.dept,y.course,y.branch,y.semester,fd.sub_code)v
LEFT JOIN cbcs_stu_course csc ON csc.admn_no= v.admn_no AND csc.session_year=v.session_yr AND csc.`session`=v.session AND csc.subject_code=v.sub_code
LEFT JOIN cbcs_subject_offered cso ON csc.sub_offered_id=cso.id
LEFT JOIN old_stu_course osc ON osc.admn_no= v.admn_no AND osc.session_year=v.session_yr AND osc.`session`=v.session AND osc.subject_code=v.sub_code
LEFT JOIN old_subject_offered oso ON osc.sub_offered_id=oso.id
LEFT JOIN subjects s ON (CASE WHEN v.mis_sub_id IS NOT NULL THEN s.id WHEN v.mis_sub_id IS NULL AND (cso.sub_code IS NULL AND oso.sub_code IS NULL) THEN s.subject_id END)= 
(CASE WHEN v.mis_sub_id IS NOT NULL THEN v.mis_sub_id WHEN v.mis_sub_id IS NULL AND (cso.sub_code IS NULL AND oso.sub_code IS NULL) THEN v.sub_code END)
)t
ORDER BY t.sub_code

/*LEFT JOIN cbcs_subject_offered cso ON cso.sub_code=v.sub_code AND v.course=cso.course_id AND (case when v.course<>'comm' then   v.branch=cso.branch_id  else 1=1  end ) AND v.session_yr=cso.session_year AND v.session=cso.session
LEFT JOIN old_subject_offered oso ON oso.sub_code=v.sub_code AND v.course=oso.course_id AND v.branch=oso.branch_id AND v.session_yr=oso.session_year AND v.session=oso.session
LEFT JOIN subjects s ON (CASE WHEN v.mis_sub_id IS NOT NULL THEN s.id WHEN v.mis_sub_id IS NULL AND (cso.sub_code IS NULL AND oso.sub_code IS NULL) THEN s.subject_id END)= (CASE WHEN v.mis_sub_id IS NOT NULL THEN v.mis_sub_id WHEN v.mis_sub_id IS NULL AND (cso.sub_code IS NULL AND oso.sub_code IS NULL) THEN v.sub_code END)
)t
ORDER BY t.sub_code*/

";
                $query = $this->db->query($sql,array($admn_no,$foil_id));
			//echo $this->db->last_query();	die();
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->result();
                    }
                    else
                    {
                        return false;
                    }
            }

function get_student_details($id){
               /* $sql="SELECT a.id AS admn_no,CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name)AS stu_name,
c.name AS cname,d.name AS bname,b.course_id,b.branch_id
 FROM user_details a
INNER JOIN stu_academic b ON b.admn_no=a.id
INNER JOIN cbcs_courses c ON c.id=b.course_id
INNER JOIN cbcs_branches d ON d.id=b.branch_id
WHERE a.id=?";*/

$sql="select  c.name AS cname,e.name AS bname,d.* from 
(SELECT a.id AS admn_no,CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name)AS stu_name,
(case when x.admn_no is not null then x.new_course_id else  b.course_id  end ) as course_id, 
(case when x.admn_no is not null then x.new_branch_id else  b.branch_id  end ) as branch_id
 FROM user_details a
INNER JOIN stu_academic b ON b.admn_no=a.id
left join cbcs_transcript x  on  x.old_course_id=b.course_id and  x.admn_no=a.id
)d
INNER JOIN cbcs_courses c ON c.id=d.course_id
INNER JOIN cbcs_branches e ON e.id=d.branch_id
WHERE d.admn_no=?";
                
                    $query = $this->db->query($sql,array($id));
			//echo $this->db->last_query();	
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
            }	
			
			function get_final_foil_desc_data_summer($admn_no,$foil_id){
				
				//$foil_id = "'" . implode("','", explode(',', $foil_id)) . "'";
            	//echo $foil_id;die();
                $sql="SELECT t.session_yr,t.session,
(CASE t.type WHEN 'R' THEN 'Regular' WHEN 'O' THEN 'Carry' WHEN 'S' THEN 'Special' end) as exam_type,
t.subname,t.cr_hr,t.cr_pts,t.grade,t.sub_code,t.sub_type,t.status,
t.tot_cr_hr,t.tot_cr_pts,t.gpa,t.cgpa,t.ctotcrpts,t.ctotcrhr
from
(
SELECT v.*, 
IFNULL(cso.id,oso.id) AS sub_offered_id1, 
IF(v.sub_code=oso.sub_code, CONCAT('o',oso.id), IF(v.sub_code=cso.sub_code, CONCAT('c',cso.id),'')) AS sub_offered_id2, 
IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_code ELSE cso.sub_code END) IS NULL, s.subject_id,
 (CASE WHEN cso.sub_code IS NULL THEN oso.sub_code ELSE cso.sub_code END)) AS subcode, 
	IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_name ELSE cso.sub_name END) IS NULL, s.name, 
	(CASE WHEN cso.sub_code IS NULL THEN oso.sub_name ELSE cso.sub_name END)) AS subname, 
	IF((CASE WHEN cso.sub_code IS NULL THEN oso.lecture ELSE cso.lecture END) IS NULL, s.lecture, (CASE WHEN cso.sub_code IS NULL THEN oso.lecture ELSE cso.lecture END)) AS lecture, IF((CASE WHEN cso.sub_code IS NULL THEN oso.practical ELSE cso.practical END) IS NULL, s.practical, (CASE WHEN cso.sub_code IS NULL THEN oso.practical ELSE cso.practical END)) AS practical, IF((CASE WHEN cso.sub_code IS NULL THEN oso.tutorial ELSE cso.tutorial END) IS NULL, s.tutorial, (CASE WHEN cso.sub_code IS NULL THEN oso.tutorial ELSE cso.tutorial END)) AS tutorial, IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_type ELSE cso.sub_type END) IS NULL, s.`type`, (CASE WHEN cso.sub_code IS NULL THEN oso.sub_type ELSE cso.sub_type END)) AS sub_type, IF((CASE WHEN cso.sub_code IS NULL THEN oso.credit_hours ELSE cso.credit_hours END) IS NULL, s.credit_hours, (CASE WHEN cso.sub_code IS NULL THEN oso.credit_hours ELSE cso.credit_hours END)) AS credit_hours, IF((CASE WHEN cso.sub_code IS NULL THEN oso.contact_hours ELSE cso.contact_hours END) IS NULL, s.contact_hours, (CASE WHEN cso.sub_code IS NULL THEN oso.contact_hours ELSE cso.contact_hours END)) AS contact_hours
FROM (
SELECT y.session_yr,y.session,y.dept,y.course,y.branch,y.semester, fd.mis_sub_id, fd.sub_code, fd.grade,y.admn_no,y.exam_type,y.type,
fd.cr_hr,fd.cr_pts,y.status,y.tot_cr_hr,y.tot_cr_pts,y.gpa,y.cgpa,y.ctotcrpts,y.ctotcrhr
FROM (
SELECT x.*
FROM (
SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id,a.`status`,a.exam_type,a.type,
a.tot_cr_hr,a.tot_cr_pts,a.gpa,a.cgpa,a.ctotcrpts,a.ctotcrhr
FROM final_semwise_marks_foil_freezed AS a
WHERE a.admn_no='".$admn_no."' AND a.id in(". $foil_id ." ) 
ORDER BY a.semester,a.admn_no,a.actual_published_on DESC
LIMIT 10000)x
GROUP BY x.semester) y
JOIN final_semwise_marks_foil_desc_freezed fd ON fd.foil_id=y.id AND fd.admn_no=y.admn_no AND fd.current_exam='Y'
GROUP BY fd.sub_code
ORDER BY y.session_yr,y.dept,y.course,y.branch,y.semester,fd.sub_code)v
LEFT JOIN cbcs_subject_offered cso ON cso.sub_code=v.sub_code AND v.course=cso.course_id AND (case when v.course<>'comm' then   v.branch=cso.branch_id  else 1=1  end ) AND v.session_yr=cso.session_year AND v.session=cso.session
LEFT JOIN old_subject_offered oso ON oso.sub_code=v.sub_code AND v.course=oso.course_id AND v.branch=oso.branch_id AND v.session_yr=oso.session_year AND v.session=oso.session
LEFT JOIN subjects s ON (CASE WHEN v.mis_sub_id IS NOT NULL THEN s.id WHEN v.mis_sub_id IS NULL AND (cso.sub_code IS NULL AND oso.sub_code IS NULL) THEN s.subject_id END)= (CASE WHEN v.mis_sub_id IS NOT NULL THEN v.mis_sub_id WHEN v.mis_sub_id IS NULL AND (cso.sub_code IS NULL AND oso.sub_code IS NULL) THEN v.sub_code END)
)t
ORDER BY t.sub_code
";
                $query = $this->db->query($sql);
			//echo $this->db->last_query();	die();
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->result();
                    }
                    else
                    {
                        return false;
                    }
            }
          



	function get_final_foil_desc_data_summer_old($admn_no,$wsms,$examtype,$sem_code,$session_yr,$summer_sem_list,$examtype_summer_list,$sem_code_summer_list){
      
	 if(  strstr($summer_sem_list,',')===FALSE ){ 

$sql="SELECT  (case 
                    when  a.ysession='1415' then '2014-2015'  
					when  a.ysession='1314' then '2013-2014'  
					when  a.ysession='1213' then '2012-2013'  
					when  a.ysession='1112' then '2011-2012'  
					
			   end ) as session_yr,
                  (CASE WHEN a.wsms='ZS' THEN 'Summer' WHEN a.wsms='MS' THEN 'Monsoon' WHEN a.wsms='WS' THEN 'Winter' ELSE a.wsms END) AS  session ,CAST(REVERSE(a.sem_code) AS UNSIGNED) AS semester , 
				  (case  
					    when   a.examtype='R' then 'Regular'
						when   a.examtype='S' then 'Special'
						when   a.examtype='T' then 'Carry1'
						when   a.examtype='U' then 'Carry1 Special'
						when   a.examtype='V' then 'Carry2'
						when   a.examtype='W' then 'Carry2 Special'
					    when   a.examtype='X' then 'Carry3'
					    when   a.examtype='Y' then 'Carry2 Special'                    
						when   a.examtype='E' then 'Early Special'
                    end)  AS exam_type,
   a.totcrhr as  tot_cr_hr  ,a.totcrpts as tot_cr_pts, a.ctotcrpts,a.ctotcrhr,a.gpa,a.ogpa as cgpa,(case when  a.passfail='F' then 'FAIL'  when  a.passfail='P' then 'PASS' end) as status,a.crdhrs as  cr_hr,a.crpts as cr_pts, a.subje_name as  subname ,a.subje_code as sub_code,a.grade, a.subje_type as sub_type
   
FROM tabulation1 a WHERE a.adm_no=?  and   a.wsms=? and  a.examtype=? and  a.sem_code=? and  a.ysession=?  ";

                $query = $this->db->query($sql,array($admn_no,trim($wsms),trim($examtype),trim($sem_code),trim($session_yr)));
				
	 }
else{
	
	
$sql="SELECT  (case 
                    when  a.ysession='1415' then '2014-2015'  
					when  a.ysession='1314' then '2013-2014'  
					when  a.ysession='1213' then '2012-2013'  
					when  a.ysession='1112' then '2011-2012'  
					
			   end ) as session_yr,
			   (CASE WHEN a.wsms='ZS' THEN 'Summer' WHEN a.wsms='MS' THEN 'Monsoon' WHEN a.wsms='WS' THEN 'Winter' ELSE a.wsms END) AS session ,CAST(REVERSE(a.sem_code) AS UNSIGNED) AS semester ,
			   (case  
					    when   a.examtype='R' then 'Regular'
						when   a.examtype='S' then 'Special'
						when   a.examtype='T' then 'Carry1'
						when   a.examtype='U' then 'Carry1 Special'
						when   a.examtype='V' then 'Carry2'
						when   a.examtype='W' then 'Carry2 Special'
					    when   a.examtype='X' then 'Carry3'
					    when   a.examtype='Y' then 'Carry2 Special'                    
						when   a.examtype='E' then 'Early Special'
                    end)  AS exam_type,
   a.totcrhr as  tot_cr_hr  ,a.totcrpts as tot_cr_pts, a.ctotcrpts,a.ctotcrhr,a.gpa,a.ogpa as cgpa,(case when  a.passfail='F' then 'FAIL'  when  a.passfail='P' then 'PASS' end) as status,a.crdhrs as  cr_hr,a.crpts as cr_pts, a.subje_name as  subname ,a.subje_code as sub_code,a.grade, a.subje_type as sub_type
   
FROM tabulation1 a WHERE a.adm_no=?  and   a.wsms in('MS','WS') and  a.examtype in(".$examtype_summer_list.") and  a.sem_code=(".$sem_code_summer_list.") and  a.ysession=?  ";

                $query = $this->db->query($sql,array($admn_no,trim($session_yr)));
	
}
	 
                
			//echo $this->db->last_query();	die();
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->result();
                    }
                    else
                    {
                        return false;
                    }
            }




		  
		//===============================================================================================
			function get_final_foil_desc_data_old($admn_no,$wsms,$examtype,$sem_code,$session_yr){
      
	 

$sql="SELECT  (case 
                    when  a.ysession='1415' then '2014-2015'  
					when  a.ysession='1314' then '2013-2014'  
					when  a.ysession='1213' then '2012-2013'  
					when  a.ysession='1112' then '2011-2012'  
					
			   end ) as session_yr,
(CASE WHEN a.wsms='ZS' THEN 'Summer' WHEN a.wsms='MS' THEN 'Monsoon' WHEN a.wsms='WS' THEN 'Winter' ELSE a.wsms END)  as session ,CAST(REVERSE(a.sem_code) AS UNSIGNED) AS semester ,  
                    (case  
					    when   a.examtype='R' then 'Regular'
						when   a.examtype='S' then 'Special'
						when   a.examtype='T' then 'Carry1'
						when   a.examtype='U' then 'Carry1 Special'
						when   a.examtype='V' then 'Carry2'
						when   a.examtype='W' then 'Carry2 Special'
					    when   a.examtype='X' then 'Carry3'
					    when   a.examtype='Y' then 'Carry2 Special'                    
						when   a.examtype='E' then 'Early Special'
                    end)  AS exam_type,


   a.totcrhr as  tot_cr_hr  ,a.totcrpts as tot_cr_pts, a.ctotcrpts,a.ctotcrhr,a.gpa,a.ogpa as cgpa,(case when  a.passfail='F' then 'FAIL'  when  a.passfail='P' then 'PASS' end) as status,a.crdhrs as  cr_hr,a.crpts as cr_pts, a.subje_name as  subname ,a.subje_code as sub_code,a.grade, a.subje_type as sub_type
   
FROM tabulation1 a WHERE a.adm_no=?  and   a.wsms=? and  a.examtype=? and  a.sem_code=? and  a.ysession=?  ";



                $query = $this->db->query($sql,array($admn_no,trim($wsms),trim($examtype),trim($sem_code),trim($session_yr)));
		//	echo $this->db->last_query();	die();
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->result();
                    }
                    else
                    {
                        return false;
                    }
            }		
			

	}
?>