<?php

class Prep_report_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    
    
    function get_regular_prep($syear)
    {
              
        $sql="select a.*,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name,
CASE (b.sex) WHEN 'm' THEN 'Male' WHEN 'f' THEN 'Female' END AS 'sex',b.category,
b.email,f.mobile_no,c.name AS dname,d.name AS cname,e.name AS bname,
b.dept_id,g.course_id,g.branch_id
 from stu_prep_data a
inner join user_details b on a.admn_no=b.id
inner join stu_academic g on g.admn_no=a.admn_no
LEFT JOIN departments c ON c.id=b.dept_id
LEFT JOIN cs_courses d ON d.id=g.course_id
LEFT JOIN cs_branches e ON e.id=g.branch_id
INNER JOIN user_other_details f ON f.id=b.id
where a.session_year=?
order by a.admn_no";

        
        
       $query = $this->db->query($sql,array($syear));

        //    echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
    }

}

?>