<?php
	class Cbcs_stu_details extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}
		
		function get_details_for_transcript($id){
                $sql="SELECT t.*,
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
JOIN (SELECT @running_total:=0) r";
                
                    $query = $this->db->query($sql,array($id));
			//echo $this->db->last_query();	
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

            	$sql="SELECT p.* from
(SELECT t.*, @running_total:=@running_total + 1 AS exam_attempt
FROM (
SELECT a.*, (CASE a.session WHEN 'Monsoon' THEN '1' WHEN 'Winter' THEN '2' WHEN 'Summer' THEN '3' END) AS order_list
FROM final_semwise_marks_foil_freezed a
WHERE a.admn_no=? AND a.course<>'MINOR'
GROUP BY a.session_yr,order_list,a.semester
ORDER BY a.session_yr,order_list,a.semester)t
JOIN (
SELECT @running_total:=0) r
)p
WHERE p.exam_attempt=?";
                
                    $query = $this->db->query($sql,array($admn_no,$exam_attempt));
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
            	
                $sql="SELECT t.session_yr,t.session,
CASE t.exam_type WHEN 'R' THEN 'Regular' WHEN 'O' THEN 'Other' ELSE ' ' end exam_type, 
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
SELECT y.session_yr,y.session,y.dept,y.course,y.branch,y.semester, fd.mis_sub_id, fd.sub_code, fd.grade,y.admn_no,y.exam_type,
fd.cr_hr,fd.cr_pts,y.status,y.tot_cr_hr,y.tot_cr_pts,y.gpa,y.cgpa,y.ctotcrpts,y.ctotcrhr
FROM (
SELECT x.*
FROM (
SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id,a.`status`,a.exam_type,
a.tot_cr_hr,a.tot_cr_pts,a.gpa,a.cgpa,a.ctotcrpts,a.ctotcrhr
FROM final_semwise_marks_foil_freezed AS a
WHERE a.admn_no=? AND a.id=?
ORDER BY a.semester,a.admn_no,a.actual_published_on DESC
LIMIT 10000)x
GROUP BY x.semester) y
JOIN final_semwise_marks_foil_desc_freezed fd ON fd.foil_id=y.id AND fd.admn_no=y.admn_no /*AND fd.grade='F'*/
GROUP BY fd.sub_code
ORDER BY y.session_yr,y.dept,y.course,y.branch,y.semester,fd.sub_code)v
LEFT JOIN cbcs_subject_offered cso ON cso.sub_code=v.sub_code AND v.course=cso.course_id AND v.branch=cso.branch_id AND v.session_yr=cso.session_year AND v.session=cso.session
LEFT JOIN old_subject_offered oso ON oso.sub_code=v.sub_code AND v.course=oso.course_id AND v.branch=oso.branch_id AND v.session_yr=oso.session_year AND v.session=oso.session
LEFT JOIN subjects s ON (CASE WHEN v.mis_sub_id IS NOT NULL THEN s.id WHEN v.mis_sub_id IS NULL AND (cso.sub_code IS NULL AND oso.sub_code IS NULL) THEN s.subject_id END)= (CASE WHEN v.mis_sub_id IS NOT NULL THEN v.mis_sub_id WHEN v.mis_sub_id IS NULL AND (cso.sub_code IS NULL AND oso.sub_code IS NULL) THEN v.sub_code END)
)t
ORDER BY t.sub_code
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
                $sql="SELECT a.id AS admn_no,CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name)AS stu_name,
c.name AS cname,d.name AS bname,b.course_id,b.branch_id
 FROM user_details a
INNER JOIN stu_academic b ON b.admn_no=a.id
INNER JOIN cbcs_courses c ON c.id=b.course_id
INNER JOIN cbcs_branches d ON d.id=b.branch_id
WHERE a.id=?";
                
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
            
			
			

	}
?>