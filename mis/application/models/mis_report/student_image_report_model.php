<?php

class Student_image_report_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_regular_student($eyear, $stype, $did, $cid, $bid)
    {
       
        
        $sql="SELECT a.id AS admn_no, 
CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name) AS sname,
 h.domain_name, 
 d.mobile_no, a.photopath, a.dob,c.blood_group, a.dept_id, a.sex,b.course_id, b.branch_id, b.enrollment_year, b.auth_id,
 e.name as dname,f.name as cname,g.name as bname,ROUND(CAST(FORMAT(j.cgpa,5) AS DECIMAL(10,2)),2) AS cgpa
FROM user_details a
left JOIN stu_academic b ON b.admn_no=a.id
left JOIN stu_details c ON c.admn_no=a.id
left JOIN user_other_details d ON d.id=a.id
left join departments e on e.id=a.dept_id
left join cs_courses f on f.id=b.course_id 
left join cs_branches g on g.id=b.branch_id
left join emaildata h on h.admission_no=a.id
LEFT JOIN users i ON i.id=a.id
JOIN 
(SELECT y.admn_no,y.cgpa FROM (SELECT a.admn_no,a.cgpa FROM final_semwise_marks_foil_freezed a ORDER BY a.admn_no,a.semester desc,a.actual_published_on DESC LIMIT 100000000000000000)y GROUP BY y.admn_no) j ON j.admn_no=a.id

WHERE 1=1";
/*if ($did!='none' || $cid!='none' || $bid!='none')
{
	$where=" where ";
}*/

if($eyear!='none'){
$sql.= " and b.enrollment_year='".$eyear."' ";
}
if($stype!='none'){
$sql.= " and b.auth_id='".$stype."' ";
}
if($did!='none'){
$sql.= " and a.dept_id='".$did."' ";
//$sql_dept= " and a.dept='".$did."' ";
}
if($cid!='none'){
$sql.= " and b.course_id='".$cid."' ";
//$sql_course= " and a.course='".$cid."' ";
}
if($bid!='none'){
$sql.= " and b.branch_id='".$bid."' ";
//$sql_brach= " and a.branch='".$cid."' ";
}


$sql.= " and i.status='A' order by dname,cname,bname,admn_no";

            $query = $this->db->query($sql);

            //echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
    }

}

?>