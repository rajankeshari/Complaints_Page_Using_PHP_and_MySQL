<?php

class Academic_report_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_student_list($sy,$cat)
    {
          
     /*   $sql = "select 
b.dept_id,
c.course_id,
c.branch_id,c.semester,a.admn_no,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name,b.sex,b.category,a.sub_cast,a.iss_dist,a.iss_state,a.iss_auth from stu_obc_category a
inner join user_details b on a.admn_no=b.id
inner join stu_academic c on c.admn_no=a.admn_no
order by b.dept_id,c.course_id,c.branch_id,c.semester,a.admn_no";*/
        
        $sql="SELECT x.*
FROM((
SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,b.dept_id,a.course_id,a.branch_id,b.sex,b.category, d.sub_cast,d.iss_dist,d.iss_state,d.iss_auth,b.email,c.mobile_no, UPPER(e.auth_id) AS stu_type,
f.fathers_annual_income,f.mothers_annual_income,a.session_year
FROM reg_regular_form a
INNER JOIN user_details b ON a.admn_no=b.id
LEFT JOIN user_other_details c ON c.id=b.id
LEFT JOIN stu_obc_category d ON d.admn_no=a.admn_no
INNER JOIN stu_academic e ON e.admn_no=a.admn_no
inner join stu_other_details f on f.admn_no=a.admn_no
WHERE a.session_year='".$sy."' AND a.hod_status='1' AND a.acad_status='1' AND b.category LIKE '%".$cat."%'
GROUP BY a.admn_no) UNION (
SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,b.dept_id,a.course_id,a.branch_id,b.sex,b.category, d.sub_cast,d.iss_dist,d.iss_state,d.iss_auth,b.email,c.mobile_no, UPPER(e.auth_id) AS stu_type,
f.fathers_annual_income,f.mothers_annual_income,a.session_year
FROM reg_exam_rc_form a
INNER JOIN user_details b ON a.admn_no=b.id
LEFT JOIN user_other_details c ON c.id=b.id
LEFT JOIN stu_obc_category d ON d.admn_no=a.admn_no
INNER JOIN stu_academic e ON e.admn_no=a.admn_no
inner join stu_other_details f on f.admn_no=a.admn_no
WHERE a.session_year='".$sy."' AND a.hod_status='1' AND a.acad_status='1' AND b.category LIKE '%".$cat."%'
GROUP BY a.admn_no))x
ORDER BY x.dept_id,x.course_id,x.branch_id,x.admn_no";

        //echo $sql;die();
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