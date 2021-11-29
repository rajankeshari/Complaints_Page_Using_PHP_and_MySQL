<?php

class Regular_registration_report extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_regular_student($syear, $sess, $etype, $did, $cid, $bid, $sem) {

        if ($etype == 'Regular') {
            if($did=='comm'){
                if($sess<>'Summer'){
                $sql="SELECT A.*, IF(p.sub_id<>'', GROUP_CONCAT(p.sub_id SEPARATOR ', '), 'N/A') AS Elective_subject_ID, IF (q.subject_id<>'', GROUP_CONCAT(q.subject_id SEPARATOR ', '),'N/A') AS Elective_Subject_code, IF(q.name<>'', GROUP_CONCAT(q.name SEPARATOR ', '),'N/A') AS Elective_Subject_Name
FROM (
SELECT a.form_id,b.dept_id,a.course_id,a.branch_id,a.admn_no,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name, 
GROUP_CONCAT(c.id SEPARATOR ', ') AS Core_subject_ID, GROUP_CONCAT(d.subject_id SEPARATOR ', ') AS Core_Subject_code, 
GROUP_CONCAT(d.name SEPARATOR ', ') AS Core_Subject_Name, a.semester,a.section,CASE (a.hod_status) WHEN '0' THEN 'Pending' WHEN '1' THEN 'Approved' 
WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS hod_status, 
CASE (a.acad_status) WHEN '0' THEN 'Pending' WHEN '1' THEN 'Approved' 
WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS acad_status, 
IF((a.hod_status='1' AND a.acad_status='1'),'Approved','Not Approved') AS overall_status, e.name AS dname,f.name AS cname,g.name AS bname,a.session_year,a.`session`
FROM reg_regular_form a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN course_structure c ON (c.aggr_id=a.course_aggr_id and c.semester=concat(a.semester,'_',a.section) )
INNER JOIN subjects d ON d.id=c.id
INNER JOIN departments e ON e.id=b.dept_id
INNER JOIN cs_courses f ON f.id=a.course_id
INNER JOIN cs_branches g ON g.id=a.branch_id
WHERE a.session_year=? AND a.`session`=? and a.course_aggr_id like '%comm%'  AND a.semester=? AND c.sequence NOT LIKE '%.%'
GROUP BY a.form_id,a.admn_no)A
LEFT JOIN reg_regular_elective_opted p ON p.form_id=A.form_id
LEFT JOIN subjects q ON q.id=p.sub_id
GROUP BY A.form_id,A.admn_no order by A.admn_no";
                $query = $this->db->query($sql, array($syear, $sess, $sem)); 
                }else{
                    
                    $sql="select a.form_id,c.dept_id,a.course_id,a.branch_id,a.admn_no,concat_ws(' ',c.first_name,c.middle_name,c.last_name)as stu_name,
GROUP_CONCAT(b.sub_id SEPARATOR ', ') AS Core_subject_ID,GROUP_CONCAT(d.subject_id SEPARATOR ', ') AS Core_Subject_code, GROUP_CONCAT(d.name SEPARATOR ', ') AS Core_Subject_Name,GROUP_CONCAT(e.semester SEPARATOR ', ') AS semester,
CASE (a.hod_status) WHEN '0' THEN 'Pending' WHEN '1' THEN 'Approved' WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS hod_status, CASE (a.acad_status) WHEN '0' THEN 'Pending' WHEN '1' THEN 'Approved' WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS acad_status, IF((a.hod_status='1' AND a.acad_status='1'),'Approved','Not Approved') AS overall_status,
ee.name AS dname,f.name AS cname,g.name AS bname,a.session_year,a.`session`,
'N/A' as Elective_subject_ID,
'N/A' as Elective_Subject_code,
'N/A' as Elective_Subject_Name
 from reg_summer_form a
inner join reg_summer_subject b on a.form_id=b.form_id
inner join user_details c on c.id=a.admn_no
inner join subjects d on d.id=b.sub_id
inner join course_structure e on e.id=b.sub_id
INNER JOIN departments ee ON ee.id=c.dept_id
INNER JOIN cs_courses f ON f.id=a.course_id
INNER JOIN cs_branches g ON g.id=a.branch_id
where a.session_year=? and a.`session`=?
GROUP BY a.form_id,a.admn_no
order by c.dept_id,a.course_id,a.branch_id,a.admn_no";
                    $query = $this->db->query($sql, array($syear, $sess)); 
                }
               
            }
            else{
                
                if($cid=='comm' and $bid='comm')
                {
                    $sql="select a.form_id,c.dept_id,a.course_id,a.branch_id,a.admn_no,concat_ws(' ',c.first_name,c.middle_name,c.last_name)as stu_name,
GROUP_CONCAT(b.sub_id SEPARATOR ', ') AS Core_subject_ID,GROUP_CONCAT(d.subject_id SEPARATOR ', ') AS Core_Subject_code, GROUP_CONCAT(d.name SEPARATOR ', ') AS Core_Subject_Name,GROUP_CONCAT(e.semester SEPARATOR ', ') AS semester,
CASE (a.hod_status) WHEN '0' THEN 'Pending' WHEN '1' THEN 'Approved' WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS hod_status, CASE (a.acad_status) WHEN '0' THEN 'Pending' WHEN '1' THEN 'Approved' WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS acad_status, IF((a.hod_status='1' AND a.acad_status='1'),'Approved','Not Approved') AS overall_status,
ee.name AS dname,f.name AS cname,g.name AS bname,a.session_year,a.`session`,
'N/A' as Elective_subject_ID,
'N/A' as Elective_Subject_code,
'N/A' as Elective_Subject_Name
 from reg_summer_form a
inner join reg_summer_subject b on a.form_id=b.form_id
inner join user_details c on c.id=a.admn_no
inner join subjects d on d.id=b.sub_id
inner join course_structure e on e.id=b.sub_id
INNER JOIN departments ee ON ee.id=c.dept_id
INNER JOIN cs_courses f ON f.id=a.course_id
INNER JOIN cs_branches g ON g.id=a.branch_id
where a.session_year=? and a.`session`=?
GROUP BY a.form_id,a.admn_no
order by c.dept_id,a.course_id,a.branch_id,a.admn_no";
                     $query = $this->db->query($sql, array($syear, $sess));
                }
                else{
                    
                
            $sql = "SELECT A.*,
if(p.sub_id<>'',GROUP_CONCAT(p.sub_id SEPARATOR ', '), 'N/A' )AS Elective_subject_ID, 
if (q.subject_id<>'',GROUP_CONCAT(q.subject_id SEPARATOR ', '),'N/A') AS Elective_Subject_code, 
if(q.name<>'',GROUP_CONCAT(q.name SEPARATOR ', '),'N/A') AS Elective_Subject_Name

FROM (
SELECT a.form_id,b.dept_id,a.course_id,a.branch_id,a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name, 
GROUP_CONCAT(c.id SEPARATOR ', ') AS Core_subject_ID, 
GROUP_CONCAT(d.subject_id SEPARATOR ', ') AS Core_Subject_code, 
GROUP_CONCAT(d.name SEPARATOR ', ') AS Core_Subject_Name,
a.semester, CASE (a.hod_status) WHEN '0' THEN 'Pending' WHEN '1' THEN 'Approved' WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS hod_status, CASE (a.acad_status) WHEN '0' THEN 'Pending' WHEN '1' THEN 'Approved' WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS acad_status, IF((a.hod_status='1' AND a.acad_status='1'),'Approved','Not Approved') AS overall_status, e.name AS dname,f.name AS cname,g.name AS bname,a.session_year,a.`session`
FROM reg_regular_form a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN course_structure c ON (c.aggr_id=a.course_aggr_id AND c.semester=a.semester)
INNER JOIN subjects d ON d.id=c.id
INNER JOIN departments e ON e.id=b.dept_id
INNER JOIN cs_courses f ON f.id=a.course_id
INNER JOIN cs_branches g ON g.id=a.branch_id
WHERE a.session_year=? AND a.`session`=?
AND b.dept_id=? AND a.course_id=? AND a.branch_id=?
AND a.semester=? AND c.sequence NOT LIKE '%.%'
GROUP BY a.form_id,a.admn_no)A 
left join reg_regular_elective_opted p on p.form_id=A.form_id
left join subjects q on q.id=p.sub_id
GROUP BY A.form_id,A.admn_no
order by A.admn_no";
         
            $query = $this->db->query($sql, array($syear, $sess,  $did, $cid, $bid, $sem));
            }
            }

        }
        
        if ($etype == 'Other') {
            $sql = "SELECT B.*
FROM(
SELECT b.dept_id,a.course_id,a.branch_id,a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,
 GROUP_CONCAT(c.id ORDER BY c.id ASC SEPARATOR ', ') AS Core_subject_ID, GROUP_CONCAT(d.subject_id ORDER BY d.subject_id SEPARATOR ', ') AS Core_Subject_code, 
 GROUP_CONCAT(d.name ORDER BY d.name SEPARATOR ', ') AS Core_Subject_Name,a.semester, CASE (a.hod_status) WHEN '0' THEN 'Pending' WHEN '1' 
 THEN 'Approved' WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS hod_status, CASE (a.acad_status) WHEN '0' THEN 'Pending' WHEN '1' 
 THEN 'Approved' WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS acad_status, IF((a.hod_status='1' AND a.acad_status='1'),'Approved','Not Approved') AS overall_status, e.name AS dname,f.name AS cname,g.name AS bname, 'Other' AS Exam_type
FROM reg_other_form a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN reg_other_subject rs ON rs.form_id=a.form_id
INNER JOIN course_structure c ON c.id=rs.sub_id
INNER JOIN subjects d ON d.id=c.id
INNER JOIN departments e ON e.id=b.dept_id
INNER JOIN cs_courses f ON f.id=a.course_id
INNER JOIN cs_branches g ON g.id=a.branch_id
WHERE a.session_year=? AND a.`session`=? AND a.`type`='R' AND b.dept_id=? AND a.course_id=? 
AND a.branch_id=? AND a.semester like '%?%'
GROUP BY a.admn_no)B UNION
SELECT C.*
FROM(
SELECT b.dept_id,a.course_id,a.branch_id,a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name, 
GROUP_CONCAT(c.id ORDER BY c.id SEPARATOR ', ') AS Core_subject_ID, GROUP_CONCAT(d.subject_id ORDER BY d.subject_id SEPARATOR ', ') AS Core_Subject_code, 
GROUP_CONCAT(d.name ORDER BY d.name SEPARATOR  ', ') AS Core_Subject_Name,a.semester, CASE (a.hod_status) WHEN '0' THEN 'Pending' WHEN '1' 
THEN 'Approved' WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS hod_status, CASE (a.acad_status) WHEN '0' THEN 'Pending' WHEN '1' 
THEN 'Approved' WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS acad_status, IF((a.hod_status='1' AND a.acad_status='1'),'Approved',
'Not Approved') AS overall_status, e.name AS dname,f.name AS cname,g.name AS bname, 'Other' AS Exam_type
FROM reg_exam_rc_form a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN reg_exam_rc_subject rs ON rs.form_id=a.form_id
INNER JOIN course_structure c ON c.id=rs.sub_id
INNER JOIN subjects d ON d.id=c.id
INNER JOIN departments e ON e.id=b.dept_id
INNER JOIN cs_courses f ON f.id=a.course_id
INNER JOIN cs_branches g ON g.id=a.branch_id
WHERE a.session_year=? AND a.`session`=? AND a.`type`='R' AND b.dept_id=? AND a.course_id=? AND a.branch_id=? AND a.semester like '%?%'
GROUP BY a.admn_no)C

";
        $query = $this->db->query($sql, array($syear, $sess,  $did, $cid, $bid, (int)$sem,$syear, $sess,  $did, $cid, $bid, (int)$sem));

            
        }
        if ($etype == 'Special') {
            $sql = "SELECT B.*
FROM(
SELECT b.dept_id,a.course_id,a.branch_id,a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,
 GROUP_CONCAT(c.id ORDER BY c.id ASC SEPARATOR ', ') AS Core_subject_ID, GROUP_CONCAT(d.subject_id ORDER BY d.subject_id SEPARATOR ', ') AS Core_Subject_code, 
 GROUP_CONCAT(d.name ORDER BY d.name SEPARATOR ', ') AS Core_Subject_Name,a.semester, CASE (a.hod_status) WHEN '0' THEN 'Pending' WHEN '1' 
 THEN 'Approved' WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS hod_status, CASE (a.acad_status) WHEN '0' THEN 'Pending' WHEN '1' 
 THEN 'Approved' WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS acad_status, IF((a.hod_status='1' AND a.acad_status='1'),'Approved','Not Approved') AS overall_status, e.name AS dname,f.name AS cname,g.name AS bname, 'Other' AS Exam_type
FROM reg_other_form a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN reg_other_subject rs ON rs.form_id=a.form_id
INNER JOIN course_structure c ON c.id=rs.sub_id
INNER JOIN subjects d ON d.id=c.id
INNER JOIN departments e ON e.id=b.dept_id
INNER JOIN cs_courses f ON f.id=a.course_id
INNER JOIN cs_branches g ON g.id=a.branch_id
WHERE a.session_year=? AND a.`session`=? AND a.`type`='S' AND b.dept_id=? AND a.course_id=? 
AND a.branch_id=? AND a.semester like '%?%'
GROUP BY a.admn_no)B UNION
SELECT C.*
FROM(
SELECT b.dept_id,a.course_id,a.branch_id,a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name, 
GROUP_CONCAT(c.id ORDER BY c.id SEPARATOR ', ') AS Core_subject_ID, GROUP_CONCAT(d.subject_id ORDER BY d.subject_id SEPARATOR ', ') AS Core_Subject_code, 
GROUP_CONCAT(d.name ORDER BY d.name SEPARATOR  ', ') AS Core_Subject_Name,a.semester, CASE (a.hod_status) WHEN '0' THEN 'Pending' WHEN '1' 
THEN 'Approved' WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS hod_status, CASE (a.acad_status) WHEN '0' THEN 'Pending' WHEN '1' 
THEN 'Approved' WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS acad_status, IF((a.hod_status='1' AND a.acad_status='1'),'Approved',
'Not Approved') AS overall_status, e.name AS dname,f.name AS cname,g.name AS bname, 'Other' AS Exam_type
FROM reg_exam_rc_form a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN reg_exam_rc_subject rs ON rs.form_id=a.form_id
INNER JOIN course_structure c ON c.id=rs.sub_id
INNER JOIN subjects d ON d.id=c.id
INNER JOIN departments e ON e.id=b.dept_id
INNER JOIN cs_courses f ON f.id=a.course_id
INNER JOIN cs_branches g ON g.id=a.branch_id
WHERE a.session_year=? AND a.`session`=? AND a.`type`='S' AND b.dept_id=? AND a.course_id=? AND a.branch_id=? AND a.semester like '%?%'
GROUP BY a.admn_no)C

";
        $query = $this->db->query($sql, array($syear, $sess,  $did, $cid, $bid, (int)$sem,$syear, $sess,  $did, $cid, $bid, (int)$sem));

            
        }
        
            
          // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
        
    }
    function get_regular_jrf($syear, $sess, $did,$etype){
        
        if($etype=='JRF'){$etype='R';}
        if($etype=='JRF_SPL'){$etype='S';}
        if($did=="" || $did=='none')
        {
            $exp="";
            $param=array($syear, $sess, $etype);
        }else
            {
            $exp="AND b.dept_id=?";
            $param=array($syear, $sess, $did,$etype);
            }
        $sql="select x.* from (SELECT b.dept_id,a.course_id,a.branch_id,a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name, GROUP_CONCAT(d.id SEPARATOR ', ') AS Core_subject_ID, GROUP_CONCAT(d.subject_id SEPARATOR ', ') AS Core_Subject_code, GROUP_CONCAT(d.name SEPARATOR ', ') AS Core_Subject_Name, CASE (a.hod_status) WHEN '0' THEN 'Pending' WHEN '1' THEN 'Approved' WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS hod_status, CASE (a.acad_status) WHEN '0' THEN 'Pending' WHEN '1' THEN 'Approved' WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS acad_status, IF((a.hod_status='1' AND a.acad_status='1'), 'Approved','Not Approved') AS overall_status, e.name AS dname,'N/A' AS cname,'N/A' AS bname, 
CASE a.`type`
           WHEN 'R' THEN 'JRF'
           WHEN 'S' THEN 'JRF-Special'
END AS 'Exam_Type',
CASE a.`type`
           WHEN 'R' THEN 'Regular'
           WHEN 'S' THEN 'Special'
END AS 'Type'
FROM reg_exam_rc_form a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN reg_exam_rc_subject rs ON rs.form_id=a.form_id
INNER JOIN subjects d ON d.id=rs.sub_id
INNER JOIN departments e ON e.id=b.dept_id
WHERE a.session_year=? AND a.`session`=? AND a.course_id='jrf' ".$exp."  and a.`type`=?
GROUP BY a.admn_no,a.hod_status,a.acad_status)x order by x.dept_id";

            $query = $this->db->query($sql, $param);

            //echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
        
    }
    function get_regular_prep($syear, $sess){
        $sql="
SELECT a.form_id,b.dept_id,a.course_id,a.branch_id,a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name, 
GROUP_CONCAT(c.id SEPARATOR ', ') AS Core_subject_ID, 
GROUP_CONCAT(d.subject_id SEPARATOR ', ') AS Core_Subject_code, 
GROUP_CONCAT(d.name SEPARATOR ', ') AS Core_Subject_Name,
a.semester, CASE (a.hod_status) WHEN '0' THEN 'Pending' WHEN '1' THEN 'Approved' WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS hod_status, CASE (a.acad_status) WHEN '0' THEN 'Pending' WHEN '1' THEN 'Approved' WHEN '2' THEN 'Rejected' ELSE 'N/A' END AS acad_status, IF((a.hod_status='1' AND a.acad_status='1'),'Approved','Not Approved') AS overall_status, e.name AS dname,f.name AS cname,g.name AS bname,a.session_year,a.`session`
FROM reg_regular_form a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN course_structure c ON (c.aggr_id=a.course_aggr_id AND c.semester=a.semester)
INNER JOIN subjects d ON d.id=c.id
INNER JOIN departments e ON e.id=b.dept_id
INNER JOIN cs_courses f ON f.id=a.course_id
INNER JOIN cs_branches g ON g.id=a.branch_id
WHERE a.session_year=? AND a.`session`=? and a.course_aggr_id like '%prep%'
AND c.sequence NOT LIKE '%.%'
GROUP BY a.form_id,a.admn_no";

            $query = $this->db->query($sql, array($syear, $sess));

          // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
        
    }
    
    function get_student_elective($sy,$sess,$cid,$bid,$sem,$admn_no)
    {
       /* $sql = "Select  group_concat(q.subject_id SEPARATOR ', ') as Elective_Subject_Code,group_concat(q.name SEPARATOR ', ') as Elective_Subject_Name from 
(SELECT a.*,b.semester
FROM optional_offered a
INNER JOIN reg_regular_form b ON a.aggr_id=b.course_aggr_id
WHERE b.admn_no=? AND a.batch=(
SELECT (a.enrollment_year+0+b.duration+0) AS passout
FROM stu_academic a
INNER JOIN cs_courses b ON b.id=a.course_id
INNER JOIN optional_offered c ON c.batch=(a.enrollment_year+0+b.duration+0)
INNER JOIN reg_regular_form d ON d.admn_no=a.admn_no
WHERE a.admn_no=?
GROUP BY a.admn_no
))A
inner join course_structure p on (A.aggr_id=p.aggr_id and A.id=p.id and A.semester=p.semester)
inner join subjects q on p.id=q.id
group by A.semester";*/
        
        $sql="select group_concat(q.subject_id SEPARATOR ', ') as Elective_Subject_Code,group_concat(q.name SEPARATOR ', ') as Elective_Subject_Name from reg_regular_form a
inner join reg_regular_elective_opted b on b.form_id=a.form_id
inner join subjects q on q.id=b.sub_id
where a.session_year=? and a.`session`=?
and a.course_id=? and a.branch_id=?
and a.hod_status='1' and a.acad_status='1' and a.semester=? and a.admn_no=?
group by a.admn_no";

            $query = $this->db->query($sql, array($sy,$sess,$cid,$bid,$sem,$admn_no));

          //  echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
    }

}

?>